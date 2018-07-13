<?php
	@include("sessioncheck.php");
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';

/*--- Load pd Listbox ---*/
if($oper=="loadpdcontent" and $oper != " ")
{
        $sid = isset($method['sid']) ? $method['sid'] : '0';		
        $licenseid = isset($method['lid']) ? $method['lid'] : '0';
        $sflag = isset($method['flag']) ? $method['flag'] : '0';

        ?>
        <script type="text/javascript" language="javascript">
                $(function() {
                        $('#testrailvisible11').slimscroll({
                                width: '410px',
                                height:'366px',
                                size: '7px',
                                alwaysVisible: true,
                                railVisible: true,
                                allowPageScroll: false,
                                railColor: '#F4F4F4',
                                opacity: 1,
                                color: '#d9d9d9',
                                 wheelStep: 1

                        });
                        $('#testrailvisible12').slimscroll({
                                width: '410px',
                                height:'366px',
                                size: '7px',
                                alwaysVisible: true,
                                railVisible: true,
                                allowPageScroll: false,
                                railColor: '#F4F4F4',
                                opacity: 1,
                                color: '#d9d9d9',
                                 wheelStep: 1
                        });
                        $("#list14").sortable({
                                connectWith: ".droptrue3",
                                dropOnEmpty: true,
                                items: "div[class='draglinkleft']",
                                receive: function(event, ui) { 
                                        $("div[class=draglinkright]").each(function(){ 
                                                if($(this).parent().attr('id')=='list14'){
                                                        fn_movealllistitems('list14','list15',$(this).children(":first").attr('id'));
                                                }
                                        });						
                                }
                        });

                        $( "#list15" ).sortable({
                                connectWith: ".droptrue3",
                                dropOnEmpty: true,
                                receive: function(event, ui) { 
                                        $("div[class=draglinkleft]").each(function(){ 
                                                if($(this).parent().attr('id')=='list15'){
                                                        fn_movealllistitems('list14','list15',$(this).children(":first").attr('id'));
                                                }
                                        });						
                                }
                        });
                        
                });  
        </script>

        <!--Start of unit drag and drop list id3&4 and testrailvisible11 &4 and droptrue3-->
        <div id="units"></div>
        <div class='row'>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <div class="dragtitle">Courses available</div>
                            <div class="draglinkleftSearch" id="s_list14" >
                               <dl class='field row'>
                                    <dt class='text'>
                                        <input placeholder='Search' type='text' id="list_3_search" name="list_3_search" onKeyUp="search_list(this,'#list14');" />
                                    </dt>
                                </dl>
                            </div>
                            <div class="dragWell" id="testrailvisible11" >
                                <div id="list14" class="dragleftinner droptrue3">
                                   <?php 						
                                    $qryunit= $ObjDB->QueryObject("SELECT a.fld_id as courseid, a.fld_course_name as coursename 
                                                                        FROM itc_course_master AS a LEFT JOIN itc_license_course_mapping AS b ON a.fld_id = b.fld_course_id 
                                                                        WHERE b.fld_license_id='".$licenseid."' AND b.fld_flag='1' AND a.fld_id 
                                                                        NOT IN (SELECT fld_course_id FROM itc_class_pdschedule_course_mapping WHERE fld_pdschedule_id='".$sid."' 
                                                                        AND fld_flag='1' AND fld_license_id='".$licenseid."') 
                                                                        GROUP BY a.fld_id");								

                                    if($qryunit->num_rows > 0){
                                            while($rowsqryunit = $qryunit->fetch_assoc()){
                                                    extract($rowsqryunit);
                                    ?>
                                <div class="draglinkleft" id="list14_<?php echo $courseid; ?>" >
                                        <div class="dragItemLable" id="<?php echo $courseid; ?>"><?php echo $coursename; ?></div>
                                        <div class="clickable" id="clck_<?php echo $courseid; ?>" onclick="fn_movealllistitems('list14','list15',<?php echo $courseid; ?>,'0','<?php echo $licenseid; ?>');"></div>
                                </div> 
                                <?php }
                                }?>
                                </div>
                            </div>
                        <div class="dragAllLink"  onclick="fn_movealllistitems('list14','list15',0,0,'<?php echo $licenseid; ?>');">add all courses</div>
                    </div>
                </div>
                <div class='six columns'>
                    <div class="dragndropcol">
                        <div class="dragtitle">Courses in your schedule</div>
                        <div class="draglinkleftSearch" id="s_list15" >
                           <dl class='field row'>
                                <dt class='text'>
                                    <input placeholder='Search' type='text' id="list_4_search" name="list_4_search" onKeyUp="search_list(this,'#list15');" />
                                </dt>
                            </dl>
                        </div>
                        <div class="dragWell" id="testrailvisible12">
                            <div id="list15" class="dragleftinner droptrue3">
                                    <?php 
                                    $qryunitselect= $ObjDB->QueryObject("SELECT a.fld_id as courseid, a.fld_course_name as coursename, COUNT(c.fld_course_id) AS chkunit
                                                                                FROM itc_course_master AS a 
                                                                                LEFT JOIN itc_class_pdschedule_course_mapping AS b ON a.fld_id = b.fld_course_id 
                                                                                LEFT JOIN itc_assignment_pd_master AS c ON c.fld_pdschedule_id=b.fld_pdschedule_id AND c.fld_course_id=b.fld_course_id
                                                                                WHERE b.fld_pdschedule_id='".$sid."' AND b.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                                                                GROUP BY a.fld_id ORDER BY b.fld_order");									
                                    if($sflag==1){
                                            $sid=0;
                                    }
                                    if($qryunitselect->num_rows > 0){
                                            while($rowsqryunitselect = $qryunitselect->fetch_assoc()){
                                                    extract($rowsqryunitselect);											
                                    ?>
                            <div class="draglinkright <?php if($chkunit!=0 and $sid!=0){?>dim<?php }?>" id="list15_<?php echo $courseid; ?>">
                                <div class="dragItemLable" id="<?php echo $courseid; ?>"><?php echo $coursename; ?></div>
                                <div class="clickable" id="clck_<?php echo $courseid; ?>" onclick="fn_movealllistitems('list14','list15',<?php echo $courseid; ?>,'0','<?php echo $licenseid; ?>');"></div>
                            </div>
                            <?php }
                            }?>
                            </div>
                        </div>
                    <div class="dragAllLink" onclick="fn_movealllistitems('list15','list14',0,0,'<?php echo $licenseid; ?>');">remove all courses</div>
                    </div>
                </div>
            </div>
            <div class="row" align="right" style="padding-top:20px;">
                <input type="button" value="OrderPDLessons" onclick="fn_orderpdlessons(<?php echo $sid.",".$licenseid; ?>)" class="darkButton" />
            </div>
            <!--End of unit drag and drop-->
         <?php 
}
        
//change the PDlessons order based on Course name
if($oper == "loadorderpdlessons" and $oper != '')
{
        $sid = isset($method['sid']) ? $method['sid'] : '0';	
        $licenseid = isset($method['lid']) ? $method['lid'] : '0';	
        $sflag = isset($method['flag']) ? $method['flag'] : '0';
        $courseids = isset($method['courseids']) ? $method['courseids'] : '0';
        $courseid = explode(',',$courseids);
        
        echo "Organize your PD's";?>                
    <div class='row'>  
            <div class='span10 offset1'>                                      
            <table class='table table-hover table-striped table-bordered' id="selectpd">
                <thead class='tableHeadText'>
                    <tr>
                        <th>Column Name</th>
                        <th>Tools</th>
                        <th>Grade</th>                                                                                             
                    </tr>
                </thead>
                <tbody>
        <?php 
        if($courseid[0] != '') {
            for($i=0;$i<sizeof($courseid);$i++)
            {
               ?>
                <tr onclick="fn_showhidepdlesson(<?php echo $courseid[$i];?>)" name="0" id="course_<?php echo $courseid[$i];?>">
                    <td colspan="3" style="font-weight:bold;"><?php echo $ObjDB->SelectSingleValue("SELECT fld_course_name FROM itc_course_master WHERE fld_id='".$courseid[$i]."'");?></td>
                </tr>
                <?php 	
                if($sid==0)
                        $extqry = '1 AS fld_flag';
                else
                        $extqry = "(CASE WHEN a.fld_id = (SELECT fld_lesson_id FROM itc_class_pdschedule_lesson_mapping WHERE fld_lesson_id=a.fld_id AND fld_pdschedule_id='".$sid."' 
                                       AND fld_flag='1') THEN 1 END) AS fld_flag";
                
               $lessqry = $ObjDB->QueryObject("SELECT w.* FROM (SELECT a.fld_id AS lessonid, CONCAT(a.fld_pd_name,' ',c.fld_version) 
                                                                AS lessonname, d.fld_order AS orders, ".$extqry." 
                                                            FROM itc_pd_master AS a 

                                                            LEFT JOIN itc_license_pd_mapping AS b ON a.fld_id = b.fld_pd_id 
                                                            LEFT JOIN itc_pd_version_track AS c ON c.fld_pd_id=a.fld_id 
                                                            LEFT JOIN itc_class_pdschedule_lesson_mapping AS d ON d.fld_lesson_id=a.fld_id AND d.fld_pdschedule_id='".$sid."' 
                                                            AND d.fld_flag='1'
                                                            WHERE b.fld_license_id='".$licenseid."' AND a.fld_course_id ='".$courseid[$i]."' AND b.fld_active='1' 
                                                            AND a.fld_delstatus='0' AND c.fld_zip_type='1' AND c.fld_delstatus='0' GROUP BY a.fld_id) AS w 
                                                            ORDER BY CASE WHEN w.orders IS NULL THEN 99999 END, w.orders  ");

                        // for select existing schedules	
                        $fld_grade=0;

                        $checksch = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_class_pdschedule_grade WHERE fld_pdschedule_id='".$sid."'"); //ans=0
                        if($checksch==0)
                        $fld_grade=1;	
                        $fld_flag=1;
                        $points=100;
                        while($res=$lessqry->fetch_assoc()){ 
                            extract($res);
                            if($sid!=0){																		
                                $gradeqry = $ObjDB->QueryObject("SELECT fld_grade, fld_points as points,fld_flag FROM itc_class_pdschedule_grade 
                                                                    WHERE fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$lessonid."'  LIMIT 0,1");//AND fld_flag=1 //new line
                                if($gradeqry->num_rows>0)
                                extract($gradeqry->fetch_assoc());
                            } 
                            ?>
                            <tr id="<?php echo $courseid[$i];?>">
                                <td style="padding-left:100px;"><?php echo $lessonname; ?></td>                            
                                <td>
                                    <div id="up_<?php echo $i+1;?>" class="synbtn-promote <?php if($diagflag=="" or $diagflag==0){?>dim<?php }?>" style="float:left"></div>
                                    <div id="down_<?php echo $i+1;?>" class="synbtn-demote <?php if($diagflag=="" or $diagflag==0){?>dim<?php }?>" style="float:left"></div>
                                        <input type="checkbox" class="ipl less_<?php echo $lessonid;?>" <?php if($fld_flag==1){ echo 'checked="checked"';}?> id="pdipl_<?php echo $lessonid;?>" value="<?php echo $lessonid; ?>"   <?php if($diagflag=='' or $diagflag==0){?> class="dim" <?php } ?>/><!-- new line -->
                                </td>
                                <td>                                    	
                                    <input type="checkbox" <?php if($fld_grade==1 or $sid==0){ echo 'checked="checked"';}?> id="pdgrade_<?php echo $lessonid;?>" value="<?php echo $lessonid; ?>" />                                       
                                    <input type="text" id="pdgradevalue_<?php echo $lessonid; ?>" name="<?php echo $lessonid;?>" onkeyup="ChkValidChar(this.id);" value="<?php echo $points; ?>" style="width:20%" />
                                </td>
                            </tr>
                            <?php 	
                        }
 
                        ?>
                                <script>								
                                    $(document).ready(function(){
                                    loads<?php echo $i+1;?>();

                                    $("#up_<?php echo $i+1;?>,#down_<?php echo $i+1;?>").click(function(){
                                        var row = $(this).parents("tr:first");  

                                        if ($(this).is("#up_<?php echo $i+1;?>") ) {
                                                var row1 =$(this).parents("tr:first").attr('id');
                                                var row2 =$(this).parents("tr:first").attr('id');
                                                $(this).parents("tr:first").attr('id',row2);
                                                $(this).parents("tr:first").attr('id',row1);
                                                var td1 =$(this).parents("tr:first").children('td').html();
                                                var td2 =$(this).parents("tr:first").children('td').html();
                                                $(this).parents("tr:first").children('td:first').html(td2);
                                                $(this).parents("tr:first").children('td:first').html(td1);
                                                row.insertBefore(row.prev());
                                        } else {
                                                var row1 =$(this).parents("tr:first").attr('id');
                                                var row2 =$(this).parents("tr:first").attr('id');
                                                $(this).parents("tr:first").attr('id',row2);
                                                $(this).parents("tr:first").attr('id',row1);
                                                var td1 =$(this).parents("tr:first").children('td').html();
                                                var td2 =$(this).parents("tr:first").children('td').html();
                                                $(this).parents("tr:first").children('td:first').html(td2);
                                                $(this).parents("tr:first").children('td:first').html(td1);						
                                                row.insertAfter(row.next());
                                        } 

                                        loads<?php echo $i+1;?>();	
                                    });
                                    function loads<?php echo $i+1;?>()
                                    {					
                                            $('div#up_<?php echo $i+1;?>').each(function(index, element){
                                                     if(index==0){
                                                            $(this).addClass('dim');
                                                     }
                                                     else {
                                                            $(this).removeClass('dim');
                                                     }
                                             });

                                            var total = $('div#down_<?php echo $i+1;?>').length;	 
                                            $('div#down_<?php echo $i+1;?>').each(function(index, element){
                                                    if(index==total-1){
                                                            $(this).addClass('dim');
                                                    }
                                                    else {
                                                            $(this).removeClass('dim');
                                                    }
                                            });	 
                                    }
                                    });
                                </script>
                            <?php 
                            $m++;	
                        }
                    }
                    else {  ?>
                        <tr>
                            <td colspan="3"></td>
                        </tr>   <?php
                    }
                    ?>
                </tbody>
            </table>  
            <script type="text/javascript" language="javascript">					
                //Function to enter only numbers in textbox
                $("input[id^=pdgradevalue_]").keypress(function (e) {
                        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                                return false;
                        }						
                });

                //Function to set the max & min values for the textbox
                String.prototype.startsWith = function (str) {
                        return (this.indexOf(str) === 0);
                }
                function ChkValidChar(id) {                        
                        var txtbx = $('#'+id).val();
                        var nexttxtbx = 100;                       
                        if(parseInt(txtbx) > parseInt(nexttxtbx)) // true
                        {
                                $('#'+id).val('');
                                $('#pdgrade_'+$('#'+id).attr('name')).removeAttr('checked');
                        }
                        else if(txtbx==''){							
                                $('#pdgrade_'+$('#'+id).attr('name')).removeAttr('checked');
                        }
                        else{
                                $('#pdgrade_'+$('#'+id).attr('name')).attr('checked','checked');
                        }
                }


                //When a teacher unchecks the associated IPL Unit Math Connection, the related Grade check box should automatically uncheck.  
                //IPL
                $(".ipl").click(function(e){  
                    var id=this.id;
                    //alert(id);
                    var ipl1 = id.split("_");
                    //alert(ipl1[1]);
                    if($('#pdipl_'+ipl1[1]).is(':checked')){
                      var tmpgrade=1;
                    }
                    else{
                      var tmpgrade=0;
                    }                     
                        if(tmpgrade == 0){
                          $('#pdgrade_'+ipl1[1]).removeAttr('checked');
                        }
                        else{                      
                          $('#pdgrade_'+ipl1[1]).attr('checked','checked');
                        }
                });
                //When a teacher unchecks the associated IPL Unit Math Connection, the related Grade check box should automatically uncheck.  
                //IPL
            </script>
        </div>            
    </div>
    <!-- Add Extend Content button to assign for lessons-->
     <script>
            <?php if($sid!=0){?>                   
            <?php }?>
    </script>
     <?php 
        if($sflag==1){
                $sid=0;
        }
    ?>     
    <div class="row rowspacer">
        <div class="tRight">
            <input type="button" id="btnstep" class="darkButton" style="width:200px; height:42px;float:right;" value="Save Schedule" onClick="fn_savepdschedule(<?php echo $sid; ?>)" />
        </div>
    </div>
    <script>
        function fn_showhidepdlesson(uid){
                var flag = $('#course_'+uid).attr('name');
                if(flag==0)
                        $('#course_'+uid).attr('name',1);
                else
                        $('#course_'+uid).attr('name',0);
                $("tr[id^="+uid+"_]").each(function()
                {
                        if(flag==0)
                                $(this).hide();
                        else
                                $(this).show();
                });
        }			
    </script>
    <?php 
}            


if($oper == "savepdschedule" and $oper != '')
{   
        try{		
                $classid = isset($method['classid']) ? $method['classid'] : '0';
                $sid = isset($method['sid']) ? $method['sid'] : '0';
                $licenseid = isset($method['licenseid']) ? $method['licenseid'] : '0';
                $sname = isset($method['sname']) ? $ObjDB->EscapeStrAll($method['sname']) : '0';
                $startdate = isset($method['startdate']) ? $method['startdate'] : '0';
                $enddate = isset($method['enddate']) ? $method['enddate'] : '0';
                $students = isset($method['students']) ? $method['students'] : '0';
                $unstudents = isset($method['unstudents']) ? $method['unstudents'] : '0';
                $studenttype = isset($method['studenttype']) ? $method['studenttype'] : '0';
                $stype = isset($method['stype']) ? $method['stype'] : '0';
                $list15 = isset($method['list15']) ? $method['list15'] : '0';
                $pdgradeflag = isset($method['pdgradeflag']) ? $method['pdgradeflag'] : '0';
                $pdgradepoint = isset($method['pdgradepoint']) ? $method['pdgradepoint'] : '0';
                $pdlessonflag = isset($method['pdlessonflag']) ? $method['pdlessonflag'] : '0';
                 $lessid = isset($method['lessid']) ? $method['lessid'] : '0';
                
                $students = explode(',',$students);
                $unstudents = explode(',',$unstudents);
                $list15 = explode(',',$list15);
                $pdgradeflag = explode(',',$pdgradeflag);
                
                $pdgradepoint = explode(',',$pdgradepoint);
                $pdlessonflag = explode(',',$pdlessonflag);	
                 $lessid = explode(',',$lessid);	
                  
                /**validation for the parameters and these below functions are validate to return true or false***/
                $validate_sid=true;
                $validate_sname=true;
                $validate_classid=true;
                $validate_stype=true;
                $validate_startdate=true;
                $validate_licenseid=true;
                if($sid!=0) 
                $validate_sid=validate_datatype($sid,'int');
                $validate_sname=validate_datas($sname,'lettersonly');
                $validate_classid=validate_datatype($classid,'int');
                $validate_licenseid=validate_datatype($licenseid,'int');
                $validate_stype=validate_datatype($stype,'int');
                $validate_startdate=validate_datas($startdate,'dateformat');

                if($validate_sid and $validate_sname and $validate_classid and $validate_stype and $validate_startdate and $validate_licenseid){
                        if($studenttype==1){
                                /*---------checing the license for student----------------------*/	
                                $students=array();			
                                $count=0;
                                $add=0;		
                                $qry = $ObjDB->QueryObject("SELECT fld_student_id 
                                                                    FROM itc_class_student_mapping 
                                                                    WHERE fld_class_id='".$classid."' AND fld_flag='1'");
                                if($qry->num_rows>0){
                                        while($res=$qry->fetch_assoc())
                                        {
                                                extract($res);
                                                $students[]=$fld_student_id;					
                                                $check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                                FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                                WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$fld_student_id."' AND a.fld_flag='1' 
                                                                                                      AND b.fld_delstatus='0'");
                                                if($check==0)
                                                {
                                                        $count++;
                                                }
                                        }
                                }
                        }
                        else{			
                                $count=0;			
                                for($i=0;$i<sizeof($students);$i++)
                                {
                                        $check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                            FROM itc_license_assign_student AS a LEFT JOIN itc_user_master AS b ON a.fld_student_id=b.fld_id 
                                                                                            WHERE a.fld_license_id='".$licenseid."' AND a.fld_student_id='".$students[$i]."' AND a.fld_flag='1' 
                                                                                                    AND b.fld_delstatus='0'");		
                                         if($check==0)
                                        {
                                                $count++;
                                        }
                                        
                                }
                                for($i=0;$i<sizeof($unstudents);$i++)
                                {                                        
                                        $check = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
                                                                                        FROM itc_license_assign_student 
                                                                                        WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' AND fld_flag='1'");
                                        if($check>0)
                                        {
                                                $studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt 
                                                                                                    FROM itc_class_sigmath_student_mapping AS a LEFT JOIN itc_class_sigmath_master
                                                                                                    AS b ON a.fld_sigmath_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_sigmath_id<>'".$sid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt 
                                                                                                    FROM itc_class_rotation_schedule_student_mappingtemp AS a LEFT JOIN itc_class_rotation_schedule_mastertemp
                                                                                                    AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_expschedule_student_mappingtemp AS a 
                                                                                                                                    LEFT JOIN itc_class_rotation_expschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                    WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                     UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_mission_student_mappingtemp AS a 
                                                                                                                                    LEFT JOIN itc_class_rotation_mission_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                    WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_modexpschedule_student_mappingtemp AS a 
                                                                                                                                    LEFT JOIN itc_class_rotation_modexpschedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                                                                                    WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a
                                                                                                    LEFT JOIN itc_class_dyad_schedulemaster
                                                                                                    AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt 
                                                                                                    FROM itc_class_triad_schedule_studentmapping AS a LEFT JOIN itc_class_triad_schedulemaster
                                                                                                    AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_indassesment_student_mapping AS a 
                                                                                                    LEFT JOIN itc_class_indassesment_master
                                                                                                    AS b ON a.fld_schedule_id=b.fld_id WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                                                                    UNION ALL 
                                                                                                    SELECT COUNT(a.fld_id) AS cnt FROM itc_class_pdschedule_student_mapping AS a
                                                                                                    LEFT JOIN itc_class_pdschedule_master AS b ON a.fld_pdschedule_id=b.fld_id
                                                                                                    WHERE a.fld_student_id='".$unstudents[$i]."' 
                                                                                                    AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_pdschedule_id<>'".$sid."'
                                                                                                    UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_exp_student_mapping AS a 
								LEFT JOIN itc_class_indasexpedition_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                        UNION ALL 
                                SELECT COUNT(a.fld_id) AS cnt FROM itc_class_mission_student_mapping AS a 
								LEFT JOIN itc_class_mission_schedule_master AS b ON a.fld_schedule_id=b.fld_id 
								WHERE a.fld_student_id='".$unstudents[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."') AS o");

                                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_student_mapping 
                                                                            SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                            WHERE fld_pdschedule_id='".$sid."' AND fld_student_id='".$unstudents[$i]."'");
                                                if($studentcount==0){
                                                        $add++;
                                                        $ObjDB->NonQuery("UPDATE itc_license_assign_student 
                                                                                    SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                                    WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$unstudents[$i]."' ");
                                                }
                                        }
                                }	
                        }
                        $remainusersqry = $ObjDB->QueryObject("SELECT fld_remain_users AS remainusers, fld_no_of_users AS totusers 
                                                                        FROM itc_license_track 
                                                                        WHERE fld_school_id='".$schoolid."' AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND fld_user_id='".$indid."' 
                                                                        AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date LIMIT 0,1");		
                        extract($remainusersqry->fetch_assoc());

                        $assignedstudents = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                            FROM itc_license_assign_student 
                                                                            WHERE fld_license_id='".$licenseid."' AND fld_flag='1' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."'");		

                        $totalremain = $remainusers-$count;
                        if($totusers>=($assignedstudents+$count)){
                                $flag=1;
                        }		
                        else{	
                                $flag=0;
                        }

                        if($flag==1){ //if student user availale for license
                                if($sid!=0)
                                {
                                        $ObjDB->NonQuery("UPDATE itc_class_pdschedule_master 
                                                                         SET fld_schedule_name='".$sname."',fld_start_date='".date('Y-m-d',strtotime($startdate))."',fld_end_date='".date('Y-m-d',strtotime($enddate))."', 
                                                                                 fld_student_type='".$studenttype."',fld_updatedby='".$uid."',fld_updated_date='".date("Y-m-d H:i:s")."' 
                                                                         WHERE fld_id='".$sid."'");
                                        $ObjDB->NonQuery("UPDATE itc_class_master 
                                                                         SET fld_updated_date='".date("Y-m-d H:i:s")."' 
                                                                         WHERE fld_id='".$classid."'");
                                }
                                else{

                                        $sid=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_class_pdschedule_master (fld_class_id,fld_schedule_name,fld_start_date,fld_end_date,fld_created_date,fld_createdby) 
                                                                            VALUES('".$classid."','".$sname."','".date('Y-m-d',strtotime($startdate))."','".date('Y-m-d',strtotime($enddate))."',
                                                                                    '".date("Y-m-d H:i:s")."','".$uid."')");					
                                        $ObjDB->NonQuery("UPDATE itc_class_master 
                                                                         SET fld_updated_date='".date("Y-m-d H:i:s")."' 
                                                                         WHERE fld_id='".$classid."'");			
                                }

                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_student_mapping 
                                                                 SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                 WHERE fld_pdschedule_id='".$sid."'");
                                for($i=0;$i<sizeof($students);$i++)
                                {
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                        FROM itc_class_pdschedule_student_mapping 
                                                                                        WHERE fld_pdschedule_id='".$sid."' AND fld_student_id='".$students[$i]."'");				
                                        if($cnt==0)
                                        {
                                                $ObjDB->NonQuery("INSERT INTO itc_class_pdschedule_student_mapping(fld_pdschedule_id, fld_student_id,fld_license_id, fld_flag,fld_created_date,fld_created_by) 
                                                                            VALUES ('".$sid."', '".$students[$i]."', '".$licenseid."','1','".date('Y-m-d H:i:s')."','".$uid."')");
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_student_mapping 
                                                                                 SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                                 WHERE fld_pdschedule_id='".$sid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
                                        }

                                        //tracing student
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                    FROM itc_license_assign_student 
                                                                                    WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."'");
                                        if($cnt==0)
                                        {
                                                $ObjDB->NonQuery("INSERT INTO itc_license_assign_student(fld_school_id, fld_license_id, fld_student_id, fld_flag,fld_created_date,fld_created_by) 
                                                                        VALUES ('".$schoolid."','".$licenseid."','".$students[$i]."','1','".date('Y-m-d H:i:s')."','".$uid."')");									
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_license_assign_student 
                                                                    SET fld_flag='1',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."'
                                                                    WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$students[$i]."' AND fld_id='".$cnt."'");
                                        }
                                }		

                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_course_mapping 
                                                            SET fld_flag='0',fld_order='0',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."'
                                                            WHERE fld_pdschedule_id='".$sid."' AND fld_license_id='".$licenseid."'");			
                                for($i=0;$i<sizeof($list15);$i++)
                                { 
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                    FROM itc_class_pdschedule_course_mapping 
                                                                                    WHERE fld_pdschedule_id='".$sid."' AND fld_course_id='".$list15[$i]."' AND fld_license_id='".$licenseid."'");
                                        if($cnt==0)
                                        {
                                                $ObjDB->NonQuery("INSERT INTO itc_class_pdschedule_course_mapping(fld_pdschedule_id, fld_course_id, fld_license_id, fld_flag,fld_order,fld_createddate,fld_createdby) 
                                                                    VALUES ('".$sid."', '".$list15[$i]."','".$licenseid."', '1','".$i."','".date('Y-m-d H:i:s')."','".$uid."')");
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_course_mapping 
                                                                        SET fld_flag='1',fld_order='".$i."',fld_updateddate='".date("Y-m-d H:i:s")."',fld_updatedby='".$uid."' 
                                                                        WHERE fld_pdschedule_id='".$sid."' AND fld_course_id='".$list15[$i]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
                                        }
                                }


                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_lesson_mapping 
                                                            SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                            WHERE fld_pdschedule_id='".$sid."'  AND fld_license_id='".$licenseid."'");

                                $lessondays=0;	
                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_lesson_mapping 
                                                                 SET fld_order='0' WHERE fld_pdschedule_id='".$sid."'");			
                                for($i=0;$i<sizeof($lessid);$i++)
                                {
                                        $lessondays = $lessondays+$ObjDB->SelectSingleValueInt("SELECT fld_pd_days 
                                                                                                    FROM itc_pd_master 
                                                                                                    WHERE fld_id='".$lessid[$i]."'");
                                    
                                        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                    FROM itc_class_pdschedule_lesson_mapping 
                                                                                    WHERE fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$lessid[$i]."' AND fld_license_id='".$licenseid."'");
                                        if($cnt==0)
                                        {
                                                if($i==0)
                                                {
                                                    $sdate=date('Y-m-d',strtotime($startdate));
                                        }
                                        else
                                        {
                                                   $sdate=date("Y-m-d",strtotime($sdate. "+1 weekdays")); 
                                                }
                                                
                                                $ObjDB->NonQuery("INSERT INTO itc_class_pdschedule_lesson_mapping(fld_pdschedule_id, fld_lesson_id, fld_license_id, fld_flag,fld_order,fld_created_date,fld_created_by,fld_startdate) 
                                                                    VALUES ('".$sid."', '".$lessid[$i]."', '".$licenseid."', '".$pdgradeflag[$i]."','".$i."','".date('Y-m-d H:i:s')."','".$uid."','".$sdate."')");
                                        }
                                        else
                                        {
                                                if($i==0)
                                                {
                                                    $sdate=date('Y-m-d',strtotime($startdate));
                                                }
                                                else
                                                {
                                                   $sdate=date("Y-m-d",strtotime($sdate. "+1 weekdays")); 
                                                }
                                                
                                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_lesson_mapping 
                                                                        SET fld_startdate='".$sdate."',fld_flag='".$pdgradeflag[$i]."',fld_order='".$i."',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' 
                                                                        WHERE fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$lessid[$i]."' AND fld_id='".$cnt."' AND fld_license_id='".$licenseid."'");
                                        }
                                }

                                //update lesson grade points
                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_grade SET fld_flag='0' WHERE fld_pdschedule_id='".$sid."'");
                                						
                                for($i=0;$i<sizeof($pdgradeflag);$i++)
                                {	

                                    $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
                                                                                    FROM itc_class_pdschedule_grade 
                                                                                    WHERE fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$lessid[$i]."' AND fld_class_id='".$classid."'");
                                        if($cnt==0)
                                        {
                                                $ObjDB->NonQuery("INSERT INTO itc_class_pdschedule_grade(fld_class_id,fld_pdschedule_id,fld_lesson_id,fld_grade,fld_points,fld_flag,fld_created_date,fld_created_by) 
                                                                        VALUES ('".$classid."','".$sid."', '".$lessid[$i]."', '".$pdgradeflag[$i]."','".$pdgradepoint[$i]."','".$pdlessonflag[$i]."','".date("Y-m-d H:i:s")."','".$uid."')");	
                                                
                                                
                                        }
                                        else
                                        {
                                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_grade 
                                                                                 SET fld_flag='".$pdlessonflag[$i]."', fld_updated_date='".date("Y-m-d H:i:s")."', fld_grade='".$pdgradeflag[$i]."', fld_points='".$pdgradepoint[$i]."', 
                                                                                        fld_updated_by='".$uid."' 
                                                                                 WHERE fld_pdschedule_id='".$sid."' AND fld_lesson_id='".$lessid[$i]."' AND fld_id='".$cnt."' AND fld_class_id='".$classid."'");
                                               
                                        }
                                }

                            
                                //update enddate
                                $enddate=date("Y-m-d",strtotime($startdate. "+".($lessondays-1)." weekdays"));
                                
                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_master 
                                                                 SET fld_end_date='".date("Y-m-d",strtotime($enddate))."' 
                                                                 WHERE fld_id='".$sid."'");			

                                $ObjDB->NonQuery("UPDATE itc_class_pdschedule_master 
                                                                 SET fld_updated_date='".date("Y-m-d H:i:s")."', fld_license_id='".$licenseid."', fld_student_type='".$studenttype."' 
                                                                 WHERE fld_id='".$sid."'");

                                

                            echo "success~".$sid;
                            send_notification($licenseid,$schoolid,$indid);
                        }
                        else{
                                echo "fail";
                        }
                }
                else{
                        echo "invalid";
                }
        }
        catch(Exception $e){
                echo "invalid";
        }
}

if($oper == "deletepdschedule" and $oper != '')
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

        
    $scheduleid = isset($method['scheduleid']) ? $method['scheduleid'] : '0';
    $type = isset($method['type']) ? $method['type'] : '0';
		
    if($type==16)
    {
            $ObjDB->NonQuery("UPDATE itc_class_pdschedule_master SET fld_delstatus='1',fld_deletedby='".$uid."',fld_deleted_date='".date("Y-m-d H:i:s")."' WHERE fld_id='".$scheduleid."'");
            $add=0;

            $licenseid = $ObjDB->SelectSingleValueInt("SELECT fld_license_id FROM itc_class_pdschedule_master WHERE fld_id='".$scheduleid."'");

            $qry = $ObjDB->SelectSingleValue("SELECT GROUP_CONCAT(fld_student_id SEPARATOR',') FROM itc_class_pdschedule_student_mapping 
                                             WHERE fld_pdschedule_id='".$scheduleid."' AND fld_flag='1'");
            $studentid = explode(',',$qry);

            for($i=0;$i<sizeof($studentid);$i++)
            {
            $studentcount = $ObjDB->SelectSingleValueInt("SELECT SUM(o.cnt) FROM (SELECT COUNT(a.fld_id) AS cnt FROM itc_class_sigmath_student_mapping AS a  
                                                                LEFT JOIN itc_class_sigmath_master AS b ON a.fld_sigmath_id=b.fld_id 
                                                                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_sigmath_id<>'".$scheduleid."'
                                                        UNION ALL 
                                                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_rotation_schedule_student_mappingtemp AS a 
                                                                LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' 
                                                        UNION ALL 
                                                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_dyad_schedule_studentmapping AS a 
                                                                LEFT JOIN itc_class_dyad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'
                                                        UNION ALL 
                                                        SELECT COUNT(a.fld_id) AS cnt FROM itc_class_triad_schedule_studentmapping AS a 
                                                                LEFT JOIN itc_class_triad_schedulemaster AS b ON a.fld_schedule_id=b.fld_id 
                                                                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."'

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
                                                                WHERE a.fld_student_id='".$studentid[$i]."' AND a.fld_flag='1' AND b.fld_license_id='".$licenseid."' AND a.fld_pdschedule_id<>'".$scheduleid."') AS o");

                if($studentcount==0){
                        $add++;
                        $ObjDB->NonQuery("UPDATE itc_license_assign_student SET fld_flag='0',fld_updated_date='".date("Y-m-d H:i:s")."',fld_updated_by='".$uid."' WHERE fld_license_id='".$licenseid."' AND fld_student_id='".$studentid[$i]."' ");
                }

            }
    }
}


@include("footer.php");
