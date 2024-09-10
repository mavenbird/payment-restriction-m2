<?php

namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Mavenbird\PaymentRestriction\Model\RuleFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\DataObject;

class Save extends \Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule
{
    /**
     * @var Date
     */
    protected $dateFilter;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor function
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param Date $dateFilter
     * @param RuleFactory $ruleFactory
     * @param LoggerInterface $logger
     * @param AuthorizationInterface $auth
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        Date $dateFilter,
        RuleFactory $ruleFactory,
        LoggerInterface $logger,
        AuthorizationInterface $auth
    ) {
        $this->dateFilter = $dateFilter;
        $this->ruleFactory = $ruleFactory;
        $this->logger = $logger;
        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter, $ruleFactory, $logger, $auth);
    }

    /**
     * Rule save action
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('mavenbird_paymentrestriction/*/');
            return;
        }

        try {
            /** @var $model \Mavenbird\PaymentRestriction\Model\Rule */
            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_mavenbird_paymentrestriction_prepare_save',
                ['request' => $this->getRequest()]
            );

            $data = $this->getRequest()->getPostValue();

            $data['pmethod'] = !empty($data['pmethod']) ? implode(',', $data['pmethod']) : '';
            $data['website'] = !empty($data['website']) ? implode(',', $data['website']) : '';
            $data['cgid'] = !empty($data['cgid']) ? implode(',', $data['cgid']) : '';

            // Apply date filtering
            if (!empty($data['from_date'])) {
                $data['from_date'] = $this->dateFilter->filter($data['from_date']);
            }
            if (!empty($data['to_date'])) {
                $data['to_date'] = $this->dateFilter->filter($data['to_date']);
            }

            $id = $this->getRequest()->getParam('rule_id');
            if ($id) {
                $model->load($id);
            }

            $validateResult = $model->validateData(new DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                $this->_redirect('mavenbird_paymentrestriction/*/edit', ['id' => $model->getId()]);
                return;
            }

            $data = $this->prepareData($data);
            $model->loadPost($data);

            $this->_session->setPageData($model->getData());

            $model->save();
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            $this->_session->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('mavenbird_paymentrestriction/*/edit', ['id' => $model->getId()]);
                return;
            }
            $this->_redirect('mavenbird_paymentrestriction/*/');
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                $this->_redirect('mavenbird_paymentrestriction/*/edit', ['id' => $id]);
            } else {
                $this->_redirect('mavenbird_paymentrestriction/*/new');
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect('mavenbird_paymentrestriction/*/edit', ['id' => $this->getRequest()->getParam('rule_id')]);
            return;
        }
    }

    /**
     * Prepares specific data
     *
     * @param array $data
     * @return array
     */
    protected function prepareData($data)
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }
}
