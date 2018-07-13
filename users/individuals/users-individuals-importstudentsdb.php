<?php
/* updated by: Vijayalakshmi PHP Programmer

updated on:21/11/2014(Selecting class name)


*/

@include("sessioncheck.php");

$oper = isset($method['oper']) ? $method['oper'] : '';
$date = date("y-m-d H:i:s");
if($oper == "importstudents" and $oper != '') 
{
    $temp=0;
	
	$j=0;
	
	$a=0;

	$k=0;
	
	$b=0;
	
	$duplicateid='';

	$path =(isset( $method['path'])) ?  $method['path'] : '';
	$classid =(isset( $method['classid'])) ?  $method['classid'] : '';
	$checboxuser =(isset( $method['chkboxuser'])) ?  $method['chkboxuser'] : '';
	$checboxpass =(isset( $method['chkboxpass'])) ?  $method['chkboxpass'] : '';	
	
	@include(__EXACTPATH__.'PHPExcel/IOFactory.php');
	require_once __EXACTPATH__.'PHPExcel/Writer/CSV.php'; 
 	$inputFileName = '../../uploaddir/importstudents/'.$path;
	
	$data=array(); // 
	$vals=array(); //
	$val=array(); // 
	$cell=array(); //
	$arr=array(); //
	$pathinfo = pathinfo($inputFileName);
	$extensionType = NULL;
	
	$FileType = PHPExcel_IOFactory::identify($inputFileName);
	if($pathinfo['extension']=='csv')
	{
	  $FileType='CSV';	
	}
	$objReader = PHPExcel_IOFactory::createReader( $FileType);
	$objPHPExcel = $objReader->load($inputFileName);
	
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
    PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
		
	$worksheet = $objPHPExcel->getActiveSheet();
	$highestRow         = $worksheet->getHighestRow(); // e.g. 10
	$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
	$schoolids=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."'");
	$districtids=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."'");
	
	$officeno='';
	$faxno='';
	$mobileno='';
	$homeno='';
	$address1='';
	$state1='';
	$city1='';
	$zipcode1='';
	$parentid='';
	$grade='';
	
	
	$datarow=array();
	$successfullyinserteddatarows=array();
    if(fnEscapeCheck($sheetData[1]['A'])==fnEscapeCheck('First Name') and fnEscapeCheck($sheetData[1]['B'])==fnEscapeCheck('Last Name') and fnEscapeCheck($sheetData[1]['C'])==fnEscapeCheck('Username') and  fnEscapeCheck($sheetData[1]['D'])==fnEscapeCheck('Password'))/* and fnEscapeCheck($sheetData[1]['E'])==fnEscapeCheck('Grade') and fnEscapeCheck($sheetData[1]['F'])==fnEscapeCheck('Custodian First Name') and fnEscapeCheck($sheetData[1]['G'])==fnEscapeCheck('Custodian Last Name') and fnEscapeCheck($sheetData[1]['H'])==fnEscapeCheck('Custodian Email') and fnEscapeCheck($sheetData[1]['I'])==fnEscapeCheck('Phone Number'))*/
    {
	    for($i=2;$i<=sizeof($sheetData);$i++)
	    {
            $data=$sheetData[$i];
            $fname=$ObjDB->EscapeStrAll($data['A']);
            $lname=$ObjDB->EscapeStrAll($data['B']);
            $username=$ObjDB->EscapeStrAll($data['C']);
            $oringpassword=addslashes($data['D']);
            $password=fnEncrypt($oringpassword, $encryptkey);
            $grade=addslashes($data['E']);
            $Cusfname=addslashes($data['F']);
            $Cuslname=addslashes($data['G']);
            $cusemail=addslashes($data['H']);
            $phonenumber=addslashes($data['I']);

            /****** Auto generate username and password code created by chandru ********/
            if(($checboxuser == 'checked') and ($checboxpass == 'checked'))
            {
                if($username == '')
                {
                    $username1 = strtolower($fname[0])."".strtolower($lname);
                    $username2 = $ObjDB->SelectSingleValue("SELECT fld_username FROM itc_user_master where fld_username = '".$username1."'");
                    if($username2 != $username1)
                    {
                        $username = $username1;
                    }
                    else
                    {
                        $rename=mt_rand(1,100);
                        $username= $username1."".$rename;
                    }

                }
                if($oringpassword == '')
                {
                    $oringpassword = generatePassword();
                    $password=fnEncrypt($oringpassword, $encryptkey);

                }
            }else if(($checboxuser == 'checked') or ($checboxpass == 'checked'))
			{
                if($checboxuser == 'checked')
				{
					if($username == '')
					{
						$username1 = strtolower($fname[0])."".strtolower($lname);
						$username2 = $ObjDB->SelectSingleValue("SELECT fld_username FROM itc_user_master where fld_username = '".$username1."'");
						if($username2 != $username1)
						{
							$username = $username1;
						}
						else
						{
							$rename=mt_rand(1,100);
							$username= $username1."".$rename;
						}
					}
				}
				else
				{
					if($oringpassword == '')
					{
						$oringpassword = generatePassword();
						$password=fnEncrypt($oringpassword, $encryptkey);
					}
				}

			}
			/****** Auto generate username and password code end line created by chandru ********/

            if($fname != "" and $lname != "" and $username != "" and $oringpassword != "" )
            {
                $hash = PHPassLib\Hash\BCrypt::hash($oringpassword);

                $userdcnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_username) 
			   											FROM itc_user_master 
														WHERE MD5(LCASE(REPLACE(fld_username,' ','')))='".fnEscapeCheck($username)."' and  fld_delstatus='0' " );
                if($userdcnt == 0)
                {
				    $temp =1;
			   		$uguid = gen_uuid();

					$userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master(fld_uuid, fld_username, fld_hashed_password, fld_profile_id,fld_role_id, fld_fname, 
															fld_lname, fld_district_id, fld_school_id, fld_activestatus,fld_user_id, 
															fld_created_by, fld_created_date, fld_pass_updated)
														values('".$uguid."','".$username."','".$hash ."','10','5','".$fname."','".$lname."','".$districtids."',
															'".$schoolids."','1','".$indid."','".$uid."','".date("y-m-d H:i:s")."', now())");
					
					if($Cusfname !="" or $Cuslname !="" or $cusemail !="" ){
						
						$uguidp = gen_uuid();	
						$parentid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master (fld_uuid, fld_email, fld_fname, fld_lname, fld_profile_id, 
																	fld_role_id, fld_district_id, fld_school_id, fld_user_id, fld_created_by, fld_created_date) 
																VALUES ('".$uguidp."','".$cusemail."','".$Cusfname."','".$Cuslname."','11','5','".$districtids."',
																	'".$schoolids."','".$indid."','".$uid."','".$date."')");
					}
					
					$arr = array($officeno,$faxno,$mobileno,$homeno,$address1,$state1,$city1,$zipcode1,$parentid,$grade);
                    $x=3;
                    for($y=0;$y<sizeof($arr);$y++)
                    {
                        if($arr[$y]!='')
                        {
                            $ObjDB->NonQuery("INSERT INTO itc_user_add_info (fld_user_id,fld_field_id,fld_field_value) 
                                            VALUES ('".$userid."','".$x."','".$arr[$y]."')");
                        }
                        $x++;
                    }
                    // to add form itc_class_student mapping when select the class name
                	if($classid != '') {

                        $ObjDB->NonQuery("INSERT INTO itc_class_student_mapping(fld_class_id, fld_student_id, fld_flag,fld_createddate,fld_createdby) 
																VALUES ('".$classid."', '".$userid."', '1','".date('Y-m-d H:i:s')."','".$uid."')");

                    }

                    $datarow[] = array("rowno" => $i, "fname" => $fname, "lname" => $lname, "message" => "Successfully added", "username" => $username, "password" => $oringpassword);
                }
                else
                {
                    $datarow[]=array("rowno"=>$i,"fname"=>$fname,"lname"=>$lname,"username"=>$username,"message"=>"Username Already Exist","password"=>$oringpassword);
                }
            }
            else
            {
                if($fname !="" or $lname !="" or $username !=""  ){
			  		$datarow[]=array("rowno"=>$i,"fname"=>$fname,"lname"=>$lname,"username"=>$username,"message"=>"Required Field is empty","password"=>$oringpassword);
                }
            }
        }

        if(!empty($datarow))
		{ ?>
<script language="javascript" type="text/javascript">
$.getScript("users/individuals/users-individuals-importstudents.js");
	
</script>
	<table class='table table-hover table-striped table-bordered' id="mytable">
        <thead class='tableHeadText'>
            <tr>	
                <th class='centerText'>First Name</th>
                <th class='centerText'>Last Name</th>
                <th class='centerText'>User Name</th>
                <th class='centerText'>Password</th>
                <th class='centerText'>Messages</th>
                <th class='centerText'>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
			for($g=0;$g<sizeof($datarow);$g++)
			{
		?>
        	    	<tr class="" style="" id="deleterow<?php echo $g;?>">
						
				<td class="centerText">
					<span id="txctfname<?php echo $g; ?>"><?php echo $datarow[$g]['fname'];?></span>
					<input type="text" id="chkfname<?php echo $g; ?>" value="<?php echo$datarow[$g]['fname'];?>" style="display:none;width:150px;">
				</td>
				<td class="centerText">
					<span id="txctlname<?php echo $g; ?>"><?php echo $datarow[$g]['lname'];?></span>
					<input type="text" id="chklname<?php echo $g; ?>" value="<?php echo$datarow[$g]['lname'];?>" style="display:none;width:150px;">
				</td>
				<td class="centerText">
					<span id="txctuname<?php echo $g; ?>"><?php echo $datarow[$g]['username'];?></span>
					<input type="text" id="chkusername<?php echo $g; ?>" value="<?php echo$datarow[$g]['username'];?>" style="display:none;width:150px;">
				</td> 
				<td class="centerText">
					<span id="txctpwd<?php echo $g; ?>"><?php echo $datarow[$g]['password'];?></span>
					<input type="text" id="chkpwd<?php echo $g; ?>" value="<?php echo$datarow[$g]['password'];?>" style="display:none;width:150px;">

				</td> 
                        <td class="centerText"><?php echo $datarow[$g]['message'];?></td>
						<td class="centerText">
							<span onclick="fn_edituser(<?php echo $g.',\''. $datarow[$g]['fname'].'\''.',\''. $datarow[$g]['lname'].'\''.',\''. $datarow[$g]['username'].'\''.',\''. $datarow[$g]['password'].'\'';?>);$('#chkfname<?php echo $g; ?>,#chklname<?php echo $g; ?>,#chkusername<?php echo $g; ?>,#chkpwd<?php echo $g; ?>,#adduser<?php echo $g; ?>,#cancel<?php echo $g; ?>').show(); $('#txctfname<?php echo $g;?>,#txctlname<?php echo $g;?>,#txctuname<?php echo $g;?>,#txctpwd<?php echo $g;?>,#edituser<?php echo $g; ?>').hide();" stlye="cursor:pointer;font-size:20px;" class="icon-synergy-edit" id="edituser<?php echo $g; ?>"></span>
							<span onclick="fn_addusername(<?php echo $g;?>);" style="cursor:pointer; font-size:20px; display:none;" class="icon-synergy-create" id="adduser<?php echo $g; ?>"></span>
							<span onclick="$('#txctfname<?php echo $g;?>,#txctlname<?php echo $g;?>,#txctuname<?php echo $g;?>,#txctpwd<?php echo $g;?>,#edituser<?php echo $g;?>').show();$('#chkfname<?php echo $g; ?>,#chklname<?php echo $g; ?>,#chkusername<?php echo $g; ?>,#chkpwd<?php echo $g; ?>,#adduser<?php echo $g; ?>,#cancel<?php echo $g; ?>').hide();" style="cursor:pointer; font-size:20px; display:none;" class="icon-synergy-close" id="cancel<?php echo $g; ?>" ></span>
						</td>         
					</tr>
				
			<?php
			}

         ?>
        </tbody>
    </table>

    <?php
    }
    else {
        if($temp == 1) { ?>
            <div class="title-info" id="emptystudent" style="font-weight:bold"> All students are added successfully</div>
            <?php
        } else {?>
            <div class="title-info" style="font-weight:bold"> No Records</div>
        <?php
        }
    }?>
         
	<?php }else
	{?>
     <div class="title-info" style="font-weight:bold">File does not have a valid format</div>   
    
 <?php 	
 }
	 }

if($oper == "addusername" and $oper != '') 
{
   $fname = isset($method['fname']) ? $method['fname'] : '';
   $lname = isset($method['lname']) ? $method['lname'] : '';
   $uname = isset($method['uname']) ? $method['uname'] : '';
   $password = isset($method['password']) ? $method['password'] : '';
   $cntval = isset($method['cntval']) ? $method['cntval'] : '';
   $classid = isset($method['classid']) ? $method['classid'] : '';

    $fname = $ObjDB->EscapeStrAll($fname);
    $lname = $ObjDB->EscapeStrAll($lname);
    $uname = $ObjDB->EscapeStrAll($uname);

	$schoolids=$ObjDB->SelectSingleValueInt("SELECT fld_school_id FROM itc_user_master WHERE fld_id='".$uid."'");
	$districtids=$ObjDB->SelectSingleValueInt("SELECT fld_district_id FROM itc_user_master WHERE fld_id='".$uid."'");

	
   if($fname != "" and $lname != "" and $uname != "" and $password != "" )
   {
	$userdcnt=$ObjDB->SelectSingleValueInt("SELECT count(fld_username) FROM itc_user_master 
						WHERE MD5(LCASE(REPLACE(fld_username,' ','')))='".fnEscapeCheck($uname)."' and  fld_delstatus='0' " );
	 if($userdcnt == 0) {
		$uguid = gen_uuid();
 		$oringpassword=fnEncrypt($password, $encryptkey);

		$userid =$ObjDB->NonQueryWithMaxValue("INSERT INTO itc_user_master(fld_uuid, fld_username, fld_password, fld_profile_id,fld_role_id, fld_fname, 
							fld_lname, fld_district_id, fld_school_id, fld_activestatus,fld_user_id, 
							fld_created_by, fld_created_date)
							values('".$uguid."','".$uname."','".$oringpassword."','10','5','".$fname."','".$lname."','".$districtids."',
							'".$schoolids."','1','".$indid."','".$uid."','".date("y-m-d H:i:s")."')");
		// to add form itc_class_student mapping when select the class name
                	if($classid != '') {

				$ObjDB->NonQuery("INSERT INTO itc_class_student_mapping(fld_class_id, fld_student_id, fld_flag,fld_createddate,fld_createdby) 
																VALUES ('".$classid."', '".$userid."', '1','".date('Y-m-d H:i:s')."','".$uid."')");

			}


		echo "success"."~".$cntval; 

	 } else {
		echo "fail"."~"."Username Already Exist...";

	 }

    } else {
     echo "empty"."~"."Required Field is empty..";
    }


}
	@include("footer.php");     
