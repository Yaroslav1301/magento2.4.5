<?php

namespace Roadmap\MessageQueue\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\MessageQueue\PublisherInterface;

class ProcessProductAfterDeleteEventObserver implements ObserverInterface
{
    public const TOPIC_NAME = 'roadmap.product.delete';

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @param PublisherInterface $publisher
     */
    public function __construct(PublisherInterface $publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * Call an API to product delete from ERP
     * after delete product from Magento
     *
     * @param   Observer $observer
     * @return  $this
     */
    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();

        $this->publisher->publish(self::TOPIC_NAME, $product);
        return $this;
    }
}
