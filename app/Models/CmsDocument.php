<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsDocument extends Model
{
    protected $table = 'tb_cms_document';

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

    public static function getDocumentByAdmin($params)
    {
        $query = CmsDocument::select('tb_cms_document.*')
            ->selectRaw('a.name as user_send, b.name as user_receive')
            ->leftJoin('tb_department', 'tb_cms_document.department_id', '=', 'tb_department.id')
            ->leftJoin('admins as a', 'tb_cms_document.admin_created_id', '=', 'a.id')
            ->leftJoin('admins as b', 'tb_cms_document.admin_receive_id', '=', 'b.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where('tb_cms_document.title', 'like', '%' . $keyword . '%');
            })
            ->when(!empty($params['admin_created_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.admin_created_id', $params['admin_created_id']);
            })
            ->when(!empty($params['admin_receive_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.admin_receive_id', $params['admin_receive_id']);
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.department_id', $params['department_id']);
            })
            ->orderByRaw('created_at DESC, admin_receive_id asc')
        ;
        return $query;
    }

    public static function getDocumentById($params)
    {
        $query = CmsDocument::select('tb_cms_document.*')
            ->selectRaw('a.name as user_send, b.name as user_receive')
            ->leftJoin('tb_department', 'tb_cms_document.department_id', '=', 'tb_department.id')
            ->leftJoin('admins as a', 'tb_cms_document.admin_created_id', '=', 'a.id')
            ->leftJoin('admins as b', 'tb_cms_document.admin_receive_id', '=', 'b.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where('tb_cms_document.title', 'like', '%' . $keyword . '%');
            })
            ->when(!empty($params['admin_created_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.admin_created_id', $params['admin_created_id']);
            })
            ->when(!empty($params['admin_receive_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.admin_receive_id', $params['admin_receive_id']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.id', $params['id']);
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                return $query->where('tb_cms_document.department_id', $params['department_id']);
            })
            ->orderByRaw('created_at DESC, admin_receive_id asc')
        ;
        return $query;
    }

}
