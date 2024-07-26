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

namespace Mavenbird\Payrestriction\Model\ResourceModel\Rule\Grid;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Mavenbird\Payrestriction\Model\ResourceModel\Rule\Collection as RuleCollection;

class Collection extends RuleCollection implements SearchResultInterface
{
    /**
     * @var AggregationInterface
     */
    protected $aggregations;

    /**
     * Construct
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param [type] $mainTable
     * @param [type] $eventPrefix
     * @param [type] $eventObject
     * @param [type] $resourceModel
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $coreDate
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param [type] $model
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        \Magento\Framework\Stdlib\DateTime\DateTime $coreDate,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\State $state,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        $model = \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $coreDate,
            $localeDate,
            $storeManager,
            $state,
            $customerRepositoryInterface
        );
        $this->_eventObject = $eventObject;
        $this->_eventPrefix = $eventPrefix;
        $this->setMainTable($mainTable);
        $this->_init($model, $resourceModel);
    }

    /**
     * GetAggregations
     *
     * @return void
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * SetAggregations
     *
     * @param [type] $aggregations
     * @return void
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * GetAllIdsSelect
     *
     * @param [type] $limit
     * @param [type] $offset
     * @return void
     */
    protected function _getAllIdsSelect($limit = null, $offset = null)
    {
        $idsSelect = clone $this->getSelect();
        $idsSelect->reset(\Magento\Framework\DB\Select::ORDER);
        $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
        $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
        $idsSelect->reset(\Magento\Framework\DB\Select::COLUMNS);
        $idsSelect->columns($this->getResource()->getIdFieldName(), 'main_table');
        $idsSelect->limit($limit, $offset);
        return $idsSelect;
    }

    /**
     * GetAllIds
     *
     * @param [type] $limit
     * @param [type] $offset
     * @return void
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol($this->_getAllIdsSelect($limit, $offset), $this->_bindParams);
    }

    /**
     * GetSearchCriteria
     *
     * @return void
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * SetSearchCriteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return void
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * GetTotalCount
     *
     * @return void
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * SetTotalCount
     *
     * @param [type] $totalCount
     * @return void
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * SetItems
     *
     * @param array|null $items
     * @return void
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * AfterLoad
     *
     * @return void
     */
    protected function _afterLoad()
    {
        foreach ($this as $item) {
            $item->setMethods(explode(',', $item->getMethods()));
            if ($item->getStores()) {
                $item->setStores(explode(',', $item->getStores()));
            } else {
                $item->setStores(['all']);
            }
            if ($item->getCustGroups()) {
                $item->setCustGroups(explode(',', $item->getCustGroups()));
            } else {
                $item->setCustGroups(['all']);
            }
        }
    }
}
