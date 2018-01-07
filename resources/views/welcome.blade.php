@extends('layouts.master')

@section('title')
    Welcome
@endsection

@section('content')
    @include('includes.message-block')
    <div class="row">
        <div class="col-md-6">
            <h3>Sign Up</h3>
            <form action="{{ route('signup') }}" method="post">
                <div class="form-group {{ $errors->has('first_name')?'has-error' : ''  }}">
                    <label for="first_name">Your Name</label>
                    <input class="form-control" type="text" name="first_name" id="first_name" required>
                </div>
                <div class="form-group {{ $errors->has('email')?'has-error' : ''  }}">
                    <label for="email">Your E-Mail</label>
                    <input class="form-control " type="text" name="email" id="email" value=" {{Request::old('email')}}" required>
                </div>
                <div class="form-group {{ $errors->has('password')?'has-error' : ''  }}">
                    <label for="password">Your Password</label>
                    <input class="form-control" type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
                {{  csrf_field() }}
            </form>
        </div>
        <div class="col-md-6">
            <h3>Sign In</h3>
            <form action="{{ route('signin') }}" method="post">
                <div class="form-group {{ $errors->has('email')?'has-error' : ''  }}">
                    <label for="email">Your E-Mail</label>
                    <input class="form-control" type="text" name="email" id="email" value=" {{Request::old('email')}}" required>
                </div>
                <div class="form-group">
                    <label for="password">Your Password</label>
                    <input class="form-control" type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
                {{  csrf_field() }}
            </form>
        </div>
    </div>
@endsection