@extends('layouts.app')

@section('content')
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
<script type="text/javascript"  src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" defer></script>
<script type="text/javascript"  src=" https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Products Listing</div>

                <div class="card-body">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th class="text-center">S.No</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($getData) && count($getData) >0)
                            @foreach($getData as $getDataKey=>$getDataVal)

                            <tr>
                                <td class="text-center">{{$getDataKey+1}}</td>
                                <td class="text-center">{{isset($getDataVal->Name)?$getDataVal->Name:""}}</td>
                                <td class="text-center">{{isset($getDataVal->Price)?$getDataVal->Price:""}}</td>
                                <td class="text-center">{{isset($getDataVal->Description)?$getDataVal->Description:""}}</td>
                                <td class="text-center">{{isset($getDataVal->Status)?$getDataVal->Status:""}}</td>
                                <td class="nowrap">
                                    <a data-toggle="tooltip" data-placement="top" target="_blank" title="View Product Detail" href="productsshow/{{$getDataVal->id}}" class="btn btn-primary">
                                        Buy Now
                                    </a>                                             
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('#table').DataTable({
        paging: true,
        scrollX: true,
        lengthChange: true,
        searching: true,
        ordering: true
    });
});
</script>
@endsection
