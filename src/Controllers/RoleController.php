<?php

namespace NAdminPanel\AdminPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NAdminPanel\AdminPanel\Models\PermissionLabel;
use NAdminPanel\AdminPanel\Models\Role;
use NAdminPanel\AdminPanel\Repositories\AdminPanelRepository;
use NAdminPanel\AdminPanel\Requests\RoleRequest;
use Yajra\Datatables\Facades\Datatables;

class RoleController extends Controller
{
    protected $accessPermission;
    protected $viewDir;
    protected $adminRepo;

    public function __construct()
    {
        $this->accessPermission = ' '.'role';
        $this->viewDir = 'nadminpanel::';
        $this->adminRepo = new AdminPanelRepository;
    }

    public function index(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission, $request);

        if ($request->ajax()) {
            $query = Role::all();
            return Datatables::of($query)
                ->addColumn('permissions', function ($role) {
                    $permissions = $role->permissions()->get();
                    $permissions_str = '';
                    foreach ($permissions as $key => $permission) {
                        if ($key != count($permissions) - 1) {
                            $permissions_str .= $permission->name . ', ';
                        } else {
                            $permissions_str .= $permission->name;
                        }
                    }
                    if ($permissions_str == '') $permissions_str = 'No Permission';
                    return '<a href="#" data-toggle="popover" data-content="' . $permissions_str . '"><button class="btn btn-xs btn-success">Permissions</button></a>';
                })
                ->addColumn('action', function ($role) {
                    return view($this->viewDir . 'admin.datatable.role', compact('role'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }
        return view($this->viewDir . 'role.indexOrArchive');
    }

    public function create()
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

        $permission_labels = PermissionLabel::all()->chunk(2);
        return view($this->viewDir.'role.createOrEdit', compact('permission_labels'));
    }

    public function store(RoleRequest $request)
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

        $role = new Role;
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->save();

        if($request->has('permissions')) {
            $role->permissions()->attach($request->input('permissions'));
        }
        return redirect()->route('role.index');
    }

    public function show($id)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission);

        $role = Role::find($id);
        return view($this->viewDir.'role.show', compact('role'));
    }

    public function edit($id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        $role = Role::find($id);
        $permission_labels = PermissionLabel::all()->chunk(2);
        return view($this->viewDir.'role.createOrEdit', compact('role', 'permission_labels'));
    }

    public function update(RoleRequest $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        $role = Role::find($id);
        if($role)
        {
            $role->name = $request->input('name');
            $role->display_name = $request->input('display_name');
            $role->save();

            if($request->has('permissions')) {
                $role->permissions()->sync($request->input('permissions'));
            } else {
                $role->permissions()->detach();
            }
        }

        return redirect()->route('role.index');
    }

    public function destroy($id)
    {
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        Role::destroy($id);
        return response()->json(['status'=>'deleted']);
    }

    public function indexArchive(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission, $request);

        if($request->ajax()){
            $query = Role::onlyTrashed()->get();
            return Datatables::of($query)
                ->addColumn('permissions', function ($role) {
                    $permissions = $role->permissions()->get();
                    $permissions_str = '';
                    foreach ($permissions as $key => $permission) {
                        if($key != count($permissions)-1) {
                            $permissions_str .= $permission->name.', ';
                        } else {
                            $permissions_str .= $permission->name;
                        }
                    }
                    if($permissions_str == '') $permissions_str = 'No Permission';
                    return '<a href="#" data-toggle="popover" data-content="'.$permissions_str.'"><button class="btn btn-xs btn-success">Permissions</button></a>';
                })
                ->addColumn('action', function ($role) {
                    return view($this->viewDir.'admin.datatable.role', compact('role'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['permissions', 'action'])
                ->make(true);
        }
        return view($this->viewDir.'role.indexOrArchive');
    }

    public function unarchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        Role::onlyTrashed()->findOrFail($id)->restore();
        if ($request->ajax()) {
            return response()->json(['status' => 'unarchived']);
        }
    }

    public function destroyArchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        Role::onlyTrashed()->findOrFail($id)->forceDelete();
        if ($request->ajax()) {
            return response()->json(['status'=>'deleted']);
        }
    }
}
