<?php
@include("sessioncheck.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
$id = isset($method['id']) ? $method['id'] : '';
$id = explode(",", $id);
$qrydetails = $ObjDB->QueryObject("SELECT fld_id as prdid ,fld_prd_type as ptype,fld_prd_name as pname,fld_prd_version as pversion,fld_prd_asset_name as assetname,fld_asset_id as assetid FROM itc_correlation_productdetails WHERE fld_id='" . $id[1] . "' AND  fld_delstatus='0' ");
$row = $qrydetails->fetch_assoc();
extract($row);
?>
<script language="javascript">
    $('#tablecontents').slimscroll({
        height: 'auto',
        railVisible: false,
        allowPageScroll: false,
        size: '7px',
        alwaysVisible: true,
        railColor: '#F4F4F4',
        opacity: 9,
        color: '#88ABC2',
        wheelStep: 1
    });
</script>
<section data-type='2home' id='tools-correlation-viewproductdetail'>

    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">View details</p>
                <p class="dialogSubTitleLight">&nbsp;</p>
            </div>
        </div>

        <div class='row formBase'>
            <div class='eleven columns centered insideForm'>
                <div class="row" style="max-height:400px;width:100%;" id="tablecontents">

                    <div class="twelve columns">
                        <?php $ptitle = $ObjDB->SelectSingleValue("SELECT fld_title_name 
                                            FROM itc_correlation_producttitles 
                                            WHERE fld_title_type='" . $id[0] . "' AND  fld_delstatus='0'"); ?>
                        <div class='row'>
                            <div class='six columns' >
                                <div class="wizardReportDesc" style="float:left;">Title&nbsp;:</div>
                                <div class="wizardReportData">&nbsp;&nbsp;<?php echo $ptitle; ?></div>
                            </div>   
                        </div>
                        <div class='row'>
                            <div class='four columns' >
                                <div class="wizardReportDesc" style="float:left;">Product Name&nbsp;:</div>
                                <div class="wizardReportData" >&nbsp;&nbsp;<?php echo $pname; ?></div>

                            </div>
                            <div class='four columns'>
                                <div class="wizardReportDesc" style="float:left;">Version&nbsp;:</div>
                                <div class="wizardReportData">&nbsp;&nbsp;<?php echo $pversion; ?></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='four columns'>
                                <div class="wizardReportDesc" style="float:left;">Asset Name:</div>

                                <div class="wizardReportData">&nbsp;&nbsp;<?php echo $assetname; ?></div>
                            </div>
                            <div class='four columns'>
                                <div class="wizardReportDesc" style="float:left;">Asset ID:</div>

                                <div class="wizardReportData">&nbsp;&nbsp;<?php echo $assetid; ?></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='four columns'>

                                <div class="wizardReportDesc">Subject:</div>

                                <?php
                                $qrydetail = $ObjDB->QueryObject("SELECT a.fld_subject_name as subname from itc_correlation_productsubject as a 
																	left join itc_correlation_productmapping as b on a.fld_id = b.fld_subject_id
																	where a.fld_delstatus='0' AND b.fld_delstatus='0'AND b.fld_product_id='" . $id[1] . "' ");
                                while ($row = $qrydetail->fetch_assoc()) {


                                    extract($row);
                                    ?>
                                    <div class="wizardReportData">&nbsp;&nbsp;<?php echo $subname; ?></div>
                                    <?php
                                }
                                ?>	
                            </div> 

                            <div class='four columns'>
                                <div class="wizardReportDesc" >Grade:</div>
                                <?php
                                $qrydetail = $ObjDB->QueryObject("SELECT a.fld_grade_name as gradename from itc_correlation_productgrade as a 
																	left join itc_correlation_productmapping as b on a.fld_id = b.fld_grade_id
																	where  b.fld_product_id='" . $id[1] . "' AND b.fld_delstatus='0'");
                                while ($row = $qrydetail->fetch_assoc()) {


                                    extract($row);
                                    ?>
                                    <div class="wizardReportData">&nbsp;&nbsp;<?php echo $gradename; ?></div>
                                    <?php
                                }
                                ?>	
                            </div>
                            <div class='four columns'>
                                <div class="wizardReportDesc" >Product set:</div>
                                <?php
                               
                                $qrydetail = $ObjDB->QueryObject("SELECT a.fld_productset_name as productsetname from itc_correlation_productset as a 
																	left join itc_correlation_productmapping as b on a.fld_id = b.fld_productset_id
																	where a.fld_delstatus='0' AND b.fld_delstatus='0' AND b.fld_product_id='" . $id[1] . "' ");
                                while ($row = $qrydetail->fetch_assoc()) {


                                    extract($row);
                                    ?>
                                    <div class="wizardReportData">&nbsp;&nbsp;<?php echo $productsetname; ?></div>
                                    <?php
                                }
                                ?>	
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
        </div>


    </div>
</section>
<?php
@include("footer.php");
