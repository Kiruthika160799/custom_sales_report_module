<?php
namespace Custom\SalesReport\Block\Adminhtml\Report;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data as BackendHelper;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Catalog\Model\CategoryFactory;

class Grid extends Extended
{
    protected $collectionFactory;
    protected $categoryFactory;
    protected $backendHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        BackendHelper $backendHelper,
        CollectionFactory $collectionFactory,
        CategoryFactory $categoryFactory,
        array $data = [] 
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('salesReportGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->join(
            ['order_item' => 'sales_order_item'],
            'main_table.entity_id = order_item.order_id',
            ['product_name' => 'order_item.name', 'quantity_sold' => 'order_item.qty_ordered', 'total_revenue' => 'order_item.row_total']
        )
        ->join(
            ['category_product' => 'catalog_category_product'],
            'order_item.product_id = category_product.product_id',
            ['category_id' => 'category_product.category_id']
        )
        ->addFieldToSelect(['entity_id', 'created_at'])
        ->getSelect()->group('order_item.order_id');
        if ($fromDate = $this->getRequest()->getParam('from_date')) {
            $collection->addFieldToFilter('main_table.created_at', ['gteq' => $fromDate]);
        }
        if ($toDate = $this->getRequest()->getParam('to_date')) {
            $collection->addFieldToFilter('main_table.created_at', ['lteq' => $toDate]);
        }
        if ($categoryId = $this->getRequest()->getParam('category_id')) {
            $collection->addFieldToFilter('category_product.category_id', ['eq' => $categoryId]);  // Filter by category_id
        }
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function getCategoryProductIds($categoryId)
    {
        $category = $this->categoryFactory->create()->load($categoryId);
        $products = $category->getProductCollection()->addFieldToSelect('entity_id');
        $productIds = [];
        foreach ($products as $product) {
            $productIds[] = $product->getId();
        }
        return $productIds;
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'order_id',
            [
                'header' => __('Order ID'),
                'index' => 'entity_id',
                'type' => 'number',
                'filter_index' => 'main_table.entity_id' 
            ]
        );

        $this->addColumn(
            'product_name',
            [
                'header' => __('Product Name'),
                'index' => 'product_name',
                'type' => 'text',
                'filter_index' => 'order_item.name'  
            ]
        );

        $this->addColumn(
            'quantity_sold',
            [
                'header' => __('Quantity Sold'),
                'index' => 'quantity_sold',
                'type' => 'number',
                'filter_index' => 'order_item.qty_ordered' 
            ]
        );

        $this->addColumn(
            'total_revenue',
            [
                'header' => __('Total Revenue'),
                'index' => 'total_revenue',
                'type' => 'currency',
                'currency_code' => 'INR',
                'filter_index' => 'order_item.row_total' 
            ]
        );

        $this->addColumn(
            'sale_date',
            [
                'header' => __('Sale Date'),
                'index' => 'created_at',
                'type' => 'datetime',
                'filter_condition_callback' => [$this, 'filterFromDate'],
                'filter_condition_callback' => [$this, 'filterToDate'] 
            ]
        );

        $this->addColumn(
            'category_id',
            [
                'header' => __('Product Category'),
                'index' => 'category_id',
                'type' => 'options',
                'options' => $this->getCategoryOptions(),
                'filter_condition_callback' => [$this, 'filterCategory']
            ]
        );
        
        $this->addColumn(
            'category_id',
            [
                'header' => __('Product Category'),
                'index' => 'category_id',
                'type' => 'options',
                'options' => $this->getCategoryOptions()
            ]
        );

        return parent::_prepareColumns();
    }

    public function filterFromDate($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->addFieldToFilter('main_table.created_at', ['gteq' => $value]);
        }
    }

    public function filterToDate($collection, $column)
    {
        $value = $column->getFilter()->getValue();
        if ($value) {
            $collection->addFieldToFilter('main_table.created_at', ['lteq' => $value]);
        }
    }

    protected function getCategoryOptions()
    {
        $categoryModel = $this->categoryFactory->create();
        $categories = $categoryModel->getCollection()
            ->addAttributeToSelect('name') 
            ->addIsActiveFilter() 
            ->load();
        $options = [];
        foreach ($categories as $category) {
            $options[$category->getId()] = $category->getName();  
        }
        return $options;
    }
}
