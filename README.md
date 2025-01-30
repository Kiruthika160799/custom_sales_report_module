**Custom Sales Report Module**
**1. Custom Module Creation**
    To create a custom sales report module in Magento 2, follow these steps:
    
**Database:**
**Utilize the following database tables:**
sales_order: Contains order details such as order ID, customer information, etc.
sales_order_item: Contains product details for each ordered item, such as product name, quantity sold, and price.

**2. Backend Admin Grid**
    The backend admin grid is used to display sales report data. Here's how to implement it:
**Menu Creation in the Reports Tab:**
    Add a custom menu item under the Reports tab in the Magento Admin Panel.
    ![image](https://github.com/user-attachments/assets/a07cbb98-5fd2-4818-be4b-da8fde5ca768)

**Custom Sales Report:**
    **This grid will display the following columns:**
        1. Order ID
        2. Product Name
        3. Quantity Sold
        4. Total Revenue
        5. Sale Date
        6. Product Category
    Implement filters for all columns, allow sorting, and provide pagination for better usability.
    ![image](https://github.com/user-attachments/assets/c4820a9e-66c1-4816-833c-7cd9c66fee78)

**3. Sales Chart:**
    Add a visual chart to represent sales data. The chart will display:
    Completed orders based on the Sale Date
    Total revenue for each order
    ![image](https://github.com/user-attachments/assets/227d09b2-0501-4027-ad48-7dce69069542)

**4. REST API**
    The Sales Report API provides a RESTful endpoint to fetch sales report data, including order details and product information. 
    This API retrieves all completed orders along with their associated products, quantity sold, and total revenue.
**API Details**
    **URL**: http://magento.loc/rest/V1/custom/report
    **Method**: GET
    **Authorization**: Bearer Token (Admin authentication required).
    **Response** :
    [
        {
            "order_id": "8",
            "sales_date": "2025-01-28 15:11:08",
            "product_name": "Joust Duffle Bag",
            "quantity_sold": "1.0000",
            "total_revenue": "12.0000"
        }
    ]
**Screenshot :** 

