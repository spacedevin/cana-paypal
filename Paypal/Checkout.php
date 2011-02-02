<?php
/**
 *
 * Paypal Checkout Library
 *
 * This is a basic library for the paypal mobile checkout NVP interface.
 * This library is compatable with Caffeine Engine, Zend Framework, as
 * well as being used stand alone.
 *
 * Devin Smith           2009.06.16            www.devin-smith.com
 *
 */


class Caffeine_Paypal_Checkout extends Caffeine_Paypal {

	public function setCheckout($params = array()) {
		$request = $params;
		$request['amt'] = $this->getAmt();
		$request['currencycode'] = $this->getCurrencycode();
		$request['desc'] = $this->getDesc();
		$request['returnurl'] = $this->getReturnurl();
		$request['cancelurl'] = $this->getCancelurl();
		$request['method'] = 'SetExpressCheckout';
		$this->setRequest($request);

		return parent::setCheckout();
	}

	public function doCheckoutPayment() {
		$request['token'] = $this->getToken();
		$request['method'] = 'DoExpressCheckoutPayment';
		$request['paymentaction'] = 'Sale';
		$request['payerid'] = $this->getPayerId();
		$request['amt'] = $this->getAmt();
		$request['ordertotal'] = $this->getAmt();
		$this->setRequest($request);

		return parent::doCheckoutPayment();
	}
	
	
	public function getDetails($id) {
		$request['transactionid'] = $id;
		$request['method'] = 'GetTransactionDetails';
		$this->setRequest($request);

		return $this->request($this->getRequest());
	}

}
