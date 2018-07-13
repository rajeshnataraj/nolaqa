<?php
	@include("sessioncheck.php");
 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$expid = $id[0];
$destnid = $id[1];
$taskid = $id[2];
$schid = $id[3];


$select_taskname=$ObjDB->SelectSingleValue("SELECT fld_task_name as taskname FROM itc_exp_task_master WHERE fld_id='".$taskid."' AND fld_delstatus = '0'");

	
?>
<section data-type='assignment-expedition-materiallist' id='assignment-expedition-viewmaterialfortask'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
       <div class='row formBase'> 
            <div class="row rowspacer">
               
                 <table class='table table-striped table-bordered' id="mytable">
                   <thead class='tableHeadText'>
                        <tr>
                            
                            <th style="width:50%" colspan="2" ><?php echo $select_taskname;?></th>
                             
                        </tr>
                   </thead>
                   <?php
                                   
                   $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_material as matid,LK.fld_materials as materialname,LK.fld_thumbimg_url as thumbimg, LK.fld_upload_path as uploadimage FROM itc_exp_extendmaterials_mapping as AB
                                                                INNER JOIN itc_class_indasexpedition_extcontent_mapping as GH ON AB.fld_extend_id = GH.fld_ext_id 
                                                                INNER JOIN itc_materials_master as LK on AB.fld_material=LK.fld_id
                                                                WHERE GH.fld_exp_id='".$expid."' AND GH.fld_schedule_id='".$schid."' AND GH.fld_active='1' AND 
                                                                AB.fld_expedition='".$expid."' AND AB.fld_destination='$destnid' AND AB.fld_task='".$taskid."' AND AB.fld_delstatus='0' AND AB.fld_delstatus='0'");
                        
                        
                        if($select_viewexpmatlist->num_rows > 0)   {
                            
                             while($rowqry=$select_viewexpmatlist->fetch_assoc())
                                     {
                                  extract($rowqry);
                   ?>
                     <tbody> 
                         <tr class="rowd-<?php echo $cnt;?>">
                              <td  style="cursor:default; text-align:center;" class="<?php echo $materialname;?>" id="definematerial_1"><?php echo $materialname;?></td>
                              <td  style="cursor:default; text-align:center;" class="<?php echo $thumbimg;?> mainBtn" id="btnassignment-expedition-viewmaterial" name="<?php echo $thumbimg; ?>,'1',<?php echo $uploadimage; ?>"><img src="<?php if($thumbimg !='') { echo $thumbimg; } else { echo __CNTMATERIALICONPATH__.$uploadimage; } ?>" style="width: 10em;cursor: pointer;"></td>
                         </tr>
                         <?php 
                                     }
                        }
                        else  {
                            ?>
                          <tbody> 
                               <tr class="Btn" id="lesson-extend-0">
                                     <td colspan="3" class="createnewtd">&nbsp;&nbsp;&nbsp;No Records</td>               
                                </tr>
                         <?php
                        }
                        ?>
                          </tbody>
              
                </table>
              
            </div>
            <div class='row rowspacer' id="unitbtn">
                       <div class='row'>
                            <div class='four columns btn primary push_two noYes' style="margin-left:35%;">
                                <a onclick="fn_cancel('assignment-expedition-resourses')" tabindex="4">Close</a>
                            </div>
                            
                        </div>
                   	</div>
          
        </div>
        
   
    </div>
</section>
<?php
	@include("footer.php");