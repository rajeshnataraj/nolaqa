<?php 
	@include("sessioncheck.php");
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
	$id = explode(',',$id);

	$activityassignqry=$ObjDB->QueryObject("SELECT a.fld_id, a.fld_student_id AS studentid, a.fld_class_id AS assignclassid, a.fld_start_date AS startdate, 
	                                              a.fld_end_date AS enddate, b.fld_class_name AS classname,CONCAT(c.fld_fname,' ',c.fld_lname) AS studentname
										   FROM itc_activity_student_mapping AS a
										   LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
										   LEFT JOIN itc_user_master AS c ON c.fld_id=a.fld_student_id
										   WHERE a.fld_activity_id='".$id[0]."' AND a.fld_flag='1' AND a.fld_created_by='".$uid."' 
										   ORDER BY a.fld_start_date");
?>
<script>
	$('#tablecontents8').slimscroll({
		height:'auto',
		size: '3px',
		railVisible: false,
		allowPageScroll: false,
		railColor: '#F4F4F4',
		opacity: 9,
		color: '#88ABC2',
	});
</script>
<section data-type='#library-activities' id='library-activities-assign'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Assign Activity</p>
                <p class="dialogSubTitle">Assign this activity to students or classes below.</p>     
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="list">
                <table class='table table-hover table-striped table-bordered setbordertopradius'>
                    <thead class='tableHeadText'>
                        <tr style="cursor:default">
                            <th style="width:30%">Assignee</th>
                            <th style="width:35%" class='centerText'>Class</th>
                            <th style="width:15%" class='centerText'>Assigned Date</th>
                            <th style="width:15%" class='centerText'>Due Date</th>
                            <th style="width:5%" class='centerText'>Remove</th>
                        </tr>
                        <tr class="mainBtn" id="btnlibrary-activities-addstudents" name='<?php echo $id[0].",0";?>'>
                            <td colspan="5"><span class="icon-synergy-add-dark small-icon" style="float:left"></span><span style=" float:left; margin:10px 0px 0px 10px; font-weight:bold; font-style:italic;">Assign this activity</span></td>
                        </tr>
                    </thead>
                </table>
                <div style="max-height:400px;width:100%;" id="tablecontents8" >
                    <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                    		<?php
							if($activityassignqry->num_rows>0){
								while($rowactivityassignqry = $activityassignqry->fetch_assoc())
								{
									extract($rowactivityassignqry);
									?>
									<tr id="<?php echo $fld_id;?>">
										<td style="width:26%" class="mainBtn" id="btnlibrary-activities-addstudents" name='<?php echo $id[0].",".$assignclassid.",".$startdate;?>'>
										<?php echo $studentname;?></td>   
										<td style="width:31%" class="mainBtn" id="btnlibrary-activities-addstudents" name='<?php echo $id[0].",".$assignclassid.",".$startdate;?>'><?php echo $classname;?></td> 
										<td style="width:13%" class="centerText mainBtn" id="btnlibrary-activities-addstudents" name='<?php echo $id[0].",".$assignclassid.",".$startdate;?>'><?php if($startdate!='' && $startdate!='0000-00-00') echo date("m/d/Y",strtotime($startdate)); ?></td> 
										<td style="width:13%" class="centerText mainBtn" id="btnlibrary-activities-addstudents" name='<?php echo $id[0].",".$assignclassid.",".$enddate;?>'><?php if($enddate!='' && $enddate!='0000-00-00') echo date("m/d/Y",strtotime($enddate)); ?></td> 
										<td style="width:7%" class="centerText" ><div class="synbtn-remove" style="margin:0 auto;" id="remove_1"></div></td>            
									</tr>
									<?php
								}
							}?>
                    	</tbody>
                	</table>
                </div>
            </div>
            <script>
				$("#remove_1 ").click(function() {
                    var delid =($(this).parents("tr").attr('id'));
                    
					var dataparam = "oper=deletestudent&fieldid="+delid;
                
					$.Zebra_Dialog('Are you sure you want to remove this Student from the Activity?',
					{
						'type': 'confirmation',
						'buttons': [
							{caption: 'No', callback: function() { }},
							{caption: 'Yes', callback: function() {	
								$.ajax({
									type: 'post',
									url: 'library/activities/library-activities-newactivity-ajax.php',
									data: dataparam,	
									async: false,
									beforeSend: function(){
										showloadingalert("Removing, please wait.");	
									},		
									success:function(data) {
										if(data=="success")
										{
											removesections('#library-activities-assign');
											$('.lb-content').html("Student removed successfully");
											closeloadingalert();
											$("#"+delid).remove();
										}
									}
								});	
							}}
						]
					});
                });
			</script>
        </div>
    </div>
</section>
<?php
	@include("footer.php");