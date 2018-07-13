<?php
@include('nusoap.php');
/* -------
	Function to validate according to the parameters
	$length -  length of the characters
	$strength - strength of the password to include special symbol or not.
   -------	*/ 
function fn_validate($string,$filter,$options){
	
	if(filter_var($string, $filter, $options) !== FALSE) {
		return true;
	}	
	else {
		return false;
	}
}


function get_random_character_from_character_list($character_list){
    $character_list_length = strlen($character_list);
    $chosen_character = substr($character_list, rand(0, $character_list_length - 1), 1);
    return $chosen_character;
}

/* -------
	Function to generate random password
	$length -  length of the characters
	$strength - no longer used, but the parameter is retained for backwards compatibility with older code.
   -------	*/
//see config.php for password requirements
function generatePassword($length=8, $strength=0)
{
    $password = '';

    $allowed_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$&*=_+";

    //First, generate $length - 3 random digits.
    for ($i = 1; $i <= $length - 3; $i++){
        $password .= get_random_character_from_character_list($allowed_characters);
    }

    //Then, generate a random uppercase, lower case and numeral
    $uppercase_characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $random_uppercase_character = get_random_character_from_character_list($uppercase_characters);

    $lowerase_characters = "abcdefghijklmnopqrstuvwxyz";
    $random_lowercase_character = get_random_character_from_character_list($lowerase_characters);

    $numerals = "0123456789";
    $random_numeral = get_random_character_from_character_list($numerals);

    //Then, insert these 3 generated characters one by one into $password
    $insertion_position = rand(0, strlen($password));
    $password = substr_replace($password, $random_uppercase_character, $insertion_position, 0);

    $insertion_position = rand(0, strlen($password));
    $password = substr_replace($password, $random_lowercase_character, $insertion_position, 0);

    $insertion_position = rand(0, strlen($password));
    $password = substr_replace($password, $random_numeral, $insertion_position, 0);

    return $password;
}

/* -------
	Function to remove spaces and special characters
	$string -  string to escape
   -------	*/ 
function alphanumericAndSpace($string)
{	 
	$string=preg_replace('/[^a-zA-Z0-9\.\s]/','',$string);
	$string=str_replace(" ", "", $string);
	return $string;
}

function fnEscapeCheck($removestring) {
	return md5(preg_replace( '/\s+/', '', strtolower($removestring)));	
}


/* -------
	Function to encrypt & decrypt the password
	$sValue -  password string to encrypt
	$sSecretKey - secrect key used for encryption
   -------	*/ 
$encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
function fnEncrypt($sValue, $sSecretKey)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function fnDecrypt($sValue, $sSecretKey)
{
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

/* -------
	Function to call a page remotely 
	$url -  page to be called
	$paramstring - parameters to pass to that page
	$method - method to pass the parameters
	$timeout - waiting time for the function stay active
	$returnresponse - boolean value to say wherther the function needs to return data or not.
   -------	*/

function curl_post_async($url, $params)
{
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

function async_call($url, $paramstring, $method='GET', $timeout='30', $returnresponse=false) 
{
	
	$method = strtoupper($method);
	$urlParts=parse_url($url);      
	$fp = fsockopen($urlParts['host'],         
			isset($urlParts['port'])?$urlParts['port']:80,         
			$errno, $errstr, $timeout);
	
	//If method="GET", add querystring parameters
	if ($method == 'GET' and $paramstring != '')
		$urlParts['path'] .= '?'.$paramstring;
	
	$out = $method." ".$urlParts['path']." HTTP/1.1\r\n";     
	$out.= "Host: ".$urlParts['host']."\r\n";
	$out.= "Connection: Close\r\n";
	
	//If method="POST", add post parameters in http request body
	if ($method=='POST')
	{
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";     
		$out.= "Content-Length: ".strlen($paramstring)."\r\n\r\n";
		$out.= $paramstring;      
	}
	
	fwrite($fp, $out);     
	
	//Wait for response and return back response only if $returnresponse=true
	if ($returnresponse)
	{
		$return = stream_get_contents($fp);
	}
	
	fclose($fp); 
	
	return $return;
}

/* -------
	Function to calculate file size
	$file -  path of the file
	$type - unit of the file size. (KB,MB,GB)
   -------	*/

function formatbytes($file, $type)  
{  
    switch($type){  
        case "KB":  
            $filesize = filesize($file) * .0009765625; // bytes to KB  
        break;  
        case "MB":  
            $filesize = (filesize($file) * .0009765625) * .0009765625; // bytes to MB  
        break;  
        case "GB":  
            $filesize = ((filesize($file) * .0009765625) * .0009765625) * .0009765625; // bytes to GB  
        break;  
    }  
    if($filesize <= 0){  
        return $filesize = 'unknown file size';}  
    else{return round($filesize, 2).' '.$type;}  
}  


/*
 Function to generate GUID for each user in the system
*/
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

/*----***Below functions are used for php validation***------*/

/***

Function to validate datatype of the data
$value is data and $validatetype is for which type of validation needs

Validation types are float,int,email,url,ip

Example : how to call this function 


echo validate_datatype('45','int');

it will return only true or false if datatype is valid then this function will return true else return false

***/

function validate_datatype($value,$validatetype)
{
	  $options='';
	  switch($validatetype)
	  {
	    case 'float':
	    $type=FILTER_VALIDATE_FLOAT;
	    break;
	    
		case 'int':
	    $type=FILTER_VALIDATE_INT;
	    break;
		
		case 'email':
	    $type=FILTER_VALIDATE_EMAIL;
	    break;
		
		case 'url':
		$type=FILTER_VALIDATE_URL;
		break;
		case 'ip':
		$type=FILTER_VALIDATE_IP;
		break;
	  
	  }
	  
       return filter_var($value, $type,$options) ? true : false; 
}



/***

Function to validate data which need to satisfy the conditions

$value is data and $validatetype is for which type of validation needs

Validation types are lettersonly,alphanumeric,letterswithbasicpunc,phone_number,chkusername,datetimeformat,timeformat,zipcode

Example : how to call this function 


echo validate_datas('itcv','lettersonly');

it will return only true or false if data is valid then this function will return true else return  false

***/

function validate_datas($string,$validatetype)
{
    switch($validatetype)
	  {
	    case 'lettersonly':
	    $type='/^[a-zA-Z0-9-_.,()&\'\"\s]+$/';  //for letters only 
		//$type='/^[a-zA-Z0-9\u00c0-\u01ffa-_.,()&\'\"\s]+$/';  //for letters only 
		//$type="/^[\s\x{00C0}-\x{01FF}a-z'-0-9_.,()&\'\"]+$/iu";  //for letters only 
		
	    
		break;
	    
		case 'alphanumeric':
	    $type='/^\w+$/i'; // for alpha numberic
	    break;
		
		case 'letterswithbasicpunc':
	    $type='/^[a-zA-Z0-9-_.,#()&\'\"\s]+$/'; //letterswithbasicpunc 
	    break;
		
		case 'phone_number':
		$type='/^[0-9]+$/'; //phonenumber
		break;
		case 'chkusername':
		$type='/^[A-Za-z0-9_]{3,100}$/'; //chkusername
		break;
		
			// validate field contains YYYY-MM-DD HH:MM:SS
		
		case 'datetimeformat':
			$pattern = '/\\A(?:^((\\d{2}(([02468][048])|([13579][26]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])))))|(\\d{2}(([02468][1235679])|([13579][01345789]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\\s(((0?[0-9])|(1[0-9])|(2[0-3]))\\:([0-5][0-9])((\\s)|(\\:([0-5][0-9])))?))?$)\\z/';
		break;

        // validate field contains HH:MM
		case 'timeformat':
			$pattern = '/^(2[0-3]|[01]?[1-9]):([0-5]?[0-9])$/';
		break;

		// validate field contains MM/DD/YYYY
		/*
		some example formats for date format:
		
		date format expressions
		YYYY-MM-DD /^(\d{4})-(\d{2})-(\d{2})$/
		DD/MM/YYYY /^(\d{2})-(\d{2})-(\d{4})$/
		MM-DD-YYYY /^(\d{2})-(\d{2})-(\d{4})$/
		DD-MMM-YYYY /^(\d{2})-(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-(\d{4})$/
		
		*/

		case 'dateformat':
			$type = '/^(\d{2})\/(\d{2})\/(\d{4})$/';
		break;
		
		 // validate zip code field for US and canada
		case 'zipcode':
			$pattern = '^\d{5}(-\d{4})?$)|(^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$)';
		break;
		
		default:
			return false;
		break;
	  
	  }
	  return preg_match($type, $string) ? true : false;
}

/**Function to check the image is valid or not it will return where true or false**/
function isImage($img){ 
    if(!getimagesize($img)){ 
        unlink($img); //if invalid image it will delete from the server
		return FALSE; 
		
    }else{ 
        return TRUE; 
    } 
} 

/**Function to check the file is valid or not it will return where true or false **/

function checkFileIsExist($filename)
{
	return file_exists($filename) ? true : false;
}

function sortArrayByArray($ipl,$sessipl) {
	$ordered = array();
	for($asd=0;$asd<sizeof($sessipl);$asd++)
	{
		
		if(isset($ipl[$asd]) and in_array($ipl[$asd],$sessipl)) {
			
			$ordered[] = $ipl[$asd];
		}
	}
	return array_values(array_unique(array_merge($ordered,$sessipl)));
}

/* Tag insert and update */
function fn_taginsert($tags,$tagtype,$itemid,$uid)
{
	global $ObjDB;

        $sessmasterprfid=$ObjDB->SelectSingleValueInt("select fld_profile_id from itc_user_master where fld_id='".$uid."' and fld_delstatus='0'");

	$ObjDB->NonQuery("update itc_main_tag_mapping set fld_access='0' where fld_item_id='".$itemid."'");
	if($tags!=''){
		$tags = $ObjDB->EscapeStrAll($tags);
		$tags = explode(',',$tags);
		for($i=0;$i<sizeof($tags);$i++){
			$tagid = explode('~',$tags[$i]);
			if(isset($tagid[1])==''){
				
                            if($sessmasterprfid==2)
                            {
                                $ObjDB->NonQuery("CALL sp_new_tag_insertadmin('".$tags[$i]."','".$tagid[0]."','".$itemid."','".$tagtype."','".date("Y-m-d H:i:s")."','".$uid."','".$sessmasterprfid."');");
			}
                            else
                            {
				$ObjDB->NonQuery("CALL sp_new_tag_insert('".$tags[$i]."','".$tagid[0]."','".$itemid."','".$tagtype."','".date("Y-m-d H:i:s")."','".$uid."','".$sessmasterprfid."');");
                            }
			}
			else{
				if($tagid[1]=='lesson')
					$ttagtype=1;
				else if($tagid[1]=='unit')
					$ttagtype=4;
				else
					$ttagtype = $tagtype;
				$tagid = $tagid[0];
				$lessonflag=1;
				
				$chktag = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_main_tag_mapping where fld_tag_id='".$tagid."' and fld_tag_type='".$ttagtype."' and fld_item_id='".$itemid."' and fld_lesson_flag='1'");
				if($chktag>0){
					$ObjDB->NonQuery("update itc_main_tag_mapping set fld_access='1' where fld_tag_id='".$tagid."' and fld_tag_type='".$ttagtype."' and fld_item_id='".$itemid."' and fld_lesson_flag='1'");
				}
				else{
					$ObjDB->NonQuery("insert into itc_main_tag_mapping(fld_tag_id,fld_tag_type,fld_item_id,fld_access,fld_lesson_flag)values('".$tagid."','".$ttagtype."','".$itemid."','1','".$lessonflag."')");
				}
			}
		}
	}
}
	
	
function fn_tagupdate($tags,$tagtype,$itemid,$uid)
{
	global $ObjDB;
	$sessmasterprfid=$ObjDB->SelectSingleValueInt("select fld_profile_id from itc_user_master where fld_id='".$uid."' and fld_delstatus='0'");
	$ObjDB->NonQuery("update itc_main_tag_mapping set fld_access='0' where fld_item_id='".$itemid."'");
	if($tags!=''){
		$tags=$ObjDB->EscapeStrAll($tags);
		$tags = explode(',',$tags);
		for($i=0;$i<sizeof($tags);$i++){
			$tagid = explode('~',$tags[$i]);
			if($tagid[1]==''){
                            
                            if($sessmasterprfid==2)
                            {
                                $ObjDB->NonQuery("CALL sp_new_tag_insertadmin('".$tags[$i]."','".$tagid[0]."','".$itemid."','".$tagtype."','".date("Y-m-d H:i:s")."','".$uid."','".$sessmasterprfid."');");
			}
                            else
                            {
				$ObjDB->NonQuery("CALL sp_new_tag_insert('".$tags[$i]."','".$tagid[0]."','".$itemid."','".$tagtype."','".date("Y-m-d H:i:s")."','".$uid."','".$sessmasterprfid."');");
                            }
			}
			else{
				if($tagid[1]=='lesson')
					$ttagtype=1;
				else if($tagid[1]=='unit')
					$ttagtype=4;
				else
					$ttagtype = $tagtype;
				$tagid = $tagid[0];
				$lessonflag=1;
				
				$chktag = $ObjDB->SelectSingleValueInt("select count(fld_id) from itc_main_tag_mapping where fld_tag_id='".$tagid."' and fld_tag_type='".$ttagtype."' and fld_item_id='".$itemid."' and fld_lesson_flag='1'");
				if($chktag>0){
					$ObjDB->NonQuery("update itc_main_tag_mapping set fld_access='1' where fld_tag_id='".$tagid."' and fld_tag_type='".$ttagtype."' and fld_item_id='".$itemid."' and fld_lesson_flag='1'");
				}
				else{
					$ObjDB->NonQuery("insert into itc_main_tag_mapping(fld_tag_id,fld_tag_type,fld_item_id,fld_access,fld_lesson_flag)values('".$tagid."','".$ttagtype."','".$itemid."','1','".$lessonflag."')");
				}
			}
		}
	}
}
/*
 Function to get all the content in particular license
*/
function fn_getcontent($licenseid){
	global $ObjDB;
	
	$unitnames = '';
	$iplnames = '';
	$modnames = '';
	$mathnames = '';
	$assnames = '';
        
        //pd
        $coursenames = '';
        $pdnames = '';
        //pd
        
	$qry_unit = $ObjDB->QueryObject("SELECT b.fld_unit_name as unitname 
											FROM itc_license_cul_mapping AS a LEFT JOIN itc_unit_master AS b ON a.fld_unit_id=b.fld_id 
											WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' GROUP BY a.fld_unit_id");	
	if($qry_unit->num_rows > 0) {							
		while($rowunit = $qry_unit->fetch_assoc()){
			extract($rowunit);
			if($unitnames=='')
				$unitnames = $unitname;
			else
				$unitnames = $unitnames.',   '.$unitname;
		}
	}
	
	$qry_lesson = $ObjDB->QueryObject("SELECT CONCAT(b.fld_ipl_name,' ',c.fld_version) AS lessonname 
										FROM itc_license_cul_mapping AS a LEFT JOIN itc_ipl_master AS b ON a.fld_lesson_id=b.fld_id
										LEFT JOIN itc_ipl_version_track AS c ON c.fld_ipl_id=b.fld_id
										WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND c.fld_zip_type='1' AND c.fld_delstatus='0'
										GROUP BY a.fld_lesson_id");				
	if($qry_lesson->num_rows > 0) {							
		while($rowlesson = $qry_lesson->fetch_assoc()){
			extract($rowlesson);	
			if($iplnames=='')
				$iplnames = $lessonname;
			else
				$iplnames = $iplnames.',   '.$lessonname;
		}
	}
	
	$qry_module = $ObjDB->QueryObject("SELECT CONCAT(b.fld_module_name,' ',c.fld_version) AS modulename 
										FROM itc_license_mod_mapping AS a LEFT JOIN itc_module_master AS b ON a.fld_module_id=b.fld_id 
											LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=b.fld_id
										WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND a.fld_type='1' AND c.fld_delstatus='0'
										GROUP BY a.fld_module_id");	
	if($qry_module->num_rows > 0) {							
		while($rowmodule = $qry_module->fetch_assoc()){
			extract($rowmodule);
			if($modnames=='')
				$modnames = $modulename;
			else
				$modnames = $modnames.',   '.$modulename;
		}
	}
	
	$qry_mathmodule = $ObjDB->QueryObject("SELECT CONCAT(b.fld_mathmodule_name,' ',c.fld_version) AS mathmodulename 
											FROM itc_license_mod_mapping AS a LEFT JOIN itc_mathmodule_master AS b ON a.fld_module_id=b.fld_id 
												LEFT JOIN itc_module_version_track AS c ON c.fld_mod_id=b.fld_module_id
											WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' AND a.fld_type='2' AND c.fld_delstatus='0'
											GROUP BY a.fld_module_id");	
	if($qry_mathmodule->num_rows > 0) {							
		while($rowmathmodule = $qry_mathmodule->fetch_assoc()){
			extract($rowmathmodule);
			if($mathnames=='')
				$mathnames = $mathmodulename;
			else
				$mathnames = $mathnames.',   '.$mathmodulename;
		}
	}
	
	$qry_assess = $ObjDB->QueryObject("SELECT b.fld_test_name AS assessmentname 
										FROM itc_license_assessment_mapping AS a, itc_test_master AS b 
										WHERE a.fld_assessment_id=b.fld_id AND a.fld_license_id='".$licenseid."' AND a.fld_access='1' 
										GROUP BY a.fld_assessment_id");				
	if($qry_assess->num_rows > 0) {							
		while($rowassess = $qry_assess->fetch_assoc()){
			extract($rowassess);	
			if($assnames=='')
				$assnames = $assessmentname;
			else
				$assnames = $assnames.',   '.$assessmentname;
		}
	}
        
        /* PD */
        $qry_pdcourse = $ObjDB->QueryObject("SELECT b.fld_course_name AS coursename FROM itc_license_pd_mapping AS a 
											LEFT JOIN itc_course_master AS b ON a.fld_course_id=b.fld_id 
											WHERE a.fld_license_id='".$licenseid."' AND a.fld_active='1' 
											AND b.fld_delstatus='0' GROUP BY a.fld_course_id");	
	if($qry_pdcourse->num_rows > 0) {							
		while($rowpdcourse = $qry_pdcourse->fetch_assoc()){
			extract($rowpdcourse);
			if($coursenames=='')
				$coursenames = $coursename;
			else
				$coursenames = $coursenames.',   '.$coursename;
		}
	}
        
        $qry_pdlesson = $ObjDB->QueryObject("SELECT CONCAT(b.fld_pd_name,' ',c.fld_version ) AS pdname 
											FROM itc_license_pd_mapping AS a 
											LEFT JOIN itc_pd_master AS b ON a.fld_pd_id=b.fld_id
											LEFT JOIN itc_pd_version_track AS c ON c.fld_pd_id=b.fld_id
											WHERE a.fld_license_id='".$licenseid."' AND a.fld_flag='1' 
											AND a.fld_active='1' AND b.fld_delstatus='0' AND c.fld_delstatus='0' AND c.fld_zip_type='1'
											GROUP BY a.fld_pd_id");				
	if($qry_pdlesson->num_rows > 0) {							
		while($rowpdlesson = $qry_pdlesson->fetch_assoc()){
			extract($rowpdlesson);	
			if($pdnames=='')
				$pdnames = $pdname;
			else
				$pdnames = $pdnames.',   '.$pdname;
		}
	}
       /* PD */ 
        
        
	$display = '';
	if($unitnames!='')
		$display = '<tr><td><strong>Unit Names: </strong></td></tr><tr><td>'.$unitnames.'</td></tr>'; 
		
	if($iplnames!='')
		$display = $display.'<tr><td><strong>IPL Names: </strong></td></tr><tr><td>'.$iplnames.'</td></tr>'; 
	
	if($modnames!='')
		$display = $display.'<tr><td><strong>Module Names: </strong></td></tr><tr><td>'.$modnames.'</td></tr>'; 
		

	if($mathnames!='')
		$display = $display.'<tr><td><strong>Math Module Names: </strong></td></tr><tr><td>'.$mathnames.'</td></tr>'; 
		
	if($assnames!='')
		$display = $display.'<tr><td><strong>Assessment Names: </strong></td></tr><tr><td>'.$assnames.'</td></tr>'; 
       /* PD */ 
        if($coursenames!='')
		$display = $display.'<tr><td><strong>PD Course Names: </strong></td></tr><tr><td>'.$coursenames.'</td></tr>'; 
	
	if($pdnames!='')
		$display = $display.'<tr><td><strong>PDLesson Names: </strong></td></tr><tr><td>'.$pdnames.'</td></tr>'; 
        /* PD */ 
	
	return $display;
				
}


function send_notification($licenseid,$schoolid,$indid){
	global $ObjDB;
	
	//start notification
	$qry_noti = $ObjDB->QueryObject("SELECT fld_id, fld_district_id, fld_school_id, fld_user_id, fld_no_of_users, fld_remain_users, fld_start_date, fld_end_date 
									FROM itc_license_track 
									WHERE ROUND(fld_no_of_users*0.9)<=(fld_no_of_users-fld_remain_users) AND fld_school_id='".$schoolid."' AND fld_user_id='".$indid."' 
										AND fld_license_id='".$licenseid."' AND fld_delstatus='0' AND '".date("Y-m-d")."' BETWEEN fld_start_date AND fld_end_date 
										AND fld_notification='0'");
	if($qry_noti->num_rows>0)
	{
		while($row = $qry_noti->fetch_assoc())
		{
			extract($row);
			$profile='';
			if(($fld_school_id=='0' && $fld_user_id=='0') || ($fld_school_id!='0' && $fld_district_id!='0'))
				$profile = 6;	
			else if($fld_user_id=='0' && $fld_district_id=='0')
				$profile = 7;
			else if($fld_district_id=='0' && $fld_school_id=='0')
				$profile = 5;	
			if($profile!=''){	
				$adminqry = $ObjDB->QueryObject("SELECT a.fld_fname AS afname, a.fld_lname AS alname, a.fld_email AS aemail, b.fld_field_value AS aphone 
												FROM itc_user_master AS a LEFT JOIN itc_user_add_info AS b ON b.fld_field_id=3 
												WHERE a.fld_district_id='".$fld_district_id."' AND a.fld_school_id='".$fld_school_id."' AND a.fld_user_id='".$fld_user_id."'  
													AND a.fld_profile_id='".$profile."' AND a.fld_delstatus='0' AND b.fld_delstatus='0' 
												ORDER BY a.fld_id LIMIT 0,1");
				if($adminqry->num_rows>0){
					extract($adminqry->fetch_assoc());
				}
			}
			$licensename = $ObjDB->SelectSingleValue("SELECT fld_license_name 
													 FROM itc_license_master 
													 WHERE fld_id='".$licenseid."' AND fld_delstatus='0'");
			if(($fld_school_id=='0' && $fld_user_id=='0') || ($fld_school_id!='0' && $fld_district_id!='0')){			
				$orname = $ObjDB->SelectSingleValue("SELECT fld_district_name FROM itc_district_master WHERE fld_id='".$fld_district_id."'")."(District purchase)";
			}
			else if($fld_user_id=='0' && $fld_district_id=='0'){			
				$orname = $ObjDB->SelectSingleValue("SELECT fld_school_name FROM itc_school_master WHERE fld_id='".$fld_school_id."'")."(School purchase)";
			}
			else if($fld_district_id=='0' && $fld_school_id=='0'){			
				$orname = $ObjDB->SelectSingleValue("SELECT CONCAT(fld_fname,' ',fld_lname) AS iname FROM itc_user_master WHERE fld_id='".$fld_user_id."'")."(Home purchase)";
			}	
			
			$qry = $ObjDB->QueryObject("SELECT fld_fname, fld_lname, fld_email, fld_username, fld_profile_id 
										FROM itc_user_master 
										WHERE fld_district_id='".$fld_district_id."' AND fld_school_id='".$fld_school_id."' AND fld_user_id='".$fld_user_id."' 
										AND fld_profile_id<>10 AND fld_delstatus='0'");
				
			if($qry->num_rows>0)
			{
				while($rowqry = $qry->fetch_assoc())
				{
					extract($rowqry);
					
					if($fld_email!='')
					{
						$html_txt = '';
						$headers = '';
						//$mailtitle = $fld_username;
						
						$subj = $licensename." - Running out of available students seats";
						$random_hash = md5(date('r', time())); 
										
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 
						//$headers .= "Content-type:multipart/mixed;" . "\r\n";    
						$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	   
						
						if($fld_profile_id==6){
							$content = '<tr><td valign="top" align="left">This is to inform you that you are getting close to running out of available students seats. Please contact our sales support staff at 800-828-5787 to add additional seats.</td></tr>';
						}
						else if($fld_profile_id==7 && $fld_district_id=='0'){
							$content = '<tr><td valign="top" align="left">This is to inform you that you are getting close to running out of available students seats. Please contact our sales support staff at 800-828-5787 to add additional seats.</td></tr>';
						}
						else if( $fld_profile_id==5){
							$content = '<tr><td valign="top" align="left">This is to inform you that you are getting close to running out of available students seats. Please contact our sales support staff at 800-828-5787 to add additional seats.</td></tr>';						
						}
						else{
						$content = '<tr><td valign="top" align="left">This is to inform you that you are getting close to running out of available students seats. Please contact your administrator to add additional seats.</td></tr>';
						$admindetails= 'Administrator: '.$afname.' '.$alname.'<br />Phone: '.$aphone.'<br />Email: '.$aemail.'';							
						}
						$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
						<tr><td valign="top" align="left">Lease: '.$licensename.'<br />					
						Students seats assigned: '.$fld_no_of_users.'<br />
	
						Students seats available: '.$fld_remain_users.'<br />
						Lease Period: '.date("m/d/Y",strtotime($fld_start_date)).'-'.date("m/d/Y",strtotime($fld_end_date)).'<br />
						</td></tr></table>';						
						//echo $html_txt;	
						$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $fld_email,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
						$client->call('SendJangoMailTransactional', $param, '', '', false, true);						
					}
				}
			}
			//for pitsco admin
			$html_txt = '';
			$headers = '';		
			$subj = $licensename."-".$orname." - Running out of available students seats";
			$random_hash = md5(date('r', time())); 
							
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
			$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	  	
			$content = '<tr><td valign="top" align="left">This is to inform you that '.$orname.' is getting close to running out of available students seats</td></tr>';
			$html_txt = '<table width="98%" cellpadding="10" cellspacing="0">'.$content.'
			<tr><td valign="top" align="left">Lease: '.$licensename.'<br />					
			Students seats assigned: '.$fld_no_of_users.'<br />
			Students seats available: '.$fld_remain_users.'<br />
			Lease Period: '.date("m/d/Y",strtotime($fld_start_date)).'-'.date("m/d/Y",strtotime($fld_end_date)).'<br />
			</td></tr></table>';
			//echo $html_txt.'~'.$subj;
			$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => 'systems_support@pitsco.com','subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
			$client->call('SendJangoMailTransactional', $param, '', '', false, true);			
			if($fld_district_id!=0 && $aemail!=''){
				//for district admin
				$html_txt = '';
				$headers = '';		
				$subj = $licensename." - Running out of available students seats";
				$random_hash = md5(date('r', time())); 
								
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=utf-8" . "\r\n"; 		
				$headers .= "From: Synergy ITC <do_not_reply@pitsco.com>" . "\r\n";	  	
				$content = '<tr><td valign="top" align="left">This is to inform you that you are getting close to running out of available students seats. Please contact our sales support staff at 800-828-5787 to add additional seats.</td></tr>';
				$html_txt = '<table width="98%" cellpadding="10" cellspacing="0"><tr><td valign="top" align="left"><br />Hi '.$fld_fname.', <br /></td></tr>'.$content.'
						<tr><td valign="top" align="left">Lease: '.$licensename.'<br />					
						Students seats assigned: '.$fld_no_of_users.'<br />
	
						Students seats available: '.$fld_remain_users.'<br />
						Lease Period: '.date("m/d/Y",strtotime($fld_start_date)).'-'.date("m/d/Y",strtotime($fld_end_date)).'<br />
						</td></tr></table>';
				//echo $html_txt.'~'.$subj;
				$param = array('SiteID' => '30','fromAddress' => 'do_not_reply@pitsco.com','fromName' => 'Synergy ITC','toAddress' => $aemail,'subject' => $subj, 'plainTex' => '','html' => $html_txt,'options' => '','groupID' => '805014','log' => 'True');
				$client->call('SendJangoMailTransactional', $param, '', '', false, true);				
			}
			$ObjDB->NonQuery("update itc_license_track set fld_notification='1' where fld_id='".$fld_id."'");	
		}
	}
	//end notification
}
/*Function to use get the arry values for additional information for users */
function getarrayvalues($arrfieldid,$arrcombine)
{
       $newarray=array();
       for($i=1;$i<=12;$i++)
       {        if(in_array($i,$arrfieldid))
               {
                       $newarray[$i]=$arrcombine[$i];
               }
               else
               {
                       $newarray[$i]='';
               }
               
        }
               return $newarray;         
}
