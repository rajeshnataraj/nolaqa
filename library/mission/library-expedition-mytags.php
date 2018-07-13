<?php
@include("sessioncheck.php");
$expeditionid = isset($method['id']) ? $method['id'] : '0';

$expeditionname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
?>


<section data-type='2home' id='library-expedition-mytags'> 
    <div class='container'>
    	<!--Load the Expedition Name-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo "My Tags"."/ ".$expeditionname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <script type="text/javascript" charset="utf-8">
            $('#tablecontents15').slimscroll({
                height:'auto',
                size: '3px',
                railVisible: false,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 9,
                color: '#88ABC2',
                wheelStep: 1,
             });
             
           
        </script> 
        <!--Load the Expedition Form-->
        <div class='row '>
            <div class='twelve columns centered insideForm'>
                <form name="exporderforms" id="exporderforms">
						<?php 
                        $qrydestinations = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fld_order AS destorder, fld_next_order AS nextdestorder, fld_toggle_status AS togstatus, fld_dest_status AS deststatus
                                                                FROM itc_exp_destination_master 
                                                                WHERE fld_exp_id='".$expeditionid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order"); 
                        $destcnt = 0;
                        if($qrydestinations->num_rows>0) {
                            while($rowqrydestinations = $qrydestinations->fetch_object()){
                                $destid[$destcnt]=$rowqrydestinations->destid;
                                $destname[$destcnt]=$rowqrydestinations->destname;
                                $destorder[$destcnt]=$rowqrydestinations->destorder;
                                $nextdestorder[$destcnt]=$rowqrydestinations->nextdestorder;
                                $desttoggle[$destcnt]=$rowqrydestinations->togstatus;
                                $desstatus[$destcnt]=$rowqrydestinations->deststatus;
                                $destcnt++;
                            }
                        }
                        ?>
                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="expordertable" width="100%">
                            <thead class='tableHeadText'>
                                <tr>
                                    <th class='centerText' style="width: 40%">Title</th>
                                    <th class='centerText' style="width: 20%">Type</th>
                                    <th class='centerText' style="width: 40%">Tags</th>
                                </tr>
                            </thead>
                        </table>
                        
                        <div style="max-height:500px;width:100%" id="tablecontents15"  >
                        <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                        <?php
                        for($i=0;$i<$destcnt;$i++)
                        {
                            $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$destid[$i]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                            
                            if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                            {
                                $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$destid[$i]."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                                {
                                    $dstatus = $desstatus[$i];
                                }
                            }
                            ?>
                            <input type="hidden" name="dest_<?php echo $destid[$i];?>" id="dest_<?php echo $i;?>" value="<?php echo $destid[$i];?>">
                            <tr>
                                <td style="width: 40%">
                                    <div><?php echo $destname[$i]; ?></div>
                                </td>
                                <td style="width: 20%" class='centerText'>
                                    Destination
                                </td>
                                <td style="width: 40%" class='centerText'> 
                                     
                                         <input style="width:50%" type="text" name="mytagdest" value="" class="destinat" id="form_mytags_desti_<?php echo $destid[$i];?>" />
                                     
                                </td>
                            </tr>
                            
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t5 = new $.TextboxList('#form_mytags_desti_<?php echo $destid[$i];?>', 
		{
                    
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
                    
		<?php 			
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='31' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$destid[$i]."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t5.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			      <?php }
				}                       
		?>	
                        var expid='<?php echo $expeditionid; ?>';
                         var destid='<?php echo $destid[$i]; ?>';
		t5.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=search&tag_type=31&expid='+expid+'&destid='+destid, type:"POST", dataType: 'json', success: function(r){
			t5.plugins['autocomplete'].setValues(r);
			t5.getContainer().removeClass('textboxlist-loading');
                        $('.textboxlist-autocomplete').css({"position":"absolute","z-index":" 1000","text-align":"left","width":"38%"});

		}});						
	});
</script>

<!-- Autocomplete script end -->

                            <?php
                            $qrytasks = $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname, fld_order AS taskorder, fld_next_order AS nexttaskorder, fld_toggle_status AS togstatus, fld_task_status AS taskstatus
                                                                FROM itc_exp_task_master 
                                                                WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order"); 
                            $taskcnt = 0;
                            if($qrytasks->num_rows>0) {
                                while($rowqrytasks = $qrytasks->fetch_object()){
                                    $taskids[$taskcnt]= $rowqrytasks->taskid;
                                    $taskid[$taskcnt]= $destid[$i]."_".$rowqrytasks->taskid;
                                    $taskname[$taskcnt]=$rowqrytasks->taskname;
                                    $taskorder[$taskcnt]=$rowqrytasks->taskorder;
                                    $nexttaskorder[$taskcnt]=$rowqrytasks->nexttaskorder;
                                    $tasktoggle[$taskcnt]=$rowqrytasks->togstatus;
                                    $taskstatus[$taskcnt]=$rowqrytasks->taskstatus;
                                    $taskcnt++;
                                }
                            }
                            
                            for($j=0;$j<$taskcnt;$j++)
                            {
                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_task_id='".$taskids[$j]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                            
                                if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                {
                                    $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                    $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_task_id='".$taskids[$j]."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                    if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                    {
                                        $tstatus = $taskstatus[$j];
                                    }
                                }
                                ?>
                                <input type="hidden" name="task_<?php echo $taskid[$j];?>" id="task_<?php echo $j;?>" value="<?php echo $taskids[$j];?>">
                                <tr>
                                    <td style="width: 40%">
                                    	<div><?php echo $taskname[$j]; ?></div>
                                    </td>
                                    <td style="width: 20%" class='centerText'>
                                        Task
	                            </td>
                                    <td style="width: 40%" class='centerText'>
                                        <input style="width:80%" type="text" name="mytagtas" value="" class="task" id="form_mytags_task_<?php echo $taskids[$j];?>" />
                                    </td>
                                </tr>
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t6 = new $.TextboxList('#form_mytags_task_<?php echo $taskids[$j];?>', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 			
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='32' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$taskids[$j]."'");
                                if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t6.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			      <?php }
				}			
		?>
                          var expid='<?php echo $expeditionid; ?>';
                         var taskid='<?php echo $taskids[$j]; ?>';
		t6.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=search&tag_type=32&expid='+expid+'&taskid='+taskid, type:"POST", dataType: 'json', success: function(r){
			t6.plugins['autocomplete'].setValues(r);
			t6.getContainer().removeClass('textboxlist-loading');	
                        $('.textboxlist-autocomplete').css({"position":"absolute","z-index":" 1000","text-align":"left","width":"38%"});
		}});						
	});
</script>

<!-- Autocomplete script end -->
                                <?php
                                $qryres = $ObjDB->QueryObject("SELECT fld_id AS resid, fld_res_name AS resname, fld_order AS resorder, fld_next_order AS nextresorder, fld_toggle_status AS togstatus, fld_resource_status AS resstatus, fld_typeof_res
                                                                FROM itc_exp_resource_master 
                                                                WHERE fld_task_id='".$taskids[$j]."' AND fld_flag='1' AND fld_delstatus='0' 
                                                                ORDER BY fld_order"); 
                                $rescnt = 0;
                                if($qryres->num_rows>0) {
                                    while($rowqryres = $qryres->fetch_object()){
                                        $resids[$rescnt]= $rowqryres->resid;
                                        $resid[$rescnt]= $taskid[$j]."_".$rowqryres->resid;
                                        $resname[$rescnt]=$rowqryres->resname;
                                        $resstatus[$rescnt]=$rowqryres->resstatus;
                                        $restoggle[$rescnt]=$rowqryres->togstatus;
                                        $restype[$rescnt]=$rowqryres->fld_typeof_res;
                                        $resorder[$rescnt]=$rowqryres->resorder;
                                        $nextresorder[$rescnt]=$rowqryres->nextresorder;
                                        $rescnt++;
                                    }
                                }
                                
                                for($k=0;$k<$rescnt;$k++)
                                {
                                    $status = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_res_id='".$resids[$k]."' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");
                            
                                    if($status=='' or $status=='0' or $status==NULL)
                                    {
                                        $createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");

                                        $status = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_res_id='".$resids[$k]."' AND fld_created_by IN (".$createdids.") AND fld_flag='1'");

                                        if($status=='' or $status=='0' or $status==NULL)
                                        {
                                            $status = $resstatus[$k];
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="<?php echo $destid[$i];?>_<?php echo $taskid[$j];?>_<?php echo $resid[$k];?>" id="res_<?php echo $resids[$k];?>" value="<?php echo $status;?>">
                                    <tr>
                                    	<td style="width: 40%">
                                            <div><?php echo $resname[$k]; ?></div>
                                        </td>
                                        <td style="width: 20%" class='centerText'>
                                            <div>Resource</div>
                                        </td>
                                        <td style="width: 40%" class='centerText'>
                                            <input style="width:80%" type="text" name="mytagres" value="" class="resource" id="form_mytags_resor_<?php echo $resids[$k];?>" />
                                        </td>
                                    </tr>
<!-- Autocomplete script start -->

<script type="text/javascript" charset="utf-8">	
	$(function(){				
		var t7 = new $.TextboxList('#form_mytags_resor_<?php echo $resids[$k];?>', 
		{
			unique: true, plugins: {autocomplete: {}},
			bitsOptions:{editable:{addKeys: [188]}}	});
		<?php 			
				$qrytag = $ObjDB->QueryObject("SELECT a.fld_id AS tagid,a.fld_tag_name AS tagname 
				                              FROM itc_main_tag_master AS a, itc_main_tag_mapping AS b 
											  WHERE a.fld_id=b.fld_tag_id AND b.fld_tag_type='33' 
											        AND b.fld_access='1' AND a.fld_created_by='".$uid."' 
													AND a.fld_delstatus='0' AND b.fld_item_id='".$resids[$k]."'");
				if($qrytag->num_rows > 0) {
					
					while($restag = $qrytag->fetch_assoc()){
						
						extract($restag); ?>
            			t7.add('<?php echo $ObjDB->EscapeStrAll($tagname); ?>','<?php echo $tagid; ?>');				
			      <?php }
				}                      
		?>		
                        var expid='<?php echo $expeditionid; ?>';
                         var resoid='<?php echo $resids[$k]; ?>';
		t7.getContainer().addClass('textboxlist-loading');				
		$.ajax({url: 'autocomplete.php', data: 'oper=search&tag_type=33&expid='+expid+'&resoid='+resoid, type:"POST", dataType: 'json', success: function(r){
			t7.plugins['autocomplete'].setValues(r);
			t7.getContainer().removeClass('textboxlist-loading');
                        $('.textboxlist-autocomplete').css({"position":"absolute","z-index":" 1000","text-align":"left","width":"38%"});
		}});						
	});
</script>

<!-- Autocomplete script end -->
                                    <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                            
                        </div>
                </form>
            </div>
        
            <div class="row rowspacer" style="margin-top:20px;">
                <div class="tLeft" style="color:#F00;"></div>
                <div class="tRight">
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Status" onClick="fn_savecontenttagstatus(<?php echo $expeditionid; ?>);" />
                </div>
            </div>
        </div>

    </div>
</section>

<?php
@include("footer.php");
