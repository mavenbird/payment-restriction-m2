<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */

namespace Mavenbird\Payrestriction\Plugin;

/**
 * Class ProductAttributes
 */
class ProductAttributes
{
    /**
     * @var null
     */
    private $resourceTable;

    public function __construct(\Mavenbird\Payrestriction\Model\ResourceModel\Rule $resourceTable)
    {
        $this->resourceTable = $resourceTable;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Config $subject
     * @param array $attributesTransfer
     * @return array|mixed
     */
    public function afterGetProductAttributes(\Magento\Quote\Model\Quote\Config $subject, $attributesTransfer)
    {
        $attributes = $this->resourceTable->getAttributes();

        foreach ($attributes as $code) {
            $attributesTransfer[] = $code;
        }

        return $attributesTransfer;
    }
}
