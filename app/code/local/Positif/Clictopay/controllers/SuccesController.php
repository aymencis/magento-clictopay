<?php
class Positif_Clictopay_SuccesController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        Mage_Core_Controller_Varien_Action::_redirect( 'checkout/onepage/success' );
		/*$this->loadLayout();
        $block = $this->getLayout()->createBlock(
		'Mage_Core_Block_Template',
		'clictopay_succes',
		array('template' => 'clictopay/succes.phtml')
		);
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();*/
    }
}
