<?php

namespace Roadmap\MessageQueue\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Roadmap\MessageQueue\Model\CsvReader;
use Roadmap\MessageQueue\Logger\Logger;
use Magento\Framework\MessageQueue\PublisherInterface;

class PublishCustomersToQueue extends Command
{
    /**
     * Path to csv file with customers data.
     * It's created just for an example
     *
     * @var string
     */
    public const CUSTOMERS_CSV_PATH = 'app/code/Roadmap/MessageQueue/Files/customers.csv';

    /**
     * Topology name for queue
     *
     * @var string
     */
    public const TOPIC_NAME = 'migrate.customers';

    /**
     * Size of elements that should be included in 1 message
     *
     * @var int
     */
    public const SIZE = 500;

    /**
     * @var CsvReader
     */
    protected $csvReader;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var PublisherInterface
     */
    protected $publisher;

    /**
     * @var SerializerInterface
     */
    protected $jsonSerializer;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param CsvReader $csvReader
     * @param State $state
     * @param Logger $logger
     * @param PublisherInterface $publisher
     * @param SerializerInterface $jsonSerializer
     * @param string|null $name
     */
    public function __construct(
        CsvReader $csvReader,
        State $state,
        Logger $logger,
        PublisherInterface $publisher,
        SerializerInterface $jsonSerializer,
        string $name = null
    ) {
        $this->csvReader = $csvReader;
        $this->state = $state;
        $this->logger = $logger;
        $this->publisher = $publisher;
        $this->jsonSerializer = $jsonSerializer;
        parent::__construct($name);
    }

    /**
     * Initialization of the command.
     */
    protected function configure()
    {
        $this->setName('queue:publisher:customers:start');
        $this->setDescription('This is a one time command for publishing customers to the queue');
        parent::configure();
    }

    /**
     * CLI command description.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $startTime = microtime(true);
        $this->output = $output;

        try {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        } catch (LocalizedException $exception) {
            $output->writeln('Importing process stopped, because unable to set area code!');
            $output->writeln('<error>'.$exception->getMessage().'</error>');
            $output->writeln('<error>'.$exception->getTraceAsString().'</error>');
            exit;
        }

        /**
         * Your data can come from different placed such as observers, API, uploaded data etc.
         */
        $data = $this->csvReader->readCsv(self::CUSTOMERS_CSV_PATH);

        if (empty($data)) {
            $this->output->writeln('<error>Customers was not published as file not contains any data!</error>');
        } else {
            $customersData = $this->prepareCustomersData($data);
        }
        if (!empty($customersData)) {
            $this->publishData($customersData);
        }

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $output->writeln('<info>Execution time of script = '  . $executionTime . ' seconds</info>');
    }

    /**
     * Prepare customers data for before publishing to queue
     *
     * @param array $data
     * @return array
     */
    protected function prepareCustomersData(array $data): array
    {
        $customersData = [];

        $keys = array_slice($data, 0, 1);
        $data = array_slice($data, 1);

        foreach ($data as $customer) {
            $count = count($customer);
            $temp = [];
            for ($i = 0; $i < $count; $i++) {
                if ($this->validateKey((string)$keys[0][$i])) {
                    $temp[$keys[0][$i]] = $customer[$i];
                }
            }
            $customersData[] = $temp;
        }

        return $customersData;
    }

    /**
     * Check just for required and pass other
     *
     * @param string $key
     * @return bool
     */
    protected function validateKey(string $key): bool
    {
        $keys = [
            'website_id', 'store_id', 'email', 'lastname',
            'firstname', 'gender', 'group_id', 'password_hash'
        ];

        return in_array($key, $keys);
    }

    /**
     * Publish data to the queue
     *
     * @param $data
     * @return void
     */
    protected function publishData($data): void
    {
        if (is_array($data)) {
            $chunks = array_chunk($data, self::SIZE);
            foreach ($chunks as $chunk) {
                $this->publisher->publish(self::TOPIC_NAME, $this->jsonSerializer->serialize($chunk));
            }
        }
    }
}
