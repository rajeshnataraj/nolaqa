<?php 
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-steps
	Description:
		Show the Test Details, Diagnostic, Mastery1, Mastery2 Questions & Review Test buttons of the selected Diagmastery from library-diagmastery.php
	Actions Performed:
		Test Details - Redirects to Diagmastery Details form - library-diagmastery-testdetails.php
		Diagnostic, Mastery1, Mastery2 Questions - Redirects to Diagmastery questions form - library-diagmastery-diagques.php
		Review Test - Redirects to Diagmastery View form - library-diagmastery-review.php
	History:
*/

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(",",$id);

/* Variable deceleration */
$lessonname='';

if($id[0]!=0)
{	
	$qry = $ObjDB->QueryObject("SELECT a.fld_ipl_name AS diagname, b.fld_access AS flag, b.fld_step_id AS stepid 
							FROM itc_ipl_master AS a 
							LEFT JOIN itc_diag_question_mapping AS b ON b.fld_lesson_id = a.fld_id 
							WHERE b.fld_id='".$id[0]."' AND b.fld_delstatus='0' AND a.fld_delstatus='0' AND a.fld_access='1'");
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
<section data-type='#library-diagmastery' id='library-diagmastery-steps'>
    <div class='container'>
    	<!--Display Diagnostic Mastery Name-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php if($id[0]!=0) { echo $lessonname;} else { "New"; }?> Diag/Mastery Wizard</p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Step Names-->
        <div class='row buttons rowspacer'>
        	<div style="width:100%;height:100px;">
            	<a style="float:left;" class='mainBtn' href='#library-diagmastery' id='btnlibrary-diagmastery-testdetails' name='<?php echo $id[0];?>,1'>
                    <div class="step-first active-first" id="testdetails">
                        <div style="width:110px; margin:0 auto;">
                            New Test<br>Details
                        </div>
                    </div>
                </a>
                <a style="float:left" class='mainBtn<?php if($stepid<2 && $flag!=1) {?> dim <?php }?>' href='#library-diagmastery' id='btnlibrary-diagmastery-diagques' name='<?php echo $id[0];?>,2'>
                    <div class="step-mid" id="diagques">
                        <div style="width:110px; margin:0 auto;">
                        	Diagnostic Questions
                        </div>
                	</div>
                </a>
                <a style="float:left" class='mainBtn<?php if($stepid<3 && $flag!=1) {?> dim <?php }?>' href='#library-diagmastery' id='btnlibrary-diagmastery-diagques' name='<?php echo $id[0];?>,3'>
                    <div class="step-mid" id="mas1ques">
                        <div style="width:110px; margin:0 auto;">
                        	Mastery1 Questions
                        </div>
                	</div>
                </a>
                <a style="float:left" class='mainBtn<?php if($stepid<4 && $flag!=1) {?> dim <?php }?>' href='#library-diagmastery' id='btnlibrary-diagmastery-diagques' name='<?php echo $id[0];?>,4'>
                    <div class="step-mid" id="mas2ques">
                        <div style="width:110px; margin:0 auto;">
                        	Mastery2 Questions
                        </div>
                	</div>
                </a>
                <a style="float:left;" class='mainBtn<?php if($stepid<5 && $flag!=1) {?> dim <?php }?>' href='#library-diagmastery' id='btnlibrary-diagmastery-review' name='<?php echo $id[0];?>,5'>
                    <div class="step-last" id="review">
                        <div style="width:110px; margin:0 auto;">
                        	Review Your<br>New Test
                        </div>
                	</div>
                </a>
            </div>
        </div>
	</div>
    
    <script language="javascript">
		var val = <?php echo $id[0]; ?>+","+<?php echo $stepid; ?>;
		if(<?php echo $stepid?>==1)
		{
			setTimeout('showpageswithpostmethod("library-diagmastery-testdetails","library/diagmastery/library-diagmastery-testdetails.php","id='+val+'");',1000);
		}
		if(<?php echo $stepid?>==2 || <?php echo $stepid?>==3 || <?php echo $stepid?>==4)
		{
			setTimeout('showpageswithpostmethod("library-diagmastery-diagques","library/diagmastery/library-diagmastery-diagques.php","id='+val+'");',1000);
		}
		if(<?php echo $stepid?>==5)
		{
			setTimeout('showpageswithpostmethod("library-diagmastery-review","library/diagmastery/library-diagmastery-review.php","id='+val+'");',1000);
		}
    </script>
</section>
<?php
	@include("footer.php");