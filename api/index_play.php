<?php 
$lessonid = isset($_REQUEST['lessonid']) ? $_REQUEST['lessonid'] : '203';
?>
<html>
<head>
	<title>HTML 5 Cross Domain Test</title>
	<script language="javascript" type="text/javascript" src="jquery-1.8.3.min.js"></script>
	<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			$('body').css('overflow-y','auto');
			var cssObjOuter = {
			  'display' : 'block',
			  'width' : $('body').width(),
			  'height' : $(document).height()
			};

			$('iframe').css(cssObjOuter);
			$(document).scrollTop(0);
		});
    	</script>
</head>
<body bgcolor="#ffffff">

<table width=100%>
<tr>
	<td align=left valign=top>
		<!--<iframe name="loadlms" src="http://content.pitsco.info/vscorm/rte1.php?SCOInstanceID=1&lessonid=<?php //echo $lessonid; ?>&ipadflag=1" height="100%" width="100%"></iframe>-->
        <iframe name="loadlms" src="http://content.pitsco.info/vscorm/test.php?SCOInstanceID=1&foldername=<?php echo $lessonid; ?>&ipadflag=1" height="100%" width="100%"></iframe>
	</td>
</tr>
</table>
</body>
</html>