@extends('app')

@section('login_form')
<div class="row">
<div class="tail">
    
    <h1 class="title">Login</h1>
    
</div>
</div>
<div class="row">

<form method="POST" action="/auth/login" class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        
        <label for="username" class="control-label col-sm-12">Username</label>
        <div class="col-sm-12">
            <input type="text" name="name" class="form-control" placeholder="Username">
        </div>
        
    </div>
    
    <div class="form-group">
        
        <label for="password" class="control-label col-sm-12">Password</label>
        <div class="col-sm-12">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        
    </div>
    
    <div class="form-group">
        <label for="remember"><input type="checkbox" name="remember"> Remember Me</label>
    </div>
    
    <div class="form-group text-center">        
        <input type="submit" class="btn btn-icom" value="Login">
    </div>
    
</form>
</div>
<div class="row">
@if(count($errors->all()) > 0 )

<div class="error text-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
</div>

@endif
</div>
@endsection
