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

class Duplicate extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule
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
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
            $this->messageManager->addError(__('Please select a rule to duplicate.'));
            return $this->_redirect('*/*');
        }

        try {
            $model  = $this->rule->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('Please select a rule to duplicate.'));
                return $this->_redirect('*/*');
            }

            $rule = clone $model;
            $rule->setIsActive(0);
            $rule->setId(null);
            $rule->save();

            $this->messageManager->addSuccess(
                __('The rule has been duplicated. Please feel free to activate it.')
            );
            return $this->_redirect('*/*/edit', ['id' => $rule->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $this->_redirect('*/*');
        }

        //unreachable
        return $this->_redirect('*/*');
    }
}
