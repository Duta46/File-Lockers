@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@section('content')
<h3 class="page-title">@lang('quickadmin.blogs.title')</h3>

    @can('blog_access')
        <p>
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>

            @if(!is_null(Auth::getUser()->role_id) && config('quickadmin.can_see_all_records_role_id') == Auth::getUser()->role_id)
                @if(Session::get('Blog.filter', 'all') == 'my')
                    <a href="?filter=all" class="btn btn-default">Show all records</a>
                @else
                    <a href="?filter=my" class="btn btn-default">Filter my records</a>
                @endif
            @endif
        </p>
    @endcan

    @can('file_delete')
        <p>
        <ul class="list-inline">
            <li><a href="{{ route('admin.blogs.index') }}" style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">@lang('quickadmin.qa_all')</a></li>
            |
            <li><a href="{{ route('admin.blogs.index') }}?show_deleted=1" style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">@lang('quickadmin.qa_trash')</a></li>
        </ul>
        </p>
    @endcan


    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($blogs) > 0 ? 'datatable' : '' }} @can('file_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                <thead>
                <tr>
                    @can('file_delete')
                        @if ( request('show_deleted') != 1 )
                            <th style="text-align:center;"><input type="checkbox" id="select-all"/></th>@endif
                    @endcan

                    <th>Title</th>
                    <th>Description</th>
                    {{-- <th>Filename</th> --}}
                    <th>Folder</th>
                    @if( request('show_deleted') == 1 )
                        <th>&nbsp;</th>
                    @else
                        <th>&nbsp;</th>
                    @endif
                </tr>
                </thead>

                <tbody>

                @if (count($blogs) > 0)
                    @foreach ($blogs as $blog)
                        <tr data-entry-id="{{ $blog->id }}">
                            @can('blog_delete')
                                @if ( request('show_deleted') != 1 )
                                    <td></td>@endif
                            @endcan
                            <td field-key='title'>{{ $blog->title }}</td>
                            <td field-key='description'>{{ $blog->description }}</td>
                            {{-- <td field-key='filename'> @foreach($file->getMedia('filename') as $media)
                                    <p class="form-group">
                                        <a href="{{url('/admin/' . $file->uuid . '/download')}}" target="_blank">{{ $media->name }} ({{ $media->size }} KB)</a>
                                    </p>
                                @endforeach</td> --}}
                            <td field-key='folder'>{{ $blog->folder->name }}</td>
                            @if( request('show_deleted') == 1 )
                            <td>
                                @can('blog_delete')
                                    {!! Form::open(array(
                                                            'style' => 'display: inline-block;',
                                                            'method' => 'POST',
                                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                                            'route' => ['admin.blogs.restore', $blog->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
                                    {!! Form::close() !!}
                                @endcan
                                @can('blog_delete')
                                    {!! Form::open(array(
                                                            'style' => 'display: inline-block;',
                                                            'method' => 'DELETE',
                                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                                            'route' => ['admin.blogs.perma_del', $blog->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        @else
                            <td>
                                <a href="{{ route('admin.blogs.download', ['id' => $blog->id]) }}" class="btn btn-xs btn-success">Download</a>

                                @can('blog_edit')
                                    <a href="{{ route('admin.blogs.edit',[$blog->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                @endcan
                                @can('blog_delete')
                                    {!! Form::open(array(
                                                            'style' => 'display: inline-block;',
                                                            'method' => 'DELETE',
                                                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                                            'route' => ['admin.blogs.destroy', $blog->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">@lang('quickadmin.qa_no_entries_in_table')</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
@stop

@section('javascript')
<script>
    $(document).ready(function () {
//            var table = $('#myTable_Wrapper').DataTable();
//console.log(table);
//            table.button( '.dt-button' ).remove();
    })
</script>
<script>
    @can('folder_delete')
            @if ( request('show_deleted') != 1 ) window.route_mass_crud_entries_destroy = '{{ route('admin.folders.mass_destroy') }}'; @endif
    @endcan

</script>
@endsection