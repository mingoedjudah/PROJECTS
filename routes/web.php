<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NotificationController;

//view welcome page
Route::get('/', function () {
return view('index');
});

Route::get('login', [UserController:: class, 'loginPage'])->name('login')->middleware(\App\Http\Middleware\PreventBackHistory::class);  //view login page
Route::post('login', [UserController:: class, 'loginPost'])->name('login-post'); //login post
Route::get('sign-up', [UserController:: class, 'signUpPage'])->name('sign-up')->middleware(\App\Http\Middleware\PreventBackHistory::class); //view sign up page
Route::post('sign-up', [UserController:: class, 'storeUserDetails'])->name('store-user-details.post'); //sign up post
Route::post('/check-username-email', [UserController::class, 'checkUsernameEmail']); //check the username and email post

Route::middleware(['auth'])->group(function () {

    //Home
    Route::get('user/home', [UserController:: class, 'homePage'])->name('user.home'); //view home  page
    Route::post('posts', [PostController::class, 'storePost'])->name('posts.store'); //store post
    Route::get('/posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit'); //show edit form
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update'); //update post
    Route::delete('posts/delete/{user_post_id}', [PostController::class, 'destroy'])->name('posts.destroy'); // delete a post
    Route::post('/follow/{userId}', [UserController::class, 'follow'])->name('follow'); //follow user
    Route::post('/unfollow/{userId}', [UserController::class, 'unfollow'])->name('unfollow'); //unfollow user
    Route::post('/logout', [UserController::class, 'userLogout'])->name('logout'); //user logout post

    //Profile
    Route::get('user/profile', [ProfileController::class, 'profilePage'])->name('user.profile'); //view profile page
    Route::get('user/edit-profile', [ProfileController::class, 'editProfilePage'])->name('user.edit-profile'); //view profile page
    Route::post('user/update-profile', [ProfileController::class, 'updateProfile'])->name('user.update-profile');
    Route::get('user/profile-user/{id}', [ProfileController::class, 'viewProfileUSer'])->name('user.profile-user');
    
    //Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store'); //store comments
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/like/toggle', [CommentController::class, 'toggleLike'])->name('like.toggle'); //like post
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');

    //Reply
    Route::post('/comments/{comment}/reply', [CommentController::class, 'storeReply'])->name('comments.reply');
    Route::get('/comments/{comment}/replies', [CommentController::class, 'getReplies'])->name('comments.getReplies');
    Route::get('/comments/{comment}/reply-count', [CommentController::class, 'getReplyCount'])->name('comments.getReplyCount');
    Route::put('/replies/{id}', [CommentController::class, 'updateReply'])->name('reply.update');
    Route::delete('/replies/{id}', [CommentController::class, 'destroyReply'])->name('reply.destroy');

    Route::put('/comments/{comment}/replies/{reply}', [CommentController::class, 'updateReply'])->name('comments.updateReply');
    Route::delete('/comments/{comment}/replies/{reply}', [CommentController::class, 'destroyReply']);

    //Notifications
    Route::get('/notifications', [CommentController::class, 'showNotifications'])->name('notifications');
    // Route::post('/notifications/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/check', [NotificationController::class, 'checkNotifications']);
    Route::post('/notifications/mark-read', [NotificationController::class, 'markAllAsRead']);

    //Friends
    Route::get('user/friends', [FriendController::class, 'friendsPage'])->name('user.friends'); //view friends page
    Route::post('/friends/follow/{userId}', [FriendController::class, 'follow'])->name('friends.follow'); //follow user 
    Route::post('/friends/unfollow/{userId}', [FriendController::class, 'unfollow'])->name('friends.unfollow'); //unfollow user

    //Search User
    Route::get('/search', [UserController::class, 'search'])->name('search');

});

