<?php
namespace Custom\SalesReport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Data extends AbstractHelper
{
    protected $orderCollectionFactory;

    public function __construct(
        CollectionFactory $orderCollectionFactory
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    public function getSalesData()
    {
        $orders = $this->orderCollectionFactory->create();
        $orders->addFieldToSelect(['entity_id', 'created_at', 'grand_total']);

        $salesData = [];
        foreach ($orders as $order) {
            $salesData[] = [
                'order_id' => $order->getEntityId(),
                'sale_date' => $order->getCreatedAt(),
                'total_revenue' => $order->getGrandTotal(),
            ];
        }

        return $salesData;
    }
}