<?php 
@include("sessioncheck.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$qry = $ObjDB->QueryObject("SELECT fld_id, fld_activity_name, fld_created_by 
                           FROM itc_activity_master 
						   WHERE fld_delstatus='0' and fld_id='".$id."'");
$resqry=$qry->fetch_object();
?>
<section data-type='#library-activities' id='library-activities-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="darkTitle"><?php echo $resqry->fld_activity_name;?></p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
		<div class='row buttons rowspacer'>
            <a class='skip btn mainBtn' href='#library-activities' id='btnlibrary-activities-viewactivity' name='<?php echo $id;?>'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <?php if($resqry->fld_created_by == $uid) { ?>
            <a class='skip btn mainBtn' href='#library-activities' id='btnlibrary-activities-newactivity' name='<?php echo $id;?>'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#library-activities' onclick="fn_delete(<?php echo $id;?>)">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
            <?php } if($sessmasterprfid==5 || $sessmasterprfid==7 || $sessmasterprfid==8 || $sessmasterprfid==9) { ?>
            <a class='skip btn mainBtn' href='#library-activities' id='btnlibrary-activities-assign' name='<?php echo $id;?>'>
                <div class="icon-synergy-add-dark"></div>
                <div class='onBtn'>Assign <br/> Activity</div>
            </a>
            <?php }?>
        </div>
    </div>
</section>
<?php
	@include("footer.php");
