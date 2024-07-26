<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\Modifiers;

/**
 * Subtotal Modifier
 */
class Subtotal implements ModifierInterface
{
    /**
     * @var string
     */
    protected $sectionConfig = '';

    /**
     * @var \Mavenbird\Payrestriction\Model\Config
     */
    private $config;

    /**
     * Subtotal constructor.
     * @param \Mavenbird\Payrestriction\Model\Config $config
     */
    public function __construct(\Mavenbird\Payrestriction\Model\Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address $object
     * @return \Magento\Quote\Model\Quote\Address
     */
    public function modify($object)
    {
        /** @var \Magento\Quote\Model\Quote\Address $tempObject */
        $tempObject = clone $object;

        $subtotal = $tempObject->getSubtotal();
        $baseSubtotal = $tempObject->getBaseSubtotal();

        if ($this->config->getTaxIncludeConfig($this->getSectionConfig())) {
            $subtotal += $tempObject->getTaxAmount();
            $baseSubtotal += $tempObject->getBaseTaxAmount();
        }

        if ($this->config->getUseSubtotalConfig($this->getSectionConfig())) {
            $subtotal += $tempObject->getDiscountAmount();
            $baseSubtotal += $tempObject->getBaseDiscountAmount();
        }

        $tempObject->setSubtotal($subtotal);
        $tempObject->setBaseSubtotal($baseSubtotal);
        $tempObject->setPackageValueWithDiscount($baseSubtotal);

        return $tempObject;
    }

    /**
     * @param $sectionConfig
     * @return $this
     */
    public function setSectionConfig($sectionConfig)
    {
        $this->sectionConfig = $sectionConfig;

        return $this;
    }

    /**
     * @return string
     */
    public function getSectionConfig()
    {
        return $this->sectionConfig;
    }
}
