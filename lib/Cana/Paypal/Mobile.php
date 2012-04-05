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


class Cana_Paypal_Mobile extends Cana_Paypal {
	
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
