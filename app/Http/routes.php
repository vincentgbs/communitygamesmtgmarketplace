<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('mtg/list/livesearch/page');
});

Route::get('home', 'BuyController@index');
Route::get('cart', 'BuyController@shoppingCart');
Route::post('update_cart_preview', 'BuyController@cartPreview');
Route::post('addToCart', 'BuyController@addToCart');
Route::post('updateCart', 'BuyController@updateCart');
Route::get('checkout', 'BuyController@previewOrder');
Route::post('address', 'BuyController@processAddress');
Route::post('cart_subtotal', 'BuyController@cartSubtotal');
Route::get('orders', 'OrderController@orders');
Route::get('order', 'OrderController@orderDetails');
Route::get('order/feedback', 'OrderController@feedback');
Route::post('order/process_feedback', 'OrderController@addFeedback');
Route::get('sell', 'SellController@allInventory');
Route::post('sell/registering', 'UserController@processSellerRegistration');
Route::post('sell/remove', 'SellController@deleteInventory'); // ajax function
Route::get('sell/orders', 'SellController@orderDetails');
Route::post('sell/updateShipment', 'SellController@itemShipped'); // ajax function
Route::get('blogs/{id?}', 'MtgController@blogs');
Route::get('blog/edit', 'BlogController@edit');
Route::post('blog/post', 'BlogController@processPost');

Route::get('mail/inbox', 'MailController@messages');
Route::post('mail/send', 'MailController@sendMessage');
Route::post('mail/users', 'UserController@userIds');

Route::get('user_agreement', function () { return view('policy/buyers'); });
Route::get('contact_us', function () { return view('messages/contactForm'); });
Route::post('process_contact', 'MailController@submitForm');

Route::post('mtg/names', 'MtgController@names'); // ajax function
Route::post('mtg/cardids', 'MtgController@cardIds'); // ajax function
Route::get('mtg', 'MtgController@index');
Route::match(array('GET', 'POST'), 'mtg/cards/{id?}', 'MtgController@cards');
Route::get('mtg/sets/{id?}', 'MtgController@sets');
Route::get('mtg/sellers/{id?}', 'MtgController@sellers');
Route::post('mtg/next_cards', 'MtgController@nextCards'); // ajax function
Route::post('mtg/buy_card_preview', 'MtgController@buyCardPreview'); // ajax function

Route::get('mtg/sell', 'SellController@mtgInventory');
Route::post('mtg/update_inventory', 'SellController@updateMtgInventory');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::post('payment', array(
    'as' => 'payment',
    'uses' => 'PaypalController@postPayment',
));
Route::get('payment/status', array(
    'as' => 'payment.status',
    'uses' => 'PaypalController@getPaymentStatus',
));
