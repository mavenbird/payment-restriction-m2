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

use Magento\Paypal\Model\Config;

class PaypalConfig
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $checkoutSession;

    /**
     * @var \Mavenbird\Payrestriction\Model\Restrict
     */
    private $restrict;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    private $paymentHelper;

    /**
     * @var string
     */
    private $methodCode;

    /**
     * @var array
     */
    private $paypalExpressMethods = [
        Config::METHOD_WPS_EXPRESS,
        Config::METHOD_WPP_EXPRESS,
        Config::METHOD_WPP_PE_EXPRESS,
        Config::METHOD_EXPRESS,
    ];

    /**
     * @var \Magento\Framework\App\ProductMetadata
     */
    private $productMetadata;

    /**
     * Construct
     *
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Mavenbird\Payrestriction\Model\Restrict $restrict
     * @param \Magento\Payment\Helper\Data $paymentHelper
     * @param \Magento\Framework\App\ProductMetadata $productMetadata
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Mavenbird\Payrestriction\Model\Restrict $restrict,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Framework\App\ProductMetadata $productMetadata
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->restrict = $restrict;
        $this->paymentHelper = $paymentHelper;
        $this->productMetadata = $productMetadata;
    }

    /**
     * AfterIsMethodAvailable
     *
     * @param Config $subject
     * @param [type] $result
     * @param [type] $paymentCode
     * @return void
     */
    public function afterIsMethodAvailable(
        Config $subject,
        $result,
        $paymentCode = null
    ) {
        if ($this->productMetadata->getVersion() <= '2.1.11') {
            $paymentCode = $this->methodCode;
            $this->methodCode = null;
        }

        if ($paymentCode && in_array($paymentCode, $this->paypalExpressMethods) && $result) {
            $paymentCode = Config::METHOD_EXPRESS;
            $quote = $this->checkoutSession->getQuote();

            if (!$quote) {
                return $this;
            }

            $paypalPaymentMethodInstance = $this->paymentHelper->getMethodInstance($paymentCode);

            if (!$this->restrict->restrictMethods([$paymentCode => $paypalPaymentMethodInstance], $quote)) {
                return false;
            }
        }

        return $result;
    }

    /**
     * BeforeIsMethodAvailable
     *
     * @param Config $subject
     * @param [type] $paymentCode
     * @return void
     */
    public function beforeIsMethodAvailable(Config $subject, $paymentCode = null)
    {
        if ($this->productMetadata->getVersion() <= '2.1.11') {
            $this->methodCode = $paymentCode;
        }

        return [$paymentCode];
    }
}
