<?php
@include("sessioncheck.php");
$date=date("Y-m-d H:i:s");
?>
<section data-type='2home' id='reports-digitalrubricmission'>
	<script language="javascript">
    	$.getScript("reports/digitalrubricmission/reports-digitalrubricmission.js");
    </script>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Digital Rubric Report for Mission</p>
                <!--<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>-->
                <p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>
            </div>
        </div>
        
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
                                <div class="selectbox-options">
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
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $shlid;?>" onclick="fn_showclass(<?php echo $shlid;?>)"><?php echo $shlname; ?></a></li>
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
                        <div id="classdiv" style="display:none">

                        </div>
                    </div>
            	</div>
                
                <div class="row rowspacer"> 
                     <div class='six columns'> 
                        <div id="showsch" style="display:none">

                        </div>
                    </div>
                    <div class='six columns'> 
						<div id="showexp" style="display:none">

						</div>
					</div>
                </div>
				<div class="row rowspacer">
					<div class='six columns'> 
						<div id="showrub" style="display:none">

						</div>
					</div>
				</div>
                <div class="row rowspacer">
					<div class='twelve columns'> 
						<div id="studentdiv" style="display:none">

						</div>
					</div>
				</div>
                
              <?php  } else{ ?>
                  <div class="row"> 
                    <div class='six columns'> Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="<?php echo $clasid; ?>" onchange="fn_showsch(this.value);" />
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                   <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span> 
                                   <b class="caret1"></b>
                               </a>
                               <div class="selectbox-options">
                                   <input type="text" class="selectbox-filter" placeholder="Search Class">
                                   <ul role="options" style="width:100%">
                                       <?php
										if($sessmasterprfid == 7)//For school Admin 
										{
											$qry = $ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_class_id AS classid, b.fld_class_name AS classname,18 AS exptype
																			FROM itc_class_indasmission_master AS a
																			LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
																			WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND  b.fld_district_id='".$sendistid."' 
																				AND b.fld_school_id='".$schoolid."' 
																			AND b.fld_delstatus = '0'  AND b.fld_flag = '1' )
																		UNION ALL
																			(SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 19 AS exptype
																			FROM itc_class_master AS a 
																			LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON b.fld_class_id=a.fld_id   
																			WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_district_id='".$sendistid."' 
																		AND a.fld_school_id='".$schoolid."' 
																			AND a.fld_delstatus = '0'  AND a.fld_flag = '1'))AS w 
																			group by w.classid ORDER BY w.classname");
										}
										else
										{
											$qry = $ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_class_id AS classid, b.fld_class_name AS classname,18 AS exptype
																			FROM itc_class_indasmission_master AS a
																			LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
																			WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."' 
																			AND b.fld_delstatus = '0'  AND b.fld_flag = '1' )
																		UNION ALL
																			(SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 19 AS exptype
																			FROM itc_class_master AS a 
																			LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON b.fld_class_id=a.fld_id   
																			WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_created_by='".$uid."'
																			AND a.fld_delstatus = '0'  AND a.fld_flag = '1'))AS w 
																			group by w.classid ORDER BY w.classname");
										}
							
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                    ?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>"><?php echo $classname; ?></a></li>
                                                    <?php
                                                }
                                            } ?>      
                                   </ul>
                               </div>
                            </div>
                        </dl>
                    </div> <!--Shows Class code Ends here-->
                
                     <!--Show Schedule-->
                    <div class='six columns'> 
                        <div id="showsch" style="display:none">

                        </div>
                    </div>
                 <!--Show Schedule-->
            	</div>
                
               <div class="row rowspacer">
					<div class='six columns'> 
						<div id="showexp" style="display:none">

						</div>
					</div>
					 <div class='six columns'> 
						<div id="showrub" style="display:none">

						</div>
					</div>
				</div>
			<!--Show Rubric-->

			<!--Shows Student -->
				<div class="row rowspacer">
					<div class='twelve columns'> 
						<div id="studentdiv" style="display:none">

						</div>
					</div>
				</div>
                <!--Shows Student -->
             <?php } ?>
                
               <!--Shows Class/Student dropdown-->
                
            
                 <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "digitalrubricmission_"; ?>" />
                
                <!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View Report" onClick="fn_digitalrubric();" />
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");