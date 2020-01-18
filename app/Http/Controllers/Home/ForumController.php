<?php

namespace App\Http\Controllers\Home;

use App\Constracts\IForum;
use App\Model\Comment;
use App\model\Lable;
use App\Model\Message;
use App\Model\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ForumController extends Controller
{
    private $peoForum;

    public function __construct(IForum $forum)
    {
        $this->peoForum = $forum;
    }

    public function index(Request $request)
    {
        $cols = $this->listCol();
        $lables = $this->peoForum->getAllLable();
        $allPosts = $this->peoForum->getAllPostData();
        $sum_post = $allPosts->count();
        $unreadMessages = $this->unreadMessage();
        $dayDiscussPost = $this->peoForum->getDayDiscussPost();
        return view('home.forum.index',[
            'dayDiscussPost' => $dayDiscussPost,
            'cols' => $cols,
            'all_lables' => $lables,
            'sum_post' => $sum_post,
            'unreadMessages' => $unreadMessages
        ]);
    }

    /*
     * 选择不同栏目显示的页面
     */
    public function colIndex(Request $request)
    {
        $allPosts = $this->peoForum->getAllPostData();
        $allLables = $this->peoForum->getAllLable();
        $sum_post = $allPosts->count();
        $cols = $this->listCol();
        $unreadMessages = $this->unreadMessage();
        $dayDiscussPost = $this->peoForum->getDayDiscussPost();
        $column_id = $request->column_id;
        $lables = $this->listLable($column_id);
        return view('home.forum.index',[
            'dayDiscussPost' => $dayDiscussPost,
            'cols' => $cols,
            'lables' => $lables,
            'all_lables' => $allLables,
            'unreadMessages' => $unreadMessages,
            'sum_post' => $sum_post
        ]);
    }

    public function lableIndex(Request $request)
    {
        $id = $request->id;
        $lable = Lable::find($id);
        $allPosts = $this->peoForum->getAllPostData();
        $allLables = $this->peoForum->getAllLable();
        $sum_post = $allPosts->count();
        $cols = $this->listCol();
        $unreadMessages = $this->unreadMessage();
        $dayDiscussPost = $this->peoForum->getDayDiscussPost();
        $column_id = 1;
        $lables = $this->listLable($column_id);
        return view('home.forum.index_lable',[
            'dayDiscussPost' => $dayDiscussPost,
            'cols' => $cols,
            'lable_name' => $lable->lable_name,
            'description' => $lable->description,
            'unreadMessages' => $unreadMessages,
            'lable_id' => $lable->id,
            'lables' => $lables,
            'all_lables' => $allLables,
            'sum_post' => $sum_post
        ]);
    }


    /*
     * 查询周回复最多的post
     */
    public function getWeekDiscussPost()
    {
        $posts = $this->peoForum->getWeekDiscussPost();
        return $posts;
    }

    /*
     * 查询所有栏目
     */
    public function listCol()
    {
        $cols = $this->peoForum->getCols();
        return $cols;
    }

    /*
     * 根据栏目查询lable
     */
    public function listLable($col_id)
    {
        $lables = $this->peoForum->getLable($col_id);
        return $lables;
    }

    /*
     * 流加载post
     */
    public function flowListPost(Request $request)
    {
        $col_id = $request->col_id;
        $lable_id = $request->lable_id;
        $orderBy = $request->orderBy;
        $page        = $request->page ? $request->page : 1;
        $p_n      = ($page - 1)*10;
        $list     = $this->peoForum->getPostPaginate($p_n, $orderBy, $col_id, $lable_id, urldecode($request->keyword));
        $data     = [
            'p'        => $page,
            'p_n'      => $p_n,
            'all_page' => ceil($list[1] / 10),
            'list'     => $list[0],
        ];
        return response()->json(['code' => 0, 'msg' => '加载成功！', 'data' => $data]);
    }

    //返回未读消息数量
    public function unreadMessage()
    {
        if(Auth::guard('user')->check()) {
            $user_id = Auth::guard('user')->user()->id;
            $private_message_count = Message::where('message_receive', $user_id)->where('is_read', 0)->get()->count();
            $comment_message1 = Comment::whereHas('parentComment', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })->where('is_read', 0)->get()->count();
            $comment_message2 = Comment::whereHas('post', function($query) use($user_id) {
                $query->where('user_id', $user_id);
            })->where('is_read', 0)->where('height_id', null)->get()->count();
            return $private_message_count+$comment_message1+$comment_message2;
        } else {
            return null;
        }

    }

    //显示搜索页面
    public function indexSearch(Request $request)
    {
        $cols = $this->listCol();
        $lables = $this->peoForum->getAllLable();
        $allPosts = $this->peoForum->getAllPostData();
        $sum_post = $allPosts->count();
        $unreadMessages = $this->unreadMessage();
        $dayDiscussPost = $this->peoForum->getDayDiscussPost();
        return view('home.forum.index_search',[
            'dayDiscussPost' => $dayDiscussPost,
            'all_lables' => $lables,
            'sum_post' => $sum_post,
            'unreadMessages' => $unreadMessages,
            'cols' => $cols
        ]);
    }
}
