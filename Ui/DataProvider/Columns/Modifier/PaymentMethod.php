<?php
namespace Mavenbird\PaymentRestriction\Ui\DataProvider\Columns\Modifier;
 
class PaymentMethod extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scope
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scope,
        array $data = []
    ) {
        $this->scope = $scope;
        parent::__construct($context, $data);
    }

    /**
     * Render Function
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $data = $row->getData($this->getColumn()->getIndex());
        $paymentMethods = !is_null($data) ? explode(',', $data) : [];
        $methodList = $this->scope->getValue('payment');
        $result = '';
        foreach ($paymentMethods as $method) {
            if (isset($methodList[$method]['title'])) {
                $result .= $methodList[$method]['title'] . ', ';
            }
        }
        return rtrim($result, ', ');
    }
}
