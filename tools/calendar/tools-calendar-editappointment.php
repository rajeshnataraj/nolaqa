<?php 
	@include("sessioncheck.php");
	$btncancel= "fn_cancel('tools-calendar-calendar')";
	$eventid=isset($method['eventid']) ? $method['eventid'] : '';
	$type=isset($method['type']) ? $method['type'] : '';
	$pagetype= $type;
	
	if($eventid==0 || $eventid=="undefined")
	{
		$title="Add Event";
		$subtitle="Add the details of your appointment.";
		$savebutton="Save Appointment";
		$eventname="";
		$startampm="";
		$startdate="";
		$enddate="";
		$starttime="";
		$endtime="";
	}
	else
	{
		$title="Edit Event";
		$subtitle="Edit the details of your appointment below.";
		$savebutton="Update Appointment";
		
		
		$eventqry=$ObjDB->QueryObject("SELECT fld_app_name AS eventname, fld_startdate AS startdate,fld_enddate AS enddate, fld_starttime AS                                      starttime,fld_endtime AS endtime 
                                      FROM itc_calendar_master WHERE fld_delstatus='0' AND fld_id='".$eventid."'");
									  
		while($resevent = $eventqry->fetch_assoc()){			
			extract($resevent);
			$eventname=$eventname;
			$startampm=date('H',strtotime($starttime));
			
			$startdate=date('m/d/Y',strtotime($startdate));
			$enddate=date('m/d/Y',strtotime($enddate));
			$starttime=date('H:i',strtotime($starttime));
			$endtime=date('H:i',strtotime($endtime));
		}
	}
	?>
<script type="text/javascript">
	$.getScript("tools/calendar/tools-calendar-calendarjs.js");
</script>
<section data-type='#tools-calendar' id='tools-calendar-editappointment'>
    <div class='container'>
    <div class="row">
         <div class="span10">
            	  <p class="dialogTitle"><?php echo $title;?></p>
                  <p class="dialogSubTitleLight"><?php echo $subtitle;?></p>
         </div>
           <div class='row formBase rowspacer'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <form id="eventform" name="eventform">
                            <div class='row'>
                                  <div class='six columns'>
                                  Appointment Title<span class="fldreq">*</span> 
                                        <dl class='field row' style="width:858px">
                                          <dt class='text'>
                                            <input placeholder='Appointment Title' required='' type='text' id="eventtitle" name="eventtitle" value="<?php echo $eventname;?>">
                                          </dt>
                                        </dl>      
                                    </div>
                                </div>
                                <div class='row'>
                                     <div class='three columns'>
                                     Start Date<span class="fldreq">*</span> 
                                        <dl class='field row'>
                                            <dt class='text'>
                                       <input  id="startdate" name="startdate" class="quantity" placeholder='Start Date'type='text'  readonly="readonly" value="<?php echo $startdate;?>" >
                                            </dt>                                        
                                        </dl>
                                    </div>                                
                                    <div class='three columns'>
                                    Start Time<span class="fldreq">*</span> 
                                        <dl class='field row'>
                                            <dt class='text'>
                                                  <input placeholder='Start Time' required='' type='text' readonly="readonly" id="starttime" name="starttime" value="<?php echo $starttime;?>">
                                            </dt>                                        
                                        </dl>
                                    </div>
                                    <div class='three columns'>
                                    End Date<span class="fldreq">*</span> 
                                        <dl class='field row'>
                                            <dt class='text'>
                                              <input placeholder='End Date' required='' type='text' id="enddate" name="enddate" readonly="readonly" value="<?php echo $enddate;?>">
                                            </dt>                                        
                                        </dl>
                                    </div>
                                    <div class='three columns'>
                                    End Time<span class="fldreq">*</span> 
                                        <dl class='field row'>
                                            <dt class='text'>
                                              <input placeholder='End Time' required='' type='text' id="endtime" name="endtime" readonly="readonly" value="<?php echo $endtime;?>">
                                            </dt>                                        
                                        </dl>
                                    </div>
                                </div>
                                <div class="row" style="margin-top:20px;">
                                    <div class="tRight">
                                        <input type="button" id="btnstep" class="darkButton" style="width:200px; height:32px;float:right;margin:5px;" value="<?php echo $savebutton;?>"onClick="fn_saveevent(<?php echo $eventid;?>,<?php echo $pagetype;?>);" />
                                        <?php if($eventid!=0 || $eventid!="undefined"){?> 
                                        <input  type="button" id="delbutton"  class="darkButton"style="width:200px; height:32px;float:right; margin:5px;" value="Delete Appointment" onClick="fn_delete(<?php echo $eventid?>)"/>
                                        <?php }?>
                                        <input  type="button" id="btnstep"  class="darkButton" style="width:150px; height:32px;float:right; margin:5px;" value="Cancel" onClick="<?php echo $btncancel;?>" />
                                    </div>
                                 </div>
   						 </form>
                	</div>
                </div>
            </div>
            </div>
    </div>
</section>
<script type="text/javascript" language="javascript">
	$("#starttime").timepicker({
		 onSelect: function(dateText,inst)
		 {
             $(this).parents().parents().removeClass('error');
         },
		  hours: 
		 {
                starts: 0,   // first displayed hour
                ends: 23    // last displayed hour
          },
			
		 }
		);
	
	$("#endtime").timepicker({
		 onSelect: function(dateText,inst)
		 {
             $(this).parents().parents().removeClass('error');
         },
		 hours: 
		 {
                starts: 0,                  // first displayed hour
                ends: 23                    // last displayed hour
          },
			
		    }
		  );

        $( "#startdate" ).datepicker( {
			 minDate: '-currentdate',
            onSelect: function(selected){
			 $("#enddate").datepicker("option","minDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
		$( "#enddate" ).datepicker( {
			minDate: '-currentdate',
            onSelect: function(selected){
				$("#startdate").datepicker("option","maxDate", selected);
             $(this).parents().parents().removeClass('error');
            }
          }
        );
        $(function(){
            $("#eventform").validate({
                	ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));
						error.addClass('msg'); 		
				},
                rules: { 
					
					eventtitle: { required: true },
					startdate: { required: true  },
					starttime: { required: true  },
					enddate: { required: true, greaterThan: "#startdate" },
					endtime: { required: true, greaterThan: "#starttime" }
				}, 
                messages: { 
					eventtitle:{  required:  "please enter appointment title"},                
					startdate:{  required: "Select the start date" },		  
					starttime:{ required:  "Select the start time" },
					enddate: {   required: "Select the end date", greaterThan: "Enddate must be greater" },
					endtime: {   required: "Select the end time", greaterThan: "Endtime must be greater" }
                },
                highlight: function(element, errorClass, validClass) {
					$(element).parent('dl').addClass(errorClass);
					$(element).addClass(errorClass).removeClass(validClass);
				},
				unhighlight: function(element, errorClass, validClass) {
					if($(element).attr('class') == 'error'){
							$(element).parents('dl').removeClass(errorClass);
							$(element).removeClass(errorClass).addClass(validClass);
					}
				},
                onkeyup: false,
                onblur: true
              });
            });	
	
</script>
<?php
	@include("footer.php");
	
                               
