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
 * Import handler class
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Model_Handler{
    /**
     * separator for product identifiers
     */
    const RELATION_SEPARATOR = ',';
    /**
     * separator for position
     */
    const POSITION_SEPARATOR = ':';

    /**
     * import relations
     * @access public
     * @param $rawData - data to import
     * @param $relation - relation type
     * @param $action - action type
     * @param $parse - parse method
     * @param $identifier - product identifier
     * @return Easylife_Relations_Model_Handler
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function importRelations($rawData, $relation, $action, $parse, $identifier){
        switch ($parse){
            case Easylife_Relations_Model_Import_Parse::EACH_LINE:
                $lines = explode("\n", $rawData);
                foreach ($lines as $line){
                    $line = trim($line);
                    $parts = explode(self::RELATION_SEPARATOR, $line);
                    //first product is main product
                    $mainId = $this->_getProductId(trim($parts[0]), $identifier);
                    if ($mainId){
                        unset($parts[0]);
                        foreach ($parts as $part){
                            $child = $this->_prepareForImport($part, $identifier);
                            if ($child['id']){
                                $toImport[$mainId][$child['id']]['position'] = $child['position'];
                            }
                        }
                    }
                }
                break;
            case Easylife_Relations_Model_Import_Parse::ALL_ON_LINE:
                $lines = explode("\n", $rawData);
                $toImport = array();
                foreach ($lines as $line){
                    $all = array();
                    $line = trim($line);
                    $parts = explode(self::RELATION_SEPARATOR, $line);
                    foreach ($parts as $part){
                        $child = $this->_prepareForImport($part, $identifier);
                        if ($child){
                            $all[] = $child;
                        }
                    }
                    foreach ($all as $key=>$value){
                        foreach ($all as $k=>$v){
                            if ($k != $key){
                                $toImport[$value['id']][$v['id']]['position'] = $v['position'];
                            }
                        }
                    }
                }
                break;
            case Easylife_Relations_Model_Import_Parse::ALL:
                $lines = explode("\n", $rawData);
                $all = array();
                foreach ($lines as $line){
                    $line = trim($line);
                    $parts = explode(self::RELATION_SEPARATOR, $line);
                    foreach ($parts as $part){
                        $child = $this->_prepareForImport($part, $identifier);
                        if ($child){
                            $all[] = $child;
                        }
                    }
                }
                $toImport = array();
                foreach ($all as $key=>$value){
                    foreach ($all as $k=>$v){
                        if ($k != $key){
                            $toImport[$value['id']][$v['id']]['position'] = $v['position'];
                        }
                    }
                }
                break;
            default:
                //custom handler
                $data = new Varien_Object(array(
                    'raw_data'  =>$rawData,
                    'relation'  =>$relation,
                    'action'    =>$action,
                    'parse'     =>$parse,
                    'identifier'=>$identifier,
                    'to_import' => array()//put results here
                ));
                Mage::dispatchEvent('easylife_relations_import_relation', array('data_object'=>$data));
                $toImport = $data->getToImport();
            break;
        }
        $this->_importRelated($toImport, $relation, $action);
        return $this;
    }

    /**
     * prepare data for import
     * @access protected
     * @param $data
     * @param $identifier
     * @return array|bool
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _prepareForImport($data, $identifier){
        $data = trim($data);
        $parts = explode(self::POSITION_SEPARATOR, $data);
        $realId = $this->_getProductId($parts[0], $identifier);
        if ($realId){
            return array(
                'id'=>$realId,
                'position'=>(isset($parts[1])) ? $parts[1] : 0
            );
        }
        return false;
    }

    /**
     * get id depending on identifier
     * @access public
     * @param $identifier
     * @param $type
     * @return mixed
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _getProductId($identifier, $type){
        if (!isset($this->_productIds[$type])){
            $this->_productIds[$type] = array();
        }
        if (!isset($this->_productIds[$type][$identifier])){
            if ($type == Easylife_Relations_Model_Import_Identifier::ID){
                //check if the product exists.
                //not sure this is the best approach
                $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', $identifier);
                if ($collection->getSize() > 0){
                    $this->_productIds[$type][$identifier] = $identifier;
                }
                else{
                    $this->_productIds[$type][$identifier] = false;
                }
            }
            elseif($type == Easylife_Relations_Model_Import_Identifier::SKU){
                $this->_productIds[$type][$identifier] = Mage::getSingleton('catalog/product')->getIdBySku($identifier);
            }
            else {
                $this->_productIds[$type][$identifier] = false;
            }
        }
        return $this->_productIds[$type][$identifier];
    }

    /**
     * store values to db
     * @access protected
     * @param $toImport
     * @param $relation
     * @param $action
     * @return Easylife_Relations_Model_Handler
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _importRelated($toImport, $relation, $action){
        $model = Mage::getSingleton('catalog/product_link')->setLinkTypeId($relation);
        $product = Mage::getModel('catalog/product');
        foreach ($toImport as $mainId=>$related){
            if ($action == Easylife_Relations_Model_Import_Action::ACTION_MERGE){
                $exist = array();
                $existingCollection = $model->getLinkCollection()
                    ->addLinkTypeIdFilter($relation)
                    ->setProduct($product->setId($mainId))
                    ->addProductIdFilter()
                    ->joinAttributes()
                ;
                foreach ($existingCollection as $item){
                    $exist[$item->getLinkedProductId()]['position'] = $item->getPosition();
                }
                foreach ($exist as $key=>$value){
                    if (!isset($related[$key])){
                        $related[$key] = $value;
                    }
                }
            }

            Mage::getResourceSingleton('catalog/product_link')->saveProductLinks(
                new Varien_Object(array('id'=>$mainId)),
                $related,
                $relation
            );
        }
        return $this;
    }
}