<?php 
@include("sessioncheck.php");
/*
	Created By - Muthukumar. D
	Page - library-diagmastery-actions
	Description:
		Show the View, Edit, Delete buttons of the selected Diagmastery from library-diagmastery.php
	
	Actions Performed:
		View - Shows the Diagmastery Details & Questions
		Edit - Redirects to Diagmastery Steps form - library-diagmastery-steps.php
		Delete - Delete the lesson from the system
	History:
*/

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
//$id[0] -> Diagmastery id, $id[1] -> Step id's, $id[2] -> Diagmastery name
?>
<section data-type='#library-diagmastery' id='library-diagmastery-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_ipl_name,' ',c.fld_version) 
																				FROM itc_ipl_master AS a LEFT JOIN `itc_diag_question_mapping` AS b ON a.fld_id=b.fld_lesson_id
																				LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=a.fld_id
																				WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_id='".$id[0]."' AND c.fld_delstatus='0' 
																				AND c.fld_zip_type='1'");?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
    
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#library-diagmastery' id='btnlibrary-diagmastery-review' name='<?php echo $id[0].",1";?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <a class='skip btn mainBtn' href='#library-diagmastery' id='btnlibrary-diagmastery-steps' name='<?php echo $id[0].",".$id[1];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#library-diagmastery' onclick="fn_deletediag(<?php echo $id[0];?>);">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
        </div>
    </div>
    <input type="hidden" id="hidflag" name="hidflag" value="1" />
</section>
<?php
	@include("footer.php");
