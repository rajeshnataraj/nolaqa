<?php 

/*

	Page - library-customcontent-actions
	Description:
	Show the View, Edit, Delete buttons of the selected customcontent from library-customcontent.php
	
	Actions Performed:
	View - Shows the customcontent details
	Edit - Redirects to customcontent detail editing form - library-customcontent-newcustomcontent.php
	Delete - Delete the customcontent from the system
	
	History:


*/

@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
?>
<section data-type='#library-customcontent' id='library-customcontent-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $id[1];?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <?php if($sessprofileid == 5 or $sessprofileid == 7 or $sessprofileid == 8 or $sessprofileid == 9) { ?>
            <a class='skip btn mainBtn' href='#library-customcontent' id='btnlibrary-customcontent-newcustomcontent' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#library-customcontent' onclick="fn_deletecustomcontent(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }?>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");