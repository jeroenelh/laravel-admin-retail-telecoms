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
                        <label>@lang("laravel-admin-retail-telecoms::kiosk.display_title")</label>
                        {{ Form::text('display_title', $data['model']->display_title, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::kiosk.slug")</label>
                        {{ Form::text('slug', $data['model']->slug, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::kiosk.is_active")</label>
                        <div style="height: 34px;line-height: 34px;">
                            <label>
                                {{ Form::radio('is_active', true, ($data['model']->is_active == true)) }}
                                @lang("laravel-admin-retail-telecoms::kiosk.is_active.yes")
                            </label> &nbsp;
                            <label>
                                {{ Form::radio('is_active', false, ($data['model']->is_active == false)) }}
                                @lang("laravel-admin-retail-telecoms::kiosk.is_active.no")
                            </label>
                        </div>
                    </div>
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