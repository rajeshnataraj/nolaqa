<?php 

/*
        Created by - Mohan M(PHP Programmer)
	Page - library-missionmaterials-actions
	Description:
	Show the View, Edit, Delete buttons of the selected material from library-missionmaterials.php
	
	Actions Performed:
	View - Shows the material details
	Edit - Redirects to material detail editing form - library-missionmaterials-newmaterial.php
	Delete - Delete the material from the system
 * DB: itc_mis_materials_master
	
	History: Updated on 10/7/2015 to check the $chk reg. $grp_fldid is empty or not

*/

@include("sessioncheck.php");
$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$material_id=$id[0];

$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_materials_master 
		                                      WHERE fld_id='".$material_id."' AND fld_delstatus='0'"); // this query to checking whether the materil name is or not

$chksessionprofileid = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_materials_master 
		                                      WHERE fld_id='".$material_id."' AND fld_delstatus='0' AND fld_sessprofile_id = '".$sessmasterprfid."'"); // this query to checking whether the materil name is or not
	
$grp_fldid = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(AB.fld_id) FROM itc_class_indasmission_extcontent_mapping AS CD
                                             INNER JOIN itc_mis_extendmaterials_mapping AS EF ON CD.fld_ext_id=EF.fld_extend_id 
                                             INNER JOIN itc_mis_materials_master as AB on EF.fld_material=AB.fld_id where CD.fld_active='1'");
       
if($grp_fldid == '')  {
    $chk = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_materials_master WHERE fld_id='".$material_id."' AND fld_id IN('')");
} else {
$chk = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_materials_master WHERE fld_id='".$material_id."' AND fld_id IN(".$grp_fldid.")");
}      
	
?>
<section data-type='#library-missionmaterials' id='library-missionmaterials-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $id[1];?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#library-missionmaterials' id='btnlibrary-missionmaterials-viewmaterials' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
           <?php
                if($chksessionprofileid == 1) {
            ?>
            <a class='skip btn mainBtn' href='#library-missionmaterials' id='btnlibrary-missionmaterials-newmaterial' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            
            <a class='skip btn main <?php if($count==0)echo 'dim'; ?>' href='#library-missionmaterials' onclick="fn_deletematerials(<?php echo $id[0];?>,<?php echo $chk;?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
                <?php } ?>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");