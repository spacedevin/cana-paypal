<?php
/**
 *
 * Paypal Mobile Checkout Library
 *
 * This is a basic library for the paypal mobile checkout NVP interface.
 * This library is compatable with Caffeine Engine, Zend Framework, as
 * well as being used stand alone.
 *
 * Devin Smith           2009.06.16            www.devin-smith.com
 *
 */


class Caffeine_Paypal {

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

		$curl = new Caffeine_Curl;
		$request['version'] = $this->getApiVersion();
		$request['pwd'] = $this->getApiPass();
		$request['user'] = $this->getApiUser();
		$request['signature'] = $this->getApiSignature();

		$res = $curl->request($this->getApiUrl(),$request);

		$res = $this->parseResponseArgs($res);
		//print_r($res);
		//$nvpReqArray = $this->parseResponseArgs($nvpreq);
		//$_SESSION['nvpReqArray']=$nvpReqArray;

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


	public function getAmt() {
		return $this->_amt;
	}

	public function setAmt($v) {
		$this->_amt = $v;
		return $this;
	}

	public function getCurrencycode() {
		return $this->_currencycode;
	}

	public function setCurrencycode($v) {
		$this->_currencycode = $v;
		return $this;
	}

	public function getDesc() {
		return $this->_desc;
	}

	public function setDesc($v) {
		$this->_desc = $v;
		return $this;
	}

	public function getReturnurl() {
		return $this->_returnurl;
	}

	public function setReturnurl($v) {
		$this->_returnurl = $v;
		return $this;
	}

	public function getCancelurl() {
		return $this->_cancelurl;
	}

	public function setCancelurl($v) {
		$this->_cancelurl = $v;
		return $this;
	}

	public function getApiUrl() {
		return $this->_apiUrl;
	}

	public function setApiUrl($v) {
		$this->_apiUrl = $v;
		return $this;
	}

	public function getApiVersion() {
		return $this->_apiVersion;
	}

	public function setApiVersion($v) {
		$this->_apiVersion = $v;
		return $this;
	}

	public function getApiUser() {
		return $this->_apiUser;
	}

	public function setApiUser($v) {
		$this->_apiUser = $v;
		return $this;
	}

	public function getApiPass() {
		return $this->_apiPass;
	}

	public function setApiPass($v) {
		$this->_apiPass = $v;
		return $this;
	}

	public function getApiSignature() {
		return $this->_apiSignature;
	}

	public function setApiSignature($v) {
		$this->_apiSignature = $v;
		return $this;
	}

	public function getToken() {
		return $this->_token;
	}

	public function setToken($v) {
		$this->_token = $v;
		return $this;
	}

	public function getResponseStatus() {
		return $this->_responseStatus;
	}

	public function setResponseStatus($v) {
		$this->_responseStatus = $v;
		return $this;
	}

	public function getRequest() {
		return $this->_request;
	}

	public function setRequest($v) {
		$this->_request = $v;
		return $this;
	}

	public function getPayerId() {
		return $this->_payerId;
	}

	public function setPayerId($id) {
		$this->_payerId = $id;
		return $this;
	}
}
