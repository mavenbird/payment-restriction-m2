<?php

namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example;

abstract class Rule extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    protected $dateFilter;

    /**
     * @var \Mavenbird\PaymentRestriction\Model\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

     /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * Construct function
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Mavenbird\PaymentRestriction\Model\RuleFactory $ruleFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\AuthorizationInterface $auth
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Mavenbird\PaymentRestriction\Model\RuleFactory $ruleFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\AuthorizationInterface $authorization
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->fileFactory = $fileFactory;
        $this->dateFilter = $dateFilter;
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
        $this->authorization = $authorization;
    }

    /**
     * Initiate rule
     *
     * @return void
     */
    protected function _initRule()
    {
        $rule = $this->ruleFactory->create();
        $this->coreRegistry->register(
            'current_rule',
            'rule_massaction',
            $rule
        );
        $id = (int)$this->getRequest()->getParam('id');

        if (!$id && $this->getRequest()->getParam('rule_id')) {
            $id = (int)$this->getRequest()->getParam('rule_id');
        }

        if ($id) {
            $this->coreRegistry->registry('rule_massaction')->load($id);
        }
        
        if ($id) {
            $this->coreRegistry->registry('current_rule')->load($id);
        }
    }

    /**
     * Initiate action
     *
     * @return Rule
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Mavenbird_PaymentRestriction::mavenbird_paymentrestriction')
            ->_addBreadcrumb(__('Payment Restriction'), __('Payment Restriction'));
        return $this;
    }

    /**
     * Returns result of current user permission check on resource and privilege
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed('Mavenbird_PaymentRestriction::paymentrestriction');
    }
}
