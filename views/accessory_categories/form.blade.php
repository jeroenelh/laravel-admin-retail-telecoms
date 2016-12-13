@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
    @if(count($errors->all()))
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    {{ Form::hidden('store_id', session('store')) }}

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory_category.display_title")</label>
                        {{ Form::text('display_title', $data['model']->display_title, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory_category.slug")</label>
                        {{ Form::text('slug', $data['model']->slug, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory_category.media")</label>
                        {{ Form::file('file', ['class' => 'form-control']) }}
                    </div>
                    @if($data['model']->medium)
                        <div class="col-md-6">
                            <label>@lang("laravel-admin-retail-telecoms::accessory.media")</label><br />
                            <img src="{{ '/images/uploads/category/'.$data['model']->medium->storage_name }}" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_footer')
    @parent
    <script>
        $(document).ready(function(){
           if (
                    $("input[name='slug']").length == 1 &&
                    $("input[name='display_title']").length == 1 &&
                    $("input[name='slug']").val() == slugify($("input[name='display_title']").val())
            ) {
                $("input[name='display_title']").bind('keyup', function() {
                    console.log("Hi!");
                    var display_title = $("input[name='display_title']").val();
                    var slug = $("input[name='slug']").val();
                    slug = slugify(display_title);
                    $( "input[name='slug']" ).val(slug);
                });
            }
        });

        function slugify(text)
        {
            return text.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')           // Replace spaces with -
                    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                    .replace(/^-+/, '')             // Trim - from start of text
                    .replace(/-+$/, '');            // Trim - from end of text
        }
    </script>
@endsection