<?php
@include("sessioncheck.php");

	$id = (isset($method['id'])) ? $method['id'] : 0;
	$id=explode(',',$id);
	
	if(isset($id[3])=="viewtriad")
	{
		$scheduleid = $id[0];
		$stype=$id[1];
		$classid = $id[2];
		$schename=isset($id[3]);
		$schedulename="";
		$export=1;
	}
	else
	{
		$scheduleid = $id[0];
		$classid = $id[1];
		$export=0;
		$schename="";
	}
	
	$triadflag=$ObjDB->SelectSingleValueInt("select fld_triadtableflg from itc_class_triad_schedulemaster where fld_id='".$scheduleid."'");
	
		$qry=$ObjDB->NonQuery("SELECT fld_startdate,fld_enddate,fld_rotation,fld_stageid FROM itc_class_triad_stagerotmapping WHERE  fld_schedule_id='".$scheduleid."' AND fld_active='1' order by fld_rotation ASC");
	
	$qrycon=$ObjDB->NonQuery("SELECT fld_id,fld_name FROM itc_class_definetriads WHERE fld_schedule_id='".$scheduleid."' AND fld_delstatus='0' ORDER BY fld_id ASC");
	
	$counttriad=$qry->num_rows;
	
	$numberofmodules=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_triad_schedule_modulemapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
	$countrot=$ObjDB->SelectSingleValueInt("SELECT SUM(fld_numberofrotation) FROM itc_class_triad_schedule_insstagemap WHERE fld_startdate<>'0000-00-00' AND fld_enddate<>'0000-00-00' AND fld_stagetype='3' AND fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
	
	
	if($schename=="viewtriad" and $triadflag==0)
	{
	?>
    	<script>
			$.Zebra_Dialog('No records found this schedule');
			fn_triadstage(<?php echo $scheduleid;?>);
		</script>
    <?php
	}
	
	if(($schename=="viewtriad" and $triadflag==1) or ($schename!="viewtriad"))
	{	
	
?>
<section data-type='#class-newclass' id='class-newclass-viewtriadtable'>
	<script language="javascript">
		$.getScript("class/newclass/class-newclass-triad.js");
	</script>	
   
    
	<div class='container'>
    	<div class='row'>
      		<div class='twelve columns'>
      			<p class="dialogTitle">Review Your New Class Schedule</p>
        		<p class="dialogSubTitleLight">Review your schedule details and rotation below. Then click "Save Instruction" to add this schedule to your class calendar.</p>
      		</div>
    	</div> 
        
         <!-- Generation start -->
         
        <div class='row rowspacer'>        	
        	<div class='twelve columns formBase'>     
        		<div class='row'>       	
                	<div class='eleven columns centered insideForm'>
                    	<div class="row"> <!-- Start and End Rotation textbox -->
                        	
                   <!-- Generation end -->
                   
                   <!-- Schedular table start -->
                         
                      	<div class='row rowspacer'>       	                       
                            <div class="gridtableouter" style="width:850px;">
    <table class="fancyTable" id="myTable05" cellpadding="0" cellspacing="0">
    <thead>
    
    <?php
	$qrystageval=$ObjDB->NonQuery("SELECT fld_stagevalue,fld_id,fld_numberofrotation FROM itc_class_triad_schedule_insstagemap WHERE fld_schedule_id='".$scheduleid."' and fld_stagetype=3 AND fld_startdate<>'0000-00-00' AND fld_flag='1' GROUP BY fld_stagevalue ORDER BY fld_stagevalue ASC");
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
        <th>Modules</th>
    <?php
    for($i=1;$i<=$countrot;$i++)
    {
    ?>
   <th style="font-size:24px;" ><span style="font-size:14px;vertical-align:top;">Rotation <?php echo $i;?></span></th>
    <?php
    }
    ?>
    </tr>
    </thead>
   <tbody <?php if($schename=="viewtriad"){?> style="pointer-events: none;
" <?php } ?>>
    <?php
	 $row=1;
	 $i=1;
         $k=1;
         $z=1;
         $t=1;
   if($counttriad>0)
   {

     while($rowcon=$qrycon->fetch_assoc())
     {
        
        extract($rowcon);
	?>
 
    <?php                                         
		$qrymodule=$ObjDB->NonQuery("SELECT a.fld_id AS moduleid, CONCAT(a.fld_module_name,' ',b.fld_version) AS modulename,fn_shortname (CONCAT(a.fld_module_name, ' ', fld_version), 1) AS shortname 
FROM itc_module_master AS a
LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id 
LEFT JOIN itc_class_triad_schedule_modulemapping AS c ON a.fld_id=c.fld_module_id 
WHERE c.fld_schedule_id='".$scheduleid."' AND c.fld_triad_id='".$fld_id."' AND c.fld_flag=1 AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
		
	while($rowmod=$qrymodule->fetch_assoc())
	{
           
        extract($rowmod);		
	?>
       <tr>
    
           <td id="module<?php echo $t;?>" class="module"><span id="<?php echo $moduleid;?>" class="tooltip module<?php echo $t;?>" title="<?php echo $modulename; ?>"><?php echo $shortname." / ".$fld_name;?></span></td>
   <?php								    
            for($r=1;$r<=$countrot;$r++) // second for loop
            {											
   ?>
                <td id="stu_<?php echo $z.$k;?>">
                    <div class="triadtop triad row<?php echo $r;?>" id="seg1_<?php echo $z;?>_<?php echo $k;?>"></div>
                    <div class="triadimagetop" id="triadimagetop_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                    <div class="triadbottom triad row<?php echo $r;?>" id="seg2_<?php echo $z;?>_<?php echo $k;?>"></div>
                     <div class="triadimagebottom" id="triadimagebottom_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                </td>
        <?php
              $k++;	
            } // second for loop ends
        ?>
                                                      
             </tr>
           <?php
		$z++;
		$k=1;
                $t++;
														
             } // module loop end
	} // triad loop end
	
       	

        }} // if loop end
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
	
   
 
         
         <script language="javascript" type="text/javascript">
                                $('#myTable05').fixedHeaderTable({ fixedColumns: 1});
                            </script>


		 </div>

		
                <input type="hidden" name="startdate" id="startdate" value="<?php echo $startdate;?>" />
                <input type="hidden" name="tempdate" id="tempdate"/>
                <input type="hidden" name="numbarofmodules" id="numberofmodules" value="<?php echo $numberofmodules;?>" />
                <input type="hidden" name="scheduleid" id="scheduleid" value="<?php echo $scheduleid;?>" />
                <input type="hidden" name="stuidname" id="stuidname"/>
                <input type="hidden" name="studentcount" id="studentcount"/>
                <input type="hidden" name="tdval" id="tdval"/>
                <input type="hidden" name="startdate" id="triadstartdate" value="<?php echo $sdate;?>" />
                <input type="hidden" name="enddate" id="triadenddate" value="<?php echo $edate;?>" /> 
                <input type="hidden" name="rotations" id="rotations" value="<?php echo $countrot;?>" />
                <input type="hidden" name="countrot" id="countrot" value="<?php echo $countrot;?>" />
                        
                        
                        <?php if($export==1)
                              {
                                if($qrystageval->num_rows>0) 
                                    {
				   ?>
                                        <div class='row rowspacer'>
                                            <div class='four columns'>&nbsp;</div>
                                            <div id="save" class='four columns btn secondary yesNo'>
                                            <a onclick="fn_rotationalexport(<?php echo  $scheduleid.",".$stype.",".$classid.","."'".$schedulename."'";?>);">Export as csv</a>
                                            </div>
                                        </div>
                                   <?php
                                    }
                              }
                              else
                              {
                              ?>
                             
                        <div class='row rowspacer'>
                            <div class="four columns btn primary push_two noYes">
                                <a onClick="fn_generatetriadscheduleajax(<?php echo $numberofmodules;?>);">Regenerate</a>
                            </div>
                            <div id="save" class='four columns btn secondary yesNo'>
                                <a onclick="fn_savetriadscheduletable();">Save Instruction</a>
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
     
    <!-- Schedular table end -->
   
    <!-- Student list popup start -->                     
			
                            <div class="popuptable" style="display:none;">
                <div class="tagpopuptop"><div class="popup-closebtn" title="close" onclick="$('.popuptable').hide();"></div></div>
                    <div class="tagpopupmid">
                        <div class="tagPopUpInner">
                            <table class="tagPopUpListTable">
                                <?php
                                         $qrystudent=$ObjDB->NonQuery("SELECT a.fld_id as id,a.fld_fname as firstname,a.fld_lname as lastname from itc_user_master as a left join itc_class_triad_schedule_studentmapping as b on a.fld_id=b.fld_student_id where b.fld_schedule_id='".$scheduleid."' and a.fld_school_id='".$schoolid."' and b.fld_flag=1 and a.fld_activestatus='1' and a.fld_delstatus='0'");
                                        
                                        if($qrystudent->num_rows>0)
                                        {
                                        while($rowstudent=$qrystudent->fetch_assoc())
                                        {
                                            extract($rowstudent)
                                    ?>
                                    <tr>
                                    <td><span class="tagPopUpList" onclick="fn_addstudenttotdtriad(<?php echo $id;?>,'<?php echo addslashes($firstname." ".$lastname);?>');"><?php echo $firstname." ".$lastname;?></span></td>
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
                        </div>
                                                     
                            <!-- Student list popup end --> 
    
   



<script language="javascript" type="text/javascript">	
	$('#myTable05').fixedHeaderTable('destroy');	
	$('#myTable05').fixedHeaderTable({fixedColumn: true });
        setTimeout('$(".module").css({"height":"40px"});',1000);
        
		
</script>
</section>
<?php
	}
	
if(($schename=="viewtriad" and $triadflag==1) or ($schename!="viewtriad"))
{	
	if($triadflag==0)
	{
	$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname FROM itc_user_master AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
		
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
	function arraymove(arr, fromIndex, toIndex) {
    element = arr[fromIndex];
    arr.splice(fromIndex, 1);
    arr.splice(toIndex, 0, element);
}
	
	var mod=<?php echo $numberofmodules;?>;
	var rot=<?php echo $countrot;?>;
	
	$('#stuidname').val(stuname);	
	var totseats=<?php echo $numberofmodules*2;?>;
	var studentcount=totseats;
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
												{caption: 'No', callback: function() { fn_triadstage(<?php echo $scheduleid;?>); }},
												{caption: 'Yes', callback: function() { 
													
													$('#viewschedules').show();
													var $target = $('html,body'); 
													$target.animate({scrollTop: $target.height()}, 1000);
													zd.close();
													fn_generatetriadscheduleajax(<?php echo $numberofmodules;?>);
													
		
		
		 }},
		]
	});
								
}
else
{
		
	fn_generatetriadscheduleajax(<?php echo $numberofmodules;?>);	
}
	

</script>
<?php
	}
	else
	{
		$qrycelldet=$ObjDB->QueryObject("SELECT CONCAT(b.fld_fname,' ',fld_lname) AS studentname,a.fld_cell_id AS cellid,a.fld_student_id AS studentid FROM itc_class_triad_schedulegriddet AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id  WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$scheduleid."' AND a.fld_flag=1");
		
		
		
		$studentcount=$qrycelldet->num_rows;
		
		while($rowcelldet=$qrycelldet->fetch_assoc())
		{
			extract($rowcelldet);
	?>
        	<script>$('#<?php echo $cellid;?>').html("<span id='<?php echo $studentid;?>'><?php echo $studentname;?></span>");</script>
        <?php
		}
			
			
			
			$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_fname, a.fld_lname FROM itc_user_master AS a LEFT JOIN itc_class_triad_schedule_studentmapping AS b ON a.fld_id=b.fld_student_id WHERE b.fld_schedule_id='".$scheduleid."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ");
		
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