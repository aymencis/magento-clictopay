<?php
/*
 * @category   Payment
 * @package    Clictopay (clictopay.com.tn)
 * @copyright  Copyright (c) 2015 Aymencis
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Positif_Clictopay_NotificationController extends Mage_Core_Controller_Front_Action {
    public function indexAction() {
        $insMessage = $this->getRequest()->getPost();
        foreach ($_REQUEST as $k => $v) {
        $v = htmlspecialchars($v);
        $v = stripslashes($v);
        $insMessage[$k] = $v;
        }
        $ref = $insMessage['Reference'];
        $act  = $insMessage['Action'];
        $orderId = $ref;
        if((int)$orderId == 0) {
            die('Hash Incorrect');
        }
        $order = Mage::getModel('sales/order');
        $order->loadByIncrementId($orderId);
        
        switch ($act) {
            case "DETAIL":
                $montant = number_format($order->getGrandTotal(), 3, '.', '');
                echo "Reference=$ref&Action=$act&Reponse=$montant";
				
                break;
            case "ERREUR":
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->addStatusHistoryComment('Error occured')->save();
                 echo "Reference=$ref&Action=$act&Reponse=OK";
                break;
            case "ACCORD":
            	try {
					$order->sendNewOrderEmail();
				} catch (Exception $e) {
					echo $e->getMessage();
				}
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->addStatusHistoryComment('Order passed')->save();
                try {
                    if(!$order->canInvoice()) {
                        Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
                    }
                    $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                    if (!$invoice->getTotalQty()) {
                        Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                    }
                    $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                    $invoice->register();
					$order->addStatusHistoryComment('Invoiced', false);
					$order->addStatusHistoryComment('AUTORISATION CODE : '.$insMessage['Param'], false);
					
                    $transactionSave = Mage::getModel('core/resource_transaction')
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder());
                    $transactionSave->save();
					$order->save();
					$invoice->sendEmail ();
					$invoice->setEmailSent ( true );
                } catch (Mage_Core_Exception $e) {
                    echo $e;
                }
                echo "Reference=$ref&Action=$act&Reponse=OK";
                break;
            case "REFUS":
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->addStatusHistoryComment('Order refused.')->save();
                echo "Reference=$ref&Action=$act&Reponse=OK";
                break;
            case "ANNULATION":
                $order->setState(Mage_Sales_Model_Order::STATE_CANCELED, true)->addStatusHistoryComment('Order canceled.')->save();
                echo "Reference=$ref&Action=$act&Reponse=OK";
                break;

            default:
                break;
        }
    }
}
?>
