<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\LayoutsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MenuController;
use App\Models\Pages;
use App\Models\PagesTranslations;
use App\Models\Settings;
use Faker\Guesser\Name;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
Auth::routes();
Route::get("/optimize",function (){
    
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    // Artisan::call('optimize', ['--quiet' => true]);
     return redirect()->route('my-main-home');
 });
Route::get('/download',[SettingsController::class,'download_code'])->name("setting.code-website");
Route::get('/clear',[SettingsController::class,'clear_website'])->name("setting.clear-website");
$selected_page = PagesTranslations::where('link', 'index')->where("status", 1)->pluck("link")->first();
$setting_language = explode(",", Settings::where('setting', 'language')->pluck("value")->first());
$selected_page_extension = Settings::where('setting', 'page_extension')->pluck("value")->first();
$allpages = PagesTranslations::where("status", 1)
->whereIn('locale', $setting_language)
    ->get();
if ($selected_page == "" && $selected_page == null) {
    Route::get('/', function () {
        return view('welcome');
    })->name("my-main-home");
}

foreach ($allpages as $allpage) {
    if ($selected_page == $allpage->link) {
        Route::get("/", [FrontController::class, 'index'])->name("my-main-home");
    } else {
        Route::get("/$allpage->link" . $selected_page_extension, [FrontController::class, 'index'])->name("$allpage->link");
    }
}
Route::get('/index', function () {
    return redirect()->route('my-main-home');
});
Route::get('/index.html', function () {
    return redirect()->route('my-main-home');
});
Route::get('/page-notfound', [FrontController::class, 'page_notfound'])->name('front.page-notfound');
$all_previewpages = PagesTranslations::where("status", 0)
    ->whereIn('locale', $setting_language)
    ->get();
foreach ($all_previewpages as $allpage) {
    Route::get("/preview/{id}/{connect_same}", [FrontController::class, 'inactive_index']);
}
Route::group(["prefix" => "admin", "middleware" => ["auth", "PreventBackHistory"]], function () {
    Route::get("/", [AdminController::class, 'index'])->name("admin.dashboard");
    Route::get("/profile", [AdminController::class, 'profile'])->name("admin.profile");
    Route::post("/profile-update", [AdminController::class, 'store'])->name("admin.profile-update");
    // Pages Start
    Route::get("/pages", [PagesController::class, 'index'])->name("page.all");
    Route::get("/page/{id}", [PagesController::class, 'index'])->name("page.inner");
    Route::get("/active-pages", [PagesController::class, 'active_page'])->name("page.active");
    Route::get("/seo-pages", [PagesController::class, 'seo_page'])->name("page.seo");
    Route::post("/seo-pages/store", [PagesController::class, 'seo_store'])->name("page.seo-store");
    Route::get("/pages/create", [PagesController::class, 'create'])->name("page.create");
    Route::post("/pages/store", [PagesController::class, 'store'])->name("page.store");
    Route::get("/pages/all-delete/{id}", [PagesController::class, 'destroy'])->name("page.all-delete");
    Route::get("/pages/edit/{id}/{connect_same}", [PagesController::class, 'edit'])->name("page.edit");
    Route::post("/pages/update/{id}/{connect_same}", [PagesController::class, 'update'])->name("page.update");
    Route::get("/pages/clone/{id}/{connect_same}", [PagesController::class, 'clone'])->name("page.clone");
    Route::get("/pages/delete/{id}/{connect_same}", [PagesController::class, 'deleted'])->name("page.delete");
    Route::get("/pages/status/{id}/{connect_same}", [PagesController::class, 'status'])->name("page.status");
    Route::get("/pages/edit-grapejs/{id}/{connect_same}", [PagesController::class, 'edit_grapejs'])->name("page.edit-grapejs");
    Route::post("/pages/edit-grapejs/upload", [PagesController::class, 'upload_image'])->name('page.upload');
    Route::post("/pages/edit-grapejs/remove", [PagesController::class, 'remove_image'])->name('page.remove-image');
    Route::get("/pages/edit-grapejs/fetch-images", [PagesController::class, 'fetch_image'])->name('page.fetch-images');
    Route::post("/pages/edit-grapejs/save/{id}/{connect_same}", [PagesController::class, 'saveDesign'])->name('page.save_grapejs');
    // Pages End
    // Settings  Start
    Route::get("/settings", [SettingsController::class, 'create'])->name("setting.update");
    Route::post("/settings/update", [SettingsController::class, 'store'])->name("setting.store");
    

    // Settings End
    Route::get("/menu", [MenuController::class, 'index'])->name("menu.all");
    Route::get("/menu/create", [MenuController::class, 'create'])->name("menu.create");
    Route::get("/menu/edit/{id}", [MenuController::class, 'edit'])->name("menu.edit");
    Route::get("/menu/delete/{id}", [MenuController::class, 'delete'])->name("menu.delete");
    Route::post("/menu/store", [MenuController::class, 'store'])->name("menu.store");
    Route::post("/menu/update/{id}", [MenuController::class, 'update'])->name("menu.update");
    Route::get("/menu/status/{id}", [MenuController::class, 'status'])->name("menu.status");
    // Layout Start
    Route::get("/layouts", [LayoutsController::class, 'index'])->name("layout.all");
    Route::get("/layouts/create", [LayoutsController::class, 'create'])->name("layout.create");
    Route::post("/layouts/store", [LayoutsController::class, 'store'])->name("layout.store");
    Route::get("/layouts/delete/{id}", [LayoutsController::class, 'destroy'])->name("layout.delete");
    Route::get("/layouts/edit/{id}", [LayoutsController::class, 'edit'])->name("layout.edit");
    Route::post("/layouts/update/{id}", [LayoutsController::class, 'update'])->name("layout.update");
    // Layout End
});
