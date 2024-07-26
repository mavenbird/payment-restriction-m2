<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\Modifiers;

/**
 * Address Modifier
 */
class Address implements \Mavenbird\Payrestriction\Model\Modifiers\ModifierInterface
{
    /**
     * @param \Magento\Framework\DataObject $object
     * @param null $rateAddress
     * @return \Magento\Framework\DataObject
     */
    public function modify($object, $rateAddress = null)
    {
        if ($rateAddress) {
            $object->setData($object->getData() + $rateAddress->getData());
        }

        return $object;
    }
}
