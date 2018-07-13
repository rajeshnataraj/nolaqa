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
<section data-type='#reports-gradereports' id='reports-gradereports-prepost'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Pre/Post Test Scores report</p>
				<!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                                <p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>
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
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid;?>" onclick="fn_showteachers(<?php echo $schoolid;?>,0,6)"><?php echo $schoolname; ?></a></li>
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
					<?php } if($sessmasterprfid!=2 and $sessmasterprfid!=3 and $sessmasterprfid!=6) { ?>
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
                                        /*Mohan M updated by Whole Class assignment */
                                        if($sessmasterprfid=='7')
                                        {
                                            $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
                                                                           FROM itc_class_master 
                                                                           WHERE fld_district_id='".$districtid."' AND fld_school_id='".$senshlid."' AND fld_delstatus='0' AND fld_archive_class='0' 
                                                                           ORDER BY fld_class_name");
                                        }
                                        else
                                        {
                                            $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname 
                                                                           FROM itc_class_master 
                                                                           WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$uid."' 
                                                                           OR fld_id IN (SELECT fld_class_id 
                                                                                                           FROM itc_class_teacher_mapping 
                                                                                                           WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) 
                                                                           ORDER BY fld_class_name");
                                        }
                                        /*Mohan M updated by Whole Class assignment */
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="$('#unid').hide(); $('#cuid').hide(); fn_load_schedule(2,<?php echo $classid;?>,1);"><?php echo $classname; ?></a></li>
                                                <?php
                                            }
                                        }?>      
                                    </ul>
                                </div>
                            </div> 
                        </dl>   
                    </div>
                    <?php }?>
                    
                    <!--Loads the Schedule details in dropdown through ajax page according to the class selection-->
                    <div class='six columns'> 
                        <dl class='field row' id='cuiddiv' style="display:none">  
                            
                        </dl>                                
                    </div>
                </div>
                    
                <!--Shows Rotation Dropdown-->
                <div class='row rowspacer'>
                	<!--Loads the Rotation details in dropdown through ajax page according to the class & schedule selection-->
                    <div class='six columns'>
                        <dl class='field row' id='uniddiv' style="display:none">  
                            
                        </dl>
                    </div>
                </div>
                <input type="hidden" id="hidprepostname" name="hidprepostname" value="<?php echo "prepostreport_"; ?>" />
                <input type="hidden" id="schtype" name="schtype" value="" />
                
                <!--View Report Button-->
                <div class="row rowspacer" style="display:none" id="viewreportdiv">
                	<input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-left:5px" value="Export as csv" onClick="fn_export(6);" />
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_gradereport(6);" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
@include("footer.php");