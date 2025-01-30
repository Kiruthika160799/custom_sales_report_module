<?php
namespace Custom\SalesReport\Model\ResourceModel\SalesReport;

use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;

class Collection extends OrderCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_mainTable = 'sales_order';

    protected function _construct()
    {
        parent::_construct();
        $this->_init('Magento\Sales\Model\Order', 'Magento\Sales\Model\ResourceModel\Order');
    }

    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()
            ->join(
                ['order_item' => $this->getTable('sales_order_item')],
                'main_table.entity_id = order_item.order_id',
                ['product_name' => 'order_item.name', 'quantity_sold' => 'order_item.qty_ordered']
            )
            ->columns(['total_revenue' => 'main_table.grand_total'])
            ->group('main_table.entity_id');
    }
}
