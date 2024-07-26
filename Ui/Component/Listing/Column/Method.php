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

class Method implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Option
     *
     * @var [type]
     */
    protected $options;

    /**
     * PoolOptionProviders
     *
     * @var [type]
     */
    protected $poolOptionProvider;

    /**
     * Construct
     *
     * @param \Mavenbird\Payrestriction\Model\OptionProvider\Pool $poolOptionProvider
     */
    public function __construct(
        \Mavenbird\Payrestriction\Model\OptionProvider\Pool $poolOptionProvider
    ) {
        $this->poolOptionProvider = $poolOptionProvider;
    }

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = $this->poolOptionProvider->getOptionsByProviderCode('payment_method');
        }

        return $this->options;
    }
}
