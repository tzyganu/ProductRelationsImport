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
 * Main form container
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Block_Adminhtml_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function __construct() {
        parent::__construct();
        $this->_blockGroup = 'easylife_relations';
        $this->_controller = 'adminhtml_import';
        $this->_updateButton('save', 'label', Mage::helper('easylife_relations')->__('Submit'));
        $this->_removeButton('delete');
        $this->_removeButton('back');

        $this->_formScripts[] = "
            function refreshOptions(){
                var importType = $('type').value;
                var actionType = $('action').value;
                if (importType == ".Easylife_Relations_Model_Import_Type::IMPORT_TYPE_UPLOAD."){
                    $('import_file').up(1).show();
                    if (!$('import_file').hasClassName('required-entry')){
                        $('import_file').addClassName('required-entry');
                    }

                    $('related').up(1).hide();
                    $('related').removeClassName('required-entry');
                }
                else{
                    $('import_file').removeClassName('required-entry');
                    $('import_file').up(1).hide();

                    $('related').up(1).show();
                    if (!$('related').hasClassName('required-entry')){
                        $('related').addClassName('required-entry');
                    }
                }
            }
            document.observe(\"dom:loaded\", function() {
                refreshOptions();
            });
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    public function getHeaderText() {
        return Mage::helper('easylife_relations')->__('Import product relations');
    }
}