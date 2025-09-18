<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiControllers\Admin\HeaderController;
use App\Http\Controllers\ApiControllers\Admin\NavbarController;
use App\Http\Controllers\ApiControllers\Admin\PermissionsController;
use App\Http\Controllers\ApiControllers\Admin\PortFollioController;
use App\Http\Controllers\ApiControllers\Admin\RolesController;
use App\Http\Controllers\ApiControllers\Admin\UsersController;
use App\Http\Controllers\ApiControllers\ProductController;
use App\Http\Controllers\ApiControllers\Testimonials;
use App\Http\Controllers\ApiControllers\UserController;
use App\Http\Controllers\ContactUSController;
use App\Models\ContactUS;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::get('/approval', [ApiAuthController::class, 'Approval']);
    Route::post('/add', [ProductController::class, 'addProduct']);
    Route::post('/list', [ProductController::class, 'list_Products']);
    Route::delete('/delete/{id}', [ProductController::class, 'delete_Product']);
    Route::put("/UpdateProduct/{id}", [ProductController::class, 'updateProduct']);
});

Route::middleware(['auth:api', 'can:view_permissions_gate'])->group(function () {
    Route::get('/Permissions', [PermissionsController::class, 'index']);
});

//permissions route protect
Route::middleware(['auth:api', 'can:create_permissions_gate'])->group(function () {
    Route::post('/Permissions', [PermissionsController::class, 'store']);
});

Route::middleware(['auth:api', 'can:delete_permissions_gate'])->group(function () {
    Route::delete('/Permissions/{id}/delete', [PermissionsController::class, 'destroy']);
});

Route::middleware(['auth:api', 'can:update_permissions_gate'])->group(function () {
    Route::get('/Permissions/{id}', [PermissionsController::class, 'edit']);
    Route::patch('/Permissions/{id}', [PermissionsController::class, 'update']);
});

Route::middleware(['auth:api', 'can:view_roles_gate'])->group(function () {
    Route::get('/Roles', [RolesController::class, 'index']);
    Route::get('/Roles/create', [RolesController::class, 'create']);
});

Route::middleware(['auth:api', 'can:create_roles_gate'])->group(function () {
    Route::post('/Roles/create', [RolesController::class, 'store']);
});

Route::middleware(['auth:api', 'can:delete_roles_gate'])->group(function () {
    Route::delete('/Roles/{id}/delete', [RolesController::class, 'destroy']);
});

Route::middleware(['auth:api', 'can:update_roles_gate'])->group(function () {
    Route::get('/roles/{role}/edit', [RolesController::class, 'edit']);
    Route::patch('/roles/{role}', [RolesController::class, 'update']);
});

//users protected route
Route::middleware(['auth:api','can:create_users_gate'])->group(function () {
    Route::get('/users/create',[UsersController::class,'create']);
    Route::post('/users',[UsersController::class,'store']);
});

Route::middleware(['auth:api','can:view_users_gate'])->group(function () {
    Route::get('/users',[UsersController::class,'index']);
    Route::get('/users/{id}',[UsersController::class,'show']);
});

Route::middleware(['auth:api','can:update_users_gate'])->group(function () {
    Route::get('/users/{user}/edit',[UsersController::class,'edit']);
    Route::PATCH('/users/{user}',[UsersController::class,'update']);
});


Route::middleware(['auth:api', 'can:delete_users_gate'])->group(function () {
    Route::delete('/Users/{id}/delete', [UsersController::class, 'destroy']);

});

Route::middleware(['auth:api', 'can:create_projects_gate'])->group(function () { //remind me to create permissions for this
Route::post('/upload_files',[PortFollioController::class,'uploadfiles']);
});

Route::middleware(['auth:api', 'can:delete_projects_gate'])->group(function () { //remind me to create permissions for this
 Route::delete('/Projects/{id}/delete',[PortFollioController::class,'deleteit']);
});

Route::middleware(['auth:api', 'can:view_projects_gate'])->group(function () { //remind me to create permissions for this
    Route::get('/ViewProjects',[PortFollioController::class,'main']);
});




//for navbar
Route::middleware(['auth:api', 'can:create_navbar_gate'])->group(function () { //remind me to create permissions for this
    Route::Post('/admin/navbar/create',[NavbarController::class,'store']);
});
Route::middleware(['auth:api', 'can:view_navbar_gate'])->group(function () { //remind me to create permissions for this
    Route::get('/admin/navbar/view/',[NavbarController::class,'view']);


});
Route::middleware(['auth:api', 'can:update_navbar_gate'])->group(function () { //remind me to create permissions for this
    Route::get('/admin/navbar/{id}',[NavbarController::class,'edit']);
    Route::post('/admin/navbar/{id}/edit',[NavbarController::class,'update']);

});

Route::middleware(['auth:api', 'can:delete_navbar_gate'])->group(function () { //remind me to create permissions for this
    Route::delete('/admin/Portfollio/Navbar/{id}/delete',[NavbarController::class,'destroy']);
});







//for headers
Route::middleware(['auth:api', 'can:create_header_gate'])->group(function () {
    Route::post('/admin/Portfollio/Headers/create',[HeaderController::class,'store']);
});

Route::middleware(['auth:api', 'can:view_header_gate'])->group(function () {
    Route::get('/admin/Portfollio/Headers/view',[HeaderController::class,'view']);
});


Route::middleware(['auth:api', 'can:update_header_gate'])->group(function () {
    Route::get('/admin/Portfollio/Headers/{id}/edit',[HeaderController::class,'edit']);
    Route::post('/admin/Portfollio/Headers/{id}/update',[HeaderController::class,'update']);
});

Route::middleware(['auth:api', 'can:delete_header_gate'])->group(function () {
    Route::delete('/admin/Portfollio/Headers/{id}/delete',[HeaderController::class,'destroy']);
});




//testimonials
Route::middleware(['auth:api', 'can:create_testimonial_gate'])->group(function () {
    Route::post('/admin/Portfollio/Testimonials/create',[Testimonials::class,'store']);
});

Route::middleware(['auth:api', 'can:view_testimonial_gate'])->group(function () {
    Route::get('/admin/Portfollio/Testimonials/view',[Testimonials::class,'view']);
});

Route::middleware(['auth:api', 'can:update_testimonial_gate'])->group(function () {
    Route::get('/admin/Portfollio/Testimonials/edit/{id}', [Testimonials::class, 'edit']);
    Route::post('/admin/Portfollio/Testimonials/update/{id}', [Testimonials::class, 'update']);
});

Route::middleware(['auth:api', 'can:delete_testimonial_gate'])->group(function () {
    Route::DELETE('/admin/Portfollio/Testimonials/{id}/delete',[Testimonials::class,'destroy']);
});

//contact Us
Route::middleware(['auth:api', 'can:view_contactus_gate'])->group(function () {
    Route::get('/admin/Portfollio/ContactUs',[ContactUSController::class,'index']);

});

Route::middleware(['auth:api', 'can:delete_contactus_gate'])->group(function () {
    Route::delete('/admin/Portfollio/ContactUs/{id}/delete',[ContactUSController::class,'destroy']);
});








//others


Route::post('/contact',[ContactUSController::class,'store']);
Route::get('/notifications', [ContactUSController::class, 'indexall']);
Route::put('/notifications/mark-as-read', [ContactUSController::class, 'markAsRead']);
Route::post('/notify', [ContactUSController::class, 'notify']);




Route::get('/admin/Portfollio/Headers',[HeaderController::class,'index']);

Route::get('/admin/navbar',[NavbarController::class,'index']);

Route::get('/Projects',[PortFollioController::class,'index']);

Route::get('/img',[PortFollioController::class,'test']);


Route::get("/product/{id}", [ProductController::class, 'getProduct']);

Route::post('/register', [UserController::class, 'register']);

Route::get('/data', [Testimonials::class, 'index']);
