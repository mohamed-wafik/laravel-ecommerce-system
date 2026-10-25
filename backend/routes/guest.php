<?php

use App\Http\Controllers\Guest\CatalogController;
use Illuminate\Support\Facades\Route;


Route::get('categories', [CatalogController::class, 'categories']);
Route::get("categories/parent",[CatalogController::class, 'parentCategory']);
Route::get('brands', [CatalogController::class, 'brands']);
Route::get('products', [CatalogController::class, 'products']);
Route::get('products/top-deals', [CatalogController::class, 'topDeals']);
Route::get('products/{slug}', [CatalogController::class, 'product']);
Route::get('products/{productId}/reviews', [CatalogController::class, 'reviews']);
Route::get('search', [CatalogController::class, 'search']);