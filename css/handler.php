<?php
function userInfo($ua=null) {
		$ua = ($ua) ? strtolower($ua) : strtolower($_SERVER['HTTP_USER_AGENT']);

		$f = 'firefox';
		$w = 'webkit';
		$s = 'safari';
		$b = array();
		
		// browser
		if(!preg_match('/opera|webtv/i', $ua) && preg_match('/msie\s(\d)/', $ua, $array)) {
				$b["user_agent"] = 'ie' . $array[1];
		}	else if(strstr($ua, 'firefox/2')) {
				$b["user_agent"] = $f;		
		}	else if(strstr($ua, 'firefox/3.5')) {
				$b["user_agent"] = $f;
		}	else if(strstr($ua, 'firefox/3')) {
				$b["user_agent"] = $f;
		} else if(strstr($ua, 'gecko/')) {
				$b["user_agent"] = $f;
		} else if(preg_match('/opera(\s|\/)(\d+)/', $ua, $array)) {
				$b["user_agent"] = 'opera';
		} else if(strstr($ua, 'konqueror')) {
				$b["user_agent"] = 'konqueror';
		} else if(strstr($ua, 'chrome')) {
				$b["user_agent"] = 'chrome';
		} else if(strstr($ua, 'android')) {
				$b["user_agent"] = 'android';
		} else if(strstr($ua, 'blackberry')) {
				$b["user_agent"] = 'blackberry';		
		} else if(strstr($ua, 'applewebkit/')) {
				$b["user_agent"] = $s;
		} else if(strstr($ua, 'mozilla/')) {
				$b["user_agent"] = $f;
		}

		// platform				
		if(strstr($ua, 'j2me')) {
				$b["user_os"] = 'mobile';
		} else if(strstr($ua, 'iphone')) {
				$b["user_os"] = 'iphone';		
		} else if(strstr($ua, 'ipod')) {
				$b["user_os"] = 'ipod';		
		} else if(strstr($ua, 'mac')) {
				$b["user_os"] = 'mac';		
		} else if(strstr($ua, 'darwin')) {
				$b["user_os"] = 'mac';		
		} else if(strstr($ua, 'webtv')) {
				$b["user_os"] = 'webtv';		
		} else if(strstr($ua, 'win')) {
				$b["user_os"] = 'win';		
		} else if(strstr($ua, 'freebsd')) {
				$b["user_os"] = 'freebsd';		
		} else if(strstr($ua, 'x11') || strstr($ua, 'linux')) {
				$b["user_os"] = 'linux';		
		}
				
		return $b;		
}


?>