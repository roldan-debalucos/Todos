@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard <button class="btn btn-primary" onclick="Create()"><i class="fa fa-plus-circle"></i> Create</button></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                </div>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{$d->id}}</td>
                            <td>{{$d->title}}</td>
                            <td>{{$d->description}}</td>
                            <td><button class="btn btn-warning" onclick="Edit({{$d->id}})"><i class="fa fa-plus-circle"></i> Edit</button> <button class="btn btn-danger" onclick="DelSel({{$d->id}})"><i class="fa fa-plus-circle"></i> Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Entry Modal --}}
    <div class="row">
      <div class="modal fade in" id="entry-modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <h4 class="modal-title"><span id="Entry-Name"></span> Todos</h4>
            </div>
            <div class="modal-body">
              <span class="AddMode EditMode">
                      <form id="add-form" data-parsley-validate novalidate>
                      <div class="box-body">
                        <div class="row">
                            <input type="hidden" name="txt_id">
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Title</label>
                              <input type="text" class="form-control" name="txt_title">
                            </div>
                          </div>                  
                        </div>
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label>Description</label>
                              <textarea class="form-control" name="txt_description"></textarea> 
                            </div>
                          </div>
                        </div>
                      </div>
                      </form>
                    </span>
                    
                    <span class="DeleteMode">
                      <center>
                          <input type="hidden" name="txt_delid">
                          <h4 class="text-transform: uppercase;">Are you sure you want to delete this item?
                          </h4>
                      </center>
                    </span>
            </div>
            <div class="modal-footer">
              <span class="AddModeBtn">
                <button type="button" class="btn btn-primary" onclick="Save()">Save</button>
              </span>
              <span class="EditModeBtn">
                <button type="button" class="btn btn-primary" onclick="Update()">Update</button>
              </span>
              <span class="DeleteMode">
                <button type="button" class="btn btn-primary" onclick="Delete()">Delete</button>
              </span>
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      </div>

      <script>
          
          function Create()
          {
             $('#Entry-Name').text('Create');
             $('.AddMode').show();
             $('.AddModeBtn').show();
             $('.EditMode').show();
             $('.EditModeBtn').hide();
             $('.DeleteMode').hide();

             Clear();

             $('#entry-modal').modal('toggle');
          }

          function Edit(id)
          {
            $('#Entry-Name').text('Edit');
            $('.AddMode').show();
            $('.AddModeBtn').hide();
            $('.EditMode').show();
            $('.EditModeBtn').show();
            $('.DeleteMode').hide();

            Clear();

            $.ajax({
                url: '{{asset('todos/')}}/'+id,
                method: 'GET',
                success : function(data)
                          {
                            $('input[name="txt_id"]').val(id);
                            $('input[name="txt_title"]').val(data[0]);
                            $('textarea[name="txt_description"]').val(data[1]);

                            $('#entry-modal').modal('toggle');
                          }
            });
          }

          function Clear()
          {
            $('input[name="txt_id"]').val('');
            $('input[name="txt_title"]').val('');
            $('textarea[name="txt_description"]').val('');
          }

          function Save()
          {
            var title = $('input[name="txt_title"]').val();
            var description = $('textarea[name="txt_description"]').val();
           
            if(!title)
            {
                alert('Please fill the Title field.');
            }
            else if(!description)
            {
                alert('Please fill the Description field.')
            }
            else
            {

               var data = {
                             _token : $('meta[name="csrf-token"]').attr('content'),
                             title: title,
                             description: description
                          };

               $.ajax({
                   url: '{{route('todos.store')}}',
                   method: 'POST',
                   data: data,
                   success : function(flag)
                             {
                                location.href = "{{route('todos.index')}}";
                             }
               });
            }
          }

          function Update()
          {
            var id = $('input[name="txt_id"]').val();
            var title = $('input[name="txt_title"]').val();
            var description = $('textarea[name="txt_description"]').val();
           
            if(!title)
            {
                alert('Please fill the Title field.');
            }
            else if(!description)
            {
                alert('Please fill the Description field.')
            }
            else
            {

               var data = {
                             _token : $('meta[name="csrf-token"]').attr('content'),
                             title: title,
                             description: description
                          };

               $.ajax({
                   url: '{{asset('todos/')}}/'+id,
                   method: 'PUT',
                   data: data,
                   success : function(flag)
                             {
                                location.href = "{{route('todos.index')}}";
                             }
               });
            }
          }

          function DelSel(id)
          {
            $('#Entry-Name').text('Delete');
            $('.AddMode').hide();
            $('.AddModeBtn').hide();
            $('.EditMode').hide();
            $('.EditModeBtn').hide();
            $('.DeleteMode').show();

            $('input[name="txt_delid"]').val(id);

            $('#entry-modal').modal('toggle');
          }

          function Delete()
          {
            var id = $('input[name="txt_delid"]').val();

            var data = {
                          _token : $('meta[name="csrf-token"]').attr('content')
                       };

            $.ajax({
                   url: '{{asset('todos/')}}/'+id,
                   method: 'DELETE',
                   data: data,
                   success : function(flag)
                             {
                                location.href = "{{route('todos.index')}}";
                             }
               });
          }
      </script>
</div>
@endsection
