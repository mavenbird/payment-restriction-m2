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

namespace Mavenbird\Payrestriction\Model\ResourceModel;

use Mavenbird\Payrestriction\Model\ResourceModel\AbstractRule;

class Rule extends AbstractRule
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('payrestriction_rule', 'rule_id');
    }

    /**
     * Return codes of all product attributes currently used in promo rules
     *
     * @return array
     */
    public function getAttributes()
    {
        $db = $this->getConnection();
        $tbl   = $this->getTable('payrestriction_attribute');

        $select = $db->select()->from($tbl, new \Zend_Db_Expr('DISTINCT code'));
        return $db->fetchCol($select);
    }

    /**
     * SaveAttributes
     *
     * @param [type] $id
     * @param [type] $attributes
     * @return void
     */
    public function saveAttributes($id, $attributes)
    {
        $db = $this->getConnection();
        $tbl = $this->getTable('payrestriction_attribute');

        $db->delete($tbl, ['rule_id=?' => $id]);

        foreach ($attributes as $code) {
            $data[] = [
                'rule_id' => $id,
                'code'    => $code,
            ];
        }
        $db->insertMultiple($tbl, $data);

        return $this;
    }
}
