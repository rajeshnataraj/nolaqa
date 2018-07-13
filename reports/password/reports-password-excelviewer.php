<?php
	/** Include PHPExcel */
	@include('sessioncheck.php');
	require_once __EXACTPATH__.'PHPExcel.php';
	
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$id = explode("~",$id);
	$exqry="";
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	$extraqry = '';
	
	if($sessmasterprfid==2 || $sessmasterprfid==3)
	{
	    if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] == ''){ // for all district user reports 
			$distqry = $ObjDB->QueryObject("SELECT fld_id AS districtid, fld_district_name AS districtname 
											FROM itc_district_master 
											WHERE fld_id='".$id[0]."'");
			if($distqry->num_rows > 0){
				while($rowdist = $distqry->fetch_assoc()){
					extract($rowdist);
				}
			}
			$filename = $districtname." - DistrictReport";
		}
	  	else if($id[0] != '' and $id[0] != 'school' and  $id[0] != 'home' and $id[1] !='') // for all school user reports 
	    {
		   	$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
											FROM itc_school_master 
											WHERE fld_id ='".$id[1]."'");
			if($distqry->num_rows > 0){
				while($rowdist = $distqry->fetch_assoc()){
					extract($rowdist);
					$filename = $districtname." - SchoolReport";
					$extraqry = "AND fld_id='".$rptschoolid."'";
				}
			}
	    }
	  
	    else if($id[0] == 'school' and $id[1] !='') // for all school purchase reports 
	    {
		   	$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
											FROM itc_school_master 
											WHERE fld_id ='".$id[1]."' and  fld_district_id='0' ");
			if($distqry->num_rows > 0){
				while($rowdist = $distqry->fetch_assoc()){
					extract($rowdist);
					$filename = $districtname." - School purchase Report";
					$extraqry = "AND fld_id='".$rptschoolid."'";
				}
			}
	    }
	  
	    else if($id[0] == 'home' and $id[1] !='') // for all school purchase reports 
	    {
			$username=$ObjDB->SelectSingleValue("SELECT  CONCAT(`fld_fname`,' ',`fld_lname` ) AS fullname ,fld_id 
												FROM `itc_user_master`  
												WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' AND fld_user_id='".$id[1]."' 
												ORDER BY fld_lname");
			$districtid = 0;
			$districtname = $username."- home purchase reports";
			$filename = $districtname;
			$extraqry = "";
			$exqry="and a.fld_user_id='".$id[1]."'";  
	    }	 	
	}
	
	
	if($sessmasterprfid==6)
	{
		if($id[1] != ''){
			if($id[1] != 0) {
				$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
												FROM itc_school_master 
												WHERE fld_id ='".$id[1]."'");
				if($distqry->num_rows > 0){
					while($rowdist = $distqry->fetch_assoc()){
						extract($rowdist);
					}
				}
			}
			$filename = $districtname." SchoolReport";
			$extraqry = "AND fld_id='".$rptschoolid."'";
		}
	}
	
	if($sessmasterprfid==7)
	{
		$distqry = $ObjDB->QueryObject("SELECT fld_id AS rptschoolid, fld_district_id AS districtid, fld_school_name AS districtname 
										FROM itc_school_master 
										WHERE fld_id ='".$schoolid."'");
		if($distqry->num_rows > 0){ 
			while($rowdist = $distqry->fetch_assoc()){
				extract($rowdist);
			}
		}
		$filename = $districtname." SchoolReport";
		$extraqry = "AND fld_id='".$rptschoolid."'";
	}
	
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("PITSCO")
								 ->setLastModifiedBy("PITSCO")
								 ->setTitle($districtname)
								 ->setSubject($districtname)
								 ->setDescription($districtname)
								 ->setKeywords($districtname)
								 ->setCategory("DISTRICT DETAILS");
	
	
	$styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			)
		),
	); 
	// Add some data
	if($id[0]!='home')
	{
	  	$name='School Name';
	}
	else
	{
		$name='User Name';
	}
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C5', $name)
				->setCellValue('D5', 'User Type')
				->setCellValue('E5', 'Full Name')
				->setCellValue('F5', 'E-Mail')
				->setCellValue('G5', 'Username')
				->setCellValue('H5', 'Password');
				
				
	$objPHPExcel->getActiveSheet()->getStyle('C5:H5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('D5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('G5')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('H5')->applyFromArray($styleThinBlackBorderOutline);
	
	if($id[0] != 'home')
	
	{
		$schqry = $ObjDB->QueryObject("SELECT fld_id AS schid, fld_school_name AS schname 
										FROM itc_school_master 
										WHERE fld_district_id='".$districtid."' ".$extraqry." and fld_delstatus='0'");
		
		$rowid = 6;
		if($schqry->num_rows > 0){
			while($rowsch = $schqry->fetch_assoc()){
				extract($rowsch);
				
				$firstcell = $rowid;
				
				$schuserqry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_email, c.fld_prf_main_id, c.fld_profile_name, 
														CONCAT(TRIM(a.fld_lname),' ',TRIM(a.fld_fname)) AS fullname, a.fld_username, a.fld_password 
													FROM itc_user_master AS a LEFT JOIN itc_school_master AS b ON a.fld_school_id=b.fld_id 
													LEFT JOIN itc_profile_master AS c ON a.fld_profile_id=c.fld_id 
													WHERE a.fld_district_id=".$districtid." AND a.fld_school_id=".$schid." AND c.fld_prf_main_id<>11 
														AND a.fld_delstatus='0' ".$exqry." 
													ORDER BY c.fld_prf_main_id, a.fld_lname");
				if($schuserqry->num_rows > 0){
					while($rowschqry = $schuserqry->fetch_object()){
						$Dcolname = 'D'.$rowid;
						$Ecolname = 'E'.$rowid;
						$Fcolname = 'F'.$rowid;
						$Gcolname = 'G'.$rowid;
						$Hcolname = 'H'.$rowid;
						
						$password = fnDecrypt($rowschqry->fld_password, $encryptkey);
						if($rowschqry->fld_username==''){ $password='';}					
						
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($Dcolname, $rowschqry->fld_profile_name)
							->setCellValue($Ecolname, $rowschqry->fullname)
							->setCellValue($Fcolname, $rowschqry->fld_email)
							->setCellValue($Gcolname, $rowschqry->fld_username)
							->setCellValue($Hcolname, $password);
						
						$objPHPExcel->getActiveSheet()->getStyle($Dcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Ecolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Fcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Gcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Hcolname)->applyFromArray($styleThinBlackBorderOutline);
						
						if($rowschqry->fld_prf_main_id == 10) {
							$setcolorcol = $Dcolname.":".$Hcolname;
							$objPHPExcel->getActiveSheet()->getStyle($setcolorcol)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($setcolorcol)->getFill()->getStartColor()->setARGB('FFFF00');	
						}
						$rowid++;
					}
				}
			
				$mergecell = "C".$firstcell.":C".($rowid-1);
				$schcell = "C".$firstcell;		
				$objPHPExcel->getActiveSheet()->mergeCells($mergecell);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($schcell, $schname);
				$objPHPExcel->getActiveSheet()->getStyle($schcell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($schcell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($mergecell)->applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle($mergecell)->getAlignment()->setWrapText(true);
			}
		}
	}
	  
	if($id[0] == 'home')
	{  
		$schqry = $ObjDB->QueryObject("SELECT  CONCAT(`fld_fname`,' ',`fld_lname` ) AS fullname ,fld_id 
										FROM `itc_user_master` 
										WHERE fld_district_id='0' AND fld_school_id='0' AND fld_profile_id='5' AND fld_user_id='".$id[1]."' 
										ORDER BY fld_lname");
		$schcnt=0;
		$rowid = 6;
		if($schqry->num_rows > 0){
			while($rowsch = $schqry->fetch_object()){
				$schid = $rowsch->fld_id;
				$schname = $rowsch->fullname;				
				$firstcell = $rowid;
				
				$schuserqry = $ObjDB->QueryObject("SELECT a.fld_id, a.fld_email, c.fld_prf_main_id, c.fld_profile_name, 
														CONCAT(TRIM(a.fld_fname),' ',TRIM(a.fld_lname)) AS fullname, a.fld_username, a.fld_password 
													FROM itc_user_master AS a 
													LEFT JOIN itc_school_master AS b ON a.fld_school_id=b.fld_id 
													LEFT JOIN itc_profile_master AS c ON a.fld_profile_id=c.fld_id 
													WHERE a.fld_district_id=".$districtid." AND a.fld_school_id=0 AND c.fld_prf_main_id<>11 AND a.fld_delstatus='0' ".$exqry." 
														ORDER BY c.fld_prf_main_id, a.fld_lname");
				if($schuserqry->num_rows > 0){
					while($rowschqry = $schuserqry->fetch_object()){
						$Dcolname = 'D'.$rowid;
						$Ecolname = 'E'.$rowid;
						$Fcolname = 'F'.$rowid;
						$Gcolname = 'G'.$rowid;
						$Hcolname = 'H'.$rowid;
						
						$password = fnDecrypt($rowschqry->fld_password, $encryptkey);
						if($rowschqry->fld_username==''){ $password='';}					
						
						$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue($Dcolname, $rowschqry->fld_profile_name)
							->setCellValue($Ecolname, $rowschqry->fullname)
							->setCellValue($Fcolname, $rowschqry->fld_email)
							->setCellValue($Gcolname, $rowschqry->fld_username)
							->setCellValue($Hcolname, $password);
						
						$objPHPExcel->getActiveSheet()->getStyle($Dcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Ecolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Fcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Gcolname)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($Hcolname)->applyFromArray($styleThinBlackBorderOutline);
						
						if($rowschqry->fld_prf_main_id == 10) {
							$setcolorcol = $Dcolname.":".$Hcolname;
							$objPHPExcel->getActiveSheet()->getStyle($setcolorcol)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
							$objPHPExcel->getActiveSheet()->getStyle($setcolorcol)->getFill()->getStartColor()->setARGB('FFFF00');	
						}
						$rowid++;
					}
				}
			
				$mergecell = "C".$firstcell.":C".($rowid-1);
				$schcell = "C".$firstcell;		
				$objPHPExcel->getActiveSheet()->mergeCells($mergecell);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($schcell, $schname);
				$objPHPExcel->getActiveSheet()->getStyle($schcell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($schcell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle($mergecell)->applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle($mergecell)->getAlignment()->setWrapText(true);		
	 		}		
		} 		
	}
	 
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Simple');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	@include("footer.php");
	// Redirect output to a client's web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>