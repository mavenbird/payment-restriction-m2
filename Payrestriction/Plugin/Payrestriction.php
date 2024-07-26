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

namespace Mavenbird\Payrestriction\Plugin;

use Magento\Payment\Helper\Data;

class Payrestriction
{
    /**
     * @var \Mavenbird\Payrestriction\Model\Restrict
     */
    private $restrict;

    /**
     * Construct
     *
     * @param \Mavenbird\Payrestriction\Model\Restrict $restrict
     */
    public function __construct(
        \Mavenbird\Payrestriction\Model\Restrict $restrict
    ) {
        $this->restrict = $restrict;
    }

    /**
     * AroundGetStoreMethods
     *
     * @param Data $subject
     * @param \Closure $proceed
     * @param [type] $store
     * @param [type] $quote
     * @return void
     */
    public function aroundGetStoreMethods(Data $subject, \Closure $proceed, $store = null, $quote = null)
    {
        $methods = $proceed($store, $quote);

        return $this->restrict->restrictMethods($methods, $quote);
    }
}
