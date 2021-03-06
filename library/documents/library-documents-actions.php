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
$docid=$id[0];

$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_sosdocument_master 
		                                      WHERE fld_id='".$docid."' AND fld_delstatus='0'"); // this query to checking whether the unit have lessons are not

$docfilename = $ObjDB->SelectSingleValue("SELECT fld_docfile_name FROM itc_sosdocument_master 
		                                      WHERE fld_id='".$docid."' AND fld_delstatus='0'");

$dfilename=basename($docfilename,".pdf");//get file name without file extension                            
                            $a=  explode('_', $dfilename);
		
?>
<section data-type='#library-documents' id='library-documents-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php echo $id[1];?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' onClick="fn_viewthedocument('<?php echo $docfilename; ?>','<?php echo $dfilename; ?>','<?php echo $a[0]; ?>');">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($sessprofileid == 2) { ?>
            <a class='skip btn mainBtn' href='#library-documents' id='btnlibrary-documents-newdocuments' name='<?php echo $id[0];?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main <?php if($count==0)echo 'dim'; ?>' href='#library-documents' onclick="fn_deletedocument(<?php echo $id[0];?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php }?>
   		</div>
    </div>
</section>
<?php
	@include("footer.php");