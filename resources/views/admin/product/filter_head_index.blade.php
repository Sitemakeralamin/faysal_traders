@extends('admin.layouts.master')
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h3 class="m-0 fw-bold">Create Filter Head</h3>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <!-- Create Filter Head -->
        <form action="{{ route('product.head.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="form-group col-md-4">
              <label><b>Filter Head Name</b> <span class="text-danger">*</span></label>
              <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
              @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group col-md-4">
              <label>Select Categories</label>
              <select name="category_ids[]" class="form-control select2" multiple required>
                  @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @if (count($category->child) > 0 ) 
                          @foreach($category->child as $sub_category)
                            <option value="{{ $sub_category->id }}"> > {{ $sub_category->title }}</option>
                                @if (count($sub_category->child) > 0 ) 
                                  @foreach($sub_category->child as $sub_sub__category)
                                    <option value="{{ $sub_sub__category->id }}"> > > {{ $sub_sub__category->title }}</option>
                                  @endforeach
                                @endif 
                          @endforeach
                        @endif 
                  @endforeach
              </select>
              {{-- <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small> --}}
          </div>
          

            <div class="form-group col-md-4 text-left">
              <label>&nbsp;</label><br>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </form>
      </div>

      <!-- Filter Heads List -->
      <div class="card-body">
        <h5>Filter Head List</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>SL</th>
              <th>Name</th>
              <th>Category</th>
              <th>Options</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($filter_heads as $index => $head)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $head->name }}</td>
                <td>
                  @foreach($head->categories as $option)
                    <span class="badge badge-secondary">{{ $option->title }}</span>
                  @endforeach

                </td>
                <td>
                  @foreach($head->options as $option)
                    <span class="badge badge-secondary">{{ $option->name }}</span>
                  @endforeach
                </td>
                <td>
                  <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#optionModal" data-head="{{ $head->id }}" data-headname="{{ $head->name }}">Add Option</button>

                  <form action="{{ route('product.filter.head.option.delete', $head->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this option?')">
                      Delete
                    </button>
                  </form>
				
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Modal for Adding Filter Option -->
<div class="modal fade" id="optionModal" tabindex="-1" aria-labelledby="optionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('product.head.options.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Option to: <span id="head-name"></span></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="filter_head_id" id="head-id">

          <div class="form-group">
            <label>Option Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Option</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $('#optionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var headId = button.data('head');
    var headName = button.data('headname');

    var modal = $(this);
    modal.find('#head-id').val(headId);
    modal.find('#head-name').text(headName);
  });
</script>
@endsection
