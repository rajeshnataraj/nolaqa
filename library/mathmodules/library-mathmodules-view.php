<?php
@include("sessioncheck.php");

$mathmoduleid = isset($method['id']) ? $method['id'] : '0';

$moduleqry = $ObjDB->QueryObject("SELECT CONCAT(a.fld_mathmodule_name,' ',c.fld_version) AS mathmodulename, a.fld_module_id, a.fld_ipl_day1, a.fld_ipl_day2,                                 b.fld_module_name, c.fld_file_name 
                                 FROM itc_mathmodule_master AS a 
								 LEFT JOIN itc_module_master AS b ON b.fld_id = a.fld_module_id 
								 LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=a.fld_module_id 
								 WHERE a.fld_id='".$mathmoduleid."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' AND c.fld_delstatus='0'");
	
	$rowmodule=$moduleqry->fetch_assoc();
	extract($rowmodule);

	$modulename = $fld_module_name;
	$filename = $fld_file_name;
	$mathmodulename = $mathmodulename;
	$moduleid = $fld_module_id;
	$iplday1 = $fld_ipl_day1;
	$iplday2 = $fld_ipl_day2;
	$msg = "View ".$mathmodulename;
	
?>	
<section data-type='2home' id='library-mathmodules-view'>
	<script language="javascript" type="text/javascript">
		$.getScript("assignment/sigmath/assignment-sigmath-test.js");
		$.getScript("library/lessons/library-lessons-newlesson.js");
    </script>
    
    <div class='container'>
    	<!--Load the Math Module Name / New Math Module-->
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle"><?php echo $msg; ?></p>
                <p class="dialogSubTitle">&nbsp;</p>
            </div>
        </div>
        
        <!--Load the Math Module Form-->
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                <!--Session Buttons in View-->
                <div class='row'>
                    <span>Preview Sessions : </span>
                </div>
                
                <div class='row rowspacer'>
                    <?php
                    $j=1;
                    for($i=0;$i<8;$i++)
                    { 
                        $tempe = $j;
                        if($tempe=='8'){
                            $tempe='e';
                                } 
                        ?>
                        <a class='skip btn sm<?php echo $tempe; ?> main' href="javascript:void(0);" id='btnlibrary-modules-player' onClick="showfullscreenmodule('<?php echo $i.",".$moduleid.",".$mathmoduleid.",".$uid;?>',2);">
                            <div class="onBtn"></div>
                        </a><?php
                        $j++;
                    }
                    ?>
                </div>
                
                <div class='row rowspacer'>
                    <span>Diagnostic Day1 : </span>
                </div>
                
                <?php 
				$ipl1 = explode(",",$iplday1);
				for($i=0;$i<sizeof($ipl1);$i++) 
				{ 
					$qry = $ObjDB->QueryObject("SELECT a.fld_ipl_name, a.fld_id, b.fld_version, b.fld_zip_name AS zipname 
					                    FROM itc_ipl_master AS a 
										     LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
									    WHERE a.fld_id='".$ipl1[$i]."' AND a.fld_delstatus='0' AND a.fld_access='1' AND b.fld_zip_type='1' AND                                         b.fld_delstatus='0'");
										
					$row=$qry->fetch_assoc();
					extract($row);
					
					?>
                    <div class='row rowspacer'>
                        <div class='six columns'><div class="wizardReportData"><?php echo $fld_ipl_name." ".$fld_version;?></div></div>
                        <div class='six columns'>
                            <div class='three columns'>
                            	<div class='mainBtn' style="cursor:pointer; color:#396" name="0~<?php echo $fld_id; ?>~fn_diagnosticstart(0,<?php echo $fld_id;?>,10)~10" id="btnassignment-sigmath-test">Diagnostic</div>
                            </div>
                            <div class='three columns'>
                            	<div class='main' style="cursor:pointer; color:#36F" onClick="fn_previewlesson('<?php echo $zipname; ?>',<?php echo $fld_id; ?>);" id="btnassignment-sigmath-test">Lesson</div>
                            </div>
                            <div class='three columns'>
                                <div class='mainBtn' style="cursor:pointer; color:#90F" name="0~<?php echo $fld_id; ?>~fn_startmastery1(0,<?php echo $fld_id;?>,0)~10" id="btnassignment-sigmath-test">Mastery1</div>
                            </div>
                            <div class='three columns'>
                                <div class='mainBtn' style="cursor:pointer; color:#90F" name="0~<?php echo $fld_id; ?>~fn_startmastery2(0,<?php echo $fld_id;?>,0)~10" id="btnassignment-sigmath-test">Mastery2</div>
                            </div>
                        </div>
                    </div>
                <?php }?>
                
                <div class='row rowspacer' style="padding-top:40px;">
                    <span>Diagnostic Day2 : </span>
                </div>
                
                <?php 
				$ipl2 = explode(",",$iplday2);
				for($i=0;$i<sizeof($ipl2);$i++) 
				{ 
					$qry = $ObjDB->QueryObject("SELECT a.fld_ipl_name AS iplname, a.fld_id AS iplid, b.fld_version AS version, b.fld_zip_name AS zipname1 
					                    FROM itc_ipl_master AS a 
										     LEFT JOIN itc_ipl_version_track AS b ON a.fld_id=b.fld_ipl_id 
										WHERE a.fld_id='".$ipl2[$i]."' AND a.fld_delstatus='0' AND a.fld_access='1' AND b.fld_zip_type='1' AND                                             b.fld_delstatus='0'");
										
					$row=$qry->fetch_assoc();
					extract($row);

					?>
                    <div class='row rowspacer'>
                        <div class='six columns'><div class="wizardReportData"><?php echo $iplname." ".$version;?></div></div>
                        <div class='six columns'>
                            <div class='three columns'>
                            	<div class='mainBtn' style="cursor:pointer; color:#396" name="0~<?php echo $iplid; ?>~fn_diagnosticstart(0,<?php echo $iplid;?>,10)~10" id="btnassignment-sigmath-test">Diagnostic</div>
                            </div>
                            <div class='three columns'>
                                <div class=' main' style="cursor:pointer; color:#36F" onClick="fn_previewlesson('<?php echo $zipname1; ?>',<?php echo $iplid; ?>);" id="btnassignment-sigmath-test">Lesson</div>
                            </div>
                            <div class='three columns'>
                                <div class='mainBtn' style="cursor:pointer; color:#90F" name="0~<?php echo $iplid; ?>~fn_startmastery1(0,<?php echo $iplid;?>,0)~10" id="btnassignment-sigmath-test">Mastery1</div>
                            </div>
                            <div class='three columns'>
                                <div class='mainBtn' style="cursor:pointer; color:#90F" name="0~<?php echo $iplid; ?>~fn_startmastery2(0,<?php echo $iplid;?>,0)~10" id="btnassignment-sigmath-test">Mastery2</div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</section>
<?php
	@include("footer.php");