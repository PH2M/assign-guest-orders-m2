PH2M_AssignGuestOrders
----------------------
Automatically reassign guest orders to customer when an account is created

Requirements
------------
Magento >= 2.1.0

Installation
------------
```
composer require ph2m/assign-guest-orders-m2
bin/magento module:enable PH2M_AssignGuestOrders
bin/magento setup:upgrade
```

Possible cases
--------------
The following cases will make the order with corresponding email assigned to the customer.

* Customer is created in front
* Customer is created in admin
* Customer is already created and a guest order is placed with his email

Licence
-------
[GNU General Public License, version 3 (GPLv3)](http://opensource.org/licenses/gpl-3.0)
