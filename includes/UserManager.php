<?php

/**
 * Created by PhpStorm.
 * User: barney
 * Date: 2017-02-16
 * Time: 3:48 PM
 */

require_once 'config.php';
require_once 'dbHelper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';


class UserManager
{
    public $id;
    private $encryptkey = 'ef800b4cf626d5c14a0c65ce2d90c15c';
    public $helper;
    public $username;
    public $name;
    public $user_profile;
    public $role_id;

    public $itcteacher;
    public $sosteacher;

    public $schoolid;
    public $indid;
    public $distid;

    public $sessionid;

    public $prf_main_id;
    public $prf_name;
    public $email;
    private $loggedin = false;
    public $userrow = array();


    public function __construct($userrow) {
        session_start();
        $this->helper = new dbHelper();
        $this->userrow = $userrow;
        $this->checkActive();

        $this->sessionid = $this->generatePassword();
        $this->id = (int)$userrow["fld_id"];
        $this->username = $userrow["fld_username"];
        $this->email = $userrow["fld_email"];
        $this->user_profile = $userrow["fld_profile_id"];
        $this->name = $userrow["fld_fname"].' '.$userrow["fld_lname"];

        $this->itcteacher = $userrow["fld_itcteacher"];
        $this->sosteacher = $userrow["fld_sosteacher"];

        $this->schoolid = $userrow["fld_school_id"];
        $this->indid = $userrow["fld_user_id"];
        $this->distid = $userrow["fld_district_id"];

        $this->profileInfo();
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->loggedin = false;
        setcookie('username', '', time()-3600);
        header('Location: index.php');
        exit();
    }

    public static function db_fetch_user($username){
        $helper = new dbHelper();
        $rows = $helper->select("itc_user_master", array("fld_username" => $username, "fld_delstatus" => 0));
        if($rows["status"] == "warning"){
            echo "Username '$username' does not exist. <br> Please check the spelling and try again.";
            exit();
        }else{
            $userrow = $rows["data"][0];
            return $userrow;
        }
    }
    public static function db_fetch_userid($userid){
        $helper = new dbHelper();
        $rows = $helper->select("itc_user_master", array("fld_id" => $userid, "fld_delstatus" => 0));
        if($rows["status"] == "warning"){
            echo "User does not exist. Please try again.";
            exit();
        }else{
            $userrow = $rows["data"][0];
            return $userrow;
        }
    }

    private function verify_password_old($login_password){
        $password_from_db = $this->decrypt_old_pass($this->userrow["fld_password"]);
        if($password_from_db == $login_password){
            return $this->set_password($login_password);
        }else{
            return "Invalid Password, Please try again.";
        }
    }

    public function verify_password($login_password){
        if($this->userrow["fld_hashed_password"] != ""){
            if (PHPassLib\Hash\BCrypt::verify($login_password, $this->userrow["fld_hashed_password"])) {
                return "success";
            }else{
                return "Invalid Password. Please try again.";
            }
        }else{
            return $this->verify_password_old($login_password);
        }
    }

    public function set_password($login_password){
        $hash = PHPassLib\Hash\BCrypt::hash($login_password, array ('rounds' => 8));
        $today = date("Y-m-d H:i:s");
        $this->helper->update("itc_user_master", array("fld_hashed_password" => $hash, "fld_updated_date" => $today), array("fld_id" => $this->id), array());
        //echo "<h4><b>$hash</b></h4>";
        return "success";
    }

    private function isBlocked()
    {
        if($this->userrow["fld_block_status"] == 1){
            echo "This account has been blocked. Please contact PITSCO Support for more details.";
            exit();
        }else{
            return false;
        }
    }

    private function isDeleted()
    {
        if($this->userrow["fld_delstatus"] == 1){
            echo "This account has been deleted. Please contact PITSCO Support for more details.";
            exit();
        }else{
            return false;
        }
    }

    private function isActive()
    {
        if($this->userrow["fld_activestatus"] == 0){
            echo "This account is no longer active. Please contact PITSCO Support for more details.";
            exit();
        }else{
            return false;
        }
    }

    public function isTrial()
    {
        $rows = $this->helper->select("itc_trial_users", array("fld_user_id" => $this->id));
        if(count($rows["data"][0]) > 1){
            return true;
        }else{
            return false;
        }
    }

    private function checkActive(){
        if(!$this->isBlocked()){
            return true;
        }else{
            return false;
        }
    }

    private function decrypt_old_pass($old_password){
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->encryptkey, base64_decode($old_password), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

    private function profileInfo(){
        $rows = $this->helper->select("itc_profile_master", array("fld_id" => $this->user_profile));
        if($rows["status"] == "warning"){
            echo "User not found. Please try again.";
            exit();
        }else{
            $profilerow = $rows["data"][0];
            $this->prf_name = $profilerow["fld_profile_name"];
            $this->prf_main_id = $profilerow["fld_prf_main_id"];
        }
    }

    public static function generatePassword(){
        $length=9;
        $strength=0;
        $vowels = 'aeuyAEUY';
        $consonants = 'bdghjmnpqrstvzBDGHJLMNPQRSTVWXZ23456789';
        if ($strength > 0) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength <= 2) {
            $vowels .= "AEUY";
        }
        if ($strength <= 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    public function check_forget(){
        if((int)$this->prf_main_id == 10){
            echo "Student accounts are not allowed to request a password reset e-mail. Please contact a teacher or instructor for assistance in resetting your password.";
            exit();
        }
        elseif(strlen($this->email) < 8 || $this->email == "support@pitsco.com" || $this->email == "systems_support@pitsco.com" || $this->email == "info@pitsco.com"){
            echo "We could not find an e-mail address associated with this account. Please contact PITSCO Customer Support at 800-774-4552 for assistance in resetting your password.";
            exit();
        }
        else{
            return true;
        }
    }

    public static function check_password_reset($userid, $token){
        $helper = new dbHelper();
        $rows = $helper->select("itc_user_master", array("fld_id" => $userid, "fld_actkey" => $token));
        if($rows["status"] == "warning"){
            return false;
        }else{
            return true;
        }
    }

    public function update_password($password){
        $hash = PHPassLib\Hash\BCrypt::hash($password, array ('rounds' => 8));
//        $hash = PHPassLib\Hash\BCrypt::hash($password);
        $today = date("Y-m-d H:i:s");
        $this->helper->update("itc_user_master", array("fld_hashed_password" => $hash, "fld_updated_date" => $today, "fld_actkey" => null), array("fld_id" => $this->id), array());
//        echo "<h4><b>$hash</b></h4>";
        return "success";
    }

    public function update_account_settings($username, $password, $email){
//            $config = PHPassLib\Hash\BCrypt::genConfig(["rounds"=>10]);
//            $hash = PHPassLib\Hash\BCrypt::hash($password, $config);
        $hash = PHPassLib\Hash\BCrypt::hash($password, array ('rounds' => 8));
        $today = date("Y-m-d H:i:s");
        $this->helper->update("itc_user_master", array("fld_username" => $username, "fld_email" => $email, "fld_hashed_password" => $hash, "fld_updated_date" => $today, "fld_actkey" => null, "fld_activestatus" => 1), array("fld_id" => $this->id), array());
        //echo "<h4><b>$hash</b></h4>";
        return "success";
    }

    public function update_account_info($password, $email){
        global $password_regex;
        global $password_fails_specification_error_message;
        if (preg_match($password_regex, $password)) {
//            $config = PHPassLib\Hash\BCrypt::genConfig(["rounds"=>10]);
//            $hash = PHPassLib\Hash\BCrypt::hash($password, $config);
            $hash = PHPassLib\Hash\BCrypt::hash($password, array ('rounds' => 8));
            $today = date("Y-m-d H:i:s");
            $this->helper->update("itc_user_master", array("fld_email" => $email, "fld_hashed_password" => $hash, "fld_updated_date" => $today, "fld_pass_updated" => $today), array("fld_id" => $this->id), array());
            //echo "<h4><b>$hash</b></h4>";
            return "success";
        }
        else{
            return $password_fails_specification_error_message;
        }
    }
}
