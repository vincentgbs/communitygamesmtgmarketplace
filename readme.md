## Community Games Marketplace

This is a clean restart of the repository that was used to run the Community Games Marketplace.
I created a new repository using the last version of the project because some of the previous commits accidentally included personal data.
While the marketplace was live, this was a private repository hosted on Bitbucket. However, after closing it down, I decided to make the repository public so others can reference how the features were deployed.

## Custom Marketplace Features
* Sellers could manage their inventory through these functions:
    * Route::get('sell', 'SellController@allInventory');
    * Route::post('sell/registering', 'UserController@processSellerRegistration');
    * Route::post('sell/remove', 'SellController@deleteInventory');
    * Route::get('sell/orders', 'SellController@orderDetails');
    * Route::post('sell/updateShipment', 'SellController@itemShipped');
* Buyers could find and purchase items through these functions:
    * Route::get('cart', 'BuyController@shoppingCart');
    * Route::post('addToCart', 'BuyController@addToCart');
    * Route::post('updateCart', 'BuyController@updateCart');
    * Route::get('checkout', 'BuyController@previewOrder');
    * Route::post('payment', 'PaypalController@postPayment');
* Buyers and sellers could message each other through the marketplace
    * Route::get('mail/inbox', 'MailController@messages');
    * Route::post('mail/send', 'MailController@sendMessage');
    * Route::post('mail/users', 'UserController@userIds');

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)

## Official Laravel Documentation

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing powerful tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).
