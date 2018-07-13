<?php
	@include("sessioncheck.php");
$date=date("Y-m-d H:i:s"); 
$id = isset($method['id']) ? $method['id'] : 0;
$id=explode(",",$id);
$expid = $id[0];
$destnid = $id[1];
$taskid = $id[2];


$select_taskname=$ObjDB->SelectSingleValue("SELECT fld_task_name as taskname FROM itc_exp_task_master WHERE fld_id='".$taskid."' AND fld_delstatus = '0'");

	
?>
<script type="text/javascript" language="javascript">
$.getScript('library/expedition/library-extend.js');
</script>
<section data-type='library-expedition-materiallist' id='library-expedition-viewmaterialfortask'>
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
                            
                            <th style="width:50%" colspan="<?php if($sessmasterprfid ==2 || $sessmasterprfid == 3 || $sessmasterprfid == 9) { echo 3; } else {echo 2;}?>" ><?php echo $select_taskname;?></th>
                             
                        </tr>
                   </thead>
                   <?php
                   if($sessmasterprfid == '2' || $sessmasterprfid == 3 )  {  //For Pitsco & Content Admin
                  
                   $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_material as matid,GH.fld_materials as materialname,GH.fld_thumbimg_url as thumbimg,GH.fld_catalog_url as catalogurl,fld_upload_path as uploadimage FROM itc_exp_extendmaterials_mapping AS AB
                                                                           INNER JOIN itc_materials_master AS GH ON AB.fld_material = GH.fld_id WHERE 
                                                                           AB.fld_expedition='".$expid."' AND AB.fld_destination='".$destnid."' AND AB.fld_task='".$taskid."' AND AB.fld_created_by='".$uid."' AND AB.fld_delstatus='0' AND GH.fld_delstatus='0' GROUP BY AB.fld_material");
                   }
                   else if($sessmasterprfid==6){ //For District Admin)
                        
                       
                       $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_id as matid,AB.fld_materials as materialname,AB.fld_thumbimg_url as thumbimg,AB.fld_catalog_url as catalogurl,fld_upload_path as uploadimage FROM itc_license_extcontent_mapping AS CD
                                                                           INNER JOIN itc_exp_extendmaterials_mapping AS EF ON CD.fld_ext_id=EF.fld_extend_id 
                                                                           INNER JOIN itc_materials_master as AB on EF.fld_material=AB.fld_id
                                                                        INNER JOIN itc_license_track AS GH ON CD.fld_license_id = GH.fld_license_id
                                                                            WHERE 
                                                                           EF.fld_expedition='".$expid."' AND EF.fld_destination='".$destnid."' AND EF.fld_task='".$taskid."' AND EF.fld_delstatus='0'
                                                                        AND  CD.fld_type = '15' AND CD.fld_module_id='".$expid."' AND CD.fld_active='1' AND EF.fld_created_by='2'
                                                                        AND GH.fld_district_id = '".$sendistid."' AND GH.fld_school_id = '0' AND GH.fld_delstatus = '0'
                                                                        AND GH.fld_start_date<='".$date."' AND GH.fld_end_date>='".$date."' AND AB.fld_delstatus='0' GROUP BY AB.fld_id");
                       
                   }
                   else {
                   
                        $select_viewexpmatlist=$ObjDB->QueryObject("SELECT AB.fld_id as matid,AB.fld_materials as materialname,AB.fld_thumbimg_url as thumbimg,AB.fld_catalog_url as catalogurl,fld_upload_path as uploadimage FROM itc_license_extcontent_mapping AS CD
                                                                                              INNER JOIN itc_exp_extendmaterials_mapping AS EF ON CD.fld_ext_id=EF.fld_extend_id 
                                                                                              INNER JOIN itc_materials_master as AB on EF.fld_material=AB.fld_id
                                                                                              INNER JOIN itc_license_track AS GH ON CD.fld_license_id = GH.fld_license_id
                                                                                               WHERE 
                                                                                              EF.fld_expedition='".$expid."' AND EF.fld_destination='".$destnid."' AND EF.fld_task='".$taskid."' AND EF.fld_delstatus='0'
                                                                                              AND  CD.fld_type = '15' AND CD.fld_module_id='".$expid."' AND CD.fld_active='1' AND EF.fld_created_by='2' AND
                                                                                              GH.fld_district_id = '".$sendistid."' AND GH.fld_school_id = '".$schoolid."' AND GH.fld_delstatus = '0' AND GH.fld_user_id='".$indid."'
                                                                                              AND GH.fld_start_date<='".$date."' AND GH.fld_end_date>='".$date."' AND AB.fld_delstatus='0' GROUP BY AB.fld_id
                                                                                UNION ALL
                                                                                     SELECT AB.fld_material as matid,GH.fld_materials as materialname,GH.fld_thumbimg_url as thumbimg,GH.fld_catalog_url as catalogurl,fld_upload_path as uploadimage FROM itc_exp_extendmaterials_mapping AS AB
                                                                           INNER JOIN itc_materials_master AS GH ON AB.fld_material = GH.fld_id
                                                                           INNER JOIN itc_exp_extendmaterials_master AS CD ON AB.fld_extend_id=CD.fld_id 
                                                                            WHERE 
                                                                                              AB.fld_expedition='".$expid."' AND AB.fld_destination='".$destnid."' AND AB.fld_task='".$taskid."' AND AB.fld_created_by='".$uid."' AND CD.fld_school_id='".$schoolid."' AND AB.fld_delstatus='0'
                                                                                              AND GH.fld_delstatus='0' ORDER BY matid");
                   }
                        
                        if($select_viewexpmatlist->num_rows > 0)   {
                            
                             while($rowqry=$select_viewexpmatlist->fetch_assoc())
                                     {
                                  extract($rowqry);
                   ?>
                     <tbody> 
                         <tr class="rowd-<?php echo $cnt;?>">
                              <td  style="cursor:default; text-align:center;" class="<?php echo $materialname;?>" id="definematerial_1"><?php echo $materialname;?></td>
                              <td  style="cursor:default; text-align:center;" class="<?php echo $thumbimg;?>" id="definematerial_1"><img src="<?php if($thumbimg != '') { echo $thumbimg; } else { echo __CNTMATERIALICONPATH__.$uploadimage; } ?>" style="width: 10em;"></td>
                           
                              <td  style="cursor:default; text-align:center;" class="<?php echo $catalogurl;?>" id="definematerial_1">
                                  <?php if($catalogurl != '') { ?>
                                  <a href="<?php echo $catalogurl; ?>" target="_blank">view catalog url</a>
                                  <?php }  else { ?>
                                  <a>___</a>
                                  <?php } ?>
                              </td>                            
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
                               <a onclick="fn_cancel('library-expedition-resourses')" tabindex="4">Close</a> 
                            </div>
                       </div>
                   	</div>
          
        </div>
        
   
    </div>
</section>
<?php
	@include("footer.php");