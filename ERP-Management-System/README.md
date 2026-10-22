\# Simple ERP Management System



\## Overview

This is a web-based Simple ERP Management System developed using PHP, MySQL, and Bootstrap. It handles core ERP modules including secure user authentication, Customer Management, Item Inventory, Invoice Generation, and detailed reports based on assignment specifications.



\## Features and Modules Covered

\- \*\*Authentication (Login System):\*\* Secure login page for system access.

&#x20; - Default Login Credentials: Username -> `admin` | Password -> `erp123#`

\- \*\*Task 1: Customer Management:\*\* Register, update, delete, and view customers including Title, First Name, Last Name, Contact Number, and District.

\- \*\*Task 2: Item Inventory Management:\*\* Register items with Item Code, Item Name, Category, Subcategory, Quantity, and Unit Price.

\- \*\*Task 3: Reports\*\*

&#x20; - \*\*3.a Invoice Summary Report:\*\* Date range filtering, customer district, item counts, and total invoice amount.

&#x20; - \*\*3.b Invoice Item-wise Report:\*\* Item codes, names, categories, quantities, unit prices, and date filters.

&#x20; - \*\*3.c Item Stock Report:\*\* Aggregated stock quantities with unique item listings.



\## Assumptions Made

\- Designed to run on a local development server like XAMPP (PHP and MySQL/MariaDB).

\- Bootstrap v5.3 is used for responsive UI styling and print-friendly layouts.

\- Currency is standardized to Sri Lankan Rupees (Rs.).



\## How-to Setup Your Project in a Local Environment

1\. Install XAMPP on your local machine.

2\. Place the project folder inside the `htdocs` directory (`C:/xampp/htdocs/ERP-Management-System/`).

3\. Open `http://localhost/phpmyadmin/`, create a database named `erp\_management`, and import the provided SQL file.

4\. Verify database credentials in `config/database.php`.

5\. Access the application in your browser via: `http://localhost/ERP-Management-System/login.php`

&#x20;  - Default Login Credentials: Username -> `admin` | Password -> `erp123#`

