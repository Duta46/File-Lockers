@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.blogs.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body table-responsive">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.blogs.fields.folder')</th>
                            <td field-key='folder'>{{ $file->folder->name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.blogs.fields.title')</th>
                            <td field-key='title'>{{ $file->title->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.blogs.fields.description')</th>
                            <td field-key='description'>{{ $file->description->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.blogs.fields.created-by')</th>
                            <td field-key='created_by'>{{ $file->created_by->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.blogs.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
        </div>
    </div>
@stop
