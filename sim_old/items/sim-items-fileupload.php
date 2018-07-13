<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$ids=explode(",",$id);

$catid = $ids[0];
$proids = $ids[2];
$itemid = $ids[3];
$desitemid = $ids[4];

$fileupload=$ObjDB->SelectSingleValue("SELECT fld_upload_filename AS filesname FROM itc_sim_desitem WHERE fld_cat_id='".$catid."' AND fld_des_id='".$itemid."' AND fld_id='".$desitemid."' AND fld_delstatus='0'");

?>
<script>
	$.getScript('sim/items/sim-items-items.js');
</script>
<section data-type='#sim-items-fileupload' id='sim-items-fileupload'>
  <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">File Upload lists</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
            <div class="row">
                    <div style="overflow-y: auto; word-wrap: break-word; height: 120px;">
						<div class="wizardReportDesc">Files upload detaile list show pages:</div>
							<div class="row">
							<div style="float: left;height: 25px;padding-bottom: 3px;padding-top: 4px;font-size:18px;"><?php echo $fileupload;?></div>
								<div>
								<a target=_new onClick="fn_download('<?php echo $fileupload;?>')" class='darkButton' style="float: right;height: 25px;margin-right: 213px;padding-bottom: 3px;padding-top: 4px;width: 125px;">
									   Download
									</a>
								</div>
							</div>
                    </div>
            </div>
           
            </div>
        </div>
         
    </div>
</section>
<?php
	@include("footer.php");