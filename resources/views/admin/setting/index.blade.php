@extends('admin.layouts.master')
@section('content')
 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Business Settings</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active">business-settings</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>

<!-- /.content-header -->
<section class="content">
	<div class="container-fluid">
		<div class="card">
              <div class="card-header">
                
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
               <form action="{{ route('setting.update')}}" method="POST" enctype="multipart/form-data">
                 @csrf
                 <div class="row">
                   <div class="col-md-3">
                     <div class="form-group">
                       <label>Shop Name*</label>
                       <input type="text" name="name" required value="{{ optional($setting)->name }}" class="form-control @error('name') is-invalid @enderror">
                       @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-3">
                    <div class="form-group">
                      <label>Header Logo*</label>
                      <input type="file" name="header_logo" class="form-control @error('header_logo') is-invalid @enderror">
                      @error('header_logo')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                       <img class="mt-2 shadow rounded border p-1" width="100%" src="{{ asset('images/website/'. optional($setting)->header_logo) }}">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Invoice Logo*</label>
                      <input type="file" name="footer_logo" class="form-control @error('footer_logo') is-invalid @enderror">
                      @error('footer_logo')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                       <img class="mt-2 shadow rounded border p-1" width="100%" src="{{ asset('images/website/'. optional($setting)->footer_logo) }}">
                    </div>
                  </div>
                  
                   <div class="col-md-3">
                     <div class="form-group">
                       <label>Favicon*</label>
                       <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror">
                       @error('favicon')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <img class="mt-2 shadow rounded border p-1" width="100px" src="{{ asset('images/website/'. optional($setting)->favicon) }}">
                     </div>
                   </div>
                   
                   <div class="col-md-6">
                     <div class="form-group">
                       <label>Phone*</label>
                       <input type="text" name="phone" required value="{{ optional($setting)->phone }}" class="form-control @error('phone') is-invalid @enderror">
                       @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                   </div>

                   <div class="col-md-6">
                    <div class="form-group">
                      <label>Email*</label>
                      <input type="email" name="email" value="{{ optional($setting)->email }}" class="form-control @error('email') is-invalid @enderror">
                      @error('email')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Address*</label>
                      <input type="text" name="address" required value="{{ optional($setting)->address }}" class="form-control @error('address') is-invalid @enderror">
                      @error('address')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Head Code</label>
                      <input type="text" name="head" value="{{ optional($setting)->head }}" class="form-control @error('head') is-invalid @enderror">
                      @error('head')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Body Code</label>
                      <input type="text" name="body" value="{{ optional($setting)->body }}" class="form-control @error('body') is-invalid @enderror">
                      @error('body')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Google Map Link</label>
                      <input type="text" name="google_map_link" value="{{ optional($setting)->google_map_link }}" class="form-control @error('google_map_link') is-invalid @enderror">
                      @error('google_map_link')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Google Map Embed</label>
                      <textarea name="google_map_embed" class="form-control @error('google_map_link') is-invalid @enderror" cols="30" rows="10">{{ optional($setting)->google_map_embed }}</textarea>
                      @error('google_map_embed')
                           <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                           </span>
                       @enderror
                    </div>
                  </div>

                  

                  <div class="col-md-12 p-2">
                    <div class="row shadow rounded border p-2">
                        <div class="col-md-12"><h3><b>Colors</b></h3></div>
                        @php
                            $fields = [
                                ['name' => 'logo_color', 'label' => 'Logo Color', 'col' => 2],
                                ['name' => 'primary_color', 'label' => 'Primary', 'col' => 2],
                                ['name' => 'secondary_color', 'label' => 'Secondary', 'col' => 2],
                                ['name' => 'header_topbar_bg_color', 'label' => 'Header Topbar bg', 'col' => 3],
                                ['name' => 'header_topbar_text_color', 'label' => 'Header Topbar text', 'col' => 3],
                                ['name' => 'header_bg_color', 'label' => 'Header bg', 'col' => 2],
                                ['name' => 'header_text_color', 'label' => 'Header text', 'col' => 2],
                                ['name' => 'header_bottom_bg_color', 'label' => 'Header Bottom bg', 'col' => 3],
                                ['name' => 'header_bottom_text_color', 'label' => 'Header Bottom text', 'col' => 3],
                                ['name' => 'body_bg_color', 'label' => 'Body_bg', 'col' => 2],
                                ['name' => 'category_bg_color', 'label' => 'Category_bg', 'col' => 2],
                                ['name' => 'footer_bg_color', 'label' => 'Footer_bg', 'col' => 3],
                                ['name' => 'footer_text_color', 'label' => 'Footer_text', 'col' => 3],
                            ];
                        @endphp
                        @foreach ($fields as $field)
                            <div class="col-md-{{ $field['col'] }}">
                                <div class="form-group">
                                    <label>{{ $field['label'] }} <span class="text-danger">*</span> </label>

                                    <div class="input-group my-colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                      <input type="text" name="{{ $field['name'] }}" value="{{ optional($setting)->{$field['name']} }}" required class="form-control @error($field['name']) is-invalid @enderror" data-original-title="" title="">
                                      <div class="input-group-append">
                                      <span class="input-group-text"><i class="fas fa-square" style="color: {{ optional($setting)->{$field['name']} }};"></i></span>
                                      </div>
                                      </div>

                                    @error($field['name'])
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                        <script>
                          $('.my-colorpicker2').colorpicker()

                          $('.my-colorpicker2').on('colorpickerChange', function(event) {
                            $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
                          })
                        </script>
                    </div>
                </div>
                
                   <div class="col-md-12 p-2">
                    <div class="row shadow rounded border p-2">
                        <div class="col-md-12"><h3><b>Social Media Link</b></h3></div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Facebook</label>
                            <input type="text" name="facebook" value="{{ optional($setting)->facebook }}" class="form-control @error('facebook') is-invalid @enderror">
                            @error('facebook')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Instagram</label>
                            <input type="text" name="instagram" value="{{ optional($setting)->instagram }}" class="form-control @error('instagram') is-invalid @enderror">
                            @error('instagram')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Twitter</label>
                            <input type="text" name="twitter" value="{{ optional($setting)->twitter }}" class="form-control @error('twitter') is-invalid @enderror">
                            @error('twitter')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>YouTube</label>
                            <input type="text" name="youtube" value="{{ optional($setting)->youtube }}" class="form-control @error('youtube') is-invalid @enderror">
                            @error('youtube')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Linkedin</label>
                            <input type="text" name="linkedin" value="{{ optional($setting)->linkedin }}" class="form-control @error('linkedin') is-invalid @enderror">
                            @error('linkedin')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                    </div>
                   </div>

                   <div class="col-md-12 p-2">
                    <div class="row shadow rounded border p-2">
                        <div class="col-md-12"><h3><b>Meta Info for SEO</b></h3></div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Meta Title</label>
                            <input type="text" name="meta_title" value="{{ optional($setting)->meta_title }}" class="form-control @error('meta_title') is-invalid @enderror">
                            @error('meta_title')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ optional($setting)->meta_keywords }}" class="form-control @error('meta_keywords') is-invalid @enderror">
                            @error('meta_keywords')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Meta Tags</label>
                            <input type="text" name="meta_tag" value="{{ optional($setting)->meta_tag }}" class="form-control @error('meta_tag') is-invalid @enderror">
                            @error('meta_tag')
                                 <span class="invalid-feedback" role="alert">
                                     <strong>{{ $message }}</strong>
                                 </span>
                             @enderror
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Meta Description</label>
                            <input type="text" name="meta_description" class="form-control" value="{{ optional($setting)->meta_description }}" id="">
                          </div>
                        </div>
                    </div>
                   </div>

                   <div class="col-md-12 text-right mt-2">
                     <div class="form-group">
                       <button class="btn btn-primary">Save Changes</button>
                     </div>
                   </div>
                 </div>
               </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
	</div>
</section>
@endsection

@section('scripts')
	<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    
  });
</script>
@endsection