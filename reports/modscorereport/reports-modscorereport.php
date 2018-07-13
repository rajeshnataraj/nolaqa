<?php
@include("sessioncheck.php");


/*
    Created By - Narendrakumar PHP Programmer
    Page - reports-modulescore
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
<section data-type='2home' id='reports-modscorereport'>
	<script language="javascript">
    	$.getScript("reports/modscorereport/reports-modscorereport.js");
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Module Score report</p>
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
                
                    <div class='three columns' id="showstart" style="display:none">
                        Start date
                        <dl class='field row'>
                            <dt class='text'>
                                 <input id="startdate1" name="startdate1" class="quantity" placeholder='Start Date' type='text' value="" >
                            </dt>                                        
                        </dl>
                    </div>
                    
                    <div class='three columns' id="showend" style="display:none">
                        End date
                        <dl class='field row'>
                            <dt class='text'>
                                 <input id="enddate1" name="enddate1" class="quantity" placeholder='End Date' type='text' value="" >
                            </dt>                                        
                        </dl>
                    </div>
                             
              </div>
                 <script type="text/javascript" language="javascript">
                        $("#startdate1").datepicker( {
                                onSelect: function(selectedDate){
                                        $("#enddate1").val('');
                                        $('#showend').show();
                                        $('#viewreportdiv').hide();
                                        $("#enddate1" ).datepicker( "option", "minDate", selectedDate );

                                }
                        });

                        $("#enddate1").datepicker( {
                                onSelect: function(dateText,inst){
                                    
                                    var stdate = $('#startdate1').val();
                                    var enddate = $('#enddate1').val();
                                                                      
                                        $('#modorrotatediv').show();

                                }
                        });
                        
                                            

                </script>
                
        <div class="row rowspacer">                 

               <div class='six columns' id="modorrotatediv" style="display:none" >

                  <input type="radio" id="radiobtn" name="radiobtn" value="1" onclick="fn_check(1);$('#modschedulediv').hide();$('#loadstudentlist').hide();$('#stunameid').hide();" />Module

                  <input type="radio" id="radiobtn" name="radiobtn" value="2" onclick="fn_check(2);$('#modschedulediv').hide();$('#loadstudentlist').hide();$('#stunameid').hide();"/>Schedule

              </div>

                     <div class='six columns'> 
                        <!--Shows Assigments Dropdown-->
                        <div id="assignmentdiv" style="display:none">
                            
                        </div>
                    </div>
                </div>

        <div class="row rowspacer" id="modschedulediv" style="display:none;"  >        
          
                 <!--  Module Schedule Display -->
             
        </div>        
                
         <div class="row rowspacer" id="loadstudentidlist" style="display:none;"></div>    
         
                <div class="row rowspacer" id="stunameid" style="display:none;">
                    <div class='four columns'>   
                        <form id="frmrep" name="frmrep">
                            <div class="field">
                                <label class="checkbox checked" for="stuname" id="chkname">
                                    <input name="stuname" id="stuname" value="1" type="checkbox" style="display:none;" checked="checked"/>
                                    <span></span>	Show Student Name
                                </label>
                            </div>
                        </form>
                    </div>
                    
                    <div class='six columns'>   
                        <form id="frmrep" name="frmrep">
                            <div class="field">
                                <label class="checkbox" for="stuid" id="chkid">
                                    <input name="stuid" id="stuid" value="2" type="checkbox" style="display:none;"/>
                                    <span></span>	Show ID #
                                </label>
                            </div>
                        </form> 
                    </div>
                </div>
               
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo 'mksreport_'; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
			<div class="six columns" style="float:right;">
				<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; margin-right:10px;" value="Export" onClick="fn_exportmodscorereport();" />
				<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="View Report" onClick="fn_modscorereport();" />
			</div>
				<input type="hidden" id="hidselectedstudentids" name="hidselectedstudentids" value="" />
                </div>
            </div>
        </div>
    </div>
</section>

<?php
	@include("footer.php");
