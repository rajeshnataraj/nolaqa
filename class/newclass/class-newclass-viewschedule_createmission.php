<?php
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : 0;
	$id=explode(",",$id);
	$scheduleid = $id[0];
	$classid = $id[1]; 
        
        
	        $qrystep1 = $ObjDB->QueryObject("SELECT count(b.fld_id) as modulecount,a.fld_id as scheduleid,a.fld_schedule_name as schedulename,a.fld_startdate as startdate,a.fld_numberofcopies as numberofcopies,a.fld_numberofrotations as numberofrotations,a.fld_rotationlength as rotationlength FROM itc_class_rotation_mission_mastertemp as a left join itc_class_rotation_schedule_mission_mappingtemp as b on b.fld_schedule_id=a.fld_id WHERE a.fld_class_id='".$classid."' AND b.fld_flag=1 AND a.fld_id='".$scheduleid."'");
		$row=$qrystep1->fetch_assoc();
		extract($row);
                
                $qrymis=$ObjDB->QueryObject("SELECT 
                                                    a.fld_id as misid,
                                                    fn_shortname(CONCAT(a.fld_mis_name, ' ', b.fld_version),
                                                            1) AS shortname,
                                                    CONCAT(a.fld_mis_name, ' ', b.fld_version) as misname,'20' As type

                                                    FROM
                                                    itc_mission_master AS a
                                                        LEFT JOIN
                                                    itc_mission_version_track AS b ON b.fld_mis_id = a.fld_id
                                                        LEFT JOIN 
                                                    itc_class_rotation_schedule_mission_mappingtemp AS c ON a.fld_id=c.fld_mission_id 
                                                    WHERE c.fld_schedule_id='".$scheduleid."' AND c.fld_flag=1 AND a.fld_delstatus='0' AND b.fld_delstatus='0' order by misname");
		
		
	
?>

<section data-type='#class-newclass' id='class-newclass-viewschedule_createmission'>
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
	
   <!-- Generation start -->
         
        <div class='row rowrotation rowspacer'>        	
        	<div class='twelve columns formBase'>     
        		<div class='row rowrotation'>       	
                	<div class='eleven columns centered insideForm'>
                    	
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
                            
                            <div class="six columns">
                            	<input type="button" class="btn" value="Generate" id="generatebtn" name="generate" onclick="fn_missiongenerate();" />
                                &nbsp;&nbsp;<input type="button" style="cursor:pointer;width:120px;text-align:center;" id=addrotimg class="btn" value="Add Rotation" onclick="fn_missionaddcolumn();" />
                                &nbsp;&nbsp;<input type="button" style="cursor:pointer;width:120px;text-align:center;" id="addmodinc" class="addmodinc btn" value="Add Mission" onclick="fn_showmission(<?php echo $scheduleid;?>);" />
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" style="cursor:pointer;width:100px;height:50px;text-align:center;" id="reset" class="reset btn" onClick="$('.rowspanone').html('&nbsp;');$('.rowspantwo').html('&nbsp;');$('.rowspanonedup').html('&nbsp;');$('.rowspantwodup').html('&nbsp;');$('#save').addClass('dim');$('div.clk').removeClass('lightrot darkrot');$('#staddbtn').removeClass('dim');$('#stsubbtn').removeClass('dim');$('#endaddbtn').removeClass('dim');$('#endsubbtn').removeClass('dim');$('#addrotimg').removeClass('dim');$('.addmodinc').removeClass('dim');" value="Reset"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" style="cursor:pointer;width:100px;height:50px;text-align:center;" id="save" onclick="fn_savemissiondetails();" class="save btn dim" value="Save"/>
                            </div>
                            <div class="five columns" style="float:right;color:#F00;width:200px;text-align:center;" id="checkseat">
                            </div>
                        </div>
                   <!-- Generation end -->
                   
                   <!-- Schedular table start -->
                         
                      	<div class='row rowrotation rowspacer'>       	                       
                            <div class="gridtableouterrot">
                                <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0">
                                    <thead>
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
                                    <tbody id="body">
                                        <?php
											$i=2;
											$k=2;
											$z=2;
											
											while($rowmission = $qrymis->fetch_assoc()) // show the module based on number of copies
											{
												extract($rowmission);
													
													for($j=1;$j<=$numberofcopies;$j++) // first for loop
													{
													?>
                                                        <tr id="tr_<?php echo $i;?>" class="<?php echo $misid."-".$type;?>">
                                                            <td id="module_<?php echo $i;?>" class="misname" onmouseover="fn_checkcellvaluemis(<?php echo $i;$i++;?>)" onmouseout="fn_checkcellvalueoutmis(this.id);"><?php echo $misname; ?></td>
                                                            <?php								    
																for($r=1;$r<=$numberofrotations;$r++) // second for loop
																{											
															?>
                                                            <td id="stu_<?php echo $z.$k;?>" style="width:205px;">
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
                                                                } // second for loop ends
                                                            ?>                                                         
                                                        </tr>
                                                	<?php
														
														$z++;
														$k=2;
													} // first for loop ends
												
													
											} // while loop ends
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
                                    </tbody>
                                </table>
                            </div>
                            
                            <script language="javascript" type="text/javascript">
                                $('#myTable05').fixedHeaderTable({ fixedColumns: 1 });
                            </script>
                           
                        </div> 
                        
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
                        <input type="hidden" name="schtype" id="schtype" value="create" /> 
                        <input type="hidden" name="stuidname" id="stuidname"/> 
                        
                        
                            </div>
                            </div>
                        </div>
                	</div>
             	</div>
     
    <!-- Schedular table end -->
   
    <!-- Student list popup start -->                     
			
                            <div class="popuptable" style="display:none;">
                                <div class="tagpopuptop"><div class="popup-closebtn" title="close" onclick="$('.popuptable').hide();"></div></div>
                                    <div class="tagpopupmid">
                                        <div class="tagPopUpInner">
                                            <table class="tagPopUpListTable">
                                                <?php
                                                         $qrystudent=$ObjDB->NonQuery("SELECT a.fld_id as id,a.fld_fname as firstname,a.fld_lname as lastname from itc_user_master as a left join itc_class_rotation_mission_student_mappingtemp as b on a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND a.fld_school_id='".$schoolid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0'");
                                                        
                                                        if($qrystudent->num_rows>0)
                                                        {
                                                        while($rowstudent=$qrystudent->fetch_assoc())
                                                        {
                                                            extract($rowstudent)
                                                    ?>
                                                    <tr>
                                                    <td><span class="tagPopUpList" onclick="fn_addstudenttotdmission(<?php echo $id;?>,'<?php echo addslashes($lastname." ".$firstname);?>');"><?php echo $lastname." ".$firstname;?></span></td>
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
        <div id="tip5" title="Add Module">
            
        </div>
    </div>
         
	<!-- Module popup End -->


<!-- Get the student id and name using javascript Array -->

<?php
		
	$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname FROM itc_user_master AS a LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
		
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

<script language="javascript" type="text/javascript">        

	$('#stuidname').val(stuname);
	
	$("#tip5").fancybox({
	'scrolling'		: 'no',
	'titleShow'		: true,
	'onClosed'		: function() {
	    $("#tip5").hide();
	}
	});
	
	$('#myTable05').fixedHeaderTable('destroy');	
	$('#myTable05').fixedHeaderTable({fixedColumn: true });
		
        $(".modhead").css({"width":"209.5px"});
        $(".misname").css({"height":"84px"});
       
        
		
</script>

</section>

 <?php
	@include("footer.php");