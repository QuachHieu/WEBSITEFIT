<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Services\AdminService;
use App\Models\Role;
use App\Consts;
use App\Models\Department;
use App\Models\Duty;
use App\Models\Degree;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
        $this->routeDefault  = 'admins';
        $this->viewPart = 'admin.pages.admins';
        $this->responseData['module_name'] = __('Admin user management');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = trim($request->input('keyword'));

        $admins = $this->adminService->getAdmins($request->all(), true);

        $this->responseData['admins'] = $admins;
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
        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $Departments = Department::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Departments'] = $Departments;
        $Degree = Degree::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Degree'] = $Degree;
        $Duty = Duty::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Duty'] = $Duty;
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

        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins",
            'password' => "required|min:8|max:255",
        ]);

        $params = $request->only([
            'email',
            'name',
            'role',
            'avatar',
            'status',
            'password',
            'json_information',
        ]);
        $params['admin_created_id'] = Auth::guard('admin')->user()->id;
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        Admin::create($params);

        return redirect()->route($this->routeDefault . '.index')->with('successMessage', __('Add new successfully!'));
    }

    
    public function loadMember(Request $request)
    { 
      
        $dep = $request->dep;

        $department = Department::where('id',$dep)->first();
        $data_post = '';
        if($department){
            $members = Admin::where('status','active')->where('department_id',$dep)->where('id','!=',Auth::guard('admin')->user()->id)->get();
            $am = '';
            foreach($members as $admin){
                $am .= '<input class="checkbox _check_all _check_relative _check_all_'.$department->id.'" type="checkbox" name="user_receive[]" value="'.$admin->id.'" data-value="'.$department->id.'"> <span>'.$admin->name.'</span><br>';
            }
            $data_post .= '
            <tr class="">
                <td class="">
                    <input class="_check_all checkbox check_class" id="check_class_'.$department->id.'" type="checkbox" name="department[]" value="'.$department->id.'"> <span>'.$department->title.'</span>
                </td>
                <td class="">
                    '.$am.'
                </td>
            </tr>
            ';
        }else{

            $admins = Admin::where('status','active')->where('id','!=',Auth::guard('admin')->user()->id)->get();
            $departments = Department::where('status','active')->get();

            foreach($departments as $department){
                $members = Admin::where('status','active')->where('department_id',$dep)->get();
                $am = '';
                foreach($admins as $admin){
                    if($admin->department_id ==$department->id){
                        $am .= '<input class="checkbox _check_all _check_relative _check_all_'.$department->id.'" type="checkbox" name="user_receive[]" value="'.$admin->id.'" data-value="'.$department->id.'"> <span>'.$admin->name.'</span><br>';
                    }
                }
                $data_post .= '
                    <tr class="">
                        <td class="">
                            <input class="_check_all checkbox check_class" id="check_class_'.$department->id.'" type="checkbox" name="department[]" value="'.$department->id.'"> <span>'.$department->title.'</span>
                        </td>
                        <td class="">
                            '.$am.'
                        </td>
                    </tr>
                ';
            }

        }

        $data_post .= '
        <script>
            $(document).ready(function() {
                $(".check-all").on("change", function() {
                    if($(".check-all:checked").val() == 0){
                        $("._check_all").prop("checked",true);
                        $(".check-all-relative").prop("checked",true);
                    }else{
                        $("._check_all").prop("checked",false);
                        $(".check-all-relative").prop("checked",false);
                    }
                });
            
                $(".check_class").on("change", function() {
                    var class_id = $(this).val();
                    if($("#check_class_"+class_id+":checked").val() == class_id){
                        $("._check_all_"+class_id).prop("checked",true);
                    }else{
                        $("._check_all_"+class_id).prop("checked",false);
                    }
                    $(".check-all-relative").prop("checked",false);
                });
            
                $("._check_all").on("change", function() {
                    var class_id = $(this).attr("data-value");
                    $(".check-all").attr("disabled", false);
                    $(".check-all").prop("checked",false);
                    $("#check_class_"+class_id).prop("checked",false);
                });
            
                $(".btn-psadmin").attr("disabled", "disabled");
                
                $(".checkbox").on("change", function() {
                    if($(".checkbox:checked").val() >= 0){
                        $(".btn-psadmin").attr("disabled", false);
                    }else{
                        $(".btn-psadmin").attr("disabled", "disabled");
                    }
                });
                
                $(".check-all-relative").on("change", function() {
                    if($(".check-all-relative:checked").val() == 0){
                        $("._check_relative").prop("checked",true);
                    }else{
                        $("._check_relative").prop("checked",false);
                    }
                    $(".check-all").prop("checked",false);
                    $(".check_class").prop("checked",false);
                });
            
                $("._check_relative").on("change", function() {
                    $(".check-all").prop("checked",false);
                    $(".check-all-relative").prop("checked",false);
                });
            });
        </script>';
        return $data_post;

    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // Do not use this function
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);

        if (!$admin) {
            return redirect()->route($this->routeDefault . '.index')->with('errorMessage', __('Record not found!'));
        }

        $roles = Role::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['roles'] = $roles;
        $Departments = Department::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Departments'] = $Departments;
        $Degree = Degree::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Degree'] = $Degree;
        $Duty = Duty::where('status', '=', Consts::USER_STATUS['active'])->orderByRaw('status ASC, iorder ASC, id DESC')->get();
        $this->responseData['Duty'] = $Duty;
        $this->responseData['admin'] = $admin;

        return $this->responseView($this->viewPart . '.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|max:255|unique:admins,email," . $admin->id,
        ]);

        $params = $request->only([
            'email',
            'name',
            'avatar',
            'role',
            'status',
            'json_information',
        ]);
        $password_new = $request->input('password_new');
        if ($password_new != '') {
            if (strlen($password_new) < 8) {
                return redirect()->back()->with('errorMessage', __('Password is very short!'));
            }
            $params['password'] = $password_new;
        }
        $params['admin_updated_id'] = Auth::guard('admin')->user()->id;

        $admin->fill($params);
        $admin->save();

        return redirect()->back()->with('successMessage', __('Successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();

        return redirect()->route($this->routeDefault . '.index')->with('successMessage',  __('Delete record successfully!'));
    }
}
