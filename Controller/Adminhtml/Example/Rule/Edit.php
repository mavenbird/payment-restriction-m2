<?php

namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule;

class Edit extends \Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule
{
    /**
     * Rule edit action
     *
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var \Mavenbird\PaymentRestriction\Model\Rule $model */
        $model = $this->ruleFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getRuleId()) {
                $this->messageManager->addErrorMessage(__('This rule no longer exists.'));
                $this->_redirect('mavenbird_paymentrestriction/*');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_session->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');

        $this->coreRegistry->register('current_rule', $model);

        $this->_initAction();
        $this->_view->getLayout()
            ->getBlock('example_rule_edit')
            ->setData('action', $this->getUrl('mavenbird_paymentrestriction/*/save'));

        $this->_addBreadcrumb($id ? __('Edit Rule') : __('New Rule'), $id ? __('Edit Rule') : __('New Rule'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(
            $model->getRuleId() ? $model->getName() : __('New Rule')
        );
        $this->_view->renderLayout();
    }
}
