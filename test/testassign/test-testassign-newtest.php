<?php
	@include("sessioncheck.php");
	$id = isset($method['id']) ? $method['id'] : '0';
	$id = explode(",",$id);
	/****dECLARATION PART****/
	$testname='';
	$testdes='';
	$timelimit='';
	$score='100';
	$attempts='';
	$graderounding='';
	$counter='';
        $expid='';
        $destid='';
        $resid='';
        $taskid='';
        $contentid='';
        $productid='';
	if($id[0] != 0)
	{
            $qrystep1 = $ObjDB->QueryObject("SELECT fld_test_name AS testname, fld_test_des AS testdes,fld_ass_type as asstype, fld_expt as expt, fld_mist as mist,fld_destid as destid,fld_taskid as taskid,fld_resid as resid,fld_prepostid as prepostid,
										fld_time_limit AS timelimit,fld_score AS score, fld_max_attempts AS attempts,fld_content_id as contentid,fld_product_id as productid
										FROM itc_test_master 
										WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
		$rowstep1 = $qrystep1->fetch_assoc();
		extract($rowstep1);
		$counter = $ObjDB->SelectSingleValueInt("select count(*) from itc_test_grading_scale_mapping where fld_test_id='".$id[0]."'");

                $prepostid=$prepostid;
                
                if($prepostid == '1'){
                    $prepostname="Pre Test";
	}
                else{
                    $prepostname="Post Test";
                }
                if($asstype == '0' and $contentid > '0' and $sessmasterprfid == 2)
                {
                 ?>
                    <script>
                        $('#astype').addClass('dim');
                        $('#contents').show();
                        $('#contents').addClass('dim');
                        fn_showproducts();
                        $('#productsdiv').addClass('dim');

                    </script>
                 <?php
                }
                
                if($asstype == '0' and $contentid=='0' and $sessmasterprfid == 2)
                {
                ?>
                   <script>
                   $('#contents').show();
                   </script>
                <?php   
                }
                
                
                if($asstype == '1'){
                    ?>
                        <script>
                            $('#astype').addClass('dim');
                            $('#misbox').hide();
                            $('#prepost').show();  
                            $('#expbox').addClass('dim');
                            fn_showdest(<?php echo $expt;?>,<?php echo $destid;?>,1);
                            fn_showtask(<?php echo $destid;?>,<?php echo $taskid;?>,1);
                            fn_showres(<?php echo $taskid;?>,<?php echo $resid;?>,1);
                            $('#destbox').addClass('dim');
                            $('#taskbox').addClass('dim');
                            $('#resbox').addClass('dim');
                            <?php if($id[2]!=1){ ?>
                            $('#prepost').addClass('dim');      
                            <?php } ?>
                        </script>
                    <?php
                }
                if($asstype == '2'){
                    ?>
                        <script>
                            $('#astype').addClass('dim');
                            $('#expbox').hide();
                            $('#misbox').show();
                        </script>
                    <?php
	}
	}
	
?>

<section data-type='test-testassign' id='test-testassign-newtest'>
	<script type="text/javascript" charset="utf-8">	
		$(function(){				
		var t4 = new $.TextboxList('#form_tags_test', 
			{
				unique: true, plugins: {autocomplete: {}},
				bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 
			if($id[0]!= '' and $id[0]!='undefined'){
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='20' 
											        AND b.fld_access='1'
													AND a.fld_delstatus='0' AND b.fld_item_id='".$id[0]."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
					t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			      <?php }
				}
			}
		?>				
			t4.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=new', type:"POST", dataType: 'json', success: function(r){
				t4.plugins['autocomplete'].setValues(r);
				t4.getContainer().removeClass('textboxlist-loading');					
			}});						
		});
				
		$('#newtest').addClass("active-first");
		$('#testreview').removeClass("active-last");
		$('#testreview').removeClass("active-mid");
	</script> 

    <div class='container'>
        <div class='row'>
        	<div class='twelve columns'>
          		<p class="dialogTitle">New Assessment Details</p>
            	<p class="dialogSubTitleLight">Provide details about new assessment below. Click Next Step to continue forward.</p>
          	</div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
            	<form method='post' id="testform" name="testform">
                    <div class="row rowspacer">
                        <div class="twelve columns">
                            <div class="title-info">Basic Information</div>
                            Assessment Name<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='New Test Name' type='text' id="testname" name="testname" value="<?php echo $testname ;?>" onBlur="$(this).valid();">
                                </dt>
                            </dl> 
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div class="twelve columns">
                        	Description
                            <dl class='field row'>
                                <dt class='text'>
                                	<input placeholder='A description' type='text' id="testdes" name="testdes" value="<?php echo htmlentities($testdes);?>">
                                </dt>
                            </dl> 
                        </div>
                    </div>
                    
                    <div id="astype" class="row rowspacer">
                        <div class="six columns">
                        Select Assessment Type<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="ddlasstype" id="ddlasstype" value="<?php echo $asstype;?>" onchange="$('#ddlasstype').valid();">
                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php if($id[0]==0){ echo "0";} else {echo $asstype;}?>"><?php if($id[0]== 0){ echo "Select type";?><script>$('#timescore').show();$('#timescore1').hide();</script> <?php } else { if($asstype ==0){ echo "Regular";?><script> $('#timescore').show();$('#timescore1').hide();</script> <?php } else if($asstype ==1) { echo "Expedition";?><script>$('#timescore1').show();$('#timescore').hide();$('#expbox').show();</script> <?php } else { echo "Mission";?><script>$('#timescore1').show();$('#timescore').hide();$('#misbox').show();</script> <?php }}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search type" >
                                        <ul role="options">
                                                <li onclick="<?php if($sessmasterprfid == 2){?> $('#contents').show(), <?php } ?> $('#expbox').hide(),$('#misbox').hide(),$('#destbox').hide(),$('#taskbox').hide(),$('#resbox').hide(),$('#prepost').hide(),$('#timescore').show();$('#timescore1').hide();$('#ddlasstype').val(0);"><a href="#" data-option="0"><?php echo "Regular";?></a></li>
                                                <li onclick="$('#contents').hide(),$('#productsdiv').hide(),$('#expbox').show(),$('#misbox').hide(),$('#destbox').show(),$('#taskbox').show(),$('#resbox').show(),$('#prepost').show(),$('#misprepost').hide();$('#timescore1').show();$('#timescore').hide();$('#ddlasstype').val(1);$('#destbox').addClass('dim'),$('#taskbox').addClass('dim'),$('#resbox').addClass('dim'),$('#prepost').addClass('dim');"><a href="#" data-option="1"><?php echo "Expedition";?></a></li>
                                                <li onclick="$('#contents').hide(),$('#productsdiv').hide(),$('#expbox').hide(),$('#misbox').show(),$('#destbox').hide(),$('#taskbox').hide(),$('#resbox').hide(),$('#prepost').hide(),$('#timescore1').show();$('#timescore').hide();$('#ddlasstype').val(2);"><a href="#" data-option="2"><?php echo "Mission";?></a></li>
                                        </ul>
                                      </div>
                                    </div>
                                </dt>
                            </dl>
                        </div>
                        
                        <div id="contents" class="six columns" style="display:none">
                        Select Title<span class="fldreq"></span>
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <?php 
                                    $contentname = $ObjDB->SelectSingleValue("SELECT fld_content_name 
                                                                            FROM itc_test_content_details WHERE fld_id = '".$contentid."' AND fld_delstatus='0'"); 
                                    
                                    if($contentname=='')
                                    {
                                        $contentname="Select Title";
                                    }
                                    
                                    ?>
                                        <input type="hidden" name="ddlcontents" id="ddlcontents" value="<?php  if($sessmasterprfid == 2){ echo $contentid; }else{ echo '0'; } ?>" onchange="fn_showproducts();">
                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                          <span class="selectbox-option input-medium" data-option="<?php if($id[0]==0){ echo "0";} else {echo $contentid;}?>"><?php if($id[0]==0){ echo "Select Title";} else { echo $contentname;}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search title">
                                        <ul role="options">
                                            
                                            <?php
                                                $contentqry=$ObjDB->QueryObject("select fld_id,fld_content_name from itc_test_content_details WHERE fld_delstatus='0'");
                                              
                                                while($rowcontent=$contentqry->fetch_assoc())
                                                {
                                                    extract($rowcontent);
                                                ?>
                                                <li><a href="#" data-option="<?php echo $fld_id;?>"><?php echo $fld_content_name;?></a></li>
                                                <?php
                                                }
                                                ?>
                                                
                                        </ul>
                                      </div>
                                    </div>
                                </dt>
                            </dl>
                        </div>
                        
                        <div id="expbox" class="six columns" style="display:none">
                        Select Expedition<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                    <?php 
                                    $expname = $ObjDB->SelectSingleValue("SELECT fld_exp_name 
                                                                            FROM itc_exp_master WHERE fld_id = '".$expt."' ORDER BY fld_exp_name ASC"); ?>
                                      <input type="hidden" name="ddlexp" id="ddlexp" value="<?php echo $expt;?>" onchange="$('#ddlexp').valid();">
                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php if($id[0]==0){ echo "0";} else {echo $expid;}?>"><?php if($id[0]== 0){ echo "Select expedition";} else {echo $expname;}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search expedition" >
                                        <ul role="options">
                                        	 <?php
											 if($sessmasterprfid == 2){
												$expqry = $ObjDB->QueryObject("SELECT fld_id AS expid, fld_exp_name as expname FROM itc_exp_master WHERE fld_delstatus='0' ORDER BY expname");
											 }
											 else{
												 $expqry = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid, b.fld_exp_name as expname FROM itc_license_exp_mapping AS a
																				LEFT JOIN itc_exp_master AS b ON a.fld_exp_id = b.fld_id
																				LEFT JOIN itc_license_track AS c ON c.fld_license_id=a.fld_license_id
																				WHERE c.fld_district_id='".$sendistid."' AND c.fld_school_id='".$senshlid."' 
																					AND a.fld_delstatus='0' AND b.fld_delstatus='0' GROUP BY expid ORDER BY expname ");
											 }
                                            while($rowexp = $expqry->fetch_assoc()){
												extract($rowexp);
												 ?>
                                            <li><a href="#" data-option="<?php echo $expid;?>" onclick="fn_showdest(<?php echo $expid;?>,0,1)"><?php echo $expname;?></a></li>
                                            <?php 
                                            }?>   
                                        </ul>
                                      </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                        
                        <div id="misbox" class="six columns" style="display:none">
                        Select Mission<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        
                                    <?php
                                    $misname = $ObjDB->SelectSingleValue("SELECT fld_mis_name 
										FROM itc_mission_master WHERE fld_id = '".$mist."' AND fld_delstatus='0' ORDER BY fld_mis_name ASC"); ?>
                                      <input type="hidden" name="ddlmis" id="ddlmis" value="<?php echo $mist;?>" onchange="$('#ddlmis').valid();">
                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="<?php if($id[0]==0){ echo "0";} else {echo $mist;}?>"><?php if($id[0]== 0){ echo "Select Mission";} else {echo $misname;}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search mission" >
                                        <ul role="options">
                                        	 <?php
											 if($sessmasterprfid == 2){
												$misqry = $ObjDB->QueryObject("SELECT fld_id AS misid, fld_mis_name as misname FROM itc_mission_master WHERE fld_delstatus='0' ORDER BY misname");
											 }
											 else{
												 $misqry = $ObjDB->QueryObject("SELECT a.fld_mis_id AS misid, b.fld_mis_name as misname FROM itc_license_mission_mapping AS a
																				LEFT JOIN itc_mission_master AS b ON a.fld_mis_id = b.fld_id
																				LEFT JOIN itc_license_track AS c ON c.fld_license_id=a.fld_license_id
																				WHERE c.fld_district_id='".$sendistid."' AND c.fld_school_id='".$senshlid."' 
																					AND a.fld_delstatus='0' AND b.fld_delstatus='0' GROUP BY misid ORDER BY misname");
											 }
                                                                                                while($rowmis = $misqry->fetch_assoc()){
												extract($rowmis);
												 ?>
                                            <li><a href="#" data-option="<?php echo $misid;?>"><?php echo $misname;?></a></li>
                                            <?php 
                                            }?>   
                                        </ul>
                    </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div id="productsdiv" class="six columns" style="display:none">
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div id="destbox" class="six columns" style="display:none" >
                            Select Destination
                                    <div class="selectbox">
                                <input type="hidden" name="destid" id="destid" value="0">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" style="width:97%" data-option="">Select Destination</span>
                                        <b class="caret1"></b>
                                      </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Destination">
                                        <ul role="options">
                                            <?php 
                                                $destid='';$destname='';
                                            ?>
                                        </ul>
                                    </div>
                        </div>
                        </div>                            
                        <div id="taskbox" class="six columns" style="display:none" >
                            Select Task
                                    <div class="selectbox">
                                <input type="hidden" name="taskid" id="taskid" value="0">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Task</span>
                                        <b class="caret1"></b>
                                      </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Task">
                                    <ul role="options">
                                        <?php
                                        $taskid='';
                                        $taskname='';
                                        ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $taskid;?>" onclick="fn_showres(<?php echo $taskid;?>)" ><?php echo $taskname; ?></a></li>
                    
                                    </ul>
                                    </div>
                        </div>
                    </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div id="resbox" class="six columns" style="display:none" >
                            Select Resource
                                    <div class="selectbox">
                                <input type="hidden" name="resid" id="resid" value="0">
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                    <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Resource</span>
                                        <b class="caret1"></b>
                                      </a>
                                <div class="selectbox-options">
                                    <input type="text" class="selectbox-filter" placeholder="Search Resource">
                                        <ul role="options">
                                            <?php $resid='';
                                                $resname='';
                                            ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $resid;?>"  ><?php echo $resname; ?></a></li>
                                        </ul>
                                    </div>
                        </div>
                        </div>
                        

                        <div id="prepost" class="six columns" style="display:none">
                         Pre/Post<span class="fldreq">*</span> 
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="prepostid" id="prepostid" value="<?php echo $prepostid; ?>" >
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                           <span class="selectbox-option input-medium" style="width:97%" data-option="<?php if($id[0] == 0){ echo "0";} else {echo $prepostname;}?>"><?php if($id[0] == 0){ echo "Select Pre/Post";} else {echo $prepostname;}?></span>
                                        <b class="caret1"></b>
                                      </a>
                                        <div class="selectbox-options" >
                                            <input type="text" class="selectbox-filter" placeholder="Search grade" >
                                            <ul role="options">
                                                    <li><a tabindex="1" href="#" data-option="1">Pretest</a></li>
                                                    <li><a tabindex="1" href="#" data-option="2">Posttest</a></li>                                                    
                                            </ul>
                                    </div>
                                    </div>
                                </dt>
                            </dl> 
                        </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <?php 
                            if($id[0] !='0'){
                                $id1 = explode(":",$timelimit);
                                $timelimit = $id1[0].":".$id1[1];
                            }
                            else{
                                $timelimit = "00:00";
                            }
                        ?>
                        <div class="four columns">
                        	Time Limit<span class="fldreq">*</span> (00:00 - Unlimited time)
                            <dl class='field row'>
                                <dt class='text'>
                                   <input placeholder='Time Limit (Minutes)' type='text' id="timelimit" name="timelimit" readonly value="<?php echo $timelimit;?>">
                                </dt>
                            </dl> 
                        </div>
                        
                        <div class="four columns">
                        	Assessment Score<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                <input placeholder='Test Score' type='text' id="score" name="score" onkeyup="ChkValidChar();" onkeypress="return isNumberKey(event)" value="<?php echo $score ;?>" onBlur="$(this).valid();">
                                </dt>
                            </dl>
                        </div>
                        
                        <div class="four columns" id="timescore">
                        	Maximum Attempts<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Maximum Attempts' type='text' id="attempts" name="attempts" maxlength="3" onkeypress="return isNumberKey(event)" value="<?php echo $attempts ;?>" onBlur="$(this).valid();">
                                </dt>
                            </dl> 
                        </div>
                        <div class="four columns" id="timescore1">
                        	Maximum Attempts<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='text'>

                                    <input placeholder='Maximum Attempts' type='text' id="attempts" readonly="readonly" name="attempts" maxlength="3" value="<?php echo "1";?>">
                                </dt>
                            </dl> 
                    </div>
                    </div>
                    
                    <div class="row rowspacer">
                        <div class='twelve columns'>
                            To create a new tag, type a name and press Enter.
                            <div class="tag_well">
                                <input type="text" name="test3" value="" id="form_tags_test" />
                            </div>
                        </div>
                        <input type="hidden" id="hidclasstypeid" name="hidclasstypeid"  value="<?php echo $labid; ?>" />
                    </div>
                    
                    <div class="row rowspacer">
                        <div class="twelve columns">
                            <div class="formSubHeading">Grading Scale</div>
                            <div class="row rowspacer">
                                <div class="three columns"><strong>Letter Grade</strong></div>
                                <div class="three columns"><strong>Lower Bound</strong></div>
                                <div class="three columns"><strong>Higher Bound</strong></div>
                                <div class="three columns">
                                    <input type="button" value="+" id='addButton' />
                                    <input type="button" value="-" id='removeButton' />
                                </div>
                            </div>
                            
                            <div class="row rowspacer" id="TextBoxesGroup">
                                <?php
                                $qry = $ObjDB->QueryObject("SELECT fld_grade, fld_lower_bound,fld_upper_bound 
								                            FROM itc_test_grading_scale_mapping 
								                            WHERE fld_test_id='".$id[0]."'");
                                if($qry->num_rows > 0)
                                {
                                    $count=1;
                                    while($row=$qry->fetch_object())
                                    {
                                        $graderounding=$row->fld_roundflag;
                                        ?>
                                        <div class="row" id="TextBoxDiv<?php echo $count;?>">
                                            <div class="three columns">
                                                <dl class='field row'>
                                                    <dt class='text'>
                                                        <input type='textbox' id='lettergrade<?php echo $count;?>' name="lettergrade<?php echo $count;?>"  maxlength="2" value="<?php echo $row->fld_grade;?>" />
                                                    </dt>
                                                </dl>
                                            </div>
                                            <div class="three columns">
                                                <dl class='field row'>
                                                    <dt class='text' >
                                                    <input type='textbox' id='lowerbound<?php echo $count; ?>' name="lowerbound<?php echo $count;?>" maxlength="3" onkeypress="return isNumberKey(event)" value="<?php echo $row->fld_lower_bound;?>" /><span style="position: absolute;right:10px;">%</span>
                                                    </dt>
                                                </dl>
                                            </div>
                                            <div class="three columns">
                                                <dl class='field row'>
                                                    <dt class='text' >
                                                        <input type='textbox' id='higherbound<?php echo $count; ?>'  name="higherbound<?php echo $count;?>" maxlength="3" onkeypress="return isNumberKey(event)" value="<?php echo $row->fld_upper_bound;?>" /><span style="position: absolute;right:10px;">%</span>
                                                    </dt>
                                                </dl>
                                            </div>
                                        </div>
                                        <?php
                                        $count++;
                                    }
                                }
                                else
                                {
                                    $grade = array("A","B","C","D","F");
                                    $lbound = array("90","80","70","60","0");
                                    $ubound = array("100","89","79","69","59");
                                    
                                    for($i=0;$i<5;$i++){
                                	?>
                                    <div class="row" id="TextBoxDiv<?php echo ($i+1);?>">  
                                        <div class="three columns">
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input type='textbox' id='lettergrade<?php echo ($i+1);?>' name="lettergrade<?php echo ($i+1);?>"  maxlength="2" value="<?php echo $grade[$i]; ?>">
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="three columns">                                                  
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input type='textbox' id='lowerbound<?php echo ($i+1);?>' name="lowerbound<?php echo ($i+1);?>" maxlength="3" value="<?php echo $lbound[$i]; ?>"  onkeypress="return isNumberKey(event)"><span style="position: absolute;right:10px;">%</span>
                                                </dt>
                                            </dl>
                                        </div>
                                        <div class="three columns">   
                                            <dl class='field row'>
                                                <dt class='text'>
                                                    <input type='textbox' id='higherbound<?php echo ($i+1);?>'  name="higherbound<?php echo ($i+1);?>" maxlength="4" value="<?php echo $ubound[$i]; ?>" onkeypress="return isNumberKey(event)">
                                                    <span style="position: absolute;right:10px;">%</span>
                                                </dt>
                                            </dl>
                                        </div>
                                    </div>
                                <?php 
                                    } // for ends
                                } // if else ends
                            ?>
                            </div>
                            <div class="row rowspacer">
                                <div class="twelve columns">
                                    Use Grade Rounding&nbsp;<input type="checkbox" name="grade" id="grade" <?php if($graderounding == 1){echo "checked"; }?> />
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="lg" id="lg" />
                        <input type="hidden" name="productid" id="productid" value="<?php echo $productid;?>"/>
                        <input type="hidden" name="lb" id="lb" />
                        <input type="hidden" name="hb" id="hb" />
                        <input type="hidden" name="boxid" id="boxid" />
                        <input type="hidden" name="counter" id="counter" value="<?php echo $counter; ?>" />
                        <input type="hidden" name="removecounter" id="removecounter" />
                        <script type="text/javascript" language="javascript">                                      
							$(document).ready(function(){
								var remove='';										
								<?php 
									if($counter==0){ ?>
										var counter = 6;
										$('#counter').val(counter);
								<?php
									}else{
								?>
										var counter=<?php echo $counter."+1";?>;
										$('#counter').val(counter);
								<?php
									}
								?>											
								
								$("#addButton").click(function () {												
									if(counter>10){
										showloadingalert("Only 10 textboxes allowed");	
										setTimeout('closeloadingalert()',2000);
										return false;
									}
									
									var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter).addClass('row');											
									
									var divcontent="<div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='higherbound" + counter + "' id='higherbound" + counter + "' class='text' maxlength='3' onkeypress='return isNumberKey(event)'><span style='position: absolute;right:10px;'>%</span></dt></dl></div>";												
									
									newTextBoxDiv.html("<div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='lettergrade" + counter + "' id='lettergrade" + counter + "' class='text' maxlength='3' /></dt></dl></div><div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='lowerbound" + counter + "' id='lowerbound" + counter + "' class='text' maxlength='3' onkeypress='return isNumberKey(event)' /><span style='position: absolute;right:10px;'>%</span></dt></dl></div>"+divcontent);
									newTextBoxDiv.appendTo("#TextBoxesGroup");
									counter++;
									$('#counter').val(counter);												
								});											 
								
								$("#removeButton").click(function () {													
									if(counter==2){		
										showloadingalert("No more textbox to remove");	
										setTimeout('closeloadingalert()',2000);												
										return false;
									}   											 
									counter--;													
									$("#TextBoxDiv" + counter).remove();
									remove+=counter+"~";
									$('#removecounter').val(remove);
									$('#counter').val(counter);	
								});           
						 	});
                    	</script>
                    </div>
    
                    <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/-->
                        <?php $createdby = $ObjDB->SelectSingleValueInt("SELECT fld_created_by FROM itc_test_master WHERE fld_id='".$id[0]."' AND fld_delstatus='0'"); 
                         $otherteachcreatedby = $ObjDB->SelectSingleValueInt("SELECT fld_otherteach_profile_id FROM itc_test_master WHERE fld_id='".$id[0]."' AND fld_delstatus='0'");
                         $distprofileidforschool = $ObjDB->SelectSingleValueInt("SELECT fld_profile_id FROM itc_user_master WHERE fld_id='".$createdby."' AND fld_delstatus='0'");                            
                        ?>
                    <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/-->
                    
                    <div class='row rowspacer'>
                        <div class='six columns'></div>
                        <div class='twelve columns'>
                        	<div class="tRight">
                                <?php 
                                    if($otherteachcreatedby=='3' AND $sessmasterprfid == '9' OR $otherteachcreatedby=='2'){
                                    ?>  <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/-->
                                    <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next_forpitsco_duplicate(<?php echo $id[0];?>);" />
                                <?php }
                                else if(($sessmasterprfid=='6' OR $sessmasterprfid=='7') AND $createdby=='2' AND $otherteachcreatedby=='0'){ ?>
                                 <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next_forpitsco_duplicate(<?php echo $id[0];?>);" />
                                  
                             <?php   }
                              else if(($sessmasterprfid == '7' OR $sessmasterprfid == '9') AND $distprofileidforschool=='6' AND $otherteachcreatedby=='0'){ ?>
                                 <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next_forpitsco_duplicate(<?php echo $id[0];?>);" />
                                  
                                <?php   }
                                
                              else if($sessmasterprfid == '9' AND $distprofileidforschool=='2' AND $otherteachcreatedby=='0'){ ?>
                                 <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next_forpitsco_duplicate(<?php echo $id[0];?>);" />
                                  
                                <?php   }
                                else if($id[2]==1){ ?>
                                 <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next_forpitsco_duplicate(<?php echo $id[0];?>);" />
                                 
                                <?php } else{ ?>  <!--/***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/-->
                                <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_next(<?php echo $id[0];?>);" />
                                <?php }?>
                            </div>
                        </div>
                    </div>
              	</form>

				<script type="text/javascript" language="javascript" >
					$(function(){
						var tabindex = 1;
						$('input,select').each(function() {
							if (this.type != "hidden") {
								var $input = $(this);
								$input.attr("tabindex", tabindex);
								tabindex++;
							}
						});
					});
					
					String.prototype.startsWith = function (str) {
						return (this.indexOf(str) === 0);
					}
					
					function ChkValidChar() {
						var txtbx = document.getElementById("score").value;
						if ((txtbx < 0) || (txtbx > 100)) // true
						{
							document.getElementById("score").value = "";
						}
                                                
//                                        if($("#ddlasstype").val()==0){
//                                                if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
//                                                {
//                                                    document.getElementById("score").value = "";
//                                                }
//                                            }
//                                            else{
//                                             if($("#prepostid").val()==1){   
//                                                if ((txtbx < 0) || (txtbx > 100)) // true
//                                                   {
//                                                        document.getElementById("score").value = "";
//                                                   }
//                                               }
//                                               else{
//                                                   if ((txtbx.startsWith("0")) || (txtbx > 100)) // true
//                                                    {
//                                                        document.getElementById("score").value = "";
//                                                    }
//                                               }
//                                            }
					}
	
					$("#attempts").ForceNumericOnly();
					$("#score").ForceNumericOnly();
					$("#timelimit").timepicker({});
					$('#timelimit').on('change', function() {
						$(this).focus();
					});

					
					$(function(){
						$("#testform").validate({
							ignore: "",
							errorElement: "dd",
							errorPlacement: function(error, element) {
								$(element).parents('dl').addClass('error');
								error.appendTo($(element).parents('dl'));
								if($(element).attr('id')=='testname'){
									error.addClass('msg').css('width','860px');
								}
								else if($(element).attr('id')=='ddlasstype'){
									error.addClass('msg').css('width','420px');
								}
								else {
									error.addClass('msg').css('width','270px');
								}
							},
							rules: { 
								testname: { required: true, lettersonly: true, 
								remote:{         url: "test/testassign/test-testassign-newtestdb.php",
								                 type:"post" , 
												data: {  
														testid: function() {
														return '<?php echo $id[0];?>';},
														oper: function() {
														return 'checktestname';}
														  
												 },
												 async:false  } },
								ddlasstype : { required: true },
								ddlexp: {
									required: {
										depends: function(element){
											return $("#ddlasstype").val()==1
										}
									}
								},
								timelimit: { required: true},
								score: { required: true },
								attempts: {
									required: {
										depends: function(element){
											return $("#ddlasstype").val()==0
										}
									}
								}	
							}, 
							messages: { 
								testname:{  required:  "Please enter assessment name", remote: "Assessment name already exists" },
								ddlasstype : { required: "Please select assessment type" },
								ddlexp : { required: "Please select expedition" },                         
								timelimit:{ required: "Please enter time limt"},
								score:{ required: "Please enter score"},		   						  
								attempts:{ required: "Please enter attempts" },	
							},
							highlight: function(element, errorClass, validClass) {
								$(element).parents('dl').addClass(errorClass);
								$(element).addClass(errorClass).removeClass(validClass);
							},
							unhighlight: function(element, errorClass, validClass) {
								 if($(element).hasClass('error')){
									$(element).parents('dl').removeClass(errorClass);
									$(element).removeClass(errorClass).addClass(validClass);
								}
							},
							onkeyup: false,
							onblur: true
						});
					});	
                </script>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");