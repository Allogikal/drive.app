<?php

use Inertia\Inertia;
use App\Models\Review;
use App\Models\UserImage;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\RestaurantImage;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\UserImageController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuPositionController;

// ALIASES FOR ROUTING
Route::aliasMiddleware('admin', IsAdmin::class);

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return Inertia::render('HomeView');
})->name('home');

Route::get('/places', function (Request $request) {
    $restaurants = Restaurant::with([
        'user',
        'images',
        'menuPositions',
        'reviews.user'
    ])->get();

    $favouriteRestaurantIds = Auth::check()
        ? Auth::user()->favourites->pluck('restaurant_id')->toArray()
        : [];

    return Inertia::render('PlacesView', [
        'places' => $restaurants,
        'favouriteRestaurantIds' => $favouriteRestaurantIds,
        'currentRoute' => $request->route()->getName(),
        'searchQuery' => $request->query('search') ?? '',
    ]);
})->name('places');

Route::get('/map', function () {
    $restaurants = Restaurant::with([
        'user',
        'images',
        'menuPositions',
        'reviews.user'
    ])->get();

    return Inertia::render('MapView', [
        'places' => $restaurants
        ]);
})->name('map');

Route::get('/gallery', function () {
    $images = UserImage::where('status', 'active')
        ->with(['user.restaurants'])
        ->orderBy('updated_at', 'desc')
        ->take(14)
        ->get();

    return Inertia::render('GalleryView', [
        'images' => $images,
    ]);
})->name('gallery');

Route::get('/place/{id}', [RestaurantController::class, 'show'])
    ->name('place-profile');

// AUTH

Route::get('/register', function () {
    return Inertia::render('Auth/RegisterView');
})->name('register')->middleware('guest');

Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', function () {
    return Inertia::render('Auth/LoginView');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/generate-captcha', [AuthController::class, 'generateCaptcha'])->name('generate-captcha');

// ADMIN

Route::middleware(['admin'])->group(function () {
    Route::get('/admin-applications', [AdminController::class, 'applications'])
    ->name('admin-applications');

    Route::post('/admin/applications/{id}/approve', [AdminController::class, 'approveApplication'])
    ->name('admin.approve-application');
    Route::post('/admin/applications/{id}/reject', [AdminController::class, 'rejectApplication'])
        ->name('admin.reject-application');
    
    Route::get('/admin-users', [AdminController::class, 'users'])
    ->name('admin-users');

    Route::post('/admin/users/{id}/block', [AdminController::class, 'blockUser'])->name('admin.block-user');
    Route::post('/admin/users/{id}/unblock', [AdminController::class, 'unblockUser'])->name('admin.unblock-user');  
    
    Route::get('/admin-places', [AdminController::class, 'places'])->name('admin-places');
    
    Route::get('/admin-reviews', [ReviewController::class, 'index'])->name('admin-reviews');
    Route::delete('/admin/reviews/{id}', [ReviewController::class, 'destroy'])
    ->name('admin.review.delete');
    
    Route::get('/admin-gallery', [AdminController::class, 'gallery'])
    ->name('admin-gallery');
    Route::post('/admin/gallery/{id}/accept', [AdminController::class, 'acceptUserImage'])
    ->name('admin.accept-user-image');
    Route::post('/admin/gallery/{id}/reject', [AdminController::class, 'rejectUserImage'])
    ->name('admin.reject-user-image');
});

// GUARD

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', function () {
        return Inertia::render('ProfileView')->with([
            'user' => auth()->user(),
            'favourites' => auth()->user()->favourites()->with(['restaurant' => function ($query) {
                $query->with('images');
            }])->get(),
            'reviews' => Review::with(['restaurant' => function ($query) {
                $query->with('images');
            }])
            ->where('user_id', auth()->id())
            ->get(),
            'userImages' => auth()->user()->images()->get(),
            'visited' => auth()->user()->restaurantsVisited()->with('images')->get()
        ]);
    })->name('profile');

    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    Route::get('/create-place', function () {
        return Inertia::render('CreatePlaceView');
    })->name('create-place');

    Route::post('/restaurants', [RestaurantController::class, 'store'])
    ->name('restaurants.store');

    Route::delete('/restaurants/{id}', [RestaurantController::class, 'destroy'])
    ->name('restaurants.destroy');

    Route::get('/edit-place/{id}', [RestaurantController::class, 'edit'])
        ->name('edit-place');

    Route::post('/edit-place/{id}', [RestaurantController::class, 'update'])
        ->name('edit-place.update');

    Route::post('/favourites/add/{restaurantId}', [FavouriteController::class, 'add'])
        ->name('favourites.add');

    Route::delete('/favourites/remove/{restaurantId}', [FavouriteController::class, 'remove'])
        ->name('favourites.remove');

    Route::get('/place/{id}/review/create', [ReviewController::class, 'create'])
        ->name('review.create');

    Route::post('/place/{id}/review', [ReviewController::class, 'store'])
        ->name('review.store');

    Route::post('/restaurants/{restaurant}/menu/cocktail', [MenuPositionController::class, 'store'])
    ->name('menu.cocktail.store');

    Route::post('/restaurants/{restaurant}/menu/main-course', [MenuPositionController::class, 'store'])
    ->name('menu.main-course.store');

    Route::post('/restaurants/{id}/visit', [RestaurantController::class, 'visit'])
    ->name('restaurants.visit');

    Route::delete('/restaurants/{id}/unvisit', [RestaurantController::class, 'unvisit'])
        ->name('restaurants.unvisit');

    Route::post('/user/upload-image', [UserImageController::class, 'store'])
    ->name('user.upload-image');
});