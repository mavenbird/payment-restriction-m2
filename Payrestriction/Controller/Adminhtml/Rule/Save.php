<?php
/**
 * Mavenbird Technologies Private Limited
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://mavenbird.com/Mavenbird-Module-License.txt
 *
 * =================================================================
 *
 * @category   Mavenbird
 * @package    Mavenbird_Payrestriction
 * @author     Mavenbird Team
 * @copyright  Copyright (c) 2018-2024 Mavenbird Technologies Private Limited ( http://mavenbird.com )
 * @license    http://mavenbird.com/Mavenbird-Module-License.txt
 */

namespace Mavenbird\Payrestriction\Controller\Adminhtml\Rule;

class Save extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule
{
    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('rule_id');
        $model = $this->_objectManager->create('Mavenbird\Payrestriction\Model\Rule');
        $data = $this->getRequest()->getPostValue();
        if ($data) {
    
            if (isset($data['rule']['conditions'])) {
                $data['conditions'] = $data['rule']['conditions'];
            }
            unset($data['rule']);
            $model->setData($data);  // common fields
            $model->loadPost($data); // rules

            $model->setId($id);
            $session = $this->_objectManager->get('Magento\Backend\Model\Session');
            try {
                $this->prepareForSave($model);

                $model->save();

                $session->setPageData(false);

                $this->messageManager->addSuccess(__('Payment Restriction has been successfully saved'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId()]);
                } else {
                    $this->_redirect('*/*');
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $session->setPageData($model->getData());
                $this->_redirect('*/*/edit', ['id' => $id]);
            }
            return;
        }

        $this->messageManager->addError(__('Unable to find a record to save'));
        $this->_redirect('*/*');
    }

    /**
     * PrepareForSave
     *
     * @param [type] $model
     * @return void
     */
    public function prepareForSave($model)
    {
        foreach (parent::FIELDS as $field) {
            // convert data from array to string
            $val = $model->getData($field);
            $model->setData($field, '');

            if (is_array($val)) {
                $model->setData($field, implode(',', $val));
            }
        }

        return true;
    }
}
