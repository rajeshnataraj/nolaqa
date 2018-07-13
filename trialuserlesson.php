<?php
@include("sessioncheck.php");
$lesson= isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';	
//get license

$lessonids = array();
$qry_license = $ObjDB->QueryObject("SELECT a.fld_license_id AS licenseid 
									FROM itc_license_track AS a
									WHERE a.fld_delstatus='0' AND a.fld_user_id='".$indid."' 
									AND a.fld_start_date<='".date("Y-m-d H:i:s")."' AND a.fld_end_date>='".date("Y-m-d H:i:s")."' GROUP BY licenseid");


if($qry_license->num_rows>0){
	while($res_license=$qry_license->fetch_assoc()){ 
		extract($res_license);		                  
			$qry = $ObjDB->QueryObject("SELECT b.fld_id AS lessonid, b.fld_ipl_name AS lessonname, b.fld_ipl_icon AS lessonicon FROM itc_license_cul_mapping AS a, itc_ipl_master AS b WHERE a.fld_lesson_id=b.fld_id  AND a.fld_license_id='".$licenseid."' AND b.fld_delstatus='0' AND b.fld_access='1' AND a.fld_active='1' GROUP BY b.fld_id");					
		
	}
	if($qry->num_rows>0){
		while($res=$qry->fetch_assoc()){
			extract($res);
			$lessonids[]=$lessonid;
		}
		$key = array_search($lesson, $lessonids);
		$nxtlesson = $lessonids[$key+1];
		$prelesson = isset($lessonids[$key-1]);
		if($prelesson==''){
			$prelesson=0;
		}
		if($nxtlesson==''){
			$nxtlesson=0;
		}
	}
}
else{
	header("Location:index.php");
}
if($lesson!=0){
	
	$lessonids[0]= $lesson;
}
$lessonname = $ObjDB->SelectSingleValue("SELECT fld_ipl_name FROM itc_ipl_master WHERE fld_id='".$lessonids[0]."'");
//get lessons from license for trial user
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $lessonname; ?></title>
<link href='css/gzip-css.php' rel='stylesheet' type="text/css" />
<script language="javascript" type="text/javascript" src='js/jquery.js'></script>
<script>
	$(document).ready(function () {
		$('body').css('overflow','hidden');
	
		var cssObjOuter = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : $(document).height()
		};
		
		var cssObjInner = {
		  'display' : 'block',
		  'width' : $('body').width(),
		  'height' : $(window).height() - 90
		};
		$('body').append('<div id="divcustomlightbox" title="Synergy ITC"><div class="btnprevclose"><p class="dialogTitleFullScr"><?php echo $lessonname; ?></p><a href="javascript:void(0);" onclick="closefullscreenlesson()" class="icon-synergy-close-dark"></a></div><div id="divlbcontent"></div><div class="diviplbotto"><p class="dialogTitleFullScr" id="fottitle"></p></div></div>');
		$('#divcustomlightbox').css(cssObjOuter);
		$('#divlbcontent').css(cssObjInner);
		$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
		$(document).scrollTop(0);
		
	});
	
	$(window).resize(function() {
		if($('#divcustomlightbox').length){
			var cssObjOuter = {
			  'display' : 'block',
			  'width' : $('body').width(),
			  'height' : $(window).height()
			};
				
			var cssObjInner = {
			  'display' : 'block',
			  'width' : $('body').width(),
			  'height' : $(window).height() - 90
			};
			
			$('#divcustomlightbox').css(cssObjOuter);
			$('#divlbcontent').css(cssObjInner);
			$('iframe').css({ 'width':$('#divlbcontent').width(), 'height' : $('#divlbcontent').height() });
		}
	});
	fn_showtriallessons(<?php echo $lessonids[0]; ?>,<?php echo $nxtlesson; ?>,<?php echo $prelesson; ?>);
	
	function fn_showtriallessons(lessonid,nxt,pre){	
		var dataparam = "oper=showtriallessons&lessonid="+lessonid+"&nxtlesson="+nxt+"&prelesson="+pre;
		$.ajax({
			type: 'post',
			url: 'trialuserlesson-ajax.php',
			data: dataparam,
			beforeSend: function(){
			},
			success:function(data) {
				$('#divlbcontent').html(data);
			}
		});
	}
	
/*
function to close the fullscreen lesson play
*/
function closefullscreenlesson(){	
	$('body').css('overflow','auto');
	$('#divcustomlightbox').remove();
	$('#divlbcontent').remove();
	window.location="index.php";
}
</script>
</head>

<body>
</body>
</html>
<?php
	@include("footer.php");