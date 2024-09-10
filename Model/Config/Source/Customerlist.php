<?php
/**
 * @category Mavenbird PaymentRestriction
 * @package Mavenbird_PaymentRestriction
 * @copyright Copyright (c) 2017 Mavenbird
 * @author Mavenbird Team <support@mavenbird.com>
 */
namespace Mavenbird\PaymentRestriction\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Helper\Category;

/**
 * Return customer List for Rule
 */
class Customerlist implements ArrayInterface
{
    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\Collection
     */
    protected $_groupcollection;
    /**
     * @param \Magento\Customer\Model\ResourceModel\Group\Collection
     */

     /**
      * Construct function
      *
      * @param \Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection
      */
    public function __construct(\Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection)
    {
        $this->_groupcollection=$groupCollection;
    }
    
    /**
     * Prepare Option Array
     *
     * @return Array
     */
    public function toOptionArray()
    {
        $groupOptions = $this->_groupcollection->toOptionArray();
        foreach ($groupOptions as $group) {
            $ret[] = [
                'value' => $group['value'],
                'label' => $group['label']
            ];
        }
        return $ret;
    }
}
