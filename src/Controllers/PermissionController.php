<?php

namespace NAdminPanel\AdminPanel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NAdminPanel\AdminPanel\Models\PermissionLabel;
use NAdminPanel\AdminPanel\Repositories\CommonRepository;
use NAdminPanel\AdminPanel\Requests\PermissionRequest;
use Yajra\Datatables\Facades\Datatables;

class PermissionController extends Controller
{
    protected $access_permission;
    protected $viewDir;
    protected $common_repo;

    public function __construct()
    {
        $this->access_permission = ' '.'permission';
        $this->common_repo = new CommonRepository;
        $this->viewDir = 'nadminpanel::';
    }

    public function index(Request $request)
    {
        $this->common_repo->isHasPermissionAccess('show'.$this->access_permission, $request);

        if($request->ajax()){
            $query = PermissionLabel::all();
            return Datatables::of($query)
                ->addColumn('action', function ($permission) {
                    return view($this->viewDir.'backend.admin.datatable.permission', compact('permission'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir.'backend.permission.index_or_archive');
    }

    public function create()
    {
        $this->common_repo->isHasPermissionAccess('create'.$this->access_permission);

        return view($this->viewDir . 'backend.permission.create_or_edit');
    }

    public function store(PermissionRequest $request)
    {
        $this->common_repo->isHasPermissionAccess('create'.$this->access_permission);

        $permission = new PermissionLabel;
        $permission->name = $request->input('name');
        $permission->save();
        return redirect()->route('permission.index');
    }

    public function edit($id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        $permission = PermissionLabel::find($id);
        return view($this->viewDir . 'backend.permission.create_or_edit', compact('permission'));
    }

    public function update(PermissionRequest $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        $permission = PermissionLabel::find($id);
        if ($permission) {
            $permission->name = $request->input('name');
            $permission->save();
        }
        return redirect()->route('permission.index');
    }

    public function destroy($id)
    {
        $this->common_repo->isHasPermissionAccess('delete'.$this->access_permission);

        PermissionLabel::destroy($id);
        return response()->json(['status' => 'deleted']);
    }

    public function indexArchive(Request $request)
    {
        $this->common_repo->isHasPermissionAccess('show'.$this->access_permission, $request);

        if ($request->ajax()) {
            $query = PermissionLabel::onlyTrashed()->get();
            return Datatables::of($query)
                ->addColumn('action', function ($permission) {
                    return view($this->viewDir . 'backend.admin.datatable.permission', compact('permission'))->render();
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view($this->viewDir . 'backend.permission.index_or_archive');
    }

    public function unarchive(Request $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('edit'.$this->access_permission);

        PermissionLabel::onlyTrashed()->findOrFail($id)->restore();
        if ($request->ajax()) {
            return response()->json(['status' => 'unarchived']);
        }
    }

    public function destroyArchive(Request $request, $id)
    {
        $this->common_repo->isHasPermissionAccess('delete'.$this->access_permission);

        PermissionLabel::onlyTrashed()->findOrFail($id)->forceDelete();
        if ($request->ajax()) {
            return response()->json(['status' => 'deleted']);
        }

    }
}
