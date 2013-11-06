<?php
/**
 * Easylife_Relations extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE_EASYLIFE_RELATIONS.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       Easylife
 * @package        Easylife_Relations
 * @copyright      Copyright (c) 2013
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml helper
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Helper_Adminhtml extends Mage_Core_Helper_Abstract{
    /**
     * tooltip block
     * @var Mage_Adminhtml_Block_Template
     */
    protected $_tooltipBlock = null;
    /**
     * get the tooltip html
     * @access public
     * @param string $title
     * @param string $text
     * @param $width
     * @return string
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getTooltipHtml($title, $text, $width = '300px'){
        return $this->getTooltipBlock()->setTitle($title)->setMessage($text)->setWidth($width)->toHtml();
    }
    /**
     * get the tooltip block for help messages
     * @access public
     * @return Mage_Adminhtml_Block_Template
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getTooltipBlock(){
        if (is_null($this->_tooltipBlock)){
            $this->_tooltipBlock = Mage::app()->getLayout()->createBlock('adminhtml/template')->setTemplate('easylife_relations/tooltip.phtml');
        }
        return $this->_tooltipBlock;
    }
}
