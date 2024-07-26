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

use Magento\Rule\Model\Condition\AbstractCondition;
use Mavenbird\Payrestriction\Model\Rule;

class NewConditionHtml extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule
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
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = $this->rule->create(
            $type
        )->setId(
            $id
        )->setType(
            $type
        )->setRule(
            $this->rule
        )->setPrefix(
            'conditions'
        );
        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }
        $this->getResponse()->setBody($html);
    }
}
