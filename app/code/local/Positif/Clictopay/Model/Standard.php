<?php
 
class Positif_Clictopay_Model_Standard extends Mage_Payment_Model_Method_Abstract
{
 
protected $_code = 'clictopay';
protected $_isInitializeNeeded      = true;
protected $_isGateway               = true;
protected $_canUseInternal          = false;
protected $_canUseForMultishipping  = false;

public function getCheckout() {
        return Mage::getSingleton('checkout/session');
    }
//get purchase routine URL
    public function getUrl() {
        return $this->getConfigData('submit_url');
    }
public function getOrderPlaceRedirectUrl()
{
//when you click on place order you will be redirected on this url, if you don't want this action remove this method
return Mage::getUrl('clictopay/redirect', array('_secure' => true));
}
//get HTML form data
public function getFormFields() {
    $order_id = $this->getCheckout()->getLastRealOrderId();
    //$order_id = Mage::getSingleton('checkout/session')->getLastOrderId();
    $order    = Mage::getModel('sales/order')->loadByIncrementId($order_id);
    $amount   =(float)$order->getGrandTotal();
	$amount = sprintf('%.3f', $amount);
    $currency_code = $order->getOrderCurrencyCode();
    $encryptedId = bin2hex(Mage::helper('core')->encrypt(base64_encode($order_id)));
    $tcoFields = array();
    $tcoFields['affilie'] = $this->getConfigData('merchant_id');
    $tcoFields['Reference'] = $encryptedId;
    $tcoFields['Devise'] = $currency_code;
    $tcoFields['Montant'] = $amount;
    return $tcoFields;
}
}
