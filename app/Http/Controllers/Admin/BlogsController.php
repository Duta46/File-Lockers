<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Request;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Admin\StoreBlogsRequest;
use App\Http\Requests\Admin\UpdateBlogsRequest;
use Illuminate\Http\Request as NRequest;

class BlogsController extends Controller
{
    public function index(){
        if (!Gate::allows('blog_access')) {
            return abort(401);
        }
        if ($filterBy = Request::get('filter')) {
            if ($filterBy == 'all') {
                Session::put('Blog.filter', 'all');
            } elseif ($filterBy == 'my') {
                Session::put('Blog.filter', 'my');
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
        $userBlogsCount = Blog::where('created_by_id', $user->id)->count();

        return view('admin.blogs.index', compact('blogs', 'userBlogsCount'));
    }

    public function create()
    {
        if (! Gate::allows('blog_access')) {
            return abort(401);
        }

        $roleId = Auth::getUser()->role_id;

        $folders = \App\Folder::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $created_bies = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        return view('admin.blogs.create', compact('folders', 'created_bies', 'roleId'));
    }

    public function store(StoreBlogsRequest $request)
    {
        if (! Gate::allows('blog_create')) {
            return abort(401);
        }
      $input = $request->all();
      $input['description'] = strip_tags($input['description']);
      $blog = Blog::create($input);

        return redirect()->route('admin.blogs.index');
    }


    /**
     * Show the form for editing Blog.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('blog_edit')) {
            return abort(401);
        }
        
        $folders = \App\Folder::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');
        $created_bies = \App\User::get()->pluck('name', 'id')->prepend(trans('quickadmin.qa_please_select'), '');

        $blog = Blog::findOrFail($id);

        return view('admin.blogs.edit', compact('blog', 'folders', 'created_bies'));
    }

    /**
     * Update Blog in storage.
     *
     * @param  \App\Http\Requests\UpdateBlogsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogsRequest $request, $id)
    {
        if (! Gate::allows('blog_edit')) {
            return abort(401);
        }
        $blog = Blog::findOrFail($id);
        $blog->update($request->all());

        return redirect()->route('admin.blogs.index');
    }

    /**
     * Remove Blog from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('blog_delete')) {
            return abort(401);
        }
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->route('admin.blogs.index');
    }

    /**
     * Delete all selected Folder at once.
     *
     * @param Request $request
     */
    public function massDestroy(NRequest $request)
    {
        if (! Gate::allows('blog_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Blog::whereIn('id', $request->input('ids'))->get();

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
        if (! Gate::allows('blog_delete')) {
            return abort(401);
        }
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->restore();

        return redirect()->route('admin.blogs.index');
    }

    /**
     * Permanently delete Folder from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (! Gate::allows('blog_delete')) {
            return abort(401);
        }
        $blog = Blog::onlyTrashed()->findOrFail($id);
        $blog->forceDelete();

        return redirect()->route('admin.blogs.index');
    }

    public function downloadPDF($id)
    {
    // Query data blog dari database
    $blog = Blog::findOrFail($id);

    $pdf = new Dompdf();
     $pdf = Pdf::loadView('admin.blogs.download', compact('blog'));
     return $pdf->stream($blog->title . '.pdf');
    }
}
