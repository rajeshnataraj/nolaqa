<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - library-diagmastery-testdetails
	Description:
		Show the Subject, Course, Units, Lesson dropdowns & Lesson Weight textbox, Tag to Create/Edit the Diagnostic-Mastery Details
	
	Actions Performed:
		Subject - Loads all the Subject names.
		Course - Loads all the Course names under the Selected Subject.
		Units - Loads all the Unit names under the Selected Course.
		Lesson - Loads all the Lesson names under the Selected Unit.
		Lesson Weight - Type only the Numeric Values Greater than '0' & Less than or Equal to '100'.
		Tag - Creates a new tag to save the Diagmastery details.
		
	History:
	

*/
?>
<section data-type='#reports-classroom' id='reports-classroom-stuschedule'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Individual Student Schedule report</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                <p class="dialogSubTitleLight">Select the class that the student is in, and then click "View Report".</p>
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
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showteachers(<?php echo $schoolid;?>,0,2)"><?php echo $schoolname; ?></a></li>
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
                
                <div class="row rowspacer">
                    <div class='six columns' id="classdiv">   
                    
                    </div>
                <?php }
				
				if($sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6) { ?>
            	<!--Shows Class & Student Dropdown-->
            	<div class="row">
                	<!--Loads the class details in dropdown-->
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
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onClick="$('#studentdiv').show(); fn_showstudent(<?php echo $classid; ?>)"><?php echo $classname; ?></a></li>
                                                <?php
                                            }
                                        }?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                    <?php }?>
                    <!--Loads the student details in dropdown through ajax page according to the class selection-->
                    <div class='six columns'>   
                        <dl class='field row'>
                        	<div id="studentdiv" style="display:none">
                            	 
                            </div>
                        </dl>
                    </div>
                </div>
                
                <div class="row rowspacer" id="stupassdiv">
                    <div class='six columns showallst' style="display:none">   
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
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "schedulereport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_showpassreport(2);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");