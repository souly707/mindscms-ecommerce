<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\Backend\SupervisorRequest;
use App\Models\UserPermissions;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'manage_supervisors, show_supervisors')) {
            return redirect('admin/index');
        }
        $supervisors = User::whereHas('roles', function ($query) {
            $query->where('name', 'supervisor');
        })
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limt_by ?? 10);

        return view('backend.supervisors.index', compact('supervisors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        return view('backend.supervisors.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupervisorRequest $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_supervisors')) {
            return redirect('admin/index');
        }

        $input['first_name']        = $request->first_name;
        $input['last_name']         = $request->last_name;
        $input['username']          = $request->username;
        $input['email']             = $request->email;
        $input['mobile']            = $request->mobile;
        $input['status']            = $request->status;
        $input['password']          = bcrypt($request->password);

        // Image Upload
        if ($image = $request->file('user_image')) {
            $file_name = Str::slug($request->username) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $input['user_image'] = $file_name;
        }

        // Create the record in database
        $supervisor = User::create($input);
        $supervisor->markEmailAsVerified();
        $supervisor->attachRole(Role::whereName('supervisor')->first()->id);
        // Add Permissions
        if (isset($request->permissions) && count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }
        //$supervisor->save();

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'supervisor Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $supervisor)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'display_supervisors')) {
            return redirect('admin/index');
        }

        return view('backend.supervisors.show', \compact('supervisor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $supervisor)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/index');
        }

        $permissions = Permission::get(['id', 'display_name']);
        $supervisorPermissions = UserPermissions::whereUserId($supervisor->id)->pluck('permission_id')->toArray();

        return view('backend.supervisors.edit', compact('supervisor', 'permissions', 'supervisorPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(supervisorRequest $request, User $supervisor)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_supervisors')) {
            return redirect('admin/index');
        }

        $input['first_name']        = $request->first_name;
        $input['last_name']         = $request->last_name;
        $input['username']          = $request->username;
        $input['email']             = $request->email;
        $input['mobile']            = $request->mobile;
        $input['status']            = $request->status;

        if (trim($request->passowrd) != '') {
            $input['password']      = bcrypt($request->password);
        }

        // Image Upload
        if ($image = $request->file('user_image')) {

            if ($supervisor->user_image != null && File::exists('assets/users/' . $supervisor->user_image)) {
                unlink('assets/users/' . $supervisor->user_image);
            }

            $file_name = Str::slug($request->username) . '.' . $image->getClientOriginalExtension();
            $path = public_path('/assets/users/' . $file_name);
            Image::make($image->getRealPath())->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($path, 100);

            $input['user_image'] = $file_name;
        }

        $supervisor->update($input);
        // Permission Update
        if (isset($request->permissions) && \count($request->permissions) > 0) {
            $supervisor->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'supervisor Updated Successfully',
            'alert-type' => 'success'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $supervisor)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/index');
        }

        if (File::exists('assets/users/' . $supervisor->user_image) && $supervisor->user_image != null) {
            unlink('assets/users/' . $supervisor->user_image);
        }

        $supervisor->delete();

        return redirect()->route('admin.supervisors.index')->with([
            'message' => 'supervisor Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the Image resource from storage.
     *
     * @param  int  $id
     * @param  int  Request
     * @return \Illuminate\Http\Response
     */

    public function remove_image(Request $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_supervisors')) {
            return redirect('admin/index');
        }

        $supervisor = User::findOrFail($request->supervisor_id);

        if (File::exists('assets/users/' . $supervisor->user_image)) {
            unlink('assets/users/' . $supervisor->user_image);
            $supervisor->user_image = null;
            $supervisor->save();
        }
        return true;
    }
}