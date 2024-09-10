<?php

namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule;

class NewAction extends \Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule
{
    /**
     * New action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
