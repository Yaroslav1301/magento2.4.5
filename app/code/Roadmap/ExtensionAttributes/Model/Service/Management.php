<?php

namespace Roadmap\ExtensionAttributes\Model\Service;

use Roadmap\ExtensionAttributes\Api\Data\AttributesInterface;
use Roadmap\ExtensionAttributes\Api\ManagementInterface;
use Roadmap\ExtensionAttributes\Api\Data\AttributesInterfaceFactory;
use Roadmap\ExtensionAttributes\Model\ResourceModel\Attributes as Resource;

class Management implements ManagementInterface
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var AttributesInterfaceFactory
     */
    private $factory;

    /**
     * Management constructor.
     * @param Resource $resource
     * @param AttributesInterfaceFactory $attributesFactory
     */
    public function __construct(
        Resource $resource,
        AttributesInterfaceFactory $attributesFactory
    ) {
        $this->resource = $resource;
        $this->factory = $attributesFactory;
    }

    /**
     * @param int $shipmentId
     * @return AttributesInterface
     */
    public function getByShipmentId(int $shipmentId): AttributesInterface
    {
        $object = $this->getNewInstance();
        $this->resource->load($object, $shipmentId, 'shipment_id');

        return $object;
    }

    public function getNewInstance(): AttributesInterface
    {
        return $this->factory->create();
    }
}
