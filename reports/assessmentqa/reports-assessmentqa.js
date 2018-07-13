/***
created by: Vijayalakshmi PHP Programmer
created on :23/12/2014
***/

function fn_openassessreport(studentid) {

val = $('#assessid').val()+","+studentid;

	setTimeout('removesections("#reports-assessmentqa");',500);
	oper="assessmentqanda";
	filename=$("#hidtestname").val()+new Date().getTime();
	ajaxloadingalert('Loading, please wait.');
	setTimeout('showpageswithpostmethod("reports-pdfviewer","reports/reports-pdfviewer.php","id='+val+'&oper='+oper+'&filename='+filename+'");',500);
}
