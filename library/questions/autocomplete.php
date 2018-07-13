<?php
	@include("sessioncheck.php");
	$oper = isset($_POST['oper']) ? $_POST['oper'] : '0';

	$response = array();
	$qry = $ObjDB->QueryObject("SELECT fld_id AS id, fld_symbol_name AS symbolname, fld_symbol AS symbol FROM itc_question_answer_pattern_master WHERE fld_delstatus='0'");	
	
	while($res = $qry->fetch_assoc())
	{
		extract($res);		
		$filename = str_replace(' ', '', strtolower($symbolname));
		$response[] = array($id, $symbolname, $symbol);
	}

	@include("footer.php");

	header('Content-type: application/json');
	echo json_encode($response);