<?php

namespace Roadmap\MessageQueue\Model\Queue\Database\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Roadmap\MessageQueue\Logger\Logger;

class Consumer
{
    /**
     * @var SerializerInterface
     */
    protected $jsonSerializer;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var CustomerInterfaceFactory
     */
    private $customerFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;


    /**
     * @param SerializerInterface $serializer
     * @param Logger $logger
     * @param CustomerInterfaceFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        SerializerInterface $serializer,
        Logger $logger,
        CustomerInterfaceFactory $customerFactory,
        StoreManagerInterface $storeManager,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->jsonSerializer = $serializer;
        $this->logger = $logger;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
    }

    public function process(string $data)
    {
        try {
            $this->logger->info("Queue has started new execution\n\n");
            $customers = $this->jsonSerializer->unserialize($data);
            if (!empty($customers) && is_array($customers)) {
                $this->execute($customers);
            }
            $this->logger->info("Queue has finished execution\n\n");
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
        $this->logger->info("Customers migrated successfully. For all logs see ../var/log/migrate_customers.log");
    }

    public function execute(array $customers)
    {
        foreach ($customers as $customer) {
            $created = false;
            try {
                $this->createNewCustomer($customer);
                $created = true;
            } catch (InputException|
            NoSuchEntityException|
            InputMismatchException|
            LocalizedException $exception) {
                $this->logger->error('Customer with email: ' .
                    '' . $customer['email'] . 'was not created');
                $this->logger->error($exception->getMessage());
            }
            if ($created) {
                $this->logger->info('Customer with email: ' .
                    '' . $customer['email'] . 'was migrated successfully');
            }
        }
    }

    /**
     * Create new user
     *
     * @param array $customerData
     * @return CustomerInterface
     * @throws InputException
     * @throws InputMismatchException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function createNewCustomer(array $customerData)
    {
        /**
         * @var $customer CustomerInterface
         */
        $customer = $this->customerFactory->create();

        $customer->setWebsiteId($customerData['website_id']);
        $customer->setStoreId($customerData['store_id']);
        $customer->setEmail($customerData['email']);
        $customer->setLastname($customerData['lastname']);
        $customer->setFirstname($customerData['firstname']);
        if (isset($customerData['gender']) && $customerData['gender'] != '') {
            $customer->setGender($customerData['gender']);
        }
        $customer->setGroupId($customerData['group_id']);

        return $this->customerRepository->save($customer, $customerData['password_hash']);
    }
}
