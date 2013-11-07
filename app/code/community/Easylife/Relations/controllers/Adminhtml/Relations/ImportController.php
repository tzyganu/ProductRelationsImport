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
 * Main controller
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Adminhtml_Relations_ImportController extends Mage_Adminhtml_Controller_Action {
    /**
     * default action
     * @access public
     * @return void
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function indexAction(){
        $this->_forward('edit');
    }
    /**
     * show import form
     * @access public
     * @return void
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function editAction(){
        $this->loadLayout();
        $this->_title(Mage::helper('easylife_relations')->__('Import product relations'))
            ->_title(Mage::helper('easylife_relations')->__('Import product relations'));
        $this->renderLayout();
    }
    /**
     * handle for submit
     * @access public
     * @return void
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function saveAction(){
        try{

            $type = $this->getRequest()->getPost('type');
            $this->_validateInput($type, 'easylife_relations/import_type', Mage::helper('easylife_relations')->__('Import type is not valid'));

            $relation = $this->getRequest()->getPost('relation');
            $this->_validateInput($relation, 'easylife_relations/import_relation', Mage::helper('easylife_relations')->__('Relation type is not valid'));

            $action = $this->getRequest()->getPost('action');
            $this->_validateInput($action, 'easylife_relations/import_action', Mage::helper('easylife_relations')->__('Action type is not valid'));

            $identifier = $this->getRequest()->getPost('identifier');
            $this->_validateInput($identifier, 'easylife_relations/import_identifier', Mage::helper('easylife_relations')->__('Identifier is not valid'));

            $parse = $this->getRequest()->getPost('parse');
            $this->_validateInput($parse, 'easylife_relations/import_parse', Mage::helper('easylife_relations')->__('Import rule is not valid'));

            if ($type == Easylife_Relations_Model_Import_Type::IMPORT_TYPE_DIRECT){
                $rawData = $this->getRequest()->getPost('related');
            }
            elseif ($type == Easylife_Relations_Model_Import_Type::IMPORT_TYPE_UPLOAD){
                $csvFile = $_FILES['import_file']['tmp_name'];
                //TODO: maybe use Varien_Io_File to read contents;
                $rawData = file_get_contents($csvFile);
            }
            else {
                throw new Easylife_Relations_Exception(Mage::helper('easylife_relations')->__('Import type is not valid'));
            }
            $log = Mage::getSingleton('easylife_relations/handler')->importRelations($rawData, $relation, $action, $parse, $identifier);
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('easylife_relations')->__('Import successful'));
            $session = Mage::getSingleton('adminhtml/session');
            foreach ($log as $type=>$messages){
                $method = 'add'.ucfirst($type);
                if (method_exists($session, $method)){
                    foreach ($messages as $message){
                        $session->$method($message);
                    }
                }
            }
        }
        catch (Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setRelationImportData($this->getRequest()->getPost());
        }
        catch (Exception $e){
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('easylife_relations')->__('An error occurred'));
            Mage::getSingleton('adminhtml/session')->setRelationImportData($this->getRequest()->getPost());
        }
        $this->_redirectReferer();
    }

    /**
     * validate input data against available values
     * @access protected
     * @param $param
     * @param $allowedParamsModel
     * @param $errorMessage
     * @return bool
     * @throws Easylife_Relations_Exception
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _validateInput($param, $allowedParamsModel, $errorMessage){
        $allowedParams = Mage::getSingleton($allowedParamsModel)->getOptionsAsArray();
        if (!isset($allowedParams[$param])){
            throw new Easylife_Relations_Exception($errorMessage);
        }
        return true;
    }
}