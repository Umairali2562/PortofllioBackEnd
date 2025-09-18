@extends('layout.index')


@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-12 col-xl-12">
                <h2>Add Permissions</h2>
                @can('create_permissions_gate')
                <form method="post" action="/Permissions" enctype="multipart/form-data">
                    {{csrf_field()}}

                    <div class="form-group" >
                        <label for="roles_permissions">Permissions</label>
                        <input type="text" data-role="tagsinput" name="Permissions" class="form-control" id="permissions">
                    </div>


                    <div class="form-group pt2">
                        <input class="btn btn-primary" type="submit" value="Add Permissions">
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
            $('#permissions').keyup(function(e){
                var str = $('#permissions').val();
                str = str.replace(/\W+(?!$)/g, '-').toLowerCase();//rplace stapces with dash
                $('#permissions-slug').val(str);
                $('#permissions-slug').attr('placeholder', str);
            });
        });

    </script>

@endsection
