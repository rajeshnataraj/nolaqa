<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
	
	/*--- Save/Update student for test  ---*/
	if($oper == "testtoshl" and $oper != '')
	{	
		$testid = (isset($_REQUEST['testid'])) ? $_REQUEST['testid'] : 0;
		$list3 = isset($_REQUEST['list3']) ? $_REQUEST['list3'] : '0';
		$list4 = isset($_REQUEST['list4']) ? $_REQUEST['list4'] : '0';
		
		$list3=explode(",",$list3);
		$list4=explode(",",$list4);
		
		// Student mapping start
		for($i=0;$i<sizeof($list3);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_school_mapping WHERE fld_test_id='".$testid."' 
			                                     AND fld_school_id='".$list3[$i]."' AND fld_flag='1'");
			$ObjDB->NonQuery("UPDATE itc_test_school_mapping SET fld_flag='0',fld_updated_by='".$uid."',fld_updated_date='".$date."'  
							WHERE fld_test_id='".$testid."' AND fld_school_id='".$list3[$i]."' AND fld_id='".$cnt."'");
		}
		
		for($i=0;$i<sizeof($list4);$i++)
		{
			$cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_school_mapping WHERE fld_test_id='".$testid."' 
			                                    AND fld_school_id='".$list4[$i]."'");
			if($cnt==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_test_school_mapping(fld_test_id, fld_school_id, fld_created_by, fld_created_date, fld_flag) 
				                 VALUES ('".$testid."', '".$list4[$i]."', '".$uid."', '".$date."' , '1')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_test_school_mapping SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".$date."'  
				                WHERE fld_test_id='".$testid."' AND fld_school_id='".$list4[$i]."' AND fld_id='".$cnt."'");
			}
		}
	}
	
		if($oper == "showstudentlists" and $oper != '')
	{ 
	   $classid = (isset($_REQUEST['classid'])) ? $_REQUEST['classid'] : 0;
	   $testid = (isset($_REQUEST['testid'])) ? $_REQUEST['testid'] : 0;
	   $startdate = (isset($_REQUEST['startdate'])) ? $_REQUEST['startdate'] : 0;
	   
	
	?>
	  <script language="javascript" type="text/javascript">
                            $(function() {
                                /* scroll for the first left box - Teachers */	
                                $('#testrailvisible1').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                    
                                });
                                /* scroll for the first right box - Teachers */	
                                $('#testrailvisible2').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                });
                                
                                /* drag and sort for the first left box - Teachers */	
                                $("#list1").sortable({
                                    connectWith: ".droptrue",
                                    dropOnEmpty: true,
                                    
                                    receive: function(event, ui) {
                                        $("div[class=draglinkright]").each(function(){ 
                                            if($(this).parent().attr('id')=='list1'){
                                                fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
												fn_hideshowassignbtn();
                                            }
                                        });
                                    }
                                });
                                /* drag and sort for the first right box - Teachers */	
                                $( "#list2" ).sortable({
                                    connectWith: ".droptrue",
                                    dropOnEmpty: true,
                                    receive: function(event, ui) {
                                        $("div[class=draglinkleft]").each(function(){ 
                                            if($(this).parent().attr('id')=='list2'){
                                                fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
												fn_hideshowassignbtn();
                                            }
                                        });
                                    }
                                });
                                $("#list1, #list2").disableSelection();
								 /* scroll for the first left box - Students */	
                                $('#testrailvisible3').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                    
                                });
                                
                                /* scroll for the first right box - Students */	
                                $('#testrailvisible4').slimscroll({
                                    width: '410px',
                                    height:'366px',
                                    size: '3px',
                                    railVisible: true,
                                    allowPageScroll: false,
                                    railColor: '#F4F4F4',
                                    opacity: 1,
                                    color: '#d9d9d9',
                                });
                                
                                $("#list3").sortable({
                                    connectWith: ".droptrue1",
                                    dropOnEmpty: true,
                                    receive: function(event, ui) {
                                        $("div[class=draglinkright]").each(function(){ 
                                            if($(this).parent().attr('id')=='list3'){
                                                fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
												fn_hideshowassignbtn();
                                            }
                                        });
                                    }
                                });
                            
                                $( "#list4" ).sortable({
                                    connectWith: ".droptrue1",
                                    dropOnEmpty: true,
                                    receive: function(event, ui) {
                                        $("div[class=draglinkleft]").each(function(){ 
                                            if($(this).parent().attr('id')=='list4'){
                                                fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
												fn_hideshowassignbtn();
                                            }
                                        });
                                    }
                                });
                                
                            });
                            </script>
	                            <div class='six columns'>
                                	Students<span class="fldreq">*</span>
                                    <div class="dragndropcol">
                                    <?php
										$qrystudent= $ObjDB->QueryObject("SELECT CONCAT(b.fld_lname,' ',b.fld_fname) AS studentname,b.fld_id AS studentid 
											                                 FROM `itc_class_student_mapping` AS a 
																			 LEFT JOIN  `itc_user_master` AS b ON a.`fld_student_id` = b.`fld_id`  
																			 WHERE a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_id NOT IN (
																			 SELECT fld_student_id FROM  itc_test_student_mapping 
																			 WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' 
																			 	AND fld_start_date='".$startdate."' AND  fld_flag='1') 
																			 ORDER BY studentname ");
									?>
                                        <div class="dragtitle">Students Available(<span id="nostudentleftdiv"> <?php echo $qrystudent->num_rows;?></span>)</div>
                                        <div class="dragWell" id="testrailvisible1" >
                                            <div id="list1" class="dragleftinner droptrue">
                                            <?php 
                                            
											if($qrystudent->num_rows > 0){
                                                while($rowsqry = $qrystudent->fetch_assoc()){
                                                    extract($rowsqry);
													
													$studentassigncount=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_test_student_mapping WHERE fld_test_id='".$testid."' 
																								AND fld_class_id='".$classid."' AND  fld_flag='1' AND fld_student_id='".$studentid."'");
                                                ?>
                                                            <div class="draglinkleft<?php if($studentassigncount!=0) echo ' dim'; ?>" id="list1_<?php echo $studentid; ?>" >
                                                                <div class="dragItemLable" id="<?php echo $studentid; ?>"><?php echo $studentname; ?><?php if($studentassigncount!=0) echo '<span style="color:red;font-size:24px;">*</span>'; ?></div>
                                                                <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $studentid; ?>);fn_hideshowassignbtn();"></div>
                                                            </div> 
                                                        <?php
                                                            }
                                                     }?>    
                                            </div>
                                        </div>
                                        <div class="dragAllLink"  onclick="fn_movealllistitems('list1','list2',0);fn_hideshowassignbtn();">add all students</div>
                                    </div>
                                </div>
                                <div class='six columns'>
                                	<span class="fldreq"></span>
                                    <div class="dragndropcol">
                                    <?php
										 $qryclassmap=$ObjDB->QueryObject("SELECT fld_id,fld_student_id as studentid, 
										                                   (SELECT CONCAT(fld_lname,' ',fld_fname)  FROM `itc_user_master` 
																		   WHERE fld_id=fld_student_id) AS studentname FROM `itc_test_student_mapping`
																		    WHERE fld_test_id='".$testid."' AND fld_class_id='".$classid."' 
																			AND fld_start_date='".$startdate."' AND fld_flag='1'  ");
									?>
                                        <div class="dragtitle">Students in Assessment(<span id="nostudentrightdiv"> <?php echo $qryclassmap->num_rows;?></span>)</div>
                                        <div class="dragWell" id="testrailvisible2">
                                            <div id="list2" class="dragleftinner droptrue">
                                                <?php 
												if($qryclassmap->num_rows > 0){
													while($rowqryclassmap = $qryclassmap->fetch_assoc()){
														extract($rowqryclassmap);
                                                ?> 
                                                                <div class="draglinkright" id="list2_<?php echo $studentid; ?>">
                                                                    <div class="dragItemLable" id="<?php echo $studentid; ?>"><?php echo $studentname;?></div>
                                                                    <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $studentid; ?>);fn_hideshowassignbtn();"></div>
                                                                </div>
                                                             <?php }
                                            }?>
                                            </div>
                                        </div>
                                        <div class="dragAllLink" onclick="fn_movealllistitems('list2','list1',0);fn_hideshowassignbtn();">remove all students</div>
                                    </div>
                                </div>
                              
                               
                                
<?php } 

	@include("footer.php");                                