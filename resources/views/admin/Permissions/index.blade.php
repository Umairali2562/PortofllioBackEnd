@extends('layout.index')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h2>This is the Permissions List:</h2>
                @can('view_permissions_gate')
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
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="product-main">

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Tools</th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach($Permissions as $permissions)
                            <tr>
                                <th scope="row">{{$permissions['id']}}</th>
                                <td>{{$permissions['name']}}</td>
                                <td>{{$permissions['slug']}}</td>

                                <td>
                                    @can('view_permissions_gate')
                                    <a href="/Permissions/{{$permissions['id']}}"><i class="fa fa-eye"></i></a>
                                    @endcan

                                    @can('update_permissions_gate')
                                    <a href="/Permissions/{{$permissions['id']}}/edit"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('delete_permissions_gate')
                                    <a href="/Permissions/{{$permissions['id']}}/delete"><i class="fa fa-trash-o"></i></a>
                                        @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>
                @endcan
            </div>
        </div>
    </div>

@endsection
