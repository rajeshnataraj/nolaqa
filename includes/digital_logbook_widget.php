<?php
/**
 * Created by PhpStorm.
 * User: Raymond
 * Date: 2017-08-01
 * Time: 10:35 AM
 */

function display_digital_logbook_widget($classid)
{
    global $ObjDB;
    $get_class_schedules_query = "SELECT 
                                                                a.fld_id AS sid, 
                                                                b.fld_expedition_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                17 AS stype, 
                                                                'Expedition Schedule' AS typename,
                                                                '' as wcalock, 
                                                                a.fld_class_id AS classid,
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_rotation_expschedule_mastertemp AS a 
                                                                LEFT JOIN itc_class_rotation_expschedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=b.fld_expedition_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid
													    UNION ALL 
                                                            SELECT 
                                                                a.fld_id AS sid, 
                                                                a.fld_exp_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                15 AS stype,
                                                                'Whole Class Assignment - Expedition' AS typename, 
                                                                a.fld_lock as wcalock,  
                                                                a.fld_class_id AS classid, 
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_indasexpedition_master AS a 
                                                                LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=a.fld_exp_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid";
    $query_logbook_object = $ObjDB->QueryObject($get_class_schedules_query);

    if($query_logbook_object->num_rows > 0) {
        ?>

        <div class="six columns">
            <div>
                <dl class='field row'>
                    <div class="selectbox">
                        <a class="selectbox-toggle" role="button" data-toggle="selectbox" href="#">
                                            <span class="selectbox-option input-medium" data-option=""
                                                  style="width:97%">Select Digital Logbook Schedule</span>
                            <b class="caret1"></b>
                        </a>
                        <div class="selectbox-options">
                            <input type="text" class="selectbox-filter"
                                   placeholder="Select Digital Logbook Schedule">
                            <ul role="options" style="width:100%">
                                <?php
                                //The class id is given in the variable $classid;

                                $get_class_schedules_query = "SELECT 
                                                                a.fld_id AS sid, 
                                                                b.fld_expedition_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                17 AS stype, 
                                                                'Expedition Schedule' AS typename,
                                                                '' as wcalock, 
                                                                a.fld_class_id AS classid,
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_rotation_expschedule_mastertemp AS a 
                                                                LEFT JOIN itc_class_rotation_expschedulegriddet AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=b.fld_expedition_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid
													    UNION ALL 
                                                            SELECT 
                                                                a.fld_id AS sid, 
                                                                a.fld_exp_id AS expid,
                                                                a.fld_schedule_name AS sname,
                                                                15 AS stype,
                                                                'Whole Class Assignment - Expedition' AS typename, 
                                                                a.fld_lock as wcalock,  
                                                                a.fld_class_id AS classid, 
                                                                a.fld_startdate AS startdate, 
                                                                a.fld_enddate AS enddate, 
                                                                COUNT(a.fld_id) AS scount 
                                                            FROM 
                                                                itc_class_indasexpedition_master AS a 
                                                                LEFT JOIN itc_class_exp_student_mapping AS b ON b.fld_schedule_id=a.fld_id 
                                                                LEFT JOIN itc_exp_master AS e ON e.fld_id=a.fld_exp_id 
                                                            WHERE 
                                                                a.fld_class_id='$classid' AND a.fld_delstatus='0' AND b.fld_flag='1' 
                                                                AND e.fld_thinksp != '0' AND e.fld_thinksp IS NOT NULL 
                                                            GROUP BY sid";
                                $query_object = $ObjDB->QueryObject($get_class_schedules_query);
                                while ($row = $query_object->fetch_assoc()) {
                                    ?>
                                    <li>
                                        <a href="library/thinkscapeTeacher.php?sid=<?= intval($row['sid']) ?>&stype=<?= intval($row['stype']) ?>&classid=<?= $classid ?>"
                                           onclick="window.open(this.href)"><?= $row['sname'] ?></a>
                                    </li>

                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
        <?php
    }
}