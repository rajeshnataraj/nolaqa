<?php 
	@include("sessioncheck.php");
	
	$id = isset($method['id']) ? $method['id'] : '0';
	$qrydetails = $ObjDB->QueryObject("SELECT fld_category_name as name,fld_category_code as code FROM itc_sim_category WHERE fld_id='".$id."' AND  fld_delstatus='0' ");
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
                        <div class="wizardReportDesc">Category Name:</div>
                        	<div class="wizardReportData"><?php echo $name;?></div>
                        <div class="wizardReportDesc">Category Code:</div>
                        	<div class="wizardReportData"><?php echo $code;?></div>
                    </div>
                </div>
                
                <div class="six columns">
               		<div class="wizardReportcols">
                        <div class="wizardReportDesc">Define Field:</div>
                        <div class="wizardReportData"><?php
							 $qryslicdetails = $ObjDB->QueryObject("SELECT fld_define_field AS fields FROM itc_sim_destination WHERE fld_cat_id='".$id."' AND fld_delstatus='0'"); 
							 
							 if($qryslicdetails->num_rows!=0){ 
								while($row=$qryslicdetails->fetch_assoc())
								{
									extract($row);
									?>
										<div class="wizardReportData" ><?php echo $fields;?></div>
									<?php
								}
							 }
							 ?></div>
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