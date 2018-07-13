<?php
@include("sessioncheck.php");

$date = date("Y-m-d H:i:s");
$oper = isset($method['oper']) ? $method['oper'] : '';

/* --- Load document dropdown --- */
if ($oper == "showdocuments" and $oper != " ") {
    $stid = isset($method['stid']) ? $method['stid'] : '';
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';

    $guiddb = $ObjDB->SelectSingleValue("SELECT fld_doc_id FROM itc_correlation_rpt_doc_mapping WHERE fld_cor_id='" . $rptid . "'");

    $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,1) as shortname, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='" . $stid . "' AND b.fld_sub_guid NOT IN(SELECT fld_doc_id from itc_correlation_rpt_doc_mapping where fld_cor_id='" . $rptid . "' and fld_flag='1') ");

    $docqrysec = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title,1) as shortname, a.fld_doc_guid AS docguid, b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title,1) as shortsubjname, b.fld_sub_year AS year, b.fld_sub_guid AS guid
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='" . $stid . "' AND b.fld_sub_guid IN(SELECT fld_doc_id from itc_correlation_rpt_doc_mapping where fld_cor_id='" . $rptid . "' and fld_flag='1') ");
    ?>

    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible11').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible12').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list5").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list5') {

                            fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
            $("#list6").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list6') {
                            fn_movealllistitems('list5', 'list6', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
        });
    </script>

    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Documents</div>
            <div class="dragWell" id="testrailvisible11" >
                <div id="list5" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list5" >
                        <dl class='field row'>
                            <dt class='text'>
                                <input placeholder='Search' type='text' id="list_5_search" name="list_5_search" onKeyUp="search_list(this, '#list5');" />
                            </dt>
                        </dl>
                    </div>
    <?php
    if ($docqry->num_rows > 0) {
        while ($docrow = $docqry->fetch_assoc()) {
            extract($docrow);
            $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            ?>
                            <div class="draglinkleft" id="list5_<?php echo $guid; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs; ?>" id="<?php echo $guid; ?>"><?php echo $shortname . " | " . $shortsubjname . " (" . $year . ")"; ?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems('list5', 'list6', 1, '<?php echo $guid; ?>');"></div>
                            </div>
            <?php
        }
    }
    ?>    
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list5', 'list6', 0);"  style="cursor: pointer;cursor:hand;width: 130px;float:right; ">add all documents</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected documents<span class="fldreq">*</span></div>
            <div class="dragWell" id="testrailvisible12">
                <div id="list6" class="dragleftinner droptrue3">

    <?php
    if ($docqrysec->num_rows > 0) {
        while ($docrowsec = $docqrysec->fetch_assoc()) {
            extract($docrowsec);
            $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            ?>
                            <div class="draglinkright" id="list6_<?php echo $guid; ?>" >
                                <input type="hidden" name="seldocument" id="seldocument" value="<?php echo $stddocs; ?>">
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs; ?>" id="<?php echo $guid; ?>"><?php echo $shortname . " | " . $shortsubjname . " (" . $year . ")"; ?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems('list5', 'list6', 1, '<?php echo $guid; ?>');"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>	
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list6', 'list5', 0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">remove all documents</div>
        </div>
    </div>


    <?php
}

/* --- Load document dropdown --- */
if ($oper == "showgrades" and $oper != " ") {



    $stid = isset($method['stid']) ? $method['stid'] : '';
    $stdid = isset($method['stdid']) ? $method['stdid'] : '';
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';
    $dimflag = '0';



    $stddocsdb = array();

    $grdguidqry = $ObjDB->QueryObject("SELECT a.fld_guid AS grdguids, a.fld_grade AS grdnames,fn_shortname(a.fld_grade,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
										b.fld_sub_title AS subjectname,b.fld_sub_year AS year
										FROM itc_correlation_rpt_std_grades AS a 
										left join itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
										WHERE a.fld_rpt_data_id='" . $rptid . "' AND a.fld_delstatus='0'");

    if ($grdguidqry->num_rows > 0) {
        while ($grdguidrow = $grdguidqry->fetch_assoc()) {
            extract($grdguidrow);
            $stddocsdb[$grdguids] = $grdnames . " | " . $subjectname . " (" . $year . ")~" . $shortgrdname . " | " . $shortsubjname . " (" . $year . ")";
        }
    }


    $grdqry = $ObjDB->QueryObject("SELECT a.fld_grade_guid AS gguid, a.fld_grade_name AS gradename 
			,fn_shortname(a.fld_grade_name,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
										b.fld_sub_title AS subjectname,b.fld_sub_year AS year,a.fld_gradeout_flag as gradeoutflag
										FROM itc_correlation_grades as a 
										left join itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
										WHERE b.fld_sub_guid IN (" . $stdid . ")");


    $stddocs = array();
    if ($grdqry->num_rows > 0) {
        while ($grdrow = $grdqry->fetch_assoc()) {
            extract($grdrow);
            $stddocs[$gguid] = $gradename . " | " . $subjectname . " (" . $year . ")~" . $shortgrdname . " | " . $shortsubjname . " (" . $year . ")~" . $gradeoutflag;
        }
    }
    ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible13').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible14').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list7").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list7') {

                            fn_movealllistitems('list7', 'list8', 1, $(this).children(":first").attr('id'));
                            fn_validategrade();
                        }
                    });
                }
            });
            $("#list8").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list8') {
                            fn_movealllistitems('list7', 'list8', 1, $(this).children(":first").attr('id'));
                            fn_validategrade();
                        }
                    });
                }
            });
        });
    </script>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Grades</div>
            <div class="dragWell" id="testrailvisible13" >
                <div id="list7" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list7" >
                        <dl class='field row'>
                            <dt class='text'>
                                <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this, '#list7');" />
                            </dt>
                        </dl>
                    </div>
    <?php
    foreach ($stddocs as $key => $val) {
        if (!array_key_exists($key, $stddocsdb)) {
            $val = explode("~", $val);
            ?>
                            <div class="draglinkleft<?php if ($val[2] == 1) {
                $dimflag = '1';
                echo " dim";
            } ?>" id="list7_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems('list7', 'list8', 1, '<?php echo $key; ?>');
                                                    fn_validategrade();"></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="dragAllLink<?php if ($dimflag == '1') {
                    echo " dim";
                } ?>" onclick="fn_movealllistitems('list7', 'list8', 0);
                            fn_validategrade();" style="cursor:pointer;cursor:hand;"
                 >add all grades</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected Grades<span class="fldreq">*</span></div>
            <div class="dragWell" id="testrailvisible14">
                <div id="list8" class="dragleftinner droptrue3">
    <?php
    foreach ($stddocs as $key => $val) {
        if (array_key_exists($key, $stddocsdb)) {
            $val = explode("~", $val);
            ?>
                            <div class="draglinkright" id="list8_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems('list7', 'list8', 1, '<?php echo $key; ?>');
                                                    fn_validategrade();"></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>	
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems('list8', 'list7', 0);
                            fn_validategrade();"  style="cursor: pointer;cursor:hand;width: 130px;float: right; ">remove all grades</div>
        </div>
    </div>


    <?php
}

/* --- Check Subject Name Duplication --- */
if ($oper == "checkreportname" and $oper != " ") {
    $rptid = isset($method['rptid']) ? $method['rptid'] : '0';
    $rpttitle = isset($method['txtrpttitle']) ? fnEscapeCheck($method['txtrpttitle']) : '';

    $count = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
											FROM itc_correlation_report_data 
											WHERE MD5(LCASE(REPLACE(fld_title,' ','')))='" . $rpttitle . "' AND fld_delstatus=0 AND fld_id<>'" . $rptid . "' AND fld_created_by='" . $uid . "'");
    if ($count == 0) {
        echo "true";
    } else {
        echo "false";
    }
}


/* --- Save Step1 Correlation Basic Info--- */
if ($oper == "savestep1" and $oper != " ") {
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $rpttitle = isset($method['rpttitle']) ? $ObjDB->EscapeStrAll($method['rpttitle']) : '';
    $ownerid = isset($method['ownerid']) ? $method['ownerid'] : '';
    $prepfor = isset($method['prepfor']) ? $ObjDB->EscapeStrAll($method['prepfor']) : '';
    $prepon = isset($method['prepon']) ? $method['prepon'] : '';
    $rptsytle = isset($method['rptsytle']) ? $method['rptsytle'] : '';
    $sec = isset($method['sec']) ? $method['sec'] : '';
    $secsep = explode(",", $sec);
    $selectschool = isset($method['selectschool']) ? $method['selectschool'] : ''; //changes	

    $rptchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
												FROM itc_correlation_report_data 
												WHERE MD5(LCASE(REPLACE(fld_title,' ','')))='" . fnEscapeCheck($rpttitle) . "' AND fld_delstatus=0 AND fld_id<>'" . $rptid . "' AND fld_created_by='" . $uid . "'");

    if ($rptchk == 0) {
        if ($rptid == 0) {
            $rptid = $ObjDB->NonQueryWithMaxValue("INSERT INTO itc_correlation_report_data (fld_title, fld_owner_id, fld_prepared_for, fld_prepared_on, fld_report_style, 
									fld_sec_std_add_summary, fld_sec_bench_add_summary, fld_sec_corr_by_std, fld_sec_corr_by_title, fld_sec_std_not_add,fld_sec_prod_description, 
									fld_created_by, fld_created_date,fld_step_id,fld_schoolid) 
								VALUES ('" . $rpttitle . "','" . $ownerid . "','" . $prepfor . "','" . $prepon . "','" . $rptsytle . "','" . $secsep[0] . "','" . $secsep[1] . "','" . $secsep[2] . "',
									'" . $secsep[3] . "','" . $secsep[4] . "','" . $secsep[5] . "','" . $uid . "','" . date("Y-m-d H:i:s") . "','1','" . $selectschool . "')");
        } else {
            $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_title='" . $rpttitle . "', fld_owner_id='" . $ownerid . "', fld_prepared_for='" . $prepfor . "', 
									fld_prepared_on='" . $prepon . "', fld_report_style='" . $rptsytle . "', fld_sec_std_add_summary='" . $secsep[0] . "', 
									fld_sec_bench_add_summary='" . $secsep[1] . "', fld_sec_corr_by_std='" . $secsep[2] . "', fld_sec_corr_by_title='" . $secsep[3] . "', 
									fld_sec_std_not_add='" . $secsep[4] . "',fld_sec_prod_description='" . $secsep[5] . "', fld_updated_by='" . $uid . "',fld_schoolid='" . $selectschool . "', fld_updated_date='" . date("Y-m-d H:i:s") . "',fld_step_id='1' 
								WHERE fld_id='" . $rptid . "'");
        }

        echo $rptid;
    } else {
        echo "invalid";
    }
}


/* --- Save Step2 Correlation Standard Info--- */
if ($oper == "savestep2" and $oper != " ") {
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $state = isset($method['state']) ? $method['state'] : 0;
    $documentid = isset($method['documentid']) ? $method['documentid'] : 0;
    $gradeids = isset($method['gradeids']) ? $method['gradeids'] : 0;

    $gradename = isset($method['gradename']) ? $method['gradename'] : 0;
    $standardname = isset($method['standardname']) ? $method['standardname'] : 0;



    $gids = explode(",", $gradeids);
    $gnames = explode("~", $gradename);
    $documentid = explode(",", $documentid);

    // step3 document mapping start

    $ObjDB->NonQuery("UPDATE itc_correlation_rpt_doc_mapping
						 SET fld_flag='0',fld_updateddate='" . date("Y-m-d H:i:s") . "',fld_updatedby='" . $uid . "' 
						 WHERE fld_cor_id='" . $rptid . "'");

    for ($i = 0; $i < sizeof($documentid); $i++) {


        $cnt = $ObjDB->SelectSingleValueInt("SELECT fld_id 
												FROM itc_correlation_rpt_doc_mapping 
												WHERE fld_cor_id='" . $rptid . "' AND fld_doc_id=" . $documentid[$i] . "");


        if ($cnt == 0) {



            $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,b.fld_sub_title AS subjectname, b.fld_sub_year AS year
										FROM itc_correlation_documents a
										LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
										WHERE  a.fld_authority_id='" . $state . "' AND b.fld_sub_guid=" . $documentid[$i] . "");

            if ($docqry->num_rows > 0) {
                $stddocs = '';
                $docrow = $docqry->fetch_assoc();
                extract($docrow);
                $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            }


            $ObjDB->NonQuery("INSERT INTO itc_correlation_rpt_doc_mapping(fld_cor_id,fld_doc_name, fld_doc_id,fld_std_body, fld_flag,fld_createddate,fld_createdby) 
																VALUES ('" . $rptid . "','" . $stddocs . "', " . $documentid[$i] . ",'" . $state . "','1','" . date('Y-m-d H:i:s') . "','" . $uid . "')");
        } else {
            $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,b.fld_sub_title AS subjectname,
											 b.fld_sub_year AS year
											FROM itc_correlation_documents a
											LEFT JOIN itc_correlation_doc_subject b ON a.fld_id=b.fld_doc_id
											WHERE  a.fld_authority_id='" . $state . "' AND b.fld_sub_guid=" . $documentid[$i] . "");

            if ($docqry->num_rows > 0) {
                $stddocs = '';
                $docrow = $docqry->fetch_assoc();
                extract($docrow);
                $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            }

            $ObjDB->NonQuery("UPDATE itc_correlation_rpt_doc_mapping 
								SET fld_flag='1',fld_updateddate='" . date("Y-m-d H:i:s") . "',fld_updatedby='" . $uid . "',
								fld_doc_name='" . $stddocs . "',fld_std_body='" . $state . "' 
								WHERE fld_cor_id='" . $rptid . "'  AND fld_doc_id=" . $documentid[$i] . " AND fld_id='" . $cnt . "'");
        }
    }




    $ObjDB->NonQuery("UPDATE itc_correlation_report_data 
						SET fld_std_body='" . $state . "', fld_step_id='2', fld_updated_by='" . $uid . "', 
						fld_updated_date='" . date("Y-m-d H:i:s") . "' 
						WHERE fld_id='" . $rptid . "'");

    $ObjDB->NonQuery("UPDATE itc_correlation_rpt_std_grades 
						SET fld_delstatus=1 
						WHERE fld_rpt_data_id='" . $rptid . "'");


    for ($i = 0; $i < sizeof($gids); $i++) {


        $gnam = $ObjDB->SelectSingleValue("SELECT fld_grade_name as name FROM itc_correlation_grades
												WHERE fld_grade_guid='" . $gids[$i] . "'");

        $gchk = $ObjDB->SelectSingleValueInt("SELECT COUNT(*) 
												FROM itc_correlation_rpt_std_grades 
												WHERE fld_rpt_data_id='" . $rptid . "' AND fld_guid='" . $gids[$i] . "'");




        if ($gchk == 0) {
            $ObjDB->NonQuery("INSERT INTO itc_correlation_rpt_std_grades (fld_rpt_data_id,fld_guid,fld_grade) 
								VALUES ('" . $rptid . "','" . $gids[$i] . "','" . $gnam . "')");
        } else {
            $ObjDB->NonQuery("UPDATE itc_correlation_rpt_std_grades 
								SET fld_delstatus=0 
								WHERE fld_rpt_data_id='" . $rptid . "' AND fld_guid='" . $gids[$i] . "'");
        }
    }


    for ($i = 0; $i < sizeof($documentid); $i++) {


        $grdqry = $ObjDB->QueryObject("SELECT fld_sub_id as subid,fld_grade_guid AS gguid, fld_grade_name AS gradename 
										FROM itc_correlation_grades
										WHERE fld_sub_id IN (SELECT fld_id FROM itc_correlation_doc_subject WHERE fld_sub_guid=" . $documentid[$i] . ")");

        if ($grdqry->num_rows > 0) {
            while ($grdrow = $grdqry->fetch_assoc()) {
                extract($grdrow);

                $ObjDB->NonQuery("UPDATE itc_correlation_rpt_std_grades 
								SET fld_sub_id='" . $subid . "'
								WHERE fld_rpt_data_id='" . $rptid . "' AND fld_guid='" . $gguid . "'");
            }
        }
    }


    echo $rptid;
}

if ($oper == "showproducts" and $oper != " ") {
    $type = isset($method['type']) ? $method['type'] : 0;
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $selectproducts = isset($method['selectproducts']) ? $method['selectproducts'] : 0;
    $selectproducts = explode(',', $selectproducts);
    $a = '0';

    if ($type == 0) {

        $productqry = $ObjDB->QueryObject("SELECT fld_id as pid,fn_shortname (CONCAT(fld_prd_name, ' ', fld_prd_version), 2) AS shortname, fld_prd_type as prdtype,fld_asset_id AS assetid
											FROM itc_correlation_productdetails WHERE  fld_delstatus='0' ");
    } else {

        $productqry = $ObjDB->QueryObject("SELECT fld_id as pid,fn_shortname (CONCAT(fld_prd_name, ' ', fld_prd_version), 2) AS shortname, fld_prd_type as prdtype,fld_asset_id AS assetid
                                                                                               FROM itc_correlation_productdetails WHERE fld_prd_type='$type' AND fld_delstatus='0' ");
    }

    $productdetails = array();
    //$productqry = $ObjDB->QueryObject($qry);
    if ($productqry->num_rows > 0) {
        $i = 0;
        while ($productqryrow = $productqry->fetch_assoc()) {
            extract($productqryrow);
            if ($assetid == '') {
                $assetid = 'MO.ENVM.3.0.0a';
            }
            $productdetails[] = array("id" => $pid, "nam" => $nam, "shortname" => $shortname, "type" => $prdtype, "productid" => $assetid);
            // print_r($productdetails);
            $i++;
        }
    }
    ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible13').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible14').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $("#list7").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list7') {
                            fn_movealllistitems('list7', 'list8', 1, $(this).attr('id').replace('list8_', ''));
                            fn_saveselect();
                            fn_validateproducts();
                        }
                    });
                }
            });

            $("#list8").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list8') {
                            fn_movealllistitems('list7', 'list8', 1, $(this).attr('id').replace('list7_', ''));
                            fn_saveselect();
                            fn_validateproducts();
                        }
                    });
                }
            });
        });
    </script>	


    <div class="dragndropcol">
        <div class="dragtitle">Products</div>
        <div class="dragWell" id="testrailvisible13" >
            <div id="list7" class="dragleftinner droptrue3">
                <div class="draglinkleftSearch" id="s_list7" >
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_7_search" name="list_7_search" onKeyUp="search_list(this, '#list7');" />
                        </dt>
                    </dl>
                </div>
    <?php
    $gnam = $ObjDB->QueryObject("SELECT fld_guid as guid FROM itc_correlation_rpt_std_grades
			       WHERE fld_rpt_data_id='" . $rptid . "' AND fld_delstatus='0'");
    if ($gnam->num_rows > 0) {
        $sguids = '';
        while ($grdrow = $gnam->fetch_assoc()) {
            extract($grdrow);


            if ($sguids == '') {
                $sguids = "'" . $guid . "'";
            } else {
                $sguids.="," . "'" . $guid . "'";
            }
        }
        $sguidsqry = " in (" . $sguids . ")";
    }

    $qrylessonunselect = $ObjDB->QueryObject("SELECT b.fld_asset_id as asstid FROM itc_correlation_productsgradeout as a
                                                                LEFT JOIN itc_correlation_productdetails as b ON b.fld_prd_asset_id = a.fld_productid
                                                                WHERE a.fld_standardguid$sguidsqry  AND fld_flag='0' GROUP BY a.fld_productid");
    $filter_greyout = array();
    while ($lessonunselect = $qrylessonunselect->fetch_assoc()) {
        extract($lessonunselect);
        array_push($filter_greyout, $asstid);
    }


    for ($i = 0; $i < sizeof($productdetails); $i++) {
        $bool = true;
        for ($j = 0; $j < sizeof($selectproducts); $j++) {
            $selctp = explode('_', $selectproducts[$j]);
            if ($selctp[0] == $productdetails[$i]['id'] and $selctp[1] == $productdetails[$i]['type']) {

                $bool = false;
            }
        }
        if ($bool) {
            $dimproduct = array_diff(array($productdetails[$i]['productid']), $filter_greyout);
            ?>
                        <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkleft<?php if (!empty($dimproduct)) {
                echo ' dim';
                $a = 1;
            } ?>" id="list7_<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $productdetails[$i]['id'] . "~" . $productdetails[$i]['type'] . "~" . $productdetails[$i]['productid']; ?>" title="<?php echo $productdetails[$i]['nam']; ?>"><?php echo $productdetails[$i]['shortname']; ?></div>
                            <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list7', 'list8', 1, '<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>'); fn_saveselect(); fn_validateproducts(); "></div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <div class="dragAllLink<?php if ($a == 1) {
                echo " dim";
            } ?>" onclick="fn_movealllistitemsproducts('list7', 'list8', 0);
                            fn_saveselect();
                            fn_validateproducts();"  style="cursor: pointer;cursor:hand;width: 130px;float: right;">add all products</div>
    </div>


                <?php
            }

            if ($oper == "showdestination" and $oper != " ") {
                $expeid = isset($method['expid']) ? $method['expid'] : 0;
                $selectproducts = isset($method['selectdestinations']) ? $method['selectdestinations'] : 0;
                $selectproducts = explode(',', $selectproducts);
                $rptid = isset($method['reportid']) ? $method['reportid'] : '';

                $expids = explode(',', $expeid);
                ?> 
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible0').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });


            $("#list21").sortable({
                connectWith: ".droptrue1",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list21') {
                            fn_movealllistitems('list21', 'list22', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

        });
    </script>                     

    <div class="dragndropcol">

    <?php
    ?>
        <div class="dragtitle">Destinations available</div>
        <div class="draglinkleftSearch" id="s_list21" >
            <dl class='field row'>
                <dt class='text'>
                    <input placeholder='Search' type='text' id="list_21_search" name="list_21_search" onKeyUp="search_list(this, '#list21');" />
                </dt>
            </dl>
        </div>
        <div class="dragWell" id="testrailvisible0" >
            <div id="list21" class="dragleftinner droptrue1">
    <?php
    for ($d = 0; $d < sizeof($expids); $d++) {

        $expeditionids = explode('~', $expids[$d]);

        $qrydest = $ObjDB->QueryObject("SELECT  a.fld_id AS destid, a.fld_dest_name AS destname, fn_shortname (CONCAT(a.fld_dest_name), 2)AS shortname,1 as desttype
                                                                FROM itc_exp_destination_master AS a 
                                                WHERE a.fld_id NOT IN( SELECT b.fld_prd_sys_id FROM itc_correlation_rpt_products AS c   
                                                LEFT JOIN itc_correlation_products AS b ON b.fld_id=c.fld_prd_id
                                                WHERE c.fld_rpt_data_id='" . $rptid . "' AND c.fld_exptype='1' AND c.fld_delstatus='0') AND a.fld_exp_id='" . $expeditionids[0] . "' 
                                                 AND a.fld_delstatus='0'  ORDER BY a.fld_order");
        if ($qrydest->num_rows > 0) {
            while ($rowsdest = $qrydest->fetch_assoc()) {
                extract($rowsdest);

                $bool = true;
                for ($j = 0; $j < sizeof($selectproducts); $j++) {
                    $selctp = explode('_', $selectproducts[$j]);

                    if ($selctp[0] == $destid and $selctp[1] == $desttype) {

                        $bool = false;
                    }
                }
                if ($bool) {
                    ?>
                                <div class="draglinkleft" id="list21_<?php echo $destid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $destid . "~" . $desttype . "~" . $destprdid; ?>" title="<?php echo $destname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $destid; ?>" onclick="fn_movealllistitems('list21', 'list22', 1,<?php echo $destid; ?>);"></div>
                                </div>
                    <?php
                }
            }
        }
    }
    ?>
            </div>
        </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list21', 'list22', 0, 0);" style="cursor: pointer;cursor:hand;width:  137px;float: right;">add all destinations</div>
    </div>        


                <?php
            }

            if ($oper == "showtasks" and $oper != " ") {
                $destidsall = isset($method['destids']) ? $method['destids'] : '';
                $rptid = isset($method['reportid']) ? $method['reportid'] : '';
                $expeid = isset($method['expid']) ? $method['expid'] : 0;
                $destid = explode(',', $destidsall);

                $selectproducts = isset($method['selecttasks']) ? $method['selecttasks'] : 0;
                $selectproducts = explode(',', $selectproducts);
                ?> 
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible2').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list23").sortable({
                connectWith: ".droptrue2",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list23') {
                            fn_movealllistitems('list23', 'list24', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });

        });
    </script>  


    <div class="dragndropcol">

        <div class="dragtitle">Tasks available</div>
        <div class="draglinkleftSearch" id="s_list23" >
            <dl class='field row'>
                <dt class='text'>
                    <input placeholder='Search' type='text' id="list_23_search" name="list_23_search" onKeyUp="search_list(this, '#list23');" />
                </dt>
            </dl>
        </div>
        <div class="dragWell" id="testrailvisible2" >
            <div id="list23" class="dragleftinner droptrue2">
    <?php
    for ($i = 0; $i < sizeof($destid); $i++) {
        $qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS taskid, a.fld_task_name AS taskname,fn_shortname (CONCAT(a.fld_task_name), 2) AS shortname,2 as tasktype
                                                                    FROM itc_exp_task_master AS a  
                                                                    WHERE a.fld_id NOT IN( SELECT b.fld_prd_sys_id FROM itc_correlation_rpt_products AS c   
                                                                    LEFT JOIN itc_correlation_products AS b ON b.fld_id=c.fld_prd_id
                                                                    WHERE c.fld_rpt_data_id='" . $rptid . "' AND c.fld_exptype='2' AND c.fld_delstatus='0') AND a.fld_dest_id='" . $destid[$i] . "'
                                                                     AND a.fld_delstatus='0'  AND a.fld_flag='1' ORDER BY a.fld_order ");


        if ($qrystudent->num_rows > 0) {
            while ($rowsstudent = $qrystudent->fetch_assoc()) {
                extract($rowsstudent);


                $bool = true;
                for ($j = 0; $j < sizeof($selectproducts); $j++) {
                    $selctp = explode('_', $selectproducts[$j]);

                    if ($selctp[0] == $destid and $selctp[1] == $desttype) {

                        $bool = false;
                    }
                }
                if ($bool) {
                    ?>
                                <div class="draglinkleft" id="list23_<?php echo $taskid; ?>" >
                                    <div class="dragItemLable tooltip" id="<?php echo $taskid . "~" . $tasktype . "~" . $taskprdid; ?>" title="<?php echo $taskname; ?>"><?php echo $shortname; ?></div>
                                    <div class="clickable" id="clck_<?php echo $taskid; ?>" onclick="fn_movealllistitems('list23', 'list24', 1,<?php echo $taskid; ?>);"></div>
                                </div>
                    <?php
                }
            }
        }
    }
    ?>
            </div>
        </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list23', 'list24', 0, 0);" style="cursor: pointer;cursor:hand;width:  120px;float: right;">add all Tasks</div>
    </div>



                <?php
            }

            if ($oper == "showresources" and $oper != " ") {
                $taskidall = isset($method['taskids']) ? $method['taskids'] : '';
                $expeid = isset($method['expid']) ? $method['expid'] : 0;
                $rptid = isset($method['reportid']) ? $method['reportid'] : '';
                $taskid = explode(',', $taskidall);

                $selectproducts = isset($method['selectres']) ? $method['selectres'] : 0;
                $selectproducts = explode(',', $selectproducts);
                ?> 
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible4').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $("#list25").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list25') {
                            fn_movealllistitems('list25', 'list26', 1, $(this).children(":first").attr('id'));
                        }
                    });
                }
            });


        });
    </script>  


    <div class="dragndropcol">
        <div class="dragtitle">Resources available</div>
        <div class="draglinkleftSearch" id="s_list25" >
            <dl class='field row'>
                <dt class='text'>
                    <input placeholder='Search' type='text' id="list_25_search" name="list_25_search" onKeyUp="search_list(this, '#list25');" />
                </dt>
            </dl>
        </div>
        <div class="dragWell" id="testrailvisible4" >
            <div id="list25" class="dragleftinner droptrue3">
    <?php
    for ($j = 0; $j < sizeof($taskid); $j++) {

        $qrycount = $ObjDB->SelectSingleValue("SELECT COUNT(a.fld_id) FROM itc_exp_resource_master AS a 
                                                                     LEFT JOIN itc_exp_res_status AS b on a.fld_id = b.fld_res_id                                                      
                                                                     WHERE a.fld_task_id='" . $taskid[$j] . "' AND a.fld_flag='1' AND a.fld_delstatus='0' AND b.fld_school_id = '" . $schoolid . "' AND b.fld_created_by='" . $uid . "' ORDER BY a.fld_order");


        if ($qrycount != 0) {


            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname,3 as restype,a.fld_expres_id as resprdid
                                                                        FROM itc_exp_resource_master AS a 
                                                                        LEFT JOIN itc_exp_res_status AS e on a.fld_id = e.fld_res_id   
                                                                        WHERE a.fld_id NOT IN( SELECT b.fld_prd_sys_id FROM itc_correlation_rpt_products AS c   
                                                                        LEFT JOIN itc_correlation_products AS b ON b.fld_id=c.fld_prd_id
                                                                        WHERE c.fld_rpt_data_id='" . $rptid . "' AND c.fld_exptype='3' AND c.fld_delstatus='0') AND a.fld_task_id='" . $taskid[$j] . "'
                                                                         AND a.fld_delstatus='0'  AND a.fld_flag='1' AND e.fld_status='1' AND e.fld_school_id = '" . $schoolid . "' AND e.fld_created_by='" . $uid . "' ORDER BY a.fld_order ");
        } else {

            $qrystudent = $ObjDB->QueryObject("SELECT a.fld_id AS resoid, a.fld_res_name AS resoname, fn_shortname (CONCAT(a.fld_res_name), 2) AS shortname,3 as restype,a.fld_expres_id as resprdid
                                                                   FROM itc_exp_resource_master AS a 
                                                                   LEFT JOIN itc_exp_res_status AS e on a.fld_id = e.fld_res_id   
                                                                   WHERE a.fld_id NOT IN( SELECT b.fld_prd_sys_id FROM itc_correlation_rpt_products AS c   
                                                                   LEFT JOIN itc_correlation_products AS b ON b.fld_id=c.fld_prd_id
                                                                   WHERE c.fld_rpt_data_id='" . $rptid . "' AND c.fld_exptype='3' AND c.fld_delstatus='0') AND a.fld_task_id='" . $taskid[$j] . "'
                                                                    AND a.fld_delstatus='0'  AND a.fld_flag='1' AND e.fld_status='1' AND e.fld_school_id = '0' ORDER BY a.fld_order ");
        }


        $gnam = $ObjDB->QueryObject("SELECT fld_guid as guid FROM itc_correlation_rpt_std_grades
			       WHERE fld_rpt_data_id='" . $rptid . "' AND fld_delstatus='0'");
        if ($gnam->num_rows > 0) {
            $sguids = '';
            while ($grdrow = $gnam->fetch_assoc()) {
                extract($grdrow);


                if ($sguids == '') {
                    $sguids = "'" . $guid . "'";
                } else {
                    $sguids.="," . "'" . $guid . "'";
                }
            }
            $sguidsqry = " in (" . $sguids . ")";
        }

        $qrylessonunselect = $ObjDB->QueryObject("SELECT b.fld_prd_id as asstid FROM itc_correlation_productsgradeout as a
                                                                LEFT JOIN itc_correlation_products as b ON b.fld_prd_asset_id = a.fld_productid
                                                                WHERE a.fld_standardguid$sguidsqry GROUP BY a.fld_productid");
        $filter_greyout = array();
        while ($lessonunselect = $qrylessonunselect->fetch_assoc()) {
            extract($lessonunselect);
            array_push($filter_greyout, $asstid);
        }



        if ($qrystudent->num_rows > 0) {
            while ($rowsstudent = $qrystudent->fetch_assoc()) {
                extract($rowsstudent);

                $dimproduct = array_diff(array($resprdid), $filter_greyout);
                ?>
                            <div class="draglinkleft<?php if (!empty($dimproduct)) {
                    echo ' dim';
                    $a = 1;
                } ?>" id="list25_<?php echo $resoid; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $resoid . "~" . $restype . "~" . $resprdid; ?>" title="<?php echo $resoname; ?>"><?php echo $shortname; ?></div>
                                <div class="clickable" id="clck_<?php echo $resoid; ?>" onclick="fn_movealllistitems('list25', 'list26', 1,<?php echo $resoid; ?>);"></div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <div class="dragAllLink"  onclick="fn_movealllistitems('list25', 'list26', 0, 0);" style="cursor: pointer;cursor:hand;width:  220px;float: right;">add all Resources</div>
    </div>



                <?php
            }

            /*
             * starts for selecting production by tag types
             */
            if ($oper == "showtagproducts" and $oper != " ") {
                $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
                $selecttagproducts = isset($method['selecttagproducts']) ? $method['selecttagproducts'] : 0;
                $selecttagproducts = explode(',', $selecttagproducts);
                $a = 0;

                if ($sessmasterprfid == 2 or $sessmasterprfid == 3) {
                    for ($i = 0; $i < sizeof($selecttagproducts); $i++) {

                        $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $selecttagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
                        if ($ptag_type->num_rows > 0) {
                            static $ipl_cnt = 0;
                            static $unit_cnt = 0;
                            static $module_cnt = 0;
                            static $mathmod_cnt = 0;

                            while ($rowqry = $ptag_type->fetch_assoc()) {
                                extract($rowqry);
                                if ($tagtype == '1') {

                                    $qryipls = "SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_ipl_name,2) AS shortname, 1 AS typ, a.fld_asset_id AS assetid,'IPL' AS titlename
                                        FROM itc_ipl_master  AS a
                                        LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
                                        WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id = '" . $titleid . "'";
                                    ++$ipl_cnt;

                                    if ($ipl_cnt == 1) {
                                        $group_qryipls = $qryipls;
                                    } elseif ($ipl_cnt > 1) {
                                        $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                                    }
                                }
                                if ($tagtype == '4') {
                                    $qryunits = "SELECT fld_id AS id, fld_unit_name AS nam,fn_shortname(fld_unit_name,2) AS shortname, 2 AS typ, fld_asset_id as assetid,'Unit' AS titlename
	  			FROM itc_unit_master 
				WHERE fld_delstatus='0' AND fld_id = '" . $titleid . "'";
                                    ++$unit_cnt;

                                    if ($unit_cnt == 1) {
                                        $group_qryunit = $qryunits;
                                    } elseif ($unit_cnt > 1) {
                                        $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                                    }
                                }
                                if ($tagtype == '3') {
                                    $qrymodules = "SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ, a.fld_asset_id AS assetid,'Module' AS titlename 
					FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'";
                                    ++$module_cnt;

                                    if ($module_cnt == 1) {
                                        $group_qrymodule = $qrymodules;
                                    } elseif ($module_cnt > 1) {
                                        $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                                    }
                                }
                                if ($tagtype == '23') {
                                    $qrymathmodules = "SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid,'Math Module' AS titlename 
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'";
                                    ++$mathmod_cnt;

                                    if ($mathmod_cnt == 1) {
                                        $group_qrymathmod = $qrymathmodules;
                                    } elseif ($mathmod_cnt > 1) {
                                        $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                                    }
                                }
                            }
                        }
                    }
                } else if ($sessmasterprfid == 6) { //Lessons listed based on available licenses for a distict
                    for ($i = 0; $i < sizeof($selecttagproducts); $i++) {
                        $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $selecttagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
                        if ($ptag_type->num_rows > 0) {
                            static $ipl_cnt = 0;
                            static $unit_cnt = 0;
                            static $module_cnt = 0;
                            static $mathmod_cnt = 0;
                            while ($rowqry = $ptag_type->fetch_assoc()) {
                                extract($rowqry);
                                if ($tagtype == '1') {
                                    $qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ ,c.fld_asset_id AS assetid,'IPL' AS titlename 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='" . $sendistid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '" . $titleid . "' 
					GROUP BY c.fld_id";
                                    ++$ipl_cnt;

                                    if ($ipl_cnt == 1) {
                                        $group_qryipls = $qryipls;
                                    } elseif ($ipl_cnt > 1) {
                                        $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                                    }
                                }
                                if ($tagtype == '4') {
                                    $qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ, c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='" . $sendistid . "' AND a.fld_start_date<=NOW() 
						AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '" . $titleid . "'
					GROUP BY c.fld_id";
                                    ++$unit_cnt;

                                    if ($unit_cnt == 1) {
                                        $group_qryunit = $qryunits;
                                    } elseif ($unit_cnt > 1) {
                                        $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                                    }
                                }
                                if ($tagtype == '3') {
                                    $qrymodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename 
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_district_id='" . $sendistid . "' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
						AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "' 
						GROUP BY b.fld_module_id";
                                    ++$module_cnt;

                                    if ($module_cnt == 1) {
                                        $group_qrymodule = $qrymodules;
                                    } elseif ($module_cnt > 1) {
                                        $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                                    }
                                }
                                if ($tagtype == '23') {
                                    $qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id 
							WHERE a.fld_delstatus='0' AND c.fld_district_id='" . $sendistid . "' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
								AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND a.fld_id = '" . $titleid . "'
							GROUP BY b.fld_module_id";
                                    ++$mathmod_cnt;

                                    if ($mathmod_cnt == 1) {
                                        $group_qrymathmod = $qrymathmodules;
                                    } elseif ($mathmod_cnt > 1) {
                                        $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                                    }
                                }
                            }
                        }
                    }
                } else {   //Lessons listed based on available licenses for a school or an individual user
                    for ($i = 0; $i < sizeof($selecttagproducts); $i++) {
                        $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $selecttagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
                        if ($ptag_type->num_rows > 0) {
                            static $ipl_cnt = 0;
                            static $unit_cnt = 0;
                            static $module_cnt = 0;
                            static $mathmod_cnt = 0;
                            while ($rowqry = $ptag_type->fetch_assoc()) {
                                extract($rowqry);
                                if ($tagtype == '1') {

                                    $qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ , c.fld_asset_id AS assetid,'IPL' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id 
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='" . $schoolid . "' AND a.fld_user_id='" . $indid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '" . $titleid . "' 
					GROUP BY c.fld_id";
                                    ++$ipl_cnt;

                                    if ($ipl_cnt == 1) {
                                        $group_qryipls = $qryipls;
                                    } elseif ($ipl_cnt > 1) {
                                        $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                                    }
                                }
                                if ($tagtype == '4') {
                                    $qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ , c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='" . $schoolid . "' AND a.fld_user_id='" . $indid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '" . $titleid . "'
					GROUP BY c.fld_id";
                                    ++$unit_cnt;

                                    if ($unit_cnt == 1) {
                                        $group_qryunit = $qryunits;
                                    } elseif ($unit_cnt > 1) {
                                        $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                                    }
                                }
                                if ($tagtype == '3') {
                                    $qrymodules = "SELECT b.fld_module_id AS id, 
						CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_school_id='" . $schoolid . "' AND c.fld_user_id='" . $indid . "' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'
						GROUP BY b.fld_module_id";
                                    ++$module_cnt;

                                    if ($module_cnt == 1) {
                                        $group_qrymodule = $qrymodules;
                                    } elseif ($module_cnt > 1) {
                                        $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                                    }
                                }
                                if ($tagtype == '23') {
                                    $qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
							WHERE a.fld_delstatus='0' AND c.fld_school_id='" . $schoolid . "' AND c.fld_user_id='" . $indid . "' 
								AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'
							GROUP BY b.fld_module_id";
                                    ++$mathmod_cnt;

                                    if ($mathmod_cnt == 1) {
                                        $group_qrymathmod = $qrymathmodules;
                                    } elseif ($mathmod_cnt > 1) {
                                        $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                                    }
                                }
                            } /* end the while loop */
                        } /* end the if($ptag_type->num_rows > 0) */
                    } /* end the loop of selecttagproducts */
                } /* end of the else part */

                if ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod == '')
                    $qry = $group_qryipls . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
                    $qry = $group_qryunit . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
                    $qry = $group_qrymodule . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
                    $qry = $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
                    $qry = $group_qryunit . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
                    $qry = $group_qryunit . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
                    $qry = $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
                    $qry = $group_qryunit . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
                    $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
                else
                    $qry = '';

                $selproductdetails = array();
                $productdetails = array();
                if ($qry != '') {
                    $productqry = $ObjDB->QueryObject($qry);
                    if ($productqry->num_rows > 0) {
                        $i = 0;
                        while ($productqryrow = $productqry->fetch_assoc()) {
                            extract($productqryrow);
                            if ($assetid == '') {
                                $assetid = 'MO.ENVM.3.0.0a';
                            }
                            $productdetails[] = array("id" => $id, "nam" => $nam, "shortname" => $shortname, "type" => $typ, "productid" => $assetid, "title_name" => $titlename, "gradeoutflag" => $gradeoutflag);
                            $i++;
                        }
                    }
                }
                ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible15').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $('#testrailvisible16').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list9").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list9') {
                            fn_movealllistitems('list9', 'list10', 1, $(this).attr('id').replace('list10_', ''));
                            fn_saveselecttag();
                            fn_validateproductstag();
                        }
                    });
                }
            });
            $("#list10").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list10') {
                            fn_movealllistitems('list9', 'list10', 1, $(this).attr('id').replace('list9_', ''));
                            fn_saveselecttag();
                            fn_validateproductstag();
                        }
                    });
                }
            });


        });
    </script>	

    <div class="dragndropcol">
        <div class="dragtitle">Products</div>
        <div class="dragWell" id="testrailvisible15" >
            <div id="list9" class="dragleftinner droptrue3">
                <div class="draglinkleftSearch" id="s_list9" >
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_9_search" name="list_9_search" onKeyUp="search_list(this, '#list9');" />
                        </dt>
                    </dl>
                </div>
    <?php
    $gnam = $ObjDB->QueryObject("SELECT fld_guid as guid FROM itc_correlation_rpt_std_grades
			       WHERE fld_rpt_data_id='" . $rptid . "' AND fld_delstatus='0'");
    if ($gnam->num_rows > 0) {
        $sguids = '';
        while ($grdrow = $gnam->fetch_assoc()) {
            extract($grdrow);


            if ($sguids == '') {
                $sguids = "'" . $guid . "'";
            } else {
                $sguids.="," . "'" . $guid . "'";
            }
        }
        $sguidsqry = " in (" . $sguids . ")";
    }

    $qrylessonunselect = $ObjDB->QueryObject("SELECT b.fld_prd_id as asstid FROM itc_correlation_productsgradeout as a
                                                                LEFT JOIN itc_correlation_products as b ON b.fld_prd_asset_id = a.fld_productid
                                                                WHERE a.fld_standardguid$sguidsqry GROUP BY a.fld_productid");
    $filter_greyout = array();
    while ($lessonunselect = $qrylessonunselect->fetch_assoc()) {
        extract($lessonunselect);
        array_push($filter_greyout, $asstid);
    }

    $qryforselectd_prods = $ObjDB->QueryObject("SELECT b.fld_prd_name,b.fld_prd_id,a.fld_type,
													b.fld_prd_sys_id
													FROM itc_correlation_rpt_products as a 
													left join itc_correlation_products as b on a.fld_prd_id=b.fld_id and a.fld_type=b.fld_prd_type
													where a.fld_rpt_data_id='" . $rptid . "' AND a.fld_delstatus='0' AND b.fld_prd_name<>''");

    if ($qryforselectd_prods->num_rows > 0) {
        while ($qryforselectd_prodsrow = $qryforselectd_prods->fetch_assoc()) {
            extract($qryforselectd_prodsrow);
            $selproductdetails[] = array("id" => $fld_prd_sys_id, "nam" => $fld_prd_name, "type" => $fld_type, "productid" => $fld_prd_id);
        }
    }


    for ($i = 0; $i < sizeof($productdetails); $i++) {
        $bool = true;
        for ($j = 0; $j < sizeof($selproductdetails); $j++) {

            if ($productdetails[$i]['id'] == $selproductdetails[$j]['id'] && $productdetails[$i]['type'] == $selproductdetails[$j]['type']) {

                $bool = false;
            }
        }
        if ($bool) {
            $dimproduct = array_diff(array($productdetails[$i]['productid']), $filter_greyout);
            ?>
                        <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkleft<?php if (!empty($dimproduct)) {
                echo ' dim';
                $a = 1;
            } ?>" id="list9_<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $productdetails[$i]['id'] . "~" . $productdetails[$i]['type'] . "~" . $productdetails[$i]['productid']; ?>" title="<?php echo $productdetails[$i]['nam']; ?>"><?php echo $productdetails[$i]['shortname'] . "/" . $productdetails[$i]['title_name']; ?></div>
                            <div class="clickable" id="clck_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsproducts('list9', 'list10', 1, '<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>');
                                                                                            fn_saveselecttag();
                                                                                            fn_validateproductstag();"></div>
                        </div>
            <?php
        }
    }
    ?>
            </div>
        </div>
        <div class="dragAllLink <?php if ($a == 1) {
        echo ' dim';
    } ?>" onclick="fn_movealllistitemsproducts('list9', 'list10', 0);
                            fn_saveselecttag();
                            fn_validateproductstag();"  style="cursor: pointer;cursor:hand;width: 113px;float: right;">add all products</div>
    </div>
    <?php
}

/*
 * ends for selecting production by tag types
 */
if ($oper == "removerightroducts" and $oper != " ") {
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $remtagproducts = isset($method['remtagproducts']) ? $method['remtagproducts'] : 0;
    $remtagproducts = explode(',', $remtagproducts);

    if ($sessmasterprfid == 2 or $sessmasterprfid == 3) {
        for ($i = 0; $i < sizeof($remtagproducts); $i++) {

            $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $remtagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
            if ($ptag_type->num_rows > 0) {
                static $ipl_cnt = 0;
                static $unit_cnt = 0;
                static $module_cnt = 0;
                static $mathmod_cnt = 0;

                while ($rowqry = $ptag_type->fetch_assoc()) {
                    extract($rowqry);
                    if ($tagtype == '1') {

                        $qryipls = "SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_ipl_name,2) AS shortname, 1 AS typ, a.fld_asset_id AS assetid,'IPL' AS titlename
                                        FROM itc_ipl_master  AS a
                                        LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
                                        WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1' AND a.fld_id = '" . $titleid . "'";
                        ++$ipl_cnt;

                        if ($ipl_cnt == 1) {
                            $group_qryipls = $qryipls;
                        } elseif ($ipl_cnt > 1) {
                            $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                        }
                    }
                    if ($tagtype == '4') {
                        $qryunits = "SELECT fld_id AS id, fld_unit_name AS nam,fn_shortname(fld_unit_name,2) AS shortname, 2 AS typ, fld_asset_id as assetid,'Unit' AS titlename
	  			FROM itc_unit_master 
				WHERE fld_delstatus='0' AND fld_id = '" . $titleid . "'";
                        ++$unit_cnt;

                        if ($unit_cnt == 1) {
                            $group_qryunit = $qryunits;
                        } elseif ($unit_cnt > 1) {
                            $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                        }
                    }
                    if ($tagtype == '3') {
                        $qrymodules = "SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ, a.fld_asset_id AS assetid,'Module' AS titlename 
					FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'";
                        ++$module_cnt;

                        if ($module_cnt == 1) {
                            $group_qrymodule = $qrymodules;
                        } elseif ($module_cnt > 1) {
                            $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                        }
                    }
                    if ($tagtype == '23') {
                        $qrymathmodules = "SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, a.fld_asset_id AS assetid,'Math Module' AS titlename 
						FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'";
                        ++$mathmod_cnt;

                        if ($mathmod_cnt == 1) {
                            $group_qrymathmod = $qrymathmodules;
                        } elseif ($mathmod_cnt > 1) {
                            $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                        }
                    }
                }  // end the while loop
            }
        }    // end the for loop
    }    // end the if condition
    else if ($sessmasterprfid == 6) { //Lessons listed based on available licenses for a distict
        for ($i = 0; $i < sizeof($remtagproducts); $i++) {
            $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $remtagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
            if ($ptag_type->num_rows > 0) {
                static $ipl_cnt = 0;
                static $unit_cnt = 0;
                static $module_cnt = 0;
                static $mathmod_cnt = 0;
                while ($rowqry = $ptag_type->fetch_assoc()) {
                    extract($rowqry);
                    if ($tagtype == '1') {
                        $qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ ,c.fld_asset_id AS assetid,'IPL' AS titlename 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='" . $sendistid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '" . $titleid . "' 
					GROUP BY c.fld_id";
                        ++$ipl_cnt;

                        if ($ipl_cnt == 1) {
                            $group_qryipls = $qryipls;
                        } elseif ($ipl_cnt > 1) {
                            $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                        }
                    }
                    if ($tagtype == '4') {
                        $qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ, c.fld_asset_id as assetid,'Unit' AS titlename
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_district_id='" . $sendistid . "' AND a.fld_start_date<=NOW() 
						AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '" . $titleid . "'
					GROUP BY c.fld_id";
                        ++$unit_cnt;

                        if ($unit_cnt == 1) {
                            $group_qryunit = $qryunits;
                        } elseif ($unit_cnt > 1) {
                            $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                        }
                    }
                    if ($tagtype == '3') {
                        $qrymodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_district_id='" . $sendistid . "' AND c.fld_school_id='0' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'
						GROUP BY b.fld_module_id";
                        ++$module_cnt;

                        if ($module_cnt == 1) {
                            $group_qrymodule = $qrymodules;
                        } elseif ($module_cnt > 1) {
                            $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                        }
                    }
                    if ($tagtype == '23') {
                        $qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename  
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id 
							WHERE a.fld_delstatus='0' AND c.fld_district_id='" . $sendistid . "' AND c.fld_school_id='0' AND c.fld_delstatus='0' AND d.fld_delstatus='0'
								AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND a.fld_id = '" . $titleid . "'
							GROUP BY b.fld_module_id";
                        ++$mathmod_cnt;

                        if ($mathmod_cnt == 1) {
                            $group_qrymathmod = $qrymathmodules;
                        } elseif ($mathmod_cnt > 1) {
                            $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                        }
                    }
                }
            }
        }
    } else {   //Lessons listed based on available licenses for a school or an individual user
        for ($i = 0; $i < sizeof($remtagproducts); $i++) {
            $ptag_type = $ObjDB->QueryObject("SELECT fld_item_id as titleid,fld_tag_type as tagtype FROM itc_main_tag_mapping where fld_tag_id='" . $remtagproducts[$i] . "' AND (fld_tag_type = '1' OR fld_tag_type = '23' OR fld_tag_type = '3' OR fld_tag_type = '4') AND fld_access = '1'");
            if ($ptag_type->num_rows > 0) {
                static $ipl_cnt = 0;
                static $unit_cnt = 0;
                static $module_cnt = 0;
                static $mathmod_cnt = 0;
                while ($rowqry = $ptag_type->fetch_assoc()) {
                    extract($rowqry);
                    if ($tagtype == '1') {

                        $qryipls = "SELECT c.fld_id AS id, CONCAT(c.fld_ipl_name,' ',d.fld_version) AS nam,fn_shortname(c.fld_ipl_name,2) AS shortname, 1 AS typ , c.fld_asset_id AS assetid,'IPL' AS titlename  
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_ipl_master AS c ON b.`fld_lesson_id`=c.fld_id 
					LEFT JOIN itc_ipl_version_track AS d ON d.fld_ipl_id=c.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='" . $schoolid . "' AND a.fld_user_id='" . $indid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_access='1'
						AND d.fld_zip_type='1' AND d.fld_delstatus='0' AND c.fld_id = '" . $titleid . "' 
					GROUP BY c.fld_id";
                        ++$ipl_cnt;

                        if ($ipl_cnt == 1) {
                            $group_qryipls = $qryipls;
                        } elseif ($ipl_cnt > 1) {
                            $group_qryipls = $group_qryipls . " UNION " . $qryipls;
                        }
                    }
                    if ($tagtype == '4') {
                        $qryunits = "SELECT c.fld_id AS id, c.fld_unit_name AS nam,fn_shortname(c.fld_unit_name,2) AS shortname, 2 AS typ , c.fld_asset_id as assetid,'Unit' AS titlename 
					FROM itc_license_track AS a 
					LEFT JOIN itc_license_cul_mapping AS b ON a.fld_license_id=b.fld_license_id 
					LEFT JOIN itc_unit_master AS c ON b.`fld_unit_id`=c.fld_id 
					WHERE a.fld_delstatus='0' AND b.fld_active='1' AND a.fld_school_id='" . $schoolid . "' AND a.fld_user_id='" . $indid . "' 
						AND a.fld_start_date<=NOW() AND a.fld_end_date>=NOW() AND c.fld_delstatus='0' AND c.fld_id = '" . $titleid . "' 
					GROUP BY c.fld_id";
                        ++$unit_cnt;

                        if ($unit_cnt == 1) {
                            $group_qryunit = $qryunits;
                        } elseif ($unit_cnt > 1) {
                            $group_qryunit = $group_qryunit . " UNION " . $qryunits;
                        }
                    }
                    if ($tagtype == '3') {
                        $qrymodules = "SELECT b.fld_module_id AS id, 
						CONCAT(a.fld_module_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname, 3 AS typ , a.fld_asset_id AS assetid,'Module' AS titlename  
						FROM itc_module_master AS a 
						LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
						LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id
						LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
						WHERE a.fld_delstatus='0' AND c.fld_school_id='" . $schoolid . "' AND c.fld_user_id='" . $indid . "' AND c.fld_delstatus='0' 
							AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=1 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'
						GROUP BY b.fld_module_id";
                        ++$module_cnt;

                        if ($module_cnt == 1) {
                            $group_qrymodule = $qrymodules;
                        } elseif ($module_cnt > 1) {
                            $group_qrymodule = $group_qrymodule . " UNION " . $qrymodules;
                        }
                    }
                    if ($tagtype == '23') {
                        $qrymathmodules = "SELECT b.fld_module_id AS id, CONCAT(a.fld_mathmodule_name,' ',d.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ , a.fld_asset_id AS assetid,'Math Module' AS titlename 
							FROM itc_mathmodule_master AS a 
							LEFT JOIN itc_license_mod_mapping AS b ON a.fld_id=b.fld_module_id 
							LEFT JOIN itc_license_track AS c ON b.fld_license_id=c.fld_license_id 
							LEFT JOIN itc_module_version_track AS d ON d.fld_mod_id=b.fld_module_id
							WHERE a.fld_delstatus='0' AND c.fld_school_id='" . $schoolid . "' AND c.fld_user_id='" . $indid . "' 
								AND c.fld_delstatus='0' AND b.fld_active='1' AND c.fld_start_date<=NOW() AND c.fld_end_date>=NOW() AND b.fld_type=2 AND d.fld_delstatus='0' AND a.fld_id = '" . $titleid . "'
							GROUP BY b.fld_module_id";
                        ++$mathmod_cnt;

                        if ($mathmod_cnt == 1) {
                            $group_qrymathmod = $qrymathmodules;
                        } elseif ($mathmod_cnt > 1) {
                            $group_qrymathmod = $group_qrymathmod . " UNION " . $qrymathmodules;
                        }
                    }
                } /* end the while loop */
            } /* end the if($ptag_type->num_rows > 0) */
        } /* end the loop of selecttagproducts */
    } /* end of the else part */


    if ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryipls . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryunit . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qrymodule . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod == '')
        $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryunit . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryunit . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod == '')
        $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymodule . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule == '' && $group_qrymathmod != '')
        $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls == '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryunit . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit == '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    elseif ($group_qryipls != '' && $group_qryunit != '' && $group_qrymodule != '' && $group_qrymathmod != '')
        $qry = $group_qryipls . " UNION ALL " . $group_qryunit . " UNION ALL " . $group_qrymodule . " UNION ALL " . $group_qrymathmod . " ORDER BY nam";
    else
        $qry = '';


    $selproductdetails = array();
    $productdetails = array();
    if ($qry != '') {
        $productqry = $ObjDB->QueryObject($qry);
        if ($productqry->num_rows > 0) {
            $i = 0;
            while ($productqryrow = $productqry->fetch_assoc()) {
                extract($productqryrow);
                if ($assetid == '') {
                    $assetid = 'MO.ENVM.3.0.0a';
                }
                $productdetails[] = array("id" => $id, "nam" => $nam, "type" => $typ);
                $i++;
            }
        }
    }
    $remove_selectprod1 = array();
    $remove_selectprod2 = array();
    $remove_selectprod3 = array();


    for ($m = 0; $m < sizeof($productdetails); $m++) {

        $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id FROM  itc_correlation_products 
													WHERE fld_prd_type ='" . $productdetails[$m]['type'] . "' 
													AND fld_prd_sys_id='" . $productdetails[$m]['id'] . "'");

        array_push($remove_selectprod1, $mprd_id);
    }
    $selected_correpqry = $ObjDB->QueryObject("SELECT fld_prd_id as prodid,fld_type as types FROM  itc_correlation_rpt_products 
			 												WHERE fld_rpt_data_id='" . $rptid . "' AND fld_tagflag ='1' AND fld_delstatus = '0'");

    if ($selected_correpqry->num_rows > 0) {

        while ($cor_productqryrow = $selected_correpqry->fetch_assoc()) {
            extract($cor_productqryrow);

            array_push($remove_selectprod3, $prodid . '_' . $types);
            array_push($remove_selectprod2, $prodid);
        }
    }


    $remproduct_result = array_diff($remove_selectprod2, $remove_selectprod1);
    $remproduct_result = array_values($remproduct_result);
    for ($n = 0; $n < sizeof($remproduct_result); $n++) {
        $ObjDB->NonQuery("UPDATE itc_correlation_rpt_products SET fld_delstatus=1 
										       WHERE fld_rpt_data_id='" . $rptid . "' AND fld_tagflag ='1' AND fld_prd_id ='" . $remproduct_result[$n] . "'");
    }
    echo json_encode($productdetails);
}
/* --- Save Step2 Correlation Standard Info--- */
if ($oper == "savestep4" and $oper != " ") {
    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
    $productid = isset($method['productid']) ? $method['productid'] : 0;
    $tagproductid = isset($method['tagproductid']) ? $method['tagproductid'] : 0;
    $tagpid = isset($method['tagpid']) ? $method['tagpid'] : 0;
    $titletype = isset($method['titletype']) ? $method['titletype'] : 0;
    $show_titletype = isset($method['show_titletype']) ? $method['show_titletype'] : 0;
    //show_titletype
    $productid = explode(',', $productid);
    $tagproductid = explode(',', $tagproductid);
    $productid = array_filter($productid);
    $tagproductid = array_filter($tagproductid);
    $statename = $ObjDB->SelectSingleValueInt("SELECT fld_std_body FROM itc_correlation_report_data 
												WHERE fld_delstatus='0' AND  fld_id='" . $rptid . "'");
    $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_step_id='4', fld_updated_by='" . $uid . "', fld_updated_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "'");
    $ObjDB->NonQuery("UPDATE itc_correlation_rpt_products SET fld_delstatus='1' WHERE fld_rpt_data_id='" . $rptid . "'");
    if (!empty($productid)) {

        for ($i = 0; $i < sizeof($productid); $i++) {
            $productdetails = explode('~', $productid[$i]);
            $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id  FROM  itc_correlation_productdetails WHERE  fld_asset_id='" . $productdetails[2] . "'"); //fld_prd_type ='".$productdetails[1]."' AND

            $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) FROM  itc_correlation_rpt_products 
												WHERE fld_rpt_data_id='" . $rptid . "' AND fld_prd_id ='" . $mprd_id . "'");

            if ($cnt == 0) {

                if ($show_titletype == '0') {

                    $ObjDB->NonQuery("INSERT INTO itc_correlation_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_product_id) 
                                                               VALUES ('" . $rptid . "','" . $productdetails[1] . "','" . $mprd_id . "','" . $productdetails[2] . "')");

                    $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_show_alltype='10', fld_updated_by='" . $uid . "', fld_updated_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "'");
                } else {


                    $ObjDB->NonQuery("INSERT INTO itc_correlation_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_product_id) 
                                    							VALUES ('" . $rptid . "','" . $productdetails[1] . "','" . $mprd_id . "','" . $productdetails[2] . "')");
                }
            } else {
                if ($show_titletype == '0') {
                    $ObjDB->NonQuery("UPDATE itc_correlation_rpt_products SET fld_delstatus='0', fld_type='" . $productdetails[1] . "'
                                                                        WHERE fld_rpt_data_id='" . $rptid . "' AND fld_prd_id='" . $mprd_id . "' AND fld_product_id='" . $productdetails[2] . "' ");
                    $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_show_alltype='10', fld_updated_by='" . $uid . "', fld_updated_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "'");
                } else {
                    $ObjDB->NonQuery("UPDATE itc_correlation_rpt_products SET fld_delstatus='0', fld_type='" . $productdetails[1] . "'
                                                                        WHERE fld_rpt_data_id='" . $rptid . "' AND fld_prd_id='" . $mprd_id . "' AND fld_product_id='" . $productdetails[2] . "' ");
                }
            }
        }
    }


    if (!empty($tagproductid)) {
        for ($i = 0; $i < sizeof($tagproductid); $i++) {
            $tagproductdetails = explode('~', $tagproductid[$i]);

            $mprd_id = $ObjDB->SelectSingleValueInt("SELECT fld_id 
														FROM  itc_correlation_productdetails 
														WHERE fld_prd_type ='" . $tagproductdetails[1] . "'");

            $cnt = $ObjDB->SelectSingleValueInt("SELECT COUNT(fld_id) 
												FROM  itc_correlation_rpt_products 
												WHERE fld_rpt_data_id='" . $rptid . "' AND fld_prd_id ='" . $mprd_id . "'");

            if ($cnt == 0) {

                $ObjDB->NonQuery("INSERT INTO itc_correlation_rpt_products(fld_rpt_data_id,fld_type,fld_prd_id,fld_tagflag) 
									VALUES ('" . $rptid . "','" . $tagproductdetails[1] . "','" . $mprd_id . "','1')");
                $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_tagproduct_id='" . $tagpid . "',fld_updated_by='" . $uid . "',fld_updated_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "' AND fld_delstatus='0'");
            } else {

                $ObjDB->NonQuery("UPDATE itc_correlation_rpt_products SET fld_delstatus='0', fld_type='" . $tagproductdetails[1] . "', fld_tagflag = '1'
							WHERE fld_rpt_data_id='" . $rptid . "' AND fld_prd_id='" . $mprd_id . "'");
                $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_tagproduct_id='" . $tagpid . "',fld_updated_by='" . $uid . "',fld_updated_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "' AND fld_delstatus='0'");
            }
        }
    }

    echo $rptid;
}
if ($oper == "sendmail" and $oper != '') {
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $requestcomments = isset($_POST['requestcomments']) ? $_POST['requestcomments'] : '';
    $rid = isset($_POST['rid']) ? $_POST['rid'] : '';
    $date = date("Y-m-d H:i:s");
    $requestdate = isset($_POST['requestdate']) ? $_POST['requestdate'] : '';
    $docid = isset($method['list66']) ? $method['list66'] : '';
    $gradeid = isset($method['list18']) ? $method['list18'] : '';
    $productid = isset($method['list21']) ? $method['list21'] : '';
    $selectstate = isset($method['selectstate']) ? $method['selectstate'] : '';

    $docid = explode(",", $docid);
    $grade = explode(",", $gradeid);
    $product = explode(",", $productid);
    $prd = array_filter($product);

    /** query for docments * */
    for ($s = 0; $s < sizeof($docid); $s++) {
        $ObjDB->NonQuery("INSERT INTO itc_correlation_request(fld_rpt_data_id,fld_select_state,fld_selected_document,fld_created_date,fld_created_by)values('" . $rid . "','" . $selectstate . "','" . $docid[$s] . "','" . date("Y-m-d H:i:s") . "','" . $uid . "')");
    }

    /** query for grade * */
    for ($a = 0; $a < sizeof($grade); $a++) {
        $ObjDB->NonQuery("INSERT INTO itc_correlation_request_grades(fld_rpt_id,fld_selected_grade_id,fld_created_date,fld_created_by)values('" . $rid . "','" . $grade[$a] . "','" . date("Y-m-d H:i:s") . "','" . $uid . "')");
    }
    /** query for products * */
    for ($i = 0; $i < sizeof($prd); $i++) {
        $productdetails = explode('_', $prd[$i]);
        $ObjDB->NonQuery("INSERT INTO itc_correlation_request_products(fld_rpt_id,fld_selected_product_id,fld_select_title,fld_created_date,fld_created_by)values('" . $rid . "','" . $productdetails[0] . "','" . $productdetails[1] . "','" . date("Y-m-d H:i:s") . "','" . $uid . "')");
    }

    /** Mail sending process    * */
    $html_txt = '';
    $headers = '';
    $getemail = $ObjDB->QueryObject("SELECT fld_email AS usermail FROM itc_user_master WHERE fld_id = '" . $id . "'");
    $rowemail = $getemail->fetch_assoc();
    extract($rowemail);
    if ($usermail != '') {
        $html_txt = '';
        $headers = '';
        $subj = "Request for correlation";
        $random_hash = md5(date('r', time()));
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
        $headers .= "From: Synergy ITC <$usermail>" . "\r\n";
        $html_txt = '<html><head>  <title>Request For Report Generation</title></head>
                                    <body>  <p>Request for correlation!</p>                                                           
                                   <table width="98%" cellpadding="10" cellspacing="0" border="2">';

        $getdetails = $ObjDB->QueryObject("SELECT a.fld_id AS id,a.fld_email AS usermail,a.fld_fname AS fname,a.fld_lname AS lname,
a.fld_school_id AS schoolid,
                                                                        b.fld_school_name AS schoolname,c.fld_name as state
FROM itc_user_master AS a
LEFT JOIN itc_school_master AS b ON b.fld_id = a.fld_school_id
                                                                        LEFT JOIN itc_standards_bodies  AS c ON  c.fld_id='" . $selectstate . "'
WHERE a.fld_id = '" . $uid . "' GROUP BY a.fld_id ");
        if ($getdetails->num_rows > 0) {
            $rowdetails = $getdetails->fetch_assoc();
            extract($rowdetails);
            if ($sessmasterprfid == 5) {
                $html_txt.='<tr><td>Teachername</td><td>' . $fname . '&nbsp;' . $lname . '</td></tr>';
            } else {
                $html_txt.='<tr><td>School Name</td><td>' . $schoolname . '</td></tr><tr><td>Teachername</td><td>' . $fname . '&nbsp;' . $lname . '</td></tr><tr><td>State</td><td>' . $state . '</td></tr>';
            }
        }
        $html_txt.='<tr><td>Creation Date</td><td>' . date("m/d/Y", strtotime($date)) . '</td></tr>';
        $html_txt.='<tr><td> Required Delivery Date</td><td>' . date("m/d/Y", strtotime($requestdate)) . '</td></tr>';

        $html_txt.='<tr><td>ProductSet</td><td>';
        for ($i = 0; $i < sizeof($prd); $i++) {
            $productdetails = explode('_', $prd[$i]);
            $qryforproduct = $ObjDB->QueryObject("SELECT b.fld_prd_name AS productname
                                                                            FROM itc_correlation_rpt_products a 
                                                                            LEFT JOIN itc_correlation_products b ON b.fld_id = a.fld_prd_id
                                                                                   WHERE a.fld_delstatus = '0' 
                                            AND  b.fld_prd_sys_id='" . $productdetails[0] . "' AND b.fld_prd_type='" . $productdetails[1] . "' group by a.fld_prd_id");
            if ($qryforproduct->num_rows > 0) {

                while ($rowproduct = $qryforproduct->fetch_assoc()) {
                    extract($rowproduct);
                    $html_txt.=$productname . "\r\n" . '<br />';
                }
            }
        }

        $html_txt.='</td></tr><tr><td>StandardName</td><td>';
        ///StandardName
        for ($s = 0; $s < sizeof($docid); $s++) {
            $qryforstandards = $ObjDB->QueryObject("SELECT 
                                                                               a.fld_doc_title AS documenttitle,b.fld_sub_title AS subjectname,b.fld_sub_year AS year
                                                                              FROM
                                                                               itc_correlation_documents a
                                                                                   LEFT JOIN itc_correlation_doc_subject b ON a.fld_id = b.fld_doc_id
                                                                                   WHERE  b.fld_sub_guid='" . $docid[$s] . "' group by b.fld_sub_guid"); // b.fld_sub_guid AS guid,a.fld_doc_guid AS docguid,
            if ($qryforstandards->num_rows > 0) {
                while ($rowstandards = $qryforstandards->fetch_assoc()) {
                    extract($rowstandards);

                    $html_txt.= $documenttitle . " | " . $subjectname . " (" . $year . ")" . "\r\n" . '<br />';
                }
            }
        }

        $html_txt.='</td></tr><tr><td>Gradename</td><td>';
        ///gradename
        for ($a = 0; $a < sizeof($grade); $a++) {
            $qryforgrade = $ObjDB->QueryObject("SELECT a.fld_grade_name AS gradename,b.fld_sub_title AS subjectname,
                                                                                b.fld_sub_year AS year  FROM itc_correlation_grades as a
                                                                                left join itc_correlation_doc_subject AS b ON a.fld_sub_id = b.fld_id
                                                                                where a.fld_grade_guid='" . $grade[$a] . "' ");
            if ($qryforgrade->num_rows > 0) {
                while ($rowgrade = $qryforgrade->fetch_assoc()) {
                    extract($rowgrade);
                    $html_txt.= $gradename . " | " . $subjectname . " (" . $year . ")" . "\r\n" . '<br />';
                }
            }
        }

        $html_txt.='</td></tr><tr><td>Comments</td><td>' . $requestcomments . '</td></tr>';
        $html_txt.='</table></body></html>';
        $param = array('SiteID' => '30', 'fromAddress' => $usermail, 'fromName' => 'Synergy ITC', 'toAddress' => 'correlationsrequest@pitsco.com', 'subject' => $subj, 'plainTex' => '', 'html' => $html_txt, 'options' => '', 'groupID' => '805014', 'log' => 'True');
        $client = new nusoap_client(PAPI_URL . '/msgg/msgg.asmx?wsdl', 'wsdl');
        if ($client->call('SendJangoMailTransactional', $param, '', '', false, true)) {
            echo "success";
        } else {
            echo "fail";
        }
    }
}

//request form 
if ($oper == "showdocuments1" and $oper != " ") {
    $stid = isset($method['stid']) ? $method['stid'] : '';
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';

    $guiddb = $ObjDB->SelectSingleValue("SELECT fld_doc_id FROM itc_correlation_rpt_doc_mapping WHERE fld_cor_id='" . $rptid . "'");

    $docqry = $ObjDB->QueryObject("SELECT a.fld_doc_title AS documenttitle,fn_shortname(a.fld_doc_title, 1) as shortname,a.fld_doc_guid AS docguid,
        b.fld_sub_title AS subjectname,fn_shortname(b.fld_sub_title, 1) as shortsubjname,b.fld_sub_year AS year,b.fld_sub_guid AS guid, 		b.fld_sub_guid as subid FROM itc_correlation_documents a   LEFT JOIN itc_correlation_doc_subject b ON a.fld_id = b.fld_doc_id WHERE
        a.fld_authority_id = '" . $stid . "' ");
    ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible112').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible123').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list55").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list55') {

                            fn_movealllistitems1('list55', 'list66', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
            $("#list66").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list66') {
                            fn_movealllistitems1('list55', 'list66', 1, $(this).children(":first").attr('id'));

                        }
                    });
                }
            });
        });
    </script>

    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Documents</div>
            <div class="dragWell" id="testrailvisible112" >
                <div id="list55" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list55" >
                        <dl class='field row'>
                            <dt class='text'>
                                <input placeholder='Search' type='text' id="list_55_search" name="list_55_search" onKeyUp="search_list(this, '#list55');" />
                            </dt>
                        </dl>
                    </div>
    <?php
    if ($docqry->num_rows > 0) {
        while ($docrow = $docqry->fetch_assoc()) {
            extract($docrow);
            $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            ?>
                            <div class="draglinkleft" id="list55_<?php echo $guid; ?>" >
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs; ?>" id="<?php echo $guid; ?>"><?php echo $shortname . " | " . $shortsubjname . " (" . $year . ")"; ?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems1('list55', 'list66', 1, '<?php echo $guid; ?>');"></div>
                            </div>
            <?php
        }
    }
    ?>    
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems1('list55', 'list66', 0);"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">add all documents</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected documents<span class="fldreq">*</span></div>
            <div class="dragWell" id="testrailvisible123">
                <div id="list66" class="dragleftinner droptrue3">
    <?php
    if ($docqrysec->num_rows > 0) {
        while ($docrowsec = $docqrysec->fetch_assoc()) {
            extract($docrowsec);
            $stddocs = $documenttitle . " | " . $subjectname . " (" . $year . ")";
            ?>
                            <div class="draglinkright" id="list66_<?php echo $guid; ?>" >
                                <input type="hidden" name="seldocument" id="seldocument" value="<?php echo $stddocs; ?>">
                                <div class="dragItemLable tooltip" title="<?php echo $stddocs; ?>" id="<?php echo $guid; ?>"><?php echo $shortname . " | " . $shortsubjname . " (" . $year . ")"; ?></div>
                                <div class="clickable" id="clck_<?php echo $guid; ?>" onclick="fn_movealllistitems1('list55', 'list66', 1, '<?php echo $guid; ?>');"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>	
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems1('list66', 'list55', 0);" style="cursor: pointer;cursor:hand;width: 160px;float:right; ">remove all documents</div>
        </div>
    </div>


    <?php
}

/* --- Load document dropdown --- */
if ($oper == "showgrades1" and $oper != " ") {
    $stid = isset($method['stid']) ? $method['stid'] : '';
    $stdid = isset($method['stdid']) ? $method['stdid'] : '';
    $rptid = isset($method['rptid']) ? $method['rptid'] : '';
    $stddocsdb = array();
    $grdguidqry = $ObjDB->QueryObject("SELECT a.fld_guid AS grdguids, a.fld_grade AS grdnames,fn_shortname(a.fld_grade,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
                                                b.fld_sub_title AS subjectname,b.fld_sub_year AS year
                                                FROM itc_correlation_rpt_std_grades AS a 
                                                left join itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
                                                WHERE a.fld_rpt_data_id='" . $rptid . "' AND a.fld_delstatus='0'");

    if ($grdguidqry->num_rows > 0) {
        while ($grdguidrow = $grdguidqry->fetch_assoc()) {
            extract($grdguidrow);
            $stddocsdb[$grdguids] = $grdnames . " | " . $subjectname . " (" . $year . ")~" . $shortgrdname . " | " . $shortsubjname . " (" . $year . ")";
        }
    }


    $grdqry = $ObjDB->QueryObject("SELECT a.fld_grade_guid AS gguid, a.fld_grade_name AS gradename 
                                                ,fn_shortname(a.fld_grade_name,1) as shortgrdname,fn_shortname(b.fld_sub_title,1) as shortsubjname,
                                                b.fld_sub_title AS subjectname,b.fld_sub_year AS year,a.fld_gradeout_flag as gradeoutflag
                                                FROM itc_correlation_grades as a 
                                                left join itc_correlation_doc_subject AS b ON a.fld_sub_id=b.fld_id
                                                WHERE a.fld_gradeout_flag='1' AND b.fld_sub_guid IN (" . $stdid . ")  ");


    $stddocs = array();
    if ($grdqry->num_rows > 0) {
        while ($grdrow = $grdqry->fetch_assoc()) {
            extract($grdrow);
            $stddocs[$gguid] = $gradename . " | " . $subjectname . " (" . $year . ")~" . $shortgrdname . " | " . $shortsubjname . " (" . $year . ")~" . $gradeoutflag;
        }
    }
    ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible134').slimscroll({
                width: '410px',
                height: '366px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible145').slimscroll({
                width: '410px',
                height: '370px',
                size: '7px',
                alwaysVisible: true,
                railVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });
            $("#list17").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list17') {

                            fn_movealllistitems1('list17', 'list18', 1, $(this).children(":first").attr('id'));
                            fn_validategrade();
                        }
                    });
                }
            });
            $("#list18").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list18') {
                            fn_movealllistitems1('list17', 'list18', 1, $(this).children(":first").attr('id'));
                            fn_validategrade();
                        }
                    });
                }
            });
        });
    </script>

    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Grades</div>
            <div class="dragWell" id="testrailvisible134" >
                <div id="list17" class="dragleftinner droptrue3">
                    <div class="draglinkleftSearch" id="s_list17" >
                        <dl class='field row'>
                            <dt class='text'>
                                <input placeholder='Search' type='text' id="list_17_search" name="list_17_search" onKeyUp="search_list(this, '#list17');" />
                            </dt>
                        </dl>
                    </div>
    <?php
    foreach ($stddocs as $key => $val) {
        if (!array_key_exists($key, $stddocsdb)) {
            $val = explode("~", $val);
            ?>
                            <div class="draglinkleft" id="list17_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems1('list17', 'list18', 1, '<?php echo $key; ?>');
                                                    fn_validategrade();"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems1('list17', 'list18', 0);
                            fn_validategrade();"  style="cursor: pointer;cursor:hand;width: 130px;float:right; ">add all grades</div>
        </div>
    </div>
    <div class='six columns'>
        <div class="dragndropcol">
            <div class="dragtitle">Selected Grades<span class="fldreq">*</span></div>
            <div class="dragWell" id="testrailvisible145">
                <div id="list18" class="dragleftinner droptrue3">
    <?php
    foreach ($stddocs as $key => $val) {
        if (array_key_exists($key, $stddocsdb)) {
            $val = explode("~", $val);
            ?>
                            <div class="draglinkright" id="list18_<?php echo $key; ?>" >
                                <div class="dragItemLable tooltip" id="<?php echo $key; ?>" title="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></div>
                                <div class="clickable" id="clck_<?php echo $key; ?>" onclick="fn_movealllistitems1('list17', 'list18', 1, '<?php echo $key; ?>');
                                                    fn_validategrade();"></div>
                            </div>
            <?php
        }
    }
    ?>
                </div>	
            </div>
            <div class="dragAllLink" onclick="fn_movealllistitems1('list18', 'list17', 0);
                            fn_validategrade();"  style="cursor: pointer;cursor:hand;width: 160px;float: right;">remove all grades</div>
        </div>
    </div>

                    <?php
                }

                if ($oper == "showproducts1" and $oper != " ") {
                    $type = isset($method['type']) ? $method['type'] : 0;
                    $rptid = isset($method['rptid']) ? $method['rptid'] : 0;
                    $selectproducts = isset($method['selectproducts']) ? $method['selectproducts'] : 0;
                    $selectproducts = explode(',', $selectproducts);
                    $qryipls = "SELECT a.fld_id AS id, CONCAT(a.fld_ipl_name,' ',b.fld_version) AS nam,fn_shortname	(a.fld_ipl_name,2) AS shortname, 
				1 AS typ, a.fld_asset_id AS assetid
				FROM itc_ipl_master AS a
				LEFT JOIN itc_ipl_version_track AS b ON b.fld_ipl_id=a.fld_id
				WHERE a.fld_access='1' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_zip_type='1'";

                    $qryunits = "SELECT a.fld_id AS id, a.fld_unit_name AS nam,fn_shortname(a.fld_unit_name,2) AS shortname, 
				2 AS typ, a.fld_asset_id as assetid
	  			FROM itc_unit_master as a
				WHERE a.fld_delstatus='0'";


                    $qrymodules = "SELECT a.fld_id AS id, CONCAT(a.fld_module_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_module_name,2) AS shortname,
 					3 AS typ, a.fld_asset_id AS assetid
						FROM itc_module_master AS a 
					LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_id
					WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";

                    $qrymathmodules = "SELECT a.fld_id AS id, CONCAT(a.fld_mathmodule_name,' ',b.fld_version) AS nam,fn_shortname(a.fld_mathmodule_name,2) AS shortname, 4 AS typ, 					a.fld_asset_id AS assetid 
							FROM itc_mathmodule_master AS a 
						LEFT JOIN itc_module_version_track AS b ON b.fld_mod_id=a.fld_module_id
						WHERE a.fld_delstatus='0' AND b.fld_delstatus='0'";

                    if ($type == 0) {
                        $qry = $qryipls . " union all " . $qryunits . " union all " . $qrymodules . " union all " . $qrymathmodules . " ORDER BY nam";
                    } else if ($type == 1) {
                        $qry = $qryipls . " ORDER BY nam";
                    } else if ($type == 2) {
                        $qry = $qryunits . " ORDER BY nam";
                    } else if ($type == 3) {
                        $qry = $qrymodules . " ORDER BY nam";
                    } else if ($type == 4) {
                        $qry = $qrymathmodules . " ORDER BY nam";
                    }

                    $productdetails = array();
                    $productqry = $ObjDB->QueryObject($qry);
                    if ($productqry->num_rows > 0) {
                        $i = 0;
                        while ($productqryrow = $productqry->fetch_assoc()) {
                            extract($productqryrow);
                            if ($assetid == '') {
                                $assetid = 'MO.ENVM.3.0.0a';
                            }
                            $productdetails[] = array("id" => $id, "nam" => $nam, "shortname" => $shortname, "type" => $typ, "productid" => $assetid);
                            $i++;
                        }
                    }
                    ?>
    <script type="text/javascript" language="javascript">
        $(function () {
            $('#testrailvisible131').slimscroll({
                width: '410px',
                height: '370px',
                railVisible: true,
                size: '7px',
                alwaysVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $('#testrailvisible141').slimscroll({
                width: '410px',
                height: '370px',
                railVisible: true,
                size: '7px',
                alwaysVisible: true,
                allowPageScroll: false,
                railColor: '#F4F4F4',
                opacity: 1,
                color: '#d9d9d9',
                wheelStep: 1,
            });

            $("#list20").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                items: "div[class='draglinkleft']",
                receive: function (event, ui) {
                    $("div[class=draglinkright]").each(function () {
                        if ($(this).parent().attr('id') == 'list20') {
                            fn_movealllistitems('list20', 'list21', 1, $(this).attr('id').replace('list21_', ''));
                            fn_saveselect();
                            fn_validateproducts();
                        }
                    });
                }
            });

            $("#list21").sortable({
                connectWith: ".droptrue3",
                dropOnEmpty: true,
                receive: function (event, ui) {
                    $("div[class=draglinkleft]").each(function () {
                        if ($(this).parent().attr('id') == 'list21') {
                            fn_movealllistitems('list20', 'list21', 1, $(this).attr('id').replace('list20_', ''));
                            fn_saveselect();
                            fn_validateproducts();
                        }
                    });
                }
            });
        });
    </script>    	

    <div class="dragndropcol">
        <div class="dragtitle">Products</div>    
        <div class="dragWell" id="testrailvisible131" >
            <div id="list20" class="dragleftinner droptrue3">            
                <div class="draglinkleftSearch" id="s_list20" >
                    <dl class='field row'>
                        <dt class='text'>
                            <input placeholder='Search' type='text' id="list_20_search" name="list_20_search" onKeyUp="search_list(this, '#list20');" />
                        </dt>
                    </dl>
                </div>

    <?php
    for ($i = 0; $i < sizeof($productdetails); $i++) {
        $bool = true;
        for ($j = 0; $j < sizeof($selectproducts); $j++) {
            $selctp = explode('_', $selectproducts[$j]);
            if ($selctp[0] == $productdetails[$i]['id'] and $selctp[1] == $productdetails[$i]['type']) {

                $bool = false;
            }
        }
        if ($bool) {
            ?>
                        <div name="<?php echo $productdetails[$i]['type']; ?>" class="draglinkleft" id="list20_<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>" >
                            <div class="dragItemLable tooltip" id="<?php echo $productdetails[$i]['id'] . "~" . $productdetails[$i]['type'] . "~" . $productdetails[$i]['productid']; ?>" title="<?php echo $productdetails[$i]['nam']; ?>"><?php echo $productdetails[$i]['shortname']; ?></div>
                            <div class="clickable" id="click_<?php echo $productdetails[$i]['productid']; ?>" onclick="fn_movealllistitemsrequest('list20', 'list21', 1, '<?php echo $productdetails[$i]['id'] . "_" . $productdetails[$i]['type']; ?>');
                                                                    fn_saveselect();
                                                                    fn_validateproducts();"></div>
                        </div>
            <?php
        }
    }
    ?>
            </div>
        </div>
        <div class="dragAllLink" onclick="fn_movealllistitemsrequest('list20', 'list21', 0);
                            fn_saveselect();
                            fn_validateproducts();" style="cursor:pointer;cursor:hand;">add all products</div>
    </div>


    <?php
}

if ($oper == "deletereport" and $oper != " ") {
    $rptid = isset($method['id']) ? $method['id'] : 0;
    $ObjDB->NonQuery("UPDATE itc_correlation_report_data SET fld_delstatus='1', fld_deleted_by='" . $uid . "', fld_deleted_date='" . date("Y-m-d H:i:s") . "' WHERE fld_id='" . $rptid . "'");
}

@include("footer.php");
