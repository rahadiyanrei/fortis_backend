<!DOCTYPE html>
<html>
  @include('component.heading')
  @include('component.js')
  @extends('component.body')
  @section('pages','Inko Wheels')
  @section('content-helper')
    <div class="col-sm-2 ml-auto">
      <a href="{{ url('wheel/inko/create') }}">
          <button type="button" class="btn btn-block btn-info btn-sm">Create</button>
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
                                <th>Name</th>
                                <th>New Release</th>
                                <th>Discontinued</th>
                                <th>Status</th>
                                <th>Sizes</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($data as $item)
                              <tr>
                                <td>{{ $item->name }}</td>
                                <td>
                                  @if ($item->is_new_release === 1)
                                    <span class="badge badge-success">Yes</span>
                                  @else
                                    <span class="badge badge-warning">No</span>
                                  @endif
                                </td>
                                <td>
                                  @if ($item->is_discontinued === 1)
                                    <span class="badge badge-success">Yes</span>
                                  @else
                                    <span class="badge badge-warning">No</span>
                                  @endif
                                </td>
                                <td>
                                  @if ($item->status === 1)
                                    <span class="badge badge-success">Active</span>
                                  @else
                                    <span class="badge badge-danger">Inactive</span>
                                  @endif
                                </td>
                                <td>
                                  @if ($item->sizes)
                                      @foreach($item->sizes as $key => $size)
                                        @if ($key !== 0), @endif
                                        {{ $size->diameter }}"
                                      @endforeach
                                  @else
                                      no data!
                                  @endif
                                </td>
                                <td>{{ $item->createdBy->fullname }}</td>
                                <td>{{ $item->updatedBy->fullname }}</td>
                                <td>
                                  <div class="btn-group">
                                    <a href="{{ url('/wheel/inko/view/'.$item->uuid) }}" style="margin-right: 0.5rem">
                                      <button type="button" class="btn btn-block btn-outline-secondary btn-xs"><i class="fas ion-eye"></i>View</button>
                                    </a>
                                    <a href="{{ url('/wheel/inko/'.$item->uuid) }}">
                                      <button type="button" class="btn btn-block btn-outline-secondary btn-xs"><i class="fas fa-edit"></i>Edit</button>
                                    </a>
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
  @endsection
  <script>
      $.widget.bridge('uibutton', $.ui.button)
  </script>

</html>