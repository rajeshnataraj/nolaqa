<?php
@include("sessioncheck.php");

$date=date("Y-m-d H:i:s");

$id = isset($method['id']) ? $method['id'] : '0';
$id = explode(",",$id);
print_r($id);
$type=$id[0];

if($type =='1' || $type =='4' ){
    $msg = "Grade Class";
}
else if($type =='2'){
    $msg = "Grade Currently Logged in";
}
else if($type =='9'){
    $msg = "Student Name";
}
else
{
    $msg = "Grade by Assignment";
}

$clasid = '';
$assingnmentid = '';
?>

<script language="javascript" type="text/javascript">
    $.getScript("library/rubric/library-rubric-gradestudentrubric.js");
</script>
<section data-type='2home' id='library-rubric-gradestudentrubric'>
 <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'> 
            <form name="rubricforms" id="rubricforms">
                <?php if($type !='9'){ ?>
                <div class="row">
                   <?php } else { ?>
                   <div class="row" style="display: none;">
                   <?php } ?>
                    <?php if($type =='1' || $type =='2' || $type =='4'){ ?>
                <!--Shows Class code start here-->
                    <div class='six columns'> Class
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="<?php echo $clasid; ?>" onchange="fn_showsch(this.value,<?php echo $type; ?>);" />
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                   <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Class</span> 
                                   <b class="caret1"></b>
                               </a>
                               <div class="selectbox-options">
                                   <input type="text" class="selectbox-filter" placeholder="Search Class">
                                   <ul role="options" style="width:100%">
                                       <?php
                                       if($type == '4'){

                                           $qry = $ObjDB->QueryObject("SELECT w.* FROM ((SELECT a.fld_class_id AS classid, b.fld_class_name AS classname,18 AS exptype
                                                                        FROM itc_class_indasmission_master AS a
                                                                        LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                        WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."' 
                                                                        AND b.fld_delstatus = '0'  AND b.fld_flag = '1' )
                                                                    UNION ALL
                                                                        (SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 19 AS exptype
                                                                        FROM itc_class_master AS a 
                                                                        LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON b.fld_class_id=a.fld_id   
                                                                        WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_created_by='".$uid."'
                                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1'))AS w 
                                                                        group by w.classid ORDER BY w.classname");
                                       }else{
                                           $qry = $ObjDB->QueryObject("SELECT w.* FROM ((SELECT b.fld_class_id AS classid, a.fld_class_name AS classname,15 AS exptype
                                                                        FROM itc_class_master AS a 
                                                                        LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_id=b.fld_class_id  
                                                                        WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND b.fld_createdby='".$uid."' 
                                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1' )
                                                                           UNION ALL
                                                                        (SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 17 AS exptype
                                                                        FROM itc_class_master AS a 
                                                                        LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON b.fld_class_id=a.fld_id   
                                                                        WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_created_by='".$uid."'
                                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1')
                                                                        UNION ALL
                                                                        (SELECT b.fld_class_id AS classid, a.fld_class_name AS classname, 20 AS exptype
                                                                        FROM itc_class_master AS a 
                                                                        LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON b.fld_class_id=a.fld_id   
                                                                        WHERE b.fld_delstatus='0' AND b.fld_flag='1' AND a.fld_created_by='".$uid."'
                                                                        AND a.fld_delstatus = '0'  AND a.fld_flag = '1')
                                                                        )AS w 
                                                                        group by w.classid ORDER BY w.classname");

                                       }
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                    ?>
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $classid;?>"><?php echo $classname; ?></a></li>
                                                    <?php
                                                }
                                            } ?>      
                                   </ul>
                               </div>
                            </div>
                        </dl>
                    </div> <!--Shows Class code Ends here-->
                    <?php } else { ?>
                   
                   <div class='six columns'> Assignment
                        <dl class='field row'>
                            <div class="selectbox">
                                <input type="hidden" name="classid" id="classid" value="<?php echo $assingnmentid; ?>" onchange="fn_showexp(this.value,<?php echo $type; ?>);" />
                                <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                   <span class="selectbox-option input-medium" data-option="" style="width:97%">Select Assignment</span> 
                                   <b class="caret1"></b>
                               </a>
                               <div class="selectbox-options">
                                   <input type="text" class="selectbox-filter" placeholder="Search Assignment">
                                   <ul role="options" style="width:100%">
                                       <?php
                                       
                                        $qry = $ObjDB->QueryObject("SELECT w.* FROM (
                                                                        (SELECT a.fld_class_id AS classid, a.fld_schedule_name AS schedulename,15 AS exptype,
                                                                        a.fld_id AS assingnmentid FROM itc_class_indasexpedition_master AS a
                                                                        LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                        WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."'
                                                                        AND b.fld_delstatus = '0'  AND b.fld_flag = '1' group by assingnmentid) 
                                                                        UNION ALL
                                                                        (SELECT a.fld_class_id AS classid, a.fld_schedule_name AS schedulename,17 AS exptype,
                                                                        a.fld_id AS assingnmentid FROM itc_class_rotation_expschedule_mastertemp AS a
                                                                        LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                        WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."'
                                                                         AND b.fld_delstatus = '0'  AND b.fld_flag = '1' group by assingnmentid)
                                                                         UNION ALL
                                                                        (SELECT a.fld_class_id AS classid, a.fld_schedule_name AS schedulename,20 AS exptype,
                                                                        a.fld_id AS assingnmentid FROM itc_class_rotation_modexpschedule_mastertemp AS a
                                                                        LEFT JOIN itc_class_master AS b ON b.fld_id=a.fld_class_id
                                                                        WHERE a.fld_delstatus='0' AND a.fld_flag='1' AND a.fld_createdby='".$uid."'
                                                                         AND b.fld_delstatus = '0'  AND b.fld_flag = '1' group by assingnmentid )
                                                                        )AS w  ORDER BY w.schedulename
                                                                        ");
                                        if($qry->num_rows>0){
                                            while($row = $qry->fetch_assoc())
                                            {
                                                extract($row);
                                                    ?> 
                                                        <li><a tabindex="-1" href="#" data-option="<?php echo $classid."~".$assingnmentid."~".$exptype;?>"><?php echo $schedulename; ?></a></li>
                                                    <?php
                                                }
                                            }
                                          ?>      
                                   </ul>
                               </div>
                            </div>
                        </dl>
                    </div>
                   
                    <?php } ?>
                <!--Show expedition-->
                    <div class='six columns'> 
                        <div id="showsch" style="display:none">

                        </div>
                    </div>
                <!--Show expedition-->
            	</div>
                <!--Show Rubric-->
                    <div class="row rowspacer">
                        <div class='six columns'> 
                            <div id="showexp" style="display:none">

                            </div>
                        </div>
                         <div class='six columns'> 
                            <div id="showrub" style="display:none">

                            </div>
                        </div>
                    </div>
                <!--Show Rubric-->
                
                <!--Shows Student -->
                    <div class="row rowspacer">
                        <div class='twelve columns'> 
                            <div id="studentdiv" style="display:none">

                            </div>
                        </div>
                    </div>
                <!--Shows Student -->
                
                <!--Shows Rubric Statement -->
                    <div class="row rowspacer">
                        <div id="rubricstmt" style="display:none">

                        </div>
                    </div>
                <!--Shows Rubric Statement -->
       
                  </form>
            </div>
        </div>
    </div>
    
</section>
<?php
	@include("footer.php");