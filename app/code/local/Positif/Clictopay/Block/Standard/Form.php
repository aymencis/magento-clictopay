<?php

class Positif_Clictopay_Block_Standard_Form extends Mage_Payment_Block_Form
{
	protected function _construct() {
		parent::_construct();
        $this->setTemplate( 'clictopay/view.phtml' );
	}
}
