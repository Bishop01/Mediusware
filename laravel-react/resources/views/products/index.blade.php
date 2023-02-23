@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <div id='product-list'>
        <form id="form" action="" method="get" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input id="title" type="text" name="title" value="{{old("title")}}" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="variants" class="form-control" multiple>
                        <option disabled value="" style="display:none;">-- Select a variant --</option>
                        <optgroup label="Color">
                          <option value="red">Red</option>
                          <option value="green">Green</option>
                          <option value="blue">Blue</option>
                        </optgroup>
                        <optgroup label="Size">
                          <option value="SM">Small</option>
                          <option value="M">Medium</option>
                          <option value="L">Large</option>
                          <option value="XL">X-Large</option>
                        </optgroup>
                        <optgroup label="Style">
                          <option value="V-NICK">V-Nick</option>
                          <option value="O-NICK">O-Nick</option>
                        </optgroup>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input id="from" type="text" name="price_from" value="{{old("price_from")}}" aria-label="First name" placeholder="From" class="form-control">
                        <input id="to" type="text" name="price_to" value="{{old("price_to")}}" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input id="date" type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Variant</th>
                            <th width="150px">Action</th>
                        </tr>
                        </thead>
    
                        <tbody>
    
                        @foreach ($products as $product)
    
                        <tr>
                            <td>{{$product->id}}</td>
                            <td>{{$product->title}} <br> Created at : {{$product->created_at}}</td>
    
                            {{-- *Using the original description break the UI, that's why sliced it.* --}}
                            <td>{{Str::limit($product->description, 30)}}</td>
    
                            <td>
                                <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant-{{$product->id}}">
                                    @foreach ($product->productVariantPrice as $variant)
                                    <dt class="col-sm-3 pb-0">
                                        @php
                                            $size = "";
                                            $color = "";
                                            $type = "";
                                            foreach ($productVariants as $var)
                                            {
                                                if ($var->id == $variant->product_variant_one)
                                                {
                                                    $color=strtoupper($var->variant);
                                                }
                                                else if ($var->id == $variant->product_variant_two)
                                                {
                                                    $size=strtoupper($var->variant);
                                                }
                                                else if ($var->id == $variant->product_variant_three)
                                                {
                                                    $type=strtoupper($var->variant);
                                                }
                                            }
                                            //echo "$size / $color / $type"
                                            echo (($size) ? "$size" : "");
                                            echo (($size && $color) ? " / $color" : $color);
                                            echo (($color && $type) ? " / $type" : $type);
                                        @endphp
                                    </dt>
                                    <dd class="col-sm-9">
                                        <dl class="row mb-0">
                                            <dt class="col-sm-4 pb-0">Price : {{ $variant->price }}</dt>
                                            <dd class="col-sm-8 pb-0">InStock : {{ $variant->stock }}</dd>
                                        </dl>
                                    </dd>
                                    <br>
                                    @endforeach
                                </dl>
                                <button onclick="$('#variant-{{$product->id}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-success">Edit</a>
                                </div>
                            </td>
                        </tr>
                            
                        @endforeach
    
                        </tbody>
    
                    </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="col-md-6 text-gray-800">
                <p>Showing {{$products->firstItem()}} to {{$products->lastItem()}} out of {{$products->total()}} </p>
            </div>
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>{{$products->links()}}</p>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
