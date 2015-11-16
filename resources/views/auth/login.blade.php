@extends('app')

@section('login_form')
<div class="tail">
    
    <h1 class="title">Login</h1>
    
</div>

<form method="POST" action="/auth/login">
   <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        
        <label for="username" class="control-label col-sm-2">Username</label>
        <div class="col-sm-10">
            <input type="text" name="name" class="form-control" placeholder="Username">
        </div>
        
    </div>
    
    <div class="form-group">
        
        <label for="password" class="control-label col-sm-2 col-xs-2">Password</label>
        <div class="col-sm-10">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        
    </div>
    
    <div class="form-group text-center">        
        <input type="submit" class="btn btn-icom" value="Login">
    </div>
    
</form>
@if(count($errors->all()) > 0 )

<div class="error">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    </ul>
</div>

@endif
@endsection
