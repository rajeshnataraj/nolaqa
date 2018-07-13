<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-gradereports-indvidualassignment
	Description:
		Show the Class, Students, Assignments & Grading dropdowns with the View Report button.
	
	Actions Performed:
		Class - Loads all the Class names.
		Students - Loads all the Students names under the Selected Class.
		Assignments - Loads all the Assignments names under the Selected Students & Class.
		Grading - 
		View Report - Redirects to the report viewing page - reports-gradereports-viewreport.php
		
	History: updated By mohan kumar .v 
 * For select all students and order changed from class->student->assignmet to  class->assignmet->student
	

*/

?>
<section data-type='#reports-gradereports' id='reports-gradereports-indvidualassignment'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Individual Assignment Points</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
				<!--<p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>-->
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<?php if($sessmasterprfid==6) { ?>
                <div class="row">
                    <div class='six columns'>
                        School
                        <div class="selectbox">
                            <input type="hidden" name="districtid" id="districtid" value="<?php echo $districtid; ?>">
                            <input type="hidden" name="schoolid" id="schoolid" value="">
                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                                <b class="caret1"></b>
                            </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search School">
                                <ul role="options" style="width:100%">
                                    <?php 
                                    $qry = $ObjDB->QueryObject("SELECT fld_id AS schoolid, fld_school_name AS schoolname 
																FROM itc_school_master 
																WHERE fld_delstatus='0' AND fld_district_id='".$districtid."'
																ORDER BY fld_school_name");
                                    if($qry->num_rows>0){
                                        while($row = $qry->fetch_assoc())
                                        {
                                            extract($row);
                                            ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showteachers(<?php echo $schoolid;?>,0,4)"><?php echo $schoolname; ?></a></li>
                                            <?php
                                        }
                                    }?>      
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class='six columns' id="teachersdiv">   
                    
                    </div>
                </div>
                <?php }?>
                
            	<!--Shows Select Class & Schedule Dropdown-->
            	<div class='row rowspacer'>
                	<?php if($sessmasterprfid==6) { ?>
                	<div class='six columns' id="classdiv">   
                    
                    </div>
                	<!--Loads Select Class Dropdown-->
					<?php } 
                                        if($sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6) { ?>
                    <div class='six columns'>
                    	Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                    <ul role="options" style="width:100%">
                                        <?php 
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
																	FROM itc_class_master 
																	WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$uid."' 
																	OR fld_id IN (SELECT fld_class_id 
																					FROM itc_class_teacher_mapping 
																					WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) 
																	ORDER BY fld_class_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
						<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="$('#rotationdiv').show(); fn_load_studentnew(<?php echo $classid; ?>);"><?php echo $classname; ?></a></li>
                                                <?php
                                            }
                                        }?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>   
                    </div>
                    <?php }?>
                    
                   
                    <!--Loads the assignment details in dropdown through ajax page according to the class selection-->
                    <div class='six columns'>   
                        <dl class='field row'>
                            <div id="rotationdiv" style="display:none">
                            	 
                            </div>
                        </dl>
                    </div>
                </div>
                
                <!--Shows Assignment & Grading Dropdown-->
                <div class='row rowspacer'>
                	 <!--Loads the student details in dropdown through ajax page according to the assignment selection-->
                    <div class='six columns'>
                        <dl class='field row'>  
                            <div id="studentdiv" style="display:none">
                            	
                            </div>
                        </dl>
                    </div>
                </div>
                
				<!-- created by chandra -->
                <div class='row rowspacer'>
                	 <!--Loads the student details in dropdown through ajax page according to the assignment selection-->
                    <div class='twelve columns'>
                        <dl class='field row'>  
                            <div id="scdulediv" style="display:none">
                            	
                            </div>
                        </dl>
                    </div>
                </div>
                
                <div class='row rowspacer' id="stupassdiv" style="display:none">
                    <div class='six columns'>   
                        <form id="frmrep" name="frmrep">
                            <div class="field">
                                <label class="checkbox" for="stuname" onclick="fn_checkstu()">
                                    <input name="stuname" id="stuname" value="1" type="checkbox" style="display:none;"/>
                                    <span></span>	Show Student Name
                                </label>
                            </div>
                        </form> 
                        <input type="hidden" id="hidcheckstu" name="hidcheckstu" value="0" />
                    </div>
                </div>  
                <input type="hidden" id="hidindassname" name="hidindassname" value="<?php echo "individualassignmentreport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                	<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-left:5px" value="Export as csv" onClick="fn_export(4);" />
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_gradereport(4);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");