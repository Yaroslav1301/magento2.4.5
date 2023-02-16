<?php

namespace Roadmap\MessageQueue\Model\Queue\RabbitMQ\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NotFoundException;
use Roadmap\MessageQueue\Logger\Logger;

class Consumer
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param Logger $logger
     */
    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param ProductInterface $product
     * @return void
     */
    public function processMessage(ProductInterface $product)
    {
        throw new NotFoundException(new \Magento\Framework\Phrase('dsafghg'));
        $this->logger->info($product->getId() . ' ' . $product->getSku());
    }
}
