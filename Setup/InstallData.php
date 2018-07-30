<?php
/**
 * @author Alex Kusakin
 */

namespace AlexKusakin\SalesPdf\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    const DEFAULT_BLOCK_ID = 'pdf_invoice_footer';
    const DEFAULT_BLOCK_TITLE = 'PDF Invoice Footer';

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * @var \Magento\Cms\Api\Data\BlockInterfaceFactory
     */
    protected $blockFactory;

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $config;

    /**
     * InstallData constructor.
     * @param \Magento\Cms\Api\Data\BlockInterfaceFactory $blockFactory
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     * @param \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config
     */
    public function __construct(
        \Magento\Cms\Api\Data\BlockInterfaceFactory $blockFactory,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $config
    ) {
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        try {
            $block = $this->blockRepository->getById(self::DEFAULT_BLOCK_ID);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $block = $this->blockFactory->create();
        }

        $block->setIdentifier(self::DEFAULT_BLOCK_ID)
            ->setIsActive(true)
            ->setTitle(self::DEFAULT_BLOCK_TITLE)
            ->setContent('Thank you for buying from us')
            ->setData('stores', \Magento\Store\Model\Store::DEFAULT_STORE_ID);

        $this->blockRepository->save($block);

        $blockId = $block->getId();
        $this->config->saveConfig(
            \AlexKusakin\SalesPdf\Model\Order\Invoice\Footer::XML_PATH_BLOCK_ID, $blockId, 'default', 0
        );
    }
}
