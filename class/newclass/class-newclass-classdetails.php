<?php 
@include("sessioncheck.php");
$tempid = isset($method['id']) ? $method['id'] : '';
$tempid=explode(',',$tempid);
$id=$tempid[0];
$flag=$tempid[1];

$classname = '';
$startdate = '';
$enddate = '';
$period = '';
$term = '';
$shedule = '';
$counter = 0;
$periodname = "Select Period";
$shedulename = "Select Schedule";
$graderounding = 0;

if($flag==1)
	$value="Save Changes";
else
	$value="Next Step";
	
if($id!=0){
	$qrystep1 = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fld_start_date AS startdate, fld_end_date AS enddate, fld_period AS period, fld_term AS term, 
										fld_shedule_type AS shedule 
									FROM itc_class_master 
									WHERE fld_id='".$id."' AND fld_delstatus='0'");
	$rowstep1 = $qrystep1->fetch_assoc();
	extract($rowstep1);	
	$periodname=$period;	
	$counter = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
											FROM itc_class_grading_scale_mapping 
											WHERE fld_class_id='".$id."' and fld_flag='1'");	
}
?>

<section data-type='#class-newclass' id='class-newclass-classdetails'>
	<script language="javascript">
		$('#people').removeClass("active-mid");
		$('#review').removeClass("active-last");
		$('#classdetails').parents().removeClass("dim");
		$('#classdetails').addClass("active-first");		
		$(function(){				
			var t4 = new $.TextboxList('#form_tags_newclass', 
			{
				unique: true, plugins: {autocomplete: {}},
				bitsOptions:{editable:{addKeys: [188]}}	});
				<?php 
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
											  FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='21' AND b.fld_access='1' AND a.fld_created_by='".$uid."' AND a.fld_delstatus='0' 
											  	AND b.fld_item_id='".$id."'");	
				if($qrytag->num_rows > 0) {
					while($restag = $qrytag->fetch_assoc()){
						extract($restag);
						?>
						t4.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			<?php 	}
				}
			?>				
			t4.getContainer().addClass('textboxlist-loading');				
			$.ajax({url: 'autocomplete.php', data: 'oper=new', dataType: 'json', success: function(r){
				t4.plugins['autocomplete'].setValues(r);
				t4.getContainer().removeClass('textboxlist-loading');					
			}});						
		});
	</script>
    
    <div class='container'>
        <div class='row'>
            <p class="dialogTitle">New Class Details</p>
            <p class="dialogSubTitleLight">Provide details about your new class below. <?php if($flag==0){?> Then click "Next step" to continue forward.<?php } else {?> Then click "Save changes" to save the details. <?php } ?></p>
        </div>

        <div class='row rowspacer'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                        <form id="classform" name="classform">
                        	<div class="formSubHeading">General information</div>
                            <div class='row'>
                            	<div class='four columns'>
                                 	Class Name<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Class Name' required='' type='text' id="classname" name="classname" value="<?php echo $classname;?>">
                                        </dt>                                        
                                    </dl>
                                </div>   
                                                             
                                <div class='four columns'>
                                	Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input  id="sdate1" readonly name="sdate1" class="quantity" placeholder='Start Date' type='text' value="<?php if($startdate!=''){ echo date("m/d/Y",strtotime($startdate));}?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class='four columns'>
                                	End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                           <input  id="edate1" readonly name="edate1" class="quantity" placeholder='End Date' type='text' value="<?php if($enddate!=''){ echo date("m/d/Y",strtotime($enddate));}?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                            
                            <div class='row rowspacer'>
                            	<div class='four columns'>
                                 	Select period<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                    	<dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="period" id="period" value="<?php echo $period; ?>"  onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="<?php echo $period; ?>" id="clearsubject" style="width:254px;"><?php echo $periodname; ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Period (1-20)">
                                                    <ul role="options" style="width:270px;">
														<?php                                                     
                                                        for($i=1; $i<=20;$i++){?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $i;?>"><?php echo $i; ?></a></li>
                                                        <?php 
                                                        }?>      
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>                                       
                                    </dl>
                                </div>  
                                                              
                                <div class='four columns'>
                                	Select schedule type<span class="fldreq">*</span>
                                	<dl class='field row'>   
                                    	<dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="shedule" id="shedule" value="<?php echo $shedule; ?>"  onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="<?php echo $shedule; ?>" id="clearsubject" style="width:254px;"><?php if($id!=0){ if($shedule==1){ echo "Traditional";} else{ echo "Block";}} else{ echo $shedulename; } ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">                                                    
                                                    <ul role="options" style="width:270px;">
                                                        <li><a tabindex="-1" href="#" data-option="1">Traditional</a></li>
                                                        <li><a tabindex="-1" href="#" data-option="2">Block</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </dt>                                       
                                    </dl>                                    
                                </div>
                                
                                <div class='four columns'>
                                	Term<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Term' type='text' id="term" name="term" value="<?php echo $term ;?>">
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                            
                            <div class='field row'>
                                To create new tag, type a name and press Enter.
                                <div class="tag_well">
                                    <input type="text" name="form_tags_newclass" value="" id="form_tags_newclass" />
                                </div>	
                            </div>
                            <!-- Created by Senthil on 27-06-2016. Grading scale template code starts Here -->
                            <div class='row'>
                            	<div class='twelve columns'>
                                    <div class="row rowspacer">
                                        <div class="six columns">
                                        Grading Scale Template
                                            <dl class='field row'>
                                                <dt class='dropdown'>
                                                    <div class="selectbox">
                                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                                        <span class="selectbox-option input-medium" data-option="">Select Template</span>
                                                        <b class="caret1"></b>
                                                      </a>
                                                      <div class="selectbox-options">
                                                        <input type="text" class="selectbox-filter" placeholder="Select Template" >
                                                        <ul role="options">
                                                            <?php
                                                            $qrygradetemp = $ObjDB->QueryObject("SELECT fld_id AS tempid, fld_class_id AS clsid, fld_temp_name AS tempname 
                                                                                                        FROM itc_class_grade_template WHERE fld_created_by='".$uid."'");	
                                                            if($qrygradetemp->num_rows > 0) {
                                                                        while($resgradetemp = $qrygradetemp->fetch_assoc()){
                                                                            extract($resgradetemp);
                                                                            ?>
                                                                           <li><a data-option="<?php echo $tempid;?>" onclick='fn_divtempshow(<?php echo $tempid;?>)'><?php echo $tempname; ?></a></li>
                                                                           <?php
                                                                    }
                                                                }
                                                           ?>
                                                        </ul>
                                                      </div>
                                                    </div>
                                                </dt>
                                            </dl>
                                        </div>
                                    </div>
                                    <div class="row rowspacer">
                                        <div id="templateselection" style="display:none;">
                                            <div class="twelve columns">
                                                We have noticed that you have modified this grading scale. Do you want to save it as template?
                                            </div>
                                            <div class="twelve columns">
                                            <div class="row">
                                                <div class='two columns'  style=" margin-top: 7px; margin-left: -25px;">
                                                    <input type="radio" id="tempyes" name="types" value="Yes" checked="checked" onchange="fn_change(1);"/>Yes
                                                    <input type="radio" id="tempno" name="types" onchange="fn_change(0);"/>No
                                                </div>
                                                 
                                           </div> 
                                            <div class='field row'>
                                                <script>
                                                    function fn_change(id){
                                                      if(id == '0')  
                                                      {
                                                          $('#tempname').val('');
                                                          $('#tempname').attr("disabled", "disabled");

                                                      }
                                                      else{
                                                          $('#tempname').removeAttr("disabled"); 
                                                          $('#tempname').val('');
                                                      }
                                                    }

                                                </script>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                                <div class="tag_well text" style="width:150px;height:20px;margin-top:-55px; margin-left:95px;">
                                                    <input type="text" placeholder='Template Name' name="tempname" value="" id="tempname" />
                                                    <input type="hidden" id="tempoption" name="tempoption" value="">
                                                </div>	


 </div>
                                            </div>
                                       </div>     
                                    </div>
                                    
                                    
                                    <!-- Created by Senthil on 27-06-2016. Grading scale template code ends Here -->
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
                                            $qry=$ObjDB->QueryObject("SELECT fld_grade, fld_lower_bound,fld_upper_bound, fld_roundflag AS graderounding 
																	FROM itc_class_grading_scale_mapping 
																	WHERE fld_class_id='".$id."' AND fld_flag='1'");											
                                            if($qry->num_rows > 0)
                                            {
                                                $count=1;
                                                while($row=$qry->fetch_assoc())
                                                {
                                                    extract($row);?>
                                                    <div class="row" id="TextBoxDiv<?php echo $count;?>">
                                                        <div class="three columns">
                                                            <dl class='field row'>
                                                                <dt class='text'>
                                                                    <input type='textbox' id='lettergrade<?php echo $count;?>' name="lettergrade<?php echo $count;?>" class="gradedet"  maxlength="6" value="<?php echo $fld_grade;?>"  onchange='fn_divshow(1);'/>
                                                                </dt>
                                                            </dl>
                                                        </div>
                                                        <div class="three columns">
                                                            <dl class='field row'>
                                                                <dt class='text'>
                                                                <input type='textbox' id='lowerbound<?php echo $count; ?>' name="lowerbound<?php echo $count;?>" maxlength="3" value="<?php echo $fld_lower_bound;?>" onkeypress="return isNumber(event)"  onchange='fn_divshow(1);'/><span style="position: absolute;right:10px;">%</span>
                                                                </dt>
                                                            </dl>
                                                        </div>
                                                        <div class="three columns">
                                                            <dl class='field row'>
                                                                <dt class='text'>
                                                                    <input type='textbox' id='higherbound<?php echo $count; ?>'  name="higherbound<?php echo $count;?>" maxlength="4" value="<?php echo $fld_upper_bound;?>" onkeypress="return isNumber(event)"  onchange='fn_divshow(1);'/>
                                                                    <span style="position: absolute;right:10px;">%</span>
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
                                                                <input type='textbox' id='lettergrade<?php echo ($i+1);?>' name="lettergrade<?php echo ($i+1);?>" class="gradedet"  maxlength="6" value="<?php echo $grade[$i]; ?>" onchange='fn_divshow(1);'>
                                                            </dt>
                                                        </dl>
                                                    </div>
                                                    <div class="three columns">                                                  
                                                        <dl class='field row'>
                                                            <dt class='text'>
                                                                <input type='textbox' id='lowerbound<?php echo ($i+1);?>' name="lowerbound<?php echo ($i+1);?>" maxlength="3" value="<?php echo $lbound[$i]; ?>"  onkeypress="return isNumber(event)" onchange='fn_divshow(1);'><span style="position: absolute;right:10px;">%</span>
                                                            </dt>
                                                        </dl>
                                                    </div>
                                                    <div class="three columns">   
                                                        <dl class='field row'>
                                                            <dt class='text'>
                                                                <input type='textbox' id='higherbound<?php echo ($i+1);?>'  name="higherbound<?php echo ($i+1);?>" maxlength="4" value="<?php echo $ubound[$i]; ?>" onkeypress="return isNumber(event)" onchange='fn_divshow(1);'>
                                                                <span style="position: absolute;right:10px;">%</span>
                                                            </dt>
                                                        </dl>
                                                    </div>
                                                </div>
                                            <?php 
                                                } // for ends
                                            } // if else ends
                                        ?>
                                        <!-- Created by Senthil on 27-06-2016. Grading scale template code-->
                                        <script>
                                        function fn_divshow(shwid){
                                           if(shwid== '1')
                                           { 
                                             $("#templateselection").show();  
                                           }
                                           else{
                                             $("#templateselection").hide();    
                                           }
                                        }
                                        function fn_divtempshow(tempid){
                                            var dataparam = "oper=clsgradetemplate&tempid="+tempid;
                                            $.ajax({
                                                    type	: "POST",
                                                    cache	: false,
                                                    url		: 'class/newclass/class-newclass-classajax.php',
                                                    data	: dataparam,
                                                    success : function(data) {
                                                        var res = data.split("~");
                                                            $('#TextBoxesGroup').html(res[0]);
                                                            $('#grade').attr("checked",false); 
                                                            $('#counter').val(res[2]);
                                                            if(res[1]==1)
                                                            {
                                                                $('#grade').attr("checked",true);                                                            
                                                            }
                                                    }
                                            });
                                            return false;
                                        }
                                        </script>
                                        <!-- Created by Senthil on 27-06-2016. Grading scale template code -->
                                     </div>
                                    <div class="row rowspacer">
                                        <div class="twelve columns">
                                            Use Grade Rounding &nbsp;<input type="checkbox" name="grade" id="grade" <?php if($graderounding == 1){echo "checked"; } ?> onchange='fn_divshow(1);' />
                                        </div>
                                    </div>
                           			
                                    <script type="text/javascript">                         
                                        $(document).ready(function(){
                                            var remove='';										
                                            <?php 
                                                if($counter==0){ ?>
                                                    var counter = 5;
                                                    $('#counter').val(counter);
                                            <?php
                                                }else{
                                            ?>
                                                    
                                                    var counter=<?php echo $counter;?>;
                                                    $('#counter').val(counter);
                                            <?php
                                                }
                                            ?>											
                                            
                                            $("#addButton").click(function () {	
                                                fn_divshow(1);
                                                if(counter>10){
                                                    alert("Only 10 textboxes allowed");
                                                    return false;
                                                }
                                                var counter = $("#TextBoxesGroup").children("div").length;
                                               	
												counter++;
												
												var newTextBoxDiv = $(document.createElement('div')).attr("id", 'TextBoxDiv' + counter).addClass('row');											
                                                
												var divcontent="<div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='higherbound" + counter + "' id='higherbound" + counter + "' class='text' maxlength='3' onkeypress='return isNumber(event)'><span style='position: absolute;right:10px;'>%</span></dt></dl></div>";												
                                                
												newTextBoxDiv.html("<div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='lettergrade" + counter + "' id='lettergrade" + counter + "' class='text' maxlength='3'/></dt></dl></div><div class='three columns'><dl class='field row'><dt class='text'><input type='text' name='lowerbound" + counter + "' id='lowerbound" + counter + "' class='text' maxlength='3' onkeypress='return isNumber(event)' /><span style='position: absolute;right:10px;'>%</span></dt></dl></div>"+divcontent);
                                                newTextBoxDiv.appendTo("#TextBoxesGroup");
                                                 $('#counter').val(counter);
                                                											
                                            });											 
                                            
                                            $("#removeButton").click(function () {													
                                                if(counter==2){														
                                                    alert("No more textbox to remove");
                                                    return false;
                                                }   											 
                                                fn_divshow(1);
                                                var counter = $("#counter").val();
                                                $("#TextBoxDiv" + counter).remove();
                                                remove+=counter+"~";
                                                $('#removecounter').val(remove);
												counter--;
                                                $('#counter').val(counter);	
                                            });
                                        });
                                        
                                        $('.gradedet').keypress(function (e) {
                                        var regex = new RegExp("^[A-Z0-9-.]+$");
                                        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
                                       
                                        if (regex.test(str)) {
                                            return true;
                                        }

                                        e.preventDefault();
                                        return false;
                                    });
                                    </script>                                            
                                    <div class='row'>
                                        <div class='twelve columns'>
                                            <div class="tRight">
                                               <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_saveclass(<?php echo $id.",".$flag;?>);" />
                                            </div>
                                        </div>
                                    </div>
                          		</div>
                         	</div>
                            <input type="hidden" name="lg" id="lg" />
                            <input type="hidden" name="lb" id="lb" />
                            <input type="hidden" name="hb" id="hb" />
                            <input type="hidden" name="boxid" id="boxid" />
                            <input type="hidden" name="counter" id="counter" value="<?php echo $counter; ?>" />
                            <input type="hidden" name="removecounter" id="removecounter" />
                    	</form>
                        
						<script type="text/javascript" language="javascript">
							$("#sdate1, #edate1").datepicker( {
								onSelect: function(dateText,inst){
									$(this).parents().parents().removeClass('error');
								}
							});							
							$(function(){
								$("#classform").validate({
									ignore: "",
										errorElement: "dd",
										errorPlacement: function(error, element) {
											$(element).parents('dl').addClass('error');
											error.appendTo($(element).parents('dl'));
											error.addClass('msg'); 	
									},
									rules: { 
										classname: { required: true, lettersonly: true, placeholder: true}, //remote:{ url: "class/newclass/class-newclass-classajax.php?oper=checkclassname&classid="+<?php //echo $id;?>, type:"post" } 										
										sdate1: { required: true },
										edate1: { required: true, greaterThan: "#sdate1" },
										period: { required: true },
										term: { required: true },
										shedule: { required: true }                                                                                
									}, 
									messages: { 
										classname:{  required:  "Please enter class name"}, //remote: "class name already exists!"               
										sdate1:{  required: "Select the start date" },
										edate1:{ required: "Select the end date", greaterThan: "Enddate must be greater"},		   						  
										period:{ required:  "Please select period" },
										term: { required: "Please enter term" },
										shedule: {  required: "Please select schedule" }                                                                               
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
                	</div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");