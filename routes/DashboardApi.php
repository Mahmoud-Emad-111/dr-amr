<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ArticlesCategoriesController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\AudiosCategoriesController;
use App\Http\Controllers\ElderController;
use App\Http\Controllers\ImageCategoriesController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MainCategoriesBookController;
use App\Http\Controllers\MessageController;
use App\Models\Audio;
use App\Models\Image;
use Illuminate\Support\Facades\Route;


Route::controller(ElderController::class)->prefix('/Elders/')->group(function () {
    //  create data with database
    Route::post('Insert',  'store');
    // this all Elders data from database
    Route::get('Get', 'Get');
    // get data use just id
    Route::post('Get_data_elder_id', 'Id_Data_elder');
    // Elder Update
    Route::post('update_elder',  'Update_Elder');
    // Elder Delete
    // this all Elders Approve data from database
    Route::get('Elders_Approve', 'Elders_Approve');


    Route::post('Delete', 'Delete');
    // get audio relation -> elder  all => Id
    Route::post('Get_Audio_Id_Elder', 'Get_Audio_Id_Elder');
    // // this get id Book -> details Elder
    // Route::post('get_id_Elder_All_Books', 'Get_books_elder_id');

    // Route::get('/editelder/{id}/edit',  'Edit_Elder');

    // Route::get('get_Articles/{id}',  'get_Articles');


});

Route::controller(AudiosCategoriesController::class)->prefix('/Audios-Categories/')->group(function () {
    Route::post('Insert', 'Store');
    Route::Get('Get', 'Get');
    Route::post('Update', 'Update');
    Route::post('Delete', 'Delete');
    Route::post('Get_audios_with_category', 'Get_audios_with_category');
});

// Audio Controller
Route::controller(AudioController::class)->prefix('/Audios/')->group(function () {
    // this create data with database
    Route::post('insert', 'store');
    // get all audio from DB
    Route::get('Get', 'Get_audio');
    // this get id Audio -> details Elder
    Route::post('Get_id', 'Get_id');
    // update-
    Route::post('Update', 'update_Audio');
    // edit and update Audio
    Route::post('Edit', 'edit');
});
// Admin Controller
Route::group(['controller' => AdminController::class, 'prefix' => '/admin/'], function () {
    // Admin Login
    Route::post('login', 'login');
    // Admin Register
    Route::post('register', 'register');
});

// Imagecategory Controller
Route::group(['controller' => ImageCategoriesController::class, 'prefix' => '/ImagesCategories/'], function () {
    Route::post('Insert', 'Store');
    Route::get('Get', 'Get');
    Route::post('Update', 'Update');
});


// Image Controller
Route::group(['controller' => ImageController::class, 'prefix' => '/Images/'], function () {
    Route::post('Insert', 'Store');
    Route::post('Update', 'Update');
    Route::post('Delete', 'Delete');

    // Route::post('Get-images-from-category','Get_data_from_Images');
    Route::get('Get', 'Get');
});

// Articles Categories Controller
Route::group(['controller' => ArticlesCategoriesController::class, 'prefix' => '/Articles-Categories/'], function () {
    Route::post('Insert', 'Store');
    Route::post('Get-Articles-From-Category', 'Get_Articles_From_Category');
    Route::get('Get', 'Get');
    Route::post('Update', 'Update');
    Route::post('Delete', 'Delete');
});


// Articles Categories Controller
Route::group(['controller' => ArticlesController::class, 'prefix' => '/Articles/'], function () {
    Route::post('Insert', 'Store');
    Route::post('Update', 'Update');
    Route::post('Delete', 'Delete');
    Route::post('Get-id', 'Get_Id');
    Route::get('Get', 'Get');
});


// Main Categories books Controller
Route::group(['controller' => MainCategoriesBookController::class, 'prefix' => '/Main-Categories-Books/'], function () {
    Route::post('Insert', 'Store');
    Route::post('Update', 'Update');
    Route::post('Delete', 'Delete');
    Route::post('Get-id', 'Get_Id');
    Route::get('Get', 'Get');
});

Route::group(['controller' => MessageController::class, 'prefix' => '/Message/'], function () {
    Route::post('edit_Message', 'edit_Message');
    Route::post('update_Message', 'update_Message');
    Route::get('get_message', 'get_message');
});
