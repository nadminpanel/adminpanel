<?php

namespace NAdminPanel\AdminPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NAdminPanel\AdminPanel\Models\PermissionLabel;
use NAdminPanel\AdminPanel\Repositories\AdminPanelRepository;
use NAdminPanel\AdminPanel\Requests\PermissionRequest;
use Yajra\DataTables\Facades\Datatables;

class PermissionController extends Controller
{
    protected $accessPermission;
    protected $viewDir;
    protected $adminRepo;

    public function __construct()
    {
        $this->accessPermission = ' '.'permission';
        $this->adminRepo = new AdminPanelRepository;
        $this->viewDir = 'nadminpanel::';
    }

    public function index(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission, $request);

        if($request->ajax()){
            $query = PermissionLabel::all();
            return $this->datatable($query);
        }
        return view($this->viewDir.'permission.indexOrArchive');
    }

    public function create()
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

        return view($this->viewDir . 'permission.createOrEdit');
    }

    public function store(PermissionRequest $request)
    {
        $this->adminRepo->isHasPermissionAccess('create'.$this->accessPermission);

        $permission = new PermissionLabel;
        $permission->name = $request->input('name');
        $permission->save();
        return redirect()->route('permission.index');
    }

    public function edit($id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        $permission = PermissionLabel::find($id);
        return view($this->viewDir . 'permission.createOrEdit', compact('permission'));
    }

    public function update(PermissionRequest $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        $permission = PermissionLabel::find($id);
        if ($permission) {
            $permission->name = $request->input('name');
            $permission->save();
        }
        return redirect()->route('permission.index');
    }

    public function destroy($id)
    {
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        PermissionLabel::destroy($id);
        return response()->json(['status' => 'deleted']);
    }

    public function indexArchive(Request $request)
    {
        $this->adminRepo->isHasPermissionAccess('show'.$this->accessPermission, $request);

        if ($request->ajax()) {
            $query = PermissionLabel::onlyTrashed()->get();
            return $this->datatable($query);
        }
        return view($this->viewDir . 'permission.indexOrArchive');
    }

    public function unarchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('edit'.$this->accessPermission);

        PermissionLabel::onlyTrashed()->findOrFail($id)->restore();
        if ($request->ajax()) {
            return response()->json(['status' => 'unarchived']);
        }
    }

    public function destroyArchive(Request $request, $id)
    {
        $this->adminRepo->isHasPermissionAccess('delete'.$this->accessPermission);

        PermissionLabel::onlyTrashed()->findOrFail($id)->forceDelete();
        if ($request->ajax()) {
            return response()->json(['status' => 'deleted']);
        }

    }

    private function datatable($query)
    {
        return Datatables::of($query)
            ->addColumn('action', function ($permission) {
                return view($this->viewDir . 'admin.datatable.permission', compact('permission'))->render();
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
