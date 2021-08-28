@extends('layouts.admin_master')

@section('content')

<main>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Add New Product</h3></div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('/insert-product') }}" enctype="multipart/form-data">
                        @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">Product Code</label>
                                        <input class="form-control py-4" name="code" type="text" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputFirstName">Product Name</label>
                                        <input class="form-control py-4" name="name" type="text" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Category</label>
                                        <input class="form-control py-4" name="category" type="text" placeholder="" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Stock</label>
                                        <input class="form-control py-4" name="stock" type="text" placeholder="" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Buy Price (perUnit)</label>
                                        <input class="form-control py-4" name="unit_price" type="text" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Sale Price(perUnit)</label>
                                        <input class="form-control py-4" name="sale_price" type="text" placeholder="" />
                                    </div>
                                </div>

                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small mb-1" for="inputLastName">Gallery</label>
                                        <input name="photo" type="file" />
                                    </div>
                                </div> -->
                            </div>

                            <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block">Submit</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection