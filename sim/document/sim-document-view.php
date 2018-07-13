<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$document = $ObjDB->QueryObject("SELECT fld_document_name as documentname,fld_upload_filename as uploadname,fld_global_status as globaldoc FROM itc_sim_document WHERE fld_id='".$id."' ANd fld_delstatus='0'");
	$row = $document->fetch_assoc();
	extract($row);

?>
<script language="javascript"> $.getScript("sim/items/sim-items-items.js"); </script>
<section data-type='#sim-items' id='sim-items-view'>

	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">View details</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase'>
			<div class='eleven columns centered insideForm'>
				<div class="row">
					<div class="six columns">
						<div class="wizardReportcols">
							<div class="wizardReportDesc">Document Name:</div>
								<div class="wizardReportData"><?php echo $documentname;?></div>
						</div>
					</div>
					<div class="six columns">
						<div class="wizardReportcols">
							<div class="wizardReportDesc">Upload File Name:</div>
								<div class="wizardReportData"><?php echo $uploadname;?></div>
						</div>
					</div>
				</div>
			</div>
        </div>
               
        <div id="shllicdetails" style="display: none;" >
        </div>
    </div>
</section>
<?php
	@include("footer.php");