<?php
namespace Custom\SalesReport\Model;

use Custom\SalesReport\Api\ReportDataInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class ReportData implements ReportDataInterface
{
    /**
     * @var OrderCollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var OrderItemCollectionFactory
     */
    protected $orderItemCollectionFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param OrderItemCollectionFactory $orderItemCollectionFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderCollectionFactory $orderCollectionFactory,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        LoggerInterface $logger
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalesReportData()
    {
        try {
            // fetching completed order
            $orderCollection = $this->orderCollectionFactory->create();
            $orderCollection->addFieldToFilter('status', 'complete');
            $orderCollection->addFieldToSelect(['entity_id', 'created_at']);
            $reportData = [];
            foreach ($orderCollection as $order) {
                 // fetching multiple products from the order
                $orderItemCollection = $this->orderItemCollectionFactory->create();
                $orderItemCollection->addFieldToFilter('order_id', $order->getId());
                $orderItemCollection->addFieldToSelect(['name', 'qty_ordered', 'row_total']);
                foreach ($orderItemCollection as $item) {
                    $reportData[] = [
                        'order_id' => $order->getId(),
                        'sales_date' => $order->getCreatedAt(),
                        'product_name' => $item->getName(),
                        'quantity_sold' => $item->getQtyOrdered(),
                        'total_revenue' => $item->getRowTotal(),
                    ];
                }
            }
            if (empty($reportData)) {
                throw new LocalizedException(__('No report data found.'));
            }
            return $reportData;
        } catch (\Exception $e) {
            $this->logger->error('Error fetching sales report data: ' . $e->getMessage());
            throw new LocalizedException(__('Unable to fetch sales report data at this time.'));
        }
    }
}
