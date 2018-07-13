<?php
    @include("sessioncheck.php");
/*------
    Page - Passport
    Description:
        1.Teacher can block/unblock Expedition Resource hyperlink in student passport
        2.Student can view the expedition using passport 
    History: Modified on 31/12/2014 by vijayalakshmi PHP Programmer
    Created BY : Vijayalakshmi PHP Programmer.(7/9/2014)    
------*/?>


<style type="text/css">

/* Styling an indeterminate progress bar */
 .graph {
                width: 95px; /* width and height are arbitrary, just make sure the #bar styles are changed accordingly */ 
                height: 7px;
                border: 1px solid #888; 
                background: rgb(168,168,168);
                background: -moz-linear-gradient(top, rgba(168,168,168,1) 0%, rgba(217, 220, 218,1) 23%);
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(168,168,168,1)), color-stop(23%,rgba(217, 220, 218,1)));
                background: -webkit-linear-gradient(top, rgba(168,168,168,1) 0%,rgba(217, 220, 218,1) 23%);
                background: -o-linear-gradient(top, rgba(168,168,168,1) 0%,rgba(217, 220, 218,1) 23%);
                background: -ms-linear-gradient(top, rgba(168,168,168,1) 0%,rgba(217, 220, 218,1) 23%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a8a8a8', endColorstr='#cccccc',GradientType=0 );
                background: linear-gradient(top, rgba(168,168,168,1) 0%,rgba(217, 220, 218,1) 23%);
                position: relative;
            }
            #bar {
                height: 6px; /* Not 30px because the 1px top-border brings it up to 30px to match #graph */
                background: rgb(255,197,120); 
                background: -moz-linear-gradient(top, rgba(170, 212, 188 ,1) 0%, rgba(114,184,144,1) 100%); 
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(170, 212, 188 ,1)), color-stop(100%,rgba(114,184,144,1))); 
                background: -webkit-linear-gradient(top, rgba(170, 212, 188 ,1) 0%,rgba(114,184,144,1) 100%); 
                background: -o-linear-gradient(top, rgba(170, 212, 188 ,1) 0%,rgba(114,184,144,1) 100%); 
                background: -ms-linear-gradient(top, rgba(170, 212, 188 ,1) 0%,rgba(114,184,144,1) 100%); 
                background: linear-gradient(top, rgba(170, 212, 188 ,1) 0%,rgba(114,184,144,1) 100%); 
                border-top: 1px solid #fceabb;
            }

</style>

<?php
$id = isset($method['id']) ? $method['id'] : '';
/*******Student Passport code start here************/
$id1 = explode(",", $id);
$schtype =15;

/*******Student Passport code end here************/
?>
<section data-type='#tools-passport' id='tools-passport-passport'>
  
  <div class='container'>
       
<?php if($sessmasterprfid==9 OR $sessmasterprfid==8){ ?>
        <div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle">Passport</p>
            <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
          </div>
        </div>
   <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <div class="row">
<!--Shows class Dropdown-->
                    <div class='six columns'>
                        <!--Shows Class Dropdown-->
                        
                            <dl class='field row'> Class
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
                            $qry = $ObjDB->QueryObject("SELECT a.fld_id AS classid,a.fld_class_name AS classname
                                            FROM itc_class_master AS a
                                            LEFT JOIN itc_class_indasexpedition_master AS b on a.fld_id=b.fld_class_id
                                            WHERE a.fld_delstatus = '0' AND a.fld_archive_class='0' AND b.fld_delstatus = '0' 
                                            AND (a.fld_created_by = '".$uid."' OR a.fld_id IN (SELECT fld_class_id
                                            FROM itc_class_teacher_mapping WHERE fld_teacher_id = '".$uid."' AND fld_flag = '1'))
                                            group by a.fld_class_name");
                                            if($qry->num_rows>0){
                                                while($row = $qry->fetch_assoc())
                                                {
                                                    extract($row);              
                                                    ?>
                                                    <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>" onClick="fn_showexpedition(<?php echo $classid; ?>)"><?php echo $classname; ?></a></li>
                                                    <?php
                                                }
                                            }?>      
                                        </ul>
                                    </div>
                                </div> 
                            </dl>
                       
                    </div>
<!--Shows expedition Dropdown-->
                        <div class='six columns'>   
                            
                            <div id="expeditiondiv" style="display:none">
                                 
                            </div>
                            
                        </div>
<!-- ends Show expedition Dropdown-->

                </div> <!-- ends of <div class="row"> -->

 <div class="row rowspacer">
                    <div class='six columns'> 
                        <!--Shows Schedule Dropdown-->
                        <div id="schedulediv" style="display:none">
                            
                        </div>
                    </div>
                </div>

<!--View Report Button-->
                <div class='row rowspacer' style="display:none" id="viewexpeditiondiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="View" onClick="fn_Expeditionstatus();" />
                </div>
            </div>  <!-- ends of <div class='eleven columns centered insideForm'> -->
            
        </div>
        <?php } ?>

 <?php if($sessmasterprfid==10){ 
  
          /** shows the expeditions for the student **/
     /*******Student Passport code start here************/
     if($id1[0] == "passport"){
         $expid = $id1[1];
         $scheduleid = $id1[2];
         $pagename = $id1[3];
         $desttpid = $id1[4];
         $tasktpid = $id1[5];
         $temporder = $id1[6];
         $restpid = $id1[7];
       
         $label2= 'AND DATE(a.fld_startdate) <= DATE(NOW())';
         $qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_name AS expname,a.fld_ui_id AS expuid
                                        FROM itc_exp_master AS a 
                                        LEFT JOIN itc_exp_version_track AS b ON a.fld_id=b.fld_exp_id 
                                        WHERE a.fld_id='".$expid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
         
     }
     else{
         $label2= 'AND DATE(a.fld_startdate) <= DATE(NOW())';
         $qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid,a.fld_class_id as classid,c.fld_ui_id AS expuid, 
                                        a.fld_id AS scheduleid, c.fld_exp_name AS expname
                                        FROM `itc_class_indasexpedition_master` AS a 
                                        LEFT JOIN `itc_class_exp_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
                                        LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
                                        LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_archive_class='0' AND d.fld_lock='0' 
                                        AND b.fld_student_id='".$uid."' ".$label2." 
                                        AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')");
     }
     /*******Student Passport code end here************/    
$expedition_cnt = 0;
if($qryexp->num_rows  == 0){ ?> 
<div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle">No Records Found.</p>
            

          </div>
        </div>

<?php }
else if($qryexp->num_rows>0){
 /*******Student Passport code start here************/
 if($id1[0] == "passport"){ ?>

<style type="text/css">

.button_example{
border:1px solid #25729a; -webkit-border-radius: 3px; -moz-border-radius: 3px;border-radius: 3px;font-size:15px;font-family:arial, helvetica, sans-serif; padding: 12px 12px 12px 12px; text-decoration:none; text-shadow: -1px -1px 0 rgba(0,0,0,0.3);font-weight:bold; color: #FFFFFF;
 background-color: #3093c7; background-image: -webkit-gradient(linear, left top, left bottom, from(#3093c7), to(#1c5a85));
 background-image: -webkit-linear-gradient(top, #3093c7, #1c5a85);
 background-image: -moz-linear-gradient(top, #3093c7, #1c5a85);
 background-image: -ms-linear-gradient(top, #3093c7, #1c5a85);
 background-image: -o-linear-gradient(top, #3093c7, #1c5a85);
 background-image: linear-gradient(to bottom, #3093c7, #1c5a85);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#3093c7, endColorstr=#1c5a85);
}

.button_example:hover{
 border:1px solid #1c5675;
 background-color: #26759e; background-image: -webkit-gradient(linear, left top, left bottom, from(#26759e), to(#133d5b));
 background-image: -webkit-linear-gradient(top, #26759e, #133d5b);
 background-image: -moz-linear-gradient(top, #26759e, #133d5b);
 background-image: -ms-linear-gradient(top, #26759e, #133d5b);
 background-image: -o-linear-gradient(top, #26759e, #133d5b);
 background-image: linear-gradient(to bottom, #26759e, #133d5b);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#26759e, endColorstr=#133d5b);
}
</style>      
<script>

    function fn_returnexp(schid,expid,schtype,pagename,desttpid,tasktpid,temporder,restpid){
        var value=5+","+schid+","+expid+","+schtype+","+pagename+","+desttpid+","+tasktpid+","+temporder+","+restpid;
        showloadingalert("Please Wait,...");
        setTimeout('closeloadingalert()',500);
        window.location='<?php echo __HOSTADDR__."/index.php?id='+value+'";?>';
    }
    </script>
<div class='row'>
    <div class='twelve columns'>
        <div class='six columns'>
            <p class="dialogTitle">Passport</p>
            <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
        </div>
        <div class='six columns'>
            <?php 
                $schtype =15;
?>
            <p class="dialogTitle" style="margin-left:270px;"><a class="button_example" onclick="fn_returnexp(<?php echo $scheduleid;?>,<?php echo $expid;?>,<?php echo $schtype;?>,'<?php echo $pagename;?>',<?php echo $desttpid;?>,<?php echo $tasktpid;?>,<?php echo $temporder;?>,<?php echo $restpid;?>);">Return to Expedition </a></p>
        </div>
    </div>
</div>   
 <?php } else{ ?>
<div class='row'>
          <div class='twelve columns'>
            <p class="dialogTitle">Passport</p>
            <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
          </div>
        </div>
<?php }
  /*******Student Passport code end here************/  
?> 

&nbsp;
<div class='row formBase'>
            <div class='eleven columns centered insideForm' style="width: 75%;">
                <div class="main clearfix">
                       <div class="bb-custom-wrapper">
                           <div id="bb-bookblock" class="bb-bookblock">
<!-- start the contents from here -->
<!-- each expedition will start from here -->
<?php
          
                    while($rowexp = $qryexp->fetch_assoc())
                    {
                        extract($rowexp);

/* get expedition file name from itc_exp_master table **/

    $qryexpdeitions = $ObjDB->QueryObject("SELECT a.fld_exp_name, a.fld_exp_desc, a.fld_expunique_id, b.fld_file_name, b.fld_version
                                        FROM itc_exp_master AS a 
                                        LEFT JOIN itc_exp_version_track AS b ON a.fld_id=b.fld_exp_id 
                                        WHERE a.fld_id='".$expid."' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
    $rowqryexpdeitions = $qryexpdeitions->fetch_object();
    $urlformedia = "expedition/".$rowqryexpdeitions->fld_file_name."/resources/";


        ?>
    <input type="hidden" id="mediaurl" class="mediaurl<?php echo $expedition_cnt; ?>" value="<?php echo $urlformedia;?>" />
<?php
    $checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' 
                                    AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
          /* calculation for progress bar starts */   
                       
                        if($checkrstatusid == '0'){  // from pitsco
/* starts calculating all resources within expedtion */
        
         $groupresourceids_req=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                                    GROUP BY a.fld_id) as cnt");

     $allresourceids_opnl=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'");

    $allresourceids_req=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'");
        
	$grp_resorid =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."'                             AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");
        
 /* ends calculating all resources within expedtion */   
   }
   else{
/* starts calculating all resources within expedtion */
      
        $groupresourceids_req=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                                    GROUP BY a.fld_id) as cnt");

     $allresourceids_opnl=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'");

    $allresourceids_req=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'");
       
	$grp_resorid =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."'                             AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");

/* ends calculating all resources within expedtion */
   }

	if($grp_resorid !='') {

	$cntresid_basedexp = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_resource_master as a 
                                               		     LEFT JOIN itc_exp_task_master as b ON a.fld_task_id = b.fld_id       
						             LEFT JOIN itc_exp_destination_master as c ON b.fld_dest_id = c.fld_id 
								WHERE c.fld_exp_id='".$expid."' AND a.fld_id NOT IN(".$grp_resorid.")");
	} else {


	$cntresid_basedexp = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_resource_master as a 
                                               		     LEFT JOIN itc_exp_task_master as b ON a.fld_task_id = b.fld_id       
						             LEFT JOIN itc_exp_destination_master as c ON b.fld_dest_id = c.fld_id 
								WHERE c.fld_exp_id='".$expid."'");
	}

	$compr_gropp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_resid) FROM itc_exp_lockpassport where 
                                                fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
                                                AND fld_expid='".$expid."' AND fld_lock_flag='1'");
	if($compr_gropp_resids != '') {



	$comprcntresid_basedexp = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where                                                   									fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
								AND fld_expid='".$expid."' AND fld_lock_flag='1' AND fld_resid IN(".$compr_gropp_resids.")");
	}
	else{
	$comprcntresid_basedexp = '';
	}

if($groupresourceids_req == '') {
            $rescomplete_req = 0;
        }
        else {
            $rescomplete_req = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$groupresourceids_req.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_schedule_id = '".$scheduleid."' AND fld_schedule_type = '".$schtype."' AND fld_read_status='1'");
        }    
 
      $total_status_exp = $allresourceids_req;
      $total_completed_exp =$rescomplete_req;  
      $exp_progress=($total_completed_exp/ $total_status_exp)*100;
      $exp_progress=round($exp_progress);
?>
        <div class="bb-item">

            <p style="margin-left: 25px;"></p>
            <p>Expedition Name: 
                <?php 
// checking the lock for the expedition name

	if($exp_progress != '100') { echo $expname; } else { 
 // modified by karthick  map id = '2' and std id ='1'

   if($cntresid_basedexp == $comprcntresid_basedexp) { echo $expname; } else {
  
                if($expuid == 2){
                    $schtype =15;
                    $values = $scheduleid.",".$expid.",".$schtype.",".'0'.",".'0'.",".'0';
                    $call = "removesections('#assignment'); showpageswithpostmethod('tools-expedition-show','tools/passport/tools-expedition-show.php','id=".$values."')";
                }
                else{
                    $schtype =15;
                    $values = $scheduleid."~".$expid."~".$schtype."~".'passport'."~".$urlformedia;;
                    $call = "removesections('#tools-passport-passport'); showpageswithpostmethod('assignment-expedition-preview','assignment/expedition/assignment-expedition-preview.php','id=".$values."')";
                }
                 ?>
                <a onclick="<?php echo $call; ?>" style=" cursor:pointer;color:#0011FF;" onMouseOver="this.style.color='#FF00FF'" onMouseOut="this.style.color='#0011FF'"><?php echo $expname;?></a>
<?php }
 }
// modified ends by karthick  map id = '2' and std id ='1'
  ?>
                
                <div id="progress123" class="graph" style="margin-top:-15px; margin-left:625px;">
                      <div id="bar" style="width:<?php echo $exp_progress.'%';?>"><div style="margin-left: 102px; margin-top: -7px; font-size: 12px;"><?php echo $exp_progress."%"; ?></div></div>
                </div>
            </p>
            &nbsp;
            <?php 
                        
/**
 * 
 * For displaying Expedition Name,
 * Destinations,
 * Tasks,
 * Resources
 * 
 * **/
if($checkrstatusid == '0'){
            $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                        FROM itc_exp_destination_master as a
                                        LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                        LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                        LEFT JOIN itc_class_indasexpedition_master as d on b.fld_license_id=d.fld_license_id
                                        LEFT JOIN itc_exp_res_status as f ON f.fld_dest_id = a.fld_id
                                        WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$scheduleid."' AND c.fld_user_id='0' 
                                            AND a.fld_delstatus = '0'
                                AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') AND f.fld_user_id = '".$indid."'
                                 AND f.fld_school_id = '0' GROUP BY destid");
        }
        else {
             $qrydestdetails=$ObjDB->QueryObject("SELECT a.fld_id as destid, a.fld_dest_name AS destname
                                        FROM itc_exp_destination_master as a
                                        LEFT JOIN itc_license_exp_mapping AS b ON a.fld_id = b.fld_dest_id
                                        LEFT JOIN itc_license_track AS c ON b.fld_license_id = c.fld_license_id
                                        LEFT JOIN itc_class_indasexpedition_master as d on b.fld_license_id=d.fld_license_id
                                        LEFT JOIN itc_exp_res_status as f ON f.fld_dest_id = a.fld_id
                                        WHERE a.fld_exp_id = '".$expid."' AND d.fld_id = '".$scheduleid."' AND c.fld_user_id='".$indid."' 
                                            AND c.fld_school_id='".$schoolid."' AND a.fld_delstatus = '0'
                                AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') AND f.fld_user_id = '".$indid."'
                                 AND f.fld_school_id = '".$schoolid."' GROUP BY destid");
        }
     
   if($qrydestdetails->num_rows>0)
    {
       ?>
       <div style="height:550px; overflow:auto;">
       <?php
        while($rowdestdetails = $qrydestdetails->fetch_assoc())
        {
            extract($rowdestdetails);

    if($checkrstatusid == '0'){

/* starts calculating all resources within Destination  */

             $grpresids_req_destnwise=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                AND b.fld_dest_id = '".$destid."'
                                                    GROUP BY a.fld_id) as cnt");


         $allresourceids_opnl_destwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."'");
        $allresourceids_req_destwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."'");
       
       $grp_resrceids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 							   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");
       
       
 /* ends calculating all resources within Destination  */    
      }
      else{
/* starts calculating all resources within Destination  */
      
        $grpresids_req_destnwise=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                AND b.fld_dest_id = '".$destid."'
                                                    GROUP BY a.fld_id) as cnt");


     $allresourceids_opnl_destwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."'");
    $allresourceids_req_destwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."'");
       
       
	$grp_resrceids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 							   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");
       
       
       
/* ends calculating all resources within Destination */
   }
	if($grp_resrceids !='') {

	$cntresid_baseddest = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_resource_master as a 
								LEFT JOIN itc_exp_task_master as b
								ON a.fld_task_id = b.fld_id 
								WHERE b.fld_dest_id = '".$destid."' AND a.fld_id NOT IN(".$grp_resrceids.")");
	} else {

	$cntresid_baseddest = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_resource_master as a 
								LEFT JOIN itc_exp_task_master as b
								ON a.fld_task_id = b.fld_id 
								WHERE b.fld_dest_id = '".$destid."'");

	}
	
	$compr_grp_resrceids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_resid) FROM itc_exp_lockpassport where 
												fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
												AND fld_expid='".$expid."' AND fld_destid='".$destid."'
												  AND fld_lock_flag='1'");
					if($compr_grp_resrceids != '') {

						$comprcntresid_baseddest = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where 													fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
												AND fld_expid='".$expid."' AND fld_destid='".$destid."' AND fld_lock_flag='1' AND fld_resid IN(".$compr_grp_resrceids.")");
					}
					else{
						$comprcntresid_baseddest = '';
					}

if($grpresids_req_destnwise == '') {

 $rescomplete_req_destwise = 0;

} else {
     $rescomplete_req_destwise = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$grpresids_req_destnwise.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_schedule_id = '".$scheduleid."' AND fld_schedule_type = '".$schtype."' AND fld_read_status='1'");
}

      $total_status_destwise = $allresourceids_req_destwise ;
      $total_completed_destwise =$rescomplete_req_destwise;  
      $dest_progress=($total_completed_destwise/ $total_status_destwise)*100;
      $dest_progress=round($dest_progress);

 /* For checking deatination, task and resoures are completed whenever student is enter into expedition(For required = 1 and Optional = 2 status) - */ 
 
      ?>
                  <ul class="tree" style="margin-left: 15px;">
                        <li>
                            
                <label>
                <?php 
// checking the lock flag to destination name for particular destination and expedition name

	
	if($dest_progress != '100') { echo $destname; } else {

	if($cntresid_baseddest == $comprcntresid_baseddest) { echo $destname; } else {	

// modified by karthick  map id = '2' and std id ='1'
                if($expuid == 2){
                    $schtype =15;
                    $values = $scheduleid.",".$expid.",".$schtype.",".'1'.",".$destid.",".'0';
                    $call = "removesections('#assignment'); showpageswithpostmethod('tools-expedition-show','tools/passport/tools-expedition-show.php','id=".$values."')";
                }
                else{
                    $i =1;
                    $values =  $destid.','.$i.','.$expid.','.$scheduleid.','.$schtype.",".'passport'.",".$urlformedia;
                    $call = "removesections('#tools-passport-passport'); showpageswithpostmethod('assignment-expedition-tasks','assignment/expedition/assignment-expedition-tasks.php','id=".$values."')";
                }
                 ?>
                <a onclick="<?php echo $call; ?>" style=" cursor:pointer;color:#0011FF;" onMouseOver="this.style.color='#FF00FF'" onMouseOut="this.style.color='#0011FF'"><?php echo $destname;?></a>
<?php
  }
}
// modified ends by karthick  map id = '2' and std id ='1'
?>
                                           
                <div id="progress" class="graph" style="margin-left:611px; margin-top:-24px">
                      <div id="bar" style="width:<?php echo $dest_progress.'%';?>"><div style="margin-left: 102px; margin-top: -7px; font-size: 12px;"><?php echo $dest_progress."%"; ?></div></div>
                </div>
                </label>
                            &nbsp;
                            &nbsp;
                           <ul class='expanded'>
                             
                            <?php
                            /** For selecting and displaying tasks related to the destination **/
                                if($checkrstatusid == '0'){

                    $qrytaskdetails=$ObjDB->QueryObject("SELECT a.fld_id as taskid,a.fld_task_name AS taskname FROM itc_exp_task_master as a 
                                       LEFT JOIN itc_exp_res_status as f ON f.fld_task_id = a.fld_id 
                                                                       WHERE a.fld_dest_id='".$destid."' AND a.fld_delstatus='0'
                                       AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '0' AND f.fld_school_id = '0'");
                }
                else {
                        
                                    $qrytaskdetails=$ObjDB->QueryObject("SELECT a.fld_id as taskid,a.fld_task_name AS taskname FROM itc_exp_task_master as a 
                                       LEFT JOIN itc_exp_res_status as f ON f.fld_task_id = a.fld_id 
                                                                       WHERE a.fld_dest_id='".$destid."' AND a.fld_delstatus='0'
                                       AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '".$indid."' AND f.fld_school_id = '".$schoolid."'");
                }
                                if($qrytaskdetails->num_rows>0)
                                    {
                                    while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
                                        {
                                            extract($rowtaskdetails);
                                            
     if($checkrstatusid == '0'){
/* starts calculating all resources within Task  */
            $grpresids_req_taskwise=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                            AND b.fld_dest_id = '".$destid."'
                                            AND a.fld_task_id = '".$taskid."'
                                                    GROUP BY a.fld_id) as cnt");


              $allresourceids_opnl_taskwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."' AND a.fld_task_id = '".$taskid."'");
            $allresourceids_req_taskwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '0'
                                                            AND d.fld_user_id = '0'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                    and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."' AND a.fld_task_id = '".$taskid."'");
            
	$grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 										   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");
            
 /* ends calculating all resources within Task  */       
       
   }
   else{
/* starts calculating all resources within Task  */
     
       $grpresids_req_taskwise=$ObjDB->SelectSingleValue("select 
                                                    GROUP_CONCAT(cnt.fld_id) 
                                                from
                                                    (SELECT 
                                                        a.fld_id
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                AND b.fld_dest_id = '".$destid."'
                                AND a.fld_task_id = '".$taskid."'
                                                    GROUP BY a.fld_id) as cnt");


     $allresourceids_opnl_taskwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '2'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."' AND a.fld_task_id = '".$taskid."'");
    $allresourceids_req_taskwise=$ObjDB->SelectSingleValue("SELECT count(DISTINCT(a.fld_id))
                                                    FROM
                                                        itc_exp_resource_master AS a
                                                    LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                    LEFT JOIN itc_exp_destination_master AS c ON b.fld_dest_id = c.fld_id
                                                    LEFT JOIN itc_exp_res_status AS d ON d.fld_res_id = a.fld_id
                                                    LEFT JOIN itc_license_exp_mapping AS e ON c.fld_id = e.fld_dest_id
                                                    LEFT JOIN itc_license_track AS f ON e.fld_license_id = f.fld_license_id
                                                    LEFT JOIN itc_class_indasexpedition_master as g ON e.fld_license_id = g.fld_license_id
                                                    where
                                                        c.fld_exp_id = '".$expid."'
                                                            AND g.fld_id = '".$scheduleid."'
                                                            AND d.fld_school_id = '".$schoolid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0' AND b.fld_dest_id = '".$destid."' AND a.fld_task_id = '".$taskid."'");
    
	$grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."'  AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");
    
/* ends calculating all resources within Task */
   }  

	if($grp_resids != '') {

            $cntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_resource_master where fld_task_id='".$taskid."' AND fld_id NOT IN(".$grp_resids.")");
	} else {

            $cntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_resource_master where fld_task_id='".$taskid."'");

	}

	$compr_grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_resid) FROM itc_exp_lockpassport where 
										fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
												AND fld_expid='".$expid."' AND fld_taskid='".$taskid."' AND fld_lock_flag='1'");
					if($compr_grp_resids != '') {

						$comprcntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where 										fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
									AND fld_expid='".$expid."' AND fld_taskid='".$taskid."' AND fld_lock_flag='1' AND fld_resid IN(".$compr_grp_resids.")");
					}
					else{
						$comprcntresid_basedtask = '';
					}

if($grpresids_req_taskwise == "")
            {
                $rescomplete_req_taskwise = 0;
            }
            else
            {
                $rescomplete_req_taskwise = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (". $grpresids_req_taskwise.") AND fld_delstatus='0' AND fld_schedule_id = '".$scheduleid."' AND fld_schedule_type = '".$schtype."' AND fld_student_id='".$uid."' AND fld_read_status='1'");
            }
       
      $total_status_taskwise = $allresourceids_req_taskwise ;
      $total_completed_taskwise =$rescomplete_req_taskwise ;  
      $task_progress=($total_completed_taskwise/ $total_status_taskwise)*100;
      $task_progress=round($task_progress);                        
                                         ?>
                                         <li>
                                           <label>
                                           <?php 
//checking the lock flag to the task for particular task,destination and expedition name

    


if($task_progress != '100') { echo $taskname; } else {

	if($cntresid_basedtask == $comprcntresid_basedtask) {
	echo $taskname;
	}
	else {
	
// modified by karthick  map id = '2' and std id ='1'
                                           if($expuid == 2){
                                                $schtype =15;
                                                $values = $scheduleid.",".$expid.",".$schtype.",".'2'.",".$destid.",".$taskid;
                                                $call = "removesections('#assignment'); showpageswithpostmethod('tools-expedition-show','tools/passport/tools-expedition-show.php','id=".$values."')";
                                            }
                                            else{
                                                $i =1;
                                                //$values = $scheduleid."~".$fld_module_id."~".$schtype;
                                                $values =  $destid.','.$taskid.','.$i.','.$expid.','.$scheduleid.','.$schtype.",".'passport'.",".$urlformedia;
                                                $call = "removesections('#tools-passport-passport'); showpageswithpostmethod('assignment-expedition-resourses','assignment/expedition/assignment-expedition-resourses.php','id=".$values."')"; 
                                            }
                                            ?>
                                           <a onclick="<?php echo $call; ?>" style=" cursor:pointer;color:#0011FF;" onMouseOver="this.style.color='#FF00FF'" onMouseOut="this.style.color='#0011FF'"><?php echo $taskname;?></a>
<?php } 
}
// modified ends by karthick  map id = '2' and std id ='1'
?>
                                           
                                                
                        <div id="progress" class="graph" style="margin-left:580px; margin-top:-12px">
                            <div id="bar" style="width:<?php echo  $task_progress.'%';?>"><div style="margin-left: 102px; margin-top: -7px; font-size: 12px;"><?php echo  $task_progress."%"; ?></div></div>
                        </div>
                        </label>
                     
                                            <ul>
                                                 <?php 
     /** For selecting and displaying resources related to the task **/  
                                if($checkrstatusid == '0'){

                      $qryresourcedetails=$ObjDB->QueryObject("SELECT a.fld_id AS resid,a.fld_res_name AS resname,a.fld_res_file_name as filename,
    a.fld_res_file_type as filetype FROM itc_exp_resource_master as a
                                         LEFT JOIN itc_exp_res_status as f ON f.fld_res_id = a.fld_id
                                                                              WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0'
                                        AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '0' AND f.fld_school_id = '0'");
                                                    

                }
                else {


                      $qryresourcedetails=$ObjDB->QueryObject("SELECT a.fld_id AS resid,a.fld_res_name AS resname,a.fld_res_file_name as filename,
    a.fld_res_file_type as filetype FROM itc_exp_resource_master as a
                                         LEFT JOIN itc_exp_res_status as f ON f.fld_res_id = a.fld_id
                                                                              WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0'
                                        AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '".$indid."' AND f.fld_school_id = '".$schoolid."'");
                                                    

                }                  
    
                                  
                            if($qryresourcedetails->num_rows>0)
                                {
                                while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                    {
                                        extract($rowresourcedetails);

            $chklockflg = $ObjDB->SelectSingleValue("SELECT fld_lock_flag as lockflag FROM itc_exp_lockpassport 
                                     WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
                                    AND fld_expid='".$expid."' AND fld_destid='".$destid."'
                                    AND fld_taskid='".$taskid."' AND fld_resid='".$resid."'");

            if($checkrstatusid == '0'){  

                $chkres_statusflg = $ObjDB->SelectSingleValue("SELECT f.fld_status FROM itc_exp_resource_master as a
                                         LEFT JOIN itc_exp_res_status as f ON f.fld_res_id = a.fld_id
                                                                              WHERE a.fld_task_id='".$taskid."' AND a.fld_id = '".$resid."' AND a.fld_delstatus='0'
                                        AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '0' AND f.fld_school_id = '0'");

            }
            else {

                $chkres_statusflg = $ObjDB->SelectSingleValue("SELECT f.fld_status FROM itc_exp_resource_master as a
                                         LEFT JOIN itc_exp_res_status as f ON f.fld_res_id = a.fld_id
                                                                              WHERE a.fld_task_id='".$taskid."' AND a.fld_id = '".$resid."' AND a.fld_delstatus='0'
                                        AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
                                       AND f.fld_user_id = '".$indid."' AND f.fld_school_id = '".$schoolid."'");

            }
        
        $rescomplete_student = $ObjDB->SelectSingleValue("SELECT fld_read_status FROM itc_exp_res_play_track WHERE fld_exp_id= '".$expid."' AND fld_schedule_id=".$scheduleid." AND fld_schedule_type = '".$schtype."'  AND fld_dest_id='".$destid."' AND fld_task_id='".$taskid."' AND fld_res_id='".$resid."' AND fld_student_id='".$uid."'");
                                                       
                                                            ?>
                                                            <li>

                                   <label><?php 
//Get href link to resource for required or optional types of toggle modified on 26th december 2014
// checking lock flag to resourse for particular resource,task,destination and expedition
	if($chklockflg == 0) {

 if($chkres_statusflg == 1 || $chkres_statusflg == 2) {
if($chkres_statusflg == 2) {
$rescomplete_student = 1; 
}
 if($rescomplete_student == 1) { 
$resclick = "loadiframes('path?destinationid=".$destid."&taskid=".$taskid."&resourceid=".$resid."&type=".$filetype."&filename=".$filename."','preview',$expid,$expedition_cnt);"; ?><a onclick="<?php echo $resclick;?>" style=" cursor:pointer;color:#0011FF;" onMouseOver="this.style.color='#FF00FF'" onMouseOut="this.style.color='#0011FF'"><?php echo $resname;?></a><?php
}
else{ echo $resname; }

}
	} else{ echo $resname; } 	?>
</label>
                                                            </li>
                                                                                                                       
                                                             <?php
                                                    
                                                     }  //ends of  while($rowresourcedetails = $qryresourcedetails->fetch_assoc())
                                                    }  // ends of  if($qryresourcedetails->num_rows>0)
                                                    ?>
                                            </ul>
                                        </li>
                                    <?php
                                
                                }  //ends of while($rowtaskdetails = $qrytaskdetails->fetch_assoc())   
                                }   // ends of  if($qrytaskdetails->num_rows>0)
                                ?>
                            </ul>
                        
                        </li>
                    </ul>
            <?php
        }  // ends of   while($rowdestdetails = $qrydestdetails->fetch_assoc())
        ?>
       </div>
        <?php
    }   // ends of if($qrydestdetails->num_rows>0)

?>
           </div>
     <?php
$expedition_cnt++;
 }// end of while 
   //   } // end of if $qryexp
// if($qryexp->num_rows  == 0){ echo "No Records Found."; }
        ?> 
<!-- each expedition will end from here -->

<!-- end the contents from here --> 
          </div>
<?php if($qryexp->num_rows>0){ ?>
         <nav>
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
         </nav>
<?php } ?>
         
              </div>

                </div>
             </div>
          </div> 
<?php }  } // end of if $qryexp ?>
    </div>   <!-- end for <div class='container'>  --->

</section>

<script language="javascript" type="text/javascript">
$.getScript('tools/passport/tools-passport.js');
      var Page = (function() {
        
        var config = {
            $bookBlock : $( '#bb-bookblock' ),
            $navNext : $( '#bb-nav-next' ),
            $navPrev : $( '#bb-nav-prev' ),
            $navFirst : $( '#bb-nav-first' ),
            $navLast : $( '#bb-nav-last' )
          },
          init = function() {
           // alert(123);
            config.$bookBlock.bookblock( {
              speed : 800,
              shadowSides : 0.2,
              shadowFlip : 0.7
            } );
            initEvents();
          },
          initEvents = function() {
            
            var $slides = config.$bookBlock.children();

            // add navigation events
            config.$navNext.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'next' );
              return false;
            } );

            config.$navPrev.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'prev' );
              return false;
            } );

            config.$navFirst.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'first' );
              return false;
            } );

            config.$navLast.on( 'click touchstart', function() {
              config.$bookBlock.bookblock( 'last' );
              return false;
            } );
            
            // add swipe events
            $slides.on( {
              'swipeleft' : function( event ) {
                config.$bookBlock.bookblock( 'next' );
                return false;
              },
              'swiperight' : function( event ) {
                config.$bookBlock.bookblock( 'prev' );
                return false;
              }
            } );

            // add keyboard events
            $( document ).keydown( function(e) {
              var keyCode = e.keyCode || e.which,
                arrow = {
                  left : 37,
                  up : 38,
                  right : 39,
                  down : 40
                };

              switch (keyCode) {
                case arrow.left:
                  config.$bookBlock.bookblock( 'prev' );
                  break;
                case arrow.right:
                  config.$bookBlock.bookblock( 'next' );
                  break;
              }
            } );
          };

          return { init : init };

      })();
       Page.init();
    </script>
<?php
    @include("footer.php");

    ?>
