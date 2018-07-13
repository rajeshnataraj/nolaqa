<?php
@include("sessioncheck.php");

$questionpath = isset($_POST['id']) ? $_POST['id'] : '0';
$questionpath = explode(',',$questionpath);

	$tmp = explode('.',$questionpath[0]);
	if($tmp[1]=='zip'){
	
	    $src = _CONTENTURL_."scormlib/previewrem.php?filename=".$questionpath[0];	
	}
	else{
		$src = _CONTENTURL_."question/remediations/".$questionpath[0];
	}
?>
<section data-type='2home' id='library-questions-preview'>
	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Preview Remediation File </p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
            <div class="row rowspacer">
                <div class='twelve columns'>
                	<iframe src="<?php echo $src; ?>" width="100%" height="500px" style="border:#F00;" id="ifr_question3" > </iframe>
                </div>
            </div>
        </div>   
    </div>
</section>
<?php
	@include("footer.php");