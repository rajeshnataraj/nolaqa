<?php
@include("sessioncheck.php");

$method=$_REQUEST;
	
$id = isset($method['id']) ? $method['id'] : '0';
$id=explode(",",$id);

$destinationid=$id[0];
$destinationorder=$id[1];
$expeditionid=$id[2];
$qrydestination = $ObjDB->QueryObject("SELECT fld_id, fld_dest_name, fld_dest_desc, fld_destunique_id 
                                      FROM itc_exp_destination_master WHERE fld_id='".$destinationid."' AND fld_flag='1' AND fld_delstatus='0'");
$rowqrydestination = $qrydestination->fetch_object();

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_exp_media_master
									WHERE fld_exp_dest_task_id='".$destinationid."' AND fld_media_category='2' AND fld_flag='1' AND fld_delstatus='0'");
?>
<section data-type='2home' id='library-expedition-tasks'>
    <div class='span12 dialogStyle1'>
        <div class='row' style="margin-bottom:15px;">
            <div class='twelve columns'>
            	<span style="color: #537F98; font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;">Destination <?php echo $destinationorder;?>:</span>
                <p class="lightTitle"><?php echo $rowqrydestination->fld_dest_name;?></p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
       
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqrydestination->fld_dest_desc!=''){?>
        	<div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;"  >
                        <div class="seven columns">
                        	<?php if($rowqrydestination->fld_dest_desc!=''){?>
                        	<strong>Description:</strong><br />
                                <div class="descexp"><?php echo $rowqrydestination->fld_dest_desc; }?></div>
                        </div>
                        
                        <div class="five columns">
							<?php 
							if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('path?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
									}
									else
									{
										$click = "fn_playaudio(".$rowqrymedias->fld_id.")";
									}
									?>
									<div class="d-list" onclick="<?php echo $click;?>" >
                                    	<?php if($rowqrymedias->fld_media_file_type!=3) {?>
										<div class="d-listimg"></div>
                                        <?php } else { 										
										?>
                                        <div id="audioControl_<?php echo $rowqrymedias->fld_id; ?>" class="d-listimg" >
                                            <audio id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>" preload='none' onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
                                             <?php $url=$_SESSION['mediaurlpath'].$rowqrymedias->fld_media_file_name;?>
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
                <p style="color:  #537F98; font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;">Tasks:</p>
                <?php 
                $qrytasks= $ObjDB->QueryObject("SELECT fld_id, fld_task_name, fn_shortname (CONCAT(fld_task_name), 1) AS shortname, fld_task_desc, fld_task_id 
												FROM itc_exp_task_master
                								WHERE fld_dest_id='".$destinationid."' AND fld_flag='1' AND fld_delstatus='0'");
                if($qrytasks->num_rows>0) {
					$i=1;
					while($rowqrytasks = $qrytasks->fetch_object()){
						?>
						<a class="skip btn mainBtn <?php echo $class;?>" href="#library-expedition-resourses" id="btnlibrary-expedition-resourses" name="<?php echo $destinationid;?>,<?php echo $rowqrytasks->fld_id;?>,<?php echo $i; ?>,<?php echo $expeditionid;?>">
                            <div class="icon-synergy-tests"></div>
                            <div class='onBtn tooltip' original-title='<?php echo $rowqrytasks->fld_task_name;?>'><?php echo $rowqrytasks->shortname;?></div>
						</a>
						<?php
						$i++;
					}
                         ?>
                
                <?php
                } ?>
                
           
               
            </div>
            
        </div>
    </div>
</section>