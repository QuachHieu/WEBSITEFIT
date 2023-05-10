<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * For check roles (permission access) for each route (function_code),
 * required each route have to a name which used to the
 * check in middleware permission and this is defined in Module, Function Management
 * @author: ThangNH
 * @created_at: 2021/10/01
 */

Route::namespace('FrontEnd')->group(function () {

  Route::get('/countmess', 'CmsController@countmess');

  Route::get('/sound', 'CmsController@sound');

  Route::get('/loadchat', 'CmsController@loadchat');

  Route::get('/sendmessage', 'CmsController@sendmessage');

  Route::get('/listchat', 'CmsController@listchat');
  
  Route::get('/countclick', 'CmsController@countclick');

  Route::get('/login', 'LoginController@index')->name('frontend.login');
  Route::post('/login', 'LoginController@login')->name('frontend.login.post');
  Route::post('/register', 'UsersController@register')->name('frontend.register');
  Route::get('/logout', 'LoginController@logout')->name('frontend.logout.post');

  Route::get('/', 'HomeController@index')->name('frontend.home');
  Route::get('search', 'CmsController@search')->name('frontend.search.index');
  
  //Lấy tất cả tin nhắn
  Route::post('message','MessageController@listMessage')->name('frontend.cms.message.list'); 
  Route::get('message','MessageController@index')->name('frontend.cms.message');

  Route::get('bac-si', 'CmsController@doctorList')->name('frontend.cms.doctor.list');

  //lấy ra tất cả tài liệu
  Route::get('dm-tai-lieu', 'CmsController@resourceCategory')->name('frontend.cms.resource_category');
  // Danh mục tài liệu
  Route::get('dm-tai-lieu/{alias}', 'CmsController@resourceCategory')->name('frontend.cms.resource_category');
  // Chi tiết tài liệu
  Route::get('tai-lieu/{alias_detail}', 'CmsController@resource')->name('frontend.cms.resource');

  Route::get('xem-them-bai-viet', 'CmsController@viewMore')->name('frontend.cms.view_more');

  // Danh mục video
  Route::get('video/{alias}', 'CmsController@postVideo')->name('frontend.cms.post_video');
  //chi tiết video
  Route::get('chi-tiet-video/{alias}', 'CmsController@videoDetail')->name('frontend.cms.post_video');

  // Danh mục hình ảnh
  Route::get('hinh-anh/{alias}', 'CmsController@postHinhanh')->name('frontend.cms.post_category');

  // Danh mục tin
  Route::get('danh-muc/{alias}', 'CmsController@postCategory')->name('frontend.cms.post_category');
  // Chi tiết tin
  Route::get('chi-tiet/{alias_detail}', 'CmsController@post')->name('frontend.cms.post');
  // Tin theo tag
  Route::get('tag/{alias_category}', 'CmsController@cmstag')->name('frontend.cms.tag');

  // Bài viết giới thiệu
  Route::get('gioi-thieu/{alias}', 'CmsController@postIntroduction')->name('frontend.cms.post_introduction');

  // Booking   
  Route::post('booking', 'BookingController@store')->name('frontend.booking.store');
  // Contact
  Route::get('lien-he', 'ContactController@index')->name('frontend.contact');
  Route::post('contact', 'ContactController@store')->name('frontend.contact.store');
  // Order
  Route::post('order-service', 'OrderController@storeOrderService')->name('frontend.order.store.service');
  // Cart
  Route::post('add-to-cart', 'OrderController@addToCart')->name('frontend.order.add_to_cart');
  Route::get('gio-hang', 'OrderController@cart')->name('frontend.order.cart');
  Route::patch('update-cart', 'OrderController@updateCart')->name('frontend.order.cart.update');
  Route::delete('remove-from-cart', 'OrderController@removeCart')->name('frontend.order.cart.remove');
  Route::post('order-product', 'OrderController@storeOrderProduct')->name('frontend.order.store.product');

  Route::get('/{alias}', 'PageController@index')->name('frontend.page');

  
  Route::group(['prefix' => 'user', 'middleware' => ['auth:web']], function () {
    
    Route::get('profile', 'UsersController@index')->name('frontend.user.index');
    Route::post('update_profile', 'UsersController@update')->name('frontend.user.update');
    Route::post('comment', 'CommentController@store')->name('frontend.comment.store');
    Route::post('addnew', 'CmsController@addnew')->name('frontend.post.addnew');
    Route::get('/logout', 'LoginController@logout')->name('frontend.logout');
  });

  Route::get('auth/google', 'LoginController@redirectToGoogle')->name('login.google');
  Route::get('auth/google/callback', 'LoginController@handleGoogleCallback'); 

  Route::get('auth/facebook', 'LoginController@redirectToFacebook')->name('login.facebook');
  Route::get('auth/facebook/callback', 'LoginController@handleFacebookCallback'); 

  Route::get('/route-cache', function() {
    \Artisan::call('route:cache');
    return 'Routes cache cleared';
});
Route::get('/clear-cache', function() {
    \Artisan::call('cache:clear');
    return 'Application cache cleared';
});
});
