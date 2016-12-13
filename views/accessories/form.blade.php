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
        <div class="col-md-8">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.display_title")</label>
                        {{ Form::text('display_title', $data['model']->display_title, ['class' => 'form-control']) }}
                    </div>
                    {{--<div class="col-md-6 form-group">--}}
                        {{--<label>@lang("laravel-admin-retail-telecoms::accessory.slug")</label>--}}
                        {{--{{ Form::text('slug', $data['model']->slug, ['class' => 'form-control']) }}--}}
                    {{--</div>--}}
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.accessory_category_id")</label>
                        <?php
                        $categories = [null => '== Kies categorie =='];
                        $acs = \Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory
                                ::currentStore()
                                ->orderBy('display_title')->get();
                        foreach ($acs as $ac) {
                            $categories[$ac->id] = $ac->display_title;
                        }
                        ?>
                        {{ Form::select('accessory_category_id', $categories, $data['model']->accessory_category_id, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.media")</label>
                        {{ Form::file('file', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.article_number")</label>
                        {{ Form::text('article_number', $data['model']->article_number, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.ean")</label>
                        {{ Form::text('ean', $data['model']->ean, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.sku")</label>
                        {{ Form::text('sku', $data['model']->sku, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.brand")</label>
                        {{ Form::text('brand', $data['model']->brand, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.price")</label>
                        {{ Form::text('price', $data['model']->price, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-3 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.is_active")</label>
                        <div style="height: 34px;line-height: 34px;">
                            <label>
                                {{ Form::radio('is_active', true, ($data['model']->is_active == true)) }}
                                @lang("laravel-admin-retail-telecoms::accessory.is_active.yes")
                            </label> &nbsp;
                            <label>
                                {{ Form::radio('is_active', false, ($data['model']->is_active == false)) }}
                                @lang("laravel-admin-retail-telecoms::accessory.is_active.no")
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.description")</label>
                        {{ Form::textarea('description', $data['model']->description, ['class' => 'form-control']) }}
                    </div>
                    @if($data['model']->medium)
                    <div class="col-md-6">
                        <label>@lang("laravel-admin-retail-telecoms::accessory.media")</label><br />
                        <img src="{{ '/images/uploads/accessory/'.$data['model']->medium->storage_name }}" />
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Apparaten</h3>
                </div>
                <div class="box-body">
                <?php
                $brands = \Microit\LaravelAdminRetailTelecoms\Models\Brand
                            ::currentStore()
                            ->orderBy('display_title')->get();
                ?>
                @foreach($brands as $brand)
                    <div class="checkbox" data-brand="{{ $brand->display_title }}">{{ $brand->display_title }}</div>
                    <div class="">
                        @foreach($brand->devices as $device)
                            <label style="display: block">
                                <?php
                                $checked = $data['model']->devices->contains($device->id);
                                ?>
                                {{ Form::checkbox('devices[]', $device->id, $checked, ['class' => 'checkbox_'.$brand->display_title]) }}
                                {{ $device->display_title }}
                            </label>
                        @endforeach
                    </div>
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

        $(document).ready(function() {
            $('.checkbox').on('dblclick', function() {
                var $brand = $(this).data('brand');
                $('.checkbox_' + $brand).attr('checked', 'checked');
            });
        });
    </script>
@endsection