<?php
/*
 * @category   Payment
 * @package    Clictopay (clictopay.com.tn)
 * @copyright  Copyright (c) 2015 Aymencis
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Positif_Clictopay_RedirectController extends Mage_Core_Controller_Front_Action {
    public function getCheckout() {
    return Mage::getSingleton('checkout/session');
    }
    protected $order;
    protected function _expireAjax() {
        if (!Mage::getSingleton('checkout/session')->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
            exit;
        }
    }
    public function indexAction() {
        $this->loadLayout();
        $block = $this->getLayout()->createBlock('clictopay/redirect');
        $this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
    }
    public function successAction() {
        $post = $this->getRequest()->getPost();
        foreach ($_REQUEST as $k => $v) {
            $v = htmlspecialchars($v);
            $v = stripslashes($v);
            $post[$k] = $v;
        }
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($post['merchant_order_id']);
        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($session->getLastRealOrderId());
        
        $this->_redirect('checkout/onepage/success');
        $order->sendNewOrderEmail();
        $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
        $order->setData('ext_order_id',$post['order_number'] );
        $order->save();
        
    }
    public function cartAction() {
        $session = Mage::getSingleton('checkout/session');
        if ($session->getLastRealOrderId()) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($session->getLastRealOrderId());
            if ($order->getId()) {
                $order->cancel()->save();
            }
            $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
            $quote->setIsActive(true)->save();
        }
        $this->_redirect('checkout/cart');
    }
}
