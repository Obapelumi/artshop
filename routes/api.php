<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/* Authentication Routes */
Route::post('signin', 'AuthController@signin');
Route::post('signup', 'AuthController@directSignup');


/* Utility Routes */
Route::get('get-countries', function() {
	$URL = 'https://restcountries.eu/rest/v2/all';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$URL");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	$headers = array();
	$headers[] = "Authorization: Bearer ";
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    return curl_error($ch);
	}
	curl_close ($ch);
	return $result;
});

Route::prefix('mail')->group(function () {
    Route::get('order/{id}', 'MailController@orderConfirmation');
    Route::post('product-purchase', 'MailController@vendorProductPurchased');
	Route::get('news-letter', 'NewsLetterController@send');
	Route::post('password-reset', 'AuthController@resetPassword');
	Route::post('verify-email', 'AuthController@verifyEmail');
	Route::get('verification-code/{email}', 'MailController@sendVerificationCode');
	Route::post('subscribe', 'NewsLetterController@subscribe');
});

Route::resource('file', 'FileController', ['except' =>['index', 'create', 'edit']]);

/*Auth required */
Route::group(['middleware' => 'auth:api'], function(){
	Route::resource('admin', 'AdminController', ['except' =>['create', 'edit']]);
	Route::post('cart', 'CartController@store');
	Route::delete('delete-tag', 'blog\BlogController@destroyBlogTag');
	Route::resource('news-letter', 'NewsLetterController', ['except' =>['create', 'edit']]);
	Route::resource('order', 'OrderController', ['except' =>['create', 'edit']]);
	Route::resource('product', 'ProductController', ['except' =>['index', 'create', 'edit']]);
	Route::delete('signout', 'AuthController@signout');
	Route::get('user/{id}', 'AuthController@getDetails');
	Route::put('user', 'AuthController@updateUser');
	Route::get('config', function () {
		$PK = config('app.paystackPK');
		$URL = config('app.url');
		return response()->json(['status' => true,  
                                 'PAYSTACK_PK' => $PK], 200);
	});
});


/* Shop Routes */
Route::resource('tag', 'TagController', ['except' =>['create', 'edit']]);
Route::resource('category', 'CategoryController', ['except' =>['create', 'edit']]);
Route::resource('customer', 'CustomerController', ['except' =>['create', 'edit']]);
Route::resource('discount', 'DiscountController', ['except' =>['create', 'edit']]);
Route::delete('discount-product/{id}', 'DiscountController@removeProduct');
Route::post('products', 'ProductController@index')->name('product.index');
Route::post('product/category', 'CategoryController@product');
Route::resource('review', 'ReviewController');
Route::prefix('search')->group(function () {
	Route::post('product', 'ProductController@search');
	Route::post('vendor', 'VendorController@search');
	Route::post('blog', 'blog\BlogController@search');
});
Route::resource('vendor', 'VendorController', ['except' =>['create', 'edit']]);


/* Blog Routes */
Route::resource('blog', 'blog\BlogController', ['except' =>['create', 'edit']]);
Route::get('blog-tags', 'blog\BlogController@blogTags');
Route::resource('blogger', 'blog\BloggerController', ['except' =>['create', 'edit']]);
Route::resource('comment', 'blog\CommentController', ['except' =>['create', 'edit', 'index', 'edit', 'update']]);
// Route::get('file/{filename}', 'FileController@show');
// Route::post('file', 'FileController@createFile');
// Route::delete('file/{id}', 'FileController@deleteFile');


