<?php

use App\Http\Controllers\ArticlesCategoriesController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\AudiosCategoriesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BooksCategoriesController;
use App\Http\Controllers\DownloadAudioController;
use App\Http\Controllers\DownloadBookController;
use App\Http\Controllers\DownloadelderController;
use App\Http\Controllers\DownloadImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

use App\Http\Controllers\ElderController;
use App\Http\Controllers\FavirateAudioController;
use App\Http\Controllers\FavirateBookController;
use App\Http\Controllers\FavirateelderController;
use App\Http\Controllers\FavirateimageController;
use App\Http\Controllers\ImageCategoriesController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Models\BooksCategories;
use App\Models\downloaded;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::controller(UserController::class)->prefix('/user/')->group(function () {
        Route::get('get_all', 'get_user_all');
        Route::post('get_id', 'get_id');
        Route::post('Get_id_favirate_audio/{id}', 'Get_id_favirate_audio');
        Route::post('Get_id_favirate_image/{id}', 'Get_id_favirate_image');
        Route::post('Get_id_favirate_Books/{id}', 'Get_id_favirate_Books');
        Route::post('Get_id_favirate_ELder/{id}', 'Get_id_favirate_ELder');


        Route::get('logout', 'logout');
    });
});


Route::controller(UserController::class)->prefix('/Auth/')->group(function () {
    Route::post('Register', 'Register');
    Route::post('Login', 'Login');
});

// elder Controller
Route::controller(ElderController::class)->prefix('/Elders/')->group(function () {
    // // this create data with database
    // Route::post('insert',  'store');
    // this all data from database
    // Route::get('get', 'Get');
    // get data use just id
    Route::post('Get_data_elder_id', 'Id_Data_elder');

    Route::post('Get_Audio_Id_Elder', 'Get_Audio_Id_Elder');

    Route::get('Get', 'Elders_Approve');
    // this get id Book -> details Elder
    // Route::post('get_id_Elder_All_Books', 'Get_books_elder_id');
    // get audio relation -> elder  all => Id

    // Edit ELDER And Update
    // Route::post('/update_elder/{id}',  'Update_Elder');

    // Route::get('/editelder/{id}/edit',  'Edit_Elder');

    // Route::get('get_Articles/{id}',  'get_Articles');

    // Route::post('Delete','Delete');


});
// PDF file Controller
Route::controller(BookController::class)->prefix('/Books/')->group(function () {
    // get all data Books
    Route::get('Get',  'Get');
    // file upload
    // Route::post('Insert', 'store')->name('file.upload');
    // get elder -> Book
    Route::post('Get_data_id',  'GetDataId');
    // get data use just id
    Route::post('Get_data_elder_id', 'Id_Data_elder');
    // Edit Book And Update
    // Route::post('Edit_Book',  'Edit_Book');
    // Route::post('Update_Book',  'Update_Book');
    // Route::post('Delete','Delete');


    // this all Elders Approve data from database

});

Route::controller(BooksCategoriesController::class)->prefix('/Books-Categories')->group(function () {
    // Route::post('insert','insert');
    Route::get('Get', 'get');
    Route::post('Get_Books', 'Get_Books');
    // Route::post('Delete','Delete');


});


Route::controller(AudiosCategoriesController::class)->prefix('/Audios-Categories/')->group(function () {
    // Route::post('Insert','Store');
    // Route::post('Update','Update');
    // Route::post('Delete','Delete');
    Route::Get('Get', 'Get');
    Route::post('Get_audios_with_category', 'Get_audios_with_category');
});

// Audio Controller
Route::controller(AudioController::class)->prefix('/Audios/')->group(function () {
    // // this create data with database
    Route::post('insert', 'store');
    Route::post('store_visit/{id}', 'store_visit');
    // // get all audio from DB
    Route::post('Get_id', 'Get_id');

    // // this get id Audio -> details Elder
    // Route::get('getid_Audio','Get_id');
    // // edit and update Audio
    // Route::post('Edit','edit');

    // // update-
    // Route::post('Update_Audio/{id}' , 'update_Audio');
    Route::Get('Get', 'Audios_public');
    Route::post('Audios_public_id', 'Audios_public_id');
});





Route::group(['controller' => ImageCategoriesController::class, 'prefix' => '/ImagesCategories/'], function () {
    Route::get('Get', 'Get');
    Route::post('Get-images-from-category', 'Get_data_from_Images');
});



Route::controller(ImageController::class)->prefix('/Images/')->group(function () {
    // Route::post('Insert','Store');
    // Route::post('Update','Update');
    // Route::post('Delete','Delete');
    Route::Get('Get', 'Get_public');
});


Route::controller(ArticlesController::class)->prefix('/Articles/')->group(function () {
    // Route::post('Insert','Store');
    // Route::post('Update/','Update');
    Route::post('Get-id', 'Get_public_Id');
    Route::Get('Get', 'Get_public');
});

Route::group(['controller' => ArticlesCategoriesController::class, 'prefix' => '/Articles-Categories/'], function () {
    Route::post('Get-Articles-From-Category', 'Get_Articles_public_From_Category');
    Route::get('Get', 'Get');
});
// Route Contact US
Route::group(['controller' => MessageController::class, 'prefix' => '/Message/'], function () {
    Route::post('create-message', 'create_message');

    Route::post('deleteMessage', 'deleteMessage');
});





Route::group(['controller' => DownloadAudioController::class, 'prefix' => '/download/'], function () {
    Route::post('Donwload-Audio', 'Donwload');
    Route::get('getAudioData', 'getAudioData');
});

Route::group(['controller' => DownloadImageController::class, 'prefix' => '/download/'], function () {
    Route::post('Download-Image', 'DownloadImage');
    Route::get('getImage', 'getImage');
});
Route::group(['controller' => DownloadBookController::class, 'prefix' => '/download/'], function () {
    Route::post('Download-Book', 'DownloadBook');
    Route::get('getBook', 'getBook');
});
Route::group(['controller' => DownloadelderController::class, 'prefix' => '/download/'], function () {
    Route::post('Download-Elder', 'DownloadElder');
    Route::get('getElder', 'getElder');
});



// Favirate
Route::controller(FavirateelderController::class)->prefix('/Favirateelder/')->group(function () {
    Route::post('favirateElder', 'favirateElder');
});


Route::controller(FavirateimageController::class)->prefix('/Favirateimage/')->group(function () {
    Route::post('Favirateimage', 'Favirateimage');
});


Route::controller(FavirateBookController::class)->prefix('/FavirateBook/')->group(function () {
    Route::post('FavirateBook', 'FavirateBook');
});

Route::controller(FavirateAudioController::class)->prefix('/FavirateAudio/')->group(function () {
    Route::post('FavirateAudio', 'FavirateAudio');
});