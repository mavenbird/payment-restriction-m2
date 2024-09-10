<?php
namespace Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule;

class MassDelete extends \Mavenbird\PaymentRestriction\Controller\Adminhtml\Example\Rule
{
    
    /**
     * Rule Delete
     *
     * @return void
     */
    public function execute()
    {
        $ruleIds = $this->getRequest()->getParam('rule_ids', []); /*get form_field_name argument variable*/
        if (!is_array($ruleIds)) {
            $ruleIds=explode(",", $ruleIds);
        }
        $model = $model = $this->ruleFactory->create();
        if (count($ruleIds)) {
            $i = 0;
            foreach ($ruleIds as $ruleId) {
                try {
                    $model->load($ruleId);
                    $model->delete();
                    $i++;
                } catch (\Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                }
            }
            if ($i > 0) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 item(s) were deleted.', $i)
                );
            }
        } else {
            $this->messageManager->addErrorMessage(
                __('You can not delete item(s), Please check again %1')
            );
        }
        $this->_redirect('*/*/index');
    }
}
