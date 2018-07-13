<?php
/*
 * Created By - Vijayalakshmi PHP Programmer
 * view the material list to each task for Expedition/destination/
 */
@include("sessioncheck.php");
 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$expid = $id[0];
$extid = $id[1];

$select_taskname=$ObjDB->QueryObject("SELECT XY.fld_expedition as expednid, XY.fld_extend_id as extendid, XY.fld_task as taskid,ZA.fld_task_name as taskname FROM itc_exp_extendmaterials_mapping AS XY 
                                         INNER JOIN itc_exp_task_master AS ZA ON XY.fld_task = ZA.fld_id 
                                         INNER JOIN itc_materials_master AS AB ON XY.fld_material = AB.fld_id 
                                         WHERE XY.fld_expedition = '".$expid."' AND XY.fld_extend_id='".$extid."' AND XY.fld_delstatus ='0' AND AB.fld_delstatus='0' GROUP BY XY.fld_task ORDER BY XY.fld_task");
	
?>
<section data-type='library-expedition' id='library-expedition-viewtaskmatlist'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
       <div class='row formBase'> 
            <div class="row rowspacer">
               
                       <?php  if($select_taskname->num_rows > 0)   {
                              while($row=$select_taskname->fetch_assoc())
                              {
                                   extract($row);
                            ?>
                 <table class='table table-striped table-bordered' id="mytable">
                   <thead class='tableHeadText'>
                        <tr>
                            
                            <th style="width:50%" colspan="2" ><?php echo $taskname;?></th>
                             
                        </tr>
                   </thead>
                   <?php
                    $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_material as matid,GH.fld_materials as materialname,GH.fld_thumbimg_url as thumbimg, GH.fld_upload_path as uploadimage FROM itc_exp_extendmaterials_mapping AS AB
                                                                           INNER JOIN itc_materials_master AS GH ON AB.fld_material = GH.fld_id WHERE AB.fld_task='".$taskid."' AND 
                                                                           AB.fld_expedition='".$expednid."' AND AB.fld_extend_id='".$extendid."' AND AB.fld_delstatus='0' AND GH.fld_delstatus='0'");
                        
                        if($select_viewexpmatlist->num_rows > 0)   {
                            
                             while($rowqry=$select_viewexpmatlist->fetch_assoc())
                                     {
                                  extract($rowqry);
                   ?>
                     <tbody> 
                         <tr class="rowd-<?php echo $cnt;?>">
                              <td  style="cursor:default; text-align:center;" class="<?php echo $materialname;?>" id="definematerial_1"><?php echo $materialname;?></td>
                                     <td  style="cursor:default; text-align:center;" class="<?php echo $thumbimg;?>" id="definematerial_1"><img src="<?php if($thumbimg !='') { echo $thumbimg; } else { echo __CNTMATERIALICONPATH__.$uploadimage; } ?> " style="width: 10em;"></td>
                         </tr>
                         <?php 
                                     }
                        }
                        ?>
                          </tbody>
              
                </table>
                <?php
                              }
                       } else {
                           ?>
                <table class='table table-striped table-bordered' id="mytable">
                    <tbody>
                     <tr class="Btn" id="lesson-extend-0">
                         <td colspan="3" class="createnewtd">&nbsp;&nbsp;&nbsp;No Records</td>               
                     </tr>
                    </tbody>
                    
                </table>
                 
                <?php
                       }
                         ?>
                    
            </div>
            <div class='row rowspacer' id="unitbtn">
                       <div class='row'>
                            <div class='four columns btn primary push_two noYes' style="margin-left:35%;">
                                <a onclick="fn_cancel('library-expedition-materiallist')" tabindex="4">Close</a>
                            </div>
                            
                        </div>
                   	</div>
          
        </div>
        
   
    </div>
</section>
<?php
	@include("footer.php");