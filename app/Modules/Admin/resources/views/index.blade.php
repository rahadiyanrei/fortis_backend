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
                            <th>Last Login</th>
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
                                        {{ $item->last_login ? Carbon\Carbon::parse($item->last_login)->diffForHumans() : 'Not yet' }}
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="{{ url('/admin/'.$item->id) }}">
                                                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs"><i class="fas fa-edit"></i>Edit</button>
                                                </a>
                                            </div>
                                            @if($item->status === 1)
                                            <div class="col-md-6">
                                                <a href="#" data-toggle="modal" data-target="#modal-confirmation-delete">
                                                    <button type="button" class="btn btn-block btn-outline-secondary btn-xs" onclick="deleteItem('{{ url('/admin/ban/'.$item->id) }}')"><i class="fas fa-ban"></i>Ban</button>
                                                </a>
                                            </div>
                                            @else
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-block btn-outline-secondary btn-xs" disabled><i class="fas fa-ban"></i>Banned</button>
                                            </div>
                                            @endif
                                        </div>
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
        <div class="modal fade" id="modal-confirmation-delete">
            <div class="modal-dialog">
              <div class="modal-content bg-warning">
                <div class="modal-header">
                  <h4 class="modal-title">Ban?</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to ban this account?
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-outline-dark" data-dismiss="modal">Cancel</button>
                  <a href="#" id="deleteLink">
                    <button type="button" class="btn btn-outline-dark">OK</button>
                  </a>
                </div>
              </div>
            </div>
        </div>
    @endsection
    <script type="text/javascript">
        $.widget.bridge('uibutton', $.ui.button)
        function deleteItem(route) {
            $('#deleteLink').attr('href', route)
        }
    </script>

</html>