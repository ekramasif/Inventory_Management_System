@extends('layouts.admin_master')
@section('content')



<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Customers List
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->company }}</td>
                        <td>{{ $row->address }}</td>
                        <td>{{ $row->phone }}</td>
                        <td>
                            <a href="{{URL::to('edit_customer')}}" class="btn btn-sm btn-info">Edit</a>
                            <!-- <a href="{{ 'add-order/'.$row->id }}" class="btn btn-sm btn-info">Order</a> -->
                        </td>
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        
<script>
   


   $('#dataTable').DataTable({
    columnDefs: [
    {bSortable: false, targets: [5]} 
  ],
                dom: 'lBfrtip',
           buttons: [
               {
                   extend: 'copyHtml5',
                   exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                       columns: [ 0, ':visible' ]
                       
                   }
               },
               {
                   extend: 'excelHtml5',
                   exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                    columns: [ 0, ':visible' ]
                   }
               },
               {
                   extend: 'pdfHtml5',
                   exportOptions: {
                    modifier: {
                        page: 'current'
                    },
                       columns: [ 0, 1, 2, 5 ]
                   }
               },

               'colvis'

           ],
            
           });
       </script>

@endsection
