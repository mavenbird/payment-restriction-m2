<?php
/**
 * @category Mavenbird PaymentRestriction
 * @package Mavenbird_PaymentRestriction
 * @copyright Copyright (c) 2017 Mavenbird
 * @author Mavenbird Team <support@mavenbird.com>
 */
namespace Mavenbird\PaymentRestriction\Observer;

use Magento\Framework\Event\ObserverInterface;
use Mavenbird\PaymentRestriction\Block\Cart\RuleExample;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Exception;

/**
 * check payment method for current user
 */
class PaymentActive implements ObserverInterface
{

    /**
     * HelperData variable
     *
     * @var varchar
     */
    protected $helperData;

    /**
     * @var TimezoneInterface $localeDate
     */
    protected $localeDate;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    private $dateTimeFactory;

    /**
     * @var Mavenbird\PaymentRestriction\Block\Cart\RuleExample
     */
    protected $ruleExample;

     /**
      * @var RuleCollectionFactory
      */
    protected $ruleCollectionFactory;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Magento\SalesRule\Model\RuleFactory
     */
    protected $salesRuleFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    
    /**
     * csmp Model
     *
     * @var \Mavenbird\PaymentRestriction\Model\Rule
     */
    protected $_ruleModel;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $_paymentHelper;
    
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * Shiiping Methods
     *
     * @var [type]
     */
    protected $shippingAllmethods;

    /**
     * Store Managers
     *
     * @var [type]
     */
    protected $storeManager;
    
   /**
    * @param \Mavenbird\PaymentRestriction\Helper\Data $helperData
    * @param TimezoneInterface $localeDate
    * @param \Magento\Store\Model\StoreManagerInterface $storeManager
    * @param \Magento\SalesRule\Model\RuleFactory $salesRuleFactory
    * @param \Magento\Framework\Serialize\Serializer\Json $serializer
    * @param \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory
    * @param RuleCollectionFactory $ruleCollectionFactory
    * @param \Magento\Payment\Helper\Data $paymentHelper
    * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
    * @param \Magento\Shipping\Model\Config\Source\Allmethods $shippingAllmethods
    * @param \Magento\Customer\Model\Session $customerSession
    * @param \Mavenbird\PaymentRestriction\Model\Rule $ruleModel
    * @param \Mavenbird\PaymentRestriction\Block\Cart\RuleExample $ruleExample
    */
    public function __construct(
        \Mavenbird\PaymentRestriction\Helper\Data $helperData,
        TimezoneInterface $localeDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\SalesRule\Model\RuleFactory $salesRuleFactory,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory,
        RuleCollectionFactory $ruleCollectionFactory,
        \Magento\Payment\Helper\Data $paymentHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Shipping\Model\Config\Source\Allmethods $shippingAllmethods,
        \Magento\Customer\Model\Session $customerSession,
        \Mavenbird\PaymentRestriction\Model\Rule $ruleModel,
        \Mavenbird\PaymentRestriction\Block\Cart\RuleExample $ruleExample
    ) {
        $this->helperData = $helperData;
        $this->localeDate = $localeDate;
        $this->_paymentHelper = $paymentHelper;
        $this->customerSession = $customerSession;
        $this->dateTimeFactory = $dateTimeFactory;
        $this->shippingAllmethods = $shippingAllmethods;
        $this->salesRuleFactory = $salesRuleFactory;
        $this->serializer = $serializer;
        $this->date = $date;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->_ruleModel=$ruleModel;
        $this->storeManager = $storeManager;
        $this->ruleExample = $ruleExample;
    }

    /**
     * Execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->helperData->getConfigValue('enable')) {
       
            $allPaymentMethods = $this->_paymentHelper->getPaymentMethods();
            $allPaymentMethodsArray = $this->_paymentHelper->getPaymentMethodList();
            $event           = $observer->getEvent();
            $method          = $event->getMethodInstance();
            $result          = $event->getResult();
            $currencyCode    = $this->storeManager->getStore()->getCurrentCurrencyCode();

            $groupId = $this->customerSession->getCustomerGroupId();
            $collection=$this->_ruleModel->getCollection();
             /**Customer login or not */
            if ($this->customerSession->isLoggedIn()) {
                
                $customerGroupId = $this->customerSession->getCustomer()->getGroupId();
                
            } else {
                 $customerGroupId = 0;
            }
         
            $validateIds = $this->ruleExample->getMessage();
      
            if (count($validateIds) > 0) {
                $webId=$this->storeManager->getStore()->getId();
                $storeView=$webId;
              
                $collection=$this->_ruleModel->getCollection();
                foreach ($collection as $row) {
                    $customer_groups[]= $row['cgid'];
                }
               
                foreach ($collection as $time) {
                    $time= $time['from_date'];
                }
                $database= $time;

                $today = $this->date->gmtDate('Y-m-d');
          
                if ($today >= $database) {
                    $database;
                } else {
                    $database='0';
                }
                
                $collection=$this->_ruleModel->getCollection()
    
                    ->addFieldToFilter("website", ['finset' => $storeView])
                    ->addFieldToFilter("cgid", ['finset' => $customerGroupId])
                    ->addFieldToFilter("is_active", 1)
                    ->addFieldToFilter("sort_order", 0)
                    ->addFieldToFilter('to_date', ['gteq' =>  $today]);
                $set=0;
                $check=0;
                if (count($collection) > 0) {
                    $this->getPayment($collection, $validateIds, $observer);
                }
               
            } else {
                return false;
            }
        }
    }
    
    /**
     * GetPayment
     *
     * @param array $collection
     * @param array $validateIds
     * @param array $observer
     * @return void
     */
    public function getPayment($collection, $validateIds, $observer)
    {
        foreach ($collection->getData() as $item) {
            $restrictedPaymentList [] = $item['pmethod'];
        }
        foreach ($restrictedPaymentList as $paymentMethodCode) {
            $restrictedPaymentList = explode(",",$paymentMethodCode) ?? '';
        }
        foreach ($restrictedPaymentList as $paymentMethodCode) {
            if ($observer->getEvent()->getMethodInstance()->getCode() == $paymentMethodCode) {
                $checkResult = $observer->getEvent()->getResult();
                $checkResult->setData('is_available', false);
            }
        }
    }
}
