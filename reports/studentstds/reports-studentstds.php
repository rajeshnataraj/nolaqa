<?php
@include("sessioncheck.php");
?>
<section data-type='2home' id='reports-studentstds'>
<script language="javascript">
    $.getScript("reports/studentstds/reports-studentstds.js");
</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Standards Progress Report</p>
                <p class="dialogSubTitleLight">Select a report to view or print. Select New Report to create your own.</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'> 
                <div class="row"> 
                    <div class='six columns'> Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Class">
                                    <ul role="options" style="width:100%">
										<?php 
                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
																	FROM itc_class_master 
																	WHERE fld_archive_class='0' AND fld_delstatus='0' AND (fld_created_by='".$uid."' 
																	OR fld_id IN (SELECT fld_class_id 
																					FROM itc_class_teacher_mapping 
																					WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) 
																	ORDER BY fld_class_name");
                                        if($qry->num_rows>0){
											while($row = $qry->fetch_assoc())
											{
												extract($row);
												?>
												<li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showschedule(<?php echo $classid;?>)"><?php echo $classname; ?></a></li>
												<?php
											}
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                
                    <!--Shows Class/Student dropdown-->
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="schedulediv" style="display:none">
                            
                        </div>
                    </div>
            	</div>
                
                <!--Shows Class/Student dropdown-->
                <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="studentdiv" style="display:none">
                            
                        </div>
                    </div>
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="statediv" style="display:none">
                            
                        </div>
                    </div>
                    
                    
                </div>
                 <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="docdiv" style="display:none">
                            
                        </div>
                    </div>
                     
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="gradediv" style="display:none">
                            
                        </div>
                    </div>
                 </div>
            
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "myprogressreport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_studentstds();" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");