<?php

declare(strict_types=1);

namespace  Roadmap\ExtensionAttributes\Model;

use Magento\Framework\Model\AbstractModel;
use Roadmap\ExtensionAttributes\Api\Data\AttributesInterface;
use Roadmap\ExtensionAttributes\Model\ResourceModel\Attributes as Resource;

class Attributes extends AbstractModel implements AttributesInterface
{
    public const CARRIER = 'carrier';
    public const COST = 'cost';
    public const DELIVERY_DATE = 'delivery_date';
    public const SHIPMENT_ID = 'shipment_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'additional_shipment_attributes_shapes_model';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * @return int
     */
    public function getShipmentId(): int
    {
        return (int) $this->getData(self::SHIPMENT_ID);
    }

    /**
     * @return string
     */
    public function getCarrier(): string
    {
        return (string) $this->getData(self::CARRIER);
    }

    /**
     * @return float
     */
    public function getCost(): float
    {
        return (float) $this->getData(self::COST);
    }

    /**
     * @return string
     */
    public function getDeliveryDate(): string
    {
        return (string) $this->getData(self::DELIVERY_DATE);
    }

    /**
     * @param int $id
     * @return void
     */
    public function setShipmentId(int $id): void
    {
        $this->setData(self::SHIPMENT_ID, $id);
    }

    /**
     * @param string $carrier
     * @return void
     */
    public function setCarrier(string $carrier): void
    {
        $this->setData(self::CARRIER, $carrier);
    }

    /**
     * @param float $cost
     * @return void
     */
    public function setCost(float $cost): void
    {
        $this->setData(self::COST, $cost);
    }

    /**
     * @param string $date
     * @return void
     */
    public function setDeliveryDate(string $date): void
    {
        $this->setData(self::DELIVERY_DATE, $date);
    }
}
