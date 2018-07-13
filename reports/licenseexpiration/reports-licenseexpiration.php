<?php

include("sessioncheck.php");

$years_query = "SELECT LEFT(lt.fld_end_date, 4) AS year " .
" FROM itc_license_master lm LEFT JOIN itc_license_track lt ON lm.fld_id = lt.fld_license_id " .
" WHERE LEFT(lt.fld_end_date, 4) IS NOT NULL " .
" GROUP BY year " .
" ORDER BY year ASC";
 
$yearswithlicenseexpirations = $ObjDB->QueryObject($years_query);


?>
	<script>
		function fn_licenseexpirationreport(){
			removesections('#reports-licenseexpiration')
			showpageswithpostmethod("reports-licenseexpiration","reports/reports-pdfviewer.php","id=" + $('#year').val() +"&oper=licenseexpirationreport&filename=licenseexpiration_" + (new Date()).getTime())
		}
		
		function fn_exportlicenseexpirationreport(){
			window.location = 'reports/licenseexpiration/reports-licenseexpiration-export.php?id=' + $('#year').val()
		}
	</script>

<section id="reports-licenseexpiration">
    <div class='container'>
        <div class='row'>
            <div class='twelve columns'>
                <p class="dialogTitle">License Expiration Report</p>
            </div>
        </div>
									
        <div class='row formBase rowspacer' id="minheightstyle" style="padding: 40px; position: relative;">
			<div>
				<label for='year'>Year: </label>
				<select role="options" style="width: 100%;" id="year">
				<?php
					while ($year = $yearswithlicenseexpirations->fetch_assoc()){
						echo "<option value='" . $year['year'] . "'>". $year['year'] . "</option>";
					}
				?>	
				</select>
                <!--View Report Button-->
                <div id="viewreportdiv" style="position: relative;">
                    <div style="position: absolute; right: 0;">
                        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="Export" onClick="fn_exportlicenseexpirationreport();" />
                        <input class="darkButton" type="button" id="btnstep" style="width:200px; height:42px;" value="View Report" onClick="fn_licenseexpirationreport();" />
                    </div>
                </div>

                <div style="margin-bottom: 40px;"></div>
			</div>
        </div>
    </div>
</section>
<?php
include("footer.php");