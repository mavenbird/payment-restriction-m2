<?php

namespace Mavenbird\PaymentRestriction\Block\Adminhtml\Example\Rule\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Cms\Ui\Component\Listing\Column\Cms\Options;

class Main extends Generic implements TabInterface
{

    /**
     * @var \Mavenbird\CSPM\Helper\Data
     */
    protected $_helper;

    /**
     * Cms variable
     *
     * @var Options $cmsOpt
     */
    protected $_cmsOpt;

    /**
     * Construct function
     *
     * @param Context $context
     * @param Registry $registry
     * @param \Mavenbird\PaymentRestriction\Helper\Data $helper
     * @param Options $cmsOpt
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        \Mavenbird\PaymentRestriction\Helper\Data $helper,
        Options $cmsOpt,
        FormFactory $formFactory,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_cmsOpt               = $cmsOpt;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * GetTabLabel function
     *
     * @return void
     */
    public function getTabLabel()
    {
        return __('Rule Information');
    }

    /**
     * GetTabTitle function
     *
     * @return void
     */
    public function getTabTitle()
    {
        return __('Rule Information');
    }

    /**
     * CanShowTab function
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

   /**
    * IsHidden function
    *
    * @return boolean
    */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return Generic
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_rule');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Information')]);

        if ($model->getId()) {
            $fieldset->addField('rule_id', 'hidden', ['name' => 'rule_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Rule Name'), 'title' => __('Rule Name'), 'required' => true]
        );

        $cgidarray=$this->_helper->getCgid();
        $fieldset->addField(
            'cgid',
            'multiselect',
            ['name' => 'cgid', 'label' => __('Customer Gruop'), 'title' => __('Customer Name'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=> $cgidarray ]
        );
       
        $pmarray=$this->_helper->getPaymentMethod();
        $fieldset->addField(
            'pmethod',
            'multiselect',
            ['name' => 'pmethod', 'label' => __('Payment Method'), 'title' => __('Payment Method'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=>$pmarray ]
        );
        
        $gwarray=$this->_helper->getWebsite();
        $fieldset->addField(
            'website',
            'multiselect',
            [
                'name'  => 'website',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'style'=>'min-width:210px;',
                'values' => $gwarray
            ]
        );
        
        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Active'), '0' => __('Inactive')]
            ]
        );

        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $fieldset->addField('sort_order', 'text', ['name' => 'sort_order', 'label' => __('Priority')]);

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'from_date',
            'date',
            [
                'name' => 'from_date',
                'label' => __('From'),
                'title' => __('From'),
                'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'date_format' => $dateFormat
            ]
        );
        $fieldset->addField(
            'to_date',
            'date',
            [
                'name' => 'to_date',
                'label' => __('To'),
                'title' => __('To'),
                'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'date_format' => $dateFormat
            ]
        );

        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }

        $this->setForm($form);

        $this->_eventManager->dispatch('adminhtml_example_rule_edit_tab_main_prepare_form', ['form' => $form]);

        return parent::_prepareForm();
    }
}
