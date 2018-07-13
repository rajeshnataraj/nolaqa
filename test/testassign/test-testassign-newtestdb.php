<?php 
	@include("sessioncheck.php");
	
	$date = date("Y-m-d H:i:s");
	$oper = isset($method['oper']) ? $method['oper'] : '';
        
        error_reporting(0);  
	if($oper=="showdest" and $oper != " " )
	{
		$expid = isset($method['expid']) ? $method['expid'] : '0'; 
		$dest = isset($method['dest']) ? $method['dest'] : '0'; 
		$type = isset($method['type']) ? $method['type'] : '0'; 
		?>
		Select Destination
                <div class="selectbox">
                    <input type="hidden" name="destid" id="destid" value="<?php echo $dest;?>">
                    <?php 
                    if($type ==1){
                        $destname = $ObjDB->SelectSingleValue("SELECT fld_dest_name 
                                                                    FROM itc_exp_destination_master WHERE fld_id = '".$dest."' AND fld_delstatus='0'");
                    }
                    if($type ==2){
                        $destname = $ObjDB->SelectSingleValue("SELECT fld_dest_name 
                                                                    FROM itc_mis_destination_master WHERE fld_id = '".$dest."' AND fld_delstatus='0'");
                    }
                    ?>

                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium"  style="width:97%" data-option="<?php if($dest ==0){ echo '0';} else {echo  $destid;}?>"><?php if($dest ==0){ echo "Select destination";} else {echo $destname;}?></span>
                      <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options">
                        <input type="text" class="selectbox-filter" placeholder="Search Destination">
                            <ul role="options">
                                    <?php
                                if($type ==1){
                                $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS destid, a.fld_dest_name as destname FROM itc_exp_destination_master AS a
                                                                                                                        LEFT JOIN itc_exp_master AS b ON a.fld_exp_id = b.fld_id
                                                                                                                        WHERE a.fld_exp_id='".$expid."' AND a.fld_delstatus='0'");
                                }
                                if($type ==2){
                                    $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS destid, a.fld_dest_name as destname FROM itc_mis_destination_master AS a
                                                                                                                        LEFT JOIN itc_mission_master AS b ON a.fld_mis_id = b.fld_id
                                                                                                                        WHERE a.fld_mis_id='".$expid."' AND a.fld_delstatus='0'");
                                }
                                if($grdqry->num_rows>0){
                                    while($row = $grdqry->fetch_assoc())
                                    {
                                        extract($row);
                                        ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $destid;?>" onclick="fn_showtask(<?php echo $destid;?>,0,<?php echo $type;?>)" ><?php echo $destname; ?></a></li>
                                        <?php
                                    }
                                }?>
                            </ul>
                    </div>
                </div>
	<?php 
	}	

	if($oper=="showtask" and $oper != " " )
	{
		$destid = isset($method['destid']) ? $method['destid'] : '0'; 
		$task = isset($method['task1']) ? $method['task1'] : '0'; 
		$type = isset($method['type']) ? $method['type'] : '0'; 
		?>
		Select Task
                <div class="selectbox">
                        <input type="hidden" name="taskid" id="taskid" value="<?php echo $task;?>">
                        <?php 
                        if($type ==1){
                            $taskname = $ObjDB->SelectSingleValue("SELECT fld_task_name 
                                                                 FROM itc_exp_task_master WHERE fld_id = '".$task."' AND fld_delstatus='0'");
                        }
                        if($type ==2){
                            $taskname = $ObjDB->SelectSingleValue("SELECT fld_task_name 
                                                                 FROM itc_mis_task_master WHERE fld_id = '".$task."' AND fld_delstatus='0'");
                        }
                        ?>
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" style="width:97%" data-option="<?php if($task ==0){ echo  "0";} else {echo $task;}?>"><?php if($task == '0'){ echo "Select Task";} else {echo $taskname;}?></span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options">
                        <input type="text" class="selectbox-filter" placeholder="Search Task">
                        <ul role="options">
                            <?php
                            if($type == '1'){
                            $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS taskid, a.fld_task_name as taskname FROM itc_exp_task_master AS a
                                                                                                                    LEFT JOIN itc_exp_destination_master AS b ON a.fld_dest_id = b.fld_id
                                                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_delstatus='0'");
                            }
                            if($type == '2'){
                                $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS taskid, a.fld_task_name as taskname FROM itc_mis_task_master AS a
                                                                                                                    LEFT JOIN itc_mis_destination_master AS b ON a.fld_dest_id = b.fld_id
                                                                                                                    WHERE a.fld_dest_id='".$destid."' AND a.fld_delstatus='0'");
                            }
                            if($grdqry->num_rows>0){
                                while($row = $grdqry->fetch_assoc())
                                {
                                    extract($row);
                                    ?>
                                        <li><a tabindex="-1" href="#" data-option="<?php echo $taskid;?>" onclick="fn_showres(<?php echo $taskid;?>,0,<?php echo $type;?>)" ><?php echo $taskname; ?></a></li>
                                    <?php
                                }
                            }?>
                        </ul>
                    </div>
                </div>
                <?php 
        }	

	if($oper=="showres" and $oper != " " )
	{
		$taskid = isset($method['taskid']) ? $method['taskid'] : '0';
		$res = isset($method['res']) ? $method['res'] : '0'; 
		$type = isset($method['type']) ? $method['type'] : '0'; 
		?>
		Select Resource
                <div class="selectbox">
                    <input type="hidden" name="resid" id="resid" value="<?php echo $res;?>">
                    <?php 
                        if($type ==1){
                            $resname = $ObjDB->SelectSingleValue("SELECT fld_res_name 
                                                                            FROM itc_exp_resource_master WHERE fld_id = '".$res."' AND fld_delstatus='0'");
                        }
                        if($type ==2){
                            $resname = $ObjDB->SelectSingleValue("SELECT fld_res_name 
                                                                            FROM itc_mis_resource_master WHERE fld_id = '".$res."' AND fld_delstatus='0'");
                        }
                        ?>
                    <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                        <span class="selectbox-option input-medium" style="width:97%" data-option= "<?php if($res == '0'){ echo "0";} else {echo $res;}?>"><?php if($res == '0'){ echo "Select Resource";} else {echo $resname;}?></span>
                        <b class="caret1"></b>
                    </a>
                    <div class="selectbox-options">
                        <input type="text" class="selectbox-filter" placeholder="Search Resource">
                            <ul role="options">
                                <?php
                                    if($type == '1'){
                                    $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS resid, a.fld_res_name as resname FROM itc_exp_resource_master AS a
                                                                                                                            LEFT JOIN itc_exp_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                                                            WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0'");
                                    }
                                    if($type == '2'){
                                        $grdqry = $ObjDB->QueryObject("SELECT  a.fld_id AS resid, a.fld_res_name as resname FROM itc_mis_resource_master AS a
                                                                                                                            LEFT JOIN itc_mis_task_master AS b ON a.fld_task_id = b.fld_id
                                                                                                                            WHERE a.fld_task_id='".$taskid."' AND a.fld_delstatus='0'");
                                    }
                                    if($grdqry->num_rows>0){
                                        while($row = $grdqry->fetch_assoc())
                                        {
                                            extract($row);
                                            ?>
                                                <li><a tabindex="-1" href="#" data-option="<?php echo $resid;?>"><?php echo $resname; ?></a></li>
                                            <?php
                                        }
                                    }?>
                            </ul>
                    </div>
                </div>
	<?php 
	}	
        
         /***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/ 
     if($oper == "step2forpitscoassessment" and $oper != '') 
     {
        try 
        {
             $oldtestid = (isset($method['testid'])) ? $method['testid'] : 0;
             $testname =(isset( $method['testname'])) ?  ($method['testname']) : '';
             $testdes =(isset( $method['testdes'])) ?  ($method['testdes']) : '';
             $asstype =(isset( $method['asstype'])) ?  ($method['asstype']) : '';
             $assexp =(isset( $method['assexp'])) ?  ($method['assexp']) : '0';
             $assmis =(isset( $method['assmis'])) ?  ($method['assmis']) : '0';
             $contentid =(isset( $method['contentid'])) ?  ($method['contentid']) : 0;
             $productid =(isset( $method['productid'])) ?  ($method['productid']) : 0;

            if($asstype == 1 or $asstype == 2)
            {
                $attempts =1;
            }
            else
            {
                $attempts =(isset( $method['attempts'])) ?  $method['attempts'] : '';
            }
            
             $timelimit =(isset( $method['timelimit'])) ?  $method['timelimit'] : '';
             $score =(isset( $method['score'])) ?  $method['score'] : '';
             $prepost = isset($method['prepost']) ? $method['prepost'] : '';          
             $destid = isset($method['destid']) ? $method['destid'] : '0';
             $taskid = isset($method['taskid']) ? $method['taskid'] : '0';
             $resid = isset($method['resid']) ? $method['resid'] : '0';

             $lettergrade =(isset( $method['lettergrade'])) ?  $method['lettergrade'] : '';
             $lowerbound =(isset( $method['lowerbound'])) ?  $method['lowerbound'] : '';
             $higherbound =(isset( $method['higherbound'])) ?  $method['higherbound'] : '';
             $boxid =(isset( $method['boxid'])) ?  $method['boxid'] : '';
             $remove =(isset( $method['remove'])) ?  $method['remove'] : '';
             $grade =(isset( $method['grade'])) ?  $method['grade'] : '';
             $tags = isset($method['tags']) ? ($method['tags']) : '';

             $lg=explode("~",$lettergrade);
             $lb=explode("~",$lowerbound);
             $hb=explode("~",$higherbound);
             $bid=explode("~",$boxid);
             $rem=explode("~",$remove);

             /**validation for the parameters and these below functions are validate to return true or false***/
             $validate_testid=true;
             $validate_testname=true;
             if($testid!=0)  $validate_testid=validate_datatype($testid,'int');
             $validate_testname=validate_datas($testname,'lettersonly'); 

             /**for purpose remove unwanted scripts****/
             $tags = $ObjDB->EscapeStrAll($tags);
             $testname = $ObjDB->EscapeStrAll($testname);
             $testdes = $ObjDB->EscapeStr($testdes);		

             if($validate_testid and $validate_testname)
             {
                     
                        if($assexp == '' ){
                            $assexp = 0;
                        }
                        
                        $questtype=$ObjDB->SelectSingleValueInt("SELECT fld_question_type FROM itc_test_master WHERE fld_id='".$oldtestid."' AND fld_delstatus='0';");
                        $totalquestion=$ObjDB->SelectSingleValueInt("SELECT fld_total_question FROM itc_test_master WHERE fld_id='".$oldtestid."' AND fld_delstatus='0';");
                        
                        $testid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_master(fld_test_name, fld_test_des,fld_ass_type, fld_expt, fld_mist, 
                                                                                                    fld_time_limit, fld_score, fld_max_attempts, fld_school_id, fld_created_by, 
                                                                        fld_created_date, fld_step_id,fld_destid,fld_prepostid,fld_taskid,fld_resid,fld_question_type,fld_total_question,fld_profile_id,fld_content_id,fld_product_id)
                                                                                               VALUES('".$testname."','".$testdes."','".$asstype."','".$assexp."','".$assmis."','".$timelimit."',
                                                                        '".$score."','".$attempts."','".$senshlid."','".$uid."','".date('Y-m-d H:i:s')."','1','".$destid."','".$prepost."','".$taskid."','".$resid."','".$questtype."','".$totalquestion."','".$sessprofileid."','".$contentid."','".$productid."')");

                        
//                         if($asstype == 1){    
//                        if($assexp != '0' && $destid=='0' && $taskid=='0' && $resid=='0')
//                        {
//                                $fldname='fld_texpid';
//                                $statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//                                                                                                                fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//                        }
//                        elseif($assexp != '0' && $destid != '0' && $taskid=='0' && $resid=='0')
//                        {
//                                $statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//                                                                                                                        fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//                        }
//                        elseif($assexp!='0' && $destid!='0' && $taskid!='0' && $resid=='0')
//                        {
//                                $statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//                                                                                                                        fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//                        }
//                        elseif($assexp!='0' && $destid!='0' && $taskid!='0' && $resid!='0')
//                        {
//                                $statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//                                                                                                                        fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//                        }
//                        if($statuscount=='0'){
//
//                                $ObjDB->NonQuery("INSERT INTO itc_exptest_toogle(fld_exptestid,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_tprepost,fld_status,fld_flag,fld_school_id,fld_created_by, 
//                                                                                                fld_created_date)
//                                                 VALUES('".$testid."','".$assexp."','".$destid."','".$taskid."','".$resid."','".$prepost."','1','1','".$schoolid."','".$uid."','".date('Y-m-d H:i:s')."')");
//
//
//                        }
//                        else{
//                                $ObjDB->NonQuery("INSERT INTO itc_exptest_toogle(fld_exptestid,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_tprepost,fld_status,fld_flag,fld_school_id,fld_created_by, 
//                                                                                                fld_created_date)
//                                                                VALUES('".$testid."','".$assexp."','".$destid."','".$taskid."','".$resid."','".$prepost."','3','1','".$schoolid."','".$uid."','".date('Y-m-d H:i:s')."')");
//
//                        }
//                        }

                        /*--Tags insert-----*/
                        fn_taginsert($tags,20,$testid,$uid);

                        for($i=0;$i<count($lg)-1;$i++)
                        {
                                $count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_grading_scale_mapping WHERE fld_test_id='".$testid."' AND fld_boxid='".$bid[$i]."'");

                                if($count == 0)
                                {	

                                        $ObjDB->NonQuery("INSERT INTO itc_test_grading_scale_mapping(fld_test_id, fld_boxid, fld_upper_bound, fld_lower_bound, 
                                                                                                 fld_grade, fld_roundflag,fld_flag)
                                                                        VALUES('".$testid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1')");

                                }
                                else{
                                }
                        }
                        
                    /***************Test Question Assign Code Start here*************/
                        $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_question_id AS qusid,fld_section_id AS fsectionid, fld_tag_id AS tagid,fld_order_by AS orderby,fld_rflag as flag,fld_order_bytags AS orderbytag
                                                                    FROM itc_test_questionassign WHERE fld_test_id='".$oldtestid."' AND fld_delstatus='0'");
                        if($qryfortesgrade->num_rows > 0)
                        {
                            while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                            {
                                extract($rowsqryfortestgrade);

                                $ObjDB->NonQuery("INSERT INTO itc_test_questionassign(fld_test_id, fld_question_id,fld_section_id,fld_tag_id, fld_order_by,fld_order_bytags,fld_rflag, fld_created_by,fld_created_date)
                                                        VALUES('".$testid."','".$qusid."','".$fsectionid."','".$tagid."','".$orderby."','".$orderbytag."','".$flag."','".$uid."','".$date."')");

                            }
                        }
                    /***************Test Question Assign Code End here*************/


                    /****************Random Question Assign Code Start here************/
                        $randomcnt = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_random_questionassign WHERE fld_rtest_id='".$oldtestid."' AND fld_delstatus='0'");

                        if($randomcnt!='0')
                        {
                            $qryfortesgrade= $ObjDB->QueryObject("SELECT fld_tag_id AS tagids,fld_avl_questions AS qncounts,fld_qn_assign AS qnassign, 
                                                                        fld_pct_section AS pctsection,fld_order_by AS orderby
                                                                        FROM itc_test_random_questionassign WHERE fld_rtest_id='".$oldtestid."' AND fld_delstatus='0'");
                            if($qryfortesgrade->num_rows > 0)
                            {
                                while($rowsqryfortestgrade = $qryfortesgrade->fetch_assoc())
                                {
                                    extract($rowsqryfortestgrade);

                                    $ObjDB->NonQuery("INSERT INTO itc_test_random_questionassign (fld_rtest_id, fld_tag_id, fld_avl_questions,
                                                        fld_qn_assign,fld_pct_section,fld_order_by,fld_created_by, fld_created_date)
                                                        VALUES ('".$testid."','".$tagids."','".$qncounts."','".$qnassign."','".$pctsection."','".$orderby."',
                                                        '".$uid."','".$date."')");

                                }
                            }
                        }
                    /****************Random Question Assign Code Start here************/
                        
                           
                    /**** Inline Exp for Mapping for students and class code start here*****/
                        if($asstype == 1)
                        {                             
                            $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping 
							 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_test_id='".$testid."'");  
		

                            $scheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasexpedition_master
                                                                    WHERE fld_exp_id='".$assexp."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($scheduleqry->num_rows>0)
                            {
                               while($rowsch = $scheduleqry->fetch_assoc())
                               {
                                    extract($rowsch);

                                    $stuqry = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_exp_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqry->num_rows>0)
                                    {
                                        while($rowstu = $stuqry->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_exp_grade AS a
                                                                                            WHERE a.fld_exp_id='".$assexp."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_exptype='3'
                                                                                            ORDER BY a.fld_exptype");


                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_exp_id='".$assexp."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");

                                            if($count == 0){	

                                                $ObjDB->NonQuery("INSERT INTO itc_test_inline_student_mapping(fld_test_id, fld_exp_id, fld_class_id, fld_schedule_id, 
                                                                                                         fld_student_id, fld_points_possible, fld_flag,fld_created_by, 
                                                                                                                fld_created_date)
                                                                                VALUES('".$testid."','".$assexp."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else{
                                                $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                                WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                               }
                            }
                        }
                        /**** Inline Exp for Mapping for students and class code End here*****/
                        
                        /*********Mission report Code Start Here Developed By Mohan M 16-7-2015********/
                        if($asstype == 2)
                        {   
                              $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping 
                                                         SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                         WHERE fld_test_id='".$testid."'");  
                   

                            $misscheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasmission_master
                                                                    WHERE fld_mis_id='".$assmis."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($misscheduleqry->num_rows>0)
                            {
                               while($rowmissch = $misscheduleqry->fetch_assoc())
                               {
                                    extract($rowmissch);

                                    $stuqrymis = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_mission_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqrymis->num_rows>0){
                                        while($rowstu = $stuqrymis->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_mission_grade AS a
                                                                                            WHERE a.fld_mis_id='".$assmis."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_mistype='3'
                                                                                            ORDER BY a.fld_mistype");

                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_mission_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_mis_id='".$assmis."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");

                                            if($count == 0)
                                            {	
                                                $ObjDB->NonQuery("INSERT INTO itc_test_mission_inline_student_mapping(fld_test_id, fld_mis_id, fld_class_id, fld_schedule_id, 
                                                                                fld_student_id, fld_points_possible, fld_flag,fld_created_by, fld_created_date)
                                                                                VALUES('".$testid."','".$assmis."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else
                                            {
                                                $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                                WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                               }
                            }
                        }
                        /*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/
                        
                        
                echo "success~".$testid;
             }
             else
             {
                      echo "fail";
             }
        }
        catch(Exception $e)
        {
            echo "fail";		
        }
     }	

    /***********Share Pitsco Assessment For Teacher Developed By Mohan M Updated By 19-8-2015*****************/ 
        
        
	/*---Save the firt step tn test------*/
	if($oper == "step2" and $oper != '') 
	{
		try {
		$testid = (isset($method['testid'])) ? $method['testid'] : 0;
		$testname =(isset( $method['testname'])) ?  ($method['testname']) : '';
		$testdes =(isset( $method['testdes'])) ?  ($method['testdes']) : '';
		$asstype =(isset( $method['asstype'])) ?  ($method['asstype']) : 0;
		$assexp =(isset( $method['assexp'])) ?  ($method['assexp']) : 0;
                $assmis =(isset( $method['assmis'])) ?  ($method['assmis']) : 0;
                $contentid =(isset( $method['contentid'])) ?  ($method['contentid']) : 0;
                $productid =(isset( $method['productid'])) ?  ($method['productid']) : 0;
		
		if($asstype == 1 or $asstype == 2){
			$attempts =1;
		}
		else{
			$attempts =(isset( $method['attempts'])) ?  $method['attempts'] : '';
		}
			$timelimit =(isset( $method['timelimit'])) ?  $method['timelimit'] : '';
			$score =(isset( $method['score'])) ?  $method['score'] : '';
		$prepost = isset($method['prepost']) ? $method['prepost'] : '0'; 
		$destid = isset($method['destid']) ? $method['destid'] : '0';
		$taskid = isset($method['taskid']) ? $method['taskid'] : '0';
		$resid = isset($method['resid']) ? $method['resid'] : '0';
		
		$lettergrade =(isset( $method['lettergrade'])) ?  $method['lettergrade'] : '';
		$lowerbound =(isset( $method['lowerbound'])) ?  $method['lowerbound'] : '';
		$higherbound =(isset( $method['higherbound'])) ?  $method['higherbound'] : '';
		$boxid =(isset( $method['boxid'])) ?  $method['boxid'] : '';
		$remove =(isset( $method['remove'])) ?  $method['remove'] : '';
		$grade =(isset( $method['grade'])) ?  $method['grade'] : '';
		$tags = isset($method['tags']) ? ($method['tags']) : '';
		
		$lg=explode("~",$lettergrade);
		$lb=explode("~",$lowerbound);
		$hb=explode("~",$higherbound);
		$bid=explode("~",$boxid);
		$rem=explode("~",$remove);
		
		/**validation for the parameters and these below functions are validate to return true or false***/
		$validate_testid=true;
		$validate_testname=true;
		if($testid!=0)  $validate_testid=validate_datatype($testid,'int');
		$validate_testname=validate_datas($testname,'lettersonly'); 
        
		/**for purpose remove unwanted scripts****/
		$tags = $ObjDB->EscapeStrAll($tags);
		$testname = $ObjDB->EscapeStrAll($testname);
		$testdes = $ObjDB->EscapeStr($testdes);		
		
		if($validate_testid and $validate_testname)
		{
			if($testid!=0)
			{
				$ObjDB->NonQuery("UPDATE itc_test_master 
								 SET fld_content_id='".$contentid."',fld_product_id='".$productid."',fld_test_name='".$testname."',fld_test_des='".$testdes."',fld_ass_type='".$asstype."',
								 	fld_expt='".$assexp."',fld_mist='".$assmis."',fld_time_limit='".$timelimit."',fld_score='".$score."',
									fld_max_attempts='".$attempts."',fld_updated_by='".$uid."',fld_updated_date='".$date."',fld_step_id='1' 
								WHERE fld_id='".$testid."'");
				
				/*---tags------*/
				$ObjDB->NonQuery("UPDATE itc_main_tag_mapping SET fld_access='0' 
								WHERE fld_tag_type='20' AND fld_item_id='".$testid."' 
								AND fld_tag_id IN(select fld_id FROM itc_main_tag_master WHERE fld_created_by='".$uid."' AND fld_delstatus='0' )");
				fn_tagupdate($tags,20,$testid,$uid);
				
			}
			else
			{
				$testid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_master(fld_test_name, fld_test_des,fld_ass_type, fld_expt,fld_mist, 
														fld_time_limit, fld_score, fld_max_attempts, fld_school_id, fld_created_by, 
                                                                                    fld_created_date, fld_step_id,fld_destid,fld_prepostid,fld_taskid,fld_resid,fld_content_id,fld_product_id,fld_profile_id)
													   VALUES('".$testname."','".$testdes."','".$asstype."','".$assexp."','".$assmis."','".$timelimit."',
                                                                                    '".$score."','".$attempts."','".$senshlid."','".$uid."','".date('Y-m-d H:i:s')."','1','".$destid."','".$prepost."','".$taskid."','".$resid."','".$contentid."','".$productid."','".$sessprofileid."')");
//                        if($asstype == 1){    
//                          if($assexp != '0' && $destid=='0' && $taskid=='0' && $resid=='0')
//				{
//					$statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//															fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//				}
//				elseif($assexp != '0' && $destid != '0' && $taskid=='0' && $resid=='0')
//				{
//					$statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//																fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//				}
//				elseif($assexp!='0' && $destid!='0' && $taskid!='0' && $resid=='0')
//				{
//					$statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//																fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//				}
//				elseif($assexp!='0' && $destid!='0' && $taskid!='0' && $resid!='0')
//				{
//					$statuscount=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_exptest_toogle WHERE fld_texpid='".$assexp."' AND fld_tdestid='".$destid."' AND
//																fld_ttaskid='".$taskid."' AND fld_tresid='".$resid."' AND fld_status='1' AND fld_flag='1' AND fld_tprepost='".$prepost."' AND fld_created_by='".$uid."'");
//				}
//					if($statuscount=='0'){
//                               
//						$ObjDB->NonQuery("INSERT INTO itc_exptest_toogle(fld_exptestid,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_tprepost,fld_status,fld_flag,fld_school_id,fld_created_by, 
//														fld_created_date)
//								 VALUES('".$testid."','".$assexp."','".$destid."','".$taskid."','".$resid."','".$prepost."','1','1','".$schoolid."','".$uid."','".date('Y-m-d H:i:s')."')");
//				
//
//					}
//					else{
//						$ObjDB->NonQuery("INSERT INTO itc_exptest_toogle(fld_exptestid,fld_texpid,fld_tdestid,fld_ttaskid,fld_tresid,fld_tprepost,fld_status,fld_flag,fld_school_id,fld_created_by, 
//														fld_created_date)
//								 		VALUES('".$testid."','".$assexp."','".$destid."','".$taskid."','".$resid."','".$prepost."','3','1','".$schoolid."','".$uid."','".date('Y-m-d H:i:s')."')");
//				
//					}
//                        }
				/*--Tags insert-----*/
					fn_taginsert($tags,20,$testid,$uid);
				
			}
                        /**** Inline Exp for Mapping for students and class code start here*****/
                          if($asstype == 1){                      
                              $ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping 
							 SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
							 WHERE fld_test_id='".$testid."'");  
		

                            $scheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasexpedition_master
                                                                    WHERE fld_exp_id='".$assexp."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($scheduleqry->num_rows>0)
                            {
                               while($rowsch = $scheduleqry->fetch_assoc())
                               {
                                    extract($rowsch);

                                    $stuqry = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_exp_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqry->num_rows>0){
                                        while($rowstu = $stuqry->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_exp_grade AS a
                                                                                            WHERE a.fld_exp_id='".$assexp."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_exptype='3'
                                                                                            ORDER BY a.fld_exptype");
                                        
                                            
                                        $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_exp_id='".$assexp."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");
					
					if($count == 0){	
						
						$ObjDB->NonQuery("INSERT INTO itc_test_inline_student_mapping(fld_test_id, fld_exp_id, fld_class_id, fld_schedule_id, 
													 fld_student_id, fld_points_possible, fld_flag,fld_created_by, 
														fld_created_date)
										VALUES('".$testid."','".$assexp."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");
						
					}
					else{
						$ObjDB->NonQuery("UPDATE itc_test_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
										WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
					}

                                        }
                                    }
                               }
                            }
                        }
                        /**** Inline Exp for Mapping for students and class code End here*****/
                        
                        
                        /*********Mission report Code Start Here Developed By Mohan M 16-7-2015*************/
                        if($asstype == 2)
                        {   
                              $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping 
                                                         SET fld_flag='0', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."' 
                                                         WHERE fld_test_id='".$testid."'");  
                   

                            $misscheduleqry = $ObjDB->QueryObject("SELECT fld_class_id AS clsid, fld_id AS scheduleid FROM itc_class_indasmission_master
                                                                    WHERE fld_mis_id='".$assmis."' AND fld_createdby='".$uid."' AND fld_delstatus='0' AND fld_flag='1'");
                            if($misscheduleqry->num_rows>0)
                            {
                               while($rowmissch = $misscheduleqry->fetch_assoc())
                               {
                                    extract($rowmissch);

                                    $stuqrymis = $ObjDB->QueryObject("SELECT fld_student_id As studentids FROM itc_class_mission_student_mapping WHERE fld_schedule_id='".$scheduleid."' AND fld_flag='1'");
                                    if($stuqrymis->num_rows>0){
                                        while($rowstu = $stuqrymis->fetch_assoc())
                                        {
                                            extract($rowstu);
                                            $possiblepointfortest = $ObjDB->SelectSingleValueInt("SELECT a.fld_pointspossible AS possiblepoint 
                                                                                            FROM itc_class_mission_grade AS a
                                                                                            WHERE a.fld_mis_id='".$assmis."'
                                                                                                    AND a.fld_class_id='".$clsid."'
                                                                                                    AND a.fld_flag='1' AND a.fld_mistype='3'
                                                                                            ORDER BY a.fld_mistype");

                                            $count=$ObjDB->SelectSingleValueInt("SELECT fld_id FROM itc_test_mission_inline_student_mapping WHERE fld_test_id='".$testid."' AND fld_mis_id='".$assmis."' AND fld_class_id='".$clsid."' AND fld_schedule_id='".$scheduleid."' AND fld_student_id='".$studentids."'  AND fld_created_by='".$uid."'");

                                            if($count == 0)
                                            {	
                                                $ObjDB->NonQuery("INSERT INTO itc_test_mission_inline_student_mapping(fld_test_id, fld_mis_id, fld_class_id, fld_schedule_id, 
                                                                                fld_student_id, fld_points_possible, fld_flag,fld_created_by, fld_created_date)
                                                                                VALUES('".$testid."','".$assmis."','".$clsid."','".$scheduleid."','".$studentids."','".$possiblepointfortest."','1','".$uid."','".date('Y-m-d H:i:s')."')");

                                            }
                                            else
                                            {
                                                $ObjDB->NonQuery("UPDATE itc_test_mission_inline_student_mapping SET fld_flag='1', fld_updated_date = '".$date."' , fld_updated_by = '".$uid."'
                                                                                WHERE fld_test_id='".$testid."' and fld_id='".$count."'");
                                            }

                                        }
                                    }
                               }
                            }
                        }
                        /*********Mission report Code End Here Developed By Mohan M 16-7-2015*************/
                        
                   
			for($i=0;$i<count($rem);$i++)
			{
				$ObjDB->NonQuery("UPDATE itc_test_grading_scale_mapping SET fld_flag=0
								 WHERE fld_boxid='".$rem[$i]."' AND fld_test_id='".$testid."' AND fld_flag=1");
			}
		
				for($i=0;$i<count($lg)-1;$i++)
				{
					$count=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_grading_scale_mapping WHERE fld_test_id='".$testid."' AND fld_boxid='".$bid[$i]."'");
					
					if($count == 0){	
						
						$ObjDB->NonQuery("INSERT INTO itc_test_grading_scale_mapping(fld_test_id, fld_boxid, fld_upper_bound, fld_lower_bound, 
													 fld_grade, fld_roundflag,fld_flag)
										VALUES('".$testid."','".$bid[$i]."','".$hb[$i]."','".$lb[$i]."','".$lg[$i]."','".$grade."','1')");
						
					}
					else{
						$ObjDB->NonQuery("UPDATE itc_test_grading_scale_mapping SET fld_upper_bound='".$hb[$i]."', fld_lower_bound='".$lb[$i]."', 
												fld_grade='".$lg[$i]."', fld_roundflag='".$grade."',fld_flag=1 
										WHERE fld_test_id='".$testid."' and fld_boxid='".$bid[$i]."'");
					}
				}
		  
		   echo "success~".$testid;
		}
		else
		{
			 echo "fail";
		}
		}
		catch(Exception $e)
		{
	       echo "fail";		
   		}
	}	
	
	/*--- Save/Update a test Final Step ---*/
	if($oper == "saveclassreview" and $oper != '')
	{		
		$classid = isset($method['classid']) ? $method['classid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_class_master SET fld_step_id='1', fld_flag='1', fld_updated_by='".$uid."', 
		                 fld_updated_date='".$date."' 
						 WHERE fld_id='".$classid."' AND fld_delstatus='0'");
		
		echo "success~".$classid;
	}
	
	/*--- Check test Name ---*/
	if($oper=="checktestname" and $oper != " " )
	{
		$testid = isset($method['testid']) ? $method['testid'] : '0';
		$testname = isset($method['testname']) ? fnEscapeCheck($method['testname']) : '';
		
		$count = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_test_master where MD5(LCASE(REPLACE(fld_test_name,' ','')))='".$testname."' 
		                                      AND fld_delstatus='0' AND fld_id<>'".$testid."'"); 
		if($count == 0){ echo "true"; }	else { echo "false"; }
	}
	
	/*--- Delete a test  ---*/
	if($oper == "deletetest" and $oper != '')
	{		
		$testid = isset($method['testid']) ? $method['testid'] : '0';
		
		$ObjDB->NonQuery("UPDATE itc_test_master SET fld_delstatus='1', fld_deleted_date='".$date."', 
		                 fld_deleted_by='".$uid."' WHERE fld_id='".$testid."'");
                /***Mohan M**/
                $countass = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_master WHERE fld_id='".$testid."' AND fld_ass_type='1' ");//AND fld_delstatus='1'
		
                if($countass != 0){ 
                    $ObjDB->NonQuery("UPDATE itc_exptest_toogle SET fld_status='3', fld_updated_date='".$date."', 
		                 fld_updated_by='".$uid."' WHERE fld_exptestid='".$testid."'");
                }
                
                $countmis = $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_master WHERE fld_id='".$testid."' AND fld_ass_type='2' ");//AND fld_delstatus='1'
		
                if($countmis != 0){ 
                    $ObjDB->NonQuery("UPDATE itc_mistest_toogle SET fld_status='3', fld_updated_date='".$date."', 
		                 fld_updated_by='".$uid."' WHERE fld_mistestid='".$testid."'");
                }
		/***Mohan M**/
		echo "success";
	}

	/*------OPen response ------*/
if($oper == "openresponse" and $oper != '')
{		
$studid = isset($method['studid']) ? $method['studid'] : '0';
$assesmentid = isset($method['assesmentid']) ? $method['assesmentid'] : '0';


			$questions=$ObjDB->QueryObject("SELECT a.fld_question as question,b.fld_answer as answer, b.fld_correct_answer as crtanswer, b.fld_question_id as 					quesid,b.fld_id as answerid,d.fld_id as assesid,d.fld_score as fullscore,d.fld_total_question as totalqn
                                    FROM itc_question_details as a 
                                    LEFT JOIN itc_test_student_answer_track as b on a.fld_id=b.fld_question_id
				    LEFT JOIN itc_test_master as d ON d.fld_id = b.fld_test_id
                                    LEFT JOIN itc_test_questionassign as c on a.fld_id=c.fld_question_id
                                    WHERE  a.fld_delstatus='0' AND b.fld_student_id='".$studid."'
                                    AND b.fld_answer_type_id='15' AND b.fld_test_id='".$assesmentid."' AND b.fld_delstatus='0'  AND c.fld_delstatus='0' AND d.fld_delstatus = '0'  GROUP BY b.fld_question_id ORDER BY b.fld_question_id");

/*   starts to show partial resuls
created By : vijayalakshmi php programmer
created Date : 1/12/2014

*/

?>
<script language="JavaScript" type="text/javascript">
function minmax(value, min, max) 
{
    if(parseInt(value) < 0 || isNaN(value)) 
        return 0; 
    else if(parseInt(value) > max) 
        return max; 
    else return value;
}
$('#tablecontents2').slimscroll({
			height:'auto',
			railVisible: false,
			allowPageScroll: false,
			railColor: '#F4F4F4',
			opacity: 9,
			color: '#88ABC2',
                        wheelStep: 1
		});
</script>
<div class='row rowspacer'>
    <div class='span10 offset1' id="queslist">
        <table class='table table-hover table-striped table-bordered setbordertopradius'>
            <thead class='tableHeadText'>
                <tr>
                    <th width="1%">#</th>
                    <th width="21%" class='centerText'>Question</th>
                    <th width="40%" class='centerText'>Answer</th>
                    <th width="18%" class='centerText'>Result</th>
		    <th width="10%" class='centerText'>Points Earned</th>
 		    <th width="10%" class='centerText'>Points Possible</th>
                </tr>
            </thead>
        </table>

	<form name="pointearnform" id="pointearnform" method="post" action="">
    <div style="max-height:400px;width:100%;" id="tablecontents2" >
              <table style="margin-bottom:0px;" class='table table-hover table-striped table-bordered bordertopradiusremove' id="tblTransactions">
            <tbody>
                <?php
                if($questions->num_rows > 0){
                        $i=1;
                        while($row=$questions->fetch_assoc()){
                        extract($row);
				$eachqn_score = $fullscore/$totalqn;

                                if($eachqn_score == "") {
					$qrypartialfor_randam= $ObjDB->QueryObject("SELECT B.fld_qn_assign as totalqn, B.fld_pct_section as totalmark FROM 												itc_test_student_answer_track as A LEFT JOIN itc_test_random_questionassign AS B 												ON A.fld_tag_id = B.fld_id WHERE A.fld_question_id='".$quesid."' AND 												A.fld_test_id = '".$assesid."'");
					$rowqry_partialfor_randam = $qrypartialfor_randam->fetch_assoc();
					extract($rowqry_partialfor_randam);

					$eachqn_score = $totalmark/$totalqn;
			}
                        ?>	
                        <tr id="<?php echo $i;?>" name="<?php echo $question;?>">
                                <td width="1%" style="text-align: center!important;" id="que"><?php echo $i; ?></td> 
				<td width="21%" class="getquestnid<?php echo $i; ?>"><?php echo $question;?>
					<input type="hidden" name="questin" id="question" class="question" value="<?php echo $quesid; ?>">
                                </td>         	                              
				<td width="40%" onclick="removesections('#test_testassign'); fn_showsingleanspage('<?php echo $studid?>','<?php echo $quesid; ?>','<?php echo $answerid; ?>');"><?php echo $answer; ?></td>
				<td width="18%" class='centerText' ><div>
                                        <input type="button" onclick="fn_selectansw('right','<?php echo $studid?>','<?php echo $quesid; ?>','<?php echo $i; ?>','<?php echo $eachqn_score; ?>');" id="PAR1_<?php echo $quesid; ?>" value="Full Credit" class="bluer_light"/>

 					<input type="button" onclick="fn_selectansw('partial','<?php echo $studid?>','<?php echo $quesid; ?>','<?php echo $i; ?>','echo $eachqn_score;');" id="PAR3_<?php echo $quesid; ?>" value="Partial Credit" class="bluep_light" title=""/>

                                        <input type="button" onclick="fn_selectansw('wrong','<?php echo $studid?>','<?php echo $quesid; ?>','<?php echo $i; ?>','0');" id="PAR2_<?php echo $quesid; ?>" value="No Credit" class="bluew_light" /></div>
                                </td>
                                <td width="10%" class="colptearned<?php echo $i; ?>" style="text-align: center!important;"><input type="text" class ="" id="numberbox<?php echo $i; ?>"  style="-moz-box-sizing: border-box; width: 80%; text-align:center;" value="<?php if($crtanswer==1){ echo $eachqn_score;}else{ echo $crtanswer;}?>" onkeyup="this.value = minmax(this.value, 0, <?php echo $eachqn_score; ?>)" style="text-align:center;" name="numberbox<?php echo $i; ?>" disabled /></td> 
				<td width="10%" class="colptpossble<?php echo $i; ?>" style="text-align: center!important;"><input type="text" name="" class="" id="ptpossible<?php echo $i; ?>" style="-moz-box-sizing: border-box; width: 80%; text-align:center;" value="<?php echo $eachqn_score; ?>" disabled style="text-align:center;"/></td> 
   
                       </tr>
                 <?php
                         $i++;
				}   // while ends
			    }  // if ends
                        ?>	

                </tbody>
            </table>
       </div>

	</form>

    </div>
</div>
<br/>
	<div class="tRight">
		<input class="darkButton" type="button" onclick="fn_cancel('test');" value="Cancel" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;">
		<input class="darkButton" type="button" onclick="fn_saveassessmentscore('<?php echo $i; ?>','<?php echo $studid; ?>');" value="Save" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;">

	</div>
<?php
	
}

/*---- select result answer for points earned ---*/
	if($oper == "selectresulttoanswer" and $oper != '')
{		
    $buttonid = isset($method['buttid']) ? $method['buttid'] : '0';
    $studid = isset($method['stuid']) ? $method['stuid'] : '0';
    $quesid = isset($method['quesid']) ? $method['quesid'] : '0';
    if($buttonid == 'right')
    {
        $buttonid=1;
    }
		elseif($buttonid == 'wrong') {
         $buttonid=0;
    }
		else  {
			$buttonid=2;
		}

	    $ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_result_flag='".$buttonid."' WHERE fld_student_id='".$studid."' 
				AND fld_answer_type_id='15' AND fld_delstatus='0' AND fld_question_id='".$quesid."'");
    echo "success";
	}
                
/* save the open resonse answers  ***/
	if($oper == "saveopenresponse" and $oper != '')
	{
	$studid = isset($method['stuid']) ? $method['stuid'] : '0';
	$quesid = isset($method['quesid']) ? $method['quesid'] : '0';
	$pts_earned = isset($method['pts_earned']) ? $method['pts_earned'] : '0';
	$pts_possible = isset($method['pts_possible']) ? $method['pts_possible'] : '0';
	$totcnt = isset($method['totcnt']) ? $method['totcnt'] : '0';
	$quesid=explode(",",$quesid);
	$pts_earned=explode(",",$pts_earned);
	$pts_possible=explode(",",$pts_possible);

	 for($i=0;$i<$totcnt;$i++) {

		$resutl_flag = $ObjDB->SelectSingleValueInt("SELECT fld_result_flag FROM itc_test_student_answer_track 
		                                        WHERE fld_student_id='".$studid."' AND fld_answer_type_id='15' AND fld_delstatus='0' AND fld_question_id='".$quesid[$i]."'"); 
		if($pts_earned[$i] == "") {
			$openflag = '0';
			$correctanswer = 0;
		}
		else
		{

              if($resutl_flag == '2'){
		$correctanswer = $pts_earned[$i];
}
              elseif($resutl_flag == '1'){
		$correctanswer = 1;
	      }
	      else{
		$correctanswer = 0;
	      }


			$openflag = '1';
		}


 			$ObjDB->NonQuery("UPDATE itc_test_student_answer_track SET fld_correct_answer='".$correctanswer."', fld_open_flag='".$openflag."' WHERE fld_student_id='".$studid."' AND  fld_answer_type_id='15' AND fld_delstatus='0' AND fld_question_id='".$quesid[$i]."'");
	 }
	 echo "success";
	}

/* show the selected open resonse answers   ***/
	if($oper == "show_ind_answer" and $oper != '')
	{
	$studid = isset($method['studid']) ? $method['studid'] : '0';
	$quesid = isset($method['questionid']) ? $method['questionid'] : '0';
	$answerid = isset($method['answerid']) ? $method['answerid'] : '0';

	$chk_ansid= $ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_test_teacher_comment WHERE fld_answer_id='".$answerid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'");

	if($chk_ansid > 0)
	{
	$chk_updateddate=$ObjDB->QueryObject("SELECT fld_id as commentid FROM itc_test_teacher_comment WHERE fld_answer_id='".$answerid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'");

	 while($row=$chk_updateddate->fetch_assoc()){
		                extract($row);
		$trackdate_update= $ObjDB->SelectSingleValue("SELECT fld_updated_date FROM itc_test_student_answer_track WHERE fld_id='".$answerid."'");

		$commentdate_update= $ObjDB->SelectSingleValue("SELECT fld_trackupdated_date FROM itc_test_teacher_comment WHERE fld_id='".$commentid."' AND fld_answer_id='".$answerid."' AND fld_created_by='".$uid."' AND fld_delstatus='0'");

		if($trackdate_update != $commentdate_update) {
		$ObjDB->NonQuery("UPDATE itc_test_teacher_comment SET fld_delstatus='1' WHERE fld_answer_id='".$answerid."' AND fld_id='".$commentid."'AND fld_created_by='".$uid."'");
		}

	 }

		echo "success~".$answerid;
	}
	else
	{
		echo "fail~0";
	}
	}

/* save the comments for individual student answer   ***/
	if($oper == "savecommented" and $oper != '')
	{
	    $studentid = isset($method['studentid']) ? $method['studentid'] : '0';
	    $id = isset($method['id']) ? $method['id'] : '0';
	    $top = isset($method['top']) ? $method['top'] : '0'; 
	    $left = isset($method['left']) ? ($method['left']) : '0';
	    $idname = isset($method['idname']) ? $method['idname'] : ''; 
	    $classname = isset($method['classname']) ? ($method['classname']) : ''; 
	    $commenttext = isset($method['commenttext']) ? ($method['commenttext']) : ''; 
	    $cmtpost = htmlentities($commenttext, ENT_QUOTES, 'UTF-8');
 	    $change_cmtpost = preg_replace('/%u([a-fA-F0-9]{4})/', '&#x\\1;', $cmtpost);
  
   	    $commentdescription = $ObjDB->EscapeStrAll($change_cmtpost);
	    $split_top =  substr($top, 0, -2);
	    $split_left =  substr($left, 0, -2);

		$view_selectedposition = $ObjDB->QueryObject("SELECT fld_id as commentid,fld_left as leftpt,fld_top as toppt,fld_tool_classname as classname FROM 									itc_test_teacher_comment WHERE fld_answer_id='".$id."' AND fld_top='".$split_top."' AND 								fld_left='".$split_left."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND 									fld_delstatus='0'");

	if($view_selectedposition->num_rows > 0){

	$rowsqry = $view_selectedposition->fetch_assoc();
	extract($rowsqry);
	$split_idname = substr($idname,-1);

	$ObjDB->NonQuery("UPDATE itc_test_teacher_comment SET fld_comment_text='".$commentdescription."', fld_tool_classname='".$classname."', 					fld_updated_by='".$uid."', fld_updated_date='".$date."' WHERE fld_id='".$commentid."' ");

	}
	else {


	$chk_updateddate= $ObjDB->SelectSingleValue("SELECT fld_updated_date FROM itc_test_student_answer_track WHERE fld_id='".$id."'");

	    $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_teacher_comment(fld_answer_id, fld_left,fld_top, fld_student_id, fld_answer_type_id, fld_comment_text, 							fld_tool_classname, fld_tool_idname, fld_created_by, fld_created_date, fld_trackupdated_date)
						  VALUES('".$id."','".$left."','".$top."','".$studentid."','15','".$commentdescription."','".$classname."','".$idname."','".$uid."','".date('Y-m-d H:i:s')."','".$chk_updateddate."')");
	}
echo "success";
	}

	if($oper == "delete_ind_answer" and $oper != '')
	{
	    $studentid = isset($method['studid']) ? $method['studid'] : '0';
	    $xpos = isset($method['xpos']) ? $method['xpos'] : '0';
	    $ypos = isset($method['ypos']) ? $method['ypos'] : '0'; 
	    $id = isset($method['answerid']) ? ($method['answerid']) : '0';
	$split_left =  substr($xpos, 0, -2);
	    $split_top =  substr($ypos, 0, -2);
	    $view_dragposition = $ObjDB->QueryObject("SELECT fld_id as commentid,fld_left as leftpt,fld_top as toppt,fld_tool_classname as classname FROM itc_test_teacher_comment WHERE fld_answer_id='".$id."' AND fld_top='".$split_top."' AND fld_left='".$split_left."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND fld_delstatus='0'");

	if($view_dragposition->num_rows > 0){
		$rowsqry = $view_dragposition->fetch_assoc();
		extract($rowsqry);
		$ObjDB->NonQuery("UPDATE itc_test_teacher_comment SET fld_delstatus = '1' WHERE fld_id='".$commentid."' ");

	}   
	}	

	if($oper == "get_content_text" and $oper != '')
	{
	    $studentid = isset($method['studid']) ? $method['studid'] : '0';
	    $xpos = isset($method['xpos']) ? $method['xpos'] : '0';
	    $ypos = isset($method['ypos']) ? $method['ypos'] : '0'; 
	    $id = isset($method['answerid']) ? ($method['answerid']) : '0';
	    $idname = isset($method['idname']) ? $method['idname'] : '';
	    $classname = isset($method['classname']) ? $method['classname'] : '';
	    $split_left =  substr($xpos, 0, -2);
	    $split_top =  substr($ypos, 0, -2);
	   
	
	     $getcontent_text = $ObjDB->SelectSingleValue("SELECT fld_comment_text FROM itc_test_teacher_comment WHERE fld_answer_id='".$id."' AND fld_top='". $split_top."' AND fld_left='".$split_left."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND fld_delstatus='0'"); 

		if($getcontent_text == '')
		{
			echo "fail~".'';
		}
		else {
			echo "success~".$getcontent_text;
		}
	}

	if($oper == "delete_ind_commenttext" and $oper != '')
	{
	    $studentid = isset($method['studid']) ? $method['studid'] : '0';
	    $xpos = isset($method['xpos']) ? $method['xpos'] : '0';
	    $ypos = isset($method['ypos']) ? $method['ypos'] : '0'; 
	    $id = isset($method['answerid']) ? ($method['answerid']) : '0';
            $tblid=isset($method['id']) ? ($method['id']) : '0';
	$split_left =  substr($xpos, 0, -2);
	    $split_top =  substr($ypos, 0, -2);

            $ObjDB->NonQuery("UPDATE itc_test_teacher_comment SET fld_delstatus = '1' WHERE fld_id='".$tblid."'");

	}   

	if($oper == "savedrop_ind_tag" and $oper != '')
	{
	    $studentid = isset($method['studid']) ? $method['studid'] : '0';
	    $xpos = isset($method['xpos']) ? $method['xpos'] : '0';
	    $ypos = isset($method['ypos']) ? $method['ypos'] : '0'; 
	    $id = isset($method['answerid']) ? ($method['answerid']) : '0';
            $classname = isset($method['classname']) ? ($method['classname']) : ''; 
            $idname = isset($method['idname']) ? $method['idname'] : ''; 
            $split_left =  substr($xpos, 0, -2);
	    $split_top =  substr($ypos, 0, -2);
	
		$view_selectedposition = $ObjDB->QueryObject("SELECT fld_id as commentid,fld_left as leftpt,fld_top as toppt,fld_tool_classname as classname FROM 									itc_test_teacher_comment WHERE fld_answer_id='".$id."' AND fld_top='".$split_top."' AND 								fld_left='".$split_left."' AND fld_student_id='".$studentid."' AND fld_answer_type_id='15' AND 									fld_delstatus='0'");

	if($view_selectedposition->num_rows > 0){

	}
	else {

	$chk_updateddate= $ObjDB->SelectSingleValue("SELECT fld_updated_date FROM itc_test_student_answer_track WHERE fld_id='".$id."'");

	    $id=$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_test_teacher_comment(fld_answer_id, fld_left,fld_top, fld_student_id, fld_answer_type_id, fld_tool_classname, fld_tool_idname, fld_created_by, fld_created_date, fld_trackupdated_date)
						  VALUES('".$id."','".$split_left."','".$split_top."','".$studentid."','15','".$classname."','".$idname."','".$uid."','".date('Y-m-d H:i:s')."','".$chk_updateddate."')");
	}
	   echo $id;
	}	
		
        if($oper=="openresponsestudent")
        {
            $testid = isset($method['assesmentid']) ? $method['assesmentid'] : '0';
         ?>
             <div class="row">
                    <div class="four columns">

                        <div class="row rowspacer">
                            <div class="wizardReportDesc">Students List:</div>
                            <?php 

$qrystudetails = $ObjDB->QueryObject("SELECT CONCAT(b.fld_lname,' ',b.fld_fname)AS studentname, a.fld_student_id as stuid, b.fld_username as username 
                                                                            FROM itc_test_student_answer_track as a 
                                                                            LEFT JOIN itc_user_master as b on a.fld_student_id=b.fld_id 
                                                                            LEFT JOIN itc_class_student_mapping as c on b.fld_id=c.fld_student_id
                                                                            LEFT JOIN itc_test_questionassign as d on d.fld_question_id=a.fld_question_id
                                                                            WHERE  a.fld_answer_type_id='15' AND a.fld_test_id='".$testid."' AND a.fld_delstatus='0' AND c.fld_createdby='".$uid."' AND d.fld_delstatus='0' GROUP BY stuid ORDER BY studentname ASC"); 
                            
                                    ?>
                                    <dl class='field row'>   
                                        <dt class='dropdown'>
                                            <style>
                                                .dropdown .caret1
                                                {

                                                    float: left;
                                                    margin-top: 10px;
                                                }
                                            </style>   
                                            <div class="selectbox">
                                                <input type="hidden" name="selectstu" id="selectstu" value="<?php echo $stuid; ?>" /><!--  -->
                                                    <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                                        <span class="selectbox-option input-medium" data-option=""  style="width:248px;">Select Student</span>
                                                        <b class="caret1"></b>
                                                    </a>
                                                    <div class="selectbox-options">
                                                         <input type="text" class="selectbox-filter" placeholder="Search Student ">
                                                         <ul role="options" style="width:270px;">
                                                             <?php 
                                                                 if($qrystudetails->num_rows>0){
                                                                     $j=1;
                                                                     while($rowstudetails = $qrystudetails->fetch_assoc())
                                                                     {
                                                                     extract($rowstudetails);
                                                                     $stuname=$studentname;
                                                                         if(strlen($stuname)>50){ $tempstuname = substr(strip_tags($stuname),0,50)."..."; } else { $tempstuname =strip_tags($stuname);}
                                                             ?>
                                                             <li><a tabindex="-1" href="#" data-option="<?php echo $stuid;?>" onclick="fn_showopenresponseques(<?php echo $stuid;?>)"><?php echo $tempstuname; ?></a></li>
                                                             <?php
                                                                 $j++;
                                                                      }
                                                                 }
                                                                 else
                                                                 { ?>
                                                                 <div class="wizardReportData">No Students</div><?php
                                                                 }
                                                             ?>
                                                         </ul>
                                                     </div>
                                            </div>
                                        </dt>                                       
                                    </dl>                                 
                        </div>   
                    </div>
                 </div> 
         <?php
        }
        
        
        if($oper=="showproducts" and $oper != '')
        {
            
            $contentid = isset($method['contentid']) ? $method['contentid'] : '0';
            $productid = isset($method['productid']) ? $method['productid'] : '0';
            
            if($contentid==1)
            {
                $qry=$ObjDB->QueryObject("SELECT a.fld_id AS productid, CONCAT(a.fld_exp_name, ' ', b.fld_version) AS productname 
                                                          FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'  
							  ORDER BY a.fld_exp_name ASC");
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_exp_name, ' ', b.fld_version) AS productname 
                                                          FROM itc_exp_master AS a 
							  LEFT JOIN itc_exp_version_track AS b ON b.fld_exp_id = '".$productid."'
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'  
							  ORDER BY a.fld_exp_name ASC");
                }
            }
            else if($contentid==2)
            {
                $qry = $ObjDB->QueryObject("SELECT b.`fld_id` AS productid,  CONCAT(b.`fld_ipl_name`,' ',a.`fld_version`) AS 
						   productname FROM `itc_ipl_master` AS b
					LEFT JOIN `itc_ipl_version_track` a ON b.`fld_id`=a.`fld_ipl_id` 
					WHERE a.`fld_delstatus` = '0' AND a.`fld_zip_type`='1' AND b.`fld_delstatus`='0' 
					GROUP BY b.`fld_id` ORDER BY b.`fld_ipl_name` ASC");
                
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT CONCAT(b.`fld_ipl_name`,' ',a.`fld_version`) AS 
						   productname FROM `itc_ipl_master` AS b
					LEFT JOIN `itc_ipl_version_track` a ON b.`fld_id`='".$productid."' 
					WHERE a.`fld_delstatus` = '0' AND a.`fld_zip_type`='1' AND b.`fld_delstatus`='0' 
					GROUP BY b.`fld_id` ORDER BY b.`fld_ipl_name` ASC");
                }
            }
            else if($contentid==3)
            {
                $qry =$ObjDB->QueryObject("SELECT   a.fld_id AS productid, CONCAT(a.fld_module_name, ' ', b.fld_version) AS productname
                                                          FROM itc_module_master AS a 
							  LEFT JOIN itc_module_version_track AS b  ON fld_mod_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_module_type<>'7'
							  ORDER BY a.fld_module_name ASC ");
                
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name, ' ', b.fld_version) AS productname
                                                          FROM itc_module_master AS a 
							  LEFT JOIN itc_module_version_track AS b  ON fld_mod_id = '".$productid."'
							  WHERE a.fld_delstatus = '0' AND  b.fld_delstatus = '0' AND a.fld_module_type<>'7'
							  ORDER BY a.fld_module_name ASC");
                }
            }
            else if($contentid==4)
            {
                $qry =$ObjDB->QueryObject("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS productname,
				   	   a.fld_id AS productid 
                                        FROM itc_mathmodule_master  a 
                                        LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
                                        WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' ORDER BY a.fld_mathmodule_name ASC ");
                
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS productname
				   	   
                                        FROM itc_mathmodule_master  a 
                                        LEFT JOIN itc_module_version_track b ON  a.fld_module_id = b.fld_mod_id
                                        WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id='".$productid."' ORDER BY a.fld_mathmodule_name ASC");
                }
            }
            else if($contentid==5)
            {
                $qry =$ObjDB->QueryObject("SELECT a.fld_id AS productid, CONCAT(a.fld_mis_name, ' ', b.fld_version) AS productname
                                                          FROM itc_mission_master AS a 
							  LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id = a.fld_id 
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'
							  ORDER BY a.fld_mis_name ASC ");
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT  CONCAT(a.fld_mis_name, ' ', b.fld_version) AS productname
                                                          FROM itc_mission_master AS a 
							  LEFT JOIN itc_mission_version_track AS b ON b.fld_mis_id = '".$productid."'
							  WHERE a.fld_delstatus = '0' AND b.fld_delstatus = '0'
							  ORDER BY a.fld_mis_name ASC ");
                }
            }
            else if($contentid==6)
            {
                $qry =$ObjDB->QueryObject("SELECT b.fld_id AS productid,CONCAT(b.fld_pd_name,' ',a.fld_version) AS 
                                                                    productname FROM itc_pd_master AS b
                                                                    LEFT JOIN itc_pd_version_track AS a ON b.fld_id=a.fld_pd_id 
                                                                    WHERE a.fld_delstatus = '0' AND a.fld_zip_type='1' AND b.fld_delstatus='0'
                                                                    GROUP BY b.fld_id ORDER BY b.fld_pd_name ASC");
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT  CONCAT(b.fld_pd_name,' ',a.fld_version) AS 
                                                                    productname FROM itc_pd_master AS b
                                                                    LEFT JOIN itc_pd_version_track AS a ON b.fld_id='".$productid."' 
                                                                    WHERE a.fld_delstatus = '0' AND a.fld_zip_type='1' AND b.fld_delstatus='0'
                                                                    GROUP BY b.fld_id ORDER BY b.fld_pd_name ASC ");
                }
            }
            else if($contentid==7)
            {
                $qry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS productname,a.fld_id AS productid FROM itc_module_master AS a WHERE a.fld_delstatus='0' AND a.fld_module_type='7' ORDER BY a.fld_module_name ASC");
                
                if($productid!='' and $productid!='0')
                {
                    $productname=$ObjDB->SelectSingleValue("SELECT CONCAT(a.fld_module_name,' ',(SELECT fld_version FROM itc_module_version_track WHERE fld_mod_id=a.fld_id AND fld_delstatus='0')) AS productname FROM itc_module_master AS a WHERE a.fld_delstatus='0' AND a.fld_module_type='7' AND a.fld_id='".$productid."' ORDER BY a.fld_module_name ASC ");
                }
            }
            
            ?>
                
                        Select Product<span class="fldreq">*</span>
                            <dl class='field row'>
                                <dt class='dropdown'>
                                    <div class="selectbox">
                                        <input type="hidden" name="ddlproducts" id="ddlproducts" value="<?php echo $productid; ?>">
                                      <a class="selectbox-toggle" tabindex="10" role="button" data-toggle="selectbox" href="#">
                                          <span class="selectbox-option input-medium" data-option="<?php if($productid==0){ echo 0;}else{ echo $productid;} ?>"><?php if($productid==0){ echo "Select Product"; } else { echo $productname; } ?></span>
                                        <b class="caret1"></b>
                                      </a>
                                      <div class="selectbox-options">
                                        <input type="text" class="selectbox-filter" placeholder="Search Product">
                                        <ul role="options">
                                            <?php
                                                  if($qry->num_rows>0)
                                                  {
                                                      while($row=$qry->fetch_assoc())
                                                      {
                                                          extract($row);
                                                      ?>
                                                      <li><a tabindex="-1" href="#" data-option="<?php echo $productid;?>"  ><?php echo $productname; ?></a></li>
                                                      <?php
                                                      }
                                                  }
                                            ?>
                                        </ul>
                                      </div>
                                    </div>
                                </dt>
                            </dl>
                        
            <?php
        }
		

	@include("footer.php");