# Sale Stock - Assessment
##### by Praditha Hidayat

### Framework: Laravel 5.5
Pre-requisits:
* PHP >= 7.0.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* XML PHP Extension
* MySQL

### How to run localy
* Clone this repository and then run `composer install` from terminal
* Create database with name `salestock_db` then run `php artisan migrate` and `php artisan db:seed` from terminal
* Rename file `.env.example` to `.env` and adjust the database configuration based on database configuration on your local machine
* Run `php artisan serve` from terminal to run the application
* You can see the list of API and its description on Swagger API Documentation at `http://localhost:8000/api/documentation`

### Application Flow
##### As Customer
1. Login as `customer` (username: `customer@mail.com`, password: `secret`) and copy the `token` to used on next request as an Authorization behaviour
2. Get all product by hit `GET:{host}/products`
3. Get all available coupon by hit `GET:{host}/coupons`
4. Put the product ID to `POST:{host}/orders` body request with its quantity and coupon ID if available
5. Do confirmation payment by hit `POST:{host}/orders/{orderID}/confirm/payment` and put confirmation payment code to the body request.
 **Assumption: Confirmation payment code is already VALID**
6. Customer can check the order status on `GET:{host}/orders/{Order ID}`
7. Wait for Admin to ship the order. **Assumption: Shipping ID will be sent to customer email**
8. Customer can check the shipment status on `GET:{host}/shipments/{Shipping ID}/status`

##### As Admin
1. Login as `admin` (username: `admin@salestock.com`, password: `secret`) and copy the `token` to used on next request as an Authorization behaviour
2. Admin can see the list of order by hit 'GET:{host}/orders'
3. Admin can see the list of Logistic partner by hit `GET:{host}/logistic-partners`
4. Admin do shipment to the order by hit `POST:{host}/orders/{orderID}/ship` and choose the logistic partner ID, OR
5. Admin do cancel to the order by hit 'POST:{host}/orders/{orderID}/cancel'
6. If the order is already shipped, admin can change the shipment status by hit `POST:{host}/shipments/{shipping ID}/status/{shipment status}`
