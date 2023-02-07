<?php

declare(strict_types=1);

namespace Roadmap\ExtensionAttributes\Api\Data;

/**
 * @api
 */
interface AttributesInterface
{
    /**
     * @return int
     */
    public function getShipmentId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setShipmentId(int $id): void;

    /**
     * @return string
     */
    public function getCarrier(): string;

    /**
     * @param string $carrier
     * @return void
     */
    public function setCarrier(string $carrier): void;

    /**
     * @return float
     */
    public function getCost(): float;

    /**
     * @param float $cost
     * @return void
     */
    public function setCost(float $cost): void;

    /**
     * @return string
     */
    public function getDeliveryDate(): string;

    /**
     * @param string $date
     * @return void
     */
    public function setDeliveryDate(string $date): void;
}
