<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");
$expeditionid = isset($method['id']) ? $method['id'] : '';

$qryexpdeitions = $ObjDB->QueryObject("SELECT a.fld_exp_name, a.fld_exp_desc, a.fld_expunique_id, b.fld_file_name, b.fld_version
										FROM itc_exp_master AS a 
										LEFT JOIN itc_exp_version_track AS b ON a.fld_id=b.fld_exp_id 
										WHERE a.fld_id='".$expeditionid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
$rowqryexpdeitions = $qryexpdeitions->fetch_object();

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_exp_media_master
									WHERE fld_exp_dest_task_id='".$expeditionid."' AND fld_media_category='1' AND fld_flag='1' AND fld_delstatus='0'");
																	
$audio = __CNTPATH__."expedition/".$rowqryexpdeitions->fld_file_name."/resources/";
$urlformedia = "expedition/".$rowqryexpdeitions->fld_file_name."/resources/";
$_SESSION['mediaurlpath']=$audio;
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript('js/video.js',function(){
		videojs.options.flash.swf = "video-js.swf";	
	});
</script>

<section data-type='2home' id='library-expedition-preview'>
    <div class='span12 dialogStyle1'>
        <div class='row' style="margin-bottom:15px;">
            <div class='twelve columns'>
            	<span style="color: #FFFFFF; font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;">Expedition:</span>
            	<p class="lightTitle"><?php echo $rowqryexpdeitions->fld_exp_name; ?></p>
            </div>
        </div>
        
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqryexpdeitions->fld_exp_desc!=''){?>
            <div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;" >
                        <div class="seven columns">
                        	<?php if($rowqryexpdeitions->fld_exp_desc!=''){?>
                        	<strong>Description:</strong><br />
                                <div class="descexp"><?php echo strip_tags($rowqryexpdeitions->fld_exp_desc); }?></div>
                        </div>
                        
                        <div class="five columns">
							<?php 
                            if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('library/expedition/library-expedition-view.php?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
									}
									else
									{
										$click = "fn_playaudio(".$rowqrymedias->fld_id.")";
									}
									?>
									<div class="d-list" onclick="<?php echo $click;?>" >
                                        <?php if($rowqrymedias->fld_media_file_type!=3) {?>
										<div class="d-listimg"></div>
                                        <?php } else { ?>
                                        <div id="audioControl_<?php echo $rowqrymedias->fld_id; ?>" class="d-listimg" >
                                            <audio id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>" preload='none' onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
                                                 <?php $url=$audio.$rowqrymedias->fld_media_file_name;?>
                                           <source src='<?php echo "../../receiveaudio.php?url=".$url;?>' type='audio/mp3' />                                               
                                            </audio>
                                        </div>
                                        <?php }?>
                                        <div class="d-listtitle"><?php echo $rowqrymedias->fld_media_name; ?></div>
                                    	<div class="d-listdesc"><?php echo $rowqrymedias->fld_media_desc; ?></div>
									</div>
									<?php 
								} 
							} ?>  
                        </div>
                    </div>
                </div>
            </div>	
            <?php }?>
            <div class='row buttons'>
                <p style="color: #FFFFFF; font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;">Destinations:</p>
                <?php 
				if($sessmasterprfid == 2 || $sessmasterprfid == 3){ //For Pitsco & Content Admin
					$qrydestinations = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fn_shortname (CONCAT(fld_dest_name), 1) AS shortname, 
																fld_dest_desc AS destdesc
															FROM itc_exp_destination_master 
															WHERE fld_exp_id='".$expeditionid."' AND fld_flag='1' AND fld_delstatus='0'");
				}
				else{				
					if($sessmasterprfid==6){ //For District Admin
						$qrydestinations = $ObjDB->QueryObject("SELECT a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 1) AS shortname, 
																	a.fld_dest_desc AS destdesc
																FROM itc_exp_destination_master AS a 
																LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id 
																LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id 
																WHERE a.fld_exp_id='".$expeditionid."' AND b.fld_exp_id='".$expeditionid."' AND a.fld_flag='1' AND b.fld_flag='1' 
																	AND a.fld_delstatus='0' AND c.fld_district_id='".$sendistid."' AND c.fld_school_id='0' AND b.fld_delstatus='0'  
																	AND c.fld_delstatus='0' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' group by destid ORDER BY a.fld_order");
																
					}
					else{ //For Remaining users
						$qrydestinations = $ObjDB->QueryObject("SELECT a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 1) AS shortname, 
																	a.fld_dest_desc AS destdesc
																FROM itc_exp_destination_master AS a 
																LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id 
																LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
																WHERE a.fld_exp_id='".$expeditionid."' AND b.fld_exp_id='".$expeditionid."' AND a.fld_flag='1' AND b.fld_flag='1' 
																	AND a.fld_delstatus='0' AND c.fld_user_id='".$indid."' AND c.fld_school_id='".$schoolid."' AND b.fld_delstatus='0' 
																	AND c.fld_delstatus='0' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."' group by destid ORDER BY a.fld_order");
					}
				}
                
                if($qrydestinations->num_rows>0) {
					$i=1;
					while($rowqrydestinations = $qrydestinations->fetch_object()){
					?>
                        <a class="skip btn mainBtn" href="#library-expedition-tasks" id="btnlibrary-expedition-tasks" name="<?php echo $rowqrydestinations->destid; ?>,<?php echo $i; ?>,<?php echo $expeditionid;?>">
                            <div class="icon-Destination"></div>
                            <div class='onBtn tooltip' original-title='<?php echo $rowqrydestinations->destname; ?>'><?php echo $rowqrydestinations->shortname; ?></div>
                        </a>
                        <?php
                        $i++;
                	}
				} ?>
            </div>
        </div>
    </div>
    <input type="hidden" id="hidexpeditionid" value="<?php echo $expeditionid;?>" />
    <input type="hidden" id="mediaurl" value="<?php echo $urlformedia;?>" />
</section>