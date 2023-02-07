<?php

declare(strict_types=1);

namespace Roadmap\ExtensionAttributes\Api;

use Roadmap\ExtensionAttributes\Api\Data\AttributesInterface;

/**
 * @api
 */
interface ManagementInterface
{

    /**
     * @param int $shipmentId
     * @return AttributesInterface
     */
    public function getByShipmentId(int $shipmentId): AttributesInterface;
}
