<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsMessage extends Model
{
    protected $table = 'tb_message';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    public static function getMessageByParam($params)
    {
        //echo "AAAAAAAAA".$params['status'];die;
        $query = CmsMessage::select('tb_message.*')
        ->selectRaw('a.name as admin_send, a.avatar as admin_avatar, b.name as admin_receive, b.avatar as receive_avatar')
        ->leftJoin('admins as a', 'tb_message.admin_created_id', '=', 'a.id')
        ->leftJoin('admins as b', 'tb_message.admin_receive_id', '=', 'b.id')
       
        ->when(!empty($params['user_key']), function ($query) use ($params) {
            return $query->where('tb_message.user_key', $params['user_key']);
        })
        ->when(!empty($params['admin_created_id']), function ($query) use ($params) {
            return $query->where('tb_message.admin_created_id', $params['admin_created_id']);
        })
        ->when(!empty($params['admin_receive_id']), function ($query) use ($params) {
            return $query->where('tb_message.admin_receive_id', $params['admin_receive_id']);
        });
        
        if(isset($params['status'])){
            return $query->where('tb_message.status', $params['status']);
        }
        $query->orderBy('tb_message.id','asc')
        ;
        
        return $query;
    }

}
