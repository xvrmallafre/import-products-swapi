# Starships and Pilots for Laravel

    xvrmallafre/import-products-swapi

## Requirements

 - PHP: 7.2.24~
 - Laravel: 6.12~

`Only tested with versions shown above`

## Main Functionalities

This package creates some tables to store information from SWAPI, and adds 2 commands to artisan:
 - php artisan swapi:import -> Gets the information from SWAPI
 - php artisan magento:import -> Sends the information to Magento 2

## Installation

### Via Composer:

 - Install the module composer by running `composer require xvrmallafre/import-products-swapi:1.0.0`
 - create the migrations `php artisan migrate`
 - create the variables needed for Magento in .enf file:
   - ```
     MAGENTO_REST_URL=http://magentourl.com/rest/
     MAGENTO_ADMIN_USER=adminuser
     MAGENTO_ADMIN_PASSWORD=adminpassword
