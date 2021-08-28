@extends('layouts.admin_master')
@section('content')
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Delivered Products
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Customer Email</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($orders as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->product_code }}</td>
                        <td>{{ $row->product_name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>
                            @if($row->order_status=='0')
                                <a href="#" class="btn btn-sm btn-info">Pending</a>
                            @else
                                <a href="#" class="btn btn-sm btn-info">Delivered</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
                
            </table>
        </div>
    </div>
</div>



@endsection