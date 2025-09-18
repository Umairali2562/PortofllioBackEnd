@extends('layout.index')

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Name: {{$role->name}}</h3>
                        <h4>Slug: {{$role->slug}}</h4>
                        <h4>Number of Posts: ....</h4>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Role</h5>
                        <p class="card-text">
                            ......
                        </p>
                        <h5 class="card-title">Permissions</h5>
                        <p class="card-text">
                            ......
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{url()->previous()}}" class="btn btn-primary">Go Back</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
