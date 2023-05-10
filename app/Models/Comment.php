<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_post_comment';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function getCommentByParam($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = Comment::select('tb_post_comment.*')->selectRaw('tb_cms_posts.title as post_title')
        ->leftJoin('tb_cms_posts', 'tb_post_comment.post_id', '=', 'tb_cms_posts.id')
        ->when(!empty($params['keyword']), function ($query) use ($params) {
            $keyword = $params['keyword'];
            return $query->where(function ($where) use ($keyword) {
                return $where->where('tb_post_comment.content', 'like', '%' . $keyword . '%')
                ->orWhere('tb_cms_posts.title', 'like', '%' . $keyword . '%');
            });
        })
        ->when(!empty($params['id']), function ($query) use ($params) {
            return $query->where('tb_post_comment.id', $params['id']);
        })
        ->when(!empty($params['post_id']), function ($query) use ($params) {
            return $query->where('tb_post_comment.post_id', $params['post_id']);
        });
        /*
        ->when(!empty($params['status']), function ($query) use ($params) {
            return $query->where('tb_post_comment.status', $params['status']);
        })*/
        if(isset($params['status']) and $params['status'] >= 0){
            return $query->where('tb_post_comment.status', $params['status']);
        }
        $query->orderBy('tb_post_comment.id','desc')
        ;
        
        return $query;
    }

}
