<?php
/**
 * Created by PhpStorm.
 * User: Raymond
 * Date: 2017-05-04
 * Time: 12:15 AM
 */

$midnight_of_coming_day = strtotime("tomorrow 00:00:00");
$cookie_saved_correctly = setcookie('suppress_licence_expiry', "true this is a test", $midnight_of_coming_day, "/");

echo $cookie_saved_correctly;