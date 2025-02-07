<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\OrganizationController;

// Группа маршрутов для API с middleware проверки API-ключа
Route::group(['middleware' => 'api.key'], function () {
    // Список зданий
    Route::get('/buildings', [BuildingController::class, 'index']);
    
    // Получение организации по ID
    Route::get('/organizations/{id}', [OrganizationController::class, 'show'])->where('id', '[0-9]+');
    
    // Список организаций по зданию
    Route::get('/organizations/by-building/{buildingId}', [OrganizationController::class, 'getByBuilding']);
    
    // Список организаций по деятельности (ID)
    Route::get('/organizations/by-activity/{activityId}', [OrganizationController::class, 'getByActivity']);
    
    // Список организаций по геолокации (lat, lng, radius)
    Route::get('/organizations/by-location', [OrganizationController::class, 'getByLocation'])->name('organizations.byLocation');
    
    // Поиск организаций по виду деятельности
    Route::get('/organizations/search/activity', [OrganizationController::class, 'searchByActivity']);
    
    // Поиск организаций по названию
    Route::get('/organizations/search/name', [OrganizationController::class, 'searchByName']);
});
