<?php
/**
 * @category Mavenbird PaymentRestriction
 * @package Mavenbird_PaymentRestriction
 * @copyright Copyright (c) 2017 Mavenbird
 * @author Mavenbird Team <support@mavenbird.com>
 */
namespace Mavenbird\PaymentRestriction\Model\Config\Source;

use \Magento\Store\Model\StoreRepository;

/**
 * return all Website
 */
class Website implements \Magento\Framework\Option\ArrayInterface
{
     /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var Rate
     */
    protected $_storeRepository;
      
    /**
     * StoreManagerInterface
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param StoreRepository $storeRepository
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        StoreRepository $storeRepository
    ) {
        $this->_storeManager = $storeManager;
        $this->_storeRepository = $storeRepository;
    }

     /**
      * Return array of options as value-label pairs
      *
      * @return array
      */
    public function toOptionArray()
    {
        $storeManagerDataList = $this->_storeManager->getStores();
        $options = [];
     
        foreach ($storeManagerDataList as $key => $value) {
            $options[] = ['label' => $value['name'], 'value' => $key];
        }
        return $options;
    }
}
