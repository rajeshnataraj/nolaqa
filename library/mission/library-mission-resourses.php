<?php

@include("sessioncheck.php");
$method=$_REQUEST;
$id = isset($method['id']) ? $method['id'] : '0';
$id=explode(",",$id);

$destinationid=$id[0];
$taskid=$id[1];
$taskorder=$id[2];
$missionid=$id[3];

$qrytask= $ObjDB->QueryObject("SELECT fld_id, fld_task_name, fld_task_desc, fld_task_id FROM itc_exp_task_master WHERE fld_id='".$taskid."' AND fld_flag='1' AND fld_delstatus='0'");
$rowqrytask = $qrytask->fetch_object();

$qrymedias = $ObjDB->QueryObject("SELECT fld_media_name, fld_media_file_type, fld_media_file_name, fld_media_desc, fld_id 
									FROM itc_mis_media_master
									WHERE fld_mis_dest_task_id='".$taskid."' AND fld_media_category='3' AND fld_flag='1' AND fld_delstatus='0'");
?>
<section data-type='2home' id='library-mission-resourses'>
    <div class='span12 dialogStyle1'>
        <div class='row'>
            <div class='twelve columns'>
            	<span style="color: <?php if($sessmasterprfid==10) { ?> #FFFFFF;<?php } else {?> #537F98;<?php }?> font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold;"></span>
                <p class="lightTitle"><?php echo $rowqrytask->fld_task_name;?> </p>
                <p class="lightSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row-fluid'>
        	<?php if($qrymedias->num_rows>0 || $rowqrytask->fld_task_desc!=''){?>
            <div class="container">
                <div class="row formBase" style="margin-bottom:15px;">
                    <div class="eleven columns centered" style="padding:20px 0px 20px 0px;"  >
                        <div class="seven columns">
                        	<?php if($rowqrytask->fld_task_desc!=''){?>
                        	<strong>Description:</strong><br />
                                <div class="descexp"><?php echo $rowqrytask->fld_task_desc; }?></div>
                        </div>
                        
                        <div class="five columns">
							<?php 
							if($qrymedias->num_rows>0) {
								while($rowqrymedias = $qrymedias->fetch_object()){
									if($rowqrymedias->fld_media_file_type!=3) {
										$click = "loadiframes('library/mission/library-mission-view.php?type=".$rowqrymedias->fld_media_file_type."&filename=".$rowqrymedias->fld_media_file_name."','preview');";
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
                                            <audio preload="auto" crossorigin="anonymous" id="yourAudio_<?php echo $rowqrymedias->fld_id; ?>" onended="fn_end(<?php echo $rowqrymedias->fld_id; ?>);">
                                           	<?php $url=$_SESSION['mediaurlpath'].$rowqrymedias->fld_media_file_name; //"http://itctest.pitsco.com/receiveaudio.php?url=".?>
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
                <p style="color: <?php if($sessmasterprfid==10) { ?> #FFFFFF;<?php } else {?> #537F98;<?php }?> font-family: 'source_sans_proregular'; font-size: 18px; font-weight: bold; margin-bottom:7px;"></p>
                <?php 
                $qryresources= $ObjDB->QueryObject("SELECT fld_id, fld_res_name, fn_shortname (CONCAT(fld_res_name), 1) AS shortname, fld_res_file_name, fld_res_file_type, fld_res_status, fld_typeof_res FROM itc_mis_resource_master WHERE fld_task_id='".$taskid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order");
                if($qryresources->num_rows>0) {
					$i=1;
					while($rowqqryresources = $qryresources->fetch_object()){
						if($rowqqryresources->fld_res_file_type!=6){
							$resclick = "loadiframes('path?destinationid=".$destinationid."&taskid=".$taskid."&resourceid=".$rowqqryresources->fld_id."&type=".$rowqqryresources->fld_res_file_type."&filename=".$rowqqryresources->fld_res_file_name."','preview');";             
							$link='';
							$target='';  
						}
						else
						{
							$resclick="return false";
							$link='href='.$rowqqryresources->fld_res_file_name;
							$target='target="new"';  
						
						}
						if($rowqqryresources->fld_typeof_res==1)
							$classname = " icon-Information";
                                                
						else if($rowqqryresources->fld_typeof_res==2)
							$classname = " icon-Activity";
						?>
						<a <?php echo $link."  ".$target; ?>  class="skip btn main <?php if($rowqqryresources->fld_res_file_name=='') { ?>dim<?php }?>" onclick="<?php echo $resclick;?>" name="<?php echo $id[0].",".$id[1] ?>,1">
							<div class="<?php echo $classname;?>"></div>
							<div class='onBtn tooltip' original-title="<?php echo $rowqqryresources->fld_res_name;?>"><?php echo $rowqqryresources->shortname;?></div>
						</a>
						<?php
						$i++;
					}
                                        
                          $select_viewexpmatlist=$ObjDB->QueryObject("");
                        
                        
                        if($select_viewexpmatlist->num_rows > 0)   {
                                        
                                        
                                        
                                        ?>
                 <a class="skip btn mainBtn" href="#library-mission-viewmaterialfortask" id="btnlibrary-mission-viewmaterialfortask" name="<?php echo $missionid;?>,<?php echo $destinationid;?>,<?php echo $taskid; ?>">
                            <div class="icon-synergy-tests"></div>
                            <div class='onBtn tooltip' original-title='View Materials'>View Materials</div>
                </a>
                        <?php  }  } ?>
            </div>
        </div>
    </div>
</section>