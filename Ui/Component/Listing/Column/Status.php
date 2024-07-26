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

namespace Mavenbird\Payrestriction\Ui\Component\Listing\Column;

class Status implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Option
     *
     * @var [type]
     */
    protected $options;

    /**
     * StatusLists
     *
     * @var [type]
     */
    protected $_statusList;

    /**
     * Construct
     *
     * @param \Mavenbird\Payrestriction\Model\System\Config\Status $statusList
     */
    public function __construct(\Mavenbird\Payrestriction\Model\System\Config\Status $statusList)
    {
        $this->_statusList = $statusList;
    }

    /**
     * ToOptionArray
     *
     * @return void
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            foreach ($this->_statusList->toOptionArray() as $value => $label) {
                $this->options[] = [
                    'value' => $value,
                    'label' => $label
                ];
            }
        }

        return $this->options;
    }
}
