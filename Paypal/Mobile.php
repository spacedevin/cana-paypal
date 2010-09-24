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


class Caffeine_Paypal_Mobile extends Caffeine_Paypal {
	
	public function setCheckout() {
		$request['amt'] = $this->getAmt();
		$request['currencycode'] = $this->getCurrencycode();
		$request['desc'] = $this->getDesc();
		$request['returnurl'] = $this->getReturnurl();
		$request['cancelurl'] = $this->getCancelurl();
		$request['method'] = 'SetMobileCheckout';
		$this->setRequest($request);

		return parent::setCheckout();
	}

	public function doCheckoutPayment() {
		$request['token'] = $this->getToken();
		$request['method'] = 'DoMobileCheckOutPayment';
		$this->setRequest($request);

		return parent::doCheckoutPayment();
	}


}

