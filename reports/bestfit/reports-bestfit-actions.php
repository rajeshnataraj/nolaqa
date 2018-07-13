<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
$filename=$_SERVER['DOCUMENT_ROOT']."/synergy-itc/reports/bestfit/bestreports/bestfit_report_".$id[0].".pdf";
?>
<section data-type='2home' id='reports-bestfit-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
            </div>
        </div>
        <?php
$stepid = $ObjDB->SelectSingleValueInt("SELECT fld_step_id FROM itc_bestfit_report_data WHERE fld_id='".$id[0]."'");
        
?>
        <div class='row buttons rowspacer'>
             
                <a class='skip btn main '<?php if ($stepid==2 || $stepid==3 ) { echo 'dim';}?>' href='#reports-bestfit-generate_report' onclick="fn_view(<?php echo $id[0]; ?>);">
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <a class='skip btn mainBtn' href='#bestfit-reports' id='btnreports-bestfit-steps' name='<?php echo $id[0];?>,1'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#bestfit-reports' onclick="fn_deletereport(<?php echo $id[0];?>);">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
           
        </div>
        
    </div>
</section>
<?php
	@include("footer.php");