@extends('layouts.auth')

@section('content')
<div class="row h-100 w-100" style="justify-content:center;display:flex" id="login-box">
    <div class="gambar">
        <img src="{{ asset('image/Vokasi-Unesa.png') }}" width="300px" height="200px" alt="">
    </div>
    <div class="card col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">{{ ucfirst(config('app.name')) }} @lang('quickadmin.qa_login')</div>
            <div class="panel-body">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Email atau Password yang anda masukkan salah. Silahkan coba lagi</strong>
                    {{-- <strong>Whoops!</strong> @lang('quickadmin.qa_there_were_problems_with_input'):
                    <br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul> --}}
                </div>
                @endif

                <form class="form-horizontal"
                      role="form"
                      method="POST"
                      action="{{ url('login') }}">
                    <input type="hidden"
                           name="_token"
                           value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label class="col-md-4 control-label">@lang('quickadmin.qa_email')</label>

                        <div class="col-md-6">
                            <input type="email"
                                   class="form-control"
                                   name="email"
                                   value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">@lang('quickadmin.qa_password')</label>

                        <div class="col-md-6">
                            <input type="password"
                                   class="form-control"
                                   name="password">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <a href="{{ route('auth.password.reset') }}">@lang('quickadmin.qa_forgot_password')</a>
                            <br>
                            <a href="{{ route('auth.register') }}">@lang('quickadmin.qa_registration')</a>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <label>
                                <input type="checkbox"
                                       name="remember"> @lang('quickadmin.qa_remember_me')
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit"
                                    class="btn btn-primary"
                                    style="margin-right: 15px;">
                                @lang('quickadmin.qa_login')
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                Contact Admin
                              </button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title" id="exampleModalLabel">Contact Admin</h3>
                                </div>
                                <div class="modal-body">
                                    Hubungi Admin 089539333737
                                </div>
                            </div>
                        </div>
                    </div>
                    

                </form>
            </div>
        </div>
    </div>
</div>
@endsection