<?php

use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\LanguageController;
use App\Livewire\Privacy;
use App\Livewire\Terms;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SellersConfController;
use App\Http\Controllers\Admin\AnnouncesController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Broadcast;

/*
*
* Auth Routes
*
* --------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/

// home route
Route::get('home', [FrontendController::class, 'index'])->name('home');

// Language Switch
Route::get('language/{language}', [LanguageController::class, 'switch'])->name('language.switch');

Route::get('dashboard', 'App\Http\Controllers\Frontend\FrontendController@index')->name('dashboard');

// pages
Route::get('terms', Terms::class)->name('terms');
Route::get('privacy', Privacy::class)->name('privacy');

Route::group(['namespace' => 'App\Http\Controllers\Frontend', 'as' => 'frontend.'], function () {
    Route::get('/', 'FrontendController@index')->name('index');

    Route::group(['middleware' => ['auth']], function () {
        /*
        *
        *  Users Routes
        *
        * ---------------------------------------------------------------------
        */
        $module_name = 'users';
        $controller_name = 'UserController';
        Route::get('profile/edit', ['as' => "{$module_name}.profileEdit", 'uses' => "{$controller_name}@profileEdit"]);
        Route::patch('profile/edit', ['as' => "{$module_name}.profileUpdate", 'uses' => "{$controller_name}@profileUpdate"]);
        Route::get('profile/changePassword', ['as' => "{$module_name}.changePassword", 'uses' => "{$controller_name}@changePassword"]);
        Route::patch('profile/changePassword', ['as' => "{$module_name}.changePasswordUpdate", 'uses' => "{$controller_name}@changePasswordUpdate"]);
        Route::get('profile/{username?}', ['as' => "{$module_name}.profile", 'uses' => "{$controller_name}@profile"]);
        Route::get("{$module_name}/emailConfirmationResend", ['as' => "{$module_name}.emailConfirmationResend", 'uses' => "{$controller_name}@emailConfirmationResend"]);
        Route::delete("{$module_name}/userProviderDestroy", ['as' => "{$module_name}.userProviderDestroy", 'uses' => "{$controller_name}@userProviderDestroy"]);

        /*
        *  Chat Routes
        */
        Route::get('/chat', ['as' => 'chat.index', 'uses' => 'ChatController@index']);
        Route::get('/chat/{user}', ['as' => 'chat.show', 'uses' => 'ChatController@show']);
        Route::post('/chat/{user}', ['as' => 'chat.store', 'uses' => 'ChatController@store']);
        Route::post('/chat/{user}/read', ['as' => 'chat.read', 'uses' => 'ChatController@markAsRead']);
        Route::get('/chat/unread-count', ['as' => 'chat.unread', 'uses' => 'ChatController@getUnreadCount']);

        // Broadcasting Authentication
        Broadcast::routes();
    });
});

/*
*
* Admin Routes
* These routes need view-backend permission
* --------------------------------------------------------------------
*/
Route::group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'can:view_backend']], function () {
    /**
     * Admin Dashboard
     * Namespaces indicate folder structure.
     */
    Route::get('/', 'AdminController@index_dashboard')->name('home');
    Route::get('dashboard', 'AdminController@index_dashboard')->name('dashboard');

    /*
     *
     *  Settings Routes
     *
     * ---------------------------------------------------------------------
     */
    Route::group(['middleware' => ['can:edit_settings']], function () {
        $module_name = 'settings';
        $controller_name = 'SettingController';
        Route::get("{$module_name}", "{$controller_name}@index")->name("{$module_name}.index");
        Route::post("{$module_name}", "{$controller_name}@store")->name("{$module_name}.store");

        // Универсальные маршруты для всех типов фильтров
        Route::get('/sellers_conf', [\App\Http\Controllers\Admin\SellersConfController::class, 'index']);
        Route::post('/sellers_conf/{type}/store', [\App\Http\Controllers\Admin\SellersConfController::class, 'store']);
        Route::put('/sellers_conf/{type}/update/{id}', [\App\Http\Controllers\Admin\SellersConfController::class, 'update']);
        Route::delete('/sellers_conf/{type}/delete/{id}', [\App\Http\Controllers\Admin\SellersConfController::class, 'delete']);
    });

    /*
    *
    *  Notification Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'notifications';
    $controller_name = 'NotificationsController';
    Route::get("{$module_name}", ['as' => "{$module_name}.index", 'uses' => "{$controller_name}@index"]);
    Route::get("{$module_name}/markAllAsRead", ['as' => "{$module_name}.markAllAsRead", 'uses' => "{$controller_name}@markAllAsRead"]);
    Route::delete("{$module_name}/deleteAll", ['as' => "{$module_name}.deleteAll", 'uses' => "{$controller_name}@deleteAll"]);
    Route::get("{$module_name}/{id}", ['as' => "{$module_name}.show", 'uses' => "{$controller_name}@show"]);

    /*
    *
    *  Backup Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'backups';
    $controller_name = 'BackupController';
    Route::get("{$module_name}", ['as' => "{$module_name}.index", 'uses' => "{$controller_name}@index"]);
    Route::get("{$module_name}/create", ['as' => "{$module_name}.create", 'uses' => "{$controller_name}@create"]);
    Route::get("{$module_name}/download/{file_name}", ['as' => "{$module_name}.download", 'uses' => "{$controller_name}@download"]);
    Route::get("{$module_name}/delete/{file_name}", ['as' => "{$module_name}.delete", 'uses' => "{$controller_name}@delete"]);

    /*
    *
    *  Roles Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'roles';
    $controller_name = 'RolesController';
    Route::resource("{$module_name}", "{$controller_name}");

    /*
    *
    *  Users Routes
    *
    * ---------------------------------------------------------------------
    */
    $module_name = 'users';
    $controller_name = 'UsersController';
    Route::get("{$module_name}/{id}/resend-email-confirmation", ['as' => "{$module_name}.emailConfirmationResend", 'uses' => "{$controller_name}@emailConfirmationResend"]);
    Route::delete("{$module_name}/user-provider-destroy", ['as' => "{$module_name}.userProviderDestroy", 'uses' => "{$controller_name}@userProviderDestroy"]);
    Route::get("{$module_name}/{id}/change-password", ['as' => "{$module_name}.changePassword", 'uses' => "{$controller_name}@changePassword"]);
    Route::patch("{$module_name}/{id}/change-password", ['as' => "{$module_name}.changePasswordUpdate", 'uses' => "{$controller_name}@changePasswordUpdate"]);
    Route::get("{$module_name}/trashed", ['as' => "{$module_name}.trashed", 'uses' => "{$controller_name}@trashed"]);
    Route::patch("{$module_name}/{id}/trashed", ['as' => "{$module_name}.restore", 'uses' => "{$controller_name}@restore"]);
    Route::get("{$module_name}/index_data", ['as' => "{$module_name}.index_data", 'uses' => "{$controller_name}@index_data"]);
    Route::get("{$module_name}/index_list", ['as' => "{$module_name}.index_list", 'uses' => "{$controller_name}@index_list"]);
    Route::patch("{$module_name}/{id}/block", ['as' => "{$module_name}.block", 'uses' => "{$controller_name}@block", 'middleware' => ['can:block_users']]);
    Route::patch("{$module_name}/{id}/unblock", ['as' => "{$module_name}.unblock", 'uses' => "{$controller_name}@unblock", 'middleware' => ['can:block_users']]);
    Route::resource("{$module_name}", "{$controller_name}");
    Route::patch("{$module_name}/{user}/update-ajax", ['as' => "{$module_name}.updateAjax", 'uses' => "{$controller_name}@updateAjax"]);

   /**
    * Объявления
    */
    $module_name = 'announces';
    $controller_name = 'AnnouncesController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::get("$module_name/responses/{id}", ['as' => "$module_name.responses", 'uses' => "$controller_name@getResponses"]);
    Route::get("$module_name/{id}/responses", ['as' => "$module_name.responses", 'uses' => "$controller_name@responses"]);
    Route::patch("$module_name/{id}/status", ['as' => "$module_name.update_status", 'uses' => "$controller_name@updateStatus"]);
    Route::resource("$module_name", "App\Http\Controllers\Admin\AnnouncesController");

    /**
     * Платежи
     */
    $module_name = 'payments';
    $controller_name = 'PaymentsController';
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::resource('payments', PaymentsController::class)->only(['index', 'show']);

    /*
     * Responses Routes
     */
    $module_name = 'responses';
    $controller_name = 'ResponsesController';
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::resource('responses', App\Http\Controllers\Admin\ResponsesController::class);

    /*
     * Feedbacks Routes
     */
    $module_name = 'feedbacks';
    $controller_name = 'FeedbacksController';
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::resource('feedbacks', App\Http\Controllers\Admin\FeedbacksController::class);
});

/**
 * File Manager Routes.
 */
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth', 'can:view_backend']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// Маршруты чата
Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/chat/{user}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unread');
});

// Маршрут для авторизации WebSocket
// Broadcast::routes(['middleware' => ['web', 'auth']]);
