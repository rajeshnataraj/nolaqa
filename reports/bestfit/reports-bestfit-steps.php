<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(",",$id);

 $reportid = (isset($id[0]) and $id[0]!= '') ? $id[0]: 0;
 $stepid = $id[1];
if($reportid!=0 and $reportid!='')
{
$editstepid=$ObjDB->SelectSingleValueInt("SELECT fld_step_id FROM itc_bestfit_report_data WHERE fld_id='".$reportid."'");
}
?>
<section data-type='2home' id='reports-bestfit-steps'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Best Fit Report</p>
                <p class="dialogSubTitleLight">Generate your report by following the steps below. To back up, click the step name to which you’d like to return.</p>
            </div>
            
        </div>
        <div class='row buttons'>
            <a style="float:left;" class='mainBtn' href='#reports-bestfit-basic_info' id='btnreports-bestfit-basic_info' name='<?php echo $reportid; ?>'>
                <div class="step-first active-first" id="bbasicstandardinfo">
                    <div style="width:110px; margin:0 auto;">Basic Report<br /> Information</div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php if($reportid!='0' && $editstepid<2) {?> dim <?php } else if($stepid<2 && $flag!=1 && $reportid=='0') {?> dim <?php }?>' href='#reports-bestfit-select_product' id='btnreports-bestfit-select_product' name='<?php echo $reportid; ?>'>
                <div class="step-mid" id="bselectproduct">
                    <div style="width:110px; margin:0 auto;">Select<br /> Products </div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php  if($reportid!='0' && $editstepid<3) {?> dim <?php  }else if($stepid<3 && $flag!=1 && $reportid=='0') {?> dim <?php }?>' href='#reports-bestfit-generate_report' id='btnreports-bestfit-generate_report' name='<?php echo $reportid; ?>'>
                <div class="step-mid" id="bgenerate">
                    <div style="width:110px; margin:0 auto;">Generate<br /> Report</div>
                </div>
            </a>             
        </div>
    </div>
    <script language="javascript">
        var val = <?php echo $reportid; ?>;	
		removesections('#reports-bestfit-steps');
        if(<?php echo $stepid; ?>==1)
        {
            setTimeout('showpageswithpostmethod("reports-bestfit-basic_info","reports/bestfit/reports-bestfit-basic_info.php","id='+val+'");',1000);
            
        }
        if(<?php echo $stepid; ?>==2)
        {
            setTimeout('showpageswithpostmethod("reports-bestfit-select_product","reports/bestfit/reports-bestfit-select_product.php","id='+val+'");',1000);
        }
        if(<?php echo $stepid; ?>==3)
        {
            setTimeout('showpageswithpostmethod("reports-bestfit-generate_report","reports/bestfit/reports-bestfit-generate_report.php","id='+val+'");',1000);
        }
        if(<?php echo $stepid; ?>==4)
        {
            setTimeout('showpageswithpostmethod("reports-bestfit-view_report","reports/bestfit/reports-bestfit-view_report.php","id='+val+'");',1000);
            
        }
    </script>
    <input type="hidden" id="hidselectedproducts" name="hidselectedproducts" />
     
</section>
<?php
	@include("footer.php");