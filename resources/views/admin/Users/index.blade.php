@extends('layout.index')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h2>This is the Users List:</h2>

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

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @can('view_users_gate')
                <div class="product-main">


                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Tools</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{$user['id']}}</th>
                                <td>{{$user['name']}}</td>
                                <td>{{$user['email']}}</td>
                                <td>
                                    @if ($user->roles->isNotEmpty())
                                        @foreach ($user->roles as $role)
                                            <span class="badge badge-secondary">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                        @if ($user->permissions->isNotEmpty())
                                            @foreach ($user->permissions as $permission)
                                                <span class="badge badge-secondary">
                                                {{ $permission->name }}
                                            </span>
                                            @endforeach
                                        @endif

                                </td>
                                <td>
                                    @can('view_users_gate')
                                    <a href="/users/{{$user['id']}}"><i class="fa fa-eye"></i></a>
                                    @endcan
                                    @can('update_users_gate')
                                        <a href="/users/{{$user['id']}}/edit"><i class="fa fa-edit"></i></a>
                                        @endcan
                                            @can('delete_users_gate')
                                    <a href="/users/{{$user['id']}}/delete"><i class="fa fa-trash-o"></i></a>
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
