<?php
	@include("sessioncheck.php");
/*------
	Page - Passport lock or unlock status using by teacher
	Description:
		1.Teacher can block/unblock Expedition Resource hyperlink in student passport
		
	History: Modified on 2/1/2015 by vijayalakshmi PHP Programmer
	Created BY : Vijayalakshmi PHP Programmer.(9/9/2014)	
------*/?>
<style>
label {
cursor: default;
}
</style>
<script src="tools/passport/tools-passport.js" type="text/javascript" language="javascript"></script>

<?php

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);
$classid = $id[0];
$expid = $id[1];
$scheduleid = $id[2];

?>
    <section data-type='#tools-passport' id='tools-passport-passport'>
	<div class='container'>
		<div class='row'>
		  <div class='twelve columns'>
		    <p class="dialogTitle">Passport</p>
		    <p class="dialogSubTitleLight">Choose a tool below to continue.</p>
		  </div>
		</div>
		&nbsp;
	<div class='row formBase'>
	   <div class='eleven columns centered insideForm' style="width: 75%;">
	     <div class="main clearfix">
	       <div class="bb-custom-wrapper">
<form id="exp_materialform1" name="exp_materialform1">
		   <div id="bb-bookblock" class="bb-bookblock">
			<!-- start the contents from here -->
			<!-- each expedition will start from here -->
<!-- each expedition will start from here -->
<?php
          
          /** shows the expeditions for the teacher **/
         $label2= 'AND DATE(a.fld_startdate) <= DATE(NOW())';


         $qryexp = $ObjDB->SelectSingleValue("SELECT c.fld_exp_name AS expname FROM `itc_class_indasexpedition_master` AS a 
                                        LEFT JOIN `itc_class_teacher_mapping` AS b ON a.fld_class_id = b.fld_class_id 
                                        LEFT JOIN itc_exp_master AS c ON a.fld_exp_id=c.fld_id 
                                        LEFT JOIN itc_class_master AS d on d.fld_id=a.fld_class_id 
                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_id='".$scheduleid."' AND a.fld_exp_id='".$expid."' AND b.fld_teacher_id='".$uid."'
					AND a.fld_delstatus='0' AND d.fld_delstatus='0' AND d.fld_lock='0' 
                                        ".$label2." 
                                        AND a.fld_license_id IN (SELECT fld_license_id FROM itc_license_track 
                                        WHERE fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND 
					fld_start_date<='".date("Y-m-d")."' AND fld_end_date >='".date("Y-m-d")."')");

	$checkrstatusid = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expid."' 
							    AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
/**
 * 
 * For displaying Expedition Name,
 * Destinations,
 * Tasks,
 * Resources
 * 
 * **/
/** For selecting and displaying Destinations related to the expedition **/

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

	$grp_resorid =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."'                             AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");

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

	$grp_resorid =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."'                             AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");
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
                                                AND fld_expid='".$expid."' AND fld_created_by='".$uid."' AND fld_lock_flag='1'");
	  if($compr_gropp_resids != '') {



                        $comprcntresid_basedexp = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where                                                   fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
                                                AND fld_expid='".$expid."' AND fld_lock_flag='1' AND fld_created_by='".$uid."' AND fld_resid IN(".$compr_gropp_resids.")");
                    }
                    else{
                        $comprcntresid_basedexp = '';
                    }

?>
			<div class="bb-item">
				<p style="margin-left: 25px;"></p>
				<p> <input type="checkbox" name="chkexplock" id="chkexplock" value="<?php echo $expid; ?>" level="Mainparent" class="maincheckbox"   <?php if($cntresid_basedexp == $comprcntresid_basedexp) 
echo 'checked';
else
echo '';
?>>&nbsp;<a style="float:left; font-size:18px;padding-right: 10px; margin-top: 0px;" id="explockstatus" class="<?php if($cntresid_basedexp == $comprcntresid_basedexp)  
echo 'icon-synergy-locked';
else
echo 'icon-synergy-unlocked';
?>" data-value=""></a>Expedition Name: <?php echo $qryexp; ?></p>
					<?php
					if($qrydestdetails->num_rows>0)
					{  ?>
						<div style="height:550px; overflow:auto;">
						<?php
						    while($rowdestdetails = $qrydestdetails->fetch_assoc())
						    {
						        extract($rowdestdetails);
		
				if($checkrstatusid == '0'){

					$grp_resrceids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 							   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");
							
				}
				else {

					$grp_resrceids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 							   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");
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
												 AND fld_created_by='".$uid."' AND fld_lock_flag='1'");
					if($compr_grp_resrceids != '') {


						$comprcntresid_baseddest = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where 													fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
												AND fld_expid='".$expid."' AND fld_destid='".$destid."' AND fld_lock_flag='1' AND fld_created_by='".$uid."' AND fld_resid IN(".$compr_grp_resrceids.")");
					}
					else{
						$comprcntresid_baseddest = '';
					}

						   
					?>
							<ul class="tree" style="margin-left: 15px;">
                           				   <li>
                                				<label><input type="checkbox" name="chkdestlock" id="chkdestlock" value="<?php echo $destid; ?>" level="parent" class="<?php echo $destid.'_';?>parentCheckBox parentbox chkdestn" <?php if($cntresid_baseddest == $comprcntresid_baseddest) 
echo 'checked';
else
echo '';
?>>&nbsp;<a style="float:left; font-size:18px;padding-right: 10px; margin-top: 0px;" id="destnlockstatus" class="<?php if($cntresid_baseddest == $comprcntresid_baseddest)  
echo 'icon-synergy-locked';
else
echo 'icon-synergy-unlocked';
?>" data-value="<?php echo $expid.','.$destid; ?>"></a><?php echo $destname;?></label>
								
								<ul class="<?php echo $destid.'_';?>expanded">
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


					$grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 										   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '0' AND fld_school_id = '0'");
				}
				else {
					$grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_res_id) FROM itc_exp_res_status where fld_exp_id='".$expid."' 							   AND fld_status='3' AND fld_flag='1' AND fld_user_id = '".$indid."' AND fld_school_id = '".$schoolid."'");
				}
if($grp_resids != '') {

					$cntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_resource_master where 											fld_task_id='".$taskid."' AND fld_id NOT IN(".$grp_resids.")");
} else {

$cntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_resource_master where 											fld_task_id='".$taskid."'");

}
					
					$compr_grp_resids =  $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_resid) FROM itc_exp_lockpassport where 
										fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
												AND fld_expid='".$expid."' AND fld_taskid='".$taskid."' AND fld_created_by='".$uid."' AND fld_lock_flag='1'");
					if($compr_grp_resids != '') {

						$comprcntresid_basedtask = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_lockpassport where 										fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' 
									AND fld_expid='".$expid."' AND fld_taskid='".$taskid."' AND fld_lock_flag='1' AND fld_created_by='".$uid."' AND fld_resid IN(".$compr_grp_resids.")");
					}
					else{
						$comprcntresid_basedtask = '';
					}	
?>
					<li><label><input type="checkbox" name="chktasklock" value="<?php echo $destid.','.$taskid; ?>" level="child" class="<?php echo $taskid.'_';?>childCheckBox childbox <?php echo $destid.'_';?>destCheckBox" id="<?php echo $destid.'_';?>chktask" <?php 
if($cntresid_basedtask == $comprcntresid_basedtask) 
echo 'checked';
else
echo '';

?>>&nbsp;<a style="float:left; font-size:18px;padding-right: 10px; margin-top: 0px;" id="tasklockstatus" class="<?php if($cntresid_basedtask == $comprcntresid_basedtask)  
echo 'icon-synergy-locked';
else
echo 'icon-synergy-unlocked';
?>" data-value="<?php echo $expid.','.$destid.','.$taskid; ?>"></a><?php echo $taskname;?></label></li>
					<ul>
 <?php
/** For selecting and displaying resources related to the task **/  
              			if($checkrstatusid == '0'){

				      $qryresourcedetails=$ObjDB->QueryObject("SELECT a.fld_id AS resid,a.fld_res_name AS resname FROM itc_exp_resource_master as a
									     LEFT JOIN itc_exp_res_status as f ON f.fld_res_id = a.fld_id
                                                                              WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0'
										AND f.fld_exp_id = '".$expid."' AND (f.fld_status='1' OR f.fld_status='2') 
								       AND f.fld_user_id = '0' AND f.fld_school_id = '0'");
                                                    

				}
				else {

				      $qryresourcedetails=$ObjDB->QueryObject("SELECT a.fld_id AS resid,a.fld_res_name AS resname FROM itc_exp_resource_master as a
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

 						$chkreslock_status = $ObjDB->SelectSingleValue("SELECT x.fld_lock_flag as lockflag FROM itc_exp_lockpassport AS x
											 WHERE fld_classid='".$classid."' AND fld_scheduleid='".$scheduleid."' AND fld_expid='".$expid."' AND 							fld_destid='".$destid."' AND fld_taskid='".$taskid."' AND fld_created_by='".$uid."' AND fld_resid='".$resid."'");
						                                                
					    ?>
 						<li>

						<label><input type="checkbox" name="chkresourcelock" value="<?php echo $destid.','.$taskid.','.$resid; ?>" level="subchild" class="<?php echo $taskid.'_';?>subchildCheckBox subchild <?php echo $destid.'_';?>resCheckBox" id="<?php echo $destid.'_';?>subchild" <?php 
if($chkreslock_status == 1)
echo 'checked';
else
echo '';
?>>&nbsp;<a style="float:left; font-size:18px;padding-right: 10px; margin-top: 0px;" id="reslockstatus" class="<?php  if($chkreslock_status == 1)
echo 'icon-synergy-locked';
else
echo 'icon-synergy-unlocked';?>" data-value="<?php echo $expid.','.$destid.','.$taskid.','.$resid; ?>"></a><?php echo $resname;?></label>
						</li>
					    <?php

					       }  // ends of while($rowresourcedetails = $qryresourcedetails->fetch_assoc())

					    }   // ends of if($qryresourcedetails->num_rows>0)
                  
					 ?>
 					</ul>
				<?php }  // ends of if($qrytaskdetails->num_rows>0)
					}   // ends of while($rowtaskdetails = $qrytaskdetails->fetch_assoc())
?>
								</ul>
							   </li>
							</ul>
					     <?php }   // ends of while($rowdestdetails = $qrydestdetails->fetch_assoc())
 ?>
						</div>
					<?php   }  //ends of if($qrydestdetails->num_rows>0)
					?>

 <input type="hidden" name="hidscheduleid" id="hidscheduleid" value="<?php echo $scheduleid; ?>"/>
 <input type="hidden" name="hidclassid" id="hidclassid" value="<?php echo $classid; ?>"/>

			</div> <!-- ends for <div class="bb-item"> -->
		   </div>   <!-- ends for <div id="bb-bookblock" class="bb-bookblock"> -->

 <div class='row rowspacer' style="padding-top:20px; margin-left:90px;">
           <div class='six columns'>
               <p class='btn primary twelve columns'>
                   <a onclick="fn_cancel('tools-passport-passport')">Cancel</a>
               </p>
           </div>
           <div class='six columns' id="savebtnlock" >
               <p class='btn secondary twelve columns'>
                   <a onclick="fn_savelockexp(<?php echo $classid.','.$scheduleid.','.$expid; ?>)">Lock</a>
               </p>
           </div>
       </div>
</form>
	       </div>
	     </div>
	   </div>
	</div> <!-- ends for <div class='row formBase'> -->
</div>  <!-- ends for  <div class='container'> -->
    </section>

<script language="javascript" type="text/javascript">

	$(function(){

		$(".maincheckbox").click(function() {

			$(".parentbox").closest("ul").find(":checkbox").prop("checked",this.checked);
		 
		});
	//clicking the parent checkbox should check or uncheck all child checkboxes
		$(".parentbox").click(function() {
			$(this).closest("ul").find(":checkbox").prop("checked",this.checked);
			var tes = $(this).closest("ul").find(":checkbox").prop("checked",this.checked).length;
			var cnt3 = $('.chkdestn').length;
			var cntallchked3 = $('.chkdestn:checked').length;
		 
			if(cntallchked3 == cnt3){
				$('.maincheckbox').prop("checked", true);
			}

			if (!this.checked) {
				$('.maincheckbox').prop("checked", false);
			}
		 
		});
	
		$(".childbox").click(function() {
		   var taskid_bses = $(this).val();
		   var response=trim(taskid_bses);
		   var sep_ptaskid=response.split(',');
		   var taskid = sep_ptaskid[1];
		   var destnid = sep_ptaskid[0];
  		   var currentdesid = $(this).attr('id');
         	   var parentdestid = currentdesid.split('_');
		   var cnt = $('.'+destnid+'_destCheckBox').length;
		   var cntallchked =  $(this).closest("ul").find('.'+destnid+'_destCheckBox:checked').length;

		   if(cnt == cntallchked) {
			$(this).closest("ul").prev().find('.'+destnid+'_parentCheckBox').prop("checked", true);

		   }

 		   var cnt1 = $('.chkdestn').length;
		   var cntallchked1 = $('.chkdestn:checked').length;

		    if(cntallchked1 == cnt1){
		       $('.maincheckbox').prop("checked", true);
		    }
		   if (this.checked) {
                      $(this).closest("ul").find('.'+taskid+'_subchildCheckBox').prop("checked", true);
		   }
		   else
		   {
		      $(this).closest("ul").find('.'+taskid+'_subchildCheckBox').prop("checked", false);
 		      $(this).closest("ul").parents("ul").find('.'+parentdestid[0]+'_parentCheckBox').prop("checked", false);
		      $('.maincheckbox').prop("checked", false);
		   }

		});
		$(".subchild").click(function() {
			var taskid_base = $(this).val();
			var response=trim(taskid_base);
			var sep_taskid=response.split(',');
		        var currentdesid = $(this).attr('id');
			var ptaskid = sep_taskid[1];
			var pdestid = sep_taskid[0];
			var parentdestid = currentdesid.split('_');

			var count = $('.'+ptaskid+'_subchildCheckBox').length;
			var countallchked =  $(this).closest("ul").find('.'+ptaskid+'_subchildCheckBox:checked').length;

		if(countallchked == count){
                	$(this).closest("ul").prev().find('.'+ptaskid+'_childCheckBox').prop("checked", true);

		}

		var count1 = $('.'+pdestid+'_destCheckBox').length;
		var countallchked1 = $(this).closest("ul").parents("ul").find('.'+pdestid+'_destCheckBox:checked').length;
		if(countallchked1 == count1){
			$(this).closest("ul").parents("ul").find('.'+parentdestid[0]+'_parentCheckBox').prop("checked", true);
		}
 		var count2 = $('.chkdestn').length;
		var countallchked2 = $('.chkdestn:checked').length;

		if(countallchked2 == count2){
		$('.maincheckbox').prop("checked", true);
		}

		if (!this.checked) {
                 	$(this).closest("ul").prev().find('.'+ptaskid+'_childCheckBox').prop("checked", false);
                 	$(this).closest("ul").parents("ul").find('.'+parentdestid[0]+'_parentCheckBox').prop("checked", false);
			$('.maincheckbox').prop("checked", false);
		}
		});
	
	});

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
