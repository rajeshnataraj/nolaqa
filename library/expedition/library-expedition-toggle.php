<?php
@include("sessioncheck.php");
$expeditionid = isset($method['id']) ? $method['id'] : '0';

$expeditionname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
?>

<section data-type='2home' id='library-expedition-toggle'>
    <div class='container'>
    	<!--Load the Expedition Name-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $expeditionname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <script type="text/javascript" charset="utf-8">
            $('#tablecontents15').slimscroll({
                height:'auto',
                size: '7px',
                alwaysVisible: true,
                wheelstep: 1,
                railVisible: false,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 9,
                color: '#88ABC2',
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
                        <table class='table table-striped table-bordered setbordertopradius' id="expordertable" width="100%">
                            <thead class='tableHeadText'>
                                <tr>
                                    <th class='centerText' style="width: 30%">Type</th>
                                    <th class='centerText' style="width: 45%">Title</th>
                                    <th class='centerText' style="width: 25%">Status</th>
                                </tr>
                            </thead>
                        </table>
                        
                        <div style="max-height:500px;width:100%" id="tablecontents15"  >
                        <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                        <?php
                        for($i=0;$i<$destcnt;$i++)
                        {
                            $dstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$destid[$i]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                            
                            if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                            {
                                $dstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_dest_id='".$destid[$i]."' AND fld_school_id='0' AND fld_user_id='0' AND fld_flag='1'");

                                if($dstatus=='' or $dstatus=='0' or $dstatus==NULL)
                                {
                                    $dstatus = $desstatus[$i];
                                }
                            }
                            ?>
                            <input type="hidden" name="dest_<?php echo $destid[$i];?>" id="dest_<?php echo $i;?>" value="<?php echo $destid[$i];?>">
                            <tr>
                                <td style="width: 30%">
                                    <?php echo $i+1;?>. Destination
                                </td>
                                <td style="width: 45%">
                                    <div><?php echo $destname[$i]; ?></div>
                                </td>
                                
                            	<td style="width: 25%" <?php if($desttoggle[$i]==1 and $sessprofileid != 2 and $dstatus==3) {?>class='dim'<?php } ?>>
                                    <input name="radiodest_<?php echo $destid[$i]; ?>" id="radiodest1_<?php echo $destid[$i]; ?>" value="1" type="radio" <?php if($dstatus==1) echo 'checked="checked"'; ?> >
                                    <label class="radio <?php if($dstatus==1) echo "checked"; ?>" for="radiodest1_<?php echo $destid[$i]; ?>" onclick="$('#dest_<?php echo $destid[$i];?>').val(1);" style="cursor: default;display: inline; font-size:14px">
                                        <span></span>Required    
                                    </label>
                                    <input name="radiodest_<?php echo $destid[$i]; ?>" id="radiodest2_<?php echo $destid[$i]; ?>" value="2" type="radio" <?php if($dstatus==2) echo 'checked="checked"'; ?>>
                                    <label class="radio <?php if($dstatus==2) echo "checked"; ?>" for="radiodest2_<?php echo $destid[$i]; ?>" onclick="$('#dest_<?php echo $destid[$i];?>').val(2);" style="cursor: default;display: inline;  font-size:14px">
                                        <span></span>Optional
                                    </label>
                                    <input name="radiodest_<?php echo $destid[$i]; ?>" id="radiodest3_<?php echo $destid[$i]; ?>" value="3" type="radio" <?php if($dstatus==3) echo 'checked="checked"'; ?>>
                                    <label class="radio <?php if($dstatus==3) echo "checked"; ?>" for="radiodest3_<?php echo $destid[$i]; ?>" onclick="$('#dest_<?php echo $destid[$i];?>').val(3);" style="cursor: default;display: inline;  font-size:14px">
                                        <span></span>Off
                                    </label>
                                </td>
                            </tr>
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
                                $tstatus = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_task_id='".$taskids[$j]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                            
                                if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                {
                                    $tstatus = $ObjDB->SelectSingleValue("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_task_id='".$taskids[$j]."' AND fld_school_id='0' AND fld_user_id='0' AND fld_flag='1'");

                                    if($tstatus=='' or $tstatus=='0' or $tstatus==NULL)
                                    {
                                        $tstatus = $taskstatus[$j];
                                    }
                                }
                                ?>
                                <input type="hidden" name="task_<?php echo $taskid[$j];?>" id="task_<?php echo $j;?>" value="<?php echo $taskids[$j];?>">
                                <tr>
                                    
                                    <td style="width: 30%;  padding-left: 60px;">
                                        <?php echo $i+1;?>.<?php echo $j+1;?> Task
	                            </td>
                                    <td style="width: 45%">
                                    	<div><?php echo $taskname[$j]; ?></div>
                                    </td>
                                    <td style="width: 25%" <?php if($tasktoggle[$j]==1 and $sessprofileid != 2 and $tstatus==3) {?>class='dim'<?php } ?>>
                                        <input name="radiotask_<?php echo $taskid[$j]; ?>" id="radiotask1_<?php echo $taskid[$j]; ?>" value="1" type="radio" <?php if($tstatus==1) echo 'checked="checked"'; ?> >
                                        <label class="radio <?php if($tstatus==1) echo "checked"; ?>" for="radiotask1_<?php echo $taskid[$j];  ?>" onclick="$('#task_<?php echo $taskid[$j]; ?>').val(1);" style="cursor: default;display: inline; font-size:14px">
                                            
                                            <span></span> Required
                                        </label>
                                        <input name="radiotask_<?php echo $taskid[$j]; ?>" id="radiotask2_<?php echo $taskid[$j]; ?>" value="2" type="radio" <?php if($tstatus==2) echo 'checked="checked"'; ?>>
                                        <label class="radio <?php if($tstatus==2) echo "checked"; ?>" for="radiotask2_<?php echo $taskid[$j]; ?>" onclick="$('#task_<?php echo $taskid[$j]; ?>').val(2);" style="cursor: default;display: inline;  font-size:14px">
                                            
                                            <span></span> Optional
                                        </label>
                                         <input name="radiotask_<?php echo $taskid[$j]; ?>" id="radiotask3_<?php echo $taskid[$j]; ?>" value="3" type="radio" <?php if($tstatus==3) echo 'checked="checked"'; ?>>
                                        <label class="radio <?php if($tstatus==3) echo "checked"; ?>" for="radiotask3_<?php echo $taskid[$j]; ?>" onclick="$('#task_<?php echo $taskid[$j]; ?>').val(3);" style="cursor: default;display: inline;  font-size:14px">
                                           
                                            <span></span> Off
                                        </label>
                                    </td>
                                </tr>
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
                                    $status = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_res_id='".$resids[$k]."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                            
                                    if($status=='' or $status=='0' or $status==NULL)
                                    {
                                        $status = $ObjDB->SelectSingleValueInt("SELECT fld_status FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_res_id='".$resids[$k]."' AND fld_school_id='0' AND fld_user_id='0' AND fld_flag='1'");

                                        if($status=='' or $status=='0' or $status==NULL)
                                        {
                                            $status = $resstatus[$k];
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="<?php echo $destid[$i];?>_<?php echo $taskid[$j];?>_<?php echo $resid[$k];?>" id="res_<?php echo $resids[$k];?>" value="<?php echo $status;?>">
                                    <tr>
                                    	
                                        <td style="width: 30%;  padding-left: 80px;">
                                            <div> <?php echo $i+1;?>.<?php echo $j+1;?>.<?php echo $k+1;?> <?php if($restype[$k]==1) echo " Instructional "; else echo " Activity ";?>Resource</div>
                                        </td>
                                        <td style="width: 45%">
                                            <div><?php echo $resname[$k]; ?></div>
                                        </td>
                                        <td style="width: 25%;" <?php if($restoggle[$k]==1 and $sessprofileid != 2 and $status==3) {?>class='dim'<?php } ?>>
                                            <input name="radiores_<?php echo $resid[$k]; ?>" id="radiores1_<?php echo $resid[$k]; ?>" value="1" type="radio" <?php if($status==1) echo 'checked="checked"'; ?> >
                                            <label class="radio <?php if($status==1) echo "checked"; ?>" for="radiores1_<?php echo $resid[$k]; ?>" onclick="$('#res_<?php echo $resid[$k];?>').val(1);" style="cursor: default;display: inline; font-size:14px">
                                                
                                                <span></span> Required
                                            </label>
                                            <input name="radiores_<?php echo $resid[$k]; ?>" id="radiores2_<?php echo $resid[$k]; ?>" value="2" type="radio" <?php if($status==2) echo 'checked="checked"'; ?>>
                                            <label class="radio <?php if($status==2) echo "checked"; ?>" for="radiores2_<?php echo $resid[$k]; ?>" onclick="$('#res_<?php echo $resid[$k];?>').val(2);" style="cursor: default;display: inline;  font-size:14px">
                                                
                                                <span></span> Optional
                                            </label>
                                             <input name="radiores_<?php echo $resid[$k]; ?>" id="radiores3_<?php echo $resid[$k]; ?>" value="3" type="radio" <?php if($status==3) echo 'checked="checked"'; ?>>
                                            <label class="radio <?php if($status==3) echo "checked"; ?>" for="radiores3_<?php echo $resid[$k]; ?>" onclick="$('#res_<?php echo $resid[$k]; ?>').val(3);" style="cursor: default;display: inline;  font-size:14px">
                                               
                                                <span></span> Off
                                            </label>
                                        </td>
                                    </tr>
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
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Status" onClick="fn_saveresstatus(<?php echo $expeditionid; ?>);" />
                </div>
                <?php 
                $chkdata = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exp_res_status WHERE fld_exp_id='".$expeditionid."' AND fld_school_id='".$schoolid."' AND fld_created_by='".$uid."' AND fld_user_id='".$indid."'");
                if($sessprofileid != 2 and $chkdata !=0){ ?>
                <div class="tRight">
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Reset to Default" onClick="fn_resetdft(<?php echo $expeditionid;?>,<?php echo $uid ?>,<?php echo $schoolid ?>,<?php echo $indid ?>);" />
            </div>
                <?php } ?>
        </div>
        </div>
        <script type="text/javascript">
        $(function(){
            $('input[name^="radiodest_"]').on( "click", function() {
                var destid = $(this).attr('name').replace("radiodest_","");
                var taskids = "radiotask"+$(this).val()+"_"+destid;
                var tasknames = "radiotask_"+destid;
                var resids = "radiores"+$(this).val()+"_"+destid;
                var resnames = "radiores_"+destid;

                $('input[name^="'+tasknames+'"]').removeAttr("checked");
                $('input[id^="'+taskids+'"]').prop("checked", true);
                $('input[name^="'+resnames+'"]').removeAttr("checked");
                $('input[id^="'+resids+'"]').prop("checked", true);
            });

            $('input[name^="radiotask_"]').on( "click", function() {

                var taskid = $(this).attr('name').replace("radiotask_","");
                var resids = "radiores"+$(this).val()+"_"+taskid;
                var resnames = "radiores_"+taskid;

                $('input[name^="'+resnames+'"]').removeAttr("checked");
                $('input[id^="'+resids+'"]').prop("checked", true);
            });

            $('input[name^="radiotask_"]').change(function() {

                var restmpid = $(this).attr('name').replace("radiotask_","").split("_");
                var destid = restmpid[0];
                var tsklen = $('input[name^="radiotask_'+destid+'"]').length/3;

                var reqcnt = $('input[id^="radiotask1_'+destid+'"]:checked').length;
                var opcnt = $('input[id^="radiotask2_'+destid+'"]:checked').length;
                var offcnt = $('input[id^="radiotask3_'+destid+'"]:checked').length;   

                if(reqcnt == tsklen){
                    $('input[id="radiodest1_'+destid+'"]').prop('checked',true);
                }

                if(reqcnt > 0){
                    $('input[id="radiodest1_'+destid+'"]').prop('checked',true);
                }

                if(reqcnt == 0 && opcnt > 0){
                    $('input[id="radiodest2_'+destid+'"]').prop('checked',true);
                }

                if(opcnt == tsklen){
                    $('input[id="radiodest2_'+destid+'"]').prop('checked',true);
                }

                if(offcnt == tsklen){
                    $('input[id="radiodest3_'+destid+'"]').prop('checked',true);
                }
            });


            $('input[name^="radiores_"]').on( "click", function() {

                var restmpid = $(this).attr('name').replace("radiores_","").split("_");
                var destid = restmpid[0];
                var taskid = restmpid[1];
                var resid = restmpid[2];
                var totreslen = $('input[name^="radiores_'+destid+'_'+taskid+'"]').length/3;

                var reqcnt = $('input[id^="radiores1_'+destid+'_'+taskid+'"]:checked').length;
                var opcnt = $('input[id^="radiores2_'+destid+'_'+taskid+'"]:checked').length;
                var offcnt = $('input[id^="radiores3_'+destid+'_'+taskid+'"]:checked').length;   

                if(reqcnt == totreslen){
                    $('input[id="radiotask1_'+destid+'_'+taskid+'"]').prop('checked',true);
                }

                if(reqcnt > 0){
                    $('input[id="radiotask1_'+destid+'_'+taskid+'"]').prop('checked',true);
                }

                if(reqcnt == 0 && opcnt > 0){
                    $('input[id="radiotask2_'+destid+'_'+taskid+'"]').prop('checked',true);
                }

                if(opcnt == totreslen){
                    $('input[id="radiotask2_'+destid+'_'+taskid+'"]').prop('checked',true);
                }

                if(offcnt == totreslen){
                    $('input[id="radiotask3_'+destid+'_'+taskid+'"]').prop('checked',true);
                }

                $('input[name^="radiotask_"]').trigger( "change" );
            });
        })
</script>
    </div>
</section>

<?php
@include("footer.php");