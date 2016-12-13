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

    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">Persoonlijke informatie</h2>
                </div>
                <div class="box-body">
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::user.name")</label>
                        {{ Form::text('name', $data['model']->name, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::user.email")</label>
                        {{ Form::text('email', $data['model']->email, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-4 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::user.password")</label>
                        {{ Form::password('password', ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">Winkels</h2>
                </div>
                <div class="box-body">
                    <?php
                    $stores = \Microit\LaravelAdminRetailTelecoms\Models\Store::orderBy('display_title')->get();
                    ?>
                    @foreach($stores as $store)
                        <label style="display: block">
                            <?php
                            $checked = ($data['model']->stores && $data['model']->stores->contains($store->id));
                            ?>
                            {{ Form::checkbox('stores[]', $store->id, $checked) }}
                            {{ $store->display_title }}
                        </label>
                    @endforeach
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