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
use App\Http\Controllers\MainCategoriesBookController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SeacrchController;
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
        // Route::get('get_all', 'get_user_all');
        // Route::post('get_id', 'get_id');
        // Route::post('Get_id_favirate_audio/{id}', 'Get_id_favirate_audio');
        // Route::post('Get_id_favirate_image/{id}', 'Get_id_favirate_image');
        // Route::post('Get_id_favirate_Books/{id}', 'Get_id_favirate_Books');
        // Route::post('Get_id_favirate_ELder/{id}', 'Get_id_favirate_ELder');

        Route::get('Profile', 'ProfileUSer');

        Route::get('Logout', 'logout');
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
    Route::post('Increase-listening', 'store_visit');
    // // get all audio from DB
    Route::post('Get_id', 'Get_id');

    Route::Get('MostListened', 'MostListened');
    Route::Get('Get', 'Audios_public');
    Route::post('Audios_public_id', 'Audios_public_id');
});





Route::group(['controller' => ImageCategoriesController::class, 'prefix' => '/ImagesCategories/'], function () {
    Route::get('Get', 'Get');
    Route::post('Get-images-from-category', 'Get_data_from_Images');
});



Route::controller(ImageController::class)->prefix('/Images/')->group(function () {

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







Route::group(['prefix'=>'/download','middleware' => 'auth:sanctum'],function(){

    Route::group(['controller' => DownloadAudioController::class], function () {
        Route::post('Donwload-Audio', 'Donwload');
        Route::get('getAudioData', 'getAudioData');
    });

    Route::group(['controller' => DownloadImageController::class], function () {
        Route::post('Download-Image', 'DownloadImage');
        Route::get('getImage', 'getImage');
    });
    Route::group(['controller' => DownloadBookController::class], function () {
        Route::post('Download-Book', 'DownloadBook');
        Route::get('getBook', 'getBook');
    });
    Route::group(['controller' => DownloadelderController::class], function () {
        Route::post('Download-Elder', 'DownloadElder');
        Route::get('getElder', 'getElder');
    });


});

Route::group(['prefix'=>'/Favorite/','middleware' => 'auth:sanctum'],function(){
    Route::controller(FavirateelderController::class)->group(function () {
        Route::post('Favorite-Elder', 'favirateElder');
        Route::get('Get_Favorite_Elder','Get_Favirate_Elder');
    });

    Route::controller(FavirateimageController::class)->group(function () {
        Route::post('Favorite-image', 'Favirateimage');
        Route::get('Get_Favorite_image', 'Get_Favirate_image');
    });


    Route::controller(FavirateBookController::class)->group(function () {
        Route::post('Favorite-Book', 'FavirateBook');
        Route::get('Get_Favorite_Books', 'Get_Favirate_Books');

    });

    Route::controller(FavirateAudioController::class)->group(function () {
        Route::post('Favorite-Audio', 'FavirateAudio');
        Route::get('Get_Favorite_Audios', 'Get_Favirate_Audios');

    });


});

// Main Categories books Controller
Route::group(['controller'=>MainCategoriesBookController::class,'prefix'=>'/Main-Categories-Books/'],function () {

    // Route::post('Get-id','Get_Id');
    Route::get('Get','Get');
});
//Categories books Controller
Route::group(['controller'=>BooksCategoriesController::class,'prefix'=>'/Categories-Books/'],function () {
    Route::get('Get','Get_Books_public');
    Route::post('Find-Book-Public','GetDataId');
    Route::get('Get-Category','Get');
});
Route::group(['controller'=>BookController::class,'prefix'=>'/Books/'],function () {
    Route::get('Get','Get_Public');
    Route::get('LatestVersionBooks','LatestVersionBooks');

    Route::post('Find-Book-Public','GetDataId');
});
// handle search
Route::controller(SeacrchController::class)->prefix('/Search/')->group(function () {
    Route::post('search_audio','search_audio');
    Route::post('search_imagecategory','search_imagecategory');
    Route::post('search_elder','search_elder');
    Route::post('search_articles','search_articles');
    Route::post('search_Book','search_Book');
});



