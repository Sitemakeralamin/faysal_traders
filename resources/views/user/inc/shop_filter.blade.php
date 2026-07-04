{{-- Price Range --}}
<style>
    .widget__bg{
        background-color: var(--white-color) !important;
    }

    .caret-icon {
        transition: transform 0.3s ease;
    }

    .caret-rotated {
        transform: rotate(180deg); /* rotate the arrow */
    }

    .price__filter--btn {
    height: 3rem !important;
    line-height: 3rem !important;
    }

    .primary__btn {
        font-size: 1.4rem;
        font-weight: var(--body-line-height);
        line-height: 3rem !important;
        display: inline-block;
        height: 3rem !important;
        padding: 0 1.4rem !important;;
        letter-spacing: .1px !important;;
        border-radius: .3rem;
        background: var(--pick-purple);
        color: var(--white-color);
        border: 0;
    }
</style>

<div class="single__widget price__filter widget__bg accordion">
    <h5 class="widget__title h6 mb-0">
        <a class="d-block py-2 filterPrice">
            Filter By Price
            <span class="caret-icon @if (request()->min_price>0 && request()->max_price>0) caret-rotated @endif">&#9660;</span> <!-- ▼ -->
        </a>
    </h5>

    <div class="collapse collapsePrice 
    @if (request()->min_price>0 && request()->max_price>0) show @endif"> {{-- Set to .show if you want it open by default --}}
        <form class="price__filter--form mt-3" action=""> 
            <div class="price__filter--form__inner mb-3 d-flex align-items-center">
                <div class="price__filter--group mr-3">
                    <label class="price__filter--label" for="fromPrice">From</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">{{ env('CURRENCY') }}</span>
                        <input class="price__filter--input__field border-0 ml-1" id="fromPrice" 
                               type="number" value="{{ $min_price }}" name="min_price" min="0">
                    </div>
                </div>
                <div class="price__divider mx-2">
                    <span>-</span>
                </div>
                <div class="price__filter--group">
                    <label class="price__filter--label" for="toPrice">To</label>
                    <div class="price__filter--input border-radius-5 d-flex align-items-center">
                        <span class="price__filter--currency">{{ env('CURRENCY') }}</span>
                        <input class="price__filter--input__field border-0 ml-1" id="toPrice"
                               type="number" value="{{ $max_price }}" name="max_price" min="0">
                    </div>	
                </div>
            </div>
            <button class="price__filter--btn primary__btn btn-block" type="submit">Filter</button>
        </form>
    </div>
</div>


@if ($category && isset($category->children) && $category->children->count() == 0)
{{-- Brands --}}
<div class="single__widget widget__bg">
 
    <h5 class="widget__title h6 mb-0">
        <a class="d-block py-2 filterPrice1">
            Brands
            <span class="caret-icon">&#9660;</span> <!-- ▼ -->
        </a>
    </h5>


    <div class="collapse collapsePrice1">
        <ul class="widget__form--check" style="overflow-y: scroll; height:300px;">
            {{-- @foreach($brands as $brand)
            <li class="widget__form--check__list">
                <label class="widget__form--check__label" for="brand_{{$brand->id}}">{{$brand->title}}</label>
                <input value="{{$brand->id}}" 
                @if ($brand->id==$brand_id) checked @endif
                class="widget__form--check__input cls_brand_{{$brand->id}} brands"  id="brand_{{$brand->id}}" type="checkbox" data-title="{{ $brand->title }}">
                <span class="widget__form--checkmark"></span>
            </li>
            @endforeach --}}

        @foreach($brands as $brand)
            <li class="widget__form--check__list">
                <label class="widget__form--check__label">
                    <input value="{{ $brand->id }}" 
                        @if ($brand->id==$brand_id) checked @endif
                        class="widget__form--check__input cls_brand_{{$brand->id}} brands"  
                        id="brand_{{ $brand->id }}" type="checkbox" data-title="{{ $brand->title }}">
                    {{ $brand->title }}
                    <span class="widget__form--checkmark"></span>
                </label>
            </li>
        @endforeach

        </ul>
    </div>

</div>
@endif

{{-- @php
    $filterHeads = \App\Models\FilterHead::with('options')->get();
@endphp

@foreach($filterHeads as $filterHead)
<div class="single__widget widget__bg">
    <h2 class="widget__title h6 mb-1">{{ $filterHead->name }}</h2>
    <ul class="widget__form--check" style="max-height: 250px; overflow-y: auto;">
        @foreach($filterHead->options as $option)
        <li class="widget__form--check__list">
            <label class="widget__form--check__label" for="option_{{ $option->id }}">
                {{ $option->name }}
            </label>
            <input type="checkbox"
                class="widget__form--check__input filter-option"
                data-head="{{ $filterHead->id }}"
                data-title="{{ $option->name }}"
                value="{{ $option->id }}"
                id="option_{{ $option->id }}">
            <span class="widget__form--checkmark"></span>
        </li>
        @endforeach
    </ul>  

</div>
@endforeach --}}


{{-- @if($filterHeads && $filterHeads->isNotEmpty())
    @foreach($filterHeads as $filterHead)
    <div class="single__widget widget__bg">
        <h2 class="widget__title h6 mb-1">{{ $filterHead->name }}</h2>
        <ul class="widget__form--check" style="max-height: 250px; overflow-y: auto;">
            @foreach($filterHead->options as $option)
            <li class="widget__form--check__list">
                <label class="widget__form--check__label" for="option_{{ $option->id }}">
                    {{ $option->name }}
                </label>
                <input type="checkbox"
                    class="widget__form--check__input filter-option"
                    data-head="{{ $filterHead->id }}"
                    data-title="{{ $option->name }}"
                    value="{{ $option->id }}"
                    id="option_{{ $option->id }}">
                <span class="widget__form--checkmark"></span>
            </li>
            @endforeach
        </ul>  
    </div>
    @endforeach
@endif --}}


@if($filterHeads && $filterHeads->isNotEmpty())
    @foreach($filterHeads as $filterHead)
    <div class="single__widget widget__bg">
        <h5 class="widget__title h6 mb-0">
            <a class="d-block py-2 filterToggle" data-target=".collapseHead{{ $filterHead->id }}">
                {{ $filterHead->name }}
                <span class="caret-icon">&#9660;</span>
            </a>
        </h5>

        <div class="collapse collapseHead{{ $filterHead->id }}">
            <ul class="widget__form--check" style="overflow-y: scroll; height:300px;">
                @foreach($filterHead->options as $option)
                <li class="widget__form--check__list">
                   <label class="widget__form--check__label">
    <input type="checkbox"
           class="widget__form--check__input filter-option"
           data-head="{{ $filterHead->id }}"
           data-title="{{ $option->name }}"
           value="{{ $option->id }}">
    {{ $option->name }}
    <span class="widget__form--checkmark"></span>
</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endforeach
@endif




{{-- <div class="single__widget widget__bg">    
    <h5 class="widget__title h6 mb-0">
        <a class="d-block filterPrice2">
            Categories
            <span class="caret-icon">&#9660;</span>
        </a>
    </h5>

   <div class="collapse collapsePrice2">
        <ul class="widget__categories--menu" style="overflow-y: scroll; height:300px;">
            @foreach($categories->where('is_active',1) as $category)
            <li class="widget__categories--menu__list">
                @if(count($category->child) > 0)
                    
                    <label class="widget__categories--menu__label d-flex align-items-center"> 
                        <img class="widget__categories--menu__img d-none" 
                        src="{{ category_image($category->image)}}" alt="{{$category->title}}">
                        <span class="widget__categories--menu__text">{{$category->title}}</span>
                       
                        
                        <svg class="widget__categories--menu__arrowdown--icon" xmlns="http://www.w3.org/2000/svg" width="12.355" height="8.394">
                            <path  d="M15.138,8.59l-3.961,3.952L7.217,8.59,6,9.807l5.178,5.178,5.178-5.178Z" transform="translate(-6 -8.59)" fill="currentColor"></path>
                        </svg>
                    </label>
                   
                    <ul class="widget__categories--sub__menu">
                        @foreach($category->child as $p_category)
                            <li class="widget__categories--sub__menu--list">
                                @if($p_category->children->count() > 0)
                                    
                                    <a class="widget__categories--sub__menu--link d-flex align-items-center" 
                                    href="{{ route('products.sub.category', ['main_cat' => $category->slug, 'sub_cat' => $p_category->slug]) }}">
                                    <label class="widget__categories--menu__label d-flex align-items-center"> 
                                        <img class="widget__categories--menu__img d-none" 
                                        src="{{ category_image($p_category->image)}}" alt="{{$p_category->title}}">
                                        <span class="widget__categories--menu__text">{{$p_category->title}}</span>
                                    </label>
                                    </a>
                                    <ul class="widget__categories--sub__menu" style="display: block; margin-left:20px;">
                                        @foreach($p_category->children as $child)
                                            <li class="widget__categories--sub__menu--list">
                                                <a href="{{ route('products.child.category',['main_cat' => $category->slug,'sub_cat' => $p_category->slug,'child_slug' => $child->slug,]) }}" class="widget__categories--sub__menu--link d-flex align-items-center">
                                                    <img class="widget__categories--sub__menu--img d-none" 
                                                    src="{{ category_image($child->image)}}" alt="{{$child->title}}">
                                                    <span class="widget__categories--sub__menu--text">{{$child->title}}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                <a class="widget__categories--sub__menu--link d-flex align-items-center" href="{{ route('products.sub.category', ['main_cat' => $category->slug, 'sub_cat' => $p_category->slug]) }}">
                                    <img class="widget__categories--sub__menu--img d-none" 
                                    src="{{ category_image($p_category->image)}}" alt="{{$p_category->title}}">
                                    <span class="widget__categories--sub__menu--text">{{$p_category->title}}</span>
                                </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
               
                    <a class="widget__categories--menu__label d-flex align-items-center" 
                        href="{{ route('product.show', $category->slug) }}">
                            <img class="widget__categories--menu__img d-none" 
                            src="{{ category_image($category->image)}}" alt="{{$category->title}}">
                            <span class="widget__categories--menu__text">{{$category->title}}</span>
                    </a>
                @endif
            </li>
            @endforeach
        </ul>
   </div>
</div> --}}

