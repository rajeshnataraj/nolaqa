<?php

@include("sessioncheck.php");
include("includes/digital_logbook_widget.php");

$gradeid = isset($method['id']) ? $method['id'] : '0';
$gradeid = explode(',',$gradeid);

$gradeperiodid=$gradeid[0];
$classid=intval($gradeid[1]);
$flag=$gradeid[2];


$gradename = '';
$gradestart = '';
$gradeend = '';
$gradefuncname = 'Save Period';
if($gradeperiodid!=0)
{
	$qrygradeperiod = $ObjDB->QueryObject("SELECT fld_grade_name, fld_start_date, fld_end_date 
												FROM itc_reports_gradebook_master 
												WHERE fld_id='".$gradeperiodid."' AND fld_delstatus='0'");
	
	$rowqrygradeperiod = $qrygradeperiod->fetch_assoc();
	extract($rowqrygradeperiod);
	
	$gradename = $fld_grade_name;
	$gradestart = date("m/d/Y",strtotime($fld_start_date));
	$gradeend = date("m/d/Y",strtotime($fld_end_date));
	$gradefuncname = 'Update Period';
}
?>
<section data-type='2home' id='reports-gradebook'>
	<link href='css/fixedtable.css' rel='stylesheet' type="text/css" />
	<script language="javascript">
   		$.getScript("reports/gradebook/reports-gradebook.js");
    </script>
    <style>
        .redcancel{
            font-family: 'source_sans_probold';
            font-size: 20px;
            line-height: 120%;
            text-align: center;
            color: #FFFFFF;
            border: 1px solid #7d180a;
            
            background: #c54224; /* Old browsers */
            background: -moz-linear-gradient(top, #ef6638 0%, #b62918 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ef6638), color-stop(100%,#b62918)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #ef6638 0%,#b62918 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #ef6638 0%,#b62918 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #ef6638 0%,#b62918 100%); /* IE10+ */
            background: linear-gradient(top, #ef6638 0%,#b62918 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ef6638', endColorstr='#b62918',GradientType=0 ); /* IE6-9 */
            
            -webkit-box-shadow: inset 0 1px 1px #fb926a,
                0 1px 2px rgba(0,0,0,0.61); /* Remove this line if you dont want a dropshadow on your buttons*/
            box-shadow: inset 0 1px 1px #fb926a,
                        0 1px 2px rgba(0,0,0,0.61); /* Remove this line if you dont want a dropshadow on your buttons*/
            
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box;
            cursor: pointer;
        }

        .redcancel:hover {
            background: #ed754e; /* Old browsers */
            background: -moz-linear-gradient(top, #ed754e 0%, #c93e23 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ed754e), color-stop(100%,#c93e23)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #ed754e 0%,#c93e23 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #ed754e 0%,#c93e23 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #ed754e 0%,#c93e23 100%); /* IE10+ */
            background: linear-gradient(top, #ed754e 0%,#c93e23 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ed754e', endColorstr='#c93e23',GradientType=0 ); /* IE6-9 */
        }
		.too-small-icon{
			font-size:1.2em;
		}
	</style>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Grade Book reports</p>				
                <p class="dialogSubTitleLight">Select the specific class you wish to view, then click "View Report".</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer' id="minheightstyle">
        	<div class='eleven columns centered insideForm'>
                <?php if($flag!='1')
                { 
                    ?>
                       <input type="hidden" name="classflag" id="classflag" value="0">
                <div class="row">
                    <div class='six columns'>
                        <!--Shows Class Dropdown-->
                        <div id="clspass">   
                            <dl class='field row'>
                                <div class="selectbox">
                                    <input type="hidden" name="classid" id="classid" value="">
                                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search Class">
                                        <ul role="options" style="width:100%">
                                            <?php 
                                            $qry = $ObjDB->QueryObject("SELECT fld_id AS classid, fld_class_name AS classname FROM itc_class_master WHERE fld_delstatus='0' AND fld_archive_class='0' AND (fld_created_by='".$uid."' OR fld_id IN (SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' AND fld_flag='1')) ORDER BY fld_class_name");
                                            if($qry->num_rows>0){
                                                while($row = $qry->fetch_assoc())
                                                {
                                                    extract($row);
                                                    ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onclick="fn_showperiod(); fn_showtable(<?php echo $classid;?>,0,0);"><?php echo $classname; ?></a></li>
                                                    <?php
                                                }
                                            }?>      
                                        </ul>
                                    </div>
                                </div> 
                            </dl>
                        </div>
                    </div>

                    <?php
                    /*The digital logbook should only appear for teachers and teacher admins.*/
                    if ($_SESSION['user_profile'] == 8 || $_SESSION['user_profile'] == 9 || $_SESSION['user_profile'] == 7 || $_SESSION['user_profile'] == 6) {
                        display_digital_logbook_widget($classid);
                    }
                    ?>
                </div>
                    <?php
                }
                else
                {   ?>
                    <script type="text/javascript" language="javascript">
                        fn_showperiod(); 
                        fn_showtable(<?php echo $classid;?>,0,0);
                    </script>
                    <input type="hidden" name="classflag" id="classflag" value="<?php echo $flag; ?>">
                    <input type="hidden" name="classid" id="classid" value="<?php echo $classid; ?>">
                    <?php 
                    $mm=1;
                }
                ?>
                <div id="showperioddiv" style="display:none">
                    <div class="row rowspacer">
                    	<form name="frmgrade" id="frmgrade">
                        	<div class='row'>
                                <div class='six columns'>
                                    Grade Period Name<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='Grade Period Name' type='text' id="txtgradename" name="txtgradename" value="<?php echo $gradename; ?>" onBlur="$(this).valid();">
                                        </dt>
                                    </dl>
                                </div>
                                
                                <div class='three columns'>
                                    Start date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="startdate1" readonly name="startdate1" class="quantity" placeholder='Start Date' type='text' value="<?php echo $gradestart; ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                                
                                <div class='three columns'>
                                    End date<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                             <input id="enddate1" readonly name="enddate1" class="quantity" placeholder='End Date' type='text' value="<?php echo $gradeend; ?>" >
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div class='row rowspacer'>
                        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="<?php echo $gradefuncname; ?>" onClick="removesections('#reports-gradebook'); fn_saveperiod(<?php echo $gradeperiodid;?>);" />
                        
						<?php if($gradeperiodid != 0) {?>
                        	<input class="redcancel" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-right:5px;" value="Cancel" onClick="fn_showperiod();" />
                        <?php }?>
                        
                    </div>
                </div>
            </div>
            
            <script type="text/javascript" language="javascript">
			
				//Function to validate the form
				$(function(){
					
					var tempclassid =$('#classid').val();
					var tempclassflag =$('#classflag').val(); 
					console.log("class=" + tempclassid + " | " + tempclassflag);
					var tabindex = 1;
					$('input,select').each(function() {
						if (this.type != "hidden") {
							var $input = $(this);
							$input.attr("tabindex", tabindex);
							tabindex++;
						}
					});
				});
			</script>
            <div class='row rowspacer' id="gradebook" style="padding-top:20px;">
                
            </div>
        </div>
    </div>
    
    <input type="hidden" id="scrolltop" value="0" />
    <input type="hidden" id="scrollleft" value="0" />
</section>
<?php
	@include("footer.php");