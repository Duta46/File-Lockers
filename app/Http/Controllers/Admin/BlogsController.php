<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use App\Http\Request\Admin\StoreBlogsRequest;
use App\Http\Requests\Admin\UpdateBlogsRequest;

use Illuminate\Http\Request as NRequest;

class BlogsController extends Controller
{
    public function index(){
        if (!Gate::allows('plan_access')) {
            return abort(401);
        }
        if ($filterBy = Request::get('filter')) {
            if ($filterBy == 'all') {
                Session::put('File.filter', 'all');
            } elseif ($filterBy == 'my') {
                Session::put('File.filter', 'my');
            }
        }

        if (request('show_deleted') == 1) {
            if (!Gate::allows('blog_delete')) {
                return abort(401);
            }
            $blogs = Blog::onlyTrashed()->get();
        } else {
            $blogs = Blog::all();
        }
        $user = Auth::getUser();
        $userFilesCount = Blog::where('created_by_id', $user->id)->count();

        return view('admin.blogs.index', compact('blogs', 'userFilesCount'));
    }

    public function create()
    {
        if (! Gate::allows('blog_create')) {
            return abort(401);
        }
        
        $created_bies = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.blogs.create', compact('created_bies'));
    }

    public function store(StoreFoldersRequest $request)
    {
        if (! Gate::allows('folder_create')) {
            return abort(401);
        }
        $folder = Folder::create($request->all());



        return redirect()->route('admin.folders.index');
    }


    /**
     * Show the form for editing Folder.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('folder_edit')) {
            return abort(401);
        }
        
        $created_bies = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $folder = Folder::findOrFail($id);

        return view('admin.folders.edit', compact('folder', 'created_bies'));
    }

    /**
     * Update Folder in storage.
     *
     * @param  \App\Http\Requests\UpdateFoldersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFoldersRequest $request, $id)
    {
        if (! Gate::allows('folder_edit')) {
            return abort(401);
        }
        $folder = Folder::findOrFail($id);
        $folder->update($request->all());



        return redirect()->route('admin.folders.index');
    }


    /**
     * Display Folder.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('folder_view')) {
            return abort(401);
        }
        
        $created_bies = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');$files = \App\File::where('folder_id', $id)->get();

        $folder = Folder::findOrFail($id);
        $userFilesCount = File::where('created_by_id', Auth::getUser()->id)->count();

        return view('admin.folders.show', compact('folder', 'files', 'userFilesCount'));
    }


    /**
     * Remove Folder from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('folder_delete')) {
            return abort(401);
        }
        $folder = Folder::findOrFail($id);
        $folder->delete();

        return redirect()->route('admin.folders.index');
    }

    /**
     * Delete all selected Folder at once.
     *
     * @param Request $request
     */
    public function massDestroy(NRequest $request)
    {
        if (! Gate::allows('folder_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Folder::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Folder from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (! Gate::allows('folder_delete')) {
            return abort(401);
        }
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $folder->restore();

        return redirect()->route('admin.folders.index');
    }

    /**
     * Permanently delete Folder from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('folder_delete')) {
            return abort(401);
        }
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $folder->forceDelete();

        return redirect()->route('admin.folders.index');
    }
}
