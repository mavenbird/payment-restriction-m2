<?php
/**
 * @category Mavenbird PaymentRestriction
 * @package Mavenbird_PaymentRestriction
 * @copyright Copyright (c) 2017 Mavenbird
 * @author Mavenbird Team <support@mavenbird.com>
 */
namespace Mavenbird\PaymentRestriction\Model\Config\Source;

use Magento\Payment\Api\PaymentMethodListInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * return all payment method
 */
class PaymentMethod implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var PaymentMethodListInterface
     */
    private $paymentMethodList;
    
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
     
    /**
     * Construct function
     *
     * @param PaymentMethodListInterface $paymentMethodList
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        PaymentMethodListInterface $paymentMethodList,
        StoreManagerInterface $storeManager
    ) {
        $this->paymentMethodList = $paymentMethodList;
        $this->storeManager = $storeManager;
    }
  
    /**
     * ToOptionArray function
     *
     * @return void
     */
    public function toOptionArray()
    {
        $storeid = $this->storeManager->getStore()->getId();
        $payments = $this->paymentMethodList->getActiveList($storeid);
        $methods = [];
       // $methods["0"] = array('label' => "Default Apply",'value' => "0");
        foreach ($payments as $payment) {
            $methods[] = [
            'label' => $payment->getTitle(),
            'value' => $payment->getCode()
            ];
        }
        return $methods;
    }
}
