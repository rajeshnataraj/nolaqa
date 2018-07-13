<?php
@include("sessioncheck.php");	
$oper = isset($method['oper']) ? $method['oper'] : '';

if($oper == "loadquestion" and $oper != '')
$id= isset($method['testid']) ? $method['testid'] : '';
$schid= isset($method['schid']) ? $method['schid'] : '';
$schtype= isset($method['schtype']) ? $method['schtype'] : '';
$maxcount=isset($method['maxcount']) ? $method['maxcount'] : '';
$maxstudatmptcount=$maxcount+1;
$showquestion1 = 0;
$hidquestionorder = 1;
{ ?>
    <div id="qbank">
        <?php
		$testpasuse=$ObjDB->SelectSingleValue("SELECT fld_test_pause
		                                  FROM `itc_test_student_mapping` 
										  WHERE fld_test_id='".$id."' AND fld_student_id='".$uid."'");
		if($testpasuse == 1){
			$pause=1;
			$testdet1 = $ObjDB->QueryObject("SELECT fld_test_id as testid, fld_time as times,fld_class_id as studentclassid, 
												fld_question_ids as quesid, fld_pause_question as pausequestion
											  FROM `itc_test_pause` 
											  WHERE fld_test_id='".$id."' AND fld_student_id='".$uid."' AND fld_delstatus='0'");
											
			$row = $testdet1->fetch_assoc();
			extract($row);
			$time=explode(":",$times);
			$h=$time[0];
			$m=$time[1];
			$s=$time[2];
			
			$totalqusid = explode("~",$quesid);
			if($pausequestion==0){
				$testpasuse = 0;
				$showquestion1 = 1;
				
				/***********This is for get the question id and shuffle that id ***************/
				$quesid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_id SEPARATOR '~') AS quesid FROM `itc_question_details` AS a 
												  LEFT JOIN `itc_test_questionassign` AS b ON a.fld_id=b.`fld_question_id` 
												  WHERE b.fld_test_id='".$id."' AND a.`fld_delstatus`='0' AND b.`fld_delstatus`='0'");
				$totalqusid = explode("~",$quesid);
				shuffle($totalqusid);// the question id get shuffled 
			}
		}
		else{
			$showquestion1 = 1;
			/***********This is for get the class id for particular student ***************/
                        if($schtype ==='18' or $schtype ==='20'){
                            $studentclassid=0;
                        }
                        else{
                            $studentclassid=$ObjDB->SelectSingleValue("SELECT fld_class_id 
											  FROM `itc_test_student_mapping` 
											  WHERE fld_test_id='".$id."' AND fld_student_id='".$uid."'");
                        }
                        
											  
			$testdet = $ObjDB->QueryObject("SELECT fld_id as testid, fld_time_limit as times, fld_test_name as testname 
											  FROM `itc_test_master` 
											  WHERE fld_id='".$id."'");
			$row = $testdet->fetch_assoc();
			extract($row);
			$time=explode(":",$times);
			$h=$time[0];
			$m=$time[1];
			$pause=0;
			
			/***********This is for get the question id and shuffle that id ***************/
			$quesid=$ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(a.fld_id SEPARATOR '~') AS quesid FROM `itc_question_details` AS a 
											  LEFT JOIN `itc_test_questionassign` AS b ON a.fld_id=b.`fld_question_id` 
											  WHERE b.fld_test_id='".$id."' AND a.`fld_delstatus`='0' AND b.`fld_delstatus`='0'");
										  
			$totalqusid = explode("~",$quesid);
			shuffle($totalqusid);// the question id get shuffled 
		}
		$i=1;
		$cq='';
		for ($k=0;$k<sizeof($totalqusid);$k++)
		{ 
                    
                    $ObjDB->NonQuery("UPDATE itc_test_student_answer_track set fld_delstatus='1', fld_retake='1', fld_updated_by='".$uid."',
								fld_updated_date='".date("Y-m-d H:i:s")."' 
							where fld_test_id='".$id."' and fld_question_id='".$totalqusid[$k]."' AND fld_attempts='".$maxcount."' and fld_student_id='".$uid."'");
                       
        	$qryques = $ObjDB->QueryObject("SELECT a.fld_question AS question, a.fld_answer_type AS answertype, b.fld_order_by AS qorder 
			                               FROM `itc_question_details` AS a LEFT JOIN `itc_test_questionassign` AS b ON a.fld_id=b.`fld_question_id` 
										   WHERE b.fld_test_id='".$id."' AND a.`fld_delstatus`='0' AND b.`fld_delstatus`='0' AND a.fld_id='".$totalqusid[$k]."'"); 
     
			if($cq=='')
			{
			    $cq=	$totalqusid[$k];
			}
			else
			{
				$cq.="~".$totalqusid[$k];
			}
			
			while($rowques=$qryques->fetch_assoc())
			{
				extract($rowques);
				?>
				<div  <?php if($pausequestion == $totalqusid[$k]){?> style="display:block" <?php } else {?>style="display:none"<?php } ?> id="questionid_<?php echo $i;?>" class="<?php echo $totalqusid[$k]; ?>" >
								
							 
								<iframe class="ifrms" onload="<?php if($times!="00:00:00"){?>hideProgress()<?php } ?>"  style="display: block; height: 683px; background-color:#FFF" id="ifrm_<?php echo $i; ?>" src="assignment/assignmentengine/assignment-assignmentengine-questionsiframe.php?id=<?php echo $totalqusid[$k];?>_<?php echo $i;?>_<?php echo $pause;?>_<?php echo $studentclassid;?>_<?php echo $testid;?>" width="100%"  frameborder="0" ></iframe>
                                        <script>
					<?php if($pausequestion == $totalqusid[$k]){
						$hidquestionorder = $i;
						if($i>1){?>
							$('#previous').removeClass('dim');
							 $('#previous').removeAttr('disabled');
						<?php }?>
						$('#goto').children('#<?php echo $i;?>').attr('selected','selected');
						$('#qustionnumber').html('Question <?php echo $i;?>');
						<?php }?>
					</script>
				</div>
			   <?php
			   
			} $i++;
		}?> 
        
        <script type="text/javascript" language="javascript">
        function hideProgress()
		{			
			$('#timecount').countdown({until: '<?php echo $h;?>h +<?php echo $m;?>m +<?php echo $s;?>s', compact: true, onTick: watchCountdown});
			var periods = $('#timecount').countdown('option', {format: 'hMS'});
			
			function watchCountdown(periods) { 
			var times = periods[4]+":"+periods[5]+":"+periods[6];			
			$('#times').val(times);
				if(times == "0:0:0"){
					timeend(<?php  echo $id;?>,"timex");
				}
			}
		}
        </script>
         <input type="hidden" value="" id="currectquesid" name="currectquesid" />
        <input type="hidden" value="<?php echo $cq?>" id="hidquesids" name="hidquesids" />
        <input type="hidden" value="<?php echo $studentclassid?>" id="hidclassid" name="hidclassid" />
        <input type="hidden" id="times" name="times" />
        <input type="hidden" value="<?php echo $hidquestionorder; ?>" id="hidquestionorder" name="hidquestionorder" />
        <input type="hidden" value="<?php echo $maxstudatmptcount; ?>" id="maxstudatmptcount" name="maxstudatmptcount" /> <!-- //mohan m -->
        <input type="hidden" value="<?php echo sizeof($totalqusid); ?>" name="hidmaxquescount" id="hidmaxquescount" />
        <input type="hidden" value="<?php echo $totalqusid[$k]; ?>" name="hidquestionid" id="hidquestionid" />
     </div>
    
    <div class='row buttons' id="finishfinal" style="display:none; background-color:white; height:100%;">
        <div class="wizardReportData" style="font-size:25px;">Your test will be submited for the grading in 30 seconds.</div>
        <div style="margin-top:30px; display:table; width:100%;" ><a style="cursor:pointer; float:left;" class="btnactive" onclick="fn_showquestion();">CLICK </a><div style="float:left; width:80%; padding-left:10px;font-size:25px;">to continue working if you have submitted the assessment by mistake</div></div>
        <div style=" margin-left: -0px;; margin-top:30px;"><a style="cursor:pointer; float:left;" class="btnactive" onclick="fn_laststep(<?php echo $id?>,0,<?php echo $schid;?>,<?php echo $schtype;?>,<?php echo $studentclassid;?>);">CLICK </a>   <div style="float:left; width:75%; padding-left:10px;font-size:25px;"> to submit the assessment for grading immediately</div></div>
    </div>
    
    <script type="text/javascript" language="javascript">
        document.domain = "pitsco.com";
		<?php if($showquestion1 == 1){?>		
		$('#questionid_1').show();
		<?php } ?>
		var maxquescnt=$('#hidmaxquescount').val(); 	
		if(maxquescnt==1)
		{
			$('#next').addClass('dim');
			$('#previous').addClass('dim');
			$('#finish').removeClass('dim');
			$('#finish').removeAttr('disabled');
		}

		fn_loadnumber();
		/** this function shows next question in order one by one**/
		$('#next').click(function(){
		 var id=$('#hidquestionorder').val();		
		 $('#questionid_'+id).hide();

            document.domain = "pitsco.com";
		fn_anscheck(<?php echo $id;?>,$('#questionid_'+id).attr('class'),1,id,0,<?php echo $schid;?>,<?php echo $schtype;?>,<?php echo $maxstudatmptcount;?>);
		 var incre=parseInt(id)+1;
		 $('#questionid_'+incre).show();
		 $('#hidquestionorder').val(incre);
		 
		  $('#'+incre).attr("selected", "selected");
		  fn_loadnumber();
		if(incre>=2)
		{
			$('#previous').removeClass('dim');
			$('#previous').removeAttr('disabled');
		}
		if(incre==maxquescnt)
		{
			$('#next').addClass('dim');
			$('#finish').removeClass('dim');
			$('#finish').removeAttr('disabled');
		}
		});
		/** this function shows previous question in order one by one**/
		$('#previous').click(function(){
			var id=$('#hidquestionorder').val();
		 $('#questionid_'+id).hide();
		 fn_anscheck(<?php echo $id;?>,$('#questionid_'+id).attr('class'),1,id,0,<?php echo $schid;?>,<?php echo $schtype;?>,<?php echo $maxstudatmptcount;?>);
		 var incre=parseInt(id)-1;
		 $('#questionid_'+incre).show();
		 $('#hidquestionorder').val(incre);
		 $('#'+incre).attr("selected", "selected");
		 fn_loadnumber();
		 if(incre==1)
		{
		   $('#previous').addClass('dim');
		}
		if(incre!=maxquescnt)
		{
		    $('#next').removeClass('dim');
			$('#next').removeAttr('disabled');
		}
	 });
		/**this function to select particular qiestion**/
		$('#goto').change(function(){
		
			 $('div[id^=questionid_]').hide();
			 $('#questionid_'+$(this).val()).show();			
			 $('#hidquestionorder').val($(this).val());
			 fn_loadnumber();
			if($(this).val()>=2)
			{
				$('#previous').removeClass('dim');
				$('#previous').removeAttr('disabled');
			}
			else if($(this).val()==1)
			{
				$('#previous').addClass('dim');
			}
			
			if($(this).val()==maxquescnt)
			{
				$('#next').addClass('dim');
				$('#finish').removeClass('dim');
				$('#finish').removeAttr('disabled');
			}
			else if($(this).val()!=maxquescnt)
			{
				$('#next').removeClass('dim');
				$('#next').removeAttr('disabled');
			}
			
			});
			
			$('#goto').click(function(){
				var id = $('#goto').val();		
			 fn_anscheck(<?php echo $id;?>,$('#questionid_'+id).attr('class'),1,id,0,<?php echo $schid;?>,<?php echo $schtype;?>,<?php echo $maxstudatmptcount;?>);
			
			});
		$('#review').click(function(){
			
			 $('div[id^=questionid_]').show();
			});
			
		function fn_loadnumber(){
			var number = $('#hidquestionorder').val();
			var qnumber = "Question "+number;
			if($('#goto').children("#"+number).attr('selected')=='selected'){
			    $('#qustionnumber').html(qnumber);
			}
		}
		/**this function to pause test**/
		$('#pause').click(function(){
			fn_pausetest(<?php echo $testid;?>);
			$('#timecount').countdown('pause');
		});
		</script>
<?php 
	}

	@include("footer.php");