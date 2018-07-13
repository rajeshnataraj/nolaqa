<?php 
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('test-testassign-testreview')";
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode("_",$id);
	
?>

<section data-type='#test-testassign' id='test-testassign-review'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Question Details</p>
            	<p class="dialogSubTitleLight">Review the selected question below</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <div class="row rowspacer">
                    <div class='twelve columns'>
                        <span class="wizardReportDesc">Question Preview:</span>
                        <div id="loadImg"><img src="<?php echo __HOSTADDR__; ?>img/iframe-loader.gif"/></div>
                        <iframe src="test/testassign/test-testassign-reviewiframe.php?id=<?php echo $id[1]; ?>" width="100%" height="300px" style="border:#F00;" id="ifr_question3" onload="$('#loadImg').remove();autoResize('ifr_question3');"> </iframe>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");