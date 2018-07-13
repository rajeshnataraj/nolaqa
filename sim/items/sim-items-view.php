<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$definefieldname = $ObjDB->SelectSingleValue("SELECT fld_define_field FROM itc_sim_items WHERE fld_id='".$id."' ANd fld_delstatus='0'");

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
                        <div class="wizardReportDesc">Field Name:</div>
                        	<div class="wizardReportData"><?php echo $definefieldname;?></div>
                        
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