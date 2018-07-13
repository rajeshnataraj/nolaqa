<?php
	@include("sessioncheck.php");
/*------
	Page - Passport
	Description:
		1.Teacher can block/unblock Expedition Resource hyperlink in student passport
		2.Student can view the expedition using passport 
	History:	
------*/?>
<script src="tools/passport/tools-passport.js" type="text/javascript" language="javascript"></script>

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

?>
<section data-type='#tools-passport' id='tools-passport-passport'>
  
  <div class='container'>
	    <div class='row'>
	      <div class='twelve columns'>
	        <p class="dialogTitle">Passport</p>
	        <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
	      </div>
	    </div>
<?php if($sessmasterprfid==9){ 
        } ?>

 <?php if($sessmasterprfid==10){ ?>
&nbsp;
	<div class='row formBase'>
       		<div class='eleven columns centered insideForm'>
	            <div class="main clearfix">
                       <div class="bb-custom-wrapper">
			  <div id="bb-bookblock" class="bb-bookblock">
<!-- start the contents from here -->
<!-- each expedition will start from here -->
<?php
          
          /** shows the expeditions for the student **/
	 $label2= 'AND DATE(a.fld_startdate) <= DATE(NOW())';
  
         $qryexp = $ObjDB->QueryObject("SELECT a.fld_exp_id AS expid,a.fld_class_id as classid, 
                                        a.fld_id AS scheduleid, c.fld_exp_name AS expname
                                        FROM `itc_class_indasexpedition_master` AS a 
                                        LEFT JOIN `itc_class_exp_student_mapping` AS b ON a.fld_id=b.fld_schedule_id 
                                        LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
                                        LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                        WHERE a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
                                        AND b.fld_student_id='".$uid."' ".$label2." 
                                        AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')");
	$expedition_cnt = 0;
	  if($qryexp->num_rows>0){
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
		
/* ends calculating all resources within expedtion */
   }
if($groupresourceids_req == '') {
			$rescomplete_req = 0;
		}
		else {
 			$rescomplete_req = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$groupresourceids_req.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");
		}    
 
      $total_status_exp = $allresourceids_req + $allresourceids_opnl;
      $total_completed_exp =$rescomplete_req + $allresourceids_opnl;  
      $exp_progress=($total_completed_exp/ $total_status_exp)*100;
      $exp_progress=round($exp_progress);

?>
<!-- catalog process starts here -->
					<div class="bb-item">
<p style="margin-left: 25px;"></p>
			<p>Expedition Name: <?php echo $expname; ?>
				<div id="progress123" class="graph" style="margin-top:-15px; margin-left:625px;">
				      <div id="bar" style="width:<?php echo $exp_progress.'%';?>"><div style="margin-left: 102px; margin-top: -7px; font-size: 12px;"><?php echo $exp_progress."%"; ?></div></div>
				</div>
			</p>
			&nbsp;
	<?php 
                        
/* calculation for progress bar starts */   
         
                        if($checkrstatusid == '0'){
        
        $resourcegroupids=$ObjDB->SelectSingleValue("select 
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
       
       
       $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");
       $totalresource=sizeof(explode(',',$resourcegroupids));
       
   }  
   else{
       
       $resourcegroupids=$ObjDB->SelectSingleValue("select 
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
                                                            AND d.fld_school_id = '".$senshlid."'
                                                            AND d.fld_user_id = '".$indid."'
                                                            and d.fld_status = '1'
                                                            and a.fld_delstatus = '0'
                                                            and b.fld_delstatus = '0'
                                                            and c.fld_delstatus = '0'
                                                    GROUP BY a.fld_id) as cnt");
       
       
       $rescomplete = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$resourcegroupids.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");
       $totalresource=sizeof(explode(',',$resourcegroupids));

   }            
                   
/* calculation for progress bar ends */  
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
						                    AND c.fld_school_id='0' AND a.fld_delstatus = '0'
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

 		$grpresids_req_destnwise=$ObjDB->SelectSingleValue("SELECT
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


 /* ends calculating all resources within Destination  */
       


	    } else {
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
  



	    }

if($grpresids_req_destnwise == '')
{
   $rescomplete_req_destwise = 0;

}
else {
$rescomplete_req_destwise = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_exp_res_play_track WHERE fld_res_id IN (".$grpresids_req_destnwise.") AND fld_delstatus='0' AND fld_student_id='".$uid."' AND fld_read_status='1'");

}




?>
<!-- destination ul tag starts -->
 <ul class="tree" style="margin-left: 15px;">
                        <li>
                            
                            <label><?php echo $destname;


?>
				<div id="progress" class="graph" style="margin-left:611px; margin-top:-24px">
				      <div id="bar" style="width:<?php echo $dest_progress.'%';?>"><div style="margin-left: 102px; margin-top: -7px; font-size: 12px;"><?php echo $dest_progress."%"; ?></div></div>
				</div>
			    </label>
                            &nbsp;
                            &nbsp;
</li>
</ul>
<!-- destination ul tag ends -->
<?php



	}   // ends of  while($rowdestdetails = $qrydestdetails->fetch_assoc())
?>
</div>   <!-- ends for  <div style="height:550px; overflow:auto;"> -->
<?php
}  // ends of if($qrydestdetails->num_rows>0)

?>
						
					</div>    <!-- ends for <div class="bb-item"> -->
					
<!-- catalog process ends here -->
<?php



		    }  // ends of while($rowexp = $qryexp->fetch_assoc())
	   }  // ends of  if($qryexp->num_rows>0){
?>



			 </div>
		 		<nav>
				    <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
				    <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
				    <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
				    <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
				 </nav>

			 

		       </div>
		    </div>
	       </div>
	</div>
<?php } ?>
    </div>   <!-- ends for <div class='container'> -->

</section>

<script language="javascript" type="text/javascript">

      var Page = (function() {
        
        var config = {
            $bookBlock : $( '#bb-bookblock' ),
            $navNext : $( '#bb-nav-next' ),
            $navPrev : $( '#bb-nav-prev' ),
            $navFirst : $( '#bb-nav-first' ),
            $navLast : $( '#bb-nav-last' )
          },
          init = function() {
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

