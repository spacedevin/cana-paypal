<?php
/**
 *
 * Paypal Mobile Checkout Library - sample implimentation
 *
 * This is a basic library for the paypal mobile checkout NVP interface.
 * This library is compatable with Caffeine Engine, Zend Framework, as
 * well as being used stand alone.
 *
 * Devin Smith            www.devin-smith.com            2009.06.16
 *
 */

require_once 'Curl.php';
require_once 'Paypal.php';
require_once 'Paypal/Mobile.php';


$paypal = new Caffeine_Paypal_Mobile;

/**
 * configuration for both sandbox and live NVP
 */
$config['env'] = 'dev';
if ($config['env'] != 'live') {
	$config['paypal']['api']['user'] = 'paypalx_api1.arzynik.com';
	$config['paypal']['api']['pass'] = 'C3Y45D2TPSEGR89P';
	$config['paypal']['api']['signature'] = 'AQU0e5vuZCvSg-XJploSa.sGUDlpAmwTh8rXHJhJILYDB3wa59d.BUyE';
	$config['paypal']['api']['endpoint'] = 'https://api-3t.sandbox.paypal.com/nvp';
	$config['paypal']['api']['url'] = 'https://www.sandbox.paypal.com/wc?t=';
} else {
	$config['paypal']['api']['user'] = '';
	$config['paypal']['api']['pass'] = '';
	$config['paypal']['api']['signature'] = '';
	$config['paypal']['api']['endpoint'] = 'https://api-3t.paypal.com/nvp';
	$config['paypal']['api']['url'] = 'https://mobile.paypal.com/wc?t=';
}
$config['paypal']['api']['version'] = '3.0';


/**
 * give our library our config
 */
$paypal->setApiVersion($config['paypal']['api']['version'])
	->setApiUrl($config['paypal']['api']['endpoint'])
	->setApiUser($config['paypal']['api']['user'])
	->setApiPass($config['paypal']['api']['pass'])
	->setApiSignature($config['paypal']['api']['signature']);


// SetMobileChechout
if (!isset($_REQUEST['token']) || $_REQUEST['token'] == '') {


	/**
	 * Optional params
	 * 
	 * $params['email'] 			= '';	// email to propagate the login page with
	 * $params['phonenum'] 			= '';	// phone number to propagate the login page with
	 * $params['taxamt'] 			= '';	// tax
	 * $params['shippingamt'] 		= '';	// shipping
	 * $params['number'] 			= '';	// internal item number
	 * $params['custom'] 			= '';	// internal returned data
	 * $params['invnum'] 			= '';	// unique invoice number
	 * $params['addressdisplay'] 	= '0';	// 1|0 require address
	 * $params['sharephonenum'] 	= '1';	// 1|0 return customers mobile number
	 * $params['shiptocity'] 		= '';	// city to propogate address form with
	 * $params['shiptostate'] 		= '';	// state to propogate the address form with
	 * $params['shiptocountry'] 	= '';	// country to propogate the address form with
	 * $params['shiptozip'] 		= '';	// zip to propogate the address form with
	 */

	$response = $paypal->setAmt('5.00')
		->setCurrencycode('USD')
		->setDesc('Test Item')
		->setReturnurl('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
		->setCancelurl('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])
		->setMobileCheckout($params);
	
	
	if ($paypal->getResponseStatus()) {
		/**
		 * forward user off to paypal
		 */
		header('Location: '.$config['paypal']['api']['url'].urldecode($paypal->getToken()));	
	} else {
		/**
		 * error!!
		 */
		echo 'ERROR<br /><pre>'.print_r($response,1);
	}

// DoMobileCheckout
} else {

	$response = $paypal->setToken($_REQUEST['token'])
		->doMobileCheckoutPayment();

	/**
	 * success!!
	 */
	if ($paypal->getResponseStatus()) {
		echo 'SUCCESS<br /><pre>'.print_r($response,1);

	/**
	 * Returned data
	 *
	 * $response['email']					// buyers email address
	 * $response['payerid']					// unique buyer id
	 * $response['payerstatus']				// status of buyers email
	 * $response['countrycode']				// country code
	 * $response['business']				// buyers business name
	 * $response['phonenum']				// buyers phone number
	 * $response['salutation']				// buyers salutation
	 * $response['firstname']				// buyers first name
	 * $response['middlename']				// buyers middle name
	 * $response['lastname']				// buyers last name
	 * $response['suffix']					// buyers suffix
	 *
	 * $response['custom']					// internaly returned data
	 * $response['invnum']					// returned unique invoice number
	 * $response['transactionid']			// external transaction id
	 * $response['parenttransactionid'] 	// used for cancels and reversals
	 * $response['receiptid']				// external receipt id
	 *
	 * $response['ordertime']				// current date and time
	 * $response['amt']						// order amount
	 * $response['currencycode']			// currency code		
	 * $response['feeamt']					// processing fee deducted
	 * $response['exchangerate']			// exchange rate for currency conversion
	 *
	 * $response['transactiontype']			// send-money
	 * $response['paymentstatus']			// Completed, Pending, or Reversed
	 * $response['reasoncode']				// used for reversals
	 *
	 * $response['name']					// buyers shipping name
	 * $response['shiptostreet']			// buyers shipping address
	 * $response['shiptostreet2']			// buters shipping address 2
	 * $response['shiptocity']				// buyers shipping city
	 * $response['shiptostate']				// buyers shipping state
	 * $response['shiptocountry']			// buyers shipping country
	 * $response['shiptozip']				// buyers shipping zip
	 * $response['shiptophonenum']			// buyers shipping phone number
	 * $response['addressowner']			// eBay or PayPal
	 * $response['addressstatus']			// None, Confirmed, or Unconfirmed
	 */


	} else {
		/**
		 * error!!
		 */
		echo 'ERROR<br /><pre>'.print_r($response,1);
	}

}

