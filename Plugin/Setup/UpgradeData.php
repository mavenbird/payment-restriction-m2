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

namespace Mavenbird\Payrestriction\Plugin\Setup;

class UpgradeData
{
    /**
     * @var Mavenbird\Core\Setup\SerializedFieldDataConverter
     */
    private $fieldDataConverter;

    /**
     * Construct
     *
     * @param \Mavenbird\Core\Setup\SerializedFieldDataConverter $fieldDataConverter
     */
    public function __construct(\Mavenbird\Core\Setup\SerializedFieldDataConverter $fieldDataConverter)
    {
        $this->fieldDataConverter = $fieldDataConverter;
    }

    /**
     * AfterConvertSerializedDataToJson
     *
     * @param \Magento\SalesRule\Setup\UpgradeData $subject
     * @param [type] $result
     * @return void
     */
    public function afterConvertSerializedDataToJson(\Magento\SalesRule\Setup\UpgradeData $subject, $result)
    {
        $this->fieldDataConverter->convertSerializedDataToJson(
            'payrestriction_rule',
            'rule_id',
            ['conditions_serialized']
        );

        return $result;
    }
}
