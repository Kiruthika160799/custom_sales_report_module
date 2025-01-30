<?php
namespace Custom\SalesReport\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Custom\SalesReport\Helper\Data;

class Index extends Action
{
    protected $resultPageFactory;
    protected $salesDataHelper;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Data $salesDataHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->salesDataHelper = $salesDataHelper;
    }

    public function execute()
    {
        $salesData = $this->salesDataHelper->getSalesData();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Custom_SalesReport::sales_report');
        $resultPage->getConfig()->getTitle()->prepend(__('Sales Report'));
        // Passing data to the block
        $block = $resultPage->getLayout()->getBlock('sales_chart');
        if ($block) {
            $block->setSalesData($salesData);
        }

        return $resultPage;
    }
}
