<?php
/**
 * @category Mavenbird PaymentRestriction
 * @package Mavenbird_PaymentRestriction
 * @copyright Copyright (c) 2017 Mavenbird
 * @author Mavenbird Team <support@mavenbird.com>
 */

namespace Mavenbird\PaymentRestriction\Helper;

/**
 * Data class for helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public const XML_PATH_MYMODULE = 'mavenbird_paymentrestriction/general/enable';
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_groupcollection;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\website
     */
    protected $_website;
    
    /**
     * @var \Mavenbird\CSPM\Model\Config\Source\PaymentMethod
     */
    protected $_paymentMethod;

    /**
     * Store Managers
     *
     * @var [type]
     */
    protected $storeManager;

   /**
    * Construct
    *
    * @param \Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection
    * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param \Magento\Store\Model\StoreManagerInterface $storeManager
    * @param \Mavenbird\PaymentRestriction\Model\Config\Source\PaymentMethod $paymentMethod
    * @param \Mavenbird\PaymentRestriction\Model\Config\Source\Website $website
    */
    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mavenbird\PaymentRestriction\Model\Config\Source\PaymentMethod $paymentMethod,
        \Mavenbird\PaymentRestriction\Model\Config\Source\Website $website
    ) {
        $this->_paymentMethod=$paymentMethod;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_groupcollection=$groupCollection;
        $this->_website=$website;
    }

    /**
     * GetConfigValue function
     *
     * @param varchar $field
     * @param varchar $storeId
     * @return void
     */
    public function getConfigValue()
    {
        return $this->scopeConfig->getValue(
            "mavenbird_paymentrestriction/general/enable",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getStoreId()
        );
    }
    
  /**
   * Prepare Option Array
   *
   * @return Array
   */
    public function getCgid()
    {
        $groupOptions = $this->_groupcollection->toOptionArray();
        foreach ($groupOptions as $group) {
            $ret[] = [
              'value' => $group['value'],
              'label' => $group['label']];
        }
        return $ret;
    }
    /**
     * Return all enable payment method
     */
    public function getPaymentMethod()
    {
        return $this->_paymentMethod->toOptionArray();
    }

    /**
     * Return all enable payment method
     */
    public function getWebsite()
    {
        return $this->_website->toOptionArray();
    }
}
