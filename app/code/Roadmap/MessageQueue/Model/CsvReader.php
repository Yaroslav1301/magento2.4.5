<?php

namespace Roadmap\MessageQueue\Model;

use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Roadmap\MessageQueue\Logger\Logger;

class CsvReader
{
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var Csv
     */
    protected $csv;

    /**
     * @var File
     */
    protected $file;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param DirectoryList $directoryList
     * @param Csv $csv
     * @param File $file
     * @param Logger $logger
     */
    public function __construct(
        DirectoryList $directoryList,
        Csv $csv,
        File $file,
        Logger $logger
    ) {
        $this->directoryList = $directoryList;
        $this->csv = $csv;
        $this->file = $file;
        $this->logger = $logger;
    }

    /**
     * Get all data from csv file
     *
     * @param string $csvFilePath
     * @return array
     */
    public function readCsv(string $csvFilePath)
    {
        $rootDirectory = $this->directoryList->getRoot();
        $csvFile = $rootDirectory . "/" . $csvFilePath;
        try {
            if ($this->file->isExists($csvFile)) {
                //set delimiter, for tab pass "\t"
                $this->csv->setDelimiter(",");
                //get data as an array
                $data = $this->csv->getData($csvFile);
                if (!empty($data)) {
                    return $data;
                }
            } else {
                $this->logger->info('Csv file not exist');
            }
        } catch (FileSystemException | \Exception $e) {
            $this->logger->info($e->getMessage());
        }
        return [];
    }
}
