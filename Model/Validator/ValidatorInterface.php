<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\Validator;

/**
 * Interface ModifierInterface
 */
interface ValidatorInterface
{
    /**
     * @param \Magento\Rule\Model\AbstractModel $rule
     * @param \Magento\Quote\Model\Quote\Item[] $items
     *
     * @return boolean
     */
    public function validate($rule, $items);
}
