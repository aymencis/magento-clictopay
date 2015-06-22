<?php
class Positif_Clictopay_EchecController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        Mage_Core_Controller_Varien_Action::_redirect( 'checkout/onepage/failure' );
		/*$this->loadLayout();
        $block = $this->getLayout()->createBlock(
		'Mage_Core_Block_Template',
		'clictopay_echec',
		array('template' => 'clictopay/echec.phtml')
		);
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();*/
    }
}
