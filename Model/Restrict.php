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

namespace Mavenbird\Payrestriction\Model;

class Restrict
{
    /**
     * @var null
     */
    protected $allRules = null;

    /**
     * @var ResourceModel\Rule\Collection
     */
    protected $ruleCollection;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $appState;

    /**
     * @var \Mavenbird\Payrestriction\Model\Validator\SalesRule
     */
    private $salesRuleValidator;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetaData;

    /**
     * Construct
     *
     * @param \Mavenbird\Payrestriction\Model\ResourceModel\Rule\Collection $ruleCollection
     * @param \Magento\Framework\App\State $appState
     * @param \Mavenbird\Payrestriction\Model\Validator\SalesRule $salesRuleValidator
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetaData
     */
    public function __construct(
        \Mavenbird\Payrestriction\Model\ResourceModel\Rule\Collection $ruleCollection,
        \Magento\Framework\App\State $appState,
        \Mavenbird\Payrestriction\Model\Validator\SalesRule $salesRuleValidator,
        \Magento\Framework\App\ProductMetadataInterface $productMetaData
    ) {
        $this->ruleCollection = $ruleCollection;
        $this->appState = $appState;
        $this->salesRuleValidator = $salesRuleValidator;
        $this->productMetaData = $productMetaData;
    }

    /**
     * RestrictMethods
     *
     * @param [type] $paymentMethods
     * @param [type] $quote
     * @return void
     */
    public function restrictMethods($paymentMethods, $quote = null)
    {
        if (!$quote) {
            return $paymentMethods;
        }

        if ($this->productMetaData->getVersion() <= '2.2.1') {
            $quote->collectTotals();
        }

        /** @var \Magento\Quote\Model\Quote\Address $address */
        if ($quote->isVirtual()) {
            $address = $quote->getBillingAddress();
        } else {
            $address = $quote->getShippingAddress();
        }

        $items = $quote->getAllItems();
        $address->setItemsToValidateRestrictions($items);
        $hasBackOrders = false;
        $hasNoBackOrders = false;

        /** @var \Magento\Quote\Model\Quote\Item $item */
        foreach ($items as $item) {
            if ($item->getBackorders() > 0) {
                $hasBackOrders = true;
            } else {
                $hasNoBackOrders = true;
            }

            if ($hasBackOrders && $hasNoBackOrders) {
                break;
            }
        }
        $paymentMethods = $this->validateMethods($paymentMethods, $address, $items);

        return $paymentMethods;
    }

    /**
     * ValidateMethods
     *
     * @param [type] $paymentMethods
     * @param [type] $address
     * @param [type] $items
     * @return void
     */
    private function validateMethods($paymentMethods, $address, $items)
    {
        foreach ($paymentMethods as $key => $method) {
            /** @var \Mavenbird\Payrestriction\Model\Rule $rule */
            foreach ($this->getRules($address) as $rule) {
                if ($rule->validate($method)
                    && $this->salesRuleValidator->validate($rule, $items)
                    && $rule->validate($address, $items)
                ) {
                    unset($paymentMethods[$key]);
                }
            }
        }

        return $paymentMethods;
    }

    /**
     * GetRules
     *
     * @param [type] $address
     * @return void
     */
    private function getRules($address)
    {
        if ($this->allRules === null) {
            $this->allRules = $this->ruleCollection->addAddressFilter($address);

            if ($this->appState->getAreaCode() == \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE) {
                $this->allRules->addFieldToFilter('for_admin', 1);
            }

            $this->allRules->load();

            /** @var \Mavenbird\Payrestriction\Model\Rule $rule */
            foreach ($this->allRules as $rule) {
                $rule->afterLoad();
            }
        }

        return $this->allRules;
    }
}
