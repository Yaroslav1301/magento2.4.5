<?php

namespace Roadmap\MessageQueue\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * @var string
     */
    protected $fileName = '/var/log/message_queue_publisher.log';
}
