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
 * Parse method source model
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Model_Import_Parse extends Easylife_Relations_Model_Import_Abstract{
    /**
     * Relate all on one line to first product in line.
     */
    const EACH_LINE     = 1;
    /**
     * Relate all products on one line
     */
    const ALL_ON_LINE   = 2;
    /**
     * Relate all products among themselves
     */
    const ALL           = 3;
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
                'label' => Mage::helper('easylife_relations')->__('Relate all on one line to first product in line.'),
                'value' => self::EACH_LINE,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Relate all products on one line'),
                'value' => self::ALL_ON_LINE,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Relate all products among themselves'),
                'value' => self::ALL,
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
        return 'easylife_relations_get_options_parse';
    }
}