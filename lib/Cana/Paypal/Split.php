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


class Cana_Paypal_Split extends Cana_Paypal {

	public function setCheckout($params = [], $items = []) {
		$request = $params;
		$request['returnurl'] = $this->getReturnurl();
		$request['cancelurl'] = $this->getCancelurl();
		$request['method'] = 'SetExpressCheckout';
		$request['noshipping'] = 1;
		$request['allownote'] = 1;
		
		$x = 0;
		foreach ($items as $key => $value) {
			$value = (object)$value;
			$request['paymentrequest_'.$x.'_amt'] = $value->amt * $value->qty;
			$request['paymentrequest_'.$x.'_itemamt'] = $value->amt * $value->qty;
			$request['paymentrequest_'.$x.'_currencycode'] = $value->currencycode;
			$request['paymentrequest_'.$x.'_desc'] = $value->desc;
			$request['paymentrequest_'.$x.'_sellerpaypalaccountid'] = $value->seller;
			$request['paymentrequest_'.$x.'_paymentrequestid'] = $value->invoice;
			
			$request['l_paymentrequest_'.$x.'_name0'] = $value->name;
			$request['l_paymentrequest_'.$x.'_desc0'] = $value->itemdesc;
			$request['l_paymentrequest_'.$x.'_amt0'] = $value->amt;
			$request['l_paymentrequest_'.$x.'_qty0'] = $value->qty;

			$x++;
		}

		$this->setRequest($request);
		return parent::setCheckout();
	}

	public function doCheckoutPayment() {
		$request['token'] = $this->getToken();
		$request['method'] = 'DoExpressCheckoutPayment';
		
		$x = 0;
		foreach ($this->getItems() as $key => $value) {
			$value = (object)$value;
			$request['paymentrequest_'.$x.'_amt'] = $value->amt * $value->qty;
			$request['paymentrequest_'.$x.'_sellerpaypalaccountid'] = $value->seller;
			$request['paymentrequest_'.$x.'_paymentrequestid'] = $value->invoice;
			$request['paymentrequest_'.$x.'_paymentaction'] = 'Sale';
			$x++;
		}

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
