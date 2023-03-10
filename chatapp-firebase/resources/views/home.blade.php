@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Posts') }}</div>
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">See Profile</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($provider as $user)
                            <tr>
                                <th scope="row">{{$user->name}}</th>
                                <td>{{$user->email}}</td>
                                <td><a href="{{route('profile',$user->id)}}" class="btn btn-info">Click here</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
