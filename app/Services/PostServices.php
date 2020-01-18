<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/21
 * Time: 23:19
 */

namespace App\Services;

use App\Constracts\IPost;
use App\Model\Post;
use App\Model\Comment;
use App\Model\Praise;
use Illuminate\Support\Facades\Auth;

class PostServices implements IPost
{
    private $peoPost;
    private $peoComment;
    private $peoPraise;
    public function __construct(
        Post $oPost,
        Comment $oComment,
        Praise $oPraise
    )
    {
        $this->peoPost = $oPost;
        $this->peoComment = $oComment;
        $this->peoPraise = $oPraise;
    }

    public function insertPostData($data)
    {
        $post_id = $this->peoPost->insertGetId($data);
        return $post_id;
    }

    public function getPostDetail($id)
    {
        $postInfo = $this->peoPost
            ->with(['comment' => function($query){
                $query->orderBy('created_at', 'asc');
            }])
            ->with('col')
            ->with('lable')
            ->find($id);
        $postInfo->visited ++;
        $postInfo->save();
        $postInfo->lable_id = $this->peoPost->valueToText($postInfo->lable_id,$this->peoPost->lableData());
        $postInfo->col_id = $this->peoPost->valueToText($postInfo->col_id,$this->peoPost->colData());
        return $postInfo;
    }

    public function saveComment($data)
    {
        $post = $this->peoPost->find($data['post_id']);
        $post->comments = $post->comments+1;
        $post->save();
        $res = $this->peoComment->insert($data);
        return response()->json([]);
    }

    public function commentPraise($id)
    {
        $opraise = $this->peoPraise;
        $user_id = Auth::guard("user")->user()->id;
        if(!empty($opraise->where(["comment_id" => $id])->where(["user_id" => $user_id])->first())){
            return false;
        }
        $opraise->insert([
            "comment_id" => $id,
            "user_id" => $user_id
        ]);
        $comment = $this->peoComment->
        find($id);
        $comment->praise++;
        $comment->save();
        return $comment->praise;
    }

    public function postPraise($id)
    {
        $opraise = $this->peoPraise;
        $user_id = Auth::guard("user")->user()->id;
        if(!empty($opraise->where(["post_id" => $id])->where(["user_id" => $user_id])->first())){
            return false;
        }
        $opraise->insert([
            "post_id" => $id,
            "user_id" => $user_id
        ]);
        $post = $this->peoPost->find($id);
        $post->praise++;
        $post->save();
        return "点赞成功";
    }

    public function getAllPost()
    {
        $allPost = $this->peoPost->all();
        return $allPost;
    }

    public function saveReplyComment($data)
    {
        $comment = $this->peoComment->find($data['height_id']);
        $comment->comments = $comment->comments + 1;
        $comment->save();
        $res = $this->peoComment->insert($data);
        return $res;
    }

}