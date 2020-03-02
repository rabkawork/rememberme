@extends('layouts.app')

@section('content')
<div class="container">
    <button class="btn btn-outline-primary btn-sm" id="addnewitems">+ Add new items</button>
    <div class="my-3 p-3 bg-white rounded box-shadow">
        <h6 class="border-bottom border-gray pb-2 mb-0">Items</h6>
    </div>
</div>


<script type="text/javascript">
$("#addnewitems").click(function(){
    $(".modal").modal('show');
    return false;
});
</script>


<div class="modal" tabindex="-1" role="dialog">
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
                <div class="form-group">
                    <label for="Items Name">Name</label>
                    <input type="text" class="form-control" id="itemsName" placeholder="Items Name">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
@endsection
