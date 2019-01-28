<?php

/*
* Homepage
*/

Route::get('/', [
  'uses' => '\Webcraft\Http\Controllers\HomeController@getIndex',
  'as' => 'home',
  'middleware' => ['auth']
]);

/*
* Authentication
*/

Route::get('/aramiza-katil', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getIndex',
  'as' => 'auth.index',
  'middleware' => ['guest']
]);

Route::get('/sifremi-unuttum', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getForgotPassword',
  'as' => 'auth.forgot_password',
  'middleware' => ['guest']
]);

Route::post('/sifremi-unuttum', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@postForgotPassword',
  'middleware' => ['guest']
]);

Route::get('/sifremi-unuttum/yeni/{email}', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getForgotNewPassword',
  'as' => 'auth.forgot_password.new',
  'middleware' => ['guest']
]);

Route::post('/sifremi-unuttum/yeni', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@postForgotNewPassword',
  'middleware' => ['guest']
]);

Route::get('/kaydi-onayla/{email?}', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getVerify',
  'as' => 'auth.verify'
]);

Route::get('/hos-geldin', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getWelcome',
  'as' => 'auth.welcome'
]);

Route::post('/signup', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@postSignup',
  'as' => 'auth.signup',
  'middleware' => ['guest']
]);

Route::post('/signin', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@postSignin',
  'as' => 'auth.signin',
  'middleware' => ['guest']
]);

Route::get('/cikis-yap', [
  'uses' => '\Webcraft\Http\Controllers\AuthController@getSignout',
  'as' => 'auth.signout',
  'middleware' => ['auth']
]);

/*
* Profile
*/

Route::get('/oyuncu/{player}', [
  'uses' => '\Webcraft\Http\Controllers\ProfileController@getIndex',
  'as' => 'profile',
  'middleware' => ['auth']
]);

Route::get('/oyuncu/{player}/oldurme-detaylari', [
  'uses' => '\Webcraft\Http\Controllers\ProfileController@getDetailKill',
  'as' => 'profile.killed',
  'middleware' => ['auth']
]);

Route::get('/oyuncu/{player}/olum-detaylari', [
  'uses' => '\Webcraft\Http\Controllers\ProfileController@getDetailDeath',
  'as' => 'profile.death',
  'middleware' => ['auth']
]);

Route::get('/oyuncu/{player}/chest', [
  'uses' => '\Webcraft\Http\Controllers\ProfileController@getChest',
  'as' => 'profile.chest',
  'middleware' => ['auth']
]);

Route::post('/oyuncu/{player}/chest/sell', [
  'uses' => '\Webcraft\Http\Controllers\ChestController@postSellItem',
  'as' => 'profile.chest.sell',
  'middleware' => ['auth']
]);

Route::post('/oyuncu/{player}/chest/sell_modal', [
  'uses' => '\Webcraft\Http\Controllers\ChestController@postSellItemModal',
  'as' => 'profile.chest.sell_modal',
  'middleware' => ['auth']
]);

/*
* Account
*/

Route::get('/hesap', [
  'uses' => '\Webcraft\Http\Controllers\AccountController@getAccount',
  'as' => 'account',
  'middleware' => ['auth']
]);

Route::get('/hesap/bildirimler', [
  'uses' => '\Webcraft\Http\Controllers\AccountController@getNotifications',
  'as' => 'account.notifications',
  'middleware' => ['auth']
]);

/*
* Friend
*/

Route::post('/oyuncu/arkadas/ekle', [
  'uses' => '\Webcraft\Http\Controllers\FriendController@postAddFriend',
  'as' => 'friend.add',
  'middleware' => ['auth']
]);

Route::post('/oyuncu/arkadas/kabul-et', [
  'uses' => '\Webcraft\Http\Controllers\FriendController@postAcceptFriend',
  'as' => 'friend.accept',
  'middleware' => ['auth']
]);

Route::post('/oyuncu/arkadas/sil', [
  'uses' => '\Webcraft\Http\Controllers\FriendController@postDeleteFriend',
  'as' => 'friend.delete',
  'middleware' => ['auth']
]);

/*
* Credit
*/

Route::get('/kredi-yukle', [
  'uses' => '\Webcraft\Http\Controllers\PaymentController@getIndex',
  'as' => 'payment',
  'middleware' => ['auth']
]);

Route::post('/payment/listener', [
  'uses' => '\Webcraft\Http\Controllers\PaymentController@postListener',
  'as' => 'payment.listener'
]);

Route::post('/payment/send', [
  'uses' => '\Webcraft\Http\Controllers\PaymentController@postSend',
  'as' => 'payment.send'
]);

Route::get('/odeme/basarili', [
  'uses' => '\Webcraft\Http\Controllers\PaymentController@getSuccess',
  'as' => 'payment.success',
  'middleware' => ['auth']
]);

Route::get('/odeme/basarisiz', [
  'uses' => '\Webcraft\Http\Controllers\PaymentController@getError',
  'as' => 'payment.error',
  'middleware' => ['auth']
]);

/*
* Market
*/

Route::get('/community/market', [
  'uses' => '\Webcraft\Http\Controllers\CommunityMarketController@getIndex',
  'as' => 'community.market',
  'middleware' => ['auth']
]);

Route::post('/community/market/buy', [
  'uses' => '\Webcraft\Http\Controllers\CommunityMarketController@postBuy',
  'as' => 'community.market.buy',
  'middleware' => ['auth']
]);

/*
* Upgrade Account
*/

Route::get('/hesabimi-yukselt', [
  'uses' => '\Webcraft\Http\Controllers\UpgradeController@getIndex',
  'as' => 'upgrade',
  'middleware' => ['auth']
]);

Route::post('/group/buy', [
  'uses' => '\Webcraft\Http\Controllers\UpgradeController@postBuy',
  'as' => 'group.buy',
  'middleware' => ['auth']
]);

/*
* Groups
*/

Route::post('/group/info', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@postInfo',
  'as' => 'group.info',
  'middleware' => ['auth']
]);

Route::post('/group/new', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@postNew',
  'as' => 'group.new',
  'middleware' => ['auth', 'admin']
]);

Route::get('/group/delete/{id}', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@getDelete',
  'as' => 'group.delete',
  'middleware' => ['auth', 'admin']
]);

Route::post('/group/new/feature', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@postNewFeature',
  'as' => 'group.new.feature',
  'middleware' => ['auth', 'admin']
]);

Route::get('/group/delete/feature/{id}', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@getDeleteFeature',
  'as' => 'group.delete.feature',
  'middleware' => ['auth', 'admin']
]);

Route::post('/group/new/command', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@postNewCommand',
  'as' => 'group.new.command',
  'middleware' => ['auth', 'admin']
]);

Route::get('/group/delete/command/{id}', [
  'uses' => '\Webcraft\Http\Controllers\GroupController@getDeleteCommand',
  'as' => 'group.delete.command',
  'middleware' => ['auth', 'admin']
]);

/*
* Users
*/

Route::get('/oyuncular', [
  'uses' => '\Webcraft\Http\Controllers\UserController@getUsers',
  'as' => 'users',
  'middleware' => ['auth']
]);

/*
* Top 100
*/

Route::get('/hit/en-iyiler', [
  'uses' => '\Webcraft\Http\Controllers\HitController@getBest100',
  'as' => 'top.best',
  'middleware' => ['auth']
]);

/*
* Statuses
*/

Route::get('/status/{id}', [
  'uses' => '\Webcraft\Http\Controllers\StatusController@getStatus',
  'as' => 'status',
  'middleware' => ['auth']
]);

Route::post('/status', [
  'uses' => '\Webcraft\Http\Controllers\StatusController@postStatus',
  'as' => 'status.post',
  'middleware' => ['auth']
]);

Route::post('/status/delete', [
  'uses' => '\Webcraft\Http\Controllers\StatusController@postDelete',
  'as' => 'status.delete',
  'middleware' => ['auth']
]);

Route::post('/status/like', [
  'uses' => '\Webcraft\Http\Controllers\StatusController@postLike',
  'as' => 'status.like',
  'middleware' => ['auth']
]);

/*
* Comments
*/

Route::post('/status/comment', [
  'uses' => '\Webcraft\Http\Controllers\CommentController@postComment',
  'as' => 'status.comment',
  'middleware' => ['auth']
]);

Route::post('/status/comment/like', [
  'uses' => '\Webcraft\Http\Controllers\CommentController@postLike',
  'as' => 'status.comment.like',
  'middleware' => ['auth']
]);

/*
* Bug
*/

Route::post('/bug', [
  'uses' => '\Webcraft\Http\Controllers\BugController@post',
  'as' => 'bug'
]);