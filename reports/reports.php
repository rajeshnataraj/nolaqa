<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports
	Description:
		Show the Classroom, Grade & Question Reports buttons.

	Actions Performed:
		Classroom - Redirect to classroom form - reports-classroom.php
		Grades - Redirect to classroom form - reports-gradereports.php
		Questions - Redirect to classroom form - reports-quesanswers.php
	
	History:
               Mohan Kumar.V
               1.Bestfit Report
 *             2.Test Results


*/
?>
<section data-type='2home' id='reports'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Reports</p>
            	<!--<p class="dialogSubTitleLight">Choose a reporting category to view its individual reports.</p>-->
                <p class="dialogSubTitleLight">Select a category to view its individual reports.</p>
            </div>
        </div>
        
        <div class='row buttons rowspacer'>
			<?php if($sessmasterprfid!=6 and $sessmasterprfid!=10) { ?>
            <?php } if($sessmasterprfid!=10 and $sessmasterprfid!=2 and $sessmasterprfid!=3 ) {
                ?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-classroom' >
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Classroom<br />Management</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradereports'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Grade</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-quesanswers'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Questions<br />& Answers</div>
            </a><?php if($sessmasterprfid!=6) {?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-gradebook'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Grade Book</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-myprogress-teacherreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Student<br /> Progress</div>
            </a>
            <?php } } if($sessmasterprfid==2 or $sessmasterprfid==3 or $sessmasterprfid==6 or $sessmasterprfid==7) {?>
            <a style="display: none;" class='skip btn <?php if($sessmasterprfid!=7) { ?>mainBtn<?php } else {?>main<?php }?>' href='#reports' id='btnreports-password' <?php if($sessmasterprfid==7) { ?>onClick="window.open('reports/password/reports-password-excelviewer.php?id=0~0~7');"<?php }?>>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Export <br />Report</div>
            </a> 
            <a class='skip btn mainBtn' href='#reports' id='btnreports-mksreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Student <br />Responses</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-modscorereport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Module<br />Score</div>
            </a>
                <?php } if($sessmasterprfid!=10) {?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-correlation'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Correlation<br />Report</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-bestfit'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Best Fit <br />Report</div>
            </a>
            <?php  } ?>
            <?php if($sessmasterprfid == 5 or $sessmasterprfid == 7 or $sessmasterprfid == 8 or $sessmasterprfid==9) { 
                $expclass=$ObjDB->SelectSingleValueInt("SELECT 
                                                            a.fld_id as classid
                                                        FROM
                                                            itc_class_master AS a
                                                                LEFT JOIN
                                                            itc_class_indasexpedition_master AS b ON a.fld_id = b.fld_class_id
                                                        WHERE
                                                            a.fld_delstatus = '0'
                                                                AND b.fld_delstatus = '0'
                                                                AND a.fld_school_id = '".$schoolid."'
                                                                AND a.fld_user_id = '".$indid."'
                                                                AND (a.fld_id IN (SELECT 
                                                                    fld_class_id
                                                                FROM
                                                                    itc_class_teacher_mapping
                                                                WHERE
                                                                    fld_teacher_id = '".$uid."'
                                                                        AND fld_flag = '1'))
                                                        GROUP BY a.fld_id
                                                        ORDER BY a.fld_id DESC");
                ?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-studentstds'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Standards<br />Progress</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-classstandards'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Class <br />Standards</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' onClick="fn_prganalytics('<?php echo $domainame?>prganalytics/index.php','<?php echo $expclass;?>');">
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Class Progress<br />Report</div>
            </a>
            
            <a class='skip btn mainBtn' href='#reports' id='btnreports-orassessment'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'> Open <br /> Response </div>
            </a>
            <?php }?>
            <?php if($sessmasterprfid==6 or $sessmasterprfid==7 or $sessmasterprfid==9 or $sessmasterprfid==8) { ?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-testreports'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Test<br />Results</div>
            </a>
            
            <?php }?>
            
            <!--- Completion Report Code Start Here -->
             <?php if($sessmasterprfid==6 or $sessmasterprfid==7) { ?><!---District Admin OR School Admin-->
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport-admin'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Completion<br />Report</div>
            </a>
            <?php }?>
            
             <?php if($sessmasterprfid==9 or $sessmasterprfid==2 or $sessmasterprfid==8) { ?><!---Pitsco Admin OR Teacher-->
            <a class='skip btn mainBtn' href='#reports' id='btnreports-completionreport'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Completion<br />Report</div>
            </a>
            <?php }?>
            
            <?php if($sessmasterprfid==2) { ?>
            <!--- License renewal for pitsco -->
            <a class='skip btn mainBtn' href='#reports' id='btnreports-licenserenewal'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title="License Renewal Report">License Renewal</div>
            </a>

            <a class='skip btn mainBtn' href='#reports' id='btnreports-licenseexpiration'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn tooltip' original-title="License Expiration Report">License Expiration</div>
            </a>

            <a class='skip btn mainBtn' href='#reports' id='btnreports-fastesttimeoverall'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Fastest Times Overall SOS</div>
            </a>

            <?php }?>
            
            
            <?php if($sessmasterprfid==10) { ?>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-myprogress'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>My Progress<br />Report</div>
            </a>
            <a class='skip btn mainBtn' href='#reports' id='btnreports-assessmentqa'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Assessment<br />Report</div>
            </a>
                <a class='skip btn mainBtn' href='#reports' id='btnreports-openresponse'>
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn'>Open<br />Response</div>
                </a>
                <a class='skip btn mainBtn' href='#library' id='btnlibrary-rubric-student'>
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn'>Grading<br />Rubric</div>
                </a>
            <?php } if($sessmasterprfid!=10 and $sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6 and $sessmasterprfid!=7) { ?>
             <a class='skip btn mainBtn' href='#reports' id='btnreports-assesmentreports'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>Assessment<br />Report</div>
            </a>
            <?php
            }
            ?>
            
            <?php if($sessmasterprfid==7 or $sessmasterprfid==2) { ?>
              
              <a class='skip btn mainBtn' href='#reports' id='btnreports-schooladministrator'>
                <div class='icon-synergy-reports'></div>
                <div class='onBtn'>School Administrator</div>
            </a>
              
            <?php
            }
            ?>
            
            <?php
            if($sessmasterprfid==2) {//and $pdcount!=0 ?>
               
			<!-- DB backup secured before July 1st and possibility to run reports  code Developed by Mohan M 1-8-2016-->
				<a class='skip btn mainBtn' href='#reports' id='btnreports-backupreports'>
					<div class='icon-synergy-reports'></div>
					<div class='onBtn'>Pre/Post Assessment</div>
				</a>
			<!-- DB backup secured before July 1st and possibility to run reports  code Developed by Mohan M 1-8-2016-->
			
            <?php }?>
            
            <!--Digital Rubric Report start-->
            <?php
            if($sessmasterprfid == '6' or $sessmasterprfid== '7' or $sessmasterprfid=='9' or $sessmasterprfid==8 ) {//and $pdcount!=0 ?>
               <a class='skip btn mainBtn' href='#reports' id='btnreports-rubric'>
                    <div class='icon-synergy-reports'></div>
                    <div class='onBtn'>Grading Rubric Report</div>
                </a>

            <?php
            
            } ?>
            <!--Digital Rubric Report End-->
			<?php
                          if($sessmasterprfid != '10')
                          {
                         ?>
                          
			<!--Answer Key start-->
			<a class='skip btn mainBtn' href='#reports' id='btnreports-answerkey'>
					<div class='icon-synergy-reports'></div>
					<div class='onBtn'>Answer Key</div>
			</a>
                        <?php
                          }
                          ?>
			<!--Answer Key End-->
        </div>
    </div>
    <script>
    function fn_prganalytics(prgpath,expcls)
    {
            window.open(prgpath);
        }
    </script>
</section>
<?php
	@include("footer.php");