@extends('layouts.navbar')
@section('css')
    <link rel="stylesheet" href="{{asset('css/showProducts.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
<div class="container">
    <a href="{{url('productcreate')}}"><button type="button" class="btn btn-success mt-2">CREATE</button></a>
    <div>
        @if(count($products)==0)
            <h1>No Products added.</h1>
        @else
        <h1 class="row justify-content-center">{{$title}}</h1>
        <div class="dropdown text-right">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                Sort by:
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li><button class="dropdown-item high-low" type="button" onclick="sortByPriceHighToLow()">Price: High to Low <i class="fa-solid fa-arrow-up"></i></button></li>
                <li><button class="dropdown-item low-high" type="button" onclick="sortByPriceLowToHigh()">Price: Low to high <i class="fa-solid fa-arrow-down"></i></button></li>
            </ul>
        </div>
        <div id="products-container" class="row justify-content-center product-grid-style">    
            @foreach($products as $product)
                <div class="col-11 col-sm-6 col-lg-4 col-xl-3 product" data-price="{{$product->price}}">
                    <div class="product-details">
                        <div class="product-img">
                            <!-- <div class="label-offer bg-red">Sale</div><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRvU-qBKoPIouYJdt9AjArTLVtUawJsV1Yg2w&usqp=CAU" alt="..."> -->
                            <!-- <div class="label-offer bg-red">Sale</div> -->
                            <img src="{{$product->productimage}}" alt="{{$product->name}}">
                            <div class="product-cart">
                            <a href="#" onclick="confirmDelete(event, {{$product->id}})"><i class="fa fa-trash"></i></a>
                            <form id="delete-form-{{$product->id}}" action="{{ route('products.destroy', $product) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                                <a href="{{route('products.edit',$product->id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                                <!-- <a href="#!"><i class="fa-solid fa-message"></i></a> -->
                                <a href="{{route('products.show',$product->id)}}"><i class="fa-solid fa-eye"></i></a>
                            </div>

                            </div>
                            <div class="product-info">
                                <a href="#!">{{$product->name}}</a>
                                <p class="price text-center m-0">
                                    <span class="red line-through me-2">$600</span>
                                    <span>{{$product->price}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

@section('scripts')

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{asset('js/showProducts.js')}}"></script>
@endsection