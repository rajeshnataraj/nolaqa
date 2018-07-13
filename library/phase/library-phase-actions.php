<?php 

/*

	Page - library-units-actions
	Description:
	Show the View, Edit, Delete buttons of the selected unit from library-units.php
	
	Actions Performed:
	View - Shows the units details
	Edit - Redirects to unit detail editing form - library-unit-newunit.php
	Delete - Delete the unit from the system
	
	History:


*/

@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
$phaseid=$id[0];

$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosphase_master 
		                                      WHERE fld_id='".$phaseid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not
		
?>
<section data-type='#library-phase' id='library-phase-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $id[1];?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#library-phase' id='btnlibrary-phase-viewphase' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($sessprofileid == 2) { ?>
            <a class='skip btn mainBtn' href='#library-phase' id='btnlibrary-phase-newphases' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main <?php if($count==0)echo 'dim'; ?>' href='#library-phase' onclick="fn_deletephase(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }?>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");