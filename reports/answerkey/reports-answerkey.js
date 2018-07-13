
function fn_viewreport(flg){
	removesections("#reports-answerkey");
	var oper = "assessmentquestionanskey";
	var val = $('#assid').val();
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&flg='+flg+'");',500);
}