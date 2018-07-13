<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
/*

	Page - library-courses-actions
	Description:
	Show the View, Edit, Delete buttons of the selected course from library-courses.php
	
	Actions Performed:
	View - Shows the course details
	Edit - Redirects to course detail editing form - library-courses-newcourses.php
	Delete - Delete the course from the system
	
	History:


*/

@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
$courseid=$id[0];
$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_pd_master 
		                                      WHERE fld_course_id='".$courseid."' AND fld_delstatus='0'"); // this query to checking whether the course have lessons are not
		
?>
<section data-type='#library-courses' id='library-courses-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $id[1];?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#library-courses' id='btnlibrary-courses-viewcourses' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-courses' id='btnlibrary-courses-newcourses' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main <?php if($count!=0)echo 'dim'; ?>' href='#library-courses' onclick="fn_deletecourses(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }?>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");