<?php
/*
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Payment
 * @package    Clictopay (clictopay.com.tn)
 * @copyright  Copyright (c) 2015 Aymencis
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Positif_Clictopay_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $clictopay = Mage::getModel('clictopay/standard');
        $form = new Varien_Data_Form();
        $form->setAction($clictopay->getUrl())
            ->setId('tcopay')
            ->setName('tcopay')
            ->setMethod('POST')
            ->setUseContainer(true);
        $clictopay->getFormFields();
        foreach ($clictopay->getFormFields() as $field=>$value) {
           $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value, 'size'=>200));
        }
        $form->addField('tcosubmit', 'submit', array('name'=>'tcosubmit'));
		$form->removeField("form_key");
        $html = '<style> #tcosubmit {display:none;} </style>';
        $html .= '<div class="clictopay"><img src='.$this->getSkinUrl("images/logo_ctp.jpg").' alt=""/><img src='.$this->getSkinUrl("images/opc-ajax-loader.gif").' alt=""/></div>';
        $html .= $form->toHtml();
        $html .= '<script type="text/javascript">document.getElementById("tcopay").submit();</script>';
        return $html;
    }
}
?>
