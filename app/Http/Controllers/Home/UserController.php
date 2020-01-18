<?php

namespace App\Http\Controllers\Home;
use App\Model\Follow;
use App\model\Lable;
use App\Model\Message;
use http\Env\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Post;
use App\Model\Comment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Thomaswelton\LaravelGravatar\Facades\Gravatar;

class UserController extends Controller
{
    public function login_view()
    {
        return view('home.user.login');
    }

    public function login(Request $request)
    {
        $username = $request->get("username");
        $password = $request->get("password");
        $status = Auth::guard("user")->attempt([
            "username" => $username,
            "password" => $password
        ]);
        if($status){
            return redirect('/');
        }else{
            return new JsonResponse("用户名或密码错误",422);
        }
    }

    public function logout()
    {
        Auth::guard("user")->logout();
        return redirect('/');

    }

    public function register_view()
    {
        return view("home.user.reg");
    }

    public function register(Request $request)
    {
        $email = $request->get("email");
        $username = $request->get("username");
        $password = $request->get("password");
        $repassword = $request->get("repassword");
        $verification_token = str_random(30);
        $ouser = new User();
        $msg = array();
        if($ouser::where(["email" =>$email])->get()->first()){
            $msg["email"] = "该邮箱已经被注册";
        }
        if($ouser::where(["username" => $username])->get()->first()){
            $msg["username"] = "用户名已存在";
        }
        if($password!==$repassword){
            $msg["password"] = "两次输入密码不一致" ;
        }
        if($msg){
            return new JsonResponse($msg,422);
        }
        $ouser::insert([
           "email" => $email,
           "username" => $username,
           "password" => bcrypt($password),
            "avatar" => Gravatar::src($email,200),
            "verification_token" => $verification_token,
        ]);
        $this->sendEmail($email, $verification_token);
        Auth::guard("user")->attempt([
            "username" => $username,
            "password" => $password
        ]);
        return redirect('/');
    }

    /**发送验证邮件
     * @param $email
     * @param $verification_token
     */
    public function sendEmail($email, $verification_token)
    {
        $message = 'test';
        $to = $email;
        $subject = '听说网邮箱验证';
        Mail::send(
            'home.user.email',
            ['verification_token' => $verification_token],
            function ($message) use($to, $subject) {
                $message->to($to)->subject($subject);
            }
        );
        return;
    }

    public function verifiedEmail(Request $request)
    {
        $token = $request->verification_token;
        $user = User::where('verification_token', $token)->first();
        if (!empty($user->username)) {
            $user->verified = 1;
            $user->save();
            return redirect('/');
        } else {
            echo "<script>alert('验证失败')</script>";
            return;
        }
    }


    public function user_set_view()
    {
        $ouser = User::find(Auth::guard("user")->user()->id);
        return view('home.user.user_set',["ouser" => $ouser]);
    }

    public function user_set(Request $request)
    {
        $verification_token = str_random(30);
        $email = $request->get("email");
        $username = $request->get("username");
        $gender = $request->get("gender");
        $city = $request->get("city");
        $profession = $request->profession;
        $university = $request->university;
        $introduce = $request->get("introduce");
        $ouser = User::find(Auth::guard("user")->user()->id);
        if($username!==$ouser->username){
            if($ouser::where(["username" => $username])->get()->first()){
                return new JsonResponse("用户名已经存在",422);
            }
        }
        if($email != Auth::guard('user')->user()->email) {
            $this->sendEmail($email, $verification_token);
            $ouser->verified = 0;
            $ouser->verification_token = $verification_token;
        }
            $ouser->email = $email;
            $ouser->username = $username;
            $ouser->gender = $gender;
            $ouser->city = $city;
            $ouser->profession = $profession;
            $ouser->university = $university;
            $ouser->introduce = $introduce;
            $ouser->save();
        return new JsonResponse("修改成功",200);
    }

    public function upload_avatar(Request $request)
    {
        $file = $request->file("file");
        $newFileName = md5(time().rand(0,10000)).".".$file->getClientOriginalExtension();
        $savePath = "/touxiang/".$newFileName;
        $bytes = Storage::put(
            $savePath,
            file_get_contents($file->getRealPath())
        );
        if(!Storage::exists($savePath)){
            exit("保存失败!");
        }
        header("Content-Type:".Storage::mimeType($savePath));
        $ouser = User::find(Auth::guard("user")->user()->id);
        $ouser->avatar = "/uploads/touxiang/".$newFileName;
        $ouser->save();
        $msg = [
            "code" => "0",
            "msg" => "头像上传成功",
            "data" => "dfgs"
        ];
        return json_encode($msg);
    }

    public function changePassword(Request $request)
    {
        $ouser = new User();
        $user = $ouser->find(Auth::guard("user")->user()->id);
        $msg = array();
        $old = $request->get("oldPassword");
        $new = $request->get("newPassword");
        $renew = $request->get("reNewPassword");
        if(! Hash::check($old,Auth::guard("user")->user()->password)){
            $msg["oldPwdWrong"] = "密码错误";
        }
        if($new!==$renew){
            $msg["reNewWrong"] = "两次输入密码不一致";
        }
        if($msg!==[]){
            return new JsonResponse($msg,422);
        }
        $user->password = bcrypt($new);
        $user->save();
        return new JsonResponse("",200);
    }

    public function user_home()
    {
        return view('home.user.user_home');
    }

    //user_index的myPost
    public function user_index()
    {
        $myPosts = $this->myPostList()[0];
        $myPosts_count = $this->myPostList()[1];
        $myComments_count = $this->recentlyCommentList()[1];
        $collectPosts_count = $this->collectPostList()[1];
        $lables_count = Lable::whereIn('id', explode(',', Auth::guard('user')->user()->lable_collect))->get()->count();
        return view('home.user.user_index',[
            'myPosts'=>$myPosts,
            'myPost_count' => $myPosts_count,
            'myComments_count' => $myComments_count,
            'collectPosts_count' => $collectPosts_count,
            'lables_count' => $lables_count
        ]);
    }

    //我的评论
    public function user_index_myComment()
    {
        $lables_count = Lable::whereIn('id', explode(',', Auth::guard('user')->user()->lable_collect))->get()->count();
        $myPosts_count = $this->myPostList()[1];
        $myComments = $this->recentlyCommentList()[0];
        $myComments_count = $this->recentlyCommentList()[1];
        $collectPosts_count = $this->collectPostList()[1];
        return view('home.user.user_index_myComment',[
            'myComments' => $myComments,
            'myPost_count' => $myPosts_count,
            'myComments_count' => $myComments_count,
            'collectPosts_count' => $collectPosts_count,
            'lables_count' => $lables_count
        ]);
    }

    public function user_index_myCollectPost()
    {
        $lables_count = Lable::whereIn('id', explode(',', Auth::guard('user')->user()->lable_collect))->get()->count();
        $myPosts_count = $this->myPostList()[1];
        $myComments_count = $this->recentlyCommentList()[1];
        $collectPosts = $this->collectPostList()[0];
        $collectPosts_count = $this->collectPostList()[1];
        return view('home.user.user_index_collectPost',[
            'myPost_count' => $myPosts_count,
            'myComments_count' => $myComments_count,
            'collectPosts' => $collectPosts,
            'collectPosts_count' => $collectPosts_count,
            'lables_count' => $lables_count
        ]);
    }

    public function user_index_myCollectLabel()
    {
        $lables = Lable::whereIn('id', explode(',', Auth::guard('user')->user()->lable_collect))->get();
        $lables_count = $lables->count();
        $myPosts_count = $this->myPostList()[1];
        $myComments_count = $this->recentlyCommentList()[1];
        $collectPosts_count = $this->collectPostList()[1];
        return view('home.user.user_index_collectLable',[
            'myPost_count' => $myPosts_count,
            'myComments_count' => $myComments_count,
            'collectPosts_count' => $collectPosts_count,
            'lables_count' => $lables_count,
            'lables' => $lables
        ]);
    }

    /*
     * 获取我发布过的话题
     */
    public function myPostList()
    {
        $oPost = new Post();
        $id = Auth::guard('user')->user()->id;
        $posts = $oPost->where('user_id',$id)->paginate(10);
        $posts_count = $oPost->where('user_id',$id)->count();
        return [$posts, $posts_count];
    }

    /*
     * 获取最近回答的数据
     */
    public function recentlyCommentList()
    {
        $oComment = new Comment();
        $oPost = new Post();
        $id = Auth::guard('user')->user()->id;
        $comments = $oComment->where('user_id',$id)->paginate(5);
        $comments_count = $oComment->where('user_id',$id)->count();
        $data = array();
        foreach ($comments as $key => $value) {
            $data[] = $value->height_id;
        }
        $comment_message_list = $oComment->with('user')->whereIn('id', $data)->get();

        foreach ($comments as &$comment) {
            foreach ($comment_message_list as $comment_message) {
                if($comment->height_id == $comment_message->id) {
                    $comment->parent_comment = $comment_message->content;
                }
            }
        }
        foreach ($comments as &$comment) {
            if(empty($comment->height_id)) {
                $comment->reply_for = $oPost->find($comment->post_id)->title;
            }
        }
        return [$comments, $comments_count];
    }

    /*
     * 获取收藏的帖子
     */
    public function collectPostList()
    {
        $post_id = Auth::guard('user')->user()->collect;
        $post_id_array = explode(',', $post_id);
        $posts = Post::whereIn('id', $post_id_array)->paginate(10);
        $posts_count = Post::whereIn('id', $post_id_array)->count();

        return [$posts, $posts_count];
    }

    /*
     * 收藏标签
     */
    public function collectLable(Request $request)
    {
        $lable_id = $request->lable_id;
        $user = Auth::guard('user')->user();
        $lable_collect_array = explode(',', $user->lable_collect);

        if(in_array($lable_id, $lable_collect_array)) {
            return response()->json('您已经收藏过了', 401);
        } else {
            $user->lable_collect = $user->lable_collect . $lable_id .',';
            $user->save();
            return response()->json('收藏成功', 200);
        }
    }

    /*
     * 删除评论
     */
    public function deleteComment(Request $request)
    {
        $comment_id = $request->comment_id;
        $res = Comment::destroy($comment_id);
        if($res) {
            return response()->json('删除成功', 200);
        } else {
            return response()->json('删除失败', 400);
        }
    }

    /*
     * 显示个人消息页面
     */
    public function userMessage()
    {
        $oMessage = new Message();
        $oComment = new Comment();
        $user_id = Auth::guard('user')->user()->id;
        $private_message = $oMessage
            ->where('message_receive', $user_id)
            ->where('type', 1)
            ->get()
            ->groupBy('parent_id');
        $id_arr = [];
        foreach ($private_message as $value) {
            $id_arr[] = $value[0]->id;
        }
        $private_message_count = $oMessage
            ->whereIn('id', $id_arr)
            ->count();
        $private_message_list = $oMessage
            ->with('user')
            ->whereIn('id', $id_arr)
            ->orderBy('created_at', 'asc')
            ->paginate(6);
        $system_message_count = $oMessage->where('message_receive', $user_id)->where('type', 2)->count();

        $myComment = $oComment->where('user_id', $user_id)->get();
        $data = array();
        foreach ($myComment as $key => $value) {
            $data[] = $value->id;
        }
        $comment_message_count = $oComment->whereIn('height_id', $data)->count();

        $post_comment_message_count = $oComment
            ->whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('height_id', null)
            ->count();

        $message_array = [
            $private_message_list,
            $system_message_count,
            $comment_message_count,
            $post_comment_message_count,
            $private_message_count
        ];
        return view('home.user.user_message', ['message_array' => $message_array]);
    }

    //获取评论消息
    public function userCommentMessage()
    {
        $oMessage = new Message();
        $oComment = new Comment();
        $user_id = Auth::guard('user')->user()->id;
        $private_message_count = $oMessage
            ->where('message_receive', $user_id)
            ->where('type', 1)
            ->get()
            ->groupBy('parent_id')
            ->count();
        $system_message_count = $oMessage->where('message_receive', $user_id)->where('type', 2)->count();
        $myComment = $oComment->where('user_id', $user_id)->get();
        $data = array();
        foreach ($myComment as $key => $value) {
            $data[] = $value->id;
        }
        $comment_message_count = $oComment->whereIn('height_id', $data)->count();
        $comment_message_list = $oComment->with('user')->whereIn('height_id', $data)->paginate(5);
        foreach ($comment_message_list as &$comment_message) {
            foreach ($myComment as $comment) {
                if($comment->id == $comment_message->height_id) {
                    $comment_message->parent_comment = $comment;
                }
            }
        }
        $post_comment_message_count = $oComment
            ->whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('height_id', null)
            ->count();

        $message_array = [
            $system_message_count,
            $private_message_count,
            $comment_message_list,
            $post_comment_message_count,
            $comment_message_count

        ];
        return view('home.user.user_message_commentMessage', ['message_array' => $message_array]);
    }

    //话题评论消息
    public function userPostCommentMessage()
    {
        $oMessage = new Message();
        $oComment = new Comment();
        $user_id = Auth::guard('user')->user()->id;
        $private_message_count = $oMessage
            ->where('message_receive', $user_id)
            ->where('type', 1)
            ->get()
            ->groupBy('parent_id')
            ->count();
        $system_message_count = $oMessage->where('message_receive', $user_id)->where('type', 2)->count();

        $myComment = $oComment->where('user_id', $user_id)->get();
        $data = array();
        foreach ($myComment as $key => $value) {
            $data[] = $value->id;
        }
        $comment_message_count = $oComment->whereIn('height_id', $data)->count();

        $post_comment_message_count = $oComment
            ->whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('height_id', null)
            ->count();

        $post_comment_message_list = $oComment
            ->with('user')
            ->whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('height_id', null)
            ->orderBy('created_at')->paginate(6);

        foreach ($post_comment_message_list as &$postComment) {
            $childComment = $oComment->where('height_id', $postComment->id)->first();
            if(!empty($childComment->content)) {
                $postComment->chat_list = 1;
            } else {
                $postComment->chat_list = 0;
            }
        }
        $message_array = [
            $private_message_count,
            $system_message_count,
            $comment_message_count,
            $post_comment_message_list,
            $post_comment_message_count
        ];
        return view('home.user.user_message_postCommentMessage', ['message_array' => $message_array]);
    }

    //系统消息
    public function userSystemMessage()
    {
        $oMessage = new Message();
        $oComment = new Comment();
        $user_id = Auth::guard('user')->user()->id;
        $private_message_count = $oMessage
            ->where('message_receive', $user_id)
            ->where('type', 1)
            ->get()
            ->groupBy('parent_id')
            ->count();

        $system_message_count = $oMessage->where('message_receive', $user_id)->where('type', 2)->count();
        $system_message_list = $oMessage->where('message_receive', $user_id)->where('type', 2)->orderBy('created_at')->paginate(10);


        $myComment = $oComment->where('user_id', $user_id)->get();
        $data = array();
        foreach ($myComment as $key => $value) {
            $data[] = $value->id;
        }
        $comment_message_count = $oComment->whereIn('height_id', $data)->count();
        $post_comment_message_count = $oComment
            ->whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })
            ->where('height_id', null)
            ->count();

        $message_array = [
            $system_message_count,
            $system_message_list,
            $comment_message_count,
            $post_comment_message_count,
            $private_message_count
        ];
        return view('home.user.user_message_systemMessage', ['message_array' => $message_array]);
    }

    public function showPostCommentList(Request $request)
    {
        $comment_id = $request->comment_id;
        $parent_comment = Comment::where('id', $comment_id)->first();
        $comments = Comment::where('parent_id', $parent_comment->parent_id)->orderBy('created_at')->get();
        return view('home.user.comment_list', ["comments" => $comments]);
    }

    /*
     * 关注
     */
    public function followAuthor(Request $request)
    {
        $user_id = Auth::guard('user')->user()->id;
        $author = $request->id;
        $oFollow = new Follow();
        $follow = $oFollow->where('user_id', $user_id)->where('author', $author)->first();
        if(empty($follow)) {
            $oFollow->insert([
                'id'=>Uuid::uuid1()->toString(),
                'user_id' => $user_id,
                'author' => $author,
                'created_at' => date('Y-m-d')
            ]);
            return response()->json([
                'data'=>'',
                'info'=>'关注成功',
                'status'=> 1
            ], 200);
        } else {
            $oFollow->where('user_id', $user_id)->where('author', $author)->delete();
            return response()->json([
                'data'=>'',
                'info'=>'取消关注成功',
                'status'=> 0
            ], 200);
        }
    }

    /**添加好友的申请
     * @param Request $request
     * @return JsonResponse
     */
    public function addFriendApply(Request $request)
    {
        $current_user = Auth::guard('user')->user();
        $message_receive = $request->id;
        $user_id = $current_user->id;
        $content = '来自'.$current_user->username.'的添加好友请求';
        $created_at = date('Y-m-d H:i:s');

        $oMessage = new Message();
        $message = $oMessage
            ->where('user_id', $user_id)
            ->where('message_receive', $message_receive)
            ->where('type', 2)
            ->where('status', 0)
            ->first();
        if(!empty($message->content)) {
            return response()->json([
                'status' => 0,
                'info' => '您已经申请过，等待验证'
            ],200);
        }
        $oMessage->insert([
            'id' => Uuid::uuid1()->toString(),
            'user_id' => $user_id,
            'type' => 2,
            'message_receive' => $message_receive,
            'content' => $content,
            'created_at' => $created_at
        ]);

        return response()->json([
            'status' => 1,
            'info' => '添加好友申请成功'
        ],200);
    }

    //处理添加好友的申请
    public function handleFriendApply(Request $request)
    {
        $oMessage = new Message();
        $message = $oMessage->find($request->id);
        $user1 = User::find(Auth::guard('user')->user()->id);
        $user2 = User::find($message->user_id);
        //接受请求
        if($request->res == '1') {
            $message->status = 1;
            $message->is_read = 1;
            $message->save();
            $user1->friend = $user1->friend . $message->user_id. ',';
            $user2->friend = $user2->friend . Auth::guard('user')->user()->id. ',';
            $user1->save();
            $user2->save();
            return response()->json('添加好友成功', 200);
        }
        //拒绝请求
        else {
            $message->status = 2;
            $message->is_read = 1;
            $message->save();
            return response()->json('拒绝添加好友', 200);
        }
    }

    //显示发送私信的页面信息
    public function showPrivateMessageView(Request $request)
    {
        $author_id = $request->id;
        return view('home/forum/add_private_message', ['author_id' => $author_id]);
    }

    //发送站内私信
    public function sendPrivateMessage(Request $request)
    {
        if(empty($request->message)) {
            echo "<script>parent.layer.closeAll();parent.layer.msg('消息内容不能为空。');</script>";
            return;
        }
        $oMessage = new Message();
        $message_receive = $request->author_id;
        $message = $request->message;
        $user_id = Auth::guard('user')->user()->id;
        $parent_id = Uuid::uuid1()->toString();
        $res = $oMessage->insert([
            'id' => $parent_id,
            'user_id' => $user_id,
            'parent_id' => $parent_id,
            'type' => 1,
            'content' => $message,
            'message_receive' => $message_receive,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($res) {
            echo "<script>parent.layer.closeAll();parent.layer.msg('发送消息成功');</script>";
            return;
        }
        echo "<script>parent.layer.closeAll();parent.layer.msg('网络错误');</script>";
        return;

    }

    //显示站内私信的对话列表
    public function showPrivateMessageList(Request $request)
    {
        $oMessage = new Message();
        $parent_id = $request->parent_id;
        $messages = $oMessage->with('user')->where('parent_id', $parent_id)->orderBy('created_at','asc')->get();
        $author_id = $messages->first()->user_id == Auth::guard('user')->user()->id ? $messages->first()->message_receive : $messages->first()->user_id;
        return view('home/user/private_message_list', ['messages' => $messages, 'author_id' => $author_id]);
    }

    public function showPrivateMessageReply(Request $request)
    {
        $oMessage = new Message();
        $parent_id = $request->parent_id;
        $messages = $oMessage->with('user')->where('parent_id', $parent_id)->get();
        $author_id = $messages->first()->user_id == Auth::guard('user')->user()->id ? $messages->first()->message_receive : $messages->first()->user_id;
        return view('home/user/reply_private_message', ['messages' => $messages, 'author_id' => $author_id]);
    }

    //回复站内信
    public function replyPrivateMessage(Request $request)
    {
        if(empty($request->message)) {
            echo "<script>parent.layer.closeAll();parent.layer.msg('内容不能为空。');</script>";
            return;
        }
        $oMessage = new Message();
        $message_receive = $request->author_id;
        $message = $request->message;
        $user_id = Auth::guard('user')->user()->id;
        $parent_id = $request->parent_id;
        $res = $oMessage->insert([
            'id' => Uuid::uuid1()->toString(),
            'user_id' => $user_id,
            'parent_id' => $parent_id,
            'type' => 1,
            'content' => $message,
            'message_receive' => $message_receive,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        if($res) {
            echo "<script>parent.layer.closeAll();parent.layer.msg('回复消息成功');</script>";
            return;
        }
        echo "<script>parent.layer.closeAll();parent.layer.msg('网络错误');</script>";
        return;
    }

    //显示我的好友列表
    public function userFriend()
    {
        $friends = $this->getMyFriends()[0];
        $friends_count = $this->getMyFriends()[1];
        $follows_count = $this->getMyFellows()[1];
        return view('home.user.user_friend', ['friends' => $friends, 'friends_count' => $friends_count ,'follows_count' => $follows_count]);
    }

    //显示我关注的人
    public function userFollow()
    {
        $friends_count = $this->getMyFriends()[1];
        $follows = $this->getMyFellows()[0];
        $follows_count = $this->getMyFellows()[1];
        return view('home.user.user_follow', ['friends_count' => $friends_count, 'follows' => $follows, 'follows_count' => $follows_count]);
    }

    //获取我的好友
    public function getMyFriends()
    {
        $friends = User::whereIn('id', explode(',', Auth::guard('user')->user()->friend))->paginate(8);
        $friends_count = User::whereIn('id', explode(',', Auth::guard('user')->user()->friend))->count();
        return [$friends,$friends_count];
    }

    //获取我关注的人
    public function getMyFellows()
    {
        $followIds = Follow::where('user_id', Auth::guard('user')->user()->id)->get(['author'])->toArray();
        $follows = User::whereIn('id', $followIds)->paginate(8);
        $follows_count = User::whereIn('id', $followIds)->count();
        return [$follows, $follows_count];
    }

    //取消关注
    public function cancelFollow(Request $request)
    {
        $author_id = $request->id;
        $res = Follow::where('user_id', Auth::guard('user')->user()->id)->where('author', $author_id)->delete();
        if($res) {
            return response()->json('取消关注成功', 200);
        } else {
            return response()->json('取消关注失败', 500);
        }
    }

    //删除好友
    public function cancelFriend(Request $request)
    {
        $friend_id = $request->id;
        $user = User::find(Auth::guard('user')->user()->id);
        $user2 = User::find($friend_id);
        Message::where('type', 1)
            ->where('user_id', Auth::guard('user')->user()->id)
            ->where('message_receive', $friend_id)
            ->delete();
        Message::where('type', 1)
            ->where('user_id', $friend_id)
            ->where('message_receive', Auth::guard('user')->user()->id)
            ->delete();
        $this->destroyFriend($user, $friend_id);
        $this->destroyFriend($user2, Auth::guard('user')->user()->id);
        return response()->json('删除好友成功');
    }

    public function destroyFriend($user,$friend_id)
    {
        $friends = explode(',', $user->friend);
        foreach ($friends as $key => $value) {
            if($value == $friend_id) {
                unset($friends[$key]);
            }
        }
        $user->friend = implode(',', $friends);
        $user->save();
    }

    //标记消息为已读
    public function changeIsRead(Request $request)
    {
        $message_id = $request->message_id;
        if($request->type == 1){
            $message = Message::find($message_id);
            $message->is_read = 1;
            $message->save();
            Message::where('parent_id', $message->parent_id)->update(['is_read' => 1]);
        } else if($request->type == 2) {
            $comment = Comment::find($message_id);
            $comment->is_read = 1;
            $comment->save();
        }

        return response()->json('标记为已读', 200);
    }

    public function cancleLabel(Request $request)
    {
        $user = User::find(Auth::guard('user')->user()->id);
        $lable_id = $request->lable_id;
        $lables = explode(',', $user->lable_collect);
        foreach ($lables as $key => $value) {
            if($value == $lable_id) {
                unset($lables[$key]);
            }
        }
        $user->lable_collect = implode(',', $lables);
        $user->save();
        return response()->json('取消标签收藏',200);
    }

}
