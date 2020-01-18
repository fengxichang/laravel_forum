<?php

namespace App\Http\Controllers\Home;

use App\Constracts\IPost;
use App\Constracts\IForum;
use App\Model\Follow;
use App\Model\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Col;
use App\Model\Lable;
use App\Model\User;
use App\Model\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;


class PostController extends Controller
{
    private $peoPost;
    private $peoForum;
    public function __construct(IPost $postShell, IForum $forumShell)
    {
        $this->peoPost = $postShell;
        $this->peoForum = $forumShell;
    }

    public function addPostView()
    {
        $cols = Col::all();
        return view("home.post.add", ["cols" => $cols]);
    }

    public function createPost(Request $request)
    {
        $col_id = $request->get("col_id");
        $lable_id = $request->get("lable_id");
        $title = $request->get("title");
        $content = $request->get("content");
        $user_id = Auth::guard("user")->user()->id;
        if(empty($col_id)) {
            return redirect('/post/addPostView');
        }
        if(empty($title)) {
            return redirect('/post/addPostView');
        }
        if(empty($content)) {
            return redirect('/post/addPostView');
        }
        $data = [
            "col_id" => $col_id,
            "lable_id" => $lable_id,
            "title" => $title,
            "content" => $content,
            "user_id" => $user_id,
            "created_at" => date("Y-m-d H:i:s"),
        ];
        $post_id = $this->peoPost->insertPostData($data);
        return redirect("/postDetail?id=".$post_id);
    }

    public function videoSave(Request $request)
    {
        $file = $request->file("file");
        $newFileName = md5(time().rand(0,10000)).".".$file->getClientOriginalExtension();
        $realPath = $file->getRealPath();
        Storage::disk('local')->put('/post/'.$newFileName, file_get_contents($realPath));
        $res = [
            "code" => "0",
            "msg" => "视频上传成功",
            "data" => [
                "src" => "/uploads/post/".$newFileName,
            ]
        ];
        return json_encode($res);
    }

    public function avatarSave(Request $request)
    {

        $file = $request->file("file");
        $newFileName = md5(time().rand(0,10000)).".".$file->getClientOriginalExtension();
        $img = Image::make($file)->save("uploads/post/".$newFileName);

//        $savePath = "/post/".$newFileName;
//        $bytes = Storage::put(
//            $savePath,
//            file_get_contents($file->getRealPath())
//        );
//        if(!Storage::exists($savePath)){
//            exit("保存失败!");
//        }

//        header("Content-Type:".Storage::mimeType($savePath));
        $msg = [
            "code" => "0",
            "msg" => "图片上传成功",
            "data" => [
                "src" => "/uploads/post/".$newFileName,
            ]
        ];
        return json_encode($msg);
    }

    //查看作者个人中心
    public function authorHome(Request $request)
    {
        $author = User::with('post')->with('comment')->find($request->id);
        $follows = 0;
        foreach ($author->comment as &$comment) {
            if(empty($comment->height_id)) {
                $comment->reply_for = Post::find($comment->post_id)->title;
            }
        }
        if(Auth::guard('user')->check()) {
            if(in_array($author->id, explode(',', Auth::guard("user")->user()->friend))) {
                $author->is_friend = 1;
            } else {
                $author->is_friend = 0;
            }
            $follows = Follow::where('author', $request->id)->where('user_id',Auth::guard('user')->user()->id)->first();
        } else {
            $author->is_friend = 0;
        }
        $follow_count = Follow::where('author', $request->id)->get()->count();
        $collect_posts = Post::whereIn('id', explode(',', $author->collect))->get();
        $collect_lables = Lable::whereIn('id', explode(',', $author->collect))->get();
        return view('home.forum.user_home',['author' => $author,
            'collect_posts' => $collect_posts,
            'collect_lables' => $collect_lables,
            '_follows' => $follows,
            'follow_count' => $follow_count
        ]);
    }

    public function postDetail(Request $request)
    {
        $lables = $this->peoForum->getAllLable();
        $allPosts = $this->peoPost->getAllPost();
        $sum_post = $allPosts->count();
        $dayDiscussPost = $this->peoForum->getDayDiscussPost();
        $post = $this->getPostDetailData($request->id);
        return view('home.post.detail',[
            'post' => $post,
            'dayDiscussPost' => $dayDiscussPost,
            'all_lables' => $lables,
            'sum_post' => $sum_post
        ]);
    }

    public function getPostDetailData($id)
    {
        $postInfo = $this->peoPost->getPostDetail($id);
        return $postInfo;
    }

    public function saveComment(Request $request)
    {
        $id = Uuid::uuid1()->toString();
        $commentData = [
            'id' => $id,
            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
            'content' => $request->get('content'),
            'parent_id' => $id,
            'created_at' => date("Y-m-d H:i:s")
        ];
        $this->peoPost->saveComment($commentData);
        return response()->json($commentData);
    }

    /*
     * 评论点赞
     */
    public function commentPraise(Request $request)
    {
        $id = $request->id;
        $commentPraise = $this->peoPost->commentPraise($id);
        if($commentPraise==false){
            return new JsonResponse("您已经点过赞",402);
        }
        return new JsonResponse($commentPraise,200);
    }

    /*
     * 文章点赞
     */
    public function PostPraise(Request $request)
    {
        $id = $request->id;
        $postPraise = $this->peoPost->postPraise($id);
        if ($postPraise == false) {
            return new JsonResponse("您已经点过赞", 200);
        }
        return new JsonResponse($postPraise, 200);
    }

    public function getLableByCol(Request $request)
    {
        $lables = Lable::where('col_id', $request->col_id)->get();
        return response()->json($lables, 200);
    }

    /*
     * 回复评论数据
     */
    public function saveReplyComment(Request $request)
    {

        $data = [
            'id' => Uuid::uuid1()->toString(),
            'height_id' => $request->comment_id,
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'content' => $request->_content,
            'parent_id' => $request->parent_id,
            'comment_username' => '回复'.$request->comment_username,
            'created_at' => date("Y-m-d H:i:s")
        ];

        $res = $this->peoPost->saveReplyComment($data);

        return response()->json($data);
    }

    /*
     * 显示评论列表
     */
    public function showCommentList(Request $request)
    {
        $comment_id = $request->comment_id;
        $parent_comment = Comment::where('id', $comment_id)->first();
        $comments = Comment::where('parent_id', $parent_comment->parent_id)->orderBy('created_at')->get();
        return view("home.post.comment_list", ["comments" => $comments]);
    }

    /*
     * 收藏话题(帖子)
     */
    public function collectPost(Request $request)
    {
        $post_id = $request->post_id;
        $user_id = Auth::guard('user')->user()->id;
        $user = User::find($user_id);

        $collectArray = explode(',', $user->collect);

        if(in_array($post_id, $collectArray)) {

            return response()->json('您已经收藏过', 200);

        } else {
            $post = Post::find($post_id);
            $post->collects = $post->collects + 1;
            $post->save();
            $user->collect = $user->collect . $post_id . ',';
            $user->save();
            return response()->json('收藏成功', 200);
        }
    }

    /*
     * 编辑话题界面
     */
    public function editPost(Request $request)
    {
        $cols = Col::all();
        $post_id = $request->post_id;
        $post = Post::with('lable')->find($post_id);
        return view('home.post.edit', ['cols' => $cols, 'post' => $post]);
    }

    /*
     * 更新post
     */
    public function updatePost(Request $request)
    {
        $data = $request->except('_token', 'file', 'post_id');
        $data['updated_at'] = date('Y-m-d H:i:s');
        Post::where('id', $request['post_id'])->update($data);
        return redirect("/postDetail?id=".$request['post_id']);
    }

    /*
     * 删除post
     */
    public function deletePost(Request $request)
    {
        $post_id = $request->post_id;
        Comment::where('post_id', $post_id)->delete();
        $res=Post::where('id', $post_id)->delete();
        if($res) {
            return response()->json('删除成功', 200);
        } else {
            return response()->json('删除失败', 400);
        }
    }

    /**发送私信前判断是不是好友
     * @param Request $request
     * @return JsonResponse
     */
    public function isFriend(Request $request)
    {
        if(Auth::guard('user')->check()) {
            $author_id = $request->author_id;
            if(in_array($author_id, explode(',', Auth::guard("user")->user()->friend))) {
                return response()->json('',200);
            } else {
                return response()->json('你们还不是好友', 400);
            }
        } else {
            return response()->json('请先登录');
        }
    }
}
