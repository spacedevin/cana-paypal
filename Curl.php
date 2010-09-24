<?php
/**
 * 
 * Baic Curl library
 *
 * Devin Smith           2006.03.05            www.devin-smith.com
 *
 */

class Caffeine_Curl {
	function request($url,$data = null,$method = 'post') {

		$ch = curl_init();    

		
		$datapost = '';
		
		if ($method == 'post') {
			if (is_array($data)) {
				foreach ($data as $key => $item) {
					if ($datapost)
						$datapost .= '&';
					$datapost .= $key.'='.@urlencode($item);
				}
			}
			curl_setopt($ch, CURLOPT_URL,$url);  
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		} else {
			if (is_array($data)) {
				foreach ($data as $key => $item) {
					$datapost .= ($datapost ? '&' : '?').$key.'='.@urlencode($item);
				}
			}
			curl_setopt($ch, CURLOPT_URL,$url.$datapost);  
			curl_setopt($ch, CURLOPT_GET, true); 
		}

		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime;
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$output = curl_exec ($ch);
		$err = curl_error($ch);
		curl_close ($ch);

		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$this->exectime = number_format($endtime - $starttime,0,'.',',');

		return $output;
	}
}
