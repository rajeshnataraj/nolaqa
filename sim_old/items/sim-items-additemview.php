<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$additemname = $ObjDB->QueryObject("SELECT fld_item_name as itemname,fld_message_details as message,fld_upload_filename as uploadname FROM itc_sim_desitem WHERE fld_id='".$id."' AND  fld_delstatus='0' ");
	$row = $additemname->fetch_assoc();
	extract($row);

?>
<script language="javascript"> $.getScript("sim/items/sim-items-items.js"); </script>
<section data-type='#sim-items-additemview' id='sim-items-additemview'>

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
                        <div class="wizardReportDesc">Field Name:</div>
                        	<div class="wizardReportData"><?php echo $itemname;?></div>
						<div class="wizardReportDesc">Message:</div>
							<div class="wizardReportData"><?php echo $message;?></div>
                        
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