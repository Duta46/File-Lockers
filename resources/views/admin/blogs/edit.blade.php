@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.blogs.title')</h3>
    
    {!! Form::model($blog, ['method' => 'PUT', 'route' => ['admin.blogs.update', $blog->id], 'blog' => true,]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('folder_id', trans('quickadmin.blogs.fields.folder').'*', ['class' => 'control-label']) !!}
                    {!! Form::select('folder_id', $folders, old('folder_id'), ['class' => 'form-control select2', 'required' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('folder_id'))
                        <p class="help-block">
                            {{ $errors->first('folder_id') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('title', trans('quickadmin.files.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => '']) !!}
                    
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('description', trans('quickadmin.files.fields.description').'*', ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'summernote', 'rows' => 3, 'required' => '']) !!}
                    
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
<script>
    $(document).ready(function() {
            $('#description').summernote();
        });
</script>
@endsection
