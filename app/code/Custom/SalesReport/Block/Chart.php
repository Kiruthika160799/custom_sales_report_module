<?php
namespace Custom\SalesReport\Block;

use Magento\Backend\Block\Template;

class Chart extends Template
{
    protected $salesData;

    public function setSalesData($salesData)
    {
        $this->salesData = $salesData;
    }

    public function getSalesData()
    {
        return $this->salesData;
    }
}
