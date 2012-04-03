<?php

/**
 * Paypal Checkout Library
 *
 * This is a basic library for the paypal mobile checkout NVP interface.
 *
 * @author		Devin Smith <devin@cana.la>
 * @date		2009.06.16
 *
 */


class Cana_Paypal {

	private $_payerId;	
	private $_amt;
	private $_currencycode = 'USD';
	private $_desc;
	private $_returnurl;
	private $_cancelurl;
	private $_apiUrl;
	private $_apiVersion = '3.0';
	private $_apiUser;
	private $_apiPass;
	private $_apiSignature;
	private $_token;
	private $_responseStatus;
	private $_request;
	const RESPONSE_SUCCESS = 'SUCCESS';

	public function setCheckout() {
		$response = $this->request($this->getRequest());
		$this->setToken(isset($response['TOKEN']) ? $response['TOKEN'] : '');
		return $response;
	}

	public function doCheckoutPayment() {
		return $this->request($this->getRequest());
	}


	public function request($request) {

		$curl = new Cana_Curl;
		$request['version'] = $this->getApiVersion();
		$request['pwd'] = $this->getApiPass();
		$request['user'] = $this->getApiUser();
		$request['signature'] = $this->getApiSignature();

		$res = $curl->request($this->getApiUrl(),$request);
		$res = $this->parseResponseArgs($res);


		if (strtoupper($res['ACK']) == self::RESPONSE_SUCCESS) {
			$this->setResponseStatus(true);
		} else {
			$this->setResponseStatus(false);
		}

		return $res;
	}


	public function parseResponseArgs($str) {
		$str = explode('&',$str);
		if (count($str > 0)) {
			foreach ($str as $value) {
				$item = explode('=',$value);
				$retval[$item[0]] = urldecode($item[1]);
			}
		}
		return $retval;
	}


	public function __call($func, $args) {
		if (strpos($func,'get') === 0) {
			$func = lcfirst(str_replace('get','',$func));
			return $this->$func;
		}
		if (strpos($func,'set') === 0) {
			$func = lcfirst(str_replace('set','',$func));
			$this->$func = array_pop($args);
			return $this;
		}
	}
}
