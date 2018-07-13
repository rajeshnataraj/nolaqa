<?php
@include("sessioncheck.php");
?>
<section data-type='#reports-completionreport' id='reports-completionreport-bystudent'>
<script language="javascript">
    $.getScript("reports/completionreport/reports-completionreport-bystudent.js");
</script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Completion Report by Student</p>
                <p class="dialogSubTitleLight"></p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
                <div class="row rowspacer"> 
                    <div class='twelve columns'>
                        <?php if($sessmasterprfid==9 or $sessmasterprfid==8) { ?>

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
                                                   <li > <a href="#" data-option="0" onclick="fn_showclass(1);"><?php echo "Expedition";?> </a> </li>
                                                    <li> <a href="#" data-option="1" onclick="fn_showclass(2);"><?php echo "Mission";?> </a> </li>
                                           </ul>
                                         </div>
                                       </div>
                                </dl>
                        </div>

                        <div id="clasid" class="six columns"> 

                        </div>

                        <?php } if($sessmasterprfid==2){ ?>

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
                                                   <li > <a href="#" data-option="0" onclick="fn_showexpforpitsco();"><?php echo "Expedition";?> </a> </li>
                                                    <li> <a href="#" data-option="1" onclick="fn_showmisonforpitsco();"><?php echo "Mission";?> </a> </li>
                                           </ul>
                                         </div>
                                       </div>
                                </dl>
                        </div>
                         <div class="six columns">
                            <div id="expeditiondiv" style="display:none">

                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row rowspacer">
                    <div class='twelve columns'>
                         <div class="six columns">
                             <div id="expeditionschedulediv" style="display:none">

                            </div>
                        </div>
                        <div class="six columns">
                       <div id="expeditiondiv" style="display:none">

                       </div>
                   </div>
                </div>
                </div>
                <div class="row rowspacer"> 
                    <div class='twelve columns'>
                         <div class="six columns">
                             <div id="missionschedulediv" style="display:none">

                            </div>
                        </div>
                         <div class="six columns">
                       <div id="missiondiv" style="display:none">

                       </div>
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
            <!-- /******Pitsco admin Coding End Here**********/-->
                <div class="row rowspacer">
                    <div class='six columns'> 
                        <div id="districtdiv" style="display:none">

                        </div>
                    </div>
                    <div class='six columns'> 
                        <div id="schooldiv" style="display:none">

                        </div>
                    </div>
                </div>
                <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="classdivforpitsco" style="display:none">

                        </div>

                    </div>
                    <div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="classscheddivforpitsco" style="display:none">

                </div>

                    </div>
                </div>
            <!-- /******Pitsco admin Coding End Here**********/-->

                     <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <!--Shows Class Dropdown-->
                            <div id="studentdiv">

                            </div>

                        </div>
                    </div>

                    <input type="hidden" name="profileid" id="profileid" value="<?php echo $sessmasterprfid; ?>">  
                    <input type="hidden" name="loginid" id="loginid" value="<?php echo $uid; ?>">
                    <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "completionreport_"; ?>" />

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