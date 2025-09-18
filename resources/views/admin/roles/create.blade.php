@extends('layout.index')


@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-xl-12">
                    <h2>Add Roles</h2>
                @can('create_roles_gate')
                <form method="post" action="/roles" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="">Role</label>
                        <input type="text" name="role_name" class="form-control" id="role_name" placeholder="Role name here" value="{{old('role_name')}}">
                    </div>

                    <div class="form-group">
                        <label for="">Slug</label>
                        <input type="text" name="role_slug" tag="role_slug" class="form-control" id="role_slug" placeholder="Role Slug..." value="{{ old('role_slug') }}" required>
                    </div>


                    <div id="permissions_box">
                        <label for="roles">Select Permissions</label>
                        <br/>
                        <div class="custom-control custom-checkbox">
                            @foreach($Permissions as $Permission)
                                <input class="custom-control-input" type="checkbox" name="roles_permissions[]" value="{{$Permission->id}}">
                                <label style="margin-left: 6px;" class="custom-control-label">{{$Permission->name}}</label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group pt2">
                        <input class="btn btn-primary" type="submit" value="Submit">
                    </div>

                </form>
                @endcan
            </div>
        </div>
    </div>
@endsection

@section('css_role_page')

    <link rel="stylesheet" href="/css/tagsinput.css">
@endsection

@section('js_role_page')

    <script src="/js/tagsinput.js"></script>
    <script>

        $(document).ready(function(){
            $('#role_name').keyup(function(e){
                var str = $('#role_name').val();
                str = str.replace(/\W+(?!$)/g, '-').toLowerCase();//rplace stapces with dash
                $('#role_slug').val(str);
                $('#role_slug').attr('placeholder', str);
            });
        });

    </script>

@endsection
