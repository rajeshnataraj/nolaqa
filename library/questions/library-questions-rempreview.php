<?php 
@include("sessioncheck.php");

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
$filepathe = $ObjDB->SelectSingleValue("select fld_file_name from itc_question_details where fld_id='".$id."'");
$tmp = explode('.',$filepathe);
if($tmp[1]=='zip'){
	
    $src = _CONTENTURL_."scormlib/previewrem.php?filename=".$filepathe;	
}
else{
	$src = _CONTENTURL_."question/remediations/".$filepathe;
}	

?>
<section data-type='#library-questions' id='library-questions-rempreview'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Preview Remediation File </p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
            <div class="row rowspacer">
                <div class='twelve columns'>
                	<iframe src="<?php echo $src; ?>" width="100%" height="500px" scrolling="no"  style="border:#F00;" id="ifr_question3" > </iframe>
                </div>
            </div>
        </div>   
    </div>     
</section>    
<?php
	@include("footer.php");