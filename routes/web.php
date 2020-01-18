<?php
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Model\Comment;
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


Route::group(['namespace' => 'Home'], function(){
    
   Route::get('/login_view','UserController@login_view')->name("login");
   Route::post('/login','UserController@login');
   Route::get('/reg_view','UserController@register_view');
   Route::post('/reg','UserController@register');
   Route::get('/user/set_view','UserController@user_set_view');
   Route::post('/user/set','UserController@user_set')->middleware("auth:user");
   Route::get('/user/home','UserController@user_home')->middleware("auth:user");
   Route::get('/user/index','UserController@user_index')->middleware("auth:user");
   Route::get('/user/index/myComment', 'UserController@user_index_myComment')->middleware("auth:user");
   Route::get('/user/index/myCollectPost', 'UserController@user_index_myCollectPost')->middleware("auth:user");
   Route::get('/user/index/myCollectLabel', 'UserController@user_index_myCollectLabel')->middleware("auth:user");
   Route::get('/user/logout','UserController@logout')->middleware("auth:user");
   Route::post('/user/changePassword','UserController@changePassword')->middleware("auth:user");
   Route::any('/user/upload_avatar','UserController@upload_avatar')->middleware("auth:user");
   Route::get('/user/collectLable','UserController@collectLable')->middleware("auth:user");
   Route::get('/user/deleteComment','UserController@deleteComment')->middleware("auth:user");
   Route::get('/user/userMessage','UserController@userMessage')->middleware("auth:user");
   Route::get('/user/userMessage/commentMessage','UserController@userCommentMessage')->middleware("auth:user");
   Route::get('/user/userMessage/postCommentMessage','UserController@userPostCommentMessage')->middleware("auth:user");
   Route::get('/user/userMessage/systemMessage','UserController@userSystemMessage')->middleware("auth:user");
   Route::get('/user/followAuthor','UserController@followAuthor')->middleware("auth:user");
    Route::get('/user/showPostCommentList','UserController@showPostCommentList');
    Route::get('/user/handleFriendApply','UserController@handleFriendApply')->middleware("auth:user");
    Route::get('/user/showPrivateMessageView','UserController@showPrivateMessageView')->middleware("auth:user");
    Route::post('/user/sendPrivateMessage','UserController@sendPrivateMessage')->middleware("auth:user");
    Route::get('/user/showPrivateMessageList','UserController@showPrivateMessageList')->middleware("auth:user");
    Route::get('/user/privateMessageReply','UserController@showPrivateMessageReply')->middleware("auth:user");
    Route::post('/user/replyPrivateMessage','UserController@replyPrivateMessage')->middleware("auth:user");
    Route::get('/user/userFriend', 'UserController@userFriend');
    Route::get('/user/userFriend/follow', 'UserController@userFollow')->middleware("auth:user");
    Route::get('/user/cancelFollow', 'UserController@cancelFollow')->middleware("auth:user");
    Route::get('/user/cancelFriend', 'UserController@cancelFriend')->middleware("auth:user");
    Route::get('/user/changeIsRead', 'UserController@changeIsRead')->middleware("auth:user");
    Route::get('/user/cancleLabel', 'UserController@cancleLabel')->middleware("auth:user");

   Route::get('/','ForumController@index');
   Route::get('/flowListPost', 'ForumController@flowListPost');
   Route::get('/colIndex', 'ForumController@colIndex');
   Route::get('/lableIndex', 'ForumController@lableIndex');
   Route::get('/listCol', 'ForumController@listCol');
   Route::get('/indexSearch', 'ForumController@indexSearch');


   Route::get('/user/addFriendApply', 'UserController@addFriendApply')->middleware("auth:user");
   Route::get('/user/verifiedEmail', 'UserController@verifiedEmail');

    Route::get('/listLable', 'PostController@getLableByCol');
    Route::get("/post/addPostView","PostController@addPostView")->middleware("auth:user");
    Route::post("/post/createPost","PostController@createPost")->middleware("auth:user");
    Route::any('/post/avatarSave','PostController@avatarSave')->middleware("auth:user");
    Route::any('/post/videoSave','PostController@videoSave')->middleware("auth:user");
    Route::get('/authorInfo','PostController@authorHome')->middleware("auth:user");
    Route::get('/postDetail','PostController@postDetail');
    Route::get('/showCommentList','PostController@showCommentList');
    Route::post('/saveComment','PostController@saveComment')->middleware("auth:user");
    Route::post('/saveReplyComment', 'PostController@saveReplyComment')->middleware("auth:user");
    Route::post('/commentPraise','PostController@commentPraise')->middleware("auth:user");
    Route::post('/PostPraise','PostController@PostPraise')->middleware("auth:user");
   Route::get('/post/collectPost', 'PostController@collectPost')->middleware("auth:user");
   Route::get('/post/editPost', 'PostController@editPost')->middleware("auth:user");
   Route::post('/post/updatePost', 'PostController@updatePost')->middleware("auth:user");
   Route::get('/post/deletePost', 'PostController@deletePost')->middleware("auth:user");
   Route::get('/post/isFriend', 'PostController@isFriend')->middleware("auth:user");
});
