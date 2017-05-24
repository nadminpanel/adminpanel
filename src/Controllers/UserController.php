<?php

namespace NAdminPanel\AdminPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NAdminPanel\AdminPanel\Models\Role;
use NAdminPanel\AdminPanel\Models\User;
use NAdminPanel\AdminPanel\Repositories\CommonRepository;
use NAdminPanel\AdminPanel\Requests\AdminRequest;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller
{
    protected $access_permission;
    protected $viewDir;
    protected $common_repo;

    public function __construct()
    {
        $this->access_permission = ' '.'user';
        $this->viewDir = "nadminpanel::";
        $this->common_repo = new CommonRepository;
    }

    public function showAdminLoginForm()
    {
        if(Auth::check()) {
            if(Auth::user()->hasAnyRole(['admin', 'developer'])) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->to(config('nadminpanel.user_landing_link'));
            }
        }
        return view($this->viewDir.'backend.admin.common.login');
    }

    public function index(Request $request)
    {
        $this->common_repo->isHasPermissionAccess('show'.$this->access_permission, $request);

        $role = (request()->route()->uri() == config('nadminpanel.admin_backend_prefix').'/admin') ? 'admin' : 'user';
        if($request->ajax()){
            if($role == 'admin') {
                $query = User::role($role)->get();
            } else {
                $query = User::whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                })->get();
            }
            return Datatables::of($query)
                ->addColumn('action', function ($user) use ($role) {
                    return view($this->viewDir.'backend.admin.datatable.'.$role, compact('user'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir.'backend.admin.index_or_archive', compact('role'));
    }

    public function create()
    {
        $this->common_repo->isHasPermissionAccess('create'.$this->access_permission);

        $roles = Role::all()->chunk(4);
        return view($this->viewDir.'backend.admin.create_or_edit', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $this->common_repo->isHasPermissionAccess('create'.$this->access_permission);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        if($request->has('roles')) {
            $user->roles()->attach($request->input('roles'));
        }

        return redirect()->route('user.index');
    }

    public function edit($id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        $user = User::find($id);
        $roles = Role::all()->chunk(4);
        return view($this->viewDir.'backend.admin.create_or_edit', compact('user', 'roles'));
    }

    public function update(AdminRequest $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        if($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        $this->common_repo->isHasPermissionAccess('delete'.$this->access_permission);

        User::destroy($id);
        return response()->json(['status'=>'deleted']);
    }

    public function indexArchive(Request $request)
    {
        $this->common_repo->isHasPermissionAccess('create'.$this->access_permission, $request);

        $role = (request()->route()->uri() == config('nadminpanel.admin_backend_prefix').'/admin/archive') ? 'admin' : 'user';
        if($request->ajax()){
            if($role == 'admin') {
                $query = User::onlyTrashed()->role($role)->get();
            } else {
                $query = User::onlyTrashed()->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'admin');
                })->get();
            }
            return Datatables::of($query)
                ->addColumn('action', function ($user) use ($role) {
                    return view($this->viewDir.'backend.admin.datatable.'.$role, compact('user'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir.'backend.admin.index_or_archive', compact('role'));
    }

    public function unarchive(Request $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        User::onlyTrashed()->findOrFail($id)->restore();
        if ($request->ajax()) {
            return response()->json(['status'=>'unarchived']);
        }
    }

    public function destroyArchive(Request $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('delete'.$this->access_permission);

        User::onlyTrashed()->findOrFail($id)->forceDelete();
        if ($request->ajax()) {
            return response()->json(['status'=>'deleted']);
        }
    }

    public function dashboard()
    {
        return view($this->viewDir.'backend.admin.common.dashboard');
    }

}
