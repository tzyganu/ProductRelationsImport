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
 * Identifier source model
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Model_Import_Identifier extends Easylife_Relations_Model_Import_Abstract{
    /**
     * use product id
     */
    const ID    = 1;
    /**
     * use product sku
     */
    const SKU   = 2;
    /**
     * cache options
     * @var null|array
     */
    protected $_options = null;
    /**
     * get options as array: var[] = array('value'=>value, 'label'=>label)
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getAllOptions($withEmpty = false){
        if (is_null($this->_options)){
            $this->_options = array();
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Product id'),
                'value' => self::ID,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Product SKU'),
                'value' => self::SKU,
            );
            $this->_dispatchEvent();
        }
        $options = $this->_options;
        if ($withEmpty){
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;
    }
    /**
     * getter for event name
     * @access public
     * @return mixed
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getEventName(){
        return 'easylife_relations_get_options_identifier';
    }
}