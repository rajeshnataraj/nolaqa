<?php
	@include("sessioncheck.php");
       
	$id = isset($method['id']) ? $method['id'] : 0;
	$id=explode(",",$id);
	$viewname=isset($id[3]);
	
	if(isset($id[3])=="viewrot")
	{
		$scheduleid = $id[0];
		$classid = $id[2];
		$schename="viewrot";
	}
	else
	{
		$scheduleid = $id[0];
		$classid = $id[1];
		$schename="";
	}
	
	        $startrotation=1;
        
		if($startrotation=='')
		{
			$startrotation=1;
		}
	
		$qrymission=$ObjDB->QueryObject("SELECT fld_mission_id as misid,'20' as type,fld_numberofrotation as numberofrotations FROM itc_class_rotation_missiondet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag=1 order by fld_row_id ASC");
		
		$numberofrotations=$ObjDB->SelectSingleValueInt("SELECT fld_numberofrotation FROM itc_class_rotation_missiondet WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_flag=1");
		
		$qrygetscheduledet=$ObjDB->QueryObject("SELECT fld_flag,fld_numberofcopies as numberofcopies,fld_startdate as startdate,fld_rotationlength as rotationlength FROM itc_class_rotation_mission_mastertemp WHERE fld_id='".$scheduleid."'");
		
		$row=$qrygetscheduledet->fetch_assoc();
		
		extract($row);
		
		
?>

<section data-type='#class-newclass' id='class-newclass-viewschedule_editmission'>
	<script language="javascript">
		$.getScript("class/newclass/class-newclass-rotationalschedule.js");
		$.getScript("class/newclass/class-newclass-missionschedule.js");
		$('#scheduleinfo').removeClass("active-first");
		$('#schedulestud').removeClass("active-mid");
		$('#schedulecon').removeClass("active-mid");
		$('#schedulereview').parents().removeClass("dim");
		$('#schedulereview').addClass("active-last");
                 $('.gridtableouterrot').css({ 'width':$('body').width()-110});
	</script>
        <style>
	
.ui-state-active
{
    display:block;
    width:200px;
    height:20px;
    border: 1px solid;
}

.ui-state-hover
{
    display:block;
    width:200px;
    height:22px;
    border: 1px solid;
}

.ui-state-hover {
background:lightyellow;
}
.ui-state-active {
background:lightgray;
}
        </style>	
    
    
	<div class='container'>
    	<div class='row rowrotation'>
      		<div class='twelve columns'>
      			<p class="dialogTitle">Review Your New Class Schedule</p>
        		<p class="dialogSubTitleLight">Review your schedule details and rotation below. <?php if(isset($id[3])=="viewrot"){?> Then click "Export as csv" rotational table export as csv format.<?php } else { ?>Then click "Save schedule" to
         add this schedule to your class calendar.<?php } ?></p>
      		</div>
    	</div> 
        
        <div class='row rowrotation rowspacer'>        	
        	<div class='twelve columns formBase'>     
        		<div class='row rowrotation'>       	
                	<div class='eleven columns centered insideForm'>
                    	<?php
							if(isset($id[3])!="viewrot")
							{
							?>
                    	<span class="wizardReportdata">Generation Rule:</span>
                    	<div class="row rowrotation"> <!-- Start and End Rotation textbox -->
                        	<div class="two columns">
                            	<span class="wizardReportDesc">Start Rotation:</span><br />
                                <input type="button" name="staddbtn" id="staddbtn" class="btn sm dim" value="+"  onclick="fn_increment('startrotation')" />
                                <input type="text" name="startrotation" id="startrotation" class="ques-input qit-small" value="1" readonly/> 
                                <input type="button" name="stsubbtn" id="stsubbtn"  class="btn sm dim"  onclick="fn_decrement('startrotation')" value="-" />
                            </div>
                            <div class="two columns">
                            	<span class="wizardReportDesc">End Rotation</span><br />
                            	<input type="button" name="endaddbtn" id="endaddbtn" class="btn sm dim" value="+" onclick="fn_increment('endrotation')"/>
                                <input type="text" name="endrotation" id="endrotation" class="ques-input qit-small" value="<?php echo $numberofrotations;?>" readonly/> 
                                <input type="button" name="endsubbtn" id="endsubbtn" class="btn sm dim" value="-"  onclick="fn_decrement('endrotation')"/>
                            </div>
                            
                             <div class="two columns">
                             <div style="margin-left:30px;">
                                 <span class="wizardReportDesc">Do you want to keep the same group of 4 students in each mission rotation ?</span></br>
                                 Yes <input type="radio" name="group" id="group" value="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No <input type="radio" id="group" name="group" value="0" checked="checked">
                              </div>
                            </div>
                            
                            <div class="six columns <?php if($id[3]=="viewrot"){?> dim <?php } ?>">
                            	<input type="button" class="btn" value="Generate" id="generatebtn" name="generate" onclick="fn_missiongenerate();" />
                                &nbsp;&nbsp;<input type="button" style="cursor:pointer;width:120px;text-align:center;" id=addrotimg class="btn" value="Add Rotation" onclick="fn_missionaddcolumn();" />
                                &nbsp;&nbsp;<input type="button" style="cursor:pointer;width:120px;text-align:center;" id="addmodinc" class="addmodinc btn" value="Add Mission" onclick="fn_showmission(<?php echo $scheduleid;?>);" />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" style="cursor:pointer;width:100px;height:50px;text-align:center;" id="reset" class="reset btn" onClick="$('.rowspanone').html('&nbsp;');$('.rowspantwo').html('&nbsp;');$('.rowspanonedup').html('&nbsp;'); $('.rowspantwodup').html('&nbsp;');fn_reset(); $('#save').addClass('dim');$('div.clk').removeClass('lightrot darkrot');$('#staddbtn').removeClass('dim');$('#stsubbtn').removeClass('dim');$('#endaddbtn').removeClass('dim');$('#endsubbtn').removeClass('dim');$('#addrotimg').removeClass('dim');$('.addmodinc').removeClass('dim');" value="Reset"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" style="cursor:pointer;width:100px;height:50px;text-align:center;" id="save" onclick="fn_savemissiondetails();" class="save btn dim" value="Save"/>
                            </div>
                            <div class="five columns" style="float:right;color:#F00;width:200px;text-align:center;" id="checkseat">
                            </div>
                        </div>
                        <?php
							}
							
							if($qrymission->num_rows>0)
							{
							?>
                        
                      	<div class='row rowrotation rowspacer'>       	                       
                            <div class="gridtableouterrot">
                                <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0">
                                    <thead <?php if(isset($id[3])=="viewrot"){?> style="pointer-events: none;
" <?php } ?>>
                                        <tr>
                                            <th class="modhead">Missions</th>
                                             <?php
												for($i=1;$i<=$numberofrotations;$i++)  // Show the number of rotation
												{
											?>
												<th class="modhead" <?php if($i==$numberofrotations){ ?>style="cursor:pointer;" title="Remove rotation" onclick="fn_missionremovecolumn();" <?php } ?>><span style="font-size:14px;vertical-align:top;">rotation <?php echo $i; ?></span></th>
											<?php
												}
												
											?>	
                                        </tr>
                                    </thead>
                                    <tbody id="body" <?php if(isset($id[3])=="viewrot"){?> style="pointer-events: none;
" <?php } ?>>
                                        <?php
											$i=2;
											$k=2;
											$z=2;
											
											while($rowmodule = $qrymission->fetch_assoc()) // show the module based on number of copies
											{
												extract($rowmodule);
												
													?>
													<tr id="tr_<?php echo $i;?>" class="<?php echo $misid."-".$type;?>">
														<td id="module_<?php echo $i;?>" class="misname" style="cursor:default;" <?php if(isset($id[3])!="viewrot"){ ?> onmouseover="fn_checkcellvaluemis(<?php echo $i;$i++;?>)" <?php } ?>><?php 
			
					echo $ObjDB->SelectSingleValue(" SELECT 
                                                    CONCAT(a.fld_mis_name, ' ', b.fld_version) 
                                                    FROM
                                                    itc_mission_master AS a
                                                        LEFT JOIN
                                                    itc_mission_version_track AS b ON b.fld_mis_id = '".$misid."'
                                                    WHERE a.fld_id='".$misid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                                        
                                                    ?></td>				
															<?php								    
																for($r=1;$r<=$numberofrotations;$r++)
																{											
															?>
															<td id="stu_<?php echo $z.$k;?>"  style="background: #FFFFFF;width:205px;">  
                                                                                                                            <div class="rowspanone clk row<?php echo $k;?>" id="seg1_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
                                                                 <div class="imagetop" id="imagetop_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                                                                 
																<div class="rowspantwo clk row<?php echo $k;?>" id="seg2_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
                                                                <div class="imagebottom" id="imagebottom_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                                                                
                                                                                                                             <div class="rowspanonedup clk row<?php echo $k;?>" id="seg3_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
                                                                 <div class="imagetopdup" id="imagetopdup_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                                                                 
																<div class="rowspantwodup clk row<?php echo $k;?>" id="seg4_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
                                                                <div class="imagebottomdup" id="imagebottomdup_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
															</td>                                       
															<?php
																	$k++;
																	
																} // for loop end
																
																$z++;
																$k=2;
															?>
													</tr>                                	                                	
													<?php
												
											} // while loop end
											 if(isset($id[3])!="viewrot"){
										?>
                                             <tr id="addmod" style="display:none;">
                                            <td style="display:none;">
                                            	
                                            </td>
                                            <?php
                                            for($r=1;$r<=$numberofrotations;$r++)
                                            {
                                            ?>
                                            	<td></td>
                                            <?php
                                            }
                                            ?>
                                        </tr>
                                        	<?php
											 }
											?>
                                        
                                    </tbody>
                                </table>
                                
                                <script language="javascript" type="text/javascript">
	
									
									
									$("#tip5").fancybox({
									'scrolling'		: 'no',
									'titleShow'		: false,
									'onClosed'		: function() {
										$("#tip5").hide();
									}
									});
												
												
								</script>
                            </div>
                            
                            <script language="javascript" type="text/javascript">
                                $('#myTable05').fixedHeaderTable({ fixedColumns: 1 });
                            </script>
                        </div>
                        
                        <?php
							}
							else
							{
							?>
                            <div class='row'>
                                <div class='twelve columns'>
									No records found
                                </div>
                            </div> 	
                            <?php
							}
							?>
                        
                        <input type="hidden" name="tdval" id="tdval" />
                        <input type="hidden" name="start_date" id="start_date" value="<?php echo $startdate;?>"/>
                        <input type="hidden" name="tempdate" id="tempdate"/>
                        <input type="hidden" name="rotationlength" id="rotationlength" value="<?php echo $rotationlength;?>"/>
                        <input type="hidden" name="noofrotation" id="noofrotation" value="<?php echo $numberofrotations;?>" />
                        <input type="hidden" name="numberofcopies" id="numberofcopies" value="<?php echo $numberofcopies;?>" />
                        <input type="hidden" name="noofmodules" id="noofmodules" value="<?php echo $modulecount;?>" />
                        <input type="hidden" name="studentcount" id="studentcount"/>
                        <input type="hidden" name="classid" id="classid" value="<?php echo $classid; ?>" />
                        <input type="hidden" name="scheduleid" id="scheduleid" value="<?php echo $scheduleid; ?>" />
                        <input type="hidden" name="moduletype" id="moduletype" value="<?php echo 20; ?>" />
                        <input type="hidden" name="schtype" id="schtype" value="edit" /> 
                        <input type="hidden" name="stuidname" id="stuidname"/>
                        <input type="hidden" name="modplaytrackrot" id="modplaytrackrot" value="<?php echo $startrotation;?>"/>
                        
                        <?php if(isset($id[3])=="viewrot" and $qrymission->num_rows>0){?>
                        	<div class='row rowspacer'>
                            <div class='four columns'>&nbsp;</div>
                            <div id="save" class='four columns btn secondary yesNo'>
                                <a onclick="fn_rotationalexport(<?php echo $id[0].",".$id[1].",".$id[2].","."'".$id[3]."'";?>);">Export as csv</a>
                            </div>
                        </div>
                        <?php
						}
						
						?>
                            </div>
                            </div>
                        </div>
                	</div>
             	</div>
   
   <!-- Student list popup start -->                     
			
    <div class="popuptable" style="display:none;">
        <div class="tagpopuptop"><div class="popup-closebtn" title="close" onclick="$('.popuptable').hide();"></div></div>
            <div class="tagpopupmid">
                <div class="tagPopUpInner">
                    <table class="tagPopUpListTable">
                        <?php
                                $qrystudent=$ObjDB->NonQuery("SELECT a.fld_id as id,a.fld_fname as firstname,a.fld_lname as lastname 
								           FROM itc_user_master as a 
										   LEFT JOIN itc_class_rotation_mission_student_mappingtemp as b on a.fld_id=b.fld_student_id 
										   WHERE b.fld_schedule_id='".$scheduleid."' and a.fld_school_id='".$schoolid."' and b.fld_flag=1 and a.fld_activestatus='1' and a.fld_delstatus='0'");
                                
                                if($qrystudent->num_rows>0)
                                {
                                while($rowstudent=$qrystudent->fetch_assoc())
                                {
                                    extract($rowstudent)
                            ?>
                            <tr>
                            <td><span class="tagPopUpList" onclick="fn_addstudenttotdmission(<?php echo $id;?>,'<?php echo addslashes($lastname." ".$firstname);?>');"><?php echo ($lastname." ".$firstname);?></span></td>
                            </tr>
                            <?php
                                }
                                }
                                else
                                {
                                ?>
                                 <tr>
                                <td><span class="tagPopUpList">No Records</span></td>
                                </tr>
                                <?php
                                }
                                ?>
                    </table>
                </div>
            </div>
        <div class="tagpopupbot"></div>
    </div>
                             
    <!-- Student list popup end --> 
    
    <!-- Module popup start -->
         	
    <div style="display: none;">
        <div id="tip5" style="width:400px;height:300px;">
            
        </div>
    </div>
         
	<!-- Module popup End -->
    
    <?php
	$qrycelldet=$ObjDB->QueryObject("SELECT CONCAT(b.fld_lname,' ',b.fld_fname) AS studentname,a.fld_cell_id AS cellid,a.fld_student_id AS studentid 
	            FROM itc_class_rotation_mission_schedulegriddet AS a 
				LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id  
				WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag=1 AND b.fld_delstatus='0'");
	
	while($rowcelldet=$qrycelldet->fetch_assoc())
	{
		extract($rowcelldet);
	?>
		<script>$('#<?php echo $cellid;?>').html("<span id='<?php echo $studentid;?>'><?php echo $studentname;?></span>");</script>
	<?php
	}
	
	$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname 
	                    FROM itc_user_master AS a 
						LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_id=b.fld_student_id 
						WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
	$studentcount=0;	
	$studentcount=$qryclassstudentmap->num_rows;
?>
	<script language="javascript" type="text/javascript">
    	var stuname= new Array();
    	var s=0;
		$('#studentcount').val(<?php echo $studentcount;?>);
    </script>
    
    <?php
    	while($rowstudent=$qryclassstudentmap->fetch_assoc())
    	{
			extract($rowstudent);		
    ?>
			<script language="javascript" type="text/javascript"> 
				stuname[s]="<?php echo $fld_lname." ".$fld_fname."~".$fld_id; ?>";
				s++;
    		</script>
    <?php	
    	}
	?>
	<script>
        fn_missionchecking();
       
    	$('#stuidname').val(stuname);
		
			$('#myTable05').fixedHeaderTable('destroy');	
			$('#myTable05').fixedHeaderTable({fixedColumn: true });
			
			function fn_reset()
			{
				 <?php
				$qrycelldet=$ObjDB->QueryObject("SELECT CONCAT(b.fld_lname,' ',b.fld_fname) AS studentname,a.fld_cell_id AS cellid,a.fld_student_id AS studentid  FROM itc_class_rotation_mission_schedulegriddet AS a 
				            LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id  
							WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag=1 AND b.fld_delstatus='0'");
				
				
				while($rowcelldet=$qrycelldet->fetch_assoc())
				{
					extract($rowcelldet);
				?>
					$('#<?php echo $cellid;?>').html("<span id='<?php echo $studentid;?>'><?php echo $studentname;?></span>");
				<?php
				}
				?>
                                
			}
		
		$('#myTable05').fixedHeaderTable('destroy');	
                $('#myTable05').fixedHeaderTable({fixedColumn: true });
                $(".modhead").css({"width":"209.5px"});
                $(".misname").css({"height":"84px"});
        
                
        
    </script>
</section>
<?php

	@include("footer.php");