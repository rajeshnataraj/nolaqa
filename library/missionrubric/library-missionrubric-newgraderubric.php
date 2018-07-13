<?php
@include("sessioncheck.php");

$id  = isset($method['id']) ? $method['id'] : '0';
$ids=explode(",",$id);

$expeditionid=$ids[0];
$rubricnameid=$ids[1];


$exptitle=$ObjDB->SelectSingleValue("SELECT a.fld_mis_name FROM itc_mission_master AS a
								 LEFT JOIN itc_license_mission_mapping AS b ON a.fld_id=b.fld_mis_id 
								 WHERE a.fld_id='".$expeditionid."' AND b.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_delstatus='0'
								 GROUP BY a.fld_id");

  $createbtn = "Save as rubric";
  $rubricname=$ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_mis_id='".$expeditionid."' AND fld_delstatus = '0' AND fld_created_by IN(2,".$uid.") AND fld_id='".$rubricnameid."'"); //new line  AND fld_created_by='".$uid."'
  
?>
<section data-type='#library-rubric' id='library-missionrubric-newgraderubric'>
<script type="text/javascript" charset="utf-8">		
	$.getScript("library/missionrubric/library-missionrubric.js");
        
</script>
<div class='container'>
    <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $rubricname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
    </div>
    
    <div class='row formBase rowspacer'>
        <div class='eleven columns centered insideForm'>
            <form name="rubricforms" id="rubricforms">
            <div class='row  rowspacer'> 
                <div class='six columns'>
                    Rubric Name<span class="fldreq">*</span>
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Rubric Name' type='text' id="txtrubricnameforsaveas" name="txtrubricnameforsaveas" value="<?php echo $rubricname;?>" onBlur="$(this).valid();" />
                        </dt>
                    </dl>
                </div>
                <div class='six columns'>
                  <input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;  margin-top: 25px;" value="Add New Section" onClick="fn_addnewsection(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0','','1','1');"  />
              </div>
             </div>
            
            
          <div class="row">
                <div class='rowspacer formBase'>  
                    <div id="expsetting" class='row rowspacer'>  
                        <div class='span10 offset1'>
                            <table class='table table-hover table-striped table-bordered setbordertopradius' id="mytable" >
                                <thead >
                                    <tr style="cursor:default;">
                                        <th width="18%">Category</th>
                                        <th width="14%" class='centerText'>4</th>
                                        <th width="14%" class='centerText'>3</th>
                                        <th width="13%" class='centerText'>2</th>
                                        <th width="13%" class='centerText'>1</th>
                                        <th width="13%" class='centerText'>0</th>
                                        <th width="7%" class='centerText'>Weight</th>
                                        <th width="7%" class='centerText'>Score</th>
                                    </tr>
                                </thead>
                                 <style> .bcolor{background: #F1F1F3;} .m{ text-decoration: overline;} .mm{text-decoration: underline;} *
                                    .table tr td:first-child {   padding-left: 20px;   }
                                </style>
                            </table>
                        </div>
					</div>
					<input type="hidden" name="hidaddnewsec" id="hidaddnewsec" value="1" />
                  	<input type="hidden" name="hidtrrubric" id="hidtrrubric" value="0" />
					<input type="hidden" name="hidtrcrubric" id="hidtrcrubric"  value="0" />
                  	<input type="hidden" name="hidrubriccount" id="hidrubriccount" value="1" />
                  	<input type="hidden" name="hiddbrubriccount" id="hiddbrubriccount" value="1" />
                  	<input type="hidden" name="hiddbrdestcount" id="hiddbrdestcount" value="1" />
                  	<input type="hidden" name="hiddbtotscore" id="hiddbtotscore" value="0" />
                  	<input type="hidden" name="hidneworimport" id="hidneworimport" value="1" />
                  	<input type="hidden" name="rowcount" id="rowcount" value="1" />                 
			   		</div>
					<div class='twelve columns'>
						  <div class='six columns'>							   
						  </div>
						  <div class='six columns'>
							 <span style="float: right; margin-top: 32px; margin-right: 18px; ">Total Score:&nbsp;&nbsp;&nbsp;<mm id='totscore'></mm></span>
						  </div>
					</div>
				</div>
				<input type="hidden" name="rubnamecount" id="rubnamecount" value="<?php echo $rubricname; ?>">
				<input type="hidden" name="rubnamecount" id="rubnamecount" value="<?php echo $rubricname; ?>">
				<div class='row rowspacer' id="viewreportdiv">
					<input class="darkButton" type="button" id="btnstep2" style="width:200px; height:42px; float:right;" value="<?php echo $createbtn;?>" onClick="fn_saveasrubric(<?php echo $expeditionid;?>,<?php echo $rubricnameid;?>,'0');"  />
				</div>
            </form> 
        </div>
    </div>   
 </div>
    <script type="text/javascript" language="javascript">
    //Function to validate the form
    $("#rubricforms").validate({
            ignore: "",
            errorElement: "dd",
            errorPlacement: function(error, element) {
                    $(element).parents('dl').addClass('error');
                    error.appendTo($(element).parents('dl'));	
                    error.addClass('msg');
            },
            rules: {
                    txtrubricname: { required: true, lettersonly: true, 
                    remote:{ 
                       url: 'library/rubric/library-rubric-graderubricajax.php',
                        type:"post", 
                        data: {  
                                uid: function() {
                                return '<?php echo $expeditionid;?>';},
                                oper: function() {
                                return 'checkrubricname';}
                            },
                            async:false } 
                        }
            }, 
            messages: { 
                    txtrubricname: { required: "Please type Rubric Name", remote: "Rubric Name already exists" }

            },
            highlight: function(element, errorClass, validClass) {
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
    </script>
</section>