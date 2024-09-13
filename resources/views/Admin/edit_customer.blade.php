@extends('layouts.admin_master')

@section('content')

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">New Customer</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{URL::to('update-customer/'.$customers->id)}}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">Customer Name</label>
                                        <input class="form-control py-4" name="name" value="{{ $customers->name }}" type="text" placeholder="" />
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">Customer Email</label>
                                        <input class="form-control py-4" name="email" type="text" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Company</label>
                                        <input class="form-control py-4" name="company" type="text" placeholder="" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Address</label>
                                        <input class="form-control py-4" name="address" type="text" placeholder="" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Phone</label>
                                        <input class="form-control py-4" name="phone" type="text" placeholder="" />
                                    </div>
                                </div>
                            </div> -->

                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block">Submit</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection