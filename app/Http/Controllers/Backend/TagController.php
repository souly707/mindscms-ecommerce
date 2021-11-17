<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\TagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'manage_tags, show_tags')) {
            return redirect('admin/index');
        }
        $tags = Tag::with('products')
            ->when(request()->keyword != null, function ($query) {
                $query->search(request()->keyword);
            })
            ->when(request()->status != null, function ($query) {
                $query->whereStatus(request()->status);
            })
            ->orderBy(request()->sort_by ?? 'id', request()->order_by ?? 'desc')
            ->paginate(request()->limt_by ?? 10);

        return view('backend.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/index');
        }
        return view('backend.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagRequest $request)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'create_tags')) {
            return redirect('admin/index');
        }

        // Create the record in database
        Tag::create($request->validated());

        return \redirect()->route('admin.tags.index')->with([
            'message' => 'Tag Created Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'display_tags')) {
            return redirect('admin/index');
        }

        return view('backend.tags.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/index');
        }

        return view('backend.tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TagRequest $request, Tag $tag)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'update_tags')) {
            return redirect('admin/index');
        }

        $input['name']      = $request->name;
        $input['slug']      = null;
        $input['status']    = $request->status;

        $tag->update($input);

        return \redirect()->route('admin.tags.index')->with([
            'message' => 'Tag Updated Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        // Roles And Permissions Granted To View This Page
        if (!auth()->user()->ability('admin', 'delete_tags')) {
            return redirect('admin/index');
        }

        $tag->delete();

        return \redirect()->route('admin.tags.index')->with([
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }
}