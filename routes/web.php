<?php

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

Route::get('/', function () {
    return redirect('admin/login');
    //return view('welcome');
});

Route::group(['middleware' => ['web','\crocodicstudio\crudbooster\middlewares\CBBackend']], function() {
    
    //menu items
    Route::get('/admin/menu_items/upload-view','AdminMenuItemsController@uploadView')->name('menu-items.view');
    Route::get('/admin/menu_items/upload-update-view','AdminMenuItemsController@uploadUpdateView')->name('menu-items.update-view');
    Route::get('/admin/menu_items/upload-template','AdminMenuItemsController@uploadTemplate')->name('menu-items.template');
    Route::get('/admin/menu_items/upload-update-template','AdminMenuItemsController@uploadUpdateTemplate')->name('menu-items.update-template');
    Route::post('/admin/menu_items/upload','AdminMenuItemsController@uploadItems')->name('menu-items.upload');
    Route::post('/admin/menu_items/export','AdminMenuItemsController@exportItems')->name('menu-items.export');
    
    //item master
    Route::get('admin/item_masters/getBrandData/{id}','AdminItemMastersController@getBrandData')->name('getBrandData');
    Route::get('admin/item_masters/getEdit/{id}','AdminItemMastersController@getEdit');
    //subcategory master
    Route::get('admin/subcategories/getCategoryCode/{id}','AdminSubcategoriesController@getCategoryCode');
    //item export
	Route::post('admin/item_masters/bartender','AdminItemMastersController@exportBartender')->name('bartender');
	Route::post('admin/item_masters/posformat','AdminItemMastersController@exportPOSFormat')->name('posformat');
	Route::post('admin/item_masters/qbformat','AdminItemMastersController@exportQBFormat')->name('qbformat');
	Route::post('admin/item_masters/item-export','AdminItemMastersController@exportItems')->name('export-items');
	
	//----added by cris 20201006----
    
    Route::get('/admin/item_masters/update-imfs','AdminItemMastersController@updateImfs')->name('updateImfs');
    Route::post('/admin/item_masters/update-imfs-upload','AdminItemMastersController@imfsUpdate')->name('update.imfs');
    
    Route::get('/admin/suppliers/update-vendor','AdminSuppliersController@updateVendor')->name('updateVendor');
    Route::post('/admin/suppliers/update-vendor-upload','AdminSuppliersController@vendorUpdate')->name('update.vendor');

    Route::get('/admin/customer_myobs/update-customer','AdminCustomerMyobsController@updateCustomer')->name('updateCustomer');
    Route::post('/admin/customer_myobs/update-customer-upload','AdminCustomerMyobsController@customerUpdate')->name('update.customer');
    //-----------------------------
	
	Route::get('admin/customer_myobs/export-trs','AdminCustomerMyobsController@customExportExcelTRS');
    
    Route::get('/admin/item_masters/upload-module','AdminItemMastersController@getUploadModule')->name('getUploadModule');
    
    Route::get('/admin/history_item_masterfile/export-ttp-history','AdminHistoryItemMasterfileController@exportSalePrice')->name('exportSalePrice');
    Route::get('/admin/history_item_masterfile/export-purchase-price-history','AdminHistoryItemMasterfileController@exportPurchasePrice')->name('exportPurchasePrice');
    
    //bulk upload fulfillment type
    Route::post('/admin/item_masters/update-items-upload','ItemUploadController@store')->name('update.imfs');
    Route::get('/admin/item_masters/update-items','ItemUploadController@create')->name('getUpdateItems');
    Route::get('/admin/item_masters/download-item-template','ItemUploadController@downloadItemTemplate')->name('downloadItemTemplate');
    
    //bulk upload segmentation
    Route::post('/admin/item_masters/upload_sku_legend','ItemSegmentationUploadController@store')->name('uploadSKULegend');
    Route::get('/admin/item_masters/upload-items-sku-legend','ItemSegmentationUploadController@create')->name('getUpdateItemsSkuLegend');
    Route::get('/admin/item_masters/download-sku-template','ItemSegmentationUploadController@downloadSKULegendTemplate')->name('downloadSKULegendTemplate');
    
    //bulk upload cost
    Route::post('/admin/item_masters/upload-items-costing','ItemPriceUploadController@store')->name('uploadCostPrice');
    Route::get('/admin/item_masters/update-items-price','ItemPriceUploadController@create')->name('getUpdateItemsPrice');
    Route::get('/admin/item_masters/download-price-template','ItemPriceUploadController@downloadPriceTemplate')->name('downloadPriceTemplate');
});