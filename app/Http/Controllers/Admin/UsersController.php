<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Consts;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->routeDefault  = 'users';
        $this->viewPart = 'admin.pages.users';
        $this->responseData['module_name'] = __('Public user management');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 
        
        $keyword = trim($request->input('keyword'));

        $rows = User::paginate(Consts::DEFAULT_PAGINATE_LIMIT);
        $this->responseData['rows'] = $rows;
        $this->responseData['keyword'] = $keyword;
        return $this->responseView($this->viewPart . '.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return $this->responseView($this->viewPart . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
        ]);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        User::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $this->responseData['user'] = $user;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins,email," . $user->id,
        ]);
        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->back()->with('errorMessage', __('Password is very short!'));
            }
            $params['password'] = $password_new;
        }
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $user->fill($params);
        $user->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
