<?php 
@include("../includes/table.class.php");
@include("../includes/comm_func.php");
$licenseid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '0';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Trial Registration</title>

<link href='../css/gzip-css.php' rel='stylesheet' type="text/css" />
<link rel="stylesheet" href="../css/pitscochart.css" type="text/css" />
<script language="javascript" type="text/javascript" src="../jquery-ui/js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script language="javascript" type="text/javascript" src='../js/jquery.validate.js'></script>
<script language="javascript" type="text/javascript" src='../js/jquery.validate.additional.js'></script>
<script language="javascript" type="text/javascript" src="trial.js"></script>
</head>

<body>

	<section class='bluewindow2'>
    	<div class='container'>
        	<div class='row formBase'>
            	<div class='eleven columns centered insideForm'>
                	<div id="body">
                    	<div class="tRight">
                            <input type="button" id="btnstep" style="float: right;height: 32px;margin-left: -198px;width: 100px;" value="login" onClick="window.location='../login.php'" />
                        </div>
                    	<div class="body-header-text">Thanks for Registering!</div>
                        <div id="body-outer">
                        	 <div class="mt-box"></div>
                        	 <p>To test drive our<span class="text-bold"> Signature Math </span>curriculum and get a whole new angle on algebra, complete the form below and we'll send you the companion culminating, hands-on group activity for the Angles unit of <span class="text-bold">Signature Math.</span> These hands-on activities give students a real-world learning experience that makes the algebra concepts they are learning online meaningful and relevant.</p>
			                <p> If you are in need of technical assistance, don't hesitate to contact our industry-leading customer support line at 800-774-4552. </p>
            			    <p>Welcome to <span class="text-bold">Signature Math,</span> a whole new angle on algebra! </p><br />
                            <form id="newuserform" name="newuserform"  enctype="multipart/form-data">
                                <table width="66%" cellspacing="0" cellpadding="0" class="box">
                                    <tr>
                                        <td class="text-bold">First Name <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td ><input type="text" id="fname" name="fname"  placeholder="Enter your first name." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Last Name <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="lname" name="lname"  placeholder="Enter your last name." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Email Name <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="email" name="email"  placeholder="Enter your email." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">District <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="district" name="district"  placeholder="Enter your district name." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">School <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="school" name="school"  placeholder="Enter your school name." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Title </td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="title" name="title"  placeholder="Enter your title." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">State <span class="fldreq">*</span> </td>
                                    </tr>
                                    <tr height="40">
                                        <td >
                                            <select id="ddlstate" name="ddlstate" class="dropdown-medium" onChange="fn_changecity(this.value);">
                                                <option value="">Select your state.</option>
                                                <?php
                                                $stateqry = $ObjDB->QueryObject("SELECT DISTINCT(fld_statevalue), fld_statename FROM itc_state_city 
																				WHERE fld_delstatus=0 
																				ORDER BY fld_statename ASC");
                                                while($rowstate = $stateqry->fetch_object()){
                                                ?>
                                                    <option value="<?php echo $rowstate->fld_statevalue; ?>"><?php echo $rowstate->fld_statename; ?></option>
                                                <?php	
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">City</td>
                                    </tr>
                                    <tr height="40">
                                        <td>
                                            <div id="divddlcity">
                                                <select id="ddlcity" name="ddlcity" class="dropdown-medium" onChange="fn_changecity(this.value);" disabled="disabled">
                                                    <option value="">Select your city.</option>                                   
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Zip</td>
                                    </tr>
                                    <tr height="40"> 
                                        <td ><input type="text" id="zip" name="zip"  placeholder="Enter your zip code." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Street Address</td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="saddress" name="saddress"  placeholder="Enter your street address." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr>
                                        <td class="text-bold">Phone Number <span class="fldreq">*</span></td>
                                    </tr>
                                    <tr height="40">
                                        <td><input type="text" id="pnumber" name="pnumber"  placeholder="Enter your phone number." class="textstyle-medium"/></td>
                                    </tr>
                                    <tr height="40">
                                        <td><span class="fldreq">*</span>&nbsp;Required fields</td>
                                    </tr>
                                    <tr height="55">
                                        <td align="center" valign="top"><input type="button" value="Register" class="trial-btn-submit btnfull" style="width:100px;" onClick="fn_submit()" /></td>
                                    </tr>
                                </table>
                            	<input type="hidden" id="licenseid" value="<?php echo $licenseid; ?>" />
                            </form>
                        </div>
                    </div>
            	</div>
          	</div>
        </div>
        <script language="javascript" type="text/javascript">
            $(function(){
				$("#newuserform").validate({
					rules: { 						
					fname: { required: true, lettersonly: true },
					lname: { required: true, lettersonly: true },						
					email: { required: true, email: true, remote:{ 
												url: "trial-ajax.php", 
												type:"POST",  
												data: {  
														email: function() {
														return $('#email').val();},
														oper: function() {
														return 'checkemail';}
														  
												 },
												 async:false 
										   }},										   
					district: { required: true},
					school: { required: true},
					ddlstate: { required: true},
					pnumber: { required: true }				
				}, 
				messages: {
					fname: { required: "This field is required"},
					lname: { required: "This field is required"},						
					email: { required: "This field is required", email: "Enter a valid email", remote:"Email already exists"},
					district: { required: "This field is required"},
					school: { required: "This field is required"},
					ddlstate: { required: "This field is required"},
					pnumber: { required: "This field is required",required: "This field is required"}
				}, 
				errorClass: 'popupError',
				errorPlacement: function(error, element) {
					var offset = $(element).offset();
					error.insertAfter(element);
					error.offset({ top: (offset.top-30), left: (offset.left+130) });
					$('.popupError span').remove();
				}, 
				onkeyup: false,
				onblur: true
				});				
            
            });	
      	</script>
        
	</section>
    
</body>
</html>
<?php
	@include("footer.php");