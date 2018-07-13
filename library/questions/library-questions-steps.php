<?php 
@include("sessioncheck.php");

$quesid = isset($_POST['id']) ? $_POST['id'] : '0';

if($quesid!=0)
{	
	$qry = $ObjDB->QueryObject("SELECT a.fld_ipl_name AS quesmasname, b.fld_step_id AS stepid, b.fld_access AS flag 
								FROM itc_ipl_master AS a 
								LEFT JOIN itc_question_details AS b ON b.fld_lesson_id = a.fld_id 
								WHERE b.fld_id='".$quesid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_access='1'");
	if($qry->num_rows>0){
		$row = $qry->fetch_assoc();
		extract($row);
	}
}
else
{
	$flag=0;
	$stepid=1;
}

?>
<section data-type='2home' id='library-questions-steps'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="darkTitle"><?php if($quesid!=0) { echo $quesmasname;} else { "New"; }?> Question Wizard</p>
                <p class="darkSubTitle">&nbsp;</p>
            </div>
        </div>
        <div class='row buttons rowspacer'>
        	<div style="width:100%;height:100px;">
            	<a style="float:left;" class='mainBtn' href='#library-questions' id='btnlibrary-questions-questiondetails' name='<?php echo $quesid;?>,<?php echo $stepid;?>'>
                    <div class="step-first active-first" id="quesdetails">
                        <div style="width:110px; margin:0 auto;">New Question Details</div>
                    </div>
                </a>
                <a style="float:left" class='mainBtn<?php if($stepid<2 && $flag!=1) {?> dim <?php }?>' href='#library-questions' id='btnlibrary-questions-quscreation'  name='<?php echo $quesid;?>,<?php echo $stepid;?>'>
                    <div class="step-mid" id="newques">
                        <div style="width:110px; margin:0 auto;">Create<br>Question</div>
                	</div>
                </a>
                <a style="float:left;" class='mainBtn<?php if($stepid<3 && $flag!=1) {?> dim <?php }?>' href='#library-questions' id='btnlibrary-questions-review'  name='<?php echo $quesid;?>,<?php echo $stepid;?>'>
                    <div class="step-last" id="review">
                        <div style="width:110px; margin:0 auto;">Review Your<br />New Question</div>
                	</div>
                </a>
            </div>
        </div>
	</div>
    <script language="javascript">
		var val = <?php echo $quesid; ?>+","+<?php echo $stepid; ?>;
		if(<?php echo $stepid; ?>==1)
		{
			setTimeout('removesections("#library-questions-questiondetails");',500);
			setTimeout('showpageswithpostmethod("library-questions-questiondetails","library/questions/library-questions-questiondetails.php","id='+val+'");',1000);
		}
		
		if(<?php echo $stepid; ?>==2)
		{
			setTimeout('removesections("#library-questions-quscreation");',500);
			setTimeout('showpageswithpostmethod("library-questions-quscreation","library/questions/library-questions-quscreation.php","id='+val+'");',1000);
		}
		
		if(<?php echo $stepid; ?>==3)
		{
			setTimeout('removesections("#library-questions-review");',500);
			setTimeout('showpageswithpostmethod("library-questions-review","library/questions/library-questions-review.php","id='+val+'");',1000);
		}
    </script>
</section>
<?php
	@include("footer.php");