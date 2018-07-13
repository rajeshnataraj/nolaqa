<?php 
@include("sessioncheck.php");
?>
<script type="text/javascript" language="javascript" >
    	$.getScript("reports/assessmentqa/reports-assessmentqa.js");
</script>
<section data-type='2home' id='reports-assessmentqa'>

  <div class='container'>
	<div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Assessment Report</p>
		<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            		
                  <div class="row rowspacer"></div>
            </div>
        </div>    
     <div class='row'>
	<div class='twelve columns formBase'>
	    <div class='row'>
                    <div class='eleven columns centered insideForm'>

		 <div class='six columns'>
                    	Assessment
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="assessid" id="assessid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assessment</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Assessment">
                                    <ul role="options" style="width:100%">
                                        <?php 
                                          $qry = $ObjDB->QueryObject("SELECT fld_id as testid, fld_test_name as assessname FROM itc_test_master 
									WHERE fld_delstatus='0' AND fld_id IN (SELECT fld_test_id 										FROM itc_test_student_mapping WHERE fld_student_id='".$uid."' AND fld_flag='1') ORDER BY fld_test_name"); 

                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $testid;?>" onclick="$('#viewassessmentreptdiv').show();"><?php echo $assessname; ?></a></li>
                                                <?php
                                            }
                                        }   ?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>   
                    </div>
<div class='row rowspacer'>
</div>
 <input type="hidden" id="hidtestname" name="hidtestname" value="<?php echo "assessmentqanda_"; ?>" />
	<!--View Report Button-->
                <div class='row rowspacer' id="viewassessmentreptdiv" style="display:none;">
                	
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_openassessreport('<?php echo $uid; ?>');" />

                </div>
		    </div>
	    </div>
	</div>
     </div>
  </div>    
</section>     
