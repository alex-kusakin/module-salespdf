<?php
/**
 * @author Alex Kusakin
 */
namespace AlexKusakin\SalesPdf\Model\Order\Invoice;

/**
 * Sales Order Invoice Footer PDF model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Footer
{
    const MARGIN_LEFT = 25;
    const MARGIN_TOP = 20;

    const XML_PATH_BLOCK_ID = 'sales_pdf/invoice/footer_cms_block';
    const ENCODING = 'UTF-8';

    /**
     * @var \Zend_Pdf_Page
     */
    protected $page;

    /**
     * @var \Magento\Sales\Model\Order\Pdf\AbstractPdf
     */
    protected $pdfModel;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $config;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Cms\Api\BlockRepositoryInterface
     */
    protected $blockRepository;

    /**
     * PDF Footer constructor.
     * @param \Zend_Pdf_Page $page
     * @param \Magento\Sales\Model\Order\Pdf\AbstractPdf $pdfModel
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
     */
    public function __construct(
        \Zend_Pdf_Page $page,
        \Magento\Sales\Model\Order\Pdf\AbstractPdf $pdfModel,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository
    ) {
        $this->page = $page;
        $this->pdfModel = $pdfModel;
        $this->config = $config;
        $this->storeManager = $storeManager;
        $this->blockRepository = $blockRepository;
    }

    /**
     * Draw footer in PDF
     *
     * @throws \Zend_Pdf_Exception
     */
    public function draw()
    {
        $content = $this->getContent();
        if ($content) {
            $this->page->drawText($content, self::MARGIN_LEFT, $this->pdfModel->y - self::MARGIN_TOP, self::ENCODING);
        }

        return $this->page;
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $blockId = $this->config->getValue(
            self::XML_PATH_BLOCK_ID,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );

        if (!$blockId) {
            return false;
        }

        try {
            $block = $this->blockRepository->getById($blockId);
        } catch (\Exception $e) {
            return false;
        }

        return $block->isActive() && $block->getContent() ? $block->getContent() : false;
    }

    /**
     * @return int
     */
    protected function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }
}
