<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Admin')
    @section('content-helper')
        <div class="col-sm-2 ml-auto">
            <a href="{{ url('admin/create') }}">
                <button type="button" class="btn btn-block btn-info btn-sm">Create Admin</button>
            </a>
        </div>
    @endsection
    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        {{ $item->id }}
                                    </td>
                                    <td>
                                        {{ $item->fullname }}
                                    </td>
                                    <td>
                                        {{ $item->email }}
                                    </td>
                                    <td>
                                        <a href="{{ url('/admin/'.$item->id) }}">
                                            <button type="button" class="btn btn-block btn-outline-secondary btn-xs"><i class="fas fa-edit"></i>Edit</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    @include('component.paginator')
                </div>
            </div>
        </div>
    @endsection
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

</html>