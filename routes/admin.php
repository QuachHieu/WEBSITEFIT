<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
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
/**---------------------------------------------------------------------------------------------------------------------------
 *                          ADMIN USER ROLE MANAGE
 * ----------------------------------------------------------------------------------------------------------------------------*/
Route::group(['namespace' => 'Admin'], function () {
    // Login
    Route::get('/login', 'LoginController@index')->name('admin.login');
    Route::post('/login', 'LoginController@login')->name('admin.login.post');
    // Authentication middleware
    Route::group(['middleware' => ['auth:admin']], function () {
        // Logout
        Route::get('/logout', 'LoginController@logout')->name('admin.logout');
        // Dashboard
        Route::get('/', 'HomeController@index')->name('admin.home');
        // All route in admin system CRUD
        Route::resources([
            'admins' => 'AdminController',
            'admin_menus' => 'AdminMenuController',
            'modules' => 'ModuleController',
            'module_functions' => 'ModuleFunctionController',
            'roles' => 'RoleController',
            'blocks' => 'BlockController',
            'block_contents' => 'BlockContentController',
            'pages' => 'PageController',
            'menus' => 'MenuController',
            'options' => 'OptionController',
            'widgets' => 'WidgetController',
            'components' => 'ComponentController',
            'component_configs' => 'ComponentConfigController',
            'widget_configs' => 'WidgetConfigController',

            'cms_taxonomys' => 'CmsTaxonomyController',
            'cms_services' => 'CmsServiceController',
            'cms_resources' => 'CmsResourceController',
            'cms_posts' => 'CmsPostController',
            'cms_products' => 'CmsProductController',
            'contacts' => 'ContactController',
            'online_exchange' => 'OnlineExchangeController',
            'live_reporting' =>  'LiveReportingController',
            'popups' => 'PopupController',
            'comment' => 'CommentController',
            'history' => 'HistoryController',
            'users' => 'UsersController',
            'introduction' => 'IntroductionController',
            'royaltie' => 'RoyaltieController',
            'department' => 'DepartmentController',
            'cms_function' => 'CmsFunctionController',
            'degree' => 'DegreeController',
            'cms_document' => 'CmsDocumentController',
            'cms_message' => 'CmsMessageController',
            'departments' => 'DepartmentController',
            'degree'=>'DegreeController',
            'function'=>'CmsFunctionController',
            'events' => 'EventController',
            'budget' => 'BudgetController',
        ]);
        
        // Lấy tin nhắn
        Route::post('cms_message/admins', 'CmsMessageController@listMessage')->name('cms_message.admin');

        // Load nhân sự theo phòng ban
        Route::post('admins/load_member', 'AdminController@loadMember')->name('admins.load_member');

        Route::get('cms_posts_featured', 'CmsPostController@listFeatured')->name('cms_posts_featured.index');
        Route::post('cms_posts/update_sort', 'CmsPostController@updateSort')->name('cms_posts.update_sort');
        // Tin crawler
		Route::post('cms_posts/load_crawler', 'CmsPostController@loadCrawler')->name('cms_posts.load_crawler');
		
        Route::post('cms_posts/load_featured', 'CmsPostController@loadFeatured')->name('cms_posts.load_featured');
        Route::post('cms_posts/post_relative', 'CmsPostController@postRelative')->name('cms_posts.post_relative');
        Route::post('cms_posts/update_featured', 'CmsPostController@updateFeatured')->name('cms_posts.update_featured');
        Route::post('cms_posts/update_position', 'CmsPostController@updatePosition')->name('cms_posts.update_position');
        Route::post('cms_posts/update_order', 'CmsPostController@updateOrder')->name('cms_posts.update_order');
        Route::post('cms_posts/update_comment_status', 'CommentController@updateStatus')->name('cms_posts.update_comment_status');
        Route::post('cms_posts/update_royaltie', 'RoyaltieController@updateStatus')->name('cms_posts.update_royalties');
        Route::post('live_reporting/create_comment', 'LiveReportingController@createComment')->name('live_reporting.create_comment');
		Route::post('live_reporting/update_live', 'LiveReportingController@updateStatus')->name('live_reporting.update_live');
        Route::post('online_exchange/create_comment', 'OnlineExchangeController@createComment')->name('online_exchange.create_comment');
        Route::post('online_exchange/status_comment', 'OnlineExchangeController@updateStatusComment')->name('online_exchange.status_comment');

        // Order services
        Route::get('order_services', 'OrderController@listOrderService')->name('order_services.index');
        Route::get('order_services/{order}', 'OrderController@showOrderService')->name('order_services.show');
        Route::put('order_services/{order}', 'OrderController@update')->name('order_services.update');
        Route::delete('order_services/{order}', 'OrderController@destroy')->name('order_services.destroy');
        // Order Products
        Route::get('order_products', 'OrderController@listOrderProduct')->name('order_products.index');
        Route::get('order_products/{order}', 'OrderController@showOrderProduct')->name('order_products.show');
        Route::put('order_products/{order}', 'OrderController@update')->name('order_products.update');
        Route::delete('order_products/{order}', 'OrderController@destroy')->name('order_products.destroy');
        Route::put('order_details/{orderDetail}', 'OrderDetailController@update')->name('order_details.update');
        Route::delete('order_details', 'OrderDetailController@destroy')->name('order_details.destroy');
        // Call request
        Route::get('call_request', 'ContactController@listCallRequest')->name('call_request.index');
        Route::get('call_request/{contact}', 'ContactController@showCallRequest')->name('call_request.show');
        Route::put('call_request/{contact}', 'ContactController@update')->name('call_request.update');
        Route::delete('call_request/{contact}', 'ContactController@destroy')->name('call_request.destroy');

        // Get params block for update Page management
        Route::get('get_block_params', 'BlockController@getBlockParams')->name('blocks.params');
        Route::get('get_block_contents_by_template', 'BlockContentController@getBlockContentsByTemplate')->name('block_contents.get_by_template');
        // Sort menu in module update menu public
        Route::post('menus/update_sort', 'MenuController@updateSort')->name('menus.update_sort');
        Route::post('menus/delete', 'MenuController@delete')->name('menus.delete');

        // Config to use laravel-filemanager
        Route::group(['prefix' => 'filemanager'], function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        });

        // Update information web to Option table
        Route::put('web/update/{id}', 'OptionController@webUpdate')->name('web.update');
        Route::get('web_information', 'OptionController@webInformation')->name('web.information');
        Route::get('web_image', 'OptionController@webImage')->name('web.image');
        Route::get('web_social', 'OptionController@webSocial')->name('web.social');
        Route::get('web_source', 'OptionController@webSource')->name('web.source');

        // Test export excel
        Route::get('export', 'ExportController@export')->name('export');
    });
});
