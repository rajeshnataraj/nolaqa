<?php

require_once("sessioncheck.php");

/*Remember that if this query is changed, then you should update it in pdf-genrate-code/licenseexpirationreport.php as well.*/
$year = intval($_REQUEST['id']);
$nextyear = $year + 1;
$license_expiration_query = "SELECT
 lm.fld_id AS 'License ID',
 IF(lt.fld_school_id != 0 AND lt.fld_district_id = 0, sm.fld_hubid, IF(lt.fld_district_id != 0 AND lt.fld_school_id = 0, dm.fld_hubid, NULL)) as 'Hub ID',
 lm.fld_license_name AS 'License Name',
 DATE_FORMAT(lt.fld_end_date, '%Y-%m-%d') AS 'Expiry Date',
 dm.fld_district_name AS 'District Name',
 sm.fld_school_name AS 'School Name',
 IF(lt.fld_school_id != 0 AND lt.fld_district_id = 0, sm.fld_city, IF(lt.fld_district_id != 0 AND lt.fld_school_id = 0, dm.fld_city, NULL)) as 'City',
 IF(lt.fld_school_id != 0 AND lt.fld_district_id = 0, sm.fld_state, IF(lt.fld_district_id != 0 AND lt.fld_school_id = 0, dm.fld_state, NULL)) as 'State'
FROM itc_license_master AS lm
LEFT JOIN itc_license_track AS lt ON lm.fld_id = lt.fld_license_id
LEFT JOIN itc_school_master AS sm ON lt.fld_school_id = sm.fld_id
LEFT JOIN itc_district_master AS dm ON lt.fld_district_id = dm.fld_id
LEFT JOIN itc_user_master AS um ON lt.fld_user_id = um.fld_id
WHERE lt.fld_end_date >= '$year-01-01 00:00:00' AND lt.fld_end_date < '$nextyear-01-01 00:00:00' AND ((lt.fld_district_id != '0' AND lt.fld_school_id = 0) OR (lt.fld_district_id = '0' AND lt.fld_school_id != 0))
GROUP BY lm.fld_id
ORDER BY lm.fld_license_name";


function generate_csv_string($query, $ObjDB){
	
	$output_string = '';
	$rows = $ObjDB->QueryObject($query);
	$fields_info = $rows->fetch_fields();
    while ($field = $rows->fetch_field()) {
		$output_string .= $field->name . ",";
    }
	
	if ($output_string != ''){
		$output_string = substr($output_string, 0, -1);
	}
	$output_string .= "\r\n";
	
	while ($row = $rows->fetch_assoc()){
		for ($i = 0; $i < $rows->field_count; $i++){
			$output_string .= "\"" . addslashes($row[$fields_info[$i]->name]) . "\",";
		}
		$output_string .= "\r\n";
		$output_string = substr($output_string, 0, -1);
	}
	return $output_string;
}

$csv_string = generate_csv_string($license_expiration_query, $ObjDB);

header('Content-Encoding: UTF-8,UTF-16LE');
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header("Content-Disposition: csv" . date("Y-m-d") . ".csv");
header("Content-Disposition: attachment; filename=license_expiration_report.csv");

print $csv_string;