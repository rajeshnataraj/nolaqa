<?php
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : '';
$id = explode(",",$id); 
if(isset($id[2]))
$flag =  $id[2];

if($id[3]=='copy')
{
    $copyflag='1';
}
 else 
{
   $copyflag='0';  
}
$reviewpage=$ObjDB->SelectSingleValueInt("SELECT fld_question_type as qtype FROM itc_test_master 
                                            WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");

$countquestion=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_questionassign 
                                            WHERE fld_test_id='".$id[0]."' AND fld_delstatus='0'");
if($countquestion == 0){?>
<script>
	$('#btntest-testassign-testreview').addClass("dim"); 
</script>
<?php }
?>
<section data-type='2home' id='test-testassign-steps'>
	<div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">New Assessment Wizard</p>
            	<p class="dialogSubTitleLight">Create your new assessment by following the steps below. To back up, click the step name to which you would like to return.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
            <div style="width:100%;height:100px;">
            	<a style="float:left;" class='mainBtn'href='#test-testassign' id='btntest-testassign-newtest' name='<?php echo $id[0];?>,<?php echo $id[1];?>'>
                    <div class="step-first active-first" id="newtest">
                        <div style="width:110px; margin:0 auto;">
                             New Assessment<br />Details
                        </div>
                    </div>
                </a>
                <a style="float:left" class='mainBtn<?php if($id[1]<2 && $flag!=1) {?> dim<?php }?>' href='#test-testassign' id='btntest-testassign-testquestion' name='<?php echo $id[0];?>,<?php echo $id[1];?>'>
                    <div class="step-mid" id="testquestion">
                        <div style="width:110px; margin:0 auto;">
                        	Add Assessment Question
                        </div>
                	</div>
                </a>
                <a style="float:left;" class='mainBtn<?php if($id[1]<2 && $flag!=1) {?> dim<?php }?>' href='#test-testassign' <?php if($reviewpage == 1){ ?> id='btntest-testassign-testreview' <?php } else{ ?> id='btntest-testassign-testrandomreview' <?php } ?>  name='<?php echo $id[0];?>,<?php echo $id[1];?>'>
                    <div class="step-last" id="testreview">
                        <div style="width:110px; margin:0 auto;">
                        	Review New Assessment
                        </div>
                	</div>
                </a>
            </div>
        </div>
    </div>
    
	<script language="javascript">
	
		<?php if(isset($id[0]) and isset($id[1])) { ?>
		var val = <?php  echo $id[0] ?>+","+<?php  echo $id[1] ?>+","+<?php  echo $copyflag ?>;
		if(<?php if(isset($id[1])) echo $id[1]?>==1)
		{
			setTimeout('removesections("#test-testassign-newtest");',500);
			setTimeout('showpageswithpostmethod("test-testassign-newtest","test/testassign/test-testassign-newtest.php","id="+val);',1000);
		}
		if(<?php if(isset($id[1])) echo $id[1]?>==2)
		{
			setTimeout('removesections("#test-testassign-testquestion");',500);
			setTimeout('showpageswithpostmethod("test-testassign-testquestion","test/testassign/test-testassign-testquestion.php","id="+val);',1000);
		}
		if(<?php if(isset($id[1])) echo $id[1]?>==3)
		{
			setTimeout('removesections("#test-testassign-testreview");',500);
			setTimeout('showpageswithpostmethod("test-testassign-testreview","test/testassign/test-testassign-testreview.php","id="+val);',1000);
		}
	<?php } ?>	
    </script>
    <input type="hidden" value="<?php echo $flag;?>" id="hidflag" />
</section>
<?php
	@include("footer.php");
