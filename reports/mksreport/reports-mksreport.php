<?php
@include("sessioncheck.php");
//echo "userid...".$uid;

/*
    Created By - Vijayalakshmi PHP Programmer
    Page - reports-knowsurvey
    Description:
        Select the District, Select School , Select class  and select assignee modules dropdowns and View Report button.

    Actions Performed:
        Select Type - Used to select either Class/Student. 
        Select Class & Student - It will displayed according to "Select Type" Selection. 
        View Report - Redirects to the page to display the details for the selection - reports-classroom-viewreport.php
    
    History: 
 * For select all students and order changed from class->student->assignmet to  class->assignmet->student


*/

?>
<section data-type='2home' id='reports-mksreport'>
	<script language="javascript">
    	$.getScript("reports/mksreport/reports-mksreport.js");
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Assessment Question & Answer Report</p>
                <p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
                <div class="row"> 
                    <div class='six columns'> District
                         <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="districtid" id="districtid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select District</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search District">
                                    <ul role="options" style="width:100%">
                                        <?php 

                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname FROM itc_district_master WHERE fld_delstatus='0' ORDER BY fld_district_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $districtid;?>" onclick="fn_showschool(<?php echo $districtid;?>)"><?php echo $districtname; ?></a></li>
                                                <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                
                    <!--Shows school names dropdown-->
                    <div class='six columns'> 
                        <!--Shows School Dropdown-->
                        <div id="schooldiv" style="display:none">
                            
                        </div>
                    </div>
            	</div>
                
                <!--Shows class dropdown-->
                <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="classdiv" style="display:none">
                            
                        </div>
                    </div>
                     <div class='six columns'> 
                        <!--Shows Assigments Dropdown-->
                        <div id="assignmentdiv" style="display:none">
                            
                        </div>
                    </div>
                </div>

         <div class="row rowspacer" id="loadstudentidlist" style="display:none;"></div>       
               
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo 'mksreport_'; ?>" />
                   <input type="hidden" id="hidexpsch" name="hidexpsch" value="0" />
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
			<div class="six columns" style="float:right;">
				<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; margin-right:10px;" value="Export" onClick="fn_exportmksreport();" />
				<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="View Report" onClick="fn_mksreport();" />
			</div>
				<input type="hidden" id="hidselectedstudentids" name="hidselectedstudentids" value="" />
                </div>
                
                
                <div class='row rowspacer' style="display:none" id="viewreportdivforexpsch">
			<div class="six columns" style="float:right;">
				<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;  margin-left: 214px;" value="View Report" onClick="fn_mksreport();" />
            </div>
				<input type="hidden" id="hidselectedstudentids" name="hidselectedstudentids" value="" />
        </div>
    </div>
        </div>
    </div>
</section>

<?php
	@include("footer.php");
