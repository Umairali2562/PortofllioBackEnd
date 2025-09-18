@extends('layout.index')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <h2>This is Role List:</h2>
                @can('view_roles_gate')

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
                            <th scope="col">Role</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Tools</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($roles as $role)
                            <tr>
                                <th scope="row">{{$role['id']}}</th>
                                <td>{{$role['name']}}</td>
                                <td>{{$role['slug']}}</td>

                                <td>

                                    @if ($role->permissions != null)

                                        @foreach ($role->permissions as $permission)
                                            <span class="badge badge-secondary">
                                                 {{ $permission->name }}
                                              </span>
                                        @endforeach

                                    @endif


                                </td>
                                <td>
                                    @can('view_roles_gate')
                                    <a href="/roles/{{$role['id']}}"><i class="fa fa-eye"></i></a>
                                    @endcan

                                    @can('update_roles_gate')
                                    <a href="/roles/{{$role['id']}}/edit"><i class="fa fa-edit"></i></a>
                                        @endcan

                                        @can('delete_roles_gate')
                                    <a href="/roles/{{$role['id']}}/delete"><i class="fa fa-trash-o"></i></a>
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
