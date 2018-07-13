<?php 
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(',',$id);
	
	
	$testassignqry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_student_id AS studentid, a.fld_class_id AS assignclassid, a.fld_start_date AS startdate, 
	                                       a.fld_end_date AS enddate, b.fld_class_name AS classname, CONCAT(c.fld_fname,' ',c.fld_lname) AS studentname 
										   FROM `itc_test_student_mapping` AS a LEFT JOIN itc_class_master AS b ON a.fld_class_id=b.fld_id 
										   LEFT JOIN itc_user_master AS c ON c.fld_id=a.fld_student_id 
										   WHERE a.fld_test_id='".$id[0]."' AND a.fld_flag='1' AND a.fld_created_by='".$uid."' 
										   AND b.fld_delstatus='0' AND c.fld_delstatus='0' ORDER BY c.fld_lname, a.fld_start_date");
?>
<script>
$('#tablecontents6').slimscroll({
	height:'auto',
	size: '3px',
	railVisible: false,
	allowPageScroll: false,
	railColor: '#F4F4F4',
	opacity: 9,
	color: '#88ABC2',
});
</script>
<section data-type='#test-testassign' id='test-testassign-assign'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Assign Assessments</p>
                <p class="dialogSubTitle">Assign this assessment to students or classes below.</p>     
            </div>
        </div>
        
        <div class='row rowspacer'>
            <div class='span10 offset1' id="list">
                <div class='span10 offset1' id="list">
                	<table class='table table-hover table-striped table-bordered setbordertopradius'>
                        <thead class='tableHeadText'>
                            <tr style="cursor:default">
                                <th style="width:28%" >Assignee</th>
                                <th style="width:27%" class='centerText'>Class</th>
                                <th style="width:15%" class='centerText'>Assigned Date</th>
                                <th style="width:15%" class='centerText'>Due Date</th>
                                <th style="width:15%" class='centerText'>Action</th>
                            </tr>
                            <tr class="mainBtn" id="btntest-testassign-addstudents" name='<?php echo $id[0].",0";?>'>
                            	<td colspan="5"><span class="icon-synergy-add-dark small-icon" style="float:left"></span><span style=" float:left; margin:10px 0px 0px 10px; font-weight:bold; font-style:italic;">Assign this assessment</span></td>
                            </tr>
                        </thead>
                	</table>
                    <div style="max-height:400px;width:100%;" id="tablecontents6" >
                        <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                            <tbody>
                            <?php
                            if($testassignqry->num_rows>0){
                                while($rowtestassignqry = $testassignqry->fetch_assoc())
                                {
                                    extract($rowtestassignqry);
                                    ?>
                                    <tr id="<?php echo $fld_id;?>">
                                        <td style="width:28%"><?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
                                                                                 FROM itc_user_master WHERE fld_id='".$studentid."' AND fld_delstatus='0'");?></td>   
                                        <td style="width:27%"><?php echo $classname;?></td> 
                                        <td style="width:15%"><?php if($startdate!='' && $startdate!='0000-00-00') echo date("m/d/Y",strtotime($startdate)); ?></td> 
                                        <td style="width:15%"><?php if($enddate!='' && $enddate!='0000-00-00') echo date("m/d/Y",strtotime($enddate)); ?></td>
                                        <td style="width:15%">
                                            <div class="icon-synergy-edit mainBtn edit" style="float:left; margin:5px; font-size:1.5em;" title="Edit Assessment" id="btntest-testassign-addstudents" name='<?php echo $id[0].",".$assignclassid.",".$startdate;?>'></div>
                                            <?php $studtestcount=$ObjDB->SelectSingleValue("SELECT COUNT(fld_max_attempts) FROM itc_test_student_mapping 
                                                                                           WHERE fld_max_attempts!='0' AND fld_student_id='".$studentid."' 
                                                                                           AND fld_test_id='".$id[0]."' AND fld_class_id='".$assignclassid."'");
                                            if($studtestcount!=0){?>
                                                <div class="synbtn-reassign mainBtn centerText" style=" float:left; margin:5px;" id="btntest-testassign-reassigntest"  title="Re-Assign Assessment" name='<?php echo $id[0].",".$studentid.",".$assignclassid.",".$startdate;?>'></div>
                                            <?php } ?>
                                            <div class="synbtn-remove" style="float:left; margin:5px;"title="Remove Assessment" id="remove_1"></div>
                                        </td>            
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
                    
					var dataparam = "oper=deletestu&fieldid="+delid;
                
					$.Zebra_Dialog('Are you sure you want to remove this Student from the Assessment?',
					{
						'type': 'confirmation',
						'buttons': [
							{caption: 'No', callback: function() { }},
							{caption: 'Yes', callback: function() {	
								$.ajax({
									type: 'post',
									url: 'test/testassign/test-testassign-addstudentsdb.php',
									data: dataparam,	
									async: false,
									beforeSend: function(){
										showloadingalert("Removing, please wait.");	
									},		
									success:function(data) {
										if(data=="success")
										{
											removesections('#test-testassign-assign');
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