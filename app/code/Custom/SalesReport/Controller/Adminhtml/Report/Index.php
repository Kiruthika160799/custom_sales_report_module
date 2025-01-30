<?php
namespace Custom\SalesReport\Controller\Adminhtml\Report;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    public function execute()
    {
        $this->_view->loadLayout();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Sales Report'));
        $resultPage->setActiveMenu('Custom_SalesReport::custom_sales_report');
        $resultPage->addBreadcrumb(__('Sales Report Grid'), __('Sales Report Grid List'));
        $this->_addContent($this->_view->getLayout()->createBlock('Custom\SalesReport\Block\Adminhtml\Report\Grid'));
        $this->_view->renderLayout();
    }
    protected function _isAllowed()
    {
        return true;
    }
}

