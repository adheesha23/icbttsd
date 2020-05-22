@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('User Details') }}</div>

                    <div class="card-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="form-group row">
                            <div class="card col-md-3">
                                <img class="card-img-top" src="{{url('uploads/'.$user->filename)}}" alt="{{$user->filename}}">
                            </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6 col-form-label text-md-left">
                                    {{$user->name}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6 col-form-label text-md-left">
                                    {{$user->email}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

                                <div class="col-md-6 col-form-label text-md-left">
                                    {{$user->role == 1 ? 'Admin' : ($user->role == 2 ? 'Manager' : 'Auditor')}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
