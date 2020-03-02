@extends('layouts.app')
@section('content')
<div class="container">
    <button class="btn btn-outline-primary btn-sm" id="addnewitems">+ Add new items</button>
    <div class="my-3 p-3 bg-white rounded box-shadow">
        <h6 class="border-bottom border-gray pb-2 mb-0">Items</h6>

        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Last Location</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>


    </div>
</div>


<script type="text/javascript">

    var table = $('#dataTable').DataTable({ 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "sPaginationType": "full_numbers",
        "pageLength": 25,        
        "ajax": {
            "url": '{{ route('items.data') }}',
            "type": "GET",
            "data": function ( data ) {
            }
        },
        "columns": [
            { "data": "no" },
            { "data": "name" },
            { "data": "location" },
            { "data": "created_at" },
            { "data": "view" },
        ],
        'columnDefs': [
            {'targets': 0, 'orderable': false},
            {'targets': 1, 'orderable': false},
            {'targets': 2, 'orderable': false},
            {'targets': 4, 'orderable': false},
        ],  
    });
    $("#addnewitems").click(function(){
        $("#newItemsModal").modal('show');
        return false;
    });


    function setLocation(id)
    {
        $("#locationId").val(id);
        $("#updateLocationItems").modal('show');
        return false;
    }

    function saveLatestLocation()
    {
        $.ajax({
            url: "{{route('items.updateHistory')}}",
            type: "post",
            data: "_token="+$('input[name="_token"]').val()+"&name="+$('#itemsLocation').val()+"&id="+$('#locationId').val(),
            success: function (response) {
               if (response.msg == "Invalid API") {
                    $('.error').html('');
                    for (var error in response.data) {
                        $('.error').append('<li ><span style="color:red;"> The Name of location field is required</span> </li>');
                    }
               }
               
               if (response.msg == "success") {
                    alert('Newest location has been updated');
                    table.ajax.reload( null, false );
                    $(".modal").modal('hide');
               }
    
               return false;
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
       return false; 
    }

    function removeItems(id){
        var retVal = confirm("Are you sure to remove this item ?");
        if( retVal == true ){
            $.ajax({
                    type: "POST",
                    url: "{{ route('items.delete') }}",
                    data: "id="+id+"&_token={{ csrf_token() }}",
                    success: function(data){
                        table.ajax.reload( null, false );
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            return true;
        }
        return id;
    }
    
    function saveItems()
    {
        $.ajax({
            url: "{{route('items.add')}}",
            type: "post",
            data: "_token="+$('input[name="_token"]').val()+"&name="+$('#itemsName').val(),
            success: function (response) {
               if (response.msg == "Invalid API") {
                    $('.error').html('');
                    for (var error in response.data) {
                        $('.error').append('<li ><span style="color:red;"> The name field is required</span> </li>');
                    }
               }
               
               if (response.msg == "success") {
                    alert('New items has been added');
                    table.ajax.reload( null, false );
                    $(".modal").modal('hide');
               }
    
               return false;
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
       return false; 
    }


</script>

<div class="modal fade " id="updateLocationItems" tabindex="-1"  aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Set Latest Location the item</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
                <form>
                    {{ csrf_field()}}
                    <div class="form-group">
                        <ul style="" class="error">

                        </ul>
                    </div>
                    <div class="form-group" style="display:none;">
                         {{ csrf_field()}}
                        <input type="text" class="form-control" id="locationId" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="Items Location">Latest Location (Name of item location)</label>
                        <input type="text" class="form-control" id="itemsLocation" placeholder="Name of item location">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveItems" onclick="saveLatestLocation();">Update</button>
                <a class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="newItemsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">New items</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
            <div class="modal-body">
                <form>
                    {{ csrf_field()}}
                    <div class="form-group">
                        <ul style="" class="error">

                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="Items Name">Name</label>
                        <input type="text" class="form-control" id="itemsName" placeholder="Items Name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="saveItems" onclick="saveItems();">Save changes</button>
                <a class="btn btn-secondary" data-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</div>


@endsection
