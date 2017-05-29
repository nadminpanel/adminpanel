<?php

namespace NAdminPanel\AdminPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NAdminPanel\AdminPanel\Models\Role;
use NAdminPanel\AdminPanel\Models\User;
use NAdminPanel\AdminPanel\Repositories\AdminPanelRepository;
use NAdminPanel\AdminPanel\Requests\AdminRequest;
use Yajra\Datatables\Facades\Datatables;

class UserController extends Controller
{
    protected $accessPermission;
    protected $viewDir;
    protected $adminRepo;

    public function __construct()
    {
        $this->accessPermission = ' '.'user';
        $this->viewDir = "nadminpanel::";
        $this->adminRepo = new AdminPanelRepository;
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
        return view($this->viewDir.'admin.common.login');
    }

    public function index(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission, $request);

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
                    return view($this->viewDir.'admin.datatable.'.$role, compact('user'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir.'admin.indexOrArchive', compact('role'));
    }

    public function create()
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

        $roles = Role::all()->chunk(4);
        return view($this->viewDir.'admin.createOrEdit', compact('roles'));
    }

    public function store(AdminRequest $request)
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

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
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        $user = User::find($id);
        $roles = Role::all()->chunk(4);
        return view($this->viewDir.'admin.createOrEdit', compact('user', 'roles'));
    }

    public function update(AdminRequest $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

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
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        User::destroy($id);
        return response()->json(['status'=>'deleted']);
    }

    public function indexArchive(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission, $request);

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
                    return view($this->viewDir.'admin.datatable.'.$role, compact('user'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir.'admin.indexOrArchive', compact('role'));
    }

    public function unarchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        User::onlyTrashed()->findOrFail($id)->restore();
        if ($request->ajax()) {
            return response()->json(['status'=>'unarchived']);
        }
    }

    public function destroyArchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        User::onlyTrashed()->findOrFail($id)->forceDelete();
        if ($request->ajax()) {
            return response()->json(['status'=>'deleted']);
        }
    }

    public function dashboard()
    {
        return view($this->viewDir.'admin.common.dashboard');
    }

}
