@extends('admin.layouts.master')
@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0 fw-bold">Create Product</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">

                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        #category_item {
            height: 200px;
            overflow: auto;
            overflow-x: hidden;
        }

        .list-group-item {
            padding: 0px !important;
            border: none !important;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="col-form-label"><b>Title</b> <span class="text-danger">*</span></label>
                                <div class="">
                                    <input type="text" name="title" value="{{ old('title') }}"
                                        class="form-control @error('title') is-invalid @enderror" required>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label class="col-form-label"><b>Brand </b></label>
                                                <div class="">
                                                    <select name="brand_id"
                                                        class="select2 form-control @error('brand_id') is-invalid @enderror">
                                                        <option value="">Please Select a Brand</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ $brand->id == old('brand_id') ? 'selected' : '' }}>
                                                                {{ $brand->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('brand_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label class="col-form-label"><b>Unit Type</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="text" name="unit_type" required placeholder="Piece"
                                                        value="{{ old('unit_type', 'Piece') }}"
                                                        class="form-control @error('unit_type') is-invalid @enderror">
                                                    @error('unit_type')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label class="col-form-label"><b>Thumbnail ( 230px X 250px )</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="file" name="image"
                                                        class="form-control @error('image') is-invalid @enderror" required>
                                                    @error('image')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="col-form-label"><b>Gallery</b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="">
                                                    <input type="file" name="gallery[]"
                                                        class="form-control @error('gallery') is-invalid @enderror" multiple
                                                        required>
                                                    @error('gallery')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label class="col-form-label"><b>Code</b></label>
                                                <div class="">
                                                    <input type="text" name="code" value="{{ old('code') }}"
                                                        class="form-control @error('code') is-invalid @enderror">
                                                    @error('code')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label"><b>Category</b> <span
                                                class="text-danger">*</span></label>
                                        <select name="category_id" required
                                            class="form-control select2 @error('category_id') is-invalid @enderror">
                                            <option value="">Select</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


             				
							<div class="col-md-12 row">
								<label class="col-form-label"><b>Type</b></label>						
									<div class="form-group">
										<div class="form-check form-check-inline bg-primary ml-2 px-3 p-2 rounded-pill text-light">
											<input class="form-check-input" type="radio" name="type" id="for_single" value="single" checked >
											<label class="form-check-label" for="for_single">Single</label>
										</div>
								</div>
							</div>

                            {{--
				<div class="form-group col-md-4">
				    <label class="col-form-label"><b>Category *</b></label>
				    <div class="">
				    	<select id="category_id" required name="category_id" class="select2 js-example-basic-multiple form-control @error('category_id') is-invalid @enderror">
				    		<option value="">Please Select a Category</option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}">{{ $category->title }}</option>
								@foreach ($category->child as $subcategory)
									<option value="{{ $subcategory->id }}">->{{ $subcategory->title }}</option>
									@foreach ($subcategory->child as $sub_subcategory)
										<option value="{{ $sub_subcategory->id }}">-->{{ $sub_subcategory->title }}</option>
									@endforeach
								@endforeach
							@endforeach
				    	</select>
			            @error('category_id')
			                <span class="invalid-feedback" role="alert">
			                    <strong>{{ $message }}</strong>
			                </span>
			            @enderror
				    </div>
				</div>
				--}}


{{-- 
                            <div class="col-md-12 my-3">
                                <div class="row shadow-sm rounded p-2">
                                    <div class="col-md-12 row">
                                        <label class="col-form-label"><b>Type</b></label>
                                        <div class="">
                                            <div class="form-group">
                                                <div
                                                    class="form-check form-check-inline bg-gray ml-2 px-3 p-2 rounded-pill text-white">
                                                    <input class="form-check-input" type="radio" name="type"
                                                        id="for_single" value="single" >
                                                    <label class="form-check-label" for="for_single">Single</label>
                                                </div>
                                                <div
                                                    class="form-check form-check-inline bg-gray px-3 ml-2 p-2 rounded-pill text-white">
                                                    <input class="form-check-input" type="radio" name="type"
                                                        id="for_variation" value="variation">
                                                    <label class="form-check-label" for="for_variation">Variation</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-10">
                                        <div id="variation" class="my-2" style="display: none;">

                                            <div class="shadow-sm p-2 rounded">
                                                <div id="variations_div" class=" p-2">

                                                </div>
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-primary btn-rounded"
                                                        data-toggle="modal" data-target="#exampleModal">Add New
                                                        Variation</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-md-12 my-3">
                                <h4 class="bold">Filter Head</h4>
                                <div class="row">
                                    
                                    @php
                                        $filterHeads = \App\Models\FilterHead::with('options')->get();
                                    @endphp

                                    @foreach ($filterHeads as $filterHead)
                                        <div class="col-md-4 col-sm-6 mb-4">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-header bg-light text-white py-2 px-3">
                                                    <h6 class="mb-0">{{ $filterHead->name }}</h6>
                                                </div>
                                                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                                                    <ul class="list-unstyled">
                                                        @foreach ($filterHead->options as $option)
                                                            <li class="form-check mb-1">
                                                                <input class="form-check-input filter-option"
                                                                    type="checkbox"
                                                                    name="filters[{{ $filterHead->id }}][]"
                                                                    value="{{ $option->id }}"
                                                                    id="option_{{ $option->id }}">
                                                                <label class="form-check-label"
                                                                    for="option_{{ $option->id }}">
                                                                    {{ $option->name }}
                                                                </label>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>


                            <div class="col-md-6 my-3">
                                <div class="shadow-sm p-2">
                                    <div class="form-group">

                                        <label for="is_featured" class="mr-3">
                                            <input type="checkbox" name="is_featured" value="1" checked
                                                id="is_featured">
                                            Recommended for you
                                        </label>

                                        <label for="is_tranding" class="mr-3">
                                            <input type="checkbox" name="is_tranding" value="1" checked
                                                id="is_tranding">
                                            Tranding Product
                                        </label>

                                        <label for="todays_deal" class="mr-3">
                                            <input type="checkbox" name="todays_deal" value="1" checked
                                                id="todays_deal">
                                            Today's Hot Deals
                                        </label>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 my-3">
                                <div id="single" class="shadow rounded p-2 border row">
                                    <div class="form-group col-md-6">
                                        <label class=""><b>Price *</b></label>

                                        <input type="number" step="any" name="price" value="{{ old('price') }}"
                                            class="form-control @error('price') is-invalid @enderror">
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="col-form-label"><b>Stock Quantity *</b></label>

                                        <input type="number" step="any" name="qty"
                                            value="{{ old('qty', 100) }}"
                                            class="form-control @error('qty') is-invalid @enderror">
                                        @error('qty')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-12">
                                <div class="form-group">
                                    <label><input type="checkbox" name="call_for_price" {{ old('call_for_price', $product->call_for_price ?? false) ? 'checked' : '' }}> Call for Price</label>
                                </div>
                            </div> --}}

                            <div class="col-md-12 my-3 mb-5">
                                <div class="shadow-sm row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label"><b>Discount Type</b></label>
                                        <select name="discount_type" class="form-control" id="">
                                            <option value="no">NO</option>
                                            <option value="flat">Flat</option>
                                            <option value="percentage">Percentage</option>
                                        </select>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label"><b>Discount Amount</b></label>
                                        <input type="number" name="discount_amount"
                                            value="{{ old('discount_amount') }}"
                                            class="form-control @error('discount_amount') is-invalid @enderror">
                                    </div>

                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="col-form-label"><b>Features</b></label>
                                <div class="">
                                    <textarea class="form-control @error('feature') is-invalid @enderror" name="feature"></textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label"><b>Specification</b></label>
                                <div class="">
                                    <textarea class="form-control @error('specification') is-invalid @enderror" name="specification">
							<table class="table table-bordered " style="border-collapse: collapse; display:block; width: 100%;">
								<tbody>
								<tr>
								<td style="width: 50%;">Fabric</td>
								<td style="width: 50%;">&nbsp;</td>
								</tr>
								<tr>
								<td style="width: 50%;">Occasion</td>
								<td style="width: 50%;">&nbsp;</td>
								</tr>
								<tr>
								<td style="width: 50%;">Cut/Fit</td>
								<td style="width: 50%;">&nbsp;</td>
								</tr>
								</tbody>
							</table>
						</textarea>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="col-form-label"><b>Description *</b></label>
                                <div class="">
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12 p-2">
                            <div class="row shadow rounded border p-2">
                                <div class="col-md-12">
                                    <h3><b>Meta Info for SEO</b></h3>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Meta Title</label>
                                        <input type="text" name="meta_title" value=""
                                            class="form-control @error('meta_title') is-invalid @enderror">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Meta Keywords</label>
                                        <input type="text" name="meta_keywords" value=""
                                            class="form-control @error('meta_keywords') is-invalid @enderror">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Meta Tags</label>
                                        <input type="text" name="tags" value=""
                                            class="form-control @error('tags') is-invalid @enderror">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <input type="text" name="meta_description" value=""
                                            class="form-control @error('meta_description') is-invalid @enderror">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


    <!-- Variation Modal -->
    <div class="modal fade text-left" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="" method="POST" id="variation_form">
                    @csrf
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title text-light" id="exampleModalLabel">Add New Variation</h5>
                        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Colors</label>
                                    <select class="form-control" name="colour" id="">
                                        <option value="">Select Colour</option>
                                        @foreach ($colors as $key => $color)
                                            <option style="background-color: {{ $color->code }};"
                                                value="{{ $color->code }}">{{ $color->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class=""><b>Variation Attribute</b></label>
                                    <select name="attribute" class="select2 form-control">
                                        <option value=""> -- Select Attribute --</option>
                                        @foreach ($variations as $variation)
                                            <option value="{{ $variation->id }}">{{ $variation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <span id="variation_loading" style="display: none;">Loading....</span>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="add_variation_button" class="btn btn-primary">Generate
                            Variation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("input[type='radio']").change(function() {
                if ($(this).val() == "variation") {
                    $("#variation").show();
                } else {
                    $("#variation").hide();
                }

                if ($(this).val() == "single") {
                    $("#single").show();
                } else {
                    $("#single").hide();
                }
            });

            $('#add_variation_button').click(function(e) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('product.generate.variation') }}",
                    method: 'post',
                    data: $('#variation_form').serialize(),
                    beforeSend: function() {
                        $('#variation_loading').show();
                    },
                    success: function(data) {
                        $('#variation_loading').hide();


                        if (data.status == 'not_selected') {
                            alert("No Variation Attribute Selected.");
                        } else if (data.status == 'yes' && data.code != '') {
                            $('#variations_div').append(data.output);
                            // if($('#variation_parent'+data.code).val()) {
                            //     alert("This Variation is Alerady Exist!");
                            // }
                            // else {
                            //     $('#variations_div').append(data.output);
                            // }
                        } else {
                            alert("No Variation Attribute Selected.");
                        }

                    }
                });
            });

        });

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            $('#customer_choice_options').append(
                '<div class="form-group col-md-6"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' +
                i + '"><input type="text" class="form-control" name="choice[]" value="' + name +
                '" placeholder="Choice Title" readonly></div><div class="col-md-8"><input type="text" class="form-control" data-role="tagsinput" name="choice_options_' +
                i + '[]" placeholder="Enter choice values"></div></div>');
        }

        function remove_variation_div(generated_id) {
            $('#variation_info_div_' + generated_id).remove();
        }
    </script>
@endsection
