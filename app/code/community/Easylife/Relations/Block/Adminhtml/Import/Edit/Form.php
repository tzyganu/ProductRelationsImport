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
 * Main form
 *
 * @category    Easylife
 * @package     Easylife_Relations
 * @author      Marius Strajeru <marius.strajeru@gmail.com>
 */
class Easylife_Relations_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access public
     * @return Easylife_Relations_Block_Adminhtml_Import_Edit_Form
     * @author Marius Strajeru <marius.strajeru@gmail.com>
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getUrl('adminhtml/relations_import/save'), 'method' => 'post', 'enctype'=>'multipart/form-data')
        );
        $this->setForm($form);
        $fieldset = $form->addFieldset('import_form', array('legend'=>Mage::helper('easylife_relations')->__('Import relations')));

        $values = Mage::getSingleton('easylife_relations/import_type')->getAllOptions(true);
        $fieldset->addField('type', 'select', array(
            'label'     => Mage::helper('easylife_relations')->__('Import type'),
            'name'      =>'type',
            'values'    => $values,
            'onchange'  => 'refreshOptions()',
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Import type'),
                Mage::helper('easylife_relations')->__('You can upload a file or manually input the data')
            )
        ));
        $relations = Mage::getSingleton('easylife_relations/import_relation')->getAllOptions(true);
        $fieldset->addField('relation', 'select', array(
            'label'     =>Mage::helper('easylife_relations')->__('Relation type'),
            'name'      =>'relation',
            'values'    => $relations,
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Relation type'),
                Mage::helper('easylife_relations')->__('Select what you want to import.').'<br />'.
                    '<ul><li>'.implode('</li><li>', Mage::getSingleton('easylife_relations/import_relation')->getOptionsAsArray(false)).'</li></ul>')
        ));

        $actions = Mage::getSingleton('easylife_relations/import_action')->getAllOptions(true);
        $fieldset->addField('action', 'select', array(
            'label'     =>Mage::helper('easylife_relations')->__('Action'),
            'name'      =>'action',
            'values'    => $actions,
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Action'),
                Mage::helper('easylife_relations')->__('Select the import behaviour. Merge current existing relations or replace them with the new ones'))
        ));
        $identifiers = Mage::getSingleton('easylife_relations/import_identifier')->getAllOptions(true);
        $fieldset->addField('identifier', 'select', array(
            'label'     =>Mage::helper('easylife_relations')->__('Work with'),
            'name'      =>'identifier',
            'values'    => $identifiers,
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Work with'),
                Mage::helper('easylife_relations')->__('Specify if the values you enter are product ids or product SKUs'))
        ));
        $parseTypes = Mage::getSingleton('easylife_relations/import_parse')->getAllOptions(true);
        $fieldset->addField('parse', 'select', array(
            'label'     =>Mage::helper('easylife_relations')->__('Import rules'),
            'name'      =>'parse',
            'values'    => $parseTypes,
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Import rules'),
                Mage::helper('easylife_relations')->__('You can define from here how the import should work.<ul><li><strong>Relate all on one line to first product in line.</strong> - first product in the row will be considered as the main product. All others will be added as relations to it.</li><li><strong>Relate all products on one line</strong> - all products on one line will be added as relations to all other products on the same line.</li><li><strong>Relate all products among themselves</strong> - all products will be added as relations to all products regardless of the line they are in.</li></ul>'))
        ));

        $fieldset->addField('related', 'textarea', array(
            'label'     =>Mage::helper('easylife_relations')->__('Related products identifiers'),
            'name'      =>'related',
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('Related products identifiers'),
                Mage::helper('easylife_relations')->__('Specify the product ids or SKUs to be imported. Use comma as a product separator and semicolon as a separator between product identifier and position. Example: 345,33:5,29. If you select import rule "Relate all on one line to first product in line" then products with identifiers 33 and 29 will be added as related to product 345. Product 33 will have the position 5 and 29 will have the default position 0 because it doesn\'t have one specified')),
            'note' => Mage::helper('easylife_relations')->__('')
        ));
        $fieldset->addField('import_file', 'file', array(
            'label'     =>Mage::helper('easylife_relations')->__('File to import'),
            'name'      =>'import_file',
            'after_element_html' => Mage::helper('easylife_relations/adminhtml')->getTooltipHtml(
                Mage::helper('easylife_relations')->__('File to import'),
                Mage::helper('easylife_relations')->__('Same rules apply as for the "Related products identifiers". The difference is that the values are read from a csv file.'))
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        if ($data = Mage::getSingleton('adminhtml/session')->getRelationImportData(true)){
            $form->setValues($data);
        }
        return parent::_prepareForm();
    }
}