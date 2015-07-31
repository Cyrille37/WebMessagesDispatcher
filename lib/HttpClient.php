<?php

class HttpClient {

	const HTTP_USERAGENT = 'SARB v0.1';
	const HTTP_CONNECTTIMEOUT = 5;
	const HTTP_TIMEOUT = 5;
	const HTTP_SSL_VERIFYPEER = false;
	const HTTP_FOLLOWLOCATION = false;
	const HTTP_PROXY = null;
	const HTTP_ENCODING = 'UTF-8';

	public function request($url, $method='GET', Array $headers = array(), Array $data = array()) {

		// CURL defaults to setting this to Expect: 100-Continue
		// which Twitter rejects !
		$headers ['Expect'] = '';

		$httpheaders = array ();
		foreach ( $headers as $k => $v ) {
			$httpheaders [] = trim ( $k . ': ' . $v );
		}
		
		$c = curl_init ();
		curl_setopt_array ( $c, array (
				CURLOPT_USERAGENT => self::HTTP_USERAGENT,
				CURLOPT_CONNECTTIMEOUT => self::HTTP_CONNECTTIMEOUT,
				CURLOPT_TIMEOUT => self::HTTP_TIMEOUT,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_SSL_VERIFYPEER => self::HTTP_SSL_VERIFYPEER,
				CURLOPT_FOLLOWLOCATION => self::HTTP_FOLLOWLOCATION,
				CURLOPT_PROXY => self::HTTP_PROXY,
				CURLOPT_ENCODING => self::HTTP_ENCODING,
				CURLOPT_HTTPHEADER => $httpheaders,
				CURLINFO_HEADER_OUT => true 
		) );
		
		if ($method == 'POST') {
			curl_setopt ( $c, CURLOPT_POST, true );
			
			$ps = array ();
			foreach ( $data as $k => $v ) {
				$ps [] = "{$k}={$v}";
			}
			curl_setopt ( $c, CURLOPT_POSTFIELDS, implode ( '&', $ps ) );
		} else if ($method == 'GET') {
			$params = array ();
			foreach ( $data as $k => $v ) {
				$params [] = urlencode ( $k ) . '=' . urlencode ( $v );
			}
			$qs = implode ( '&', $params );
			$url = strlen ( $qs ) > 0 ? $url . '?' . $qs : $url;
		} else {
			throw new \Exception ( 'Request failed! Unknow method=' . $method );
		}
		
		// echo 'url: ', $url, "\n";
		
		curl_setopt ( $c, CURLOPT_URL, $url );
		
		$response = curl_exec ( $c );
		$code = curl_getinfo ( $c, CURLINFO_HTTP_CODE );
		$info = curl_getinfo ( $c );
		curl_close ( $c );
		
		if ($code != 200) {
			throw new \Exception ( 'Request failed! code=' . $code . ', response= ' . $response );
			// echo 'CODE : '.$code ."\n";
			echo 'INFO: ' . var_export ( $info, true ) . "\n";
			// echo 'RESPONSE: '.var_export($response, true)."\n";
		}
		return $response;
	}
}
