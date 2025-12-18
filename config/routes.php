<?php

// Auth routes
$router->get('/', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/logout', 'AuthController@logout');

// Home
$router->get('/home', 'RepairsController@home');

// Repairs routes (formerly Fix)
$router->get('/repairs', 'RepairsController@index');
$router->get('/repairs/create', 'RepairsController@create');
$router->get('/repairs/{id}', 'RepairsController@show');
$router->post('/repairs/check-customer', 'RepairsController@checkCustomer');
$router->post('/repairs/new-customer', 'RepairsController@storeNewCustomer');
$router->post('/repairs/existing-customer', 'RepairsController@storeExistingCustomer');
$router->post('/repairs/{id}/status', 'RepairsController@updateStatus');

// Inventory routes (formerly Parts)
$router->get('/inventory', 'InventoryController@index');
$router->get('/inventory/create', 'InventoryController@create');
$router->get('/inventory/{id}', 'InventoryController@show');
$router->post('/inventory', 'InventoryController@store');
$router->get('/supplier/create', 'InventoryController@addSupplier');
$router->post('/supplier', 'InventoryController@storeSupplier');

// Purchase routes
$router->get('/purchase', 'PurchaseController@index');
$router->get('/purchase/create', 'PurchaseController@create');
$router->get('/purchase/{id}', 'PurchaseController@show');
$router->post('/purchase', 'PurchaseController@store');
$router->post('/purchase/{id}/activate', 'PurchaseController@activate');
$router->post('/purchase/{id}/pay', 'PurchaseController@markPaid');
$router->post('/purchase/{id}/receive', 'PurchaseController@receive');
$router->post('/purchase/parts-by-supplier', 'PurchaseController@getPartsBySupplier');

