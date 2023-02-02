<?php

namespace Roadmap\Sitemap\Model\ItemProvider;

use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\SitemapItemFactory;

class ExamplePages implements ItemProviderInterface
{
    /**
     * @var ExamplePagesConfigReader
     */
    private $configReader;

    /**
     * @var SitemapItemFactory
     */
    private $itemFactory;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepositoryInterface;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var array
     */
    protected $sitemapItems = [];

    /**
     * ExamplePages constructor.
     * @param ExamplePagesConfigReader $configReader
     * @param SitemapItemFactory $itemFactory
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ExamplePagesConfigReader $configReader,
        SitemapItemFactory $itemFactory,
        PageRepositoryInterface $pageRepositoryInterface,     //CMS pages will be used as an example
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->configReader = $configReader;
        $this->itemFactory = $itemFactory;

        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param int $storeId
     * @return array
     * @throws NoSuchEntityException|LocalizedException
     */
    public function getItems($storeId): array
    {
        //CMS pages will be used as an example here you can provide your custom entity data with urls

        $searchCriteria = $searchCriteria = $this->searchCriteriaBuilder->create();
        $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();

        foreach ($pages as $page) {
            $this->sitemapItems[] = $this->itemFactory->create(
                [
                    'url' => $page->getIdentifier(),
                    'updatedAt' => $page->getUpdateTime(),
                    'priority' => $this->getPriority($storeId),
                    'changeFrequency' => $this->getChangeFrequency($storeId)
                ]
            );
        }

        return $this->sitemapItems;
    }

    /**
     * @param int $storeId
     *
     * @return string
     *
     */
    private function getChangeFrequency(int $storeId): string
    {
        return $this->configReader->getChangeFrequency($storeId);
    }

    /**
     * @param int $storeId
     *
     * @return string
     *
     */
    private function getPriority(int $storeId): string
    {
        return $this->configReader->getPriority($storeId);
    }
}
