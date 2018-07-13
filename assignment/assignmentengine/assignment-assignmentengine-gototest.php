<?php
@include("sessioncheck.php");

$id= isset($method['id']) ? $method['id'] : '';
$id=explode("~",$id);
$testname=$ObjDB->SelectSingleValue("SELECT fld_test_name from itc_test_master where fld_id='".$id[0]."'");
?>
<section data-type='2home' id='assignment-assignmentengine-gototest'>
<script language="javascript">
		$.getScript("assignment/assignmentengine/assignment-assignmentengine-test.js");
	</script>
    <div class='container'>
        <div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle">Assignments and Grades</p>
          </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <div class="row">
                    <div class="twelve columns">
                         <div class="wizardReportData" style="font-size:43px;">You are now ready to begin the <br/> Test for <?php echo $testname; ?></div>
                    </div>
                </div>
                        
                <div class="row rowspacer">
                    <div class="six columns">
                        <p class='btn'>
                            <a onclick="fn_later();" href="#do-later">DO LATER</a>
                        </p>
                    </div>
                    <div class="six columns">
                        <p class='btn right'>
                            <?php
                            if($id[1]=="mis"){ ?> <a onclick="fn_questions(<?php echo $id[0];?>,'<?php echo $id[1];?>',<?php echo $id[2];?>,<?php echo $id[3];?>);" href="#begin">BEGIN</a> <?php }
                            else{ ?> <a onclick="fn_questions(<?php echo $id[0];?>,<?php echo $id[1];?>,0,0);" href="#begin">BEGIN</a> <?php }
                            ?>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="hidtesid" name="hidtesid" value="" />
</section>
<?php
	@include("footer.php");
