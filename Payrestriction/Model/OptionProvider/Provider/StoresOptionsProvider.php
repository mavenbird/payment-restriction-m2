<?php
/**
 * @author Mavenbird Team
 * @copyright Copyright (c) 2020 Mavenbird (https://www.mavenbird.com)
 * @package Mavenbird_Payrestriction
 */


namespace Mavenbird\Payrestriction\Model\OptionProvider\Provider;

/**
 * OptionsProvider
 */
class StoresOptionsProvider implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $store;

    /**
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(\Magento\Store\Model\System\Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->store->getStoreValuesForForm(false, true);
    }
}
