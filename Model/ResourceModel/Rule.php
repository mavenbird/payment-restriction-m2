<?php

namespace Mavenbird\PaymentRestriction\Model\ResourceModel;

class Rule extends \Magento\Rule\Model\ResourceModel\AbstractResource
{

    /**
     * Initialize main table and table id field
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mavenbird_paymentrestriction', 'rule_id');
    }
}
