<?php

declare(strict_types=1);

namespace Roadmap\ExtensionAttributes\Model\ResourceModel\Attributes;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Roadmap\ExtensionAttributes\Model\ResourceModel\Attributes as Resource;
use Roadmap\ExtensionAttributes\Model\Attributes as Model;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, Resource::class);
    }
}
