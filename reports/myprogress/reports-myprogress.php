<?php
@include("sessioncheck.php");

?>
<section data-type='2home' id='reports-myprogress'>
	<script language="javascript">
   		$.getScript("reports/myprogress/reports-myprogress.js");
    </script>
    
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">My Progress Report</p>
				<p class="dialogSubTitleLight"></p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            <?php if($uid1!='') {?>
            	<div class="row">
                	<!--Select Type (Class/Student)-->
                	<div class='twelve columns'>   
                    	Student
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="studentid" id="studentid" value="">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Student</span>
                                    <b class="caret1"></b>
                                </a>
                                <div class="selectbox-options">
                                    <ul role="options" style="width:100%">
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $uid;?>" onclick="fn_showclass(<?php echo $uid;?>)"><?php echo $username; ?></a></li>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $uid1;?>" onclick="fn_showclass(<?php echo $uid1;?>)"><?php echo $username1; ?></a></li>
                                    </ul>
                                </div>
                            </div> 
                        </dl>
                    </div>
                </div>
                <?php }?>
            	<!--Shows Select Type & Class/Student Dropdown-->
            	<div class="row rowspacer">
                	<!--Select Type (Class/Student)-->
                	<div class='six columns' id="showclsdiv" <?php if($uid1!='') {?>style="display:none"<?php }?>>  
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
                                        $qry = $ObjDB->QueryObject("SELECT a.fld_id AS classid, a.fld_class_name AS classname 
																	FROM itc_class_master AS a 
																	LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_class_id 
																	WHERE a.fld_delstatus='0' AND a.fld_archive_class='0' AND b.fld_student_id='".$uid."' AND b.fld_flag='1'
																	ORDER BY a.fld_class_name");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showschedule(<?php echo $classid;?>,<?php echo $uid;?>)"><?php echo $classname; ?></a></li>
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
                
				<div class="row rowspacer">
					<div class='six columns'> 
                        <!--Shows Class Dropdown-->
                        <div id="rotationdiv" style="display:none">
                            
                        </div>
                    </div>
                </div>
                
                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "myprogressreport_"; ?>" />
                <input type="hidden" id="studentid" name="studentid" value="<?php echo $uid; ?>" />
                <input type="hidden" id="profileid" name="profileid" value="<?php echo $sessmasterprfid; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_myprogress();" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");