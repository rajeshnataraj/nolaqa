<?php
@include("sessioncheck.php");

/*
	Created By - Muthukumar. D
	Page - reports-classroom-stupassword
	Description:
		Show the Select Type, Select Class & Select Student dropdowns and View Report button.

	Actions Performed:
		Select Type - Used to select either Class/Student. 
		Select Class & Student - It will displayed according to "Select Type" Selection. 
		View Report - Redirects to the page to display the details for the selection - reports-classroom-viewreport.php
	
	History:


*/

?>
<section data-type='#reports-classroom' id='reports-classroom-stupassword'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Student Passwords report</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                    <p class="dialogSubTitleLight">Select the type of report you wish to view, and then click "View Report".</p>
            </div>
        </div>
        
        <input type="hidden" name="schoolid" id="schoolid" value="<?php echo $schoolid; ?>">
        <input type="hidden" name="indid" id="indid" value="<?php echo $indid; ?>">
        
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
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showteachers(<?php echo $schoolid;?>,0,1)"><?php echo $schoolname; ?></a></li>
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
                
                <div class="row rowspacer">
                	<div class='six columns' id="typesdiv" <?php if($sessmasterprfid==6) { ?>style="display:none"<?php }?>>   
                    	Type  
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classstutype" id="classstutype" value="Select Type">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Type</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">			    
                                    <ul role="options" style="width:100%;">
                                        <li><a tabindex="-1" href="#" data-option="1" onclick="fn_showclassstu(1)">Class</a></li>
                                        <li><a tabindex="-1" href="#" data-option="2" onclick="fn_showclassstu(2)">Student</a></li>
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                    
                    <div class='six columns' id="classstudiv">   
                    
                    </div>
                </div>
                <input type="hidden" id="hidteacher" name="hidteacher" value="<?php echo $uid; ?>" />
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "passwordreport_"; ?>" />
                
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_showpassreport(1);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");