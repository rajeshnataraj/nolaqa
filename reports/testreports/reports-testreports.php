<?php
@include("sessioncheck.php");

/*
	Created By - Mohan Kumar.V
	Page - reports-testreports
	Description:
		Show the Class, Students, Assignments & Grading dropdowns with the View Report button.
	
	Actions Performed:
		Class - Loads all the Class names.
		Students - Loads all the Students names under the Selected Class.
		Assignments - Loads all the Assignments names under the Selected Students & Class.
		Grading - 
		View Report - Redirects to the report viewing page - reports-gradereports-viewreport.php
		


*/

?>
<section data-type='#reports-testreports' id='reports-testresultsreport'>
    <script language="javascript">
   		$.getScript("reports/testreports/reports-testreports.js");
    </script>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Test results report</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                <p class="dialogSubTitleLight">Select the type of report you wish to view, and then click "View Report".</p>
            </div>
        </div>
        <div class="row ">
            <div class="twelve columns">
                
           
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	                <?php 
                if($sessmasterprfid == 6){ //For District Admin ?>
                <div class="row"> 
                    <div class='six columns'> School
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="schoolid" id="schoolid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#"> <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span> <b class="caret1"></b> </a>
                                <div class="selectbox-options" style="top: -133px;">
                                    <input type="text" class="selectbox-filter" placeholder="Search School">
                                    <ul role="options" style="width:100%">
                                        <?php 
                                         $qry = $ObjDB->QueryObject("SELECT fld_school_name AS shlname, fn_shortname(fld_school_name,1) AS shortname, fld_id AS shlid,
											fld_district_id AS shldistid, fld_school_logo AS shllogo 
										FROM itc_school_master 
										WHERE fld_district_id='".$sendistid."' AND fld_delstatus='0' AND fld_district_id !=0 order by fld_school_name ASC");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                    extract($row);
                                                    ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $shlid;?>" onclick="$('#waytoview').show(); $('#viewdropdiv').hide(); fn_showwaystoviewreports(<?php echo $shlid;?>)"><?php echo $shlname; ?></a></li>
                                                    <?php
                                            }
                                        }?>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                
                     <!--Shows Ways to sort Type report Drop down-->
                    <div class='six columns'>   
                        <dl class='field row'>
                            <!--Loads the sort report details in drop down through ajax page according to the View type selection-->
                        <div id="waytoview" style="display:none">
                            
                            	 
                            </div>
                        </dl>
                    </div>
            	</div>
                
              
                <!--Loads the View details in drop down through ajax page according to the View type & Sort Type selection -->
                <div class='row rowspacer'>
                    <div class='six columns'>   
                        <dl class='field row'>
                       <div id="sortdropdiv" style="display:none">
                         </div>
                            </dl>
                    </div>
                	 <div class='six columns'>   
                        <dl class='field row'>
                       <div id="viewdropdiv" style="display:none">
                         </div>
                            </dl>
                    </div>
                        
                </div>
                
                
                <div class='row rowspacer'>
            	
                    
                     <div class='six columns'>   
                        <dl class='field row'>
                       <div id="attemptsdiv" style="">
                         </div>
                            </dl>
                    </div>
                
                      </div>
              <?php  } else if($sessmasterprfid==7 or $sessmasterprfid==9 or $sessmasterprfid==8) {?>
            	<!--Shows Select Ways to View Type report Drop down-->
            	<div class='row rowspacer'>
                 <div class='six columns'>
                    	 Ways to view the report
                        <dl class='field row'>
                           <div class="selectbox">
                    <input type="hidden" name="viewtype" id="viewtype" value="" />
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select View Type</span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options" style="top: -133px;">
                        <input type="text" class="selectbox-filter" placeholder="Search View Type Report">
                        <ul role="options" style="width:100%;">
                            <li><a tabindex="-1" href="#" data-option="1" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(1);">Assessment</a></li>
                            <li><a tabindex="-1" href="#" data-option="2" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(2);">Standard</a></li>
                            <li><a tabindex="-1" href="#" data-option="3" onclick="$('#sortdropdiv').show(); $('#viewdropdiv').hide(); fn_showsortreport(3);">Question</a></li>
                            
                        </ul>
                    </div>
                </div> 
                        </dl>   
                    </div>
                    <!--Shows Ways to sort Type report Drop down-->
                    <div class='six columns'>   
                        <dl class='field row'>
                            <!--Loads the sort report details in drop down through ajax page according to the View type selection-->
                        <div id="sortdropdiv" style="display:none">
                            
                            	 
                            </div>
                        </dl>
                    </div>
                </div>
                
            	<!--Loads the View details in drop down through ajax page according to the View type & Sort Type selection -->
                <div class='row rowspacer'>
            	
                    
                     <div class='six columns'>   
                        <dl class='field row'>
                       <div id="viewdropdiv" style="">
                         </div>
                            </dl>
                    </div>
                    <div class='six columns'>   
                        <dl class='field row'>
                       <div id="attemptsdiv" style="">
                         </div>
                            </dl>
                    </div>
                </div>
                <!--Loads the View details in drop down through ajax page according to the View type & Sort Type selection -->
                <div class='row rowspacer'>
                    
                	 <div class='six columns'>   
                        <dl class='field row'>
                       <div id="attemptsdiv" style="">
                         </div>
                            </dl>
                    </div>
                     <div class='six columns'>   
                        <dl class='field row'>
                       <div id="viewdropdiv" style="">
                         </div>
                            </dl>
                    </div>
                        
                </div>
                    <?php }?>
                    
                
              </div>
                
                <input type="hidden" id="hidtestname" name="hidtestname" value="<?php echo "testresultsreport_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none; margin-bottom:30px;" id="viewreportdiv">
                   
                    <input type="button" id="btnstep" class="darkButton" style="width:190px; height:42px; margin-left: 710px;" value="View Report" onClick="fn_testresultsreport(<?php echo $schid;?>);" />
                    
                </div>
            </div>
         </div>
        </div>
        </div>
   
</section>
<?php
@include("footer.php");
