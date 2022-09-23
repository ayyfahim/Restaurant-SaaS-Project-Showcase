# Restaurant App

<br>

Dinness is a Laravel based Restaruant SAAS built by me. It uses ReactJs to run the Front-end. 

[Online Demo](https://dinness.topcruder.com)

<br>

## Client Pages:
<img src="/store_front.png" width="18%"></img>
<img src="/store_front_2.png" width="18%"></img>
<img src="/homepage.png" width="18%"></img>
<img src="/profile.png" width="18%"></img>
<img src="/profile_w_sidebar.png" width="18%"></img>
<img src="/product_page.png" width="18%"></img>
<img src="/product_page_2_NestedAddons.png" width="18%"></img>
<img src="/cart_1.png" width="18%"></img>
<img src="/cart_2.png" width="18%"></img>
<img src="/edit_profile.png" width="18%"></img>
<img src="/guestmode.png" width="18%"></img>
<img src="/scan_qr.png" width="18%"></img>
<img src="/Screenshot_1.png" width="18%"></img>


## All Features :

### QR CODE SCANNER 

A built-in QR code generator and scanner. Customer can use their phone camera or webcam to scan QR code on app launch.

### GOOGLE MAP & GEOLOCATION SUPPORT  

Restaurant is able to add a map location so if the customer is out of the radius they can’t order and more geo features.


### KITCHENS AND WAITERS HAVE DIFFERENT ADMIN PANEL  

Kitchens and Waiters have different admin panels where they can manage the orders. Restaurant owners can create kitchen and waiter accounts.

### ORDER PROCESS

1.	The customer creates an order first then it goes to Restaurant dashboard for approval. After the approval it goes to Kitchen dashboard. 
2.	When the Kitchen dashboard prepares the food they click a button to mark the order as “Served”. 
3.	After that Waiters get a notification on their dashboards. They serve the food and then they can click a button to mark the order as complete.

### KITCHEN LOCATION

Products, Categories and Add-ons can be set to a Kitchen. For example: Drinks category can be set to a Kitchen called “Bar”. So when someone orders a food with a drink, the “Bar” Kitchen will get the order and notification on the Kitchen Dashboard. 
There’s also a Main Kitchen functionality where the main kitchen gets all the orders on their dashboards.

### KITCHENS CAN SERVE THE FOOD

Kitchen can mark an order as “ready to serve” through their dashboard. It helps the waiter to see which order is ready to be serve.

### ASSIGN TABLE TO WAITERS

From Restaurant dashboard you can assign multiple tables to a single waiter. So the waiter only has to manage those tables and get notifications from Waiter dashboard.

Restaurant can also choose time shifts for each waiter. Waiters can see the time shifts on their dashboard.

### WAITERS CAN CREATE ORDERS MANUALLY

Waiters can create order through their dashboard. They don’t have to wait for customers to create them.

### ADVANCED COUPON SYSTEM

1.	A COUPON can be added to selected products and categories. So the coupon will only work with those Products and products within those Categories.
2.	Products and Categories can be excluded from a coupon. So all the other Products and products within the Categories will work with that coupon except the excluded ones.
3.	Minimum/Maximum spend can be added so a customer has to spend that minimum or maximum amount to use that coupon.
4.	Coupon also has an expiry date and Limit per user.

### DISCOUNTS WITH TIME PERIOD

1.	Discount can be set with a specific time period. So a customer can only use that discount code within that time period. 
2.	The discount amount can be a fixed amount or percentage amount. 
3.	Also a discount code can be set for multiple or single product.

### PRODUCT ADDONS

Restaurant can add multiple Add-on’s for each product. 
1.	The Add-ons can be of 2 types Checkbox and Extra. 
2.	It can also be set to a min and max amount so the customer have to meet those requirements. 
3.	If the multi select option is chosen customer can select multiple Add-ons inside the same Add-on category.
When ordering the customer can choose the Add-ons.

### ADD-ON CATEOGRY

You can create an Add-on category and inside there will be multiple single Add-ons.

### ORDERS CAN BE PREPAID

An order can be prepaid by a customer.

### OPEN HOURS

Restaurant can select opening hours for each day of the week. Customers can only order and view the restaurant during open hours only.

### INDEX LOCATION

Products and Categories will be shown according to the index numbers from highest to lowest. It can be set by restaurants.

### ALLERGENS & DIET PREFERENCES

Restaurants can add allergens and diet preferences to a product & category. So a customer can choose a food with his diet preference and allergens.

### VIEW ORDERS ON SAME TABLE

Customer can see all the orders on the same table they are in.

### PAY ALL AT ONCE

All orders on the table can be paid at once by a customer.

### ADDING MULTIPLE CARDS

Customer can add multiple cards on their account. So next time they can chose a card.

![ADD CARDS](/addCard.png)

### VIEW ORDERS AND RECEIPTS

Viewing all orders and seeing receipts can be done from the customer menu.

### LOGIN THROUGH OTP AND SOCIAL MEDIA

Customer can login with their Phone numbers. They can also login using their Facebook and Google accounts.

<img src="/login.png" width="30%"></img> 
<img src="/login_2.png" width="30%"></img> 
<img src="/login_3.png" width="30%"></img>

---

### :hammer_and_wrench: Technologies used :

<div>
  <img src="https://github.com/devicons/devicon/blob/master/icons/laravel/laravel-plain-wordmark.svg" title="Laravel" alt="Laravel" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/react/react-original.svg" title="ReactJS" alt="ReactJS" width="40" height="40"/>&nbsp;
  <img src="https://github.com/devicons/devicon/blob/master/icons/php/php-original.svg" title="PHP" alt="PHP" width="40" height="40"/>
</div>
