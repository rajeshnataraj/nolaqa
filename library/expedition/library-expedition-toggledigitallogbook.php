<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

@include("sessioncheck.php");

$expedition_enabled = false;

$id = isset($method['id']) ? $method['id'] : '';
$id=explode(",",$id);

$exp_id = intval($id[0]);
//$_POST['id'] = expedition id


//$teacher_id is the user id of the logged in teacher or teacher admin
$teacher_id = intval($_SESSION['userid']);

$school_id = intval($_SESSION['schoolid']);

function get_logbook_enabled($exp_id, $teacher_id, $school_id){
    global $ObjDB;
    //The fld_ts_toggle status is supposed to be the same for all resources for each teacher and expedition pair, so taking the value of the first resource should be representative of the status
    $logbook_enabled_query = "select fld_toggle_status from itc_digital_logbook_toggle_status where fld_exp_id = $exp_id and fld_teacher_id = $teacher_id and fld_school_id = $school_id";
    $logbook_enabled_query_object = $ObjDB->QueryObject($logbook_enabled_query);

    $logbook_enabled_row = $logbook_enabled_query_object->fetch_assoc();

    $logbook_enabled = $logbook_enabled_row['fld_toggle_status'];

    if ($logbook_enabled == null|| $logbook_enabled == ''){
        //By default, the logbook is enabled
        $logbook_enabled = true;
    }

    return $logbook_enabled;
}

$logbook_enabled = get_logbook_enabled($exp_id, $teacher_id, $school_id);

?>

<section data-type='2home' id='library-expedition-toggledigitallogbook'>
    <div class="container">
        <div class="row">
            <p class="lightTitle">Toggle Digital Logbook</p>
            <p class="lightSubTitle">&nbsp;</p>
        </div>
        <div class="row">
            <table style="margin-bottom:0px;" class='table table-striped table-bordered bordertopradiusremove'>
                <thead class='tableHeadText'>
                <tr>
                    <th class='centerText' style="width: 25%">Status</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="position: relative;">
                            <div style="position: relative; display: table; width: 100%; height: 100%;">
                                <div style="vertical-align: middle; position: absolute; top: 25%;">
                                    <input name="radio_toggle_digital_logbook" id="radio_toggle_digital_logbook_enabled" value="1" type="radio" <?php if ($logbook_enabled){ print "checked=\"checked\"";}?> >
                                    <label class="radio" for="radio_toggle_digital_logbook_enabled" style="cursor: default;display: inline; font-size:14px">
                                        Enabled
                                    </label>
                                </div>
                                <div style="vertical-align: middle; position: absolute; left: 50%; top: 25%;">
                                    <input name="radio_toggle_digital_logbook" id="radio_toggle_digital_logbook_disabled" value="1" type="radio" <?php if (!$logbook_enabled){ print "checked=\"checked\"";}?>>
                                    <label class="radio" for="radio_toggle_digital_logbook_disabled" style="cursor: default;display: inline; font-size:14px">
                                        Disabled
                                    </label>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row rowspacer">
            <div class="tRight"></div>
                <input type="button" class="darkButton" style="width:200px; height:42px;float:right;margin-bottom:20px;margin-right:20px;" value="Save Status" onClick="fn_toggledigitallogbook(<?= $exp_id ?>);" />
            </div>
        </div>
    </div>
</section>

<?php
@include("footer.php");