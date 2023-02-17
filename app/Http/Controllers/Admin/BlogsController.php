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
        if (!Gate::allows('blog_access')) {
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
            $files = Blog::onlyTrashed()->get();
        } else {
            $files = Blog::all();
        }
        $user = Auth::getUser();
        $userFilesCount = Blog::where('created_by_id', $user->id)->count();

        return view('admin.blogs.index', compact('blogs', 'userFilesCount'));
    }
}
