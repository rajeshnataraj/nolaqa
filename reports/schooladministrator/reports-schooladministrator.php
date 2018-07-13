<?php
@include("sessioncheck.php");


/*
    Created By - Narendrakumar Team Leader
    Page - reports-schooladministrator
    Description:
        Select the District, Select class , Select Teacher  and select assignments and View Report button.

    Actions Performed:
        School Administrators should be able to view accumulated data and information for the entire school, groups of teachers, a particular teacher, a particular class. This is read-only. They would have no ability to modify the data.
   * 
   * User who interact: 
     School Administrator: view
     Pitsco Admin: view
 
 School Administrator
1.	Under Reports? A new report will be available for School Admin to view some available data of the school
2.	The data available will include assignments (Expeditions, IPL’s, Modules) and assessments on each class
3.	The report will be included in the Dashboard
4.	The report will have the option to show the results for all teachers, group of teachers or one teacher, for all classes, group of classes or one class
5.	The report will include: Name of the school, classes assign to each teacher, assignment assign to each class, assessments assign to each class, assignment complete, assessments complete, average score per schedule, average score per assessment
6.	The completion on each assignment will be based on the completion of each student, when all students complete the assignment (Expedition, Module, IPL), the report will show the assignment as “Complete”
7.	The completion on each assessment will be based on the completion of each student, when all students complete the assessment, the report will show the assessments as “Complete”
8.	The average Points Earned and Points Possible will be based on the score of each assessment and each assignment
9.	Assessments for expeditions will display the total score reported in the grade boo

Pitsco Admin
10.	Under Reports? A new report will be available for Pitsco Admin to view some available data of the school, district, etc
11.	The data available will include assignments (Expeditions, IPL’s, Modules) and assessment on each class
12.	The report will have the option to show the results for a school, district, etc, all teachers, group of teachers or one teacher, for all classes, group of classes or one class 
13.	Name of the District, Name of the school, classes assign to each teacher, assignments assign to each class, assessments assign to each class, assignments complete, assessments complete, average score per assignment, average score per assessment
14.	The completion on each assessment will be based on the completion of each student, when all students complete the assessment, the report will show the assessments as “Complete”
15.	The average Points Earned and Points Possible will be based on the score of each assessment and each assignment



4. Notes
If there are more than one teacher assigned to the same class, both will see the same information
*/

?>
<section data-type='2home' id='reports-schooladministrator'>
	<script language="javascript">
    	$.getScript("reports/schooladministrator/reports-schooladministrator.js");
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">School Administrator report</p>
                <p class="dialogSubTitleLight">Select the type of report you wish to view, and then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
                <?php
                    if($sessmasterprfid==2)
                    {
                    ?>    
                    <div class="row"> 
                        <div class='six columns'>   
                            Category
                         <dl class='field row'>
                            <div class="selectbox">
                                    <input type="hidden" name="categoryid" id="categoryid" value="Select Category">
                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Category</span>
                                        <b class="caret1"></b>
                                    </a>
                                <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Category">		    
                                            <ul role="options" style="width:100%;">
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="1" onclick="fn_schoolpurchase(1);">School Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="2" onclick="fn_homepurchase(2);">Home Purchase</a>
                                                </li>
                                                <li>
                                                    <a tabindex="-1" href="#" data-option="3" onclick="fn_distpurchase(3);">District</a>
                                                </li>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                  </div>
                
                  <div class="row rowspacer">
                    <div class='six columns'> 
                        <div id="districtdiv" style="display:none"></div>
                    </div>
                  </div> 
                
                  <div class="row rowspacer">
                    <div class='six columns'> 
                        <div id="schooldiv" style="display:none"></div>
                    </div>
                  </div>
              <?php
                    }
                    else
                    {
                    ?>
                    <script>
                     setTimeout("fn_showclass();",1500);
                    </script>
                    <?php
                    }
                    ?>
                  
                  <div class="row <?php if($sessmasterprfid==2) { ?>rowspacer<?php } ?>">
                    <div class='twelve columns'> 
                        <div id="classdiv" style="display:none"></div>
                    </div>
                  </div>
                      
                  <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="teacherdiv" style="display:none"></div>
                    </div>
                  </div>
                      
                  <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="assignmentsdiv" style="display:none"></div>
                    </div>
                  </div>
                            
                      
                  <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo 'schooladministrator_'; ?>" />
         
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
			<div style="float:right;margin-right:20px;">
                            <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="View Report" onClick="fn_schooladministratorreport(<?php echo $sessmasterprfid; ?>);" />
			</div>
				<input type="hidden" id="hidselectedstudentids" name="hidselectedstudentids" value="" />
                </div>
                <div class="row rowspacer"></div>
            </div>
        </div>
    </div>
</section>

<?php
	@include("footer.php");
