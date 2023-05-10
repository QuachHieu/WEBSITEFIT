<?php
namespace App\Http\Services;

use App\Consts;
use App\Models\Booking;
use App\Models\CmsPost;
use App\Models\CmsTaxonomy;
use App\Models\Contact;
use App\Models\Menu;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Comment;
use App\Models\BlockContent;
use App\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRole;
use App\Models\AdminMenu;
use App\Models\Budget;
use App\Models\CmsResource;
use App\Models\Department;
use App\Models\Degree;
use App\Models\Event;
use App\Models\CmsFunction;
class ContentService
{
    public static function getMenu($params = [])
    {
        $query = Menu::select('tb_menus.*')
            ->selectRaw('count(b.id) AS sub')
            ->leftJoin('tb_menus AS b', 'tb_menus.id', '=', 'b.parent_id')
            ->groupBy('tb_menus.id')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_menus.id', '=', $params['id']);
            })
            ->when(!empty($params['parent_id']), function ($query) use ($params) {
                $query->where('tb_menus.parent_id', '=', $params['parent_id']);
            })
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                return $query->where(function ($where) use ($params) {
                    return $where->where('tb_menus.name', 'like', '%' . $params['keyword'] . '%');
                });
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_menus.status', $params['status']);
        } else {
            $query->where('tb_menus.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_menus.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_menus.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_menus.id desc');
        }

        return $query;
    }


    public static function getOption()
    {
        return Option::where('is_system_param', 0)->get();
    }

    public static function tinNoiBat($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,  tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_posts.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }

        $query->where('tb_cms_posts.aproved_date',"<=", date('Y-m-d H:i:s'));

        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }

        return $query;
    }

    public static function getPage($params = [])
    {
        $query = Page::select('tb_pages.*')
            ->when(!empty($params['id']), function ($query) use ($params) {
                $query->where('tb_pages.id', '=', $params['id']);
            })
            ->when(!empty($params['route_name']), function ($query) use ($params) {
                $query->where('tb_pages.route_name', '=', $params['route_name']);
            })
            ->when(!empty($params['alias']), function ($query) use ($params) {
                $query->where('tb_pages.alias', '=', $params['alias']);
            });
        // Status delete
        if (!empty($params['status'])) {
            $query->where('tb_pages.status', $params['status']);
        } else {
            $query->where('tb_pages.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_pages.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_pages.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_pages.iorder ASC, tb_pages.id desc');
        }

        return $query;
    }
    public static function getEvent($params, $isPaginate = false)
    {
        $query = Event::selectRaw('tb_events.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_events.title', 'like', '%' . $keyword . '%')
                        ;
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_events.id', $params['id']);
            });
         
        if (!empty($params['status'])) {
            $query->where('tb_events.status', $params['status']);
        } else {
            $query->where('tb_events.status', "!=", Consts::STATUS_DELETE);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_events.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_events.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_events.iorder ASC, tb_events.id DESC');
        }

        return $query;
    } 
    public static function getBudget($params, $isPaginate = false)
    {
        $query = Budget::selectRaw('tb_budget.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_budget.title', 'like', '%' . $keyword . '%')
                        ;
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_budget.id', $params['id']);
            });
         
        if (!empty($params['status'])) {
            $query->where('tb_budget.status', $params['status']);
        } else {
            $query->where('tb_budget.status', "!=", Consts::STATUS_DELETE);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_budget.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_budget.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_budget.iorder ASC, tb_budget.id DESC');
        }

        return $query;
    }
    public static function getCmsDepartment($params, $isPaginate = false)
    {
        $query = Department::select('tb_department.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_department.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_department.id', $params['id']);
            })
           ;
        if (!empty($params['status'])) {
            $query->where('tb_department.status', $params['status']);
        } else {
            $query->where('tb_department.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_department.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_department.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw(' tb_department.iorder ASC, tb_department.id DESC');
        }

        return $query;
    } 

    public static function getCmsDegree($params, $isPaginate = false)
    {
        $query = Degree::select('tb_degree.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_degree.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_degree.id', $params['id']);
            })
           ;
        if (!empty($params['status'])) {
            $query->where('tb_degree.status', $params['status']);
        } else {
            $query->where('tb_degree.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_degree.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_degree.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw(' tb_degree.iorder ASC, tb_degree.id DESC');
        }

        return $query;
    }

    public static function getCmsFunction($params, $isPaginate = false)
    {
        $query = CmsFunction::select('tb_function.*')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_function.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_function.id', $params['id']);
            })
           ;
        if (!empty($params['status'])) {
            $query->where('tb_function.status', $params['status']);
        } else {
            $query->where('tb_function.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_function.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_function.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw(' tb_function.iorder ASC, tb_function.id DESC');
        }

        return $query;
    }

    public static function getCmsTaxonomy($params, $isPaginate = false)
    {
        $query = CmsTaxonomy::select('tb_cms_taxonomys.*')
            ->selectRaw('count(b.id) AS sub')
            ->leftJoin('tb_cms_taxonomys AS b', 'tb_cms_taxonomys.id', '=', 'b.parent_id')
            ->groupBy('tb_cms_taxonomys.id')
            
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_taxonomys.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_taxonomys.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['taxonomy']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.taxonomy', $params['taxonomy']);
            })
            ->when(!empty($params['vitri']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.vitri', $params['vitri']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.is_featured', $params['is_featured']);
            })
            ->when(!empty($params['url_part']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.url_part', $params['url_part']);
            })
            ->when(!empty($params['taxonomy_different']), function ($query) use ($params) {
                return $query->where('tb_cms_taxonomys.taxonomy', '!=', $params['taxonomy_different']);
            });
        if (!empty($params['status'])) {
            $query->where('tb_cms_taxonomys.status', $params['status']);
        } else {
            $query->where('tb_cms_taxonomys.status', "!=", Consts::STATUS_DELETE);
        }
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_taxonomys.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_taxonomys.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_taxonomys.taxonomy, tb_cms_taxonomys.iorder ASC, tb_cms_taxonomys.id DESC');
        }

        return $query;
    }

    public static function getCmsPost($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_cms_posts.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                //return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                return $query->where('tb_cms_posts.taxonomy_id',$params['taxonomy_id']);
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_cms_posts.url_part', $params['url_part']);
        }

        if (!empty($params['aproved_date'])) {
            $query->where('tb_cms_posts.aproved_date', '<=' , $params['aproved_date']);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_cms_posts.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_cms_posts.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id ASC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    } 

    public static function getCmsResource($params, $isPaginate = false)
    {
        $query = CmsResource::selectRaw('tb_resource.*, tb_cms_taxonomys.url_part as taxonomy_url_part,admins.avatar as avatar,admins.name as fullname,
            b.name as admin_name, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_resource.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_resource.admin_created_id')
            ->leftJoin('admins as b', 'b.id', '=', 'tb_resource.admin_updated_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_resource.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_resource.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_resource.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                //return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                return $query->where('tb_resource.taxonomy_id',$params['taxonomy_id']);
            })
            ;

       
        if (!empty($params['status'])) {
            $query->where('tb_resource.status', $params['status']);
        } else {
            $query->where('tb_resource.status', "!=", Consts::STATUS_DELETE);
        }
        
        if (!empty($params['url_part'])) {
            $query->where('tb_resource.url_part', $params['url_part']);
        }

        if (!empty($params['phanloai'])) {
            $query->where('tb_resource.phanloai', "!=" , $params['phanloai']);
        }

        if (!empty($params['news_position'])) {
            $query->where('tb_resource.news_position', $params['news_position']);
        }

        if (isset($params['admin_created_id'])) {
            $query->where('tb_resource.admin_created_id', $params['admin_created_id']);
        }

        if (isset($params['admin_updated_id'])) {
            $query->where('tb_resource.admin_updated_id', $params['admin_updated_id']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_resource.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_resource.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_resource.iorder ASC, tb_resource.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    // Lấy tin liên quan
    public static function getCmsPostRelative($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.* ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id','!=',$params['id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                //return $query->where('tb_cms_posts.category','LIKE' ,'%,'.$params['taxonomy_id'].',%');
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (isset($params['post_id']) and is_array($params['post_id'])) {
            $query->whereIn('tb_cms_posts.id',$params['post_id']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position','>',$params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.aproved_date DESC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getCmsPostTag($params, $isPaginate = false)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.*, tb_cms_taxonomys.url_part as taxonomy_url_part ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%')
                        //->orWhere('tb_cms_posts.json_params->title->vi', 'like', '%' . $keyword . '%')
                        ;
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                //return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                return $query->where('tb_cms_posts.cms_tag','LIKE' ,'%,'.$params['taxonomy_id'].',%');
            })
            ;

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (isset($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getContact($params)
    {
        $query = Contact::select('tb_contacts.*')
            ->selectRaw('tb_cms_taxonomys.title AS department')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_contacts.json_params->department_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_contacts.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_contacts.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                $query->where('tb_contacts.json_params->department_id', '=', $params['department_id']);
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_contacts.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_contacts.status', $params['status']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_contacts.created_at', '<=', $params['created_at_to']);
            });

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_contacts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_contacts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_contacts.is_type ASC, tb_contacts.id DESC');
        }

        return $query;
    }

    public static function getBooking($params)
    {
        $query = Booking::select('tb_bookings.*')
            ->selectRaw('tb_cms_taxonomys.title AS department')
            ->selectRaw('tb_cms_posts.title AS doctor')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_bookings.department_id')
            ->leftJoin('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_bookings.doctor_id')

            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_bookings.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_bookings.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_bookings.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['department_id']), function ($query) use ($params) {
                $query->where('tb_bookings.department_id', '=', $params['department_id']);
            })
            ->when(!empty($params['doctor_id']), function ($query) use ($params) {
                return $query->where('tb_bookings.doctor_id', $params['doctor_id']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_bookings.status', $params['status']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_bookings.booking_date', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_bookings.booking_date', '<=', $params['created_at_to']);
            });

        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_bookings.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_bookings.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_bookings.id DESC');
        }

        return $query;
    }

    public static function getOrderService($params)
    {
        $query = Order::select('tb_orders.*')
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(tb_order_details.json_params, '$.post_link')) as post_link")
            ->selectRaw('tb_cms_posts.title AS post_title')
            ->join('tb_order_details', 'tb_order_details.order_id', '=', 'tb_orders.id')
            ->join('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_order_details.item_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_orders.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        return $query;
    }

    public static function getOrderProduct($params)
    {
        $query = Order::select('tb_orders.*')
            ->selectRaw('SUM(tb_order_details.quantity) AS total_quantity, SUM(tb_order_details.quantity * tb_order_details.price) AS total_money')
            ->leftJoin('tb_order_details', 'tb_order_details.order_id', '=', 'tb_orders.id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_orders.name', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.email', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_orders.phone', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['is_type']), function ($query) use ($params) {
                return $query->where('tb_orders.is_type', $params['is_type']);
            })
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_orders.status', $params['status']);
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_orders.id', $params['id']);
            })
            ->when(!empty($params['created_at_from']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '>=', $params['created_at_from']);
            })
            ->when(!empty($params['created_at_to']), function ($query) use ($params) {
                $query->where('tb_orders.created_at', '<=', $params['created_at_to']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_orders.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_orders.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_orders.id DESC, tb_orders.status ASC');
        }

        $query->groupBy('tb_orders.id');

        return $query;
    }

    public static function getOrderDetail($params)
    {
        $query = OrderDetail::select('tb_order_details.*')
            ->selectRaw('tb_cms_posts.title AS post_title, tb_cms_posts.image, tb_cms_posts.image_thumb')
            ->join('tb_cms_posts', 'tb_cms_posts.id', '=', 'tb_order_details.item_id')
            ->when(!empty($params['status']), function ($query) use ($params) {
                return $query->where('tb_order_details.status', $params['status']);
            })
            ->when(!empty($params['order_id']), function ($query) use ($params) {
                return $query->where('tb_order_details.order_id', $params['order_id']);
            });
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_order_details.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_order_details.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_order_details.id DESC');
        }

        return $query;
    }
    
    public static function getCmsPostLoading($params,$articles)
    {
        $query = CmsPost::selectRaw('tb_cms_posts.* ,admins.avatar as avatar,admins.name as fullname, tb_cms_taxonomys.title AS taxonomy_title, tb_cms_taxonomys.taxonomy AS taxonomy, tb_cms_taxonomys.json_params AS taxonomy_json_params')
            ->leftJoin('tb_cms_taxonomys', 'tb_cms_taxonomys.id', '=', 'tb_cms_posts.taxonomy_id')
            ->leftJoin('admins', 'admins.id', '=', 'tb_cms_posts.admin_created_id')
            ->when(!empty($params['keyword']), function ($query) use ($params) {
                $keyword = $params['keyword'];
                return $query->where(function ($where) use ($keyword) {
                    return $where->where('tb_cms_posts.title', 'like', '%' . $keyword . '%')
                        ->orWhere('tb_cms_posts.json_params->title->vi', 'like', '%' . $keyword . '%');
                });
            })
            ->when(!empty($params['id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', $params['id']);
            })
            ->when(!empty($params['different_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.id', '!=', $params['different_id']);
            })
            ->when(!empty($params['taxonomy_id']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.taxonomy_id', $params['taxonomy_id']);
                //return $query->where('tb_cms_posts.category','LIKE' ,','.$params['taxonomy_id'].',');
            })
            ->when(!empty($params['is_featured']), function ($query) use ($params) {
                return $query->where('tb_cms_posts.is_featured', $params['is_featured']);
            });

        if (!empty($params['is_type'])) {
            $query->where('tb_cms_posts.is_type', $params['is_type']);
        } else {
            $query->where('tb_cms_posts.is_type', Consts::POST_TYPE['post']);
        }
        if (!empty($params['status'])) {
            $query->where('tb_cms_posts.status', $params['status']);
        } else {
            $query->where('tb_cms_posts.status', "!=", Consts::STATUS_DELETE);
        }

        if (!empty($params['news_position'])) {
            $query->where('tb_cms_posts.news_position', $params['news_position']);
        }

        $query->where('tb_cms_posts.aproved_date','<=',date('Y-m-d H:i:s'));

        $query->whereRaw('tb_cms_posts.id NOT IN ('.$articles.')');
        
        // Check with order_by params
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_cms_posts.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_cms_posts.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_cms_posts.iorder ASC, tb_cms_posts.id DESC');
        }
        if (!empty($params['limit'])){
            $query->limit($params['limit']);
        }
        return $query;
    }

    public static function getComment($params)
    {
        $query = Comment::select('tb_post_comment.*')
            ->selectRaw('users.name as comment_name, users.avatar')
            ->leftJoin('users', 'users.id', '=', 'tb_post_comment.user_id');
        
        $query->where('tb_post_comment.post_id', $params['post_id']);
        $query->where('tb_post_comment.status', 0);
        if (!empty($params['order_by'])) {
            if (is_array($params['order_by'])) {
                foreach ($params['order_by'] as $key => $value) {
                    $query->orderBy('tb_post_comment.' . $key, $value);
                }
            } else {
                $query->orderByRaw('tb_post_comment.' . $params['order_by'] . ' desc');
            }
        } else {
            $query->orderByRaw('tb_post_comment.id DESC');
        }

        return $query;
    }

    public static function getBlockContentByParams($params)
    {
        $query = BlockContent::select('tb_block_contents.*');
            
        $query->where('tb_block_contents.status', 'active');
		
		if (!empty($params['block_code'])) {
            $query->where('tb_block_contents.block_code', $params['block_code']);
        }
		
        $query->orderByRaw('tb_block_contents.iorder asc');

        return $query;
    }

    public static function postTime($date_at){
        
        $first_date = time();
        $secon_date = strtotime($date_at);
        $diff = abs($first_date - $secon_date);
        $phut = $diff / 24;
        
        if($phut >= 60){
            $gio = floor($phut/60);
            if($gio < 10){
                $hienthingay = floor($gio).' giờ trước';
            }else{
                $hienthingay = date('H:i d/m/Y',strtotime($date_at));
            }
        }else{
            $hienthingay = floor($phut).' phút trước';
        }
        return $hienthingay;
    }

    public static function checkRole($routeDefault,$function){
        
        if(Auth::guard('admin')->user()->is_super_admin == 1){
           return true;
        }else{
            //dd($routeDefault);
            //$url_full = url()->full();
            //$routeDefault = str_replace(Consts::url_admin,'',$url_full);

            $menu_admin = AdminMenu::where('url_link',$routeDefault)->first();
            
            $role_id = Auth::guard('admin')->user()->role;
            $menu_id = '';
            if($menu_admin){
                $menu_id = $menu_admin->id;
            }
            
            $check_role = UserRole::where('role_id',$role_id)->first();

            $array_role_action = (array)$check_role->json_action;
            
            if(isset($array_role_action[$menu_id])){
                $arrayRoleAction = $array_role_action[$menu_id];
            }else{
                $arrayRoleAction = array();
            }
            $check_true = 0;
            foreach($arrayRoleAction as $checkAction){
                if($checkAction == $function){
                    $check_true = 1;
                }
            }
            return $check_true;
        }
        
    }

	public static function stringTruncate($string, $length = 100, $etc = '...', $break_words = false, $middle = false) {

		if ($length == 0) {
			return '';
		}

		$charset = 'UTF-8';
		$_UTF8_MODIFIER = 'u';

		if (mb_strlen ( $string, $charset ) > $length) {
			$length -= min ( $length, mb_strlen ( $etc, $charset ) );
			if (! $break_words && ! $middle) {
				$string = preg_replace ( '/\s+?(\S+)?$/' . $_UTF8_MODIFIER, '', mb_substr ( $string, 0, $length + 1, $charset ) );
			}
			if (! $middle) {
				return mb_substr ( $string, 0, $length, $charset ) . $etc;
			}

			return mb_substr ( $string, 0, $length / 2, $charset ) . $etc . mb_substr ( $string, - $length / 2, $length, $charset );
		}

		return $string;
	}
	

}
