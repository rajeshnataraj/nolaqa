<?php
@include("sessioncheck.php");

$expeditionid = isset($method['id']) ? $method['id'] : '0';

$expeditionname = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name,' ',b.fld_version)
												FROM itc_exp_master AS a 
												LEFT JOIN itc_exp_version_track AS b ON a.fld_id = b.fld_exp_id 
												WHERE a.fld_id='".$expeditionid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0'");
?>

<section data-type='2home' id='library-expedition-changeorder'>
    <div class='container'>
    	<!--Load the Expedition Name-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $expeditionname; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the Expedition Form-->
        <div class='row '>
            <div class='twelve columns centered insideForm'>
                <form name="exporderforms" id="exporderforms">
						<?php 
                        $qrydestinations = $ObjDB->QueryObject("SELECT fld_id AS destid, fld_dest_name AS destname, fld_order AS destorder, fld_next_order AS nextdestorder
                                                                FROM itc_exp_destination_master 
                                                                WHERE fld_exp_id='".$expeditionid."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order"); 
                        $destcnt = 0;
                        if($qrydestinations->num_rows>0) {
                            while($rowqrydestinations = $qrydestinations->fetch_object()){
                                $destid[$destcnt]=$rowqrydestinations->destid;
                                $destname[$destcnt]=$rowqrydestinations->destname;
                                $destorder[$destcnt]=$rowqrydestinations->destorder;
                                $nextdestorder[$destcnt]=$rowqrydestinations->nextdestorder;
                                $destcnt++;
                            }
                        }
                        ?>
                        <table class='table table-hover table-striped table-bordered setbordertopradius' id="expordertable" width="100%">
                        <thead class='tableHeadText'>
                            <tr>
                                <th class='centerText'>Title</th>
                                <th class='centerText'>Type</th>
                                <th class='centerText'>Next Element</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        for($i=0;$i<$destcnt;$i++)
                        {
                            $nextdest = $nextdestorder[$i]; //$i                            
                            ?>
                            	<tr>
                            	<td>
                                	<div><?php echo $destname[$i]." / Destination"; ?></div>
                                </td>
                                <td>
                                </td>
                                <td>
                                <?php if($nextdest < $destcnt) {?>
                                <div class="selectbox">
                                    <input type="hidden" name="selectnextdest_<?php echo $destid[$i];?>" id="selectnextdest_<?php echo $i;?>" value="<?php echo $nextdest;?>">
                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                        <span class="selectbox-option input-medium" id="dest_<?php echo $i;?>" data-option="<?php echo $nextdest;?>"><?php echo $destname[$nextdest];?></span>
                                        <b class="caret1"></b>
                                    </a>
                                    <div class="selectbox-options" >
                                        <input type="text" class="selectbox-filter" placeholder="Search Destination">			    
                                        <ul role="options">
                                            <?php for($l=0;$l<$destcnt;$l++) {?>
                                            <li><a tabindex="-1" href="#" id="nextdest_<?php echo $i;?>" data-option="<?php echo $l;?>" onclick="fn_changeorder('nextdest_',<?php echo $i;?>,<?php echo $l;?>,<?php echo $destcnt;?>,'dest')" style="display:<?php if($l < $nextdest) { ?>none<?php }?>"><?php echo $destname[$l];?></a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            	<?php }?>
                                </td>
                            	</tr>
                            <?php
                            $qrytasks = $ObjDB->QueryObject("SELECT fld_id AS taskid, fld_task_name AS taskname, fld_order AS taskorder, fld_next_order AS nexttaskorder
                                                                FROM itc_exp_task_master 
                                                                WHERE fld_dest_id='".$destid[$i]."' AND fld_flag='1' AND fld_delstatus='0' ORDER BY fld_order"); 
                            $taskcnt = 0;
                            if($qrytasks->num_rows>0) {
                                while($rowqrytasks = $qrytasks->fetch_object()){
                                    $taskid[$taskcnt]=$rowqrytasks->taskid;
                                    $taskname[$taskcnt]=$rowqrytasks->taskname;
                                    $taskorder[$taskcnt]=$rowqrytasks->taskorder;
                                    $nexttaskorder[$taskcnt]=$rowqrytasks->nexttaskorder;
                                    $taskcnt++;
                                }
                            }
                            
                            for($j=0;$j<$taskcnt;$j++)
                            {
                                $nexttask = $nexttaskorder[$j]; //$j                                
                                ?>
                                	<tr>
                                    <td>
                                    	<div><?php echo $taskname[$j]." / Task"; ?></div>
                                    </td>
                                    <td>
	                                </td>
                                    <td>
                                    <?php if($nexttask < $taskcnt) {?>
                                    <div class="selectbox">
                                        <input type="hidden" name="selectnexttask_<?php echo $taskid[$j];?>" id="selectnexttask_<?php echo $i."_".$j;?>" value="<?php echo $nexttask;?>">
                                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" id="task_<?php echo $j;?>" data-option="<?php echo $nexttask;?>"><?php echo $taskname[$nexttask];?></span>
                                            <b class="caret1"></b>
                                        </a>
                                        <div class="selectbox-options" >
                                            <input type="text" class="selectbox-filter" placeholder="Search Task">			    
                                            <ul role="options">
                                                <?php for($l=0;$l<$taskcnt;$l++) {?>
                                                <li><a tabindex="-1" href="#" id="nexttask_<?php echo $i."_".$j;?>" data-option="<?php echo $l;?>" onclick="fn_changeorder('nexttask_<?php echo $i."_"; ?>',<?php echo $j;?>,<?php echo $l;?>,<?php echo $taskcnt;?>,'task')" style="display:<?php if($l < $nexttask) { ?>none<?php }?>"><?php echo $taskname[$l];?></a></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php }?>
                                    </td>
                                    </tr>
                                <?php
                                $qryres = $ObjDB->QueryObject("SELECT fld_id AS resid, fld_res_name AS resname, fld_order AS resorder, fld_next_order AS nextresorder
                                                                FROM itc_exp_resource_master 
                                                                WHERE fld_task_id='".$taskid[$j]."' AND fld_flag='1' AND fld_delstatus='0' AND fld_typeof_res='2' 
                                                                ORDER BY fld_order"); 
                                $rescnt = 0;
                                if($qryres->num_rows>0) {
                                    while($rowqryres = $qryres->fetch_object()){
                                        $resid[$rescnt]=$rowqryres->resid;
                                        $resname[$rescnt]=$rowqryres->resname;
                                        $resorder[$rescnt]=$rowqryres->resorder;
                                        $nextresorder[$rescnt]=$rowqryres->nextresorder;
                                        $rescnt++;
                                    }
                                }
                                
                                for($k=0;$k<$rescnt;$k++)
                                {
                                    $nextres = $nextresorder[$k]; //$k                                    
                                    ?>
                                    	<tr>
                                    	<td>
                                        	<div><?php echo $resname[$k]." / Resource"; ?></div>
                                        </td>
                                        <td>
                                            <div>Activity</div>
                                        </td>
                                        <td>
                                        <?php if($k < $rescnt-1) {?>
                                        <div class="selectbox">
                                            <input type="hidden" name="selectnextres_<?php echo $resid[$k];?>" id="selectnextres_<?php echo $i."_".$j."_".$k;?>" value="<?php echo $nextres;?>">
                                            <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                <span class="selectbox-option input-medium" id="res_<?php echo $k;?>" data-option="<?php echo $nextres;?>"><?php echo $resname[$k+1];?></span>
                                                <b class="caret1"></b>
                                            </a>
                                            <div class="selectbox-options" >
                                                <input type="text" class="selectbox-filter" placeholder="Search Resource">		    
                                                <ul role="options">
                                                    <?php for($l=0;$l<$rescnt;$l++) {?>
                                                    <li><a tabindex="-1" href="#" id="nextres_<?php echo $i."_".$j."_".$k;?>" data-option="<?php echo $l;?>" onclick="fn_changeorder('nextres_<?php echo $i."_".$j."_"; ?>',<?php echo $k;?>,<?php echo $l;?>,<?php echo $rescnt;?>,'res')" style="display:<?php if($l < $nextres) { ?>none<?php }?>"><?php echo $resname[$l];?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php }?>
                                        </td>
                                        </tr>
                                    <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </form>
            </div>
        
            <div class="row rowspacer" style="margin-top:20px;">
                <div class="tLeft" style="color:#F00;"></div>
                <div class="tRight">
                    <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Order" onClick="fn_saveorder(<?php echo $expeditionid; ?>);" />
                </div>
            </div>
        </div>
    </div>
</section>

<?php
@include("footer.php");