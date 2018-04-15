# ArtshopNG Technical Documentation

## Table Of Contents
1. [Introduction, Overview & Features](#introduction)
2. [ARTSHOP API](#artshop-api)
3. [ARTSHOP Front-End](#artshop-frontend)
4. [Contributing](#contributing)

## Introduction, Overview & Features

### About
**[ArtshopNG](http://artshop.com.ng)** seeks to be the world’s leading online marketplace for Nigeria Art, Crafts and Textile Prints. **[ArtshopNG](http://artshop.com.ng)** promotes the beauty of the Nigerian culture by creating an online community where crafters, artists and makers could sell their handmade, vintage goods and craft supplies. <br>

There are three types of users on the platform: Customers, Vendors and Administrators. Vendors register and can add products which are then reviewed by the Administrator before they are displayed for Customers to purchase. When paymment is made the amount is split between **[ArtshopNG](http://artshop.com.ng)** and the vendor whose product was purchased.


### Technical Overview
The project was built as a RESTful-api on the back-end with [Laravel](https://laravel.com) and MySQL. The Presentation Layer is a [Vuejs](https://vuejs.org) single page web application which gets data from the [Laravel](https://laravel.com) backend. **[ArtshopNG](http://artshop.com.ng)** integrates with [Paystack](https://paystack.com) as its payment solution to recieve money and handle payouts to Vendors whose products were purchased. Asides [Laravel](https://laravel.com) and [Vuejs](https://vuejs.org) **[ArtshopNG](http://artshop.com.ng)** uses several other technologies. Here is a comprehensive list of frameworks, libraries and packages used in this applicatiion:

#### Front-End Technology Stack
- HTML5, CSS3, Javascript
- [Bootstrap CSS](https://getbootstrap.com)
- [Sofani - Furniture Store HTML Template](https://themeforest.net/item/sofani-furniture-store-html-template/19892365) and its dependencies.
- [Vuejs](https://vuejs.org)
- [JQuery](https://jquery.com/)
- [Moment.js](https://momentjs.com/) for Date and Time ormating
- [Axios.js](https://github.com/axios/axios) for AJAX requests
- [Smoke.js](https://smoke-js.com/) for Alerts

#### Back-End Technology Stack

- [PHP 7.1.16](http://php.net) - MySQL - [Laravel 5.4](https://laravel.com/docs/5.4)
- [Laravel Passport](https://laravel.com/docs/5.4/passport) for OAuth
- [Barryvdh's Laravel CORS Package](https://github.com/barryvdh/laravel-cors)
- [S-ichikawa's Laravel Sendgrid Driver](https://github.com/s-ichikawa/laravel-sendgrid-driver)