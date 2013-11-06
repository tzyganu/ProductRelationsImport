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
 * Action source model
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Model_Import_Action extends Easylife_Relations_Model_Import_Abstract{
    /**
     * replace existing relations
     */
    const ACTION_REPLACE    = 1;
    /**
     * merge existing with new
     */
    const ACTION_MERGE      = 2;
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
                'label' => Mage::helper('easylife_relations')->__('Replace existing relations'),
                'value' => self::ACTION_REPLACE,
            );
            $this->_options[] = array(
                'label' => Mage::helper('easylife_relations')->__('Merge with existing relations'),
                'value' => self::ACTION_MERGE,
            );
        }
        $options = $this->_options;
        if ($withEmpty){
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;
    }
}