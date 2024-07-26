<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\Rule\Condition;

/**
 * Class ConditionBuilder
 */
class ConditionBuilder
{
    const MAVENBIRD_COMMON_RULES_PATH_TO_CONDITIONS = 'Mavenbird\Payrestriction\Model\Rule\Condition\\';
    const MAVENBIRD_SHIP_RESTRICTION_PATH_TO_CONDITIONS = 'Mavenbird\Shiprestriction\Model\Rule\Condition\\';
    const MAVENBIRD_SHIP_RULES_PATH_TO_CONDITIONS = 'Mavenbird\Shiprules\Model\Rule\Condition\\';
    const MAGENTO_SALES_RULE_PATH_TO_CONDITIONS = 'Magento\SalesRule\Model\Rule\Condition\\';

    /**
     * @param $conditions
     * @return array
     */
    public function getChangedNewChildSelectOptions($conditions)
    {
        foreach ($conditions as $key => $value) {
            if (isset($value['value'])
                && $value['value'] == self::MAGENTO_SALES_RULE_PATH_TO_CONDITIONS . 'Product\Combine'
            ) {
                $conditions[$key]['value'] = self::MAVENBIRD_COMMON_RULES_PATH_TO_CONDITIONS . 'Product\Combine';
            }
        }

        return $conditions;
    }
}
