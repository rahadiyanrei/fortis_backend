<!DOCTYPE html>
<html>
    @include('component.heading')
    @include('component.js')
    @extends('component.body')
    @section('pages','Find Dealer')
    @section('content-helper')
        <div class="col-sm-2 ml-auto">
            <a href="{{ url('contact/dealer/create') }}">
                <button type="button" class="btn btn-block btn-info btn-sm">Create Dealer</button>
            </a>
        </div>
    @endsection
    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-tools">
                            <form>
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="global_search" class="form-control float-right" value="{{ app('request')->input('global_search') }}" placeholder="Search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Province</th>
                                <th>Dealer Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $dealer)
                                <tr>
                                    <td>{{ $dealer->province->name }}</td>
                                    <td>{{ $dealer->name }}</td>
                                    <td>{{ $dealer->email }}</td>
                                    <td>{{ $dealer->phone_number }}</td>
                                    <td>
                                        @if ($dealer->status === 1)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $dealer->createdBy->fullname }}</td>
                                    <td>{{ $dealer->updatedBy->fullname }}</td>
                                    <td>
                                        <a href="{{ url('/contact/dealer/'.$dealer->uuid) }}">
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