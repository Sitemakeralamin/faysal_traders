<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Alert;
use Image;
use App\Models\Brand;
use Dompdf\Css\Color;
use App\Models\Colors;
use App\Models\Product;
use App\Models\Category;
use App\Models\Variation;
use App\Models\FilterHead;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductStocks;
use App\Models\FilterHeadOption;
use App\Models\ProductWithCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (user()->type == 1) {
            $products = Product::orderBy('id', 'DESC')->get();
            return view('admin.product.index', compact('products'));
        } else {
            Alert::toast('Access Denied !', 'error');
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (user()->type == 1) {
            $categories = Category::orderBy('id', 'DESC')->get();
            $brands = Brand::orderBy('id', 'DESC')->get();
            $colors = Colors::orderBy('name', 'ASC')->get();
            $variations = Variation::all();
            return view('admin.product.create', compact('categories', 'brands', 'colors', 'variations'));
        } else {
            Alert::toast('Access Denied !', 'error');
            return back();
        }
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code . $character;
        }

        if (Product::where('code', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }


    public function generate_variation(Request $request)
    {
        $info = '';
        $atributes = $request->attribute;
        $output = '';
        $atribute_output = '';
        $colour = $request->colour;

        if (!is_null($colour)) {
            $colour_info = Colors::where('code', $colour)->first();
            $color_id = $colour_info->id;
            $colour_output = '<div class="p-2 shadow rounded"><b>Colour: </b> <span style="background-color: ' . $colour . '; padding: 5px;">' . $colour_info->name . '</span></div>';
        } else {
            $colour_output = '';
            $colour_info = '';
            $color_id = '';
        }

        $generating_id = date("ymdhis");

        if (!is_null($atributes)) {
            $atribute_info = Variation::where('id', $atributes)->first();
            if (!is_null($atribute_info)) {
                $atribute_output .= '<div>
                        <input type="hidden" name="attribute_id[]" value="' . $atribute_info->id . '">
                        <input type="hidden" name="attribute_id' . $generating_id . '" value="' . $atribute_info->id . '">
                            
                        <label><span class="text-danger">*</span>' . $atribute_info->title . '</label>
                        <input type="text" class="form-control" name="attribute_value[]" value="" required>
                    </div>';
            } else {
                $atribute_output .= '<div>
                        <input type="hidden" name="attribute_id[]" value="">
                        <input type="hidden" name="attribute_id' . $generating_id . '" value="">
                        <input type="hidden" name="attribute_value[]" value="">
                        </div>';
            }

        } else {
            $atribute_output = '';
            $atribute_info = '';
        }


        $output .= '<div class="row p-2 shadow rounded mb-4" id="variation_info_div_' . $generating_id . '">
                        <input type="hidden" id="variation_parent' . $generating_id . '" name="variation_parent[]" value="' . $generating_id . '">
                        <input type="hidden" name="colour_attribute[]" value="' . $color_id . '">
                        <input type="hidden" name="new_or_old[]" value="new">
			              <div class="col-md-5">
			                <div>
                                ' . $colour_output . '<br>
                                ' . $atribute_output . '
			                </div>
			              </div>
			              <div class="col-md-6 shadow rounded border p-1 px-4">
			                  <div class="form-group">
            				    <label class="col-form-label"><b>Image</b></label>
            				    <input type="file" name="variation_image' . $generating_id . '" class="form-control">
            				  </div>
            				  
            				  <div class="form-group">
            				    <label class="col-form-label"><span class="text-danger">*</span><b>Variant Price</b></label>
            				    <input type="number" name="variant_price[]" class="form-control"required step=any>
            				  </div>
            				  
            				  <div class="form-group">
            				    <label class="col-form-label"><span class="text-danger">*</span><b>Stock Quantity</b></label>
            				    <input type="number" name="variation_stock_qty[]" class="form-control" required>
            				  </div>
			              </div>
			              
			              <div class="col-md-1">
			                  <button type="button" class="btn btn-danger" onclick="remove_variation_div(' . $generating_id . ')"><i class="fas fa-trash-alt text-light"></i></button>
			              </div>
			          </div>';

        $info = [
            'status' => 'yes',
            'code' => $generating_id,
            'output' => $output,
        ];

        return Response($info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'image' => 'nullable',
            'category_id' => 'required|max:191',
            'slug' => 'required|unique:products,slug',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        // Check for uniqueness excluding current record
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $product = new Product;
        $product->title = $request->title;
        $product->slug = $slug;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_type = $request->unit_type;
        $product->is_featured = isset($request->is_featured) ? 1 : 0;
        $product->is_tranding = isset($request->is_tranding) ? 1 : 0;
        $product->todays_deal = isset($request->todays_deal) ? 1 : 0;
        $product->discount_type = $request->discount_type;
        $product->discount_amount = $request->discount_amount;
        $product->type = $request->type;
        $product->feature = $request->feature;
        $product->specification = $request->specification;
        $product->description = $request->description;


        if (isset($request->call_for_price) && $request->call_for_price) {
            $product->call_for_price = 1;
        }

        if (!empty($request->code)) {
            $product->code = $request->code;
        } else {
            $product->code = $this->generateUniqueCode();
        }

        // image save
        if ($request->image) {
            $image = $request->file('image');
            $img = time() . rand() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/product/' . $img);
            Image::make($image)->save($location);
            $product->thumbnail_image = $img;
        }

        //Meta info
        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->tags = $request->tags;
        $product->meta_description = $request->meta_description;

        $product->save();

        // filter head
        // Save filter options if present
        if ($request->has('filters') && is_array($request->filters)) {
            $filterOptionIds = [];
            foreach ($request->filters as $filterHeadId => $optionIds) {
                foreach ($optionIds as $optionId) {
                    $filterOptionIds[] = $optionId;
                }
            }

            $product->options()->sync($filterOptionIds);
        }


        if ($request->type == 'variation') {
            $colors = array();
            $attributes = array();
            if ($request->has('variation_parent')) {
                foreach ($request->variation_parent as $key => $row) {
                    $variation_parent = $request->variation_parent[$key];

                    if ($request->has('colour_attribute')) {
                        //For colors attribute
                        $color = $request->colour_attribute[$key];
                        if (!in_array($color, $colors) && $color != '') {
                            array_push($colors, $color);
                        }
                    } else {
                        $color = '';
                    }

                    if ($request->has('attribute_id')) {
                        //For variation attribute
                        $attribute = $request->attribute_id[$key];
                        if (!in_array($attribute, $attributes) && $attribute != '') {
                            array_push($attributes, $attribute);
                        }
                        $attribute_value = $request->attribute_value[$key];
                    } else {
                        $attribute = '';
                        $attribute_value = '';
                    }

                    $stock = new ProductStocks;
                    $stock->product_id = $product->id;
                    $stock->color = $color;
                    $stock->variant = $attribute;
                    $stock->variant_output = $attribute_value;
                    $stock->price = $request->variant_price[$key];
                    $stock->qty = $request->variation_stock_qty[$key];

                    // image save
                    $img_v = 'variation_image' . $variation_parent;
                    if ($request->$img_v) {
                        $image = $request->file($img_v);
                        $img = time() . rand() . '.' . $image->getClientOriginalExtension();
                        $location = public_path('images/product/' . $img);
                        Image::make($image)->save($location);
                        $stock->image = $img;
                    }

                    $stock->save();
                }

                $product->colors = $colors;
                $product->attributes = $attributes;
                $product->save();

            }
        } else {
            $stock = new ProductStocks;
            $stock->product_id = $product->id;
            $stock->price = $request->price;
            $stock->qty = $request->qty;
            $stock->save();
        }


        if (isset($request->category_id)) {
            $new_category = new ProductWithCategory;
            $new_category->category_id = $request->category_id;
            $new_category->product_id = $product->id;
            $new_category->save();
        }


        // check if any gallery image then save
        if (count($request->gallery) > 0) {
            $i = 0;
            foreach ($request->gallery as $gallery) {
                $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
                $location = public_path('images/product/' . $img);
                Image::make($gallery)->save($location);

                $gallery = new ProductImage;
                $gallery->image = $img;
                $gallery->product_id = $product->id;
                $gallery->save();
                $i = $i + 1;
            }
        }

        Alert::toast('Product Added!', 'success');
        return redirect()->route('product.index');
    }

    // public function store(Request $request)
// {
//     $validatedData = $request->validate([
//         'title' => 'required|max:191',
//         'category_id' => 'required|max:191',
//         'slug' => 'required|unique:products,slug',
//         'image' => 'nullable', 
//         'gallery.*' => 'nullable',
//     ]);

    //     // Slug generation logic (same as before)
//     $slug = Str::slug($request->title);
//     $originalSlug = $slug;
//     $count = 1;

    //     // Check for uniqueness excluding the current record
//     while (Product::where('slug', $slug)->exists()) {
//         $slug = $originalSlug . '-' . $count++;
//     }

    //     dd($request->all());

    //     // Create product instance
//     $product = new Product;
//     $product->title = $request->title;
//     $product->slug = $slug;
//     $product->category_id = $request->category_id;
//     $product->sub_category_id = $request->sub_category_id;
//     $product->brand_id = $request->brand_id;
//     $product->unit_type = $request->unit_type;
//     $product->is_featured = isset($request->is_featured) ? 1 : 0;
//     $product->is_tranding = isset($request->is_tranding) ? 1 : 0;
//     $product->todays_deal = isset($request->todays_deal) ? 1 : 0;
//     $product->discount_type = $request->discount_type;
//     $product->discount_amount = $request->discount_amount;
//     $product->type = $request->type;
//     $product->feature = $request->feature;
//     $product->specification = $request->specification;
//     $product->description = $request->description;

    //     // Handle 'call for price' logic
//     if (isset($request->call_for_price) && $request->call_for_price) {
//         $product->call_for_price = 1;  // Assuming this is your field for 'call for price'
//         $product->price = 0;  // Price set to 0 when 'call for price' is enabled
//     }

    //     // Save product code (either provided or generate a unique one)
//     $product->code = $request->code ?? $this->generateUniqueCode();

    //     // Handle main product image
//     if ($request->hasFile('image')) {
//         $image = $request->file('image');
//         $img = time() . rand() . '.' . $image->getClientOriginalExtension();
//         $location = public_path('images/product/' . $img);
//         Image::make($image)->save($location);
//         $product->thumbnail_image = $img;
//     }

    //     // Meta info
//     $product->meta_title = $request->meta_title;
//     $product->meta_keywords = $request->meta_keywords;
//     $product->tags = $request->tags;
//     $product->meta_description = $request->meta_description;

    //     $product->save();

    //     // Handle filter options if provided
//     if ($request->has('filters') && is_array($request->filters)) {
//         $filterOptionIds = [];
//         foreach ($request->filters as $filterHeadId => $optionIds) {
//             foreach ($optionIds as $optionId) {
//                 $filterOptionIds[] = $optionId;
//             }
//         }
//         $product->options()->sync($filterOptionIds);
//     }

    //     // Handle gallery images
//     if ($request->has('gallery')) {
//         $i = 0;
//         foreach ($request->gallery as $gallery) {
//             if ($gallery) { // Ensure gallery image is provided
//                 $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
//                 $location = public_path('images/product/' . $img);
//                 Image::make($gallery)->save($location);

    //                 ProductImage::create([
//                     'product_id' => $product->id,
//                     'image' => $img, // Save the image if it's uploaded
//                 ]);
//                 $i++;
//             }
//         }
//     }

    //     // Handle categories
//     if (isset($request->category_id)) {
//         $new_category = new ProductWithCategory;
//         $new_category->category_id = $request->category_id;
//         $new_category->product_id = $product->id;
//         $new_category->save();
//     }

    //     // Handle stock (single or variations)
//     if ($request->type == 'variation') {
//         // Handle variations here
//     } else {
//         // Handle simple product stock
//         $stock = new ProductStocks;
//         $stock->product_id = $product->id;
//         $stock->price = $request->price;
//         $stock->qty = $request->qty;
//         $stock->save();
//     }

    //     Alert::toast('Product Added!', 'success');
//     return redirect()->route('product.index');
// }





    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (user()->type == 1) {
            $product = Product::find($id);
            if (!is_null($product)) {
                $categories = Category::orderBy('id', 'DESC')->get();
                $sub_categories = optional($product->category)->child;

                $brands = Brand::orderBy('id', 'DESC')->get();
                $colors = Colors::orderBy('name', 'ASC')->get();
                $variations = Variation::all();
                return view('admin.product.edit', compact('product', 'categories', 'colors', 'variations', 'sub_categories', 'brands'));
            } else {
                Alert::toast('Page Not Found !', 'error');
                return back();
            }
        } else {
            Alert::toast('Access Denied !', 'error');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $validatedData = $request->validate([
    //         'title' => 'required|max:191',
    //         'category_id' => 'required|max:191',
    //     ]);


    //     $slug = Str::slug($request->title);
    //     $originalSlug = $slug;
    //     $count = 1;

    //     // Check for uniqueness excluding current record
    //     while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
    //         $slug = $originalSlug . '-' . $count++;
    //     }        


    //     $product = Product::find($id);

    //     if (!is_null($product)) {

    //         $product->title = $request->title;
    //         $product->slug = $slug;
    //         $product->category_id = $request->category_id;
    //         $product->sub_category_id = $request->sub_category_id;
    //         $product->brand_id = $request->brand_id;
    //         $product->unit_type = $request->unit_type;
    //         $product->is_featured = isset($request->is_featured)? 1 : 0;
    //         $product->is_tranding = isset($request->is_tranding)? 1 : 0;
    //         $product->todays_deal = isset($request->todays_deal)? 1 : 0;
    //         $product->discount_type = $request->discount_type;
    //         $product->discount_amount = $request->discount_amount;
    //         $product->type = $request->type;
    //         $product->feature = $request->feature;
    //         $product->specification = $request->specification;
    //         $product->description = $request->description;


    //         //Meta info
    //         $product->meta_title = $request->meta_title;
    //         $product->meta_keywords = $request->meta_keywords;
    //         $product->tags = $request->tags;
    //         $product->meta_description = $request->meta_description;


    //         if ( !empty($request->code)) {
    //             $product->code = $request->code;
    //         }
    //         else{
    //             $product->code = $this->generateUniqueCode();
    //         }

    //         // image save
    //         if ($request->image){

    //             if (File::exists('images/product/'.$product->thumbnail_image)){
    //                 File::delete('images/product/'.$product->thumbnail_image);
    //             }

    //             $image = $request->file('image');
    //             $img = time() . '.' . $image->getClientOriginalExtension();
    //             $location = public_path('images/product/'. $img);
    //             Image::make($image)->save($location);
    //             $product->thumbnail_image = $img;
    //         }

    //         $product->save();

    //         //filter head section
    //         if ($request->has('filters') && is_array($request->filters)) {
    //             $filterOptionIds = [];
    //             foreach ($request->filters as $filterHeadId => $optionIds) {
    //                 foreach ($optionIds as $optionId) {
    //                     $filterOptionIds[] = $optionId;
    //                 }
    //             }

    //             $product->options()->sync($filterOptionIds);
    //         }


    //         // check if any gallery image then save
    //         if ($request->has('gallery')) {
    //             if (count($request->gallery) > 0){
    //                 foreach ($product->product_image as $image) {
    //                     if (File::exists('images/product/'.$image->image)){
    //                         File::delete('images/product/'.$image->image);
    //                     }
    //                     $image->delete();
    //                 }
    //                 $i = 0;
    //                 foreach ($request->gallery as $gallery){
    //                     $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
    //                     $location = public_path('images/product/'. $img);
    //                     Image::make($gallery)->save($location);

    //                     $gallery = new ProductImage;
    //                     $gallery->image = $img;
    //                     $gallery->product_id = $product->id;
    //                     $gallery->save();
    //                     $i = $i + 1;
    //                 }
    //             }
    //         }

    //         foreach ($product->product_category as $p_category) {
    //             $p_category->delete();
    //         }

    //         if(isset($request->category_id)) {
    //             $new_category = new ProductWithCategory;
    //             $new_category->category_id = $request->category_id;
    //             $new_category->product_id = $product->id;
    //             $new_category->save();
    //         }

    //         if($request->type == 'variation') {
    //             $colors = array();
    //             $attributes = array();
    //             if($request->has('variation_parent')){
    //                 foreach($request->variation_parent as $key => $row) {
    //                     $new_or_old = $request->new_or_old[$key];

    //                     if($new_or_old == 'old') {
    //                         $variation_parent = $request->variation_parent[$key];
    //                         $stock = ProductStocks::find($variation_parent);
    //                         if(!is_null($stock)) {
    //                             if($request->is_active[$key] == 2) { // Delete variation
    //                                 $stock->delete();
    //                             }
    //                             else {

    //                                 if($request->has('colour_attribute')){
    //                                     //For colors attribute
    //                                     $color = $request->colour_attribute[$key];
    //                                     if(!in_array($color, $colors) && $color != '') {
    //                                         array_push($colors, $color);
    //                                     }
    //                                 }
    //                                 else {
    //                                     $color = '';
    //                                 }

    //                                 if($request->has('attribute_id') && $request->attribute_id <> null){
    //                                     //For variation attribute
    //                                     $attribute = $request->attribute_id[$key];
    //                                     if(!in_array($attribute, $attributes) && $attribute != '') {
    //                                         array_push($attributes, $attribute);
    //                                     }
    //                                     $attribute_value = $request->attribute_value[$key] ?? '';
    //                                 }
    //                                 else {
    //                                     $attribute = '';
    //                                     $attribute_value = '';
    //                                 }

    //                                 $stock->color = $color;
    //                                 $stock->variant = $attribute;
    //                                 $stock->variant_output = $attribute_value;
    //                                 $stock->price = $request->variant_price[$key];
    //                                 $stock->qty = $request->variation_stock_qty[$key];
    //                                 $stock->is_active = $request->is_active[$key];


    //                                 // image save
    //                                 $img_v = 'variation_image'.$variation_parent;
    //                                 if ($request->$img_v){

    //                                     if (File::exists('images/product/'.$stock->image)){
    //                                         File::delete('images/product/'.$stock->image);
    //                                     }

    //                                     $image = $request->file($img_v);
    //                                     $img = time().rand().'.' . $image->getClientOriginalExtension();
    //                                     $location = public_path('images/product/'. $img);
    //                                     Image::make($image)->save($location);
    //                                     $stock->image = $img;
    //                                 }

    //                                 $stock->save();
    //                             }
    //                         }
    //                     }
    //                     else {
    //                         $variation_parent = $request->variation_parent[$key];

    //                         if($request->has('colour_attribute')){
    //                             //For colors attribute
    //                             $color = $request->colour_attribute[$key];
    //                             if(!in_array($color, $colors) && $color != '') {
    //                                 array_push($colors, $color);
    //                             }
    //                         }
    //                         else {
    //                             $color = '';
    //                         }

    //                         if($request->has('attribute_id') && $request->attribute_id <> ''){
    //                             //For variation attribute
    //                             $attribute = $request->attribute_id[$key] ?? '';
    //                             if(!in_array($attribute, $attributes) && $attribute != '') {
    //                                 array_push($attributes, $attribute);
    //                             }
    //                             $attribute_value = $request->attribute_value[$key] ?? '';
    //                         }
    //                         else {
    //                             $attribute = '';
    //                             $attribute_value = '';
    //                         }

    //                         $stock = new ProductStocks;
    //                         $stock->product_id = $product->id;
    //                         $stock->color = $color;
    //                         $stock->variant = $attribute;
    //                         $stock->variant_output = $attribute_value;
    //                         $stock->price = $request->variant_price[$key];
    //                         $stock->qty = $request->variation_stock_qty[$key];

    //                         // image save
    //                         $img_v = 'variation_image'.$variation_parent;
    //                         if ($request->$img_v){
    //                             $image = $request->file($img_v);
    //                             $img = time().rand().'.' . $image->getClientOriginalExtension();
    //                             $location = public_path('images/product/'. $img);
    //                             Image::make($image)->save($location);
    //                             $stock->image = $img;
    //                         }

    //                         $stock->save();
    //                     }



    //                 }

    //                 $product->colors = $colors;
    //                 $product->attributes = $attributes;
    //                 $product->save();

    //             }
    //         }
    //         else {

    //             if(count($product->variation_stock) > 0) {
    //                 foreach($product->variation_stock as $v_stock) {
    //                     if (File::exists('images/product/'.$v_stock->image)){
    //                         File::delete('images/product/'.$v_stock->image);
    //                     }
    //                     $v_stock->delete();
    //                 }
    //             }

    //             $stock = $product->single_stock;
    //             if(!is_null($stock)) {
    //                 $stock->price = $request->single_price;
    //                 $stock->qty = $request->single_qty;
    //                 $stock->save();
    //             }
    //             else {
    //                 $stock = new ProductStocks;
    //                 $stock->product_id = $product->id;
    //                 $stock->price = $request->single_price;
    //                 $stock->qty = $request->single_qty;
    //                 $stock->save();
    //             }
    //         }

    //         Alert::toast('Product Updated!', 'success');
    //         return redirect()->route('product.index');
    //     }
    //     else{
    //         Alert::toast('Something went wrong!', 'error');
    //         return redirect()->route('product.index');
    //     }
    // }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:191',
            'category_id' => 'required|max:191',
        ]);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;

        while (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $product = Product::find($id);
        if (!$product) {
            Alert::toast('Something went wrong!', 'error');
            return redirect()->route('product.index');
        }

        $callForPrice = $request->has('call_for_price') ? 1 : 0;
        $product->slug = $slug;
        $product->title = $request->title;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_type = $request->unit_type;
        $product->is_featured = $request->has('is_featured') ? 1 : 0;
        $product->is_tranding = $request->has('is_tranding') ? 1 : 0;
        $product->todays_deal = $request->has('todays_deal') ? 1 : 0;
        $product->discount_type = $request->discount_type;
        $product->discount_amount = $request->discount_amount;
        $product->type = $request->type;
        $product->feature = $request->feature;
        $product->specification = $request->specification;
        $product->description = $request->description;
        $product->call_for_price = $callForPrice;

        $product->meta_title = $request->meta_title;
        $product->meta_keywords = $request->meta_keywords;
        $product->tags = $request->tags;
        $product->meta_description = $request->meta_description;

        $product->code = $request->filled('code') ? $request->code : $this->generateUniqueCode();

        // thumbnail image
        if ($request->image) {
            if (File::exists('images/product/' . $product->thumbnail_image)) {
                File::delete('images/product/' . $product->thumbnail_image);
            }
            $image = $request->file('image');
            $img = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/product/' . $img);
            Image::make($image)->save($location);
            $product->thumbnail_image = $img;
        }

        $product->save();

        // filter head options
        if ($request->has('filters') && is_array($request->filters)) {
            $filterOptionIds = [];
            foreach ($request->filters as $filterHeadId => $optionIds) {
                foreach ($optionIds as $optionId) {
                    $filterOptionIds[] = $optionId;
                }
            }
            $product->options()->sync($filterOptionIds);
        }

        // update gallery
        if ($request->has('gallery') && count($request->gallery) > 0) {
            foreach ($product->product_image as $image) {
                if (File::exists('images/product/' . $image->image)) {
                    File::delete('images/product/' . $image->image);
                }
                $image->delete();
            }

            $i = 0;
            foreach ($request->gallery as $gallery) {
                $img = time() . $i . '.' . $gallery->getClientOriginalExtension();
                $location = public_path('images/product/' . $img);
                Image::make($gallery)->save($location);

                $galleryImage = new ProductImage;
                $galleryImage->image = $img;
                $galleryImage->product_id = $product->id;
                $galleryImage->save();
                $i++;
            }
        }

        // update categories
        foreach ($product->product_category as $p_category) {
            $p_category->delete();
        }

        if ($request->filled('category_id')) {
            $new_category = new ProductWithCategory;
            $new_category->category_id = $request->category_id;
            $new_category->product_id = $product->id;
            $new_category->save();
        }

        // handle variation
        if ($request->type == 'variation') {
            $colors = [];
            $attributes = [];

            if ($request->has('variation_parent')) {
                foreach ($request->variation_parent as $key => $row) {
                    $new_or_old = $request->new_or_old[$key];
                    $variation_parent = $request->variation_parent[$key];

                    if ($new_or_old == 'old') {
                        $stock = ProductStocks::find($variation_parent);
                        if ($stock) {
                            if ($request->is_active[$key] == 2) {
                                if (File::exists('images/product/' . $stock->image)) {
                                    File::delete('images/product/' . $stock->image);
                                }
                                $stock->delete();
                            } else {
                                $color = $request->colour_attribute[$key] ?? '';
                                $attribute = $request->attribute_id[$key] ?? '';
                                $attribute_value = $request->attribute_value[$key] ?? '';

                                if (!in_array($color, $colors) && $color !== '')
                                    $colors[] = $color;
                                if (!in_array($attribute, $attributes) && $attribute !== '')
                                    $attributes[] = $attribute;

                                $stock->color = $color;
                                $stock->variant = $attribute;
                                $stock->variant_output = $attribute_value;
                                $stock->price = $callForPrice ? 0 : ($request->variant_price[$key] ?? 0);
                                $stock->qty = $callForPrice ? 0 : ($request->variation_stock_qty[$key] ?? 0);
                                $stock->is_active = $request->is_active[$key];

                                $img_v = 'variation_image' . $variation_parent;
                                if ($request->hasFile($img_v)) {
                                    if (File::exists('images/product/' . $stock->image)) {
                                        File::delete('images/product/' . $stock->image);
                                    }
                                    $image = $request->file($img_v);
                                    $img = time() . rand() . '.' . $image->getClientOriginalExtension();
                                    $location = public_path('images/product/' . $img);
                                    Image::make($image)->save($location);
                                    $stock->image = $img;
                                }

                                $stock->save();
                            }
                        }
                    } else {
                        $color = $request->colour_attribute[$key] ?? '';
                        $attribute = $request->attribute_id[$key] ?? '';
                        $attribute_value = $request->attribute_value[$key] ?? '';

                        if (!in_array($color, $colors) && $color !== '')
                            $colors[] = $color;
                        if (!in_array($attribute, $attributes) && $attribute !== '')
                            $attributes[] = $attribute;

                        $stock = new ProductStocks;
                        $stock->product_id = $product->id;
                        $stock->color = $color;
                        $stock->variant = $attribute;
                        $stock->variant_output = $attribute_value;
                        $stock->price = $callForPrice ? 0 : ($request->variant_price[$key] ?? 0);
                        $stock->qty = $callForPrice ? 0 : ($request->variation_stock_qty[$key] ?? 0);

                        $img_v = 'variation_image' . $variation_parent;
                        if ($request->hasFile($img_v)) {
                            $image = $request->file($img_v);
                            $img = time() . rand() . '.' . $image->getClientOriginalExtension();
                            $location = public_path('images/product/' . $img);
                            Image::make($image)->save($location);
                            $stock->image = $img;
                        }

                        $stock->save();
                    }
                }

                $product->colors = $colors;
                $product->attributes = $attributes;
                $product->save();
            }
        } else {
            // Simple product stock
            if (count($product->variation_stock) > 0) {
                foreach ($product->variation_stock as $v_stock) {
                    if (File::exists('images/product/' . $v_stock->image)) {
                        File::delete('images/product/' . $v_stock->image);
                    }
                    $v_stock->delete();
                }
            }

            $stock = $product->single_stock;
            if ($stock) {
                $stock->price = $callForPrice ? 0 : ($request->single_price ?? 0);
                $stock->qty = $callForPrice ? 0 : ($request->single_qty ?? 0);
                $stock->save();
            } else {
                $stock = new ProductStocks;
                $stock->product_id = $product->id;
                $stock->price = $callForPrice ? 0 : ($request->single_price ?? 0);
                $stock->qty = $callForPrice ? 0 : ($request->single_qty ?? 0);
                $stock->save();
            }
        }

        return redirect()->route('product.index')->with('success', 'Product Updated!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!is_null($product)) {
            // Deleting the gallery files
            foreach ($product->product_image as $image) {
                if (File::exists('images/product/' . $image->image)) {
                    File::delete('images/product/' . $image->image);
                }
                $image->delete();
            }
            // Deleting the product image
            if (File::exists('images/product/' . $product->thumbnail_image)) {
                File::delete('images/product/' . $product->thumbnail_image);
            }

            // Deleting the variations 
            if (count($product->variation_stock) > 0) {
                foreach ($product->variation_stock as $v_stock) {
                    if (File::exists('images/product/' . $v_stock->image)) {
                        File::delete('images/product/' . $v_stock->image);
                    }
                    $v_stock->delete();
                }
            }

            $product->delete();
            Alert::toast('Product has been deleted !', 'success');
            return back();
        } else {
            session()->flash('error', 'Something went wrong !');
            return back();
        }
    }

    public function color_index()
    {

        if (user()->type == 1) {
            $colors = Colors::orderBy('name', 'ASC')->get();
            return view('admin.color.index', compact('colors'));
        } else {
            Alert::toast('Access Denied !', 'error');
            return back();
        }
    }

    public function color_store(Request $request)
    {

        if ($request->color_code) {
            $code = $request->color_code;
        } else {
            $code = $request->code;
        }
        $name = $request->name;

        $check_color = Colors::where('name', $name)->orWhere('code', $code)->first();
        if (!is_null($check_color)) {
            Alert::toast('This name or Color is exist!', 'error');
            return back();
        }

        $color = new Colors;
        $color->name = $name;
        $color->code = $code;
        $color->save();

        Alert::toast('New Color Added.', 'success');
        return back();

    }

    public function color_edit($id)
    {
        if (user()->type == 1) {
            $color = Colors::find($id);
            if (!is_null($color)) {
                return view('admin.color.edit', compact('color'));
            } else {
                session()->flash('error', 'Something went wrong !');
                return back();
            }
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }

    public function color_update(Request $request, $id)
    {
        if ($request->color_code) {
            $code = $request->color_code;
        } else {
            $code = $request->code;
        }
        $name = $request->name;

        $color = Colors::find($id);
        $color->name = $name;
        $color->code = $code;
        $color->save();

        Alert::toast('Color Updated.', 'success');
        return Redirect()->route('color.index');

    }

    public function product_stock()
    {
        if (user()->type == 1) {
            $stock_info = ProductStocks::all();
            return view('admin.product.product_stock', compact('stock_info'));
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }

    public function stock_qty_update(Request $request)
    {
        if (user()->type == 1) {
            $stock_id = $request->stock_id;
            $stock_info = ProductStocks::find($stock_id);
            if (is_null($stock_info)) {
                session()->flash('error', 'Product Stock Info not Found!');
                return back();
            }
            $stock_info->qty = ($request->stock_qty) + 0;
            $stock_info->save();
            return back()->with('success', 'Product Stock Quantity Updated.');
        } else {
            session()->flash('error', 'Access Denied !');
            return back();
        }
    }


    /**
     * filter head
     */
    public function filterHeadIndex()
    {
        $filter_heads = FilterHead::with('categories', 'options')
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::where('parent_id', '=', 0)->orderBy('id', 'DESC')->get();

        return view('admin.product.filter_head_index', compact('filter_heads', 'categories'));
    }

    /**
     * filter head
     */
    public function storeFilterHead(Request $request)
    {
        $request->validate([
            // 'name' => 'required|string|max:255|unique:filter_heads,name',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $filterHead = FilterHead::where('name', $request->name)->first();

        if (!$filterHead) {
            $filterHead = new FilterHead();
            $filterHead->name = $request->name;
            $filterHead->save();
        }

        $filterHead->categories()->syncWithoutDetaching($request->category_ids);


        // $filterHead = FilterHead::firstOrNew([
        //     'name' => $request->name,
        // ]);

        // $filterHead->categories()->syncWithoutDetaching($request->category_ids);

        return redirect()->back()->with('success', 'Filter head created and linked to selected categories.');
    }



    /**
     * options head store
     */
    public function storeFilterHeadOptions(Request $request)
    {

        $request->validate([
            'filter_head_id' => 'required',
            'name' => 'required',
        ]);

        FilterHeadOption::create([
            'filter_head_id' => $request->filter_head_id,
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Options added successfully.');

    }

    /**
     * filter head delte
     */
    public function destroyFilterHeadOption($id)
    {
        $head = FilterHead::find($id);

        if (empty($head)) {
            return redirect()->back()->with('error', 'Data not found');
        }

        // Delete all associated options
        FilterHeadOption::where('filter_head_id', $id)->delete();

        // Optionally delete the head itself
        $head->delete();

        return redirect()->back()->with('success', 'Filter Head and its options deleted successfully.');
    }





}
