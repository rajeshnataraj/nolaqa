<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
        
        if($oper=="showmisass" and $oper != " ")
	{
            $misids = isset($method['misids']) ? $method['misids'] : '0';		
            $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
            $schlicenseid = isset($method['schlicenseid']) ? $method['schlicenseid'] : '';
            if($misids!=''){
            ?>
                <div class="row rowspacer"> Select Mission Assessment
                <style>
                  h2.acc_trigger {
                      padding: 0;
                      margin: 0 0 5px 0;
                      width: 100%;
                      font-size: 20px;
                      font-weight: normal;
                      float: left;
                      margin-bottom:0;
                  }
                  h2.acc_trigger a {
                      text-decoration: none;
                      display: block;
                      padding: 0 0 0 15px;
                  }
                </style>
                <script type="text/javascript" language="javascript">
                    jQuery(document).ready(function ($) {
                    //Set default open/close settings
                      var divs=$('.accordion>div').hide(); //Hide/close all containers	
                      $(".accordion>div:first").show();
                      $(".accordion>h2>a>input:first").addClass('removeButton').removeClass('addButton');
                      $(".accordion>h2>a>input:first").val('-');
                         var h2s = $(".accordion>h2").click(function () {
                      if($(this).children().children('input').hasClass('addButton'))
                      {
                          $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                          $(".accordion>h2>a>input").val('+');
                          $(this).children().children('input').addClass('removeButton').removeClass('addButton');
                          $(this).children().children('input').val('-');
                      }
                      else
                      {	
                          $(".accordion>h2>a>input").addClass('addButton').removeClass('removeButton');
                          $(".accordion>h2>a>input").val('+');
                          $(this).children().children('input').addClass('addButton').removeClass('removeButton');
                          $(this).children().children('input').val('+');	
                      }
                          h2s.not(this).removeClass('active')
                          $(this).toggleClass('active')
                          divs.not($(this).next()).slideUp()
                          //var spans=$('.accordion>span').hide(); 
                          //spans.not($(this).next()).slideUp()
                          $(this).next().slideToggle()
                          return false; //Prevent the browser jump to the link anchor

                        });
                    });
                </script>

                <div class="accordion">
                    <?php
                        $distadminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '0'  AND fld_district_id='".$sendistid."' 
                                                            AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '6'");

                        $schladminid=$ObjDB->SelectSingleValue("SELECT fld_id FROM itc_user_master WHERE fld_school_id = '$senshlid' 
                                                                    AND fld_delstatus = '0' AND fld_user_id='0' AND fld_profile_id = '7'");
                        
                        $qrytmis = $ObjDB->QueryObject("SELECT fld_mis_name as misname, fld_id as misid FROM itc_mission_master WHERE fld_id IN(".$misids.")");

                        if ($qrytmis->num_rows > 0) {
                            $a = 0;
                            $x=0;
                            $y=0;
                            $z=0;
                            while ($rowtmis = $qrytmis->fetch_assoc()) {

                                extract($rowtmis);
                                ?>
                                <h2 class="acc_trigger"><a href="#"><input type="button" class="addButton" value="+" >&nbsp;<?php echo $misname;?></a></h2>
                                <?php

                                    if ($qrytmis->num_rows > 0) {
                                        ?>
                                            <div class="acc_container">

                                                            <table cellpadding="19px" cellspacing="19px" > 
                                                                <tr>
                                                                    <?php

//                                                                    $qrymistestdetail= $ObjDB->QueryObject("select fld_id AS testid,fld_test_name AS testname
//                                                                                                        from itc_test_master where  
//                                                                                                        fld_mist='".$misid."' and fld_ass_type='2' and fld_delstatus='0' ");
                                                                    $qrymistestdetail= $ObjDB->QueryObject("SELECT a.fld_id AS testid, a.fld_test_name AS testname FROM itc_test_master AS a
                                                                                                        LEFT JOIN
                                                                                                        `itc_license_assessment_mapping` AS b ON a.fld_id = b.fld_assessment_id
                                                                                                        LEFT JOIN
                                                                                                        `itc_license_track` AS c ON b.fld_license_id = c.fld_license_id
                                                                                                        WHERE c.fld_school_id='".$senshlid."' AND c.fld_start_date<='".$date."' AND c.fld_end_date>='".$date."'
                                                                                                                        AND a.fld_delstatus = '0' AND c.fld_delstatus = '0' AND c.fld_user_id = '".$indid."' and b.fld_access = '1'
                                                                                                                        and a.fld_ass_type='2' and b.fld_license_id='".$schlicenseid."'
                                                                                                                        and fld_profile_id='2' and a.fld_mist='".$misid."' and a.fld_delstatus = '0'
                                                                                                                UNION ALL
                                                                                                        select fld_id AS testid, fld_test_name AS testname
                                                                                                        from itc_test_master where fld_ass_type='2' and  fld_created_by IN('".$uid."','".$distadminid."','".$schladminid."') 
                                                                                                        and fld_mist='".$misid."' and fld_delstatus = '0'");
                                                                    if ($qrymistestdetail->num_rows > 0) {
                                                                        $i=1;
                                                                        while ($rowtmis1 = $qrymistestdetail->fetch_assoc()) {
                                                                        extract($rowtmis1);
                                                                        $chkvalmis = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_mis_ass 
                                                                                                                                    WHERE fld_test_id='".$testid."' AND fld_sch_id='".$scheduleid."' 
                                                                                                                                    AND fld_flag='1' AND fld_schtype_id='20'");
                                                                        ?>
                                                                    <td>
                                                                        <dt style="margin-left: 25px;">
                                                                            <input id="chkboxtest_<?php echo $testid; ?>" type="checkbox" value="<?php echo $misid;?>" name="chkbox" <?php if($chkvalmis=='1' || $scheduleid=='0'){echo "checked"; } ?>  >
                                                                            <span></span>
                                                                            <?php echo $testname; ?>
                                                                        </dt>
                                                                    </td>
                                                                    <?php
                                                                        if($i%3==0)
                                                                        {
                                                                                echo "</tr><tr>";
                                                                        }
                                                                        $i++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            </table>

                                            </div>
                                        <?php
                                    }
                                     $a++;
                            }

                    }?>
                </div>
            </div>
            <?php
            }
        }
        
        if($oper=="missionloadcontent" and $oper != " ")
	{
		$sid = isset($method['sid']) ? $method['sid'] : '0';		
		$licenseid = isset($method['lid']) ? $method['lid'] : '0';
		$classid = isset($method['classid']) ? $method['classid'] : '';
		$mtype = isset($method['type']) ? $method['type'] : '0';
		$flag=0;
// Checking to module template type
		if($mtype == 0) {
		      $mtype=1;
		      $assigntype = 0;
		    }
		    else
		    {
		      $assigntype = 1;
		    }
		
		$qryschdet=$ObjDB->NonQuery("SELECT COUNT(b.fld_id) AS countschedulestumap,a.fld_flag as flag,a.fld_schedule_name AS schedulename,a.fld_startdate AS startdate,a.fld_numberofcopies AS numberofcopies,a.fld_numberofrotations AS numberofrotations,a.fld_rotationlength AS rotationlength
                                    FROM itc_class_rotation_mission_mastertemp AS a
                                    LEFT JOIN itc_class_rotation_mission_student_mappingtemp AS b ON b.fld_schedule_id=a.fld_id
                                    WHERE a.fld_id='".$sid."' AND b.fld_flag='1'");
									
		if($qryschdet->num_rows>0)
		{
		$row=$qryschdet->fetch_assoc();
		extract($row);
			if($flag==0)
			{
				$count=0;
			}
			else
			{
				$count=1;
			}
		}
		else
		{
			$count=0;
			$countschedulestumap=0;
		}
		
		if($count==0)
		{
			$type="create";
		}
		else
		{
			$type="update";
		}
		
	if($countschedulestumap==0)
	{
		$countstudent=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' AND fld_flag=1");
	}
	else
	{
		$countstudent=$countschedulestumap;
	}
	
	
	if($sid==0 or $flag==0)
	{
		$value="Next";
	}
	else
	{
		if($assigntype == 0)
		   $value="Next";
		else
		$value="View Schedule";
	}
        
        
        
        if($sid!=0)
        {
            $expids='';
            $qrygetexpeditionclasssch=$ObjDB->QueryObject("SELECT fld_mission_id AS expid FROM itc_class_rotation_schedule_mission_mappingtemp
                                                                    WHERE fld_schedule_id='".$sid."' AND fld_flag='1'"); //a.fld_schedule_name AS schedulename

            if($qrygetexpeditionclasssch->num_rows>0)
            {
                $m=1;
                while($rowclasssch=$qrygetexpeditionclasssch->fetch_assoc())
                {
                        extract($rowclasssch);

                        if($m=='1'){
                                $expids=$expid;
                        }
                        else
                        {
                                $expids=$expids.",".$expid;
                        }
                        $m++;
                }
            }

            $sqry=" AND fld_mis_id IN (".$expids.")";

        }
        else
        {
                $sqry='';
        }

		
?>
				<form id="sform">
					<div class='row'>
							<div class='four columns'>
                            	Number of copies<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                              <input placeholder='number of copies' required='' type='text' id="numberofcopies" name="numberofcopies" value="<?php if($numberofcopies!=''){ echo $numberofcopies; }else { echo "1";}?>" <?php if($count==1){?> readonly title="Read only" <?php }?> onkeypress="return isNumberKey(event);">
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	 Number of rotations<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                          <input placeholder='number of rotations' required='' type='text' id="numberofrotations" name="numberofrotations" value="<?php if($numberofrotations>0){ echo $numberofrotations ;} else { echo "1";} ?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                                <div class='four columns'>
                                	Rotation  length<span class="fldreq">*</span>
                                    <dl class='field row'>
                                        <dt class='text'>
                                            <input placeholder='rotation length' required='' type='text' id="rotationlength" name="rotationlength" value="<?php if($rotationlength!=''){echo $rotationlength;}else { echo "7";}?>" onkeypress="return isNumberKey(event);" <?php if($count==1){?> readonly title="Read only" <?php }?>>
                                        </dt>                                        
                                    </dl>
                                </div>
                            </div>
                         </form>
					
							
                            <script>fn_loadmission(<?php echo $sid.",".$mtype.",".$assigntype;?>);</script>
                           
                            <div id="missions"> 
                                                       
                            </div> 
                            
                            <!-- Mission Schedule Code developed by Mohan M 24-3-2016-->
                            <!-- Mohan M-->
<div class="row " id="rubriccontent" style="">
    <div class='twelve columns'>
        <?php
        if($sessmasterprfid == 5)
        { 	//For Teacher inv

                 $qry = "SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by IN (2 , ".$uid.")  ".$sqry."
                                        UNION SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' and fld_district_id = '0' ".$sqry." and fld_school_id = '0' and fld_user_id='".$indid."'";


        }
        else if($sessmasterprfid == 7)
        { 	//For School Admin

                $qry = "SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by IN (2 , ".$uid.") ".$sqry."
                        UNION 
                        SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' and fld_district_id = '".$sendistid."' ".$sqry." and fld_school_id = '0' order by fld_profile_id ASC";

        }
        else
        { 	//For Teacher

                $qry="SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' AND fld_created_by ='2' ".$sqry."
                                        UNION SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0'  ".$sqry."
                                and fld_district_id = '".$sendistid."' and fld_school_id = '0'
                                        UNION  SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0'  ".$sqry."
                                and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
                                        UNION  SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_delstatus = '0' ".$sqry."
                                and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

        }
        ?>
        Select Grading Rubric
        <div> 
            <dl class='field row' >  
                <?php
                $rubricvalues=array();
                $qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
                if($qry_for_get_all_expedition->num_rows>0)
                {
                    $i=1; 
                    ?><table cellpadding="19px" cellspacing="19px" > <tr><?php
                    while($row=$qry_for_get_all_expedition->fetch_assoc())
                    {
                        extract($row); 

                        $chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
                                                                                                        WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$sid."' 
                                                                                                        AND fld_delstatus='0' AND fld_schedule_type='19'");

                        ?>
                        <td>
                            <dt>       
                                <input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="<?php echo $fld_mis_id; ?>" name="chkbox" <?php if($chkval=='1' || $sid=='0'){echo "checked"; } ?>  >
                                <span></span>
                                <?php echo $fld_rub_name; ?>
                            </dt>
                        </td>
                        <?php
                        if($i%3==0)
                        {
                            echo "</tr><tr>";
                        }
                        $i++;

                    }
                    ?> </tr></table> <?php
                }
                ?>
            </dl>
        </div>
    </div>	
</div>
                            <div id="showmisass" class='row rowspacer'></div>
 <!-- Mohan M-->      
                          
                            <!-- Mission Schedule Code developed by Mohan M 24-3-2016-->        
			    <div class="row rowspacer" style="margin-top:20px;">
                                <div class="tLeft" style="color:#F00;">
                                </div>
                                <div class="tRight" id="modnxtstep" style="display:none;">
                                    <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;" value="<?php echo $value;?>" onClick="fn_savemissionschedule(0);" />
                                </div>
                            </div>
                            <?php
		                    if($assigntype == 0) {
		                      $sid = 0;
		                      $type="create";
		                    }
                            ?>
                             <input type="hidden" id="scount" value="<?php echo $countstudent; ?>"/>
                             <input type="hidden" id="rotationtype" value="<?php echo $type; ?>"/>
                             <input type="hidden" id="scheduleid" value="<?php echo $sid; ?>"/>
                             
				  

 <script type="text/javascript" language="javascript">
       
		$(function(){
            $("#sform").validate({
                	ignore: "",
					errorElement: "dd",
					errorPlacement: function(error, element) {
						$(element).parents('dl').addClass('error');
						error.appendTo($(element).parents('dl'));
						error.addClass('msg'); 		
				},
                rules: { 
					numberofcopies: { required: true },
					numberofrotations: { required: true },
					rotationlength: { required: true }
				}, 
                messages: { 	  
					numberofcopies:{ required:  "please enter number of copies" },
					numberofrotations: {   required: "Enter number of rotations" },
					rotationlength: {   required: "please enter rotation length" }
					
					
					
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
<?php
}
     
/********Mohan M***********/
if($oper=="showrubric" and $oper!='')
{
    $misids= isset($method['list4']) ? $method['list4'] : '0';
    
     $pitscoadmins=$ObjDB->SelectSingleValue("SELECT group_concat(fld_id) FROM itc_user_master WHERE fld_profile_id='2' AND fld_delstatus='0' AND fld_activestatus='1'");
    
    if($misids!='')
    {
        if($sessmasterprfid == 5)
        { 	//For Teacher inv

                 $qry = "SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
                                        UNION SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' and fld_district_id = '0' and fld_school_id = '0' and fld_user_id='".$indid."'";


        }
        else if($sessmasterprfid == 7)
        { 	//For School Admin

                $qry = "SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' AND fld_created_by IN (".$pitscoadmins." , ".$uid.") 
                                        UNION 
                                        SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' and fld_district_id = '".$sendistid."' and fld_school_id = '0' order by fld_profile_id ASC";

        }
        else
        { 	//For Teacher

                $qry="SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id ,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' AND fld_created_by IN(".$pitscoadmins.")
                                UNION SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' 
                                and fld_district_id = '".$sendistid."' and fld_school_id = '0'
                                UNION  SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' 
                                and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' and fld_profile_id='7'
                                UNION  SELECT fld_rub_name, fld_id, fld_mis_id, fld_created_by, fld_profile_id,fn_shortname (CONCAT(fld_rub_name), 1) AS shortname  FROM itc_mis_rubric_name_master WHERE fld_mis_id IN (".$misids.") and fld_delstatus = '0' 
                                and fld_district_id = '".$sendistid."' and fld_school_id='".$schoolid."' AND fld_created_by ='".$uid."' order by fld_profile_id ASC";

        }

        $rubricvalues=array();
        $qry_for_get_all_expedition = $ObjDB->QueryObject($qry);
        if($qry_for_get_all_expedition->num_rows>0)
        { ?>
            <div class="row" > 
                <div class='twelve columns'>Select Grading Rubric
                    <div >
                        <dl class='field row' > 
                            <?php
                            $i=1; 
                            ?><table cellpadding="19px" cellspacing="19px" > <tr><?php
                            while($row=$qry_for_get_all_expedition->fetch_assoc())
                            {
                                extract($row); 

                                $chkval = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_expmis_rubricmaster 
                                                                                                                WHERE fld_rubric_id='".$fld_id."' AND fld_schedule_id='".$scheduleid."' 
                                                                                                                AND fld_delstatus='0' AND fld_schedule_type='19'");

                                ?>
                                <td>
                                    <dt>       
                                        <input id="chkboxrubric_<?php echo $fld_id; ?>" type="checkbox" value="<?php echo $fld_mis_id; ?>" name="chkbox" <?php if($chkval=='0'){echo "checked"; } ?>   >
                                        <span></span>
                                        <?php echo $fld_rub_name; ?>
                                    </dt>
                                </td>
                                <?php
                                if($i%3==0)
                                {
                                    echo "</tr><tr>";
                                }
                                $i++;
                            }
                            ?> </tr></table> 
                        </dl>
                    </div>
                </div>	
            </div>
            <?php
        }
        else
        {
            echo "fail";
        }
    }
}
/********Mohan M***********/
        

        
/*--- load Missions  ---*/
	if($oper=="loadmission" and $oper!='')
	{
		$licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
         	$assigntype = isset($method['assigntype']) ? $method['assigntype'] : '';
		
		$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_mission_mastertemp WHERE fld_flag=1 and fld_id='".$scheduleid."'");
		
                if($assigntype == 0)
                {
                  $count = 0;
                }
		

	       $countmodulemap=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_schedule_mission_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_flag=1");
			
			if($countmodulemap==0)
			{
	?>
    		<script>
				fn_movealllistitems('list3','list4',0);
            </script>
    <?php
			}
			?>
    	 <script language="javascript" type="text/javascript">
    					$(function() {
							$('#testrailvisible15').slimscroll({
								width: '410px',
								height:'366px',
								size: '7px',
                                                                alwaysVisible: true,
                                                                wheelStep: 1,
								railVisible: true,
                                                                allowPageScroll: false,
								railColor: '#F4F4F4',
								opacity: 1,
								color: '#d9d9d9'
								
							});
							$('#testrailvisible16').slimscroll({
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
							
							$("#list3").sortable({
								connectWith: ".droptrue1",
								dropOnEmpty: true,
								items: "div[class='draglinkleft']",
								receive: function(event, ui) {
									$("div[class=draglinkright]").each(function(){ 
										if($(this).parent().attr('id')=='list3'){
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'mission');
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
											fn_movealllistitems('list3','list4',$(this).children(":first").attr('id'),'mission');
										}
									});
								}
							});
                        });
                    
      		 </script>
            <div class='row rowspacer <?php if($count==1){echo "dim";}?>' >
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                               
																					
                                        $qrymission= $ObjDB->QueryObject("SELECT 
                                                                            a.fld_id as misid,
                                                                            fn_shortname(CONCAT(a.fld_mis_name, ' ', b.fld_version),
                                                                                    1) AS shortname,
                                                                            CONCAT(a.fld_mis_name, ' ', b.fld_version) as misname

                                                                            FROM
                                                                            itc_mission_master AS a
                                                                                LEFT JOIN
                                                                            itc_mission_version_track AS b ON b.fld_mis_id = a.fld_id
                                                                                LEFT JOIN
                                                                            itc_license_mission_mapping AS c ON a.fld_id = c.fld_mis_id
                                                                            WHERE
                                                                            a.fld_id NOT IN (SELECT 
                                                                                    fld_mission_id
                                                                                FROM
                                                                                    itc_class_rotation_schedule_mission_mappingtemp
                                                                                WHERE
                                                                                    fld_schedule_id = '".$scheduleid."'
                                                                                        AND fld_flag = '1')
                                                                                AND c.fld_license_id = '".$licenseid."'
                                                                                AND c.fld_flag = '1'
                                                                                AND a.fld_delstatus = '0'
                                                                                AND b.fld_delstatus = '0' group by a.fld_id
                                                                            ORDER BY misname");
										
                        ?>
                        <div class="dragtitle">Missions (<span id="leftmoddiv"><?php echo $qrymission->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible15" >
                        <div class="draglinkleftSearch" id="s_list3" >
                                                   <dl class='field row'>
                                                        <dt class='text'>
                                                            <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list3');" />
                                                        </dt>
                                                    </dl>
                                                </div>
                            <div id="list3" class="dragleftinner droptrue1">
									<?php 
                                       
											if($qrymission->num_rows > 0){
												while($rowsqry = $qrymission->fetch_assoc()){
													extract($rowsqry);
													
                                                ?>
                                            <div class="draglinkleft" id="list3_<?php echo $misid; ?>" title="<?php echo $misname; ?>">
                                                <div class="dragItemLable" id="<?php echo $misid; ?>"><?php echo $misname; ?></div>
                                                <div class="clickable" id="clck_<?php echo $misid; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $misid; ?>','mission');"></div>
                                            </div> 
                                        <?php }
                                            }?>
                            </div>
                        </div>
                        <div class="dragAllLink"  onclick="fn_movealllistitems('list3','list4',0,'mission');">add all missions</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <?php
                              
                                     $qrymismap=$ObjDB->QueryObject("SELECT 
                                                                            a.fld_id as misid,
                                                                            fn_shortname(CONCAT(a.fld_mis_name, ' ', b.fld_version),
                                                                                    1) AS shortname,
                                                                            CONCAT(a.fld_mis_name, ' ', b.fld_version) as misname

                                                                            FROM
                                                                            itc_mission_master AS a
                                                                                LEFT JOIN
                                                                            itc_mission_version_track AS b ON b.fld_mis_id = a.fld_id
                                                                                LEFT JOIN 
                                                                            itc_class_rotation_schedule_mission_mappingtemp AS c ON a.fld_id=c.fld_mission_id 
									    WHERE c.fld_schedule_id='".$scheduleid."' AND c.fld_flag=1 AND a.fld_delstatus='0' AND b.fld_delstatus='0' order by misname");
											       
									
                        ?>
                        <div class="dragtitle">Mission in your class (<span id="rightmoddiv"><?php echo $qrymismap->num_rows;?></span>)</div>
                        <div class="dragWell" id="testrailvisible16">
                            <div id="list4" class="dragleftinner droptrue1">
                                <?php 
                                    
											if($qrymismap->num_rows > 0){
												while($rowmodulemap = $qrymismap->fetch_assoc()){
													extract($rowmodulemap);
                                                ?>
                                                <div class="draglinkright" id="list4_<?php echo $misid; ?>" title="<?php echo $misname; ?>">
                                                    <div class="dragItemLable" id="<?php echo $misid; ?>"><?php echo $misname;?></div>
                                                    <div class="clickable" id="clck_<?php echo $misid; ?>" onclick="fn_movealllistitems('list3','list4','<?php echo $misid; ?>','mission');"></div>
                                                </div>
                                         <?php }
                                            }?>   
                            </div>
                        </div>
                        <div class="dragAllLink" onclick="fn_movealllistitems('list4','list3',0,'mission');">remove all missions</div>
                    </div>
                </div>
            </div>
                         
                            
                                      
    <?php
	}
    
        
if($oper == "saveschedule" and $oper != '')
{
    try
    {
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        $sid = isset($method['sid']) ? $method['sid'] : '0';
        $sname = isset($method['sname']) ? $method['sname'] : '0';
        $startdate = isset($method['startdate']) ? $method['startdate'] : '0';
        $enddate = isset($method['enddate']) ? $method['enddate'] : '0';
        $scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
        $students = isset($method['students']) ? $method['students'] : '0';
        $unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
        $studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
        $numberofcopies = isset($method['numberofcopies']) ? $method['numberofcopies'] : '0';
        $numberofrotations = isset($method['numberofrotations']) ? $method['numberofrotations'] : '0';
        $rotationlength = isset($method['rotationlength']) ? $method['rotationlength'] : '0';
        $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
        $missions = isset($method['missions']) ? $method['missions'] : '0';
        $schflag = isset($method['schflag']) ? $method['schflag'] : '0';


        $selectchkboxids = isset($method['selectchkboxids']) ? $method['selectchkboxids'] : '0';     //Mohan M     
        $selectchkboxids = explode(',',$selectchkboxids);  //Mohan M   
        
        $selectchkboxtestids = isset($method['selectchkboxtestids']) ? $method['selectchkboxtestids'] : '0';     //karthi    
        $selectchkboxtestids = explode(',',$selectchkboxtestids);  //karthi 
        
        $students = explode(',',$students);
        $missions = explode(',',$missions);
        $unstudents = explode(',',$unstudents);


        $validate_schname=true;
        $validate_schid=true;
        $validate_date=true;

        if($sid!=0)
        {
                $validate_schid=validate_datatype($sid,'int');
                $validate_schname=validate_datas($sname,'lettersonly'); 
                $validate_date=validate_datas($startdate,'dateformat'); 
        }

        if($validate_schid and $validate_schname and $validate_date)
        {


        $remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");



        if($studenttype==1){
                /*---------checking the license for student----------------------*/				
                $count=0;
                $qry = $ObjDB->QueryObject("SELECT fld_student_id FROM itc_class_student_mapping WHERE fld_class_id='".$classid."' and fld_flag='1'");
                if($qry->num_rows>0){
                        $students=array();
                        while($res=$qry->fetch_assoc())
                        {
                                extract($res);
                                $students[]=$fld_student_id;
                                $check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
                                         LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                 WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' AND b.fld_delstatus='0'");

                                if($check==0)
                                {
                                        $count++;
                                }
                        }
                }
        }
        else
        {
                $count=0;
                $add=0;			
                for($i=0;$i<sizeof($students);$i++)
                {
                        $check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) FROM itc_license_assign_student AS a 
                                 LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                         WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' AND b.fld_delstatus='0'");

                        if($check==0)
                        {
                                $count++;
                        }
                }

                $remainusers = $ObjDB->SelectSingleValueInt("SELECT fld_remain_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");




                for($i=0;$i<sizeof($unstudents);$i++)
                {
                        $check = $ObjDB->SelectSingleValueInt("SELECT count(*) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' and fld_student_id='".$unstudents[$i]."' and fld_flag='1'");

                        if($check>0)
                        {

                        $studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a                                 LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
                                                        LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'

                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND b.fld_license_id='".$licenseid."'

                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                         UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                   WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_schedule_id<>'".$sid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
                                                        LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
                                                        LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
                                                        LEFT JOIN itc_class_indassesment_master AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
                                                        LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'

                        UNION ALL 
                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a 
                                                        LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id 
                                                        WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'

                             ) AS o");

                                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");

                                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_schedulegriddet SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");

                                if($studentcount==0){
                                        $add++;
                                        $ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
                                }
                        }
                }
        }



        $assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");

        $totalusers = $ObjDB->SelectSingleValueInt("SELECT fld_no_of_users FROM itc_license_track WHERE fld_school_id='".$schoolid."' and fld_license_id='".$licenseid."' and fld_delstatus='0' and fld_user_id='".$indid."' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date");

        $totalremain = $remainusers-$count;
        if($totalusers>=($assignedstudents+$count)){
                $flag=1;
        }		
        else{	
                $flag=0;
        }


        if($flag==1) //if student user availale for license
        { 

                if($sid!=0)
                {
                        $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_schedule_name='".$ObjDB->EscapeStrAll($sname)."',fld_student_type='".$studenttype."',fld_startdate='".date("Y-m-d",strtotime($startdate))."',fld_rotationlength='".$rotationlength."',fld_numberofcopies='".$numberofcopies."',fld_numberofrotations='".$numberofrotations."',fld_updatedby='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$sid."'");
                }
                else
                {



                        $ObjDB->NonQuery("insert into itc_class_rotation_mission_mastertemp (fld_class_id,fld_license_id,fld_schedule_name,fld_scheduletype,fld_student_type,fld_startdate,fld_numberofcopies,fld_numberofrotations,fld_rotationlength,fld_created_date,fld_createdby) values('".$classid."','".$licenseid."','".$ObjDB->EscapeStrAll($sname)."','".$scheduletype."','".$studenttype."','".date("Y-m-d",strtotime($startdate))."','".$numberofcopies."','".$numberofrotations."','".$rotationlength."','".date("Y-m-d H:i:s")."','".$uid."')");

                        $sid=$ObjDB->SelectSingleValueInt("SELECT MAX(fld_id) FROM itc_class_rotation_mission_mastertemp");


                }

                /* Schedule Mission Mapping */
               if($schflag!=1)
               {
                $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mission_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");
               }

                for($i=0;$i<sizeof($missions);$i++)
                {


                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_mission_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_mission_id='".$missions[$i]."'");
                        if($cnt==0)
                        {
                                $ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_mission_mappingtemp(fld_schedule_id,fld_mission_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$missions[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                        }
                        else
                        {
                                $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mission_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_mission_id='".$missions[$i]."'  AND fld_id='".$cnt."'");
                        }
                }

                /* Schedule Module Mapping End */

                /* Schedule Student Mapping */

                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."'");

                for($i=0;$i<sizeof($students);$i++)
                {
                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_mission_student_mappingtemp WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");
                        if($cnt==0)
                        {
                                $ObjDB->NonQuery("INSERT INTO itc_class_rotation_mission_student_mappingtemp(fld_schedule_id, fld_student_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$sid."', '".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                        }
                        else
                        {
                                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_student_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
                        }

                        /* Schedule Student Mapping End */

                        //tracing student
                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_license_assign_student WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
                        if($cnt==0)
                        {
                                $ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) VALUES ('".$schoolid."', '".$licenseid."', '".$students[$i]."', '1','".date('Y-m-d H:i:s')."','".$uid."')");
                        }
                        else
                        {
                                $ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
                        }
                }
                
                
                /****************Mohan M  Feb 20 2016******************/	
                if($selectchkboxids[0] != '')
                {
                    $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
                                                             SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                    WHERE fld_schedule_id='".$sid."'");


                    for($m=0;$m<sizeof($selectchkboxids);$m++)
                    {
                        $templistrubric = explode('~',$selectchkboxids[$m]);

                        if($templistrubric[0]!='' and $templistrubric[1]!=0)
                        {

                            $classname = $ObjDB->SelectSingleValue("SELECT fld_class_name FROM itc_class_master WHERE fld_id='".$classid."' AND fld_delstatus='0'");
                            $schedulename = $ObjDB->SelectSingleValue("SELECT fld_schedule_name FROM itc_class_rotation_mission_mastertemp WHERE fld_id='".$sid."' AND fld_scheduletype='20' AND fld_delstatus='0'");
                            $expname = $ObjDB->SelectSingleValue("SELECT fld_mis_name FROM itc_mission_master WHERE fld_id='".$templistrubric[1]."' AND fld_delstatus='0'");
                            $rubricname = $ObjDB->SelectSingleValue("SELECT fld_rub_name FROM itc_mis_rubric_name_master WHERE fld_id='".$templistrubric[0]."' AND fld_delstatus='0'");

                            $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_expmis_rubricmaster WHERE fld_schedule_id = '".$sid."'
                                                                            AND fld_rubric_id = '".$templistrubric[0]."' AND fld_expmisid = '".$templistrubric[1]."' AND fld_schedule_type='19'");

                            if($cnt==0)
                            {
                                $ObjDB->NonQuery("INSERT INTO itc_class_expmis_rubricmaster (fld_class_id, fld_schedule_id, fld_schedule_type, fld_expmisid, fld_rubric_id, fld_created_by, fld_created_date,fld_class_name,fld_schedule_name,fld_expmisname,fld_rubric_name)
                                VALUES('".$classid."','".$sid."','19','".$templistrubric[1]."','".$templistrubric[0]."','".$uid."','".date("Y-m-d H:i:s")."', '".$ObjDB->EscapeStrAll($classname)."', '".$ObjDB->EscapeStrAll($schedulename)."','".$ObjDB->EscapeStrAll($expname)."','".$ObjDB->EscapeStrAll($rubricname)."')");
                            }
                            else    
                            {
                                $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster 
                                                    SET fld_delstatus='0',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',  
                                                    fld_class_name='".$ObjDB->EscapeStrAll($classname)."',fld_schedule_name='".$ObjDB->EscapeStrAll($schedulename)."',fld_expmisname='".$ObjDB->EscapeStrAll($expname)."',fld_rubric_name='".$ObjDB->EscapeStrAll($rubricname)."'
                                                    WHERE fld_schedule_id='".$sid."' AND fld_rubric_id='".$templistrubric[0]."' AND fld_expmisid='".$templistrubric[1]."'");
                            }
                        }					
                    }
                }
                else
                {
                        $ObjDB->NonQuery("UPDATE itc_class_expmis_rubricmaster
                                            SET fld_delstatus='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                   WHERE fld_schedule_id='".$sid."'");

                }
                /****************Mohan M  mar 20 2016******************/
                /****************Karthi  sep 28 2016******************/	
                if($selectchkboxtestids[0] != '')
                {
                        $ObjDB->NonQuery("UPDATE itc_mis_ass
                                                                 SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                        WHERE fld_sch_id='".$sid."'");


                        for($n=0;$n<sizeof($selectchkboxtestids);$n++)
                        {
                                $templisttest = explode('~',$selectchkboxtestids[$n]);

                                if($templisttest[0]!='' and $templisttest[1]!=0)
                                {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_mis_ass WHERE fld_sch_id = '".$sid."'
                                                                                AND fld_test_id = '".$templisttest[0]."' AND fld_mis_id = '".$templisttest[1]."' AND fld_schtype_id='20'");

                                        if($cnt==0)
                                        {
                                                $ObjDB->NonQuery("INSERT INTO itc_mis_ass (fld_class_id, fld_sch_id, fld_schtype_id, fld_mis_id, fld_test_id, fld_created_by, fld_created_date,fld_school_id,fld_user_id)
                                                                    VALUES('".$classid."','".$sid."','20','".$templisttest[1]."','".$templisttest[0]."','".$uid."','".date("Y-m-d H:i:s")."','".$schoolid."','".$indid."')");
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_mis_ass 
                                                                    SET fld_flag='1',fld_updated_by='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_school_id='".$schoolid."', fld_user_id='".$indid."'
                                                                    WHERE fld_sch_id='".$sid."' AND fld_test_id='".$templisttest[0]."' AND fld_mis_id='".$templisttest[1]."'");
                                        }
                                }					
                        }
                }
                else
                {
                        $ObjDB->NonQuery("UPDATE itc_mis_ass
                                            SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                   WHERE fld_sch_id='".$sid."'");
                }
                /****************Karthi  sep 28 2016******************/

                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."',fld_license_id='".$licenseid."' WHERE fld_id='".$sid."'");

            echo "success~".$sid;

            send_notification($licenseid,$schoolid,$indid);

        }		
        else
        {
            echo "exceed";
        }
        }
        else
        {
                echo "fail";		
        }
    }
    catch(Exception $e)
    {
             echo "failcatch";
    }
}


/*--- Show Missions  ---*/
	if($oper == "showmission" and $oper != '')
	{	
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
                $licenseid=isset($method['licenseid']) ? $method['licenseid'] : '0';
		
	?>
		<dl class='field row'> 
            <dt class="dropdown" style="width:300px;">     
            <div class="selectbox">
                <input type="hidden" name="selectmodule" id="selectmodule" value=" ">
                <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                    <span class="selectbox-option input-medium" data-option=" ">Select Mission</span>
                    <b class="caret1"></b>
                </a>
                <div class="selectbox-options">
                    <input type="text" class="selectbox-filter" placeholder="Search Mission" value="">
                    <ul role="options">
                        <?php 
                         
                         
                             $qrymis=$ObjDB->QueryObject("SELECT a.fld_id as id, CONCAT(a.fld_mis_name,' ',b.fld_version) as misname,'20' as type 
							                          FROM itc_mission_master AS a
							                               LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id=a.fld_id
							                               LEFT JOIN itc_license_mission_mapping AS c ON a.fld_id=c.fld_mis_id 
													  WHERE c.fld_license_id='".$licenseid."' AND a.fld_delstatus='0' AND c.fld_flag='1' AND b.fld_delstatus='0' group by id order by misname");
                                                 
                         
                         
                        if($qrymis->num_rows > 0)
                        {
                          while($rowsqry = $qrymis->fetch_assoc())
                          {
                              extract($rowsqry);
                              ?>
                           
                            <li><a tabindex="-1" href="#" data-option="<?php echo $id;?>"  onclick="fn_addmission(<?php echo $id;?>,<?php echo $scheduleid;?>,<?php echo $type;?>);" title="<?php echo $misname;?>" class="tooltip"><?php echo $misname;?> </a></li>
                        <?php 
                        }
                        }
                        else
                        {
                            echo "No Records";
                        }
                        ?>       
                    </ul>
                </div>
            </div>
            </dt>
        </dl>      
	<?php
	}
        
        
        /*--- add mission  ---*/
	if($oper == "addmission" and $oper != '')
	{	
		$misid = isset($method['misid']) ? $method['misid'] : '0';
		$scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
		$scheduletype = isset($method['scheduletype']) ? $method['scheduletype'] : '0';
		$thlength = isset($method['thlength']) ? $method['thlength'] : '0';
		$trlength = isset($method['trlength']) ? $method['trlength'] : '0';
		$type = isset($method['type']) ? $method['type'] : '0';
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;
                $mode = isset($method['mode']) ? $method['mode'] : '0';
		$trlength=explode("_",$trlength);
		
                $rowid=$trlength[1]+1;
                
		
				$misname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mis_name,' ',b.fld_version) 
												FROM itc_mission_master AS a 
													LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id=a.fld_id
												WHERE a.fld_id='".$misid."' AND b.fld_delstatus='0' AND a.fld_delstatus='0'");
                             if($mode=="edit")
                             {
                                
                                $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_missiondet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$misid."' 
						    AND fld_row_id='".$rowid."'");
				
                                if($count==0)
                                {
                                        $ObjDB->NonQuery("INSERT INTO itc_class_rotation_missiondet(fld_class_id,fld_schedule_id,fld_mission_id,fld_numberofrotation,fld_row_id)
                                                                             values('".$classid."','".$scheduleid."','".$misid."','".$numberofrotation."','".$rowid."')");
                                }
                                else
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_rotation_missiondet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$misid."' and fld_row_id='".$rowid."'");
                                }
                                
                             }
                                
                                
			
		
		
	?>
    
    <tr id="tr_<?php echo $trlength[1]+1; ?>" class="<?php echo $misid."-".$type; ?>">
        <td id="module_<?php echo $trlength[1]+1;?>" class="misname" onmouseover="fn_checkcellvaluemis(<?php echo $trlength[1]+1;?>)" onmouseout="fn_checkcellvalueoutmis(this.id)"><?php echo $misname; ?></td>
            <?php
				$k=2;
				$z=$trlength[1]+1;
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td id="stu_<?php echo $z.$k;?>" style="background: #FFFFFF;width:205px;">
                
                	                <div class="rowspanone clk row<?php echo $k;?>" id="seg1_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagetop" id="imagetop_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
					<div class="rowspantwo clk row<?php echo $k;?>" id="seg2_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagebottom" id="imagebottom_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
                                        <div class="rowspanonedup clk row<?php echo $k;?>" id="seg3_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagetopdup" id="imagetopdup_<?php echo $z;?>_<?php echo $k;?>" title="Delete"></div>
					<div class="rowspantwodup clk row<?php echo $k;?>" id="seg4_<?php echo $z;?>_<?php echo $k;?>">&nbsp;</div>
					<div class="imagebottomdup" id="imagebottomdup_<?php echo $z;?>_<?php echo $k;?>" title="Delete"  <?php $k++; ?>></div>
                </td>
                <?php
				}
				?>
		</tr>
         <tr id="addmod" style="display:none;">
        	<td style="display:none;">
                  
            </td>
            <?php
				for($i=1;$i<$thlength;$i++)
				{
				?>
                <td></td>
                <?php
				}
				?>
         </tr>
    <?php
	}
        
    if($oper=="removemission" and $oper!='')
    {
        $mistype = isset($method['mistype']) ? $method['mistype'] : '0';
        $rowid = isset($method['rowid']) ? $method['rowid'] : '0';
        $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
        $classid = isset($method['classid']) ? $method['classid'] : '0';
        
        $mis=explode('-',$moduletype);
        
       
         
         $ObjDB->NonQuery("UPDATE itc_class_rotation_missiondet SET fld_flag='0'
                                WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$mission[0]."' and fld_row_id='".$rowid."'");
        
    }
    
    
        /*--- save rotational table cell details  ---*/
	if($oper == "saverotation" and $oper != '')
	{

		$classid = (isset($method['classid'])) ? $method['classid'] : 0;
		$scheduleid = (isset($method['scheduleid'])) ? $method['scheduleid'] : 0;
		$moduledet = (isset($method['moduledet'])) ? $method['moduledet'] : 0;	
		$numberofrotation = (isset($method['numberofrotation'])) ? $method['numberofrotation'] : 0;	
		$celldet = (isset($method['celldet'])) ? $method['celldet'] : 0;
		$startdate = (isset($method['startdate'])) ? $method['startdate'] : 0;

		$rotlength = (isset($method['rotlength'])) ? $method['rotlength'] : 0;
		$rotlength=$rotlength-1;
		$count=0;
		$moduledet=explode(",",$moduledet);
		$celldet=explode(",",$celldet);

		$schflag=$ObjDB->SelectSingleValueInt("SELECT fld_flag FROM itc_class_rotation_mission_mastertemp WHERE fld_id='".$scheduleid."'");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_missiondet SET fld_flag='0' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
                $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mission_mappingtemp SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$scheduleid."'");
		
		$j=2;
		for($i=0;$i<sizeof($moduledet);$i++)
		{
			if($moduledet[$i]!="undefined")
			{
				$getmoduledet=explode("-",$moduledet[$i]);
				
                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_class_rotation_schedule_mission_mappingtemp WHERE fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."'");
                        if($cnt==0)
                        {
                                $ObjDB->NonQuery("INSERT INTO itc_class_rotation_schedule_mission_mappingtemp(fld_schedule_id,fld_mission_id,fld_flag,fld_createddate,fld_createdby) VALUES ('".$scheduleid."', '".$getmoduledet[0]."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                        }
                        else
                        {
                                $ObjDB->NonQuery("UPDATE itc_class_rotation_schedule_mission_mappingtemp SET fld_flag='1',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."'  AND fld_id='".$cnt."'");
                        }
				
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) 
				            FROM itc_class_rotation_missiondet
						    WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."' 
							AND fld_row_id='".$j."'");
				
			if($count==0)
			{
				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_missiondet(fld_class_id,fld_schedule_id,fld_mission_id,fld_numberofrotation,fld_row_id)
				                                     values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$numberofrotation."','".$j."')");
			}
			else
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_missiondet SET fld_flag='1',fld_numberofrotation='".$numberofrotation."' 
		        WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."'  and fld_row_id='".$j."'");
			}
		}
			
			$j++;
		}
                
                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_schedulegriddet SET fld_flag='0',fld_updateddate='".date('Y-m-d H:i:s')."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
		
		for($i=0;$i<sizeof($celldet);$i++)
		{
			$getcelldet=explode("~",$celldet[$i]);
			$getrowid=explode("_",$getcelldet[2]);
			$getmoduledet=explode("-",$getcelldet[0]);
			
			if($getcelldet[3]!="undefined")
			{
				$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_mission_schedulegriddet WHERE fld_class_id='".$classid."' AND  fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."'  and fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			
			  if($count==0)
			  {
                                   
	                            $ObjDB->NonQuery("INSERT INTO  itc_class_rotation_mission_schedulegriddet(fld_class_id,fld_schedule_id,fld_mission_id,fld_rotation,fld_cell_id,fld_student_id,fld_row_id,fld_createddate,fld_createdby)values('".$classid."','".$scheduleid."','".$getmoduledet[0]."','".$getcelldet[1]."','".$getcelldet[2]."','".$getcelldet[3]."','".$getrowid[1]."','".date("Y-m-d H:i:s")."','".$uid."')");
			  
			  }
			  else
			  {
                                 
                                $ObjDB->NonQuery("UPDATE itc_class_rotation_mission_schedulegriddet SET fld_flag='1',fld_student_id='".$getcelldet[3]."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."' AND fld_mission_id='".$getmoduledet[0]."' AND fld_row_id='".$getrowid[1]."' AND fld_cell_id='".$getcelldet[2]."'");
			  
			   }
			}
			
		}
		
                	
	if($schflag==0)
	{
		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			if($i==2)
			{
				$sdate=$i."~".$startdate;
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_missionscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			else
			{
				$startdate=date("Y-m-d",strtotime($enddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_missionscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");
				
			}
			
		}
		
		
		
		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}
	else
	{
		$ObjDB->NonQuery("UPDATE itc_class_rotation_missionscheduledate SET fld_flag=0 WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

		$sdate='';
		$edate='';
		for($i=2;$i<=$numberofrotation+1;$i++)
		{
			$rotcount=$ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_rotation_missionscheduledate WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

			if($rotcount==1)
			{
				$ObjDB->NonQuery("UPDATE itc_class_rotation_missionscheduledate SET fld_flag=1,fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_rotation='".$i."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");
			}
			else
			{
				$rotation=$i-1;
				$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_missionscheduledate WHERE fld_rotation='".$rotation."' AND fld_class_id='".$classid."' AND fld_schedule_id='".$scheduleid."'");

				$startdate=date("Y-m-d",strtotime($rotenddate. "+1 weekdays"));
				$enddate=date("Y-m-d",strtotime($startdate. "+".$rotlength." weekdays"));

				$ObjDB->NonQuery("INSERT INTO itc_class_rotation_missionscheduledate(fld_class_id,fld_schedule_id,fld_rotation,fld_startdate,fld_enddate,fld_createddate,fld_createdby)VALUES('".$classid."','".$scheduleid."','".$i."','".$startdate."','".$enddate."','".date("Y-m-d H:i:s")."','".$uid."')");


			}
			
		}

		$rotenddate=$ObjDB->SelectSingleValue("SELECT fld_enddate FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$scheduleid."' and fld_flag='1' AND fld_rotation IN(SELECT MAX(fld_rotation) FROM itc_class_rotation_missionscheduledate WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1') LIMIT 0,1 ");
		
		$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_enddate='".$rotenddate."',fld_gridupdatedby='".$uid."',fld_gridupdateddate='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
	}


		$ObjDB->NonQuery("UPDATE itc_class_rotation_mission_mastertemp SET fld_flag=1,fld_updated_date='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' WHERE fld_id='".$scheduleid."'");
		
		
}