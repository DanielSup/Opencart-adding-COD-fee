# Source code of the e-shop at www.flakon.cz for adding an information about the cost for cash on delivery

This repository contains source code with my extensions of source code for adding an information about the fee for cash on delivery to the order. The information about the fee for cash on delivery is saved in the `cod_fee` variable in files in the directory `catalog/model/shipping` which represent possible shipping methods. Some of the shipping methods contain the information about the fee for cash on delivery in the `cod_fee` variable in the `quote_data` and `method_data` arrays.

The information about the fee for cash on delivery is added also in the `codfee.php` file in the `catalog/model/total` directory. This file ensures adding an information about the price for shipping to the order which can customer see before completation of the order.

The repository also contains modified `cod.php` file representing payment by cash on delivery. The file `cod.php` gets the information about the fee for cash on delivery for the selected shipping method. Then the information about the fee for cash on delivery is saved in the `method_data` array which is then used for showing the information about the fee for cash on delivery and adding it to the total price of the order.

The repository also contains modified `confirm.php` file which ensures showing the information about the fee for cash on delivery to the customer before completing of the order. The file `payment_method.php` ensures that cash on delivery is not offered if the `cod_fee` variable is not defined for the selected shipping method because in some countries and for some shipping methods is not possible to pay by cash on delivery.
