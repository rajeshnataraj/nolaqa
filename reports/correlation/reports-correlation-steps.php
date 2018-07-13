<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;
$id = explode(",",$id);

 $reportid = (isset($id[0]) and $id[0]!= '') ? $id[0]: 0;
$stepid = $id[1];
if($reportid!=0 and $reportid!='')
{
$editstepid=$ObjDB->SelectSingleValueInt("SELECT fld_step_id FROM itc_correlation_report_data WHERE fld_id='".$reportid."'");
}
?>
<section data-type='2home' id='reports-correlation-steps'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Correlation Report</p>
                <p class="dialogSubTitleLight">Generate your report by following the steps below. To back up, click the step name to which you’d like to return.</p>
            </div>
        </div>
        <div class='row buttons'>
            <a style="float:left;" class='mainBtn' href='#reports-correlation-basic_info' id='btnreports-correlation-basic_info' name='<?php echo $reportid; ?>'>
                <div class="step-first active-first" id="cbasicinfo">
                    <div style="width:110px; margin:0 auto;">Basic Report<br /> Information</div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php if($reportid!='0' && $editstepid<2) {?> dim <?php } else if($stepid<2 && $flag!=1 && $reportid=='0') {?> dim <?php }?>' href='#reports-correlation-select_standard' id='btnreports-correlation-select_standard' name='<?php echo $reportid; ?>'>
                <div class="step-mid" id="cselectstandard">
                    <div style="width:110px; margin:0 auto;">Select<br /> Standard</div>
                </div>
            </a>
            <a style="float:left" class='mainBtn<?php  if($reportid!='0' && $editstepid<3) {?> dim <?php  }else if($stepid<3 && $flag!=1 && $reportid=='0') {?> dim <?php }?>' href='#reports-correlation-select_product' id='btnreports-correlation-select_product' name='<?php echo $reportid; ?>'>
                <div class="step-mid" id="cselectproduct">
                    <div style="width:110px; margin:0 auto;">Select<br /> Products</div>
                </div>
            </a>
            <a style="float:left;" class='mainBtn<?php if($reportid!='0' && $editstepid<=3) {?> dim <?php }else if($stepid<4 && $flag!=1 && $reportid=='0') {?> dim <?php }?>' href='#reports-correlation-view_report' id='btnreports-correlation-view_report' name='<?php echo $reportid; ?>'>
                <div class="step-last" id="cviewreport">
                    <div style="width:110px; margin:0 auto;">View Your<br /> Report</div>
                </div>
            </a>   
        </div>
    </div>
    <script language="javascript">
        var val = <?php echo $reportid; ?>;
		
		removesections('#reports-correlation-steps');
        if(<?php echo $stepid; ?>==1)
        {
            setTimeout('showpageswithpostmethod("reports-correlation-basic_info","reports/correlation/reports-correlation-basic_info.php","id='+val+'");',1000);		
        }
        if(<?php echo $stepid; ?>==2)
        {
            setTimeout('showpageswithpostmethod("reports-correlation-select_standard","reports/correlation/reports-correlation-select_standard.php","id='+val+'");',1000);
        }
        if(<?php echo $stepid; ?>==3)
        {
            setTimeout('showpageswithpostmethod("reports-correlation-select_product","reports/correlation/reports-correlation-select_product.php","id='+val+'"',1000);
        }
        if(<?php echo $stepid; ?>==4)
        {
            setTimeout('showpageswithpostmethod("reports-correlation-view_report","reports/correlation/reports-correlation-view_report.php","id='+val+'");',1000);
        }
    </script>
    <input type="hidden" id="hidselectedproducts" name="hidselectedproducts" />
    <input type="hidden" id="hidselectedtagproducts" name="hidselectedtagproducts" value="" />
    <input type="hidden" id="hidselecteddestinations" name="hidselecteddestinations" />
    <input type="hidden" id="hidselectedtasks" name="hidselectedtasks" />
    <input type="hidden" id="hidselectedresources" name="hidselectedresources" />
     
</section>
<?php
	@include("footer.php");