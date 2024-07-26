<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */

namespace Mavenbird\Payrestriction\Model\Validator;

/**
 * Class SalesRule
 */
class SalesRule implements ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate($rule, $items)
    {
        $providedCouponCodes = $this->getCouponCodes($items);
        $providedRuleIds = $this->getRuleIds($items);

        return $this->isApply($rule, $providedCouponCodes, $providedRuleIds) ?
            !$this->isApply($rule, $providedCouponCodes, $providedRuleIds, false) : false;
    }

    /**
     * @param \Magento\Rule\Model\AbstractModel $rule
     * @param array $providedCouponCodes
     * @param array $providedRuleIds
     * @param bool $isDisable
     *
     * @return bool
     */
    private function isApply($rule, $providedCouponCodes, $providedRuleIds, $isDisable = true)
    {
        if ($isDisable) {
            $coupons = $rule->getCouponDisable();
            $discountIds = $rule->getDiscountIdDisable();
        } else {
            $coupons = $rule->getCoupon();
            $discountIds = $rule->getDiscountId();
        }

        if (!$coupons && !$discountIds) {
            return $isDisable;
        }

        $activeCoupons = $coupons ? array_intersect(explode(',', strtolower($coupons)), $providedCouponCodes) : [];
        $activeRules = $discountIds ? array_intersect(explode(',', $discountIds), $providedRuleIds) : [];

        return !($activeCoupons || $activeRules);
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item[] $items
     *
     * @return array
     */
    private function getRuleIds($items)
    {
        if (empty($items)) {
            return [];
        }

        /** @var \Magento\Quote\Model\Quote\Item $firstItem */
        $firstItem = current($items);
        $rules = $firstItem->getQuote()->getAppliedRuleIds();

        if (is_null($rules) || trim($rules) === '') {
            return [];
        }

        return explode(',', trim($rules));
    }

    /**
     * @param \Magento\Quote\Model\Quote\Item[] $items
     *
     * @return array
     */
    private function getCouponCodes($items)
    {
        if (!count($items)) {
            return [];
        }

        /** @var \Magento\Quote\Model\Quote\Item $firstItem */
        $firstItem = current($items);
        $couponCode = $firstItem->getQuote()->getCouponCode();

        if (is_null($couponCode) || trim($couponCode) === '') {
            return [];
        }

        $codes = trim(strtolower($couponCode));

        return array_map('trim', explode(",", $codes));
    }
}
