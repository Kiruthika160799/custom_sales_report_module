<?php
namespace Custom\SalesReport\Api;

interface ReportDataInterface
{
    /**
     * 
     *
     * @return \Magento\Framework\Api\ExtensibleDataInterface[]
     */
    public function getSalesReportData();
}
