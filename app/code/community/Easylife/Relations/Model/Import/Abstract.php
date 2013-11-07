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
 * Abstract source model
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
abstract class Easylife_Relations_Model_Import_Abstract {
    /**
     * cache options
     * @var null|array
     */
    protected $_options = null;
    /**
     * event name dispatched when getting the options
     * @var string
     */
    protected $_eventName = 'easylife_relations_get_action_options';
    /**
     * get options as array: var[] = array('value'=>value, 'label'=>label)
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public abstract function getAllOptions($withEmpty);
    /**
     * getter for event name
     * @access public
     * @return mixed
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public abstract function getEventName();

    /**
     * get options as array: var[key] = value
     * @access public
     * @param bool $withEmpty
     * @return array
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getOptionsAsArray($withEmpty = false){
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option){
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * dispatch event for altering the options
     * @access protected
     * @return $this
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _dispatchEvent(){
        $eventName = $this->getEventName();
        if ($eventName){
            $obj = new Varien_Object(array('options'=>$this->_options));
            Mage::dispatchEvent($this->getEventName(), array('data_object'=>$obj));
            $this->_options = $obj->getOptions();
        }
        return $this;
    }
}