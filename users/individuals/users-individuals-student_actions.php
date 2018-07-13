<?php 
@include("../../sessioncheck.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$id=explode(",",$id);

?>
<section data-type='#users-individuals' id='users-individuals-student_actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1]." Actions";?></p>
				<p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#users-individuals-student_newstudent' id='btnusers-individuals-student_newstudent' name='<?php echo $id[0].",".$id[2].",".$id[3].",".$id[4];?>'>
                <div class="icon-synergy-edit"></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#users_profile' onclick="fn_deletstudent(<?php echo $id[0];?>)">
                <div class="icon-synergy-trash"></div>
                <div class='onBtn'>Delete</div>
            </a>
        </div>
    </div>
</section>
