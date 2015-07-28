<?php
class Positif_Clictopay_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
protected $_code = 'clictopay';
protected $_formBlockType = 'clictopay/standard_form';
protected $_isInitializeNeeded      = true;
protected $_isGateway               = true;
protected $_canUseInternal          = false;
protected $_canUseForMultishipping  = false;

public function createFormBlock($name) {
	$block = $this->getLayout()->createBlock('clictopay/standard_form', $name)
		->setMethod('clictopay')
		->setPayment($this->getPayment())
		->setTemplate('clictopay/view.phtml');

	return $block;
}
public function getCheckout() {
	return Mage::getSingleton('checkout/session');
}
//get purchase routine URL
public function getUrl() {
	return $this->getConfigData('submit_url');
}
public function getOrderPlaceRedirectUrl() {
	//when you click on place order you will be redirected on this url, if you don't want this action remove this method
	return Mage::getUrl('clictopay/redirect', array('_secure' => true));
}
//get HTML form data
public function getFormFields() {
    $order_id = $this->getCheckout()->getLastRealOrderId();
    $order    = Mage::getModel('sales/order')->loadByIncrementId($order_id);
    $amount   = round($order->getGrandTotal(), 2);
    $amount	  = number_format($amount,2);
    $currency_code = $order->getOrderCurrencyCode();
    $tcoFields = array();
    $tcoFields['affilie'] = $this->getConfigData('merchant_id');
    $tcoFields['reference'] = $order_id;
    $tcoFields['devise'] = $currency_code;
    $tcoFields['montant'] = $amount;
    return $tcoFields;
}
}
