<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';

$id=explode(",",$id);
$filename=$_SERVER['DOCUMENT_ROOT']."/reports/correlation/correlationreports/correlation_report_".$id[0].".pdf";
//echo $filename;
?>
<section data-type='2home' id='reports-correlation-actions'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $id[1];?></p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
             <a class='skip btn main <?php if (!file_exists($filename)) { echo 'dim';}?>' href='#correlation-reports' onclick='fn_viewpdf(<?php echo $id[0]; ?>)'>
                <div class='icon-synergy-view'></div>
                <div class='onBtn'>View</div>
            </a>
            <a class='skip btn mainBtn' href='#correlation-reports' id='btnreports-correlation-steps' name='<?php echo $id[0];?>,1'>
                <div class='icon-synergy-edit'></div>
                <div class='onBtn'>Edit</div>
            </a>
            <a class='skip btn main' href='#correlation-reports' onclick="fn_deletereport(<?php echo $id[0];?>);">
                <div class='icon-synergy-trash'></div>
                <div class='onBtn'>Delete</div>
            </a>
           
        </div>
        
    </div>
</section>
<?php
	@include("footer.php");