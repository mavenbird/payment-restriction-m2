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

use Mavenbird\Payrestriction\Model\Rule;

class Delete extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule
{
    /**
     * Rules
     *
     * @var [type]
     */
    protected $rule;

    /**
     * Construct
     *
     * @param Rule $rule
     * @return void
     */
    public function _construct(Rule $rule)
    {
        $this->rule = $rule;
        parent::_construct();
    }

    /**
     * Execute
     *
     * @return void
     */
    public function execute()
    {
        $id     = (int) $this->getRequest()->getParam('id');
        $model = $this->rule->load($id);

        if ($id && !$model->getId()) {
            $this->messageManager->addError(__('Record does not exist'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            $model->delete();
            $this->messageManager->addSuccess(
                __('Payment Restriction has been successfully deleted')
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }
}
