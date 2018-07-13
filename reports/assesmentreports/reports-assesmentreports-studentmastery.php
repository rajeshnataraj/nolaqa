<?php
@include("sessioncheck.php");

/*
	Created By - SenthilNathan. S
	
*/
?>
<section data-type='#reports-assesmentreports' id='reports-assesmentreports-studentmastery'>
    <script language="javascript">
   		$.getScript("reports/assesmentreports/reports-assesmentreport.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Mastery Report</p>
                <p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<div class='row rowspacer'>
                    <?php  if($sessmasterprfid==9 or $sessmasterprfid==8) { ?>
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
                                    
                                     $qry = $ObjDB->QueryObject("SELECT f.classid,f.classname FROM (SELECT a.fld_id AS classid, a.fld_class_name AS classname
                                                                                FROM itc_class_master as a
                                                                                LEFT JOIN itc_test_student_mapping as b ON a.fld_id = b.fld_class_id 
                                                                                WHERE fld_archive_class='0' AND a.fld_delstatus = '0' AND b.fld_created_by='".$uid."' AND b.fld_flag='1'
                                                                                AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
                                                                                FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
                                                                                UNION ALL
                                                                                SELECT b.fld_class_id AS classid,c.fld_class_name AS classname FROM itc_test_master AS a
                                                                                LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_expt=b.fld_exp_id
                                                                                LEFT JOIN itc_class_master AS c ON b.fld_class_id=c.fld_id

                                                                                WHERE a.fld_ass_type='1' AND a.fld_created_by='".$uid."' AND b.fld_createdby='".$uid."'
                                                                                AND c.fld_archive_class='0' AND c.fld_delstatus='0' AND a.fld_delstatus = '0' AND b.fld_delstatus='0' AND b.fld_flag='1' 
                                                                                UNION ALL

                                                                                SELECT d.fld_class_id AS classid,c.fld_class_name AS classname FROM itc_test_master AS a
                                                                                LEFT JOIN itc_class_indasmission_master AS d ON a.fld_mist=d.fld_mis_id
                                                                                LEFT JOIN itc_class_master AS c ON d.fld_class_id=c.fld_id

                                                                                WHERE a.fld_ass_type='2' AND a.fld_created_by='".$uid."' AND d.fld_createdby='".$uid."'
                                                                                AND c.fld_archive_class='0' AND c.fld_delstatus='0' AND a.fld_delstatus = '0' AND d.fld_delstatus='0' AND d.fld_flag='1') as f GROUP BY classid ORDER BY classname");
                                    if($qry->num_rows>0){
                                        while($row = $qry->fetch_assoc())
                                        {
                                            extract($row);
                                            ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onClick="$('#assessmentdiv').show(); fn_assessment(<?php echo $classid; ?>)"><?php echo $classname; ?></a></li>
                                            <?php
                                        }
                                    }?>      
                                </ul>
                            </div>
                        </div>
                        </dl>
                    </div>
                    <?php } ?>
                    <div class='six columns'>   
                    
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="assessmentdiv" style="display:none">
                    
                    </div>
                                </div>
                            </div> 
                    
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        	<div id="studentdiv" style="display:none">
                            	
                            </div>
                    </div>
                </div>
                
                <!---Show Standards -->                
                    <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <div id="standardsdiv" style="display:none">Show Standards
                                <input type="radio" id="tag" name="types" checked="checked" value="1" onclick="getradioval(1);" />Yes
                                <input type="radio" id="search" name="types" value="0" onclick="getradioval(0);" />No
                            </div>
                        </div>
                    </div>
                <!---Show Standards -->             
                
                <input type="hidden" id="hidassengname" name="hidassengname" value="<?php echo "studentmasteryreport_"; ?>" />
                
                <input type="hidden" id="hidradioval" name="hidradioval" value="1" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_gradereport(1,<?php echo $uid; ?>);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");