<?php 
/*
	Page - library-lessons-actions
	Description:
		Show the View, Edit, Delete, Extend buttons of the selected lesson from library-lessons.php
	
	Actions Performed:	
		View - Shows the lesson in fullscreen
		Edit - Redirects to Lesson detail editing form - library-lesson-newlesson.php
		Delete - Delete the lesson from the system
                Extend - To include the extend content for the lesson  from - library-lessons-extend.php
	
	History:	
		
*/

@include("sessioncheck.php");

//get the lesson id, lesson name and zipfilename
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$id = explode(",",$id); // 0 - lessonid, 1 - lesson name, 2 - zipfilename
$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_cul_mapping WHERE fld_lesson_id='".$id[0]."' AND fld_active='1'");
?>
<section data-type='#library-lessons' id='library-lessons-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',c.fld_version) 
																			FROM itc_ipl_master AS a LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=a.fld_id
																			WHERE a.fld_delstatus='0' AND a.fld_id='".$id[0]."' AND c.fld_delstatus='0' AND c.fld_zip_type='1'");?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn main' href='javascript:void(0);' id='btnlibrary-lessons-preview' onClick="showfullscreenlesson('<?php echo $id[2]; ?>',<?php echo $id[0]; ?>);">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            
			<?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-lessons' id='btnlibrary-lessons-newlesson' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            
            <a class='skip btn main <?php if($count!=0){ echo 'dim'; } ?>' href='#library-lessons' onclick="fn_deletelesson(<?php echo $id[0];?>);">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
             <?php  }?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");