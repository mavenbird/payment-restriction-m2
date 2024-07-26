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

namespace Mavenbird\Payrestriction\Controller\Adminhtml\Rule;

use Magento\Backend\App\Action;
use Mavenbird\Payrestriction\Controller\Adminhtml\Rule;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

abstract class AbstractMassAction extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule
{

    /**
     * Filters
     *
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * CollectionFactory
     *
     * @var \Mavenbird\Payrestriction\Model\ResourceModel\Rule\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * LoggerInterface
     *
     * @var LoggerInterface
     */
    protected $loggerInterface;

    /**
     * Construct
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Mavenbird\Payrestriction\Model\ResourceModel\Rule\CollectionFactory $collectionFactory
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Mavenbird\Payrestriction\Model\ResourceModel\Rule\CollectionFactory $collectionFactory,
        PageFactory $resultPageFactory,
        LoggerInterface $loggerInterface
    ) {
        parent::__construct($context, $coreRegistry, $resultLayoutFactory, $resultPageFactory);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->loggerInterface = $loggerInterface;
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());

            $this->massAction($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            $this->loggerInterface->critical($e);
        }

        return $this->_redirect('*/*');
    }

    /**
     * MassAction
     *
     * @param \Mavenbird\Payrestriction\Model\ResourceModel\Rule\Collection $collection
     * @return void
     */
    abstract protected function massAction($collection);
}
