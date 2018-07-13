<?php
@include("sessioncheck.php");

/*
	Created By - Nrendrakumar. D
	Page - library-modules-Grade
	Description:
		Show the grades based on modules
		
	Actions Performed:
		
		
	History:
	

*/
$id = isset($method['id']) ? $method['id'] : '0';
$msg = $ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',b.fld_version) 
                                  FROM itc_module_master AS a 
								  LEFT JOIN itc_module_version_track AS b ON a.fld_id=b.fld_mod_id
								  WHERE a.fld_id='".$id."' AND b.fld_delstatus='0'");
?>
<script type="text/javascript" charset="utf-8">	
	$.getScript('library/modules/library-modules.js');
</script>
<section data-type='#library-modules' id='library-modules-grade'>
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
      
        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <form name="moduleforms" id="moduleforms">
					<?php
					$createdids = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_id) FROM `itc_user_master` WHERE fld_profile_id IN (2,3) AND fld_delstatus='0'");
					
					if($sessmasterprfid!=2 and $sessmasterprfid!=3)
					{
						$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_preassment_id AS pageid, fld_grade AS grade, fld_points AS points, 
														fld_session_id AS sessionid 
													FROM itc_module_wca_grade 
													WHERE fld_module_id='".$id."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' AND fld_schedule_type='1' 
														AND	fld_class_id='0' AND fld_schedule_id='0' 
													ORDER BY fld_type, fld_id");
					}
					
					if($qry->num_rows<=0 or $sessmasterprfid==2 or $sessmasterprfid==3)
                    {
						
						$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, fld_preassment_id AS pageid, fld_grade AS grade, fld_points AS points, 
														fld_session_id AS sessionid 
													FROM itc_module_wca_grade 
													WHERE fld_module_id='".$id."' AND fld_flag='1' AND fld_school_id='0' AND fld_user_id='0' AND fld_schedule_type='1' 
														AND	fld_class_id='0' AND fld_schedule_id='0' AND fld_created_by IN (".$createdids.")
													ORDER BY fld_type, fld_id");					
					}
					
					if($qry->num_rows<=0)
                    {
                                            	$qry = $ObjDB->QueryObject("SELECT fld_id, fld_page_title AS title, '0' AS pageid, fld_grade AS grade, fld_points AS points, fld_session_id AS sessionid 
												  FROM itc_module_grade 
												  WHERE fld_module_id='".$id."' AND fld_flag='1' AND fld_points<>'0'		
													UNION ALL 		
												  SELECT fld_id, fld_performance_name AS title, '0' AS pageid, fld_grade AS grade, fld_points_possible AS points, '0' AS sessionid 
												  FROM itc_module_performance_master 
												  WHERE fld_module_id='".$id."' AND fld_performance_name<>'Total Pages' AND fld_delstatus='0' AND fld_points_possible<>'0'
												  GROUP BY fld_performance_name 
												  ORDER BY fld_id");
					}
                    ?>
                    <table cellpadding="10" cellspacing="10" border="1" id="gradedtable">
                        <?php
                        if($qry->num_rows>0)
                        {
                            $i=1;
                            while($row=$qry->fetch_assoc())
                            {
                                extract($row);
                                $evencount = ($i % 2);
                                if($points==0)
                                {
                                    $points='';
                                }
                                if($evencount != 0)
                                {
                                    ?>
                                    <tr height="40">
                                        <td style="width:20%"><label id="wca_<?php echo $fld_id."#".$sessionid."#".$pageid?>"><?php echo $title;?></label></td>
                                        <td align="right" style="width:15%"><input type="text" maxlength="3" id="point_<?php echo $i;?>" name="point_<?php echo $i;?>" value="<?php echo $points;?>" style="width:30%" onkeyup="ChkValidChar(this.id);" /></td>
                                        <td style="width:15%">
                                            <input type="checkbox" id="grade_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if($grade==1){echo 'checked="checked"';}?> value="" />Graded
                                        </td>
                                    <?php 
                                }
                                else if($evencount == 0)
                                {
                                    ?>
                                        <td style="width:20%"><label id="wca_<?php echo $fld_id."#".$sessionid."#".$pageid?>"><?php echo $title;?></label></td>
                                        <td align="right" style="width:15%"><input type="text" maxlength="3" id="point_<?php echo $i;?>" name="point_<?php echo $i;?>" value="<?php echo $points;?>" style="width:30%" onkeyup="ChkValidChar(this.id);" /></td>
                                        <td style="width:15%">
                                            <input type="checkbox" id="grade_<?php echo $i;?>" name="<?php echo $i; ?>" <?php if($grade==1){echo 'checked="checked"';}?> value="" />Graded
                                        </td>
                                    </tr>
                                    <?php 
                                }
                                
                                if($points > 100)
                                    $maxval = 200;
                                else
                                    $maxval = 100;
                                ?>
                                <input type="hidden" id="maxpoint_<?php echo $i;?>" name="maxpoint_<?php echo $i;?>" value="<?php echo $maxval;?>" />
                                <?php
                                $i++;
                            }
                        }
						else
						{
							echo "No Records";
						}
                        ?>
                    </table>
        
                    <script type="text/javascript" language="javascript">
                        $(function(){
                            var tabindex = 1;
                            $('input,select').each(function() {
                                if (this.type != "hidden") {
                                    var $input = $(this);
                                    $input.attr("tabindex", tabindex);
                                    tabindex++;
                                }
                            });
                        });
                
                        //Function to enter only numbers in textbox
                        $("input[id^=point_]").keypress(function (e) {
                            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {					
                                return false;
                            }
                        });
                        
                        //Function to set the max & min values for the textbox
                        String.prototype.startsWith = function (str) {
                            return (this.indexOf(str) === 0);
                        }
                        function ChkValidChar(id) {
                            var newid = id.replace('point_','maxpoint_');
                            var txtbx = document.getElementById(id).value;
                            var nexttxtbx = document.getElementById(newid).value;                            
                            if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
                            {
                                document.getElementById(id).value = "";                                
                            }
                            else if(parseInt(txtbx)==0)
                            {
                                 document.getElementById(id).value = "";
                            }
                        }
                    </script>
                </form>
            </div>
            <?php if($qry->num_rows>0)
             { ?>
            <div class="row rowspacer" style="margin-top:20px;">
                <div class="tLeft" style="color:#F00;"></div>
                <div class="tRight" id="modnxtstep">
                    <input type="button" class="darkButton" id="btnstep" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Grade" onClick="savegrade(<?php echo $id; ?>);" />
                </div>
            </div>
            <?php }?>
            <input type="hidden" id="hidschid" value="<?php echo $schoolid;?>" />
        </div> 
    </div>
</section>
<?php
@include("footer.php");