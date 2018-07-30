<?php
/**
 * @author Alex Kusakin
 */
namespace AlexKusakin\SalesPdf\Model\Config\Source;

/**
 * CMS Blocks source model
 */
class Block implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var \Magento\Framework\Api\SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * Blocks source constructor.
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Api\SortOrderBuilder $sortOrderBuilder
    ) {
        $this->blockRepository = $blockRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * To option array
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = [['label' => __('Not Selected'), 'value' => '']];

            $sortOrder = $this->sortOrderBuilder
                ->setField('title')
                ->setDirection('ASC')
                ->create();

            $searchCriteria = $this->searchCriteriaBuilder
                ->addSortOrder($sortOrder)
                ->create();

            $blocks = $this->blockRepository->getList($searchCriteria);
            foreach ($blocks->getItems() as $block) {
                $this->options[] = [
                    'label' => $block->getTitle(),
                    'value' => $block->getId()
                ];
            }
        }

        return $this->options;
    }
}
