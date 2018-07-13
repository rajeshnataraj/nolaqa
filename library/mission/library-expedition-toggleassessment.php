<?php
@include("sessioncheck.php");

$expeditionid = isset($method['id']) ? $method['id'] : '0';

$expeditionname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
?>

<section data-type='2home' id='library-expedition-toggleassessment'>
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
                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="expordertable" width="100%">
                            <thead class='tableHeadText'>
                                <tr>
                                    <th class='centerText' style="width: 25%">Title</th>
                                    <th class='centerText' style="width: 35%">Item Name</th>
                                    <th class='centerText' style="width: 15%">Pre/Post</th>
                                    <th class='centerText' style="width: 25%">Toggle</th>
                                </tr>
                            </thead>
                        </table>
                        
                        <div style="max-height:500px;width:100%" id="tablecontents15"  >
                        <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove'>
                        <tbody>
                        <?php
                        $expedition=$ObjDB->QueryObject("select a.fld_status as status,a.fld_exptestid as testid,b.fld_test_name as testname,c.fld_exp_name as expname,a.fld_tprepost as prepost from itc_exptest_toogle as a
                                     left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                     left join itc_exp_master as c on c.fld_id=a.fld_texpid
                                     where a.fld_texpid='".$expeditionid."' and a.fld_tdestid='0' and a.fld_flag='1' and b.fld_delstatus='0' and c.fld_delstatus='0' and a.fld_created_by='".$uid."' order by a.fld_tprepost ASC");
                        if($expedition->num_rows>0)
                        {
                            while($rowexp=$expedition->fetch_assoc())
                            {
                                extract($rowexp);
                        ?>
                            <input type="hidden" name="exp_<?php echo $expeditionid;?>" id="exp_<?php echo $i;?>" value="<?php echo $expeditionid;?>">
                            <tr>
                                <td style="width:25%">
                                    <?php echo $testname;?>
                                </td>
                                <td style="width:35%">
                                    <?php echo $expname." / Exp";?>
                                </td>
                                <td style="width: 15%" class='centerText'>
                                    <?php if($prepost==1){ echo "Pre Test"; }else{ echo "Post Test"; }?>
                                </td>
                            	<td style="width: 25%">
                                    <input name="radioexp_<?php echo $testid."_".$expeditionid; ?>" id="radioexp1_<?php echo $expeditionid; ?>" value="1" type="radio" class="<?php echo "radio1_".$testid."_".$expeditionid; if($prepost==1){ echo " preexp1_".$expeditionid;}else{ echo " posexp1_".$expeditionid; }?>" <?php if($status==1) echo 'checked="checked"'; ?>>
                                    <label class="radio" for="radioexp1_<?php echo $expeditionid; ?>" onclick="$('#exp_<?php echo $expeditionid;?>').val(1);" style="cursor: default;display: inline; font-size:14px">
                                        <span></span> Required
                                    </label>
                                    <input name="radioexp_<?php echo $testid."_".$expeditionid; ?>" id="radioexp2_<?php echo $expeditionid; ?>" value="2" type="radio" class="<?php echo "radio2_".$testid."_".$expeditionid; if($prepost==1){ echo " preexp2_".$expeditionid;}else{ echo " posexp2_".$expeditionid; }?>" <?php if($status==2) echo 'checked="checked"'; ?>>
                                    <label class="radio" for="radioexp2_<?php echo $expeditionid; ?>" onclick="$('#exp_<?php echo $expeditionid;?>').val(2);" style="cursor: default;display: inline;  font-size:14px">
                                        
                                        <span></span> Optional
                                    </label>
                                    <input name="radioexp_<?php echo $testid."_".$expeditionid; ?>" id="radioexp3_<?php echo $expeditionid; ?>" value="3" type="radio" class="<?php echo "radio3_".$testid."_".$expeditionid; if($prepost==1){ echo " preexp3_".$expeditionid;}else{ echo " posexp3_".$expeditionid; }?>" <?php if($status==3) echo 'checked="checked"'; ?>>
                                    <label class="radio" for="radioexp3_<?php echo $expeditionid; ?>" onclick="$('#exp_<?php echo $expeditionid;?>').val(3);" style="cursor: default;display: inline;  font-size:14px">
                                        
                                        <span></span> Off
                                    </label>
                                </td>
                            </tr>
                            <?php
                                    } // exp while end
                                 } // exp if end end
                                 
                                 $destinationunic=$ObjDB->QueryObject("SELECT fld_tdestid as destidu FROM itc_exptest_toogle where fld_texpid='".$expeditionid."' and fld_tdestid<>'0' and fld_flag='1' and fld_created_by='".$uid."' group by fld_tdestid");
                                 
                                 if($destinationunic->num_rows>0)
                                 {
                                    while($rowdetsunic=$destinationunic->fetch_assoc())
                                    {
                                        extract($rowdetsunic);
                                        
                                        $destination=$ObjDB->QueryObject("select a.fld_status as status,a.fld_exptestid as testid,a.fld_tdestid as destid,b.fld_test_name as testname,c.fld_dest_name as destname,a.fld_tprepost as prepost from itc_exptest_toogle as a
                                                                   left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                                   left join itc_exp_destination_master as c on c.fld_id=a.fld_tdestid
                                                                   where a.fld_texpid='".$expeditionid."' and a.fld_tdestid='".$destidu."' and a.fld_ttaskid='0' and a.fld_flag='1' and b.fld_delstatus='0' and a.fld_created_by='".$uid."' and c.fld_delstatus='0' order by a.fld_tprepost ASC");
                                 
                                        if($destination->num_rows>0)
                                        {
                                           while($rowdest=$destination->fetch_assoc())
                                           {
                                               extract($rowdest);
                                               
                                               $flag='';
                             ?>
                            
                                        <input type="hidden" name="dest_<?php echo $destid;?>" id="dest_<?php echo $i;?>" value="<?php echo $destid;?>">
                                        <tr>
                                            <td style="width:25%">
                                                <?php echo $testname;?>
                                            </td>
                                            <td style="width:35%">
                                                <?php echo $destname." / Dest";?>
                                            </td>
                                            <td style="width: 15%" class='centerText'>
                                                <?php if($prepost==1){ echo "Pre Test"; }else{ echo "Post Test"; }?>
                                            </td>
                                            <td style="width: 25%">
                                                <input name="radiodest_<?php echo $testid."_".$destid; ?>" id="radiodest1_<?php echo $destid; ?>" class="<?php echo "radio1_".$testid."_".$destid; if($prepost==1){ echo " predest1_".$destid;}else{ echo " posdest1_".$destid; }?>" value="1" type="radio" <?php if($status==1) echo 'checked="checked"'; ?>>
                                                <label class="radio" for="radiodest1_<?php echo $destid; ?>" onclick="$('#dest_<?php echo $destid;?>').val(1);" style="cursor: default;display: inline; font-size:14px">

                                                    <span></span> Required
                                                </label>
                                                <input name="radiodest_<?php echo $testid."_".$destid; ?>" id="radiodest2_<?php echo $destid; ?>" class="<?php echo "radio2_".$testid."_".$destid; if($prepost==1){ echo " predest2_".$destid;}else{ echo " posdest2_".$destid; }?>" value="2" type="radio" <?php if($status==2) echo 'checked="checked"'; ?>>
                                                <label class="radio>" for="radiodest2_<?php echo $destid; ?>" onclick="$('#dest_<?php echo $destid;?>').val(2);" style="cursor: default;display: inline;  font-size:14px">

                                                    <span></span> Optional
                                                </label>
                                                <input name="radiodest_<?php echo $testid."_".$destid; ?>" id="radiodest3_<?php echo $destid; ?>" class="<?php echo "radio3_".$testid."_".$destid; if($prepost==1){ echo " predest3_".$destid;}else{ echo " posdest3_".$destid; }?>" value="3" type="radio" <?php if($status==3) echo 'checked="checked"'; ?>>
                                                <label class="radio" for="radiodest3_<?php echo $destid; ?>" onclick="$('#dest_<?php echo $destid;?>').val(3);" style="cursor: default;display: inline;  font-size:14px">

                                                    <span></span> Off
                                                </label>
                                            </td>
                                        </tr>
                                    
                             <?php
                                           } // Child destination while end
                                        } // Child destination if end
                                        
                                        $taskunic=$ObjDB->QueryObject("SELECT fld_ttaskid as taskidu FROM itc_exptest_toogle where fld_texpid='".$expeditionid."' and fld_tdestid='".$destidu."' and fld_ttaskid<>'0' and fld_flag='1' and fld_created_by='".$uid."' group by fld_ttaskid");
                                        
                                        if($taskunic->num_rows>0)
                                        {
                                            while($rowtaskunic=$taskunic->fetch_assoc())
                                            {
                                                extract($rowtaskunic);
                                                
                                                $task=$ObjDB->QueryObject("select a.fld_status as status,a.fld_exptestid as testid,a.fld_ttaskid as taskid,b.fld_test_name as testname,c.fld_task_name as taskname,a.fld_tprepost as prepost from itc_exptest_toogle as a
                                                                   left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                                   left join itc_exp_task_master as c on c.fld_id=a.fld_ttaskid
                                                                   where a.fld_texpid='".$expeditionid."' and a.fld_tdestid='".$destidu."' and a.fld_ttaskid='".$taskidu."' and a.fld_tresid='0' and a.fld_flag='1' and b.fld_delstatus='0' and c.fld_delstatus='0' and a.fld_created_by='".$uid."' order by a.fld_tprepost ASC");
                                    
                                                if($task->num_rows>0)
                                                {
                                                    while($rowtask=$task->fetch_assoc())
                                                    {
                                                        extract($rowtask);
                                      ?>
                                                    <input type="hidden" name="task_<?php echo $destidu."_".$taskid;?>" id="task_<?php echo $j;?>" value="<?php echo $taskid;?>">
                                                        <tr>
                                                            <td style="width:25%">
                                                                <?php echo $testname;?>
                                                            </td>
                                                            <td style="width:35%">
                                                                <?php echo $taskname." / Task";?>
                                                            </td>
                                                            <td style="width: 15%" class='centerText'>
                                                                <?php if($prepost==1){ echo "Pre Test"; }else{ echo "Post Test"; }?>
                                                            </td>
                                                            <td style="width: 25%">
                                                                <input name="radiotask_<?php echo $testid."_".$destidu."_".$taskid; ?>" id="radiotask1_<?php echo $destidu."_".$taskid; ?>" value="1" type="radio" class="<?php echo "radio1_".$testid."_".$taskid; if($prepost==1){ echo " pretask1_".$taskid;}else{ echo " postask1_".$taskid; }?>" <?php if($status==1) echo 'checked="checked"'; ?>>
                                                                <label class="radio" for="radiotask1_<?php echo $destidu."-".$taskid;  ?>" onclick="$('#task_<?php echo $destidu."-".$taskid; ?>').val(1);" style="cursor: default;display: inline; font-size:14px">

                                                                    <span></span> Required
                                                                </label>
                                                                <input name="radiotask_<?php echo $testid."_".$destidu."_".$taskid; ?>" id="radiotask2_<?php echo $destidu."_".$taskid; ?>" value="2" type="radio" class="<?php echo "radio2_".$testid."_".$taskid; if($prepost==1){ echo " pretask2_".$taskid;}else{ echo " postask2_".$taskid; }?>" <?php if($status==2) echo 'checked="checked"'; ?>>
                                                                <label class="radio" for="radiotask2_<?php echo $destidu."-".$taskid; ?>" onclick="$('#task_<?php echo $destidu."-".$taskid; ?>').val(2);" style="cursor: default;display: inline;  font-size:14px">

                                                                    <span></span> Optional
                                                                </label>
                                                                 <input name="radiotask_<?php echo $testid."_".$destidu."_".$taskid; ?>" id="radiotask3_<?php echo $destidu."_".$taskid; ?>" value="3" type="radio" class="<?php echo "radio3_".$testid."_".$taskid; if($prepost==1){ echo " pretask3_".$taskid;}else{ echo " postask3_".$taskid; }?>" <?php if($status==3) echo 'checked="checked"'; ?>>
                                                                <label class="radio" for="radiotask3_<?php echo $destidu."_".$taskid; ?>" onclick="$('#task_<?php echo $destidu."_".$taskid; ?>').val(3);" style="cursor: default;display: inline;  font-size:14px">

                                                                    <span></span> Off
                                                                </label>
                                                            </td>
                                                        </tr>
                                        
                                      <?php
                                                    } // sub task while end
                                                } // sub task if end
                                                
                                                $resourceunic=$ObjDB->QueryObject("SELECT fld_tresid as resourceidu FROM itc_exptest_toogle where fld_texpid='".$expeditionid."' and fld_tdestid='".$destidu."' and fld_ttaskid='".$taskidu."' and fld_tresid<>'0' and fld_flag='1' and fld_created_by='".$uid."' group by fld_tresid");
                                                
                                                if($resourceunic->num_rows>0)
                                                {
                                                    while($rowresourceunicunic=$resourceunic->fetch_assoc())
                                                    {
                                                        extract($rowresourceunicunic);
                                                        
                                                        $resource=$ObjDB->QueryObject("select a.fld_status as status,a.fld_exptestid as testid,a.fld_tresid as resourceid,b.fld_test_name as testname,c.fld_res_name as resourcename,a.fld_tprepost as prepost from itc_exptest_toogle as a
                                                                   left join itc_test_master as b on b.fld_id=a.fld_exptestid
                                                                   left join itc_exp_resource_master as c on c.fld_id=a.fld_tresid
                                                                   where a.fld_texpid='".$expeditionid."' and a.fld_tdestid='".$destidu."' and a.fld_ttaskid='".$taskidu."' and a.fld_tresid='".$resourceidu."' and a.fld_flag='1' and b.fld_delstatus='0' and c.fld_delstatus='0' and a.fld_created_by='".$uid."' order by a.fld_tprepost ASC");
                                        
                                                            if($resource->num_rows>0)
                                                            {
                                                                while($rowresource=$resource->fetch_assoc())
                                                                {
                                                                    extract($rowresource);
                                                 ?>
                                                            <input type="hidden" name="<?php echo $destid;?>_<?php echo $taskid;?>_<?php echo $resourceid;?>" id="res_<?php echo $resourceid;?>" value="<?php echo $resourceid;?>">
                                                            <tr>
                                                                <td style="width:25%">
                                                                    <?php echo $testname;?>
                                                                </td>
                                                            <td style="width:35%">
                                                                <?php echo $resourcename." / Res";?>
                                                            </td>
                                                            <td style="width: 15%" class='centerText'>
                                                                <?php if($prepost==1){ echo "Pre Test"; }else{ echo "Post Test"; }?>
                                                            </td>
                                                                <td style="width: 25%;">
                                                                    <input name="radiores_<?php echo $testid."_".$resourceid."_".$taskid."_".$destid; ?>" id="radiores1_<?php echo $resourceid; ?>" value="1" type="radio" class="<?php echo "radio1_".$testid."_".$resourceid; if($prepost==1){ echo " preresource1_".$resourceid;}else{ echo " posresource1_".$resourceid; }?>" <?php if($status==1) echo 'checked="checked"'; ?>>
                                                                    <label class="radio" for="radiores1_<?php echo $resourceid; ?>" onclick="$('#res_<?php echo $resourceid;?>').val(1);" style="cursor: default;display: inline; font-size:14px">

                                                                        <span></span> Required
                                                                    </label>
                                                                    <input name="radiores_<?php echo $testid."_".$resourceid."_".$taskid."_".$destid; ?>" id="radiores2_<?php echo $resourceid; ?>" value="2" type="radio" class="<?php echo "radio2_".$testid."_".$resourceid; if($prepost==1){ echo " preresource2_".$resourceid;}else{ echo " postask1_".$resourceid; }?>" <?php if($status==2) echo 'checked="checked"'; ?>>
                                                                    <label class="radio" for="radiores2_<?php echo $resourceid; ?>" onclick="$('#res_<?php echo $resourceid;?>').val(2);" style="cursor: default;display: inline;  font-size:14px">

                                                                        <span></span> Optional
                                                                    </label>
                                                                     <input name="radiores_<?php echo $testid."_".$resourceid."_".$taskid."_".$destid; ?>" id="radiores3_<?php echo $resourceid; ?>" value="3" type="radio" class="<?php echo "radio3_".$testid."_".$resourceid; if($prepost==1){ echo " preresource3_".$resourceid;}else{ echo " posresource3_".$resourceid; }?>" <?php if($status==3) echo 'checked="checked"'; ?>>
                                                                    <label class="radio" for="radiores3_<?php echo $resourceid; ?>" onclick="$('#res_<?php echo $resourceid; ?>').val(3);" style="cursor: default;display: inline;  font-size:14px">

                                                                        <span></span> Off
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        
                                                <?php
                                                
                                                               } // Sub res while end
                                                            } // sub res if end
                                                            
                                                    } // parent res while end
                                                } // parent res if end
                                             
                                            } // parent task while end
                                         } // Parent task if end
                                         
                                    } // parent dest while end
                                 } // parent if end 
                               ?>
                        </tbody>
                    </table>
                            
                        </div>
                </form>
            </div>
        
            <div class="row rowspacer" style="margin-top:20px;">
                <div class="tLeft" style="color:#F00;"></div>
                <div class="tRight">
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Status" onClick="fn_savetoggleassesment(<?php echo $expeditionid; ?>);" />
                </div>
            </div>
        </div>
        <script type="text/javascript">
            
        </script>
    </div>
</section>

<script type="text/javascript">
            $(function(){
                $('input[name^="radioexp_"]').on( "click", function() {
                   
                    var classname= $(this).attr('class');
                    
                    var splitclass=classname.split(" ");
                    
                    var classname2=splitclass[1].split("_");
                    
                    var checkedname='';
                    
                    if(classname2[0]=="preexp1" || classname2[0]=="preexp2" || classname2[0]=="preexp3")
                    {
                        
                        $('.preexp3_'+classname2[1]).prop('checked',true);
                        
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                    if(classname2[0]=="posexp1" || classname2[0]=="posexp2" || classname2[0]=="posexp3")
                    {
                        $('.posexp3_'+classname2[1]).prop('checked',true);
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                });
                
                
                $('input[name^="radiodest_"]').on( "click", function() {
                    
                    var classname= $(this).attr('class');
                    
                    var splitclass=classname.split(" ");
                    
                    var classname2=splitclass[1].split("_");
                    
                    var checkedname='';
                    
                    if(classname2[0]=="predest1" || classname2[0]=="predest2" || classname2[0]=="predest3")
                    {
                        
                        $('.predest3_'+classname2[1]).prop('checked',true);
                        
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                    if(classname2[0]=="posdest1" || classname2[0]=="posdest2" || classname2[0]=="posdest3")
                    {
                        $('.posdest3_'+classname2[1]).prop('checked',true);
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                });
                
                $('input[name^="radiotask_"]').on( "click", function() {
                    
                    var classname= $(this).attr('class');
                    
                    var splitclass=classname.split(" ");
                    
                    var classname2=splitclass[1].split("_");
                    
                    var checkedname='';
                    
                    if(classname2[0]=="pretask1" || classname2[0]=="pretask2" || classname2[0]=="pretask3")
                    {
                        
                        $('.pretask3_'+classname2[1]).prop('checked',true);
                        
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                    if(classname2[0]=="postask1" || classname2[0]=="postask2" || classname2[0]=="postask3")
                    {
                        $('.postask3_'+classname2[1]).prop('checked',true);
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                });
                
                $('input[name^="radioresource_"]').on( "click", function() {
                   
                    var classname= $(this).attr('class');
                    
                    var splitclass=classname.split(" ");
                    
                    var classname2=splitclass[1].split("_");
                    
                    var checkedname='';
                    
                    if(classname2[0]=="preresource1" || classname2[0]=="preresource2" || classname2[0]=="preresource3")
                    {
                        
                        $('.preresource3_'+classname2[1]).prop('checked',true);
                        
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                    if(classname2[0]=="posresource1" || classname2[0]=="posresource2" || classname2[0]=="posresource3")
                    {
                        $('.posresource3_'+classname2[1]).prop('checked',true);
                        $('.'+splitclass[0]).prop('checked',true);
                    }
                    
                });
            })
</script>

<?php
@include("footer.php");


