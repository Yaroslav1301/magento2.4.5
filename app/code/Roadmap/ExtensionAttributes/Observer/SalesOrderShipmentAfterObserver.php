<?php

namespace Roadmap\ExtensionAttributes\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Psr\Log\LoggerInterface;
use Roadmap\ExtensionAttributes\Api\Data\AttributesInterfaceFactory;
use Roadmap\ExtensionAttributes\Model\ResourceModel\Attributes as Resource;

/**
 * Observes the `sales_order_shipment_save_after` event.
 */
class SalesOrderShipmentAfterObserver implements ObserverInterface
{
    /**
     * @var AttributesInterfaceFactory
     */
    private $factory;

    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param AttributesInterfaceFactory $attributesInterfaceFactory
     * @param Resource $resource
     * @param LoggerInterface $logger
     */
    public function __construct(
        AttributesInterfaceFactory $attributesInterfaceFactory,
        Resource $resource,
        LoggerInterface $logger
    ) {
        $this->factory = $attributesInterfaceFactory;
        $this->resource = $resource;
        $this->logger = $logger;
    }
    /**
     * This is just sample example how data can be saved for extension attributes
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $shipment = $observer->getEvent()->getShipment();

        // here just simple example of how to add additional data to the shipment new model
        $shipmentAttributes = $this->factory->create();
        $shipmentAttributes->setShipmentId($shipment->getEntityId());
        $shipmentAttributes->setCost(12.85);
        $shipmentAttributes->setCarrier('Some Test Carrier');
        $shipmentAttributes->setDeliveryDate(date("Y-m-d H:i:s"));
        try {
            $this->resource->save($shipmentAttributes);
        } catch (\Exception | AlreadyExistsException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
