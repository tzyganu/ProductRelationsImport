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
 * Relation type source model
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Model_Import_Relation extends Easylife_Relations_Model_Import_Abstract{
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
                'label' => Mage::helper('easylife_relations')->__('Related products'),
                'value' => Mage_Catalog_Model_Product_Link::LINK_TYPE_RELATED,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Up sells'),
                'value' => Mage_Catalog_Model_Product_Link::LINK_TYPE_UPSELL,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Cross sells'),
                'value' => Mage_Catalog_Model_Product_Link::LINK_TYPE_CROSSSELL,
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
        return 'easylife_relations_get_options_relation';
    }
}