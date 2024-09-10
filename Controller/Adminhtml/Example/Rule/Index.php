<?php

namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule;

class Index extends \Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_addBreadcrumb(__('Paymentt Restriction'), __('Payment Restriction'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Payment Restriction'));
        $this->_view->renderLayout('root');
    }
}
