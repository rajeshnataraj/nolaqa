<?php
@include("sessioncheck.php");

$tempid = isset($method['id']) ? $method['id'] : '0';
$tempid=explode(',',$tempid);
$id=$tempid[0];
$flag=$tempid[1];

if($flag==1)
	$value="Save Changes";
else
	$value="Next Step";
?>
<script language="javascript">
	$('#classdetails').removeClass("active-first");
	$('#review').removeClass("active-last");
	$('#people').parents().removeClass("dim");
	$('#people').addClass("active-mid");
</script>

<section data-type='#class-newclass' id='class-newclass-addpeople'>
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle">Add People to Your Class.</p>
            <p class="dialogSubTitleLight">Using the list below, add teachers and students to your class.  <?php if($flag==0){?> Then click "Next step" to continue forward.<?php } else {?> Then click "Save changes" to save the details. <?php } ?></p>
        </div>
        
        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                	<div class='eleven columns centered insideForm'>
                        <form  id="createlicense" name="createlicense" method='post'>
                            <script language="javascript" type="text/javascript">
								$(function() {
									$('div[id^="testrailvisible"]').each(function(index, element) {
										$(this).slimscroll({ /*------- Scroll for Modules Left Box ------*/
											width: '410px',
											height:'366px',
											size: '7px',
											railVisible: true,
                                                                                        alwaysVisible: true,
											allowPageScroll: false,
											railColor: '#F4F4F4',
											opacity: 1,
											color: '#d9d9d9',
                                                                                        wheelStep: 1
										});
									});
									
									/* drag and sort for the first left box - Teachers */	
									$("#list1").sortable({
										connectWith: ".droptrue",
										dropOnEmpty: true,
										items: "div[class='draglinkleft']",
										receive: function(event, ui) {
											$("div[class=draglinkright]").each(function(){ 
												if($(this).parent().attr('id')=='list1'){
													fn_movealllistitems('list1','list2',$(this).children(":first").attr('id'));
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
												}
											});
										}
									});
									
									$("#list3").sortable({
										connectWith: ".droptrue1",
										dropOnEmpty: true,
										items: "div[class='draglinkleft']",
										receive: function(event, ui) {
											$("div[class=draglinkright]").each(function(){ 
												if($(this).parent().attr('id')=='list3'){
													fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'));
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
												}
											});
										}
									});
								});
                            </script> 
                               
                            <div class='row'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    	<div class="dragtitle">Teachers available</div>
                                        <div class="draglinkleftSearch" id="s_list1" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_1_search" name="list_1_search" onKeyUp="search_list(this,'#list1');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible1" >
                                            <div id="list1" class="dragleftinner droptrue">
												<?php 
                                                $qryteacher= $ObjDB->QueryObject("SELECT fld_id AS teacherid, fld_fname AS firstname, fld_lname AS lastname 
																				 FROM itc_user_master 
																				 WHERE fld_id NOT IN (SELECT fld_teacher_id FROM itc_class_teacher_mapping WHERE fld_class_id='".$id."' 
																				 	AND fld_flag='1') AND (fld_school_id = '".$schoolid."' AND fld_user_id='".$indid."') AND
																					(fld_profile_id=5 OR fld_profile_id=7 OR fld_profile_id=8 OR fld_profile_id=9) 
																					AND fld_activestatus='1' AND fld_delstatus='0' AND fld_id<>'".$uid."'");
                                                if($qryteacher->num_rows > 0){
													while($rowsqry = $qryteacher->fetch_assoc()){
														extract($rowsqry);
														?>
														<div class="draglinkleft" id="list1_<?php echo $teacherid; ?>" >
															<div class="dragItemLable" id="<?php echo $teacherid; ?>"><?php echo $firstname." ".$lastname; ?></div>
															<div class="clickable" id="clck_<?php echo $teacherid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $teacherid; ?>);"></div>
														</div> 
														<?php
													}
                                                }?>    
                                            </div>
                                        </div>
                                    	<div class="dragAllLink"  onclick="fn_movealllistitems('list1','list2',0);">add all teachers</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    	<div class="dragtitle">Teachers in your class</div>
                                        <div class="draglinkleftSearch" id="s_list2" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_2_search" name="list_2_search" onKeyUp="search_list(this,'#list2');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible2">
                                            <div id="list2" class="dragleftinner droptrue">
                                                <div class="draglinkright" id="list2_<?php echo $uid; ?>" style="pointer-events: none;">
                                                	<div class="dragItemLable" id="<?php echo $uid; ?>">
														<?php echo $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) 
																							 FROM itc_user_master 
																							 WHERE fld_id='".$uid."'");?>
                                                    </div>
                                                	<div class="clickable" id="clck_<?php echo $uid; ?>" ></div>
                                                </div>
												<?php 
                                                $qryclassteachermap=$ObjDB->QueryObject("SELECT a.fld_id AS teacherid, a.fld_fname AS firstname, a.fld_lname AS lastname 
																						FROM itc_user_master AS a LEFT JOIN itc_class_teacher_mapping AS b ON a.fld_id=b.fld_teacher_id 
																						WHERE b.fld_class_id='".$id."' AND b.fld_flag=1 AND a.fld_activestatus='1' 
																							AND a.fld_delstatus='0' AND a.fld_id<>'".$uid."'");
                                                if($qryclassteachermap->num_rows > 0){
													while($rowqryclassteachermap = $qryclassteachermap->fetch_assoc()){
														extract($rowqryclassteachermap);
														?> 
														<div class="draglinkright" id="list2_<?php echo $teacherid; ?>">
                                                            <div class="dragItemLable" id="<?php echo $teacherid; ?>"><?php echo $firstname." ".$lastname;?></div>
                                                            <div class="clickable" id="clck_<?php echo $teacherid; ?>" onclick="fn_movealllistitems('list1','list2',<?php echo $teacherid; ?>);"></div>
														</div>
													<?php }
                                                }?>
                                            </div>
                                        </div>
                                    	<div class="dragAllLink" onclick="fn_movealllistitems('list2','list1',0);">remove all teachers</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class='row rowspacer'>
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
										$qrystudent= $ObjDB->QueryObject("SELECT fld_id AS studentid,CONCAT(fld_lname,' ',fld_fname)AS studentname,fld_username as username 
																				 FROM itc_user_master 
																				 WHERE fld_id NOT IN (SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$id."' 
																				 	AND fld_flag='1') AND (fld_school_id = '".$schoolid."' AND fld_user_id='".$indid."') 
																					AND fld_profile_id=10 AND fld_activestatus='1' AND fld_delstatus='0' 
																				 ORDER BY studentname");
									?>
                                        <div class="dragtitle">   &nbsp;&nbsp;&nbsp;Students available (<span id="nostudentleftdiv"> <?php echo $qrystudent->num_rows;?></span>) <div class='row rowspacer' title="Create student" style="cursor:pointer; float:left;" onclick="fn_addstudent();"><strong><input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;" value="Create Student"> </strong></div></div>
                                        &nbsp;<div class="draglinkleftSearch" id="s_list3" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible3" >
                                            <div id="list3" class="dragleftinner droptrue1">
												<?php 
                                                if($qrystudent->num_rows > 0){
													while($rowqryclassstudentmap = $qrystudent->fetch_assoc()){
														extract($rowqryclassstudentmap);
														?>
														<div class="draglinkleft" id="list3_<?php echo $studentid; ?>" >
                                                            <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $studentname; ?></div>
                                                            <div class="clickable" id="clck_<?php echo $studentid;?>" onclick="fn_movealllistitems('list3','list4',<?php echo $studentid;?>);"></div>
														</div> 
													<?php }
                                                }?>    
                                            </div>
                                        </div>
                                   		<div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0);">add all Students</div>
                                    </div>
                                </div>
                                
                                <div class='six columns'>
                                    <div class="dragndropcol">
                                    <?php
										$qryclassstudentmap=$ObjDB->QueryObject("SELECT a.fld_id AS studentid,CONCAT(a.fld_lname,' ',a.fld_fname)AS studentname,a.fld_username as username
																					FROM itc_user_master AS a LEFT JOIN itc_class_student_mapping AS b ON a.fld_id=b.fld_student_id 
																					WHERE b.fld_class_id='".$id."' AND b.fld_flag=1 AND a.fld_activestatus='1' AND a.fld_delstatus='0' ORDER BY studentname ");
									?>
                                    	<div class="dragtitle"> &nbsp;&nbsp; &nbsp;&nbsp;Students in your class (<span id="nostudentrightdiv"> <?php echo $qryclassstudentmap->num_rows;?></span>)</div> 
                                       <div class="row rowspacer"><div class="row rowspacer"><div class="row rowspacer"> <div class="row rowspacer"><div class="draglinkleftSearch" id="s_list4" >
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list4');" />
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="dragWell" id="testrailvisible4">
                                            <div id="list4" class="dragleftinner droptrue1">
                                            <?php 
                                            if($qryclassstudentmap->num_rows > 0){
												while($rowsqry = $qryclassstudentmap->fetch_assoc()){
													extract($rowsqry);
													?> 
													<div class="draglinkright" id="list4_<?php echo $studentid; ?>">
                                                        <div class="dragItemLable tooltip" id="<?php echo $studentid; ?>" title="<?php echo $username;?>"><?php echo $studentname;?></div>
                                                        <div class="clickable" id="clck_<?php echo $studentid; ?>" onclick="fn_movealllistitems('list3','list4',<?php echo $studentid; ?>);">
                                                        </div>
													</div>
												<?php }
                                            }?>
                                            </div>
                                        </div>
                                    	<div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0);">remove all Students</div>
                                    </div>
                                </div>
                            
                            
                            <div class="row rowspacer">
                                <div class="tRight">
                                     <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_teacherstudentidmaptoclass(<?php echo $id.",".$flag;?>);" />
                                </div>
                                <input type="hidden" name="classid" id="classid" value="<?php echo $id;?>" />
                            </div>
                        </form>	
                    </div>
                </div>
            </div>
        </div>   
    </div>
</section>
<?php
	@include("footer.php");