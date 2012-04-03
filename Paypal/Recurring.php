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


class Cana_Paypal_Recurring extends Cana_Paypal {

	public function setCheckout($params = array()) {
		$request = $params;
		$request['amt'] = $this->getAmt();
		$request['returnurl'] = $this->getReturnurl();
		$request['cancelurl'] = $this->getCancelurl();
		$request['noshipping'] = '1';
		$request['initamt'] = $this->getInitAmt();
		$request['l_billingtype0'] = 'RecurringPayments';
		$request['l_billingagreementdescription0'] = $this->getDesc();
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
		$request['currencycode'] = $this->getCurrencycode();
		$request['l_billingtype'] = 'ReccuringPayment';
		$this->setRequest($request);

		return parent::doCheckoutPayment();
	}

	public function createProfile() {
		$request['billingperiod'] = $this->getPeriod();
		$request['billingfrequency'] = $this->getFrequency();

		$date = new DateTime('today');
		$request['profilestartdate'] = $date->format('Y-m-d').'T'.$date->format('H:i:s').'z';

		$request['token'] = $this->getToken();
		$request['amt'] = $this->getAmt();
		$request['initamt'] = $this->getInitAmt();
		$request['desc'] = $this->getDesc();
		$request['method'] = 'CreateRecurringPaymentsProfile';
		$this->setRequest($request);

		return $this->request($this->getRequest());
	}
	
	
	public function getDetails() {
		$request['token'] = $this->getToken();
		$request['method'] = 'GetExpressCheckoutDetails';
		$this->setRequest($request);

		return $this->request($this->getRequest());
	}

}
