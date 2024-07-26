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

namespace Mavenbird\Payrestriction\Ui\Component\Listing\Column;

class Store implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Option
     *
     * @var [type]
     */
    protected $options;

    /**
     * Stores
     *
     * @var [type]
     */
    protected $store;

    /**
     * Construct
     *
     * @param \Magento\Store\Model\ResourceModel\Store\Collection $store
     */
    public function __construct(
        \Magento\Store\Model\ResourceModel\Store\Collection $store
    ) {
        $this->store = $store;
    }

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = $this->store->toOptionArray();
            $this->options[] = [
                'value' => 'all',
                'label' => __('Restricts In All')
            ];
        }

        return $this->options;
    }
}
