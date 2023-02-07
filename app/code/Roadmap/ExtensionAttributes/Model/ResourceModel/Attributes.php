<?php

declare(strict_types=1);

namespace Roadmap\ExtensionAttributes\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Attributes extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'additional_shipment_attributes_shapes_resource_model';

    protected function _construct()
    {
        $this->_init('additional_shipment_attributes', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
