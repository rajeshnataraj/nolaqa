<?php 
@include("sessioncheck.php");

$id = isset($method['id']) ? $method['id'] : 0;

$rpttitle = '';
$ownerid = '';
$preparefor = '';
$prepareon = '';
$state = '';
$stdid = '';
$rptstyle = '';
$school='';
$sec1 = '';
$sec2 = '';
$sec3 = '';
$sec4 = '';
$sec5 = '';
$sec6 = '';

$rptstylearr = array('test','Elementary Curriculum','Mid-Level Curriculum', 'High School Curriculum', 'Algebra', 'Star Academy', 'Generic');

if($id != '0'){
    
        $qry = $ObjDB->QueryObject("SELECT a.fld_title as rpttitle, a.fld_owner_id AS ownerid, a.fld_prepared_for AS preparefor,
                                    a.fld_prepared_on AS prepareon, a.fld_report_style AS rptstyle, a.fld_sec_std_add_summary AS sec1,
                                    a.fld_sec_bench_add_summary AS sec2, a.fld_sec_corr_by_std AS sec3, a.fld_sec_corr_by_title AS sec4,
                                    a.fld_sec_std_not_add AS sec5,a.fld_sec_prod_description AS sec6,b.fld_doc_id AS stdid, c.fld_name AS statename, c.fld_id AS state, fld_schoolid AS school
                                    FROM itc_bestfit_report_data AS a 
                                    LEFT JOIN itc_bestfit_rpt_doc_mapping AS b ON a.fld_id=b.fld_best_id
                                    LEFT JOIN itc_standards_bodies AS c ON b.fld_std_body=c.fld_id
                                    WHERE a.fld_id='".$id."' AND b.fld_flag='1'");
	$rowcrp = $qry->fetch_assoc();
	extract($rowcrp);
}
else{
		$fullname="Select Owner";
                $school='0';
}

?>

<script type="text/javascript">
$.getScript('reports/bestfit/reports-bestfit.js');
	$('#bbasicstandardinfo').addClass("active-first");
	$('#bselectproduct').removeClass("active-mid");
	$('#bgenerate').removeClass("active-mid");
	$('#bviewreport').removeClass("active-last");
        
	<?php if($id != '0' and $state != '') {?>	
	
		fn_showdocuments('<?php echo $state; ?>',<?php echo $id; ?>,'<?php echo $stdid; ?>');		
		
	<?php } ?>
</script>


<section data-type='2home' id='reports-bestfit-basic_info'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">Step 1: Basic Report Information</p>
                <p class="dialogSubTitleLight">Provide the following basic information about your report. Press &ldquo;Next Step&rdquo; to continue.</p>
                <div class="row rowspacer"></div>
            </div>
        </div> 
        
        <div class='row'>
            <div class='twelve columns formBase'>
                <div class='row'>
                    <div class='eleven columns centered insideForm'>
                    	<form id="frmbasicinfo" name="frmbasicinfo">
                            <div class="row">
                        	<div class='eight columns'>Basic Information</div>
                            </div>
                            <div class="row rowspacer">
                                <div class='six columns'>
                                    Report Title<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                        <input placeholder='Report Title' type='text' name="txtrpttitle" id="txtrpttitle" value="<?php echo $rpttitle; ?>" onBlur="$(this).valid();" />          							</dt>
                                    </dl>
                                </div>
                                
                                <div class='six columns'>
                                    Report Owner<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="selectowner" id="selectowner" value="<?php echo $uid; ?>" onchange="$(this).valid();" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="<?php echo $uid; ?>"><?php  echo $sessusrfullname; ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search Owner" />
                                                    <ul role="options">
                                                       <?php 
                                                        if($schoolid==0 and $indid==0)
                                                        {
                                                            $extraqry="AND fld_profile_id >1 AND fld_profile_id <=3";
                                                        }
                                                        else if($schoolid!=0 and $indid==0)
                                                        {
                                                            $extraqry="AND fld_profile_id >=7 AND fld_profile_id <=9 and fld_school_id='".$schoolid."' and fld_user_id='0'";
                                                        }
                                                        else if($schoolid==0 and $indid!=0)
                                                        {
                                                           $extraqry="AND fld_profile_id >=7 AND fld_profile_id <=9 and fld_school_id='0' and fld_user_id='".$indid."'";  
                                                        }

                                                        $qryname = $ObjDB->QueryObject("SELECT fld_id AS ownerid, CONCAT(fld_fname,' ', fld_lname) AS fullname 
                                                                                        FROM itc_user_master 
                                                                                        WHERE fld_delstatus='0' ".$extraqry." and fld_activestatus='1' ");

                                                        while($res = $qryname->fetch_assoc())
                                                        {
                                                            extract($res);
                                                            ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $ownerid;?>"><?php echo $fullname; ?></a></li>
                                                            <?php 
                                                        }?>
                                                    </ul>
                                                </div>
                                            </div>
                                        <dt>
                                    </dl>                            
                                </div>
                            </div>
                            
                            <div class="row rowspacer">
                        	<div class='six columns'>
                                    Prepared for
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Prepared for' type='text' name="txtpreparefor" id="txtpreparefor" value="<?php echo $preparefor; ?>" onBlur="$(this).valid();" />
                                        </dt>
                                    </dl>
                                </div>
                                <div class='six columns'>
                                    Date
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Prepared on' type='text' name="txtprepareon" id="txtprepareon" value="<?php echo $prepareon; ?>" onBlur="$(this).valid();" />
                                        </dt>
                                    </dl>
                                </div>
                            </div>
                             <div class="row rowspacer">
                        	<div class='six columns'>
                            	Report style<span class="fldreq">*</span>
                            	<dl class='field row'>   
                                    <dt class='dropdown'>   
                                        <div class="selectbox">
                                            <input type="hidden" name="selectrptstyle" id="selectrptstyle" value="<?php echo $rptstyle; ?>" onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="1"><?php if($rptstyle == '') {?>Report Style<?php } else { echo $rptstylearr[$rptstyle]; } ?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                            
                                                <input type="text" class="selectbox-filter" placeholder="Report style" />
                                                <ul role="options">
                                                   <li><a tabindex="-1" href="#" data-option="1">Elementary Curriculum</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="2">Mid-Level Curriculum</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="3">High School Curriculum</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="4">Algebra</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="5">Star Academy</a></li>
                                                   <li><a tabindex="-1" href="#" data-option="6">Generic</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <dt>
                                </dl>
                            </div>
                          <?php if ($sessmasterprfid==2){?>
                            <div class='six columns'>
                                School name
                                <dl class='field row'>   
                                    <dt class='dropdown'>   
                                        <div class="selectbox">
                                            <?php 

                               $schoolnames = $ObjDB->SelectSingleValue("SELECT a.fld_school_name
								    from itc_school_master AS a 
								    left join itc_bestfit_report_data  AS b on  b.fld_schoolid = a.fld_id
								    WHERE b.fld_delstatus='0' AND b.fld_id='".$id."'");         
                                            ?>
                                            <input type="hidden" name="selectschool" id="selectschool" value="<?php echo $school; ?>" onchange="$(this).valid();" />
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" data-option="<?php echo $school; ?>">
<?php if($school=='0'){ echo "Select School";} else {echo $schoolnames;}?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options">
                                                <input type="text" class="selectbox-filter" placeholder="Search Owner" />
                                                <ul role="options">
                                                   <?php                                                                                
                                                 $qryschool =$ObjDB->QueryObject("select fld_school_name as schoolname,fld_id as school
                                                                                from itc_school_master where fld_delstatus='0'");
                                                    
                                                    while($res = $qryschool->fetch_assoc())
                                                    {
                                                        extract($res);
                                                        
                                                    ?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $school;?>"><?php echo $schoolname; ?></a></li>
                                                        <?php 
                                                    }
                                                    ?>
                                                </ul>
                        </div>
                                        </div>
                                    <dt>
                                </dl>                            
                            </div>
                        </div>
                  <?php }?>
                        <div class="row rowspacer">
                        	<div class='eight columns'>Show sections</div>
                        </div>
                        <div class="row rowspacer">
                        	<ul class="six columns">
                                <li class="field">
                                  <label class="checkbox <?php if($sec1 == 1 or $sec1 == '') { echo 'checked'; } ?>" for="check1" style="width:250px;">
                                    <input name="checkbox[]" id="check1" value="1" type="checkbox" style="display:none;" <?php if($sec1 == 1 or $sec1 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Standards Addressed Summary
                                  </label>
                                  <label class="checkbox <?php if($sec2 == 1 or $sec2 == '') { echo 'checked'; } ?>" for="check2" style="width:250px;">
                                    <input name="checkbox[]" id="check2" value="2" style="display:none;" type="checkbox" <?php if($sec2 == 1 or $sec2 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Benchmarks Addressed Summary
                                  </label>
                                  <label class="checkbox <?php if($sec3 == 1 or $sec3 == '') { echo 'checked'; } ?>" for="check3" style="width:200px;">
                                    <input name="checkbox[]" id="check3" value="3" style="display:none;" type="checkbox" <?php if($sec3 == 1 or $sec3 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Correlations by Standard
                                  </label>
                                </li>
                          	</ul>
                            <ul class="six columns">
                                <li class="field">
                                  <label class="checkbox <?php if($sec4 == 1 or $sec4 == '') { echo 'checked'; } ?>" for="check4" style="width:200px;">
                                    <input name="checkbox[]" id="check4" value="4" type="checkbox" style="display:none;" <?php if($sec4 == 1 or $sec4 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Correlations by Title
                                  </label>
                                  <label class="checkbox <?php if($sec5 == 1 or $sec5 == '') { echo 'checked'; } ?>" for="check5" style="width:200px;">
                                    <input name="checkbox[]" id="check5" value="5" style="display:none;" type="checkbox" <?php if($sec5 == 1 or $sec5 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Standards Not Addressed
                                  </label>
                                  <label class="checkbox <?php if($sec6 == 1 or $sec6 == '') { echo 'checked'; } ?>" for="check6" style="width:170px;">
                                    <input name="checkbox[]" id="check6" value="6" style="display:none;" type="checkbox" <?php if($sec6 == 1 or $sec6 == '') { echo 'checked="checked"'; } ?> />
                                    <span></span> Product Description
                                  </label>
                                </li>
                          	</ul>
                        </div>
                            
                            <div class="row">
                        	<div class='eight columns'>Select Standard</div>
                            </div> 
                            
                            <div class="row rowspacer">
                                <div class='six columns'>
                                    Select State<span class="fldreq">*</span>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>   
                                            <div class="selectbox">
                                                <input type="hidden" name="selectstate" id="selectstate" value="<?php echo $state; ?>" onchange="$(this).valid(); fn_showdocuments(this.value,<?php echo $id; ?>);" />
                                                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                    <span class="selectbox-option input-medium" data-option="1"><?php if($state == '') {?>Select State<?php } else { echo $statename; } ?></span>
                                                    <b class="caret1"></b>
                                                </a>
                                                <div class="selectbox-options">
                                                    <input type="text" class="selectbox-filter" placeholder="Search State" />
                                                    <ul role="options">
                                                       <?php 
                                                        $qry = $ObjDB->QueryObject("SELECT fld_id AS stid, fld_guid AS stcode, fld_name AS stname 
																					FROM itc_standards_bodies ORDER BY stname 
");
                                                        while($res=$qry->fetch_assoc())
                                                        {
                                                           extract($res);	
                                                          ?>
                                                            <li><a tabindex="-1" href="#" data-option="<?php echo $stid;?>"><?php echo $stname; ?></a></li>
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
								<div class='twelve columns' id="dpdocuments">
								
								</div>
							</div>

                            
                            <div class="row rowspacer">
                                <div class='twelve columns' id="divdocgrades">

                                </div>
                            </div>
                        
                            <div class="row rowspacer">
                                <input class="btn <?php if($id != '0' and $state == '') {?>dim<?php } ?>" type="button" id="btnstep1"  style="width:200px; height:42px;float:right;" value="Next Step" onClick="fn_movenextstep(<?php echo $id; ?>,1);" />
                            </div>
							
							<input type="hidden" name="stdid" id="stdid" value="<?php echo $stdid; ?>">
							<input type="hidden" name="corid" id="corid" value="<?php echo $id; ?>">
							<input type="hidden" name="state" id="state" value="<?php echo $state; ?>">

                        </form>
                        
                        <script type="text/javascript" language="javascript">
                            /***addd category validate****/
                            $(function(){
                                    $("#txtprepareon").datepicker( {
                                            dateFormat: "yy-mm-dd",
                                            onSelect: function(dateText,inst){
                                                    $(this).parents().parents().removeClass('error');
                                            }
                                    });

                                    <?php 
                                            if($id == 0) {
                                    ?>
                                            $("#txtprepareon").datepicker("setDate", new Date());
                                    <?php
                                            }
                                    ?>

                                    $("#frmbasicinfo").validate({
                                            ignore: "",
                                            errorElement: "dd",
                                            errorPlacement: function(error, element) {
                                                $(element).parents('dl').addClass('error');
                                                error.appendTo($(element).parents('dl'));
                                                error.addClass('msg');
                                            },
                                            rules: {
                                                    txtrpttitle: { required: true, lettersonly:true, remote:{ url: "reports/bestfit/reports-bestfit-ajax.php",
                                                    data: {  
                                                                                    rptid: function() {
                                                                                    return '<?php echo $id;?>';},
                                                                                    oper: function() {
                                                                                    return 'checkreportname';}

                                                                     },
                                                                     type:"POST", 
                                                                     async:false } },
                                                    selectowner: {required: true },
                                                    selectrptstyle: { required: true },
                                                    selectstate: {required: true },
                                                    seldocument: { required: true }
                                            },

                                            messages: {
                                                    txtrpttitle: { required: "please type report name",lettersonly:"please enter letters and numbers only", remote: "report name already exists" },
                                                    selectowner: {required: "please select owner" },
                                                    selectrptstyle: { required: "please select report style" },
                                                    selectstate: { required: "please select state" },
                                                    seldocument: { required: "please select report style" }
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