<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-modules-actions
	Description:
		Show the View, Edit, Delete buttons of the selected Diagmastery from library-modules.php
	
	Actions Performed:
		View - Redirects to Module details form - library-modules-newmodule.php(View only)
		Edit - Redirects to Module details form - library-modules-newmodule.php(Edit only)
		Delete - Delete the module from the system
	
	History:
	

*/
	
$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$id[1]= $ObjDB->SelectSingleValue("SELECT fld_module_name 
                                  FROM itc_module_master 
								  WHERE fld_id='".$id[0]."'");
?>
<section data-type='#library-modules' id='library-modules-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#library-subjects' id='btnlibrary-modules-newmodule' name='<?php echo $id[0].",1";?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-modules' id='btnlibrary-modules-newmodule' name='<?php echo $id[0].",0";?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#library-modules' onclick="fn_deletemodule(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }
				if($sessprofileid!=10 and $sessprofileid!=11 and $sessprofileid!=6)
				{
			?>
            	<a class='skip btn mainBtn' href='#library-modules' id='btnlibrary-modules-grade' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Grade</div>
            	</a>
                
            	<a class='skip btn mainBtn' href='#library-modules' id="btnlibrary-modules-extend" name="<?php echo $id[0].",0";?>" >
                <div class='icon-columns-extend'></div>
                <div class='onBtn'>Extend</div>
            </a>
            <?php
			}
			?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");