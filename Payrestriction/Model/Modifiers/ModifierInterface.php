<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\Modifiers;

/**
 * Interface ModifierInterface
 */
interface ModifierInterface
{
    /**
     * Modify Object
     * @param \Magento\Framework\DataObject $object
     * @return \Magento\Framework\DataObject
     */
    public function modify($object);
}
