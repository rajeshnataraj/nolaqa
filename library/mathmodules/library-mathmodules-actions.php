<?php 
@include("../../sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-mathmodules-actions
	Description:
		Show the View, Edit, Delete buttons of the selected Diagmastery from library-mathmodules.php
	
	Actions Performed:
		View - Redirects to mathmodule details form - library-mathmodules-view.php(View only)
		Edit - Redirects to Module details form - library-mathmodules-newmathmodule.php(Edit only)
		Delete - Delete the mathmodule from the system
	
	History:
	

*/
	
$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$id[1]= $ObjDB->SelectSingleValue("SELECT fld_mathmodule_name 
                                  FROM itc_mathmodule_master 
								  WHERE fld_id='".$id[0]."'");
?>
<section data-type='#library-mathmodules' id='library-mathmodules-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons'>
            <a class='skip btn mainBtn' href='#library-mathmodules' id='btnlibrary-mathmodules-view' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($sessprofileid == 2 || $sessprofileid == 3) { ?>
            <a class='skip btn mainBtn' href='#library-mathmodules' id='btnlibrary-mathmodules-newmathmodule' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#library-mathmodules' onclick="fn_deletemathmodule(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            
            <?php 
			}
			if($sessprofileid!=10 and $sessprofileid!=11 and $sessprofileid!=6)
				{
			?>
            	<a class='skip btn mainBtn' href='#library-mathmodules' id='btnlibrary-mathmodules-grade' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Grade</div>
            	</a>
                
            	 <a class='skip btn mainBtn' href='#library-mathmodules' id="btnlibrary-mathmodules-extend" name="<?php echo $id[0].",0";?>" >
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