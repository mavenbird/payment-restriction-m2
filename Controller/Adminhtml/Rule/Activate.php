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

class Activate extends \Mavenbird\Payrestriction\Controller\Adminhtml\Rule\AbstractMassAction
{
    /**
     * MassAction
     *
     * @param [type] $collection
     * @return void
     */
    protected function massAction($collection)
    {
        foreach ($collection as $model) {
            $model->setIsActive(1);
            $model->save();
        }
        $message = __('Record(s) have been updated.');
        $this->messageManager->addSuccess($message);
    }
}
