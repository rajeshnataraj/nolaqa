<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");
?>
<section data-type='#reports-completionreport' id='reports-completionreport-admin'>
    
<script language="javascript">
    $.getScript("reports/completionreport/reports-completionreport-admin.js");
</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Completion Report</p>
                <p class="dialogSubTitleLight">Select the type of report you wish to view, and then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'> 
                <div class="row rowspacer"> 
                     <div class="six columns">
                        Select Type<span class="fldreq">*</span>
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="typeid" id="typeid" value="" >
                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option">Select Type</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search type" >
                                    <ul role="options">
                                        <li> <a href="#" data-option="0" onclick="fn_expend();"><?php echo "Expedition";?> </a> </li>
                                        <li> <a href="#" data-option="1" onclick="fn_mison();"><?php echo "Mission";?> </a> </li>
                                    </ul>
                                </div>
                            </div>
                        </dl>
                    </div>
                    <!--Shows Class/Student dropdown-->
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                       
                    </div>
            	</div>

                <div class="row rowspacer">
                    <div class='six columns'> 
                        <div id="expenddiv" style="display:none">
                    
                        </div>
                    </div>
                </div>     
                <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="misondiv" style="display:none">

                        </div>
                    </div>
                </div>     
                    
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="destinationdiv" style="display:none">
                    
                </div>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="taskdiv">

                        </div>
                    </div>
                </div>
                
                <div class="row rowspacer">
                    <div class='twelve columns'> 
                        <div id="resourcediv">

                        </div>
                    </div>
                </div>
            
        <div class="row rowspacer">
            <div class='twelve columns'> 
                <!--Shows Class Dropdown-->
                <div id="classdiv">

                </div>
            </div>
        </div>
    <!-- District Admin code start here-->
        <div class="row rowspacer">
                <div class='twelve columns'> 
                    <!--Shows Class Dropdown-->
                    <div id="schooldiv" style="display:none">

                    </div>
                </div>
        </div>
    <!-- District Admin code End  here-->
                
                <input type="hidden" name="profileid" id="profileid" value="<?php echo $sessmasterprfid; ?>">  
                <input type="hidden" name="loginid" id="loginid" value="<?php echo $uid; ?>">
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "completionreportadmin_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_savecompletionrpt();" />
                </div>
                  </div>
            </div>
        </div>

</section>
<?php
	@include("footer.php");