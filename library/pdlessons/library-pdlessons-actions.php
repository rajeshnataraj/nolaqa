<?php 
/*
	Page - library-pd-actions
	Description:
		Show the View, Edit, Delete buttons of the selected pd from library-pd.php
	
	Actions Performed:	
		View - Shows the pd in fullscreen
		Edit - Redirects to Lesson detail editing form - library-pd-newpd.php
		Delete - Delete the pd from the system
	
	History:	
		
*/

@include("sessioncheck.php");

//get the pd id, pd name and zipfilename
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id = explode(",",$id); // 0 - pdid, 1 - pd name, 2 - zipfilename

$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_pd_mapping WHERE fld_pd_id='".$id[0]."' AND fld_active='1'");

$filename=basename($id[2],".zip");//get file name without file extension 

    $a=  explode('_', $filename);
if((string)$a[1] == '1483544903'){
    $a[1] = '1465914918';
    //echo $filename;
    $filename = 'ExpeditionOverview2016_1465914918';
    $a=  explode('_', $filename);
}
?>
<section data-type='#library-pd' id='library-pdlessons-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
           
            	<p class="darkTitle"><?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_pd_name,' ',c.fld_version) 
										FROM itc_pd_master AS a LEFT JOIN itc_pd_version_track AS c ON c.fld_pd_id=a.fld_id
										WHERE a.fld_delstatus='0' AND a.fld_id='".$id[0]."' AND c.fld_delstatus='0' AND c.fld_zip_type='1'");?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>    
        <div class='row buttons rowspacer'>
            <a class='skip btn main' href='javascript:void(0);' id='btnlibrary-pdlessons-preview' onClick="showfullscreenlessonpd('<?php echo $filename; ?>',<?php echo $id[0]; ?>,'<?php echo $a[0]; ?>');">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            
			<?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-pdlessons' id='btnlibrary-pdlessons-newpd' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            
            <a class='skip btn main <?php if($count==0){ echo 'dim'; } ?>' href='#library-pdlessons' onclick="fn_deletepd(<?php echo $id[0];?>);">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");