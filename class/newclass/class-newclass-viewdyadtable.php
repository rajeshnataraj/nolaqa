<?php 
@include("sessioncheck.php");

	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
	if(isset($id[3])=="viewdyad")
	{
		$scheduleid = $id[0];
		$stype=$id[1];
		$classid = $id[2];
		$schedulename=$id[3];
		$schename=isset($id[3]);
		$export=1;
	}
	else
	{
		$scheduleid = $id[0];
		$classid = $id[1];
		$export=0;
		$schename="";
	}
	
	$dyadflag=$ObjDB->SelectSingleValueInt("select fld_dyadtableflg from itc_class_dyad_schedulemaster where fld_id='".$scheduleid."'");
	
	$qry=$ObjDB->NonQuery("SELECT fld_startdate,fld_enddate,fld_rotation,fld_stageid FROM itc_class_dyad_stagerotmapping WHERE  fld_schedule_id='".$scheduleid."' AND fld_active='1' order by fld_rotation ASC");
	
	$qrycon=$ObjDB->NonQuery("SELECT fld_id,fld_name FROM itc_class_definedyads WHERE fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
	
	$countdyad=$qry->num_rows;
	$numberofmodules=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_dyad_schedule_modulemapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
	$countrot=$ObjDB->SelectSingleValueInt("SELECT SUM(fld_numberofrotation) FROM itc_class_dyad_schedule_insstagemap WHERE fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_stagetype='3' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
	
	if($countdyad<=5)
	{
		$stagecount=$countdyad+1;
	}
	else
	{
		$stagecount=5;
	}
	
	if($schename=="viewdyad" and $dyadflag==0)
	{
	?>
    	<script>
			$.Zebra_Dialog('No records found this schedule');
			fn_dyadstage(<?php echo $scheduleid;?>);
		</script>
    <?php
	}
	
	if(($schename=="viewdyad" and $dyadflag==1) or ($schename!="viewdyad"))
	{	
	
?>
<section data-type='#class-newclass' id='class-newclass-viewdyadtable'> 
  <script>
  $.getScript("class/newclass/class-newclass-dyad.js");
  </script>
  <div class='container' id="viewschedules">
    <div class='row'>
      <div class='twelve columns'>
      	<p class="dialogTitle">Review your new class schedule</p>
        <p class="dialogSubTitleLight">Review your schedule details and rotation below. Then click "Save Instruction" to add this schedule to your class calendar.</p>        
      </div>
    </div> 
    <div class='row buttons' id="licenselist">
        <div class='row-fluid'>
          <div class='span12'>
            <div class='demoDialog'>  
              <div class='row rowspacer'>
              	<div class="popuptable" style="display:none;">
                <div class="tagpopuptop"><div class="popup-closebtn" title="close" onclick="$('.popuptable').hide();"></div></div>
                    <div class="tagpopupmid">
                        <div class="tagPopUpInner">
                            <table class="tagPopUpListTable">
                                <?php
                                         $qrystudent=$ObjDB->NonQuery("SELECT a.fld_id as id,a.fld_fname as firstname,a.fld_lname as lastname from itc_user_master as a left join itc_class_dyad_schedule_studentmapping as b on a.fld_id=b.fld_student_id where b.fld_schedule_id='".$scheduleid."' and a.fld_school_id='".$schoolid."' and b.fld_flag=1 and a.fld_activestatus='1' and a.fld_delstatus='0'");
                                        
                                        if($qrystudent->num_rows>0)
                                        {
                                        while($rowstudent=$qrystudent->fetch_assoc())
                                        {
                                            extract($rowstudent)
                                    ?>
                                    <tr>
                                    <td><span class="tagPopUpList" onclick="fn_addstudenttotddyad(<?php echo $id;?>,'<?php echo addslashes($firstname." ".$lastname);?>');"><?php echo $firstname." ".$lastname;?></span></td>
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
            
               
     <style>
	 .tbl th, .tbl td{ 
		 width:150px;
		 border: solid 1px #000;
	 }
	 </style>           
	<div class="GridViewScrollContainer">            
	<table border="1" class="tbl">
    <thead>
    <?php
		$qrystageval=$ObjDB->NonQuery("SELECT fld_id,fld_stagevalue,fld_numberofrotation FROM itc_class_dyad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' and fld_stagetype=3 AND fld_startdate<>'0000-00-00' AND fld_flag='1' GROUP BY fld_stagevalue ORDER BY fld_stagevalue ASC");
		
		if($qrystageval->num_rows>0)
		{
			$sdate='';
			$edate='';
			while($row=$qry->fetch_assoc())
			{
				extract($row);
					if($sdate=='')
					{
						$sdate=$fld_rotation."~".$fld_startdate."~".$fld_stageid;
					}
					else
					{
						$sdate.=",".$fld_rotation."~".$fld_startdate."~".$fld_stageid;
					}
					
					if($edate=='')
					{
						
						$edate=$fld_rotation."~".$fld_enddate;
					}
					else
					{
						
						$edate.=",".$fld_rotation."~".$fld_enddate;
					}
				
				
			}
    ?>

    <tr>
    <th align="center" colspan="2"><div style="width:280px;"></div></th>
    <?php
    for($i=1;$i<=$countrot;$i++)
    {
    ?>
   <th style="text-align:center;vertical-align:middle;"><div style="width:400px;height:40px; font-weight: bold; font-size:16px; padding-top:10px; ">Rotation <?php echo $i;?></div></th>
    <?php
    }
    ?>
    </tr>
	</thead>
	<tbody <?php if($schename=="viewdyad"){?> style="pointer-events: none;
" <?php } ?>>
    <?php
	 $row=1;
	 $k=1;
	 if($countdyad>0)
	 {
	 while($rowcon=$qrycon->fetch_assoc())
     {
        extract($rowcon);
	?>
    <tr>
    <td width="10%" style="width:80px;text-align:center;vertical-align:middle;font-size:20px;"><?php echo $fld_name;?></td>
    <td align="center" width="10%" style="padding-left:0px; padding-right:0px;">
    <table cellpadding="0" cellspacing="0" width="100%" style="border:none;">
    <?php
		$qrymodule=$ObjDB->NonQuery("SELECT a.fld_id as moduleid, fn_shortname(CONCAT(a.fld_module_name,' ',b.fld_version),1) AS                                           shortname, CONCAT(a.fld_module_name,' ',b.fld_version) as modulename 
							               FROM itc_module_master AS a 
							               LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
							               LEFT JOIN itc_class_dyad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
							               WHERE c.fld_schedule_id='".$scheduleid."' and c.fld_dyad_id='".$fld_id."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND                                           a.fld_delstatus='0'");
	
	
	while($rowmod=$qrymodule->fetch_assoc())
	{	
        extract($rowmod);		
	?>
    <tr>
   <td id="module<?php echo $k;?>" style="width:250px;font-size:18px;text-align:center;border:none;vertical-align:middle;height:81px; <?php if($k%2!=0){?> border-bottom:1px solid #000; <?php } ?>"><span id="<?php echo $moduleid;?>"><?php echo $modulename;?></span></td>
    </tr>
   	<?php
		$k++;
	 }
	 ?>
    </table>
    </td>
    
     <?php 
			$column=1;
			for($i=1;$i<=$countrot;$i++)
			{
		?>
         <td align="center"  style="padding:0px; vertical-align:top">
        	<table cellpadding="0" cellspacing="0" width="100%" style="border:none;">
            	<tr>
                	<td align="center" style="height:40px;border:none;border-bottom:1px solid #000; vertical-align:middle"> 
                    				<div id="seg1_<?php echo $row;?>_<?php echo $column;?>" class="dyadtop dyad row<?php echo $i;?>"></div>
                                    <div class="dyadimagetop" id="dyadimagetop_<?php echo $row;?>_<?php echo $column;?>" title="Delete"></div>
                    </td>
                </tr>
                <tr>
                    <td  align="center" style="height:40px;border:none;border-bottom:1px solid #000; vertical-align:middle">
                     		<div id="seg2_<?php echo $row;?>_<?php echo $column;?>" class="dyadbottom dyad row<?php echo $i;?>"></div>
                     		<div class="dyadimagebottom" id="dyadimagebottom_<?php echo $row; $row++;?>_<?php echo $column;?>" title="Delete"></div>
                    </td>
               </tr>
               <tr>
                	<td align="center" style="height:40px;border:none;border-bottom:1px solid #000; vertical-align:middle"> 
                    				<div id="seg1_<?php echo $row;?>_<?php echo $column;?>" class="dyadtop dyad row<?php echo $i;?>"></div>
                                    <div class="dyadimagetop" id="dyadimagetop_<?php echo $row;?>_<?php echo $column;?>" title="Delete"></div>
                    </td>
                </tr>
                <tr>
                    <td  align="center" style="height:40px;border:none; vertical-align:middle;">
                     		<div id="seg2_<?php echo $row;?>_<?php echo $column;?>" class="dyadbottom dyad row<?php echo $i;?>"></div>
                     		<div class="dyadimagebottom" id="dyadimagebottom_<?php echo $row; $row--?>_<?php echo $column; $column++;?>" title="Delete"></div>
                    </td>
               </tr>
               
            </table>
        </td>
        <?php
			}
			?>
            
	</td>
    </tr>
    <?php
		$row=$row+2;
	 }
	}
	}
	else
	{
		?>
        <tr>
        <td>No Records Found
        </td>
        </tr>
        <?php
	}
	 ?>
   </tbody>
</table>

	
    </div>
	
				<input type="hidden" name="startdate" id="startdate" value="<?php echo $startdate;?>" />
                <input type="hidden" name="tempdate" id="tempdate"/>
                <input type="hidden" name="numbarofmodules" id="numberofmodules" value="<?php echo $numberofmodules;?>" />
                <input type="hidden" name="scheduleid" id="scheduleid" value="<?php echo $scheduleid;?>" />
                <input type="hidden" name="stuidname" id="stuidname"/>
                <input type="hidden" name="studentcount" id="studentcount"/>
                <input type="hidden" name="tdval" id="tdval"/>
                <input type="hidden" name="startdate" id="dyadstartdate" value="<?php echo $sdate;?>" />
                <input type="hidden" name="enddate" id="dyadenddate" value="<?php echo $edate;?>" />
                <input type="hidden" name="countrot" id="countrot" value="<?php echo $countrot;?>" />

              </div>
              
               <?php if($export==1){
				   if($qrystageval->num_rows>0) 
							{
				   ?>
                        	<div class='row rowspacer'>
                            <div class='four columns'>&nbsp;</div>
                            <div id="save" class='four columns btn secondary yesNo'>
                                <a onclick="fn_rotationalexport(<?php echo $scheduleid.",".$stype.",".$classid.","."'".$schedulename."'";?>);">Export as csv</a>
                            </div>
                        </div>
                        <?php
							}
						}
						else
						{
						?>
               <div class="row rowspacer" style="margin-top:20px;">
                                <div class="tRight" id="modnxtstep">
                                   <input type="button" class="darkButton dyadbtn" id="btnstep" style="width:200px; height:42px;float:right;" value="Save Instruction" onclick="fn_savedyadscheduletable();"/>&nbsp;&nbsp; <input type="button" class="darkButton dyadbtn" id="btnstep" style="width:200px;margin-right:10px; height:42px;float:right;" value="Regenerate" onclick="fn_generatedyadschedule(2);"/>
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
</section>
<?php
	}
	
if(($schename=="viewdyad" and $dyadflag==1) or ($schename!="viewdyad"))
{
	if($dyadflag==0)
	{
	$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname FROM itc_user_master AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
		
		$studentcount=$qryclassstudentmap->num_rows;
?>
	<script>
    var stuname= new Array();
    var s=0;
	$('#studentcount').val(<?php echo $studentcount;?>);
    </script>
    
    <?php
    	while($rowstudent=$qryclassstudentmap->fetch_assoc())
    	{
			extract($rowstudent);
			
    ?>
			<script> 
				stuname[s]="<?php echo $fld_fname." ".$fld_lname."~".$fld_id; ?>";
				s++;
    		</script>
    <?php	
    	}
	?>
<script>	

	
	var mod=<?php echo $numberofmodules;?>;
	var rot=<?php echo $countrot;?>;
	
	$('#stuidname').val(stuname);	
	var totseats=<?php echo $numberofmodules*2;?>;
	var studentcount=<?php echo $numberofmodules*2;?>;
	var trlength=<?php echo $numberofmodules;?>;
	
	if(totseats==studentcount)
	{
		var combination="true";
	}
	
	
	if(mod!=rot)
	{	
		$('#viewschedules').hide();
		var zd=$.Zebra_Dialog(' The number of modules not equal to number of rotations. Generate this schedule ?',
								 {
								'type':     'confirmation',
								'title':    'Generate this schedule confirmation',
								'buttons':  [
												{caption: 'No', callback: function() { fn_dyadstage(<?php echo $scheduleid;?>); }},
												{caption: 'Yes', callback: function() { 
													
													$('#viewschedules').show();
													var $target = $('html,body'); 
													$target.animate({scrollTop: $target.height()}, 1000);
													zd.close();
													fn_generatedyadschedule(1);
		 }},
		]
	});
								
}
else
{
		fn_generatedyadschedule(1);
		
}
	
</script>
<?php
	}
	else
	{
		$qrycelldet=$ObjDB->QueryObject("SELECT CONCAT(b.fld_fname,' ',fld_lname) AS studentname,a.fld_cell_id AS cellid,a.fld_student_id AS studentid FROM itc_class_dyad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id  WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag=1");
		
		
		
		$studentcount=$qrycelldet->num_rows;
		
		while($rowcelldet=$qrycelldet->fetch_assoc())
		{
			extract($rowcelldet);
	?>
        	<script>$('#<?php echo $cellid;?>').html("<span id='<?php echo $studentid;?>'><?php echo $studentname;?></span>");</script>
        <?php
		}
			
			
			
			$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname FROM itc_user_master AS a LEFT JOIN itc_class_dyad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
		
		$studentcount=$qryclassstudentmap->num_rows;
?>
	<script>
    var stuname= new Array();
    var s=0;
	$('#studentcount').val(<?php echo $studentcount;?>);
    </script>
    
    <?php
    	while($rowstudent=$qryclassstudentmap->fetch_assoc())
    	{
			extract($rowstudent);
			
    ?>
			<script> 
				stuname[s]="<?php echo $fld_fname." ".$fld_lname."~".$fld_id; ?>";
				s++;
    		</script>
    <?php	
    	}
	?>
<script>

	$('#stuidname').val(stuname);	
</script>
	<?php
	}
}

	@include("footer.php");