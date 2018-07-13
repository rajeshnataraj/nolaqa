<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

        
@include("sessioncheck.php");

/*
	Created By - Mohan M
	Page - backupreports.php
*/

?>
<section data-type='#reports-backupreports' id='reports-backupreports'>
    <div class='container'>
     <script type="text/javascript" charset="utf-8">	
		$.getScript('reports/backupreports/reports-backupreports.js');
    </script>
        <div class='row'>
            <div class='twelve columns'>
            	<p class="dialogTitle">Pre/Post Test scores report</p>
				<p class="dialogSubTitleLight">Customize your report below, then press View Report.</p>
            </div>
        </div>
        <div class='row formBase rowspacer'>
            <div class='eleven columns centered insideForm'>
                
                <div class="row">
                    <div class='six columns'>
                        School
                        <div class="selectbox">
                            <input type="hidden" name="schoolid" id="schoolid" value="">
                            <a class="selectbox-toggle" style="width:100%" role="button" data-toggle="selectbox" href="#">
                                <span class="selectbox-option input-medium" data-option="" style="width:97%">Select School</span>
                                <b class="caret1"></b>
                            </a>
                            <div class="selectbox-options">
                                <input type="text" class="selectbox-filter" placeholder="Search School">
                                <ul role="options" style="width:100%">
                                    <?php 
                                    $qry = $ObjDB->QueryObject("SELECT fld_district_id AS distid, fld_id AS schoolid, fld_school_name AS schoolname 
																FROM itc_school_master WHERE fld_delstatus='0' GROUP BY schoolid
																ORDER BY fld_school_name");
                                    if($qry->num_rows>0){
                                        while($row = $qry->fetch_assoc())
                                        {
                                            extract($row);
                                            ?>
                                            <li><a tabindex="-1" href="#" data-option="<?php echo $schoolid."-".$distid;?>" onclick="fn_showclass(<?php echo $schoolid;?>,<?php echo $distid;?>)"><?php echo $schoolname; ?></a></li>
                                            <?php
                                        }
                                    }?>      
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class='six columns' id="classdiv">   
                    
                    </div>
                </div> <!-- row -->

                <input type="hidden" id="hidfilename" name="hidfilename" value="<?php echo "backupreports_"; ?>" />
                
                <div class='row rowspacer' style="display:none" id="viewreportdiv">
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-left:5px" value="Excel Unformatted" onClick="fn_export(2);" />
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right; margin-left:5px" value="Excel Formatted" onClick="fn_export(1);" />
                    <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px; float:right;" value="PDF" onClick="fn_showpassreport(1);" />
                  
                </div>
                
            </div>
        </div>
        
    </div>
</section>
<?php
	@include("footer.php");