<?php
namespace Mavenbird\PaymentRestriction\Ui\DataProvider\Columns\Modifier;
 
class CustomerGroup extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        array $data = []
    ) {
        $this->groupRepository = $groupRepository;
        parent::__construct($context, $data);
    }
 
    /**
     * Render function
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $groupIds = explode(',', $row->getData($this->getColumn()->getIndex()));
        $result = '';
      
        foreach ($groupIds as $key => $groupId) {
            $group = $this->groupRepository->getById($groupId);
            $result .= $group->getCode().',';
        }
        return rtrim($result, ',');
    }
}
