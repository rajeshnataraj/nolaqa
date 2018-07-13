<?php
$oper = (isset($_REQUEST['oper'])) ? $_REQUEST['oper'] : '';

if($oper == "download") {
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"userprofile.csv\"");
	$data=stripcslashes($_REQUEST['csv_text']);
	echo $data;
}	
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Module - Asset ID Report </title>

<style type="text/css">
body, html  { height: 50%; }
html, body, div, span, applet, object, iframe,
/*h1, h2, h3, h4, h5, h6,*/ p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, left,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-size: 100%;
	vertical-align: baseline;
	background: transparent;
}
body { line-height: 1; }
ol, ul { list-style: none; }
blockquote, q { quotes: none; }
blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; }
:focus { outline: 0; }
del { text-decoration: line-through; }
table {border-spacing: 0; } /* IMPORTANT, I REMOVED border-collapse: collapse; FROM THIS LINE IN ORDER TO MAKE THE OUTER BORDER RADIUS WORK */

/*------------------------------------------------------------------ */

/*This is not important*/
body{
	font-family:Arial, Helvetica, sans-serif;
}
table a:hover {
	color: #bd5a35;
	text-decoration:underline;
}

table {
	font-family:Arial, Helvetica, sans-serif;
	color:#666;
	font-size:14px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin:20px 0px 0px 410px;
	border:#ccc 1px solid;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table th {
	padding:12px 25px 12px;
	/*border-top:5px solid #fafafa;*/
	border-bottom:1px solid #e0e0e0;

	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child{
	text-align: center;
}
table tr:first-child th:first-child{
	-moz-border-radius-topleft:3px;
	-webkit-border-top-left-radius:3px;
	border-top-left-radius:3px;
}
table tr:first-child th:last-child{
	-moz-border-radius-topright:1px;
	-webkit-border-top-right-radius:3px;
	border-top-right-radius:3px;
}
table tr{
	text-align: left;
	padding-left:20px;
}
table tr td:first-child{
	text-align: center;
	border-left: 0;
}
table tr td {
	padding:10px 20px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;
	
	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td{
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td{
	border-bottom:0;
}
table tr:last-child td:first-child{
	-moz-border-radius-bottomleft:10px;
	-webkit-border-bottom-left-radius:3px;
	border-bottom-left-radius:3px;
}
table tr:last-child td:last-child{
	-moz-border-radius-bottomright:3px;
	-webkit-border-bottom-right-radius:3px;
	border-bottom-right-radius:3px;
}
table tr:hover td{
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}
.button {
 display: block;
 background:#62869b;
 width: 170px;
 height: 41px; 
 padding: 30px 40px 30px 50;
 font: 0.9em/6px Verdana, Arial, Helvetica, sans-serif;
 color: #fff;
 text-decoration:none;
 -webkit-border-radius: 15px;
 -khtml-border-radius: 15px;
 -moz-border-radius: 15px;
 border-radius:7px; 
 cursor: pointer;
 margin-left: 400px;
 margin-top: 30px;
 }
 .button:hover {
 background: #3e5a6b;
 }
</style>

	<script type="text/javascript" src="../jquery-ui/js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="exportcsv/ExportHTMLTable.js"></script>

</head>
<body>
<?php 
 @include('../includes/table.class.php');
 @include('../includes/comm_func.php');
 ?> 


<form action="user-profile.php" method ="post" >

	<span align="center"><input type="submit" class="button" onclick="getCSVData();" value="Export as CSV"></span>

	<table cellspacing='0' id="csv"> 
		<tr>
                    <th>Sno</th>
	            <th>ID</th>
                    <th>Level</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Password</th>
	        </tr>
		<?php
			$i=1;
			$query=$ObjDB->QueryObject("SELECT a.fld_id AS uid, b.fld_profile_name AS sessprofilename, a.fld_username AS uname, CONCAT(a.fld_fname,' ',a.fld_lname) AS name,
    a.fld_password AS password, a.fld_created_by AS createdby FROM itc_user_master AS a, itc_profile_master AS b WHERE a.fld_profile_id=b.fld_id AND (b.fld_delstatus = 0 OR b.fld_delstatus=2) AND b.fld_id in(8,9) AND a.fld_activestatus='1' AND a.fld_delstatus='0'");

			while($row=$query->fetch_assoc())
			{
			  extract($row);  
		?>
	    
			<tr>
				<td><?php echo $i;?></td>
                                <td><?php echo $uid; ?></td>
                                <td><?php echo $sessprofilename; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $uname; ?></td>
                                <td><?php echo fnDecrypt($password,$encryptkey); ?></td>
			</tr>
		<?php 
			$i++;
			}
		?>
	</table>
	<input type="hidden" name="oper" id="oper" value="download" />
	<input type="hidden" name="csv_text" id="csv_text" />
	<script>
		function getCSVData(){
		 var csv_value=$('#csv').table2CSV({delivery:'value'});
		 $("#csv_text").val(csv_value);	
		}
	</script>
</form>
</html>
<?php
}