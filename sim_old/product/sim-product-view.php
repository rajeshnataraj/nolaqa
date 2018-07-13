<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$qrydetails = $ObjDB->QueryObject("SELECT fld_product_name as name,fld_product_key as code FROM itc_sim_product WHERE fld_id='".$id."' AND  fld_delstatus='0' ");
	$row = $qrydetails->fetch_assoc();
	extract($row);
?>
<script language="javascript"> $.getScript("sim/category/sim-category.js"); </script>
<section data-type='#sim-category' id='sim-category-view'>

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
                        <div class="wizardReportDesc">Product Name:</div>
                        	<div class="wizardReportData"><?php echo $name;?></div>
                        <div class="wizardReportDesc">Product Code:</div>
                        	<div class="wizardReportData"><?php echo $code;?></div>
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