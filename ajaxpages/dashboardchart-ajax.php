<?php
@include('sessioncheck.php');

//continue only if $_POST is set and it is a Ajax request
if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
	
	//Get page number from Ajax POST
	if(isset($_POST["page"])){
		$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
		if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
	}else{
		$page_number = 1; //if there's no page number, set it to 1
	}
        
        $item_per_page=3;

        //get total number of records from database for pagination
	$results = $ObjDB->SelectSingleValueInt("SELECT count(*)
                                            FROM itc_class_master 
                                            WHERE fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                                                    AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
                                                    AND fld_flag='1'))");
	//break records into pages
	$total_pages = ceil($results/$item_per_page);
	
	//get starting position to fetch the records
	$page_position = (($page_number-1) * $item_per_page);
    ?>
    <style>
        .meter { 
            height: 13px;  /* Can be anything */
            position: relative;
            margin: 0 17px -7px -67px; /* Just for demo spacing */
            background: #555;
            -moz-border-radius: 25px;
            -webkit-border-radius: 25px;
            border-radius: 25px;           
            -webkit-box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
            -moz-box-shadow   : inset 0 -1px 1px rgba(255,255,255,0.3);
            box-shadow        : inset 0 -1px 1px rgba(255,255,255,0.3);
        }
        .meter > span {
            display: block;
            height: 100%;
               -webkit-border-top-right-radius: 8px;
            -webkit-border-bottom-right-radius: 8px;
                   -moz-border-radius-topright: 8px;
                -moz-border-radius-bottomright: 8px;
                       border-top-right-radius: 8px;
                    border-bottom-right-radius: 8px;
                -webkit-border-top-left-radius: 20px;
             -webkit-border-bottom-left-radius: 20px;
                    -moz-border-radius-topleft: 20px;
                 -moz-border-radius-bottomleft: 20px;
                        border-top-left-radius: 20px;
                     border-bottom-left-radius: 20px;
            background-color: rgb(43,194,83);

            background-image: -moz-linear-gradient(
              center bottom,
              rgb(43,194,83) 37%,
              rgb(84,240,84) 69%
             );
        }
        .orange > span {
                background-color: #f1a165;
                background-image: -moz-linear-gradient(top, #f1a165, #f36d0a);
                background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f1a165),color-stop(1, #f36d0a));
                background-image: -webkit-linear-gradient(#f1a165, #f36d0a); 
        }
        .red > span {
                background-color: #f0a3a3;
                background-image: -moz-linear-gradient(top, #f0a3a3, #FF6666);
                background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0, #f0a3a3),color-stop(1, #FF6666));
                background-image: -webkit-linear-gradient(#f0a3a3, #FF6666);
        }
       
    </style>
    <div style="text-align: left;  font-size: 18px; font-weight: bold;  margin:-31px 0px 9px 61px;" class="icon-synergy-edit">&nbsp;Class Progress</div><!--- margin:-16px 0px 8px 61px; -->

    <?php
    $c=0;
       /********Get Class details code start here********/
    $qryclass = $ObjDB->QueryObject("SELECT fld_class_name AS classname, fn_shortname(fld_class_name,2) AS shortname, fld_id AS classid, fld_lab AS classtypeid, 
                                                            fld_step_id AS stepid, fld_flag AS flag 
                                                    FROM itc_class_master 
                                                    WHERE fld_delstatus='0' AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
                                                            AND (fld_created_by='".$uid."' OR fld_id IN(SELECT fld_class_id FROM itc_class_teacher_mapping WHERE fld_teacher_id='".$uid."' 
                                                            AND fld_flag='1'))  LIMIT $page_position, $item_per_page");
    if($qryclass->num_rows>0){
        while($rowclass = $qryclass->fetch_assoc())
        {

            extract($rowclass); 
            
            
            if (strlen($shortname) <= '12') {         
 $sclsname=substr($shortname, 0, 8) . '...';
            } else {
           $sclsname=substr($shortname, 0, 10) . '...';
             
            }
            $fclsname=$classname;

            $noofsession=0;  $totalmodincls=0; $iplflag=0; $expflag=0; $modflag=0; $finalresult=0; $noofwcasession=0;  $wcamodflag=0;

            $qryschedules = $ObjDB->QueryObject("SELECT a.fld_id AS sid,  a.fld_schedule_name AS sname, 1 AS stype, 'IPL - IPL' AS typename, b.fld_unit_id AS lessonid,  
                                                        a.fld_class_id AS classid, a.fld_start_date AS startdate, a.fld_end_date AS enddate, '' AS scount
                                                        FROM itc_class_sigmath_master AS a 
                                                        LEFT JOIN itc_class_sigmath_unit_mapping AS b ON a.fld_id = b.fld_sigmath_id 
                                                        LEFT JOIN itc_unit_master AS c ON c.fld_id = b.fld_unit_id 
                                                        WHERE a.fld_class_id = '".$classid."' AND a.fld_flag = '1' AND a.fld_delstatus = '0' AND b.fld_flag = '1' AND c.fld_activestatus = '0' 
                                                          AND c.fld_delstatus = '0' GROUP BY sid
                                                    UNION ALL 													
                                                SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,a.fld_scheduletype AS stype, (CASE WHEN a.fld_scheduletype=2 
                                                        THEN 'Module Schedule' WHEN a.fld_scheduletype=6 THEN 'Math Module Schedule' END) AS typename,'' as lessonid, a.fld_class_id AS classid,
                                                        a.fld_startdate AS startdate, a.fld_enddate AS enddate, '' AS scount
                                                    FROM itc_class_rotation_schedule_mastertemp AS a LEFT JOIN itc_class_rotation_schedule_student_mappingtemp AS b 
                                                            ON b.fld_schedule_id=a.fld_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                    GROUP BY sid
                                                    UNION ALL 
                                                    SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,5 AS stype,'Whole Class Assignment' AS typename, a.fld_moduletype as lessonid,a.fld_class_id AS classid, 
                                                            a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                                                    FROM itc_class_indassesment_master AS a LEFT JOIN itc_class_indassesment_student_mapping AS b ON b.fld_schedule_id=a.fld_id
                                                    WHERE a.fld_class_id='".$classid."' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                    GROUP BY sid
                                                UNION ALL 
                                                SELECT a.fld_id AS sid, a.fld_schedule_name AS sname,15 AS stype,'Whole Class Assignment - Expedition' AS typename, a.fld_exp_id AS lessonid,  a.fld_class_id AS classid, 
                                                        a.fld_startdate AS startdate, a.fld_enddate AS enddate, COUNT(a.fld_id) AS scount 
                                                    FROM itc_class_indasexpedition_master AS a LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id
                                                    WHERE a.fld_class_id='".$classid."'  AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                    GROUP BY sid");
            $totalresources=0; $totalnofstu=0; $noofrescompleted=0; $expfinaloutput=0; $totalnoofresources=0; $nofstuandlessons=0;$totalstuanscount=0; $finalresult=0; $sesscompleted = 0;$finalmodaverage=0;
             $wcasesscompleted=0; $wcafinalecamodaverage=0;
            if($qryschedules->num_rows!=0)
            {   /********Schedule If start here**********/                                         
                while($rowschedule=$qryschedules->fetch_assoc())
                { /********Schedule loop start here**********/            
                    extract($rowschedule);
                    $colors=array("","orange ","red ");

                    /**********IPL code start here************/
                    if($stype == '1')
                    {
                                /*****Total no of student in the schedule********/
                                $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                              FROM itc_class_sigmath_student_mapping 
                                                                              WHERE fld_sigmath_id='".$sid."' AND fld_flag='1'");


                                 /*****Total no of lessons in the schedule********/
                                $qrylessonscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                   FROM itc_class_sigmath_lesson_mapping 
                                                                                   WHERE fld_sigmath_id='".$sid."' AND fld_flag='1'");

                                $nofstuandlessons+=($studentcount*$qrylessonscount);

                                /***** student answered lessons************/
                                $qrystuanswercount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                        FROM itc_assignment_sigmath_master 
                                                                                        WHERE fld_schedule_id='".$sid."' AND fld_class_id='".$classid."' AND fld_status<>'0' AND fld_delstatus='0'");
                                $totalstuanscount+=$qrystuanswercount;
                                $iplflag='1';
                    }
                    /**********IPL code start here************/


                    /***********Expedition Code Start Here**********/
                    if($stype == '15')
                    { 
                           /*****Total no of student in the schedule********/
                           $studentcount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                           FROM itc_class_exp_student_mapping 
                                                                                           WHERE fld_schedule_id='".$sid."' 
                                                                                                   AND fld_flag='1'");
                           $totalnofstu+=$studentcount;

                           $totalresources = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_resource_master As a
                                                                                           WHERE a.fld_exp_id = '".$lessonid."' AND a.fld_resource_status='1' AND a.fld_delstatus='0'");


                            $totalnoofresources=$totalnofstu*$totalresources;


                            $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id AS studentids, CONCAT(b.fld_fname,' ',b.fld_lname) AS studentname FROM itc_class_exp_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id WHERE a.fld_schedule_id='".$sid."' AND a.fld_flag='1' ORDER BY b.fld_lname");						
                            if($qrystudents->num_rows>0)
                            {
                                while($rowstudents=$qrystudents->fetch_assoc())
                                {
                                    extract($rowstudents);
                                    $qryfortotalnofres = $ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_exp_res_play_track As a
                                                                                       WHERE a.fld_exp_id='".$lessonid."' AND a.fld_student_id='".$studentids."' AND fld_schedule_type='15' 
                                                                                       AND fld_read_status='1' AND a.fld_delstatus='0'");
                                    $noofrescompleted+=$qryfortotalnofres;
                                     $expflag='1';
                                }
                            }
                    } 
                    /***********Expedition Code End Here**********/    



                    /*********Module And Math Module code start Here***********/           
                    if($stype==2 or $stype==6)
                    { 
                        if($stype==2)
                        {
                            $schtype='1';
                        }
                        else
                        {
                            $schtype='4';
                        }

                        /***************Total No of Student In the Schedule************/
                            $totalnoofstuinsch=$ObjDB->SelectSingleValueInt("SELECT count(a.fld_id) FROM itc_class_rotation_schedulegriddet AS a
                                                                            LEFT JOIN itc_class_rotation_schedule_mastertemp AS b ON a.fld_schedule_id=b.fld_id
                                                                             where a.fld_class_id='".$classid."' AND a.fld_flag='1' AND b.fld_delstatus='0'");

                        /***************Total No of module In the Schedule************/
                           $totalnoofmodinsch=$ObjDB->SelectSingleValueInt("SELECT count(fld_id) FROM itc_class_rotation_schedule_module_mappingtemp Where fld_schedule_id='".$sid."' AND fld_flag='1';");
                           $totalmodincls+=$totalnoofmodinsch;

                        /*****Student for schedule code start here*******/
                            $qrystu = $ObjDB->QueryObject("SELECT fld_student_id as studid FROM itc_class_rotation_schedule_student_mappingtemp 
                                                              WHERE fld_schedule_id = '".$sid."' and fld_flag ='1'");
                            if($qrystu->num_rows!=0)
                            {   /********Schedule If start here**********/                                         
                                while($rowstu=$qrystu->fetch_assoc())
                                { /********Schedule loop start here**********/            
                                    extract($rowstu);                                   

                                    /***********Get Module Id*************/
                                    $qrydetails = "SELECT a.fld_module_id AS ids, CONCAT(b.fld_module_name,' ',c.fld_version) AS modunnames, 
                                                             a.fld_rotation AS rotation, d.fld_startdate AS startdate, d.fld_enddate AS enddate 
                                                     FROM itc_class_rotation_schedulegriddet AS a 
                                                     LEFT JOIN itc_class_rotation_scheduledate AS d ON a.fld_schedule_id=d.fld_schedule_id AND a.fld_rotation=d.fld_rotation
                                                     LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
                                                     LEFT JOIN itc_module_version_track AS c ON b.fld_id=c.fld_mod_id 
                                                     WHERE a.fld_class_id='".$classid."' AND a.fld_schedule_id='".$sid."' AND a.fld_student_id='".$studid."' 
                                                             AND a.fld_flag='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND d.fld_flag='1'
                                                     ORDER BY a.fld_startdate";//LIMIT ".$start.",".$end."


                                    $qryschedulesmod = $ObjDB->QueryObject($qrydetails);

                                    if($qryschedulesmod->num_rows > 0)
                                    { 	
                                        while($rowschedules=$qryschedulesmod->fetch_assoc())
                                        {
                                            extract($rowschedules);

                                            $totalchapters = 7;
                                            $newmodid = $ids;

                                            for($i=0;$i<$totalchapters;$i++)
                                            {	
                                                $sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$ids."' AND fld_student_id='".$studid."' AND fld_schedule_type='".$schtype."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_type<>'0'");

                                                $viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$ids."' AND fld_tester_id='".$studid."' AND fld_schedule_type='".$schtype."' AND fld_section_id='".$i."' AND fld_delstatus='0'");

                                                $totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");

                                                $totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");

                                                if($sesscount==$totalsess && $viewedpages>=$totalpages)
                                                        $sesscompleted++;
                                            }
                                        } //while loop end here for $rowschedules
                                    }  /***********Get Module Id*************/
                                }//while loop end here $rowstu
                            }
                        /*****Student for schedule end here*******/                         
                            $noofsession=($totalmodincls*7)*$totalnoofstuinsch;
                            $finalmodaverage=round(($sesscompleted/$noofsession)*100,2);
                            $modflag='1';
                    }
                    /*********Module And Math Module code start Here***********/    

                    
                    
                    
                    
                    /*********Ind Module And Math Module code start Here***********/           
                    if($stype=='5')
                    {
                        if($lessonid==1){
                            $moduletype='1';
                        }
                        else if($lessonid==2){
                            $moduletype='2';
                            $wcasesscompleted=0;
                        }
                        /***************Total No of Student In the Schedule************/
                        $totalnoofstuinsch=$ObjDB->SelectSingleValueInt("SELECT count(a.fld_student_id) FROM itc_class_indassesment_student_mapping AS a 
                                                                        LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id 
                                                                        WHERE a.fld_schedule_id='".$sid."' AND a.fld_flag='1' ");  
                        /***************Total No of module In the Schedule************/

                        $totalnoofmodinsch=$ObjDB->SelectSingleValueInt("SELECT count(fld_module_id) FROM itc_class_indassesment_master
                                                                                           WHERE fld_id='".$sid."' AND fld_delstatus='0' AND fld_moduletype='".$moduletype."'");



                        $qrystudents = $ObjDB->QueryObject("SELECT a.fld_student_id as studid FROM itc_class_indassesment_student_mapping AS a LEFT JOIN itc_user_master AS b ON b.fld_id=a.fld_student_id WHERE a.fld_schedule_id='".$sid."' AND a.fld_flag='1' ORDER BY b.fld_lname");						
                        if($qrystudents->num_rows>0)
                        {   /********Schedule If start here**********/      
                            while($rowstudents=$qrystudents->fetch_assoc())
                            {
                                extract($rowstudents);   

                                /***********Get Module Id*************/

                                $qrydetails = $ObjDB->QueryObject("SELECT a.fld_module_id AS ids, a.fld_moduletype as mtype, (CASE WHEN a.fld_moduletype=1 
                                                                            THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=a.fld_module_id) 
                                                                            WHEN a.fld_moduletype=2 
                                                                            THEN (SELECT fld_mathmodule_name FROM itc_mathmodule_master WHERE fld_id=a.fld_module_id) 
                                                                            WHEN a.fld_moduletype=7 
                                                                            THEN (SELECT fld_module_name FROM itc_module_master WHERE fld_id=a.fld_module_id) 
                                                                            WHEN a.fld_moduletype=17 
                                                                            THEN (SELECT fld_contentname FROM itc_customcontent_master WHERE fld_id=a.fld_module_id) 
                                                                            END) AS modulename 
                                                                    FROM itc_class_indassesment_master AS a 
                                                                    WHERE a.fld_id='".$sid."' AND a.fld_delstatus='0'");
                                if($qrydetails->num_rows>0)
                                {
                                    while($rowschedules=$qrydetails->fetch_assoc())
                                    {
                                        extract($rowschedules);

                                        if($mtype==1)
                                        {
                                            $schtype='5';
                                            $newmodid = $ids;
                                        }
                                        else if($mtype==2)
                                        {
                                            $schtype='6';
                                            $newmodid = $ObjDB->SelectSingleValueInt("SELECT fld_module_id 
                                                                                                FROM itc_mathmodule_master 
                                                                                                WHERE fld_id='".$ids."'");

                                            $qrydays = $ObjDB->QueryObject("SELECT fld_ipl_day1, fld_session_day1, fld_session_day2, fld_ipl_day2 
                                                                                                                                                FROM itc_mathmodule_master 
                                                                                                                                                WHERE fld_id='".$ids."' AND fld_delstatus='0'"); 
                                            $rowqrydays =$qrydays->fetch_assoc();	
                                            extract($rowqrydays);

                                            $session1 = $fld_session_day1;
                                            $session2 = $fld_session_day2;

                                            $ipl1 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                     FROM itc_assignment_sigmath_master 
                                                                                                                     WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$studid."' 
                                                                                                                     AND fld_status<>'0' AND fld_test_type='5' AND fld_delstatus='0' AND fld_lesson_id IN (".$fld_ipl_day1.")"); 
                                            $ipl2 = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
                                                                                                                     FROM itc_assignment_sigmath_master 
                                                                                                                     WHERE fld_class_id='".$classid."' AND fld_schedule_id='".$sid."' AND fld_student_id='".$studid."' 
                                                                                                                     AND fld_status<>'0' AND fld_test_type='5' AND fld_delstatus='0' AND fld_lesson_id IN (".$fld_ipl_day2.")"); 

                                            if($ipl1==4)
                                                    $iplcount1 = 1;
                                            if($ipl2==4)
                                                    $iplcount2 = 1;
                                        }
                                        $totalchapters = 7;

                                        for($i=0;$i<$totalchapters;$i++)
                                        {	
                                            $sesscount = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_points_master WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$ids."' AND fld_student_id='".$studid."' AND fld_schedule_type='".$schtype."' AND fld_preassment_id='0' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_type<>'0'");

                                            $viewedpages = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_play_track WHERE fld_schedule_id='".$sid."' AND fld_module_id='".$ids."' AND fld_tester_id='".$studid."' AND fld_schedule_type='".$schtype."' AND fld_section_id='".$i."' AND fld_delstatus='0'");

                                            $totalpages = $ObjDB->SelectSingleValueInt("SELECT fld_points_possible FROM itc_module_performance_master WHERE fld_module_id='".$newmodid."' AND fld_session_id='".$i."' AND fld_delstatus='0' AND fld_performance_name='Total Pages'");

                                            $totalsess = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM itc_module_performance_master WHERE (fld_performance_name = 'Attendance' OR fld_performance_name = 'Participation') AND fld_module_id='".$newmodid."' AND fld_delstatus='0' AND fld_session_id='".$i."'");

                                            if($sesscount==$totalsess && $viewedpages>=$totalpages)
                                                    $wcasesscompleted++;
                                        }
                                    }
                                }
                            }
                        }
                        /********Schedule If start here**********/      
                        if($mtype==2){
                            $totalchapters=9;
                            $wcasesscompleted=$wcasesscompleted+$iplcount1+$iplcount2;
                        }                        

                        $noofwcasession=($totalnoofmodinsch*$totalchapters)*$totalnoofstuinsch;
                       
                        $wcafinalecamodaverage+=round(($wcasesscompleted/$noofwcasession)*100,2);
                        $wcamodflag='1';                                         
                        $wcasesscompleted=0;
                        $iplcount1=0;$iplcount2=0;
                    } //type id=5
                    /********* Ind Module And Math Module code start Here***********/ 
                    
                } /********Schedule loop End here**********/

                $iplfinaloutput=round(($totalstuanscount/$nofstuandlessons)*100,2);
                $expfinaloutput=round(($noofrescompleted/$totalnoofresources)*100,2);
                
                $totaldivide=$iplflag+$expflag+$modflag+$wcamodflag;

                $finalresult=round(($iplfinaloutput+$expfinaloutput+$finalmodaverage+$wcafinalecamodaverage)/$totaldivide,2);
             
            } /********Schedule If End here**********/  

	echo '<table class="contents"><tr>';
	echo '<td>';    
        ?>

        <div style="width:455px;">
            <div style="width:110px; font: 15px/20px Arial, Helvetica, sans-serif;margin:10px 28px 0px 54px; cursor:pointer;">
                <label class="tooltip"  style="cursor:pointer;" title="<?php echo $fclsname; ?>" onclick="fn_showclassdetail('<?php echo $classid; ?>');"><?php echo $sclsname; ?></label> 
            </div>
            <div style='margin:-16px -12px 18px 220px;' onclick="fn_showclassdetail('<?php echo $classid; ?>');"><!--- -17 -->
                <div class="meter <?php echo $colors[$c]; ?>" style="cursor:pointer;">
                    <span style="width: <?php echo $finalresult."%"; ?>"></span>
                </div>
            </div>
            <div style="width:20px; margin:-38px 108px -14px 457px;">
                <label title="<?php echo round($finalresult)."%"; ?>"><?php echo round($finalresult)."%"; ?></label> 
            </div>
        </div>
        <?php
		$c++;	
		
        echo '</td>';
	echo '</tr></table>';
	} /********Class loop End here**********/
    } /********Class If End here**********/ 
	
	echo '<div align="right"; style="margin:13px 41px 0px 201px;">';//15
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	echo paginate_function($item_per_page, $page_number, $get_total_rows[0], $total_pages);
	echo '</div>';
}

################ pagination function #########################################
function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<table class="pagination"><tr>';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 3; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
        
       			$previous_link = ($previous==0)?1:$previous;
            $pagination .= '<td class="first"><a href="#" data-page="1" class="tooltip"  title="First">&laquo;</a></td>'; //first link         
                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                    if($i > 0){
                        $pagination .= '<td><a href="#" data-page="'.$i.'" class="tooltip" title="Page'.$i.'">'.$i.'</a></td>';
                    }
                }   
            $first_link = false; //set first link to false      
        
        if($first_link){ //if current active page is first link
            $pagination .= '<td class="first active">'.$current_page.'</td>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<td class="last active">'.$current_page.'</td>';
        }else{ //regular current link
            $pagination .= '<td class="active">'.$current_page.'</td>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<td><a href="#" data-page="'.$i.'"  class="tooltip"  title="Page '.$i.'">'.$i.'</a></td>';
            }
        }
        if($current_page < $total_pages){ 
				$next_link = ($i > $total_pages)? $total_pages : $i;             
                $pagination .= '<td class="last"><a href="#" data-page="'.$total_pages.'" class="tooltip"  title="Last">&raquo;</a></td>'; //last link
        }
        
        $pagination .= '</tr></table>'; 
    }
    return $pagination; //return pagination links
}
?>
<script>
        
    function fn_showclassdetail(clsid)
    {
        ajaxloadingalert();
        removesections('#home');
        setTimeout('showpages("class-class","class/class.php");',1000);
        setTimeout('showpageswithpostmethod("class-newclass-actions","class/newclass/class-newclass-actions.php","id='+clsid+'");',2000);
    }

</script>