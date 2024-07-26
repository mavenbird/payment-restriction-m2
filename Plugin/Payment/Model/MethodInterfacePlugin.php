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

namespace Mavenbird\Payrestriction\Plugin\Payment\Model;

use Magento\Quote\Api\Data\CartInterface;

/**
 * Payment method is available validation
 */
class MethodInterfacePlugin
{
    /**
     * @var \Mavenbird\Payrestriction\Model\Restrict
     */
    private $restrict;

    /**
     * @var \Magento\Quote\Api\Data\CartInterface|null
     */
    private $quote;

    /**
     * Cyonstruct
     *
     * @param \Mavenbird\Payrestriction\Model\Restrict $restrict
     */
    public function __construct(\Mavenbird\Payrestriction\Model\Restrict $restrict)
    {
        $this->restrict = $restrict;
    }

    /**
     * Check whether payment method can be used
     *
     * @param \Magento\Payment\Model\MethodInterface $subject
     * @param bool $result
     * @param CartInterface|null $quote
     *
     * @return bool
     */
    public function afterIsAvailable(\Magento\Payment\Model\MethodInterface $subject, $result, $quote = null)
    {
        if ($quote === null) {
            $quote = $this->quote;
        }
        if ($result === false || $quote === null) {
            return $result;
        }

        $allowedMethods = $this->restrict->restrictMethods([$subject->getCode() => $subject], $quote);

        return isset($allowedMethods[$subject->getCode()]);
    }

    /**
     * BeforeIsAvailable
     *
     * @param \Magento\Payment\Model\MethodInterface $subject
     * @param [type] $quote
     * @return void
     */
    public function beforeIsAvailable(\Magento\Payment\Model\MethodInterface $subject, $quote = null)
    {
        $this->quote = $quote;
    }
}
