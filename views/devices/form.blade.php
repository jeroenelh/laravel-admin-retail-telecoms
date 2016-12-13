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
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::device.display_title")</label>
                        {{ Form::text('display_title', $data['model']->display_title, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::device.slug")</label>
                        {{ Form::text('slug', $data['model']->slug, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::device.brand_id")</label>
                        <?php
                        $brand_select = [null => '== Kies merk =='];
                        $brands = \Microit\LaravelAdminRetailTelecoms\Models\Brand::currentStore()->orderBy('display_title')->get();
                        foreach ($brands as $brand) {
                            $brand_select[$brand->id] = $brand->display_title;
                        }
                        ?>
                        {{ Form::select('brand_id', $brand_select, $data['model']->brand_id, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::device.media")</label>
                        {{ Form::file('file', ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 form-group">
                        <label>@lang("laravel-admin-retail-telecoms::device.is_active")</label>
                        <div style="height: 34px;line-height: 34px;">
                            <label>
                                {{ Form::radio('is_active', true, ($data['model']->is_active == true)) }}
                                @lang("laravel-admin-retail-telecoms::device.is_active.yes")
                            </label> &nbsp;
                            <label>
                                {{ Form::radio('is_active', false, ($data['model']->is_active == false)) }}
                                @lang("laravel-admin-retail-telecoms::device.is_active.no")
                            </label>
                        </div>
                    </div>
                    @if($data['model']->medium)
                        <div class="col-md-6">
                            <label>@lang("laravel-admin-retail-telecoms::accessory.media")</label><br />
                            <img src="{{ '/images/uploads/device/'.$data['model']->medium->storage_name }}" />
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($data['model']->id)
            @include('laravel-admin-retail-telecoms::devices.repair')


            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h2 class="box-title">Accessoires</h2>
                    </div>
                    <div class="box-body">
                        <div class="row" style="padding-bottom: 20px;">
                            <div class="col-md-3">
                                <select class="form-control accessories_cat">
                                    <option value="selected">Gekoppelde accessoires</option>
                                    <option value="all">Alle accessoires</option>
                                    @foreach(\Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory::orderBy('display_title')->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->display_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control accessories_search" />
                            </div>
                            <div class="col-md-1">
                                <span class="form-control btn btn-default btn-flat search_accessories">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>
                        </div>

                        <div class="accessory_overview"></div>

                    </div>
                </div>
            </div>
        @endif
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

        function calculate_item_height()
        {
            var $itemheight = 0;

            $('.itemlist .item').css('height', 'auto');

            $('.itemlist .item').each(function(){
                if ($(this).height() > $itemheight) {
                    $itemheight = $(this).height();
                }
            });

            $('.itemlist .item').css('height', $itemheight + 'px');
        }

        @if($data['model']->id)
            /**
             * Accessories
             */
            var search_accessory_page = 1;
            $(".accessories_search").keypress(function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    $('.search_accessories').click();
                }
            });

            $(document).delegate('.accessories_links a', 'click keyup', function(e) {
                e.preventDefault();
                search_accessory_page = $(this).html();
                $('.search_accessories').click();
            });

            $(document).delegate('input.accessory_connect', 'change', function(e) {
                e.preventDefault();
                var checked = $(this).prop('checked') ? 'connect' : 'disconnect';
                var accessory_id = $(this).parents('.item').data('item-id');

                $.post( "/admin/ajax/device/ajax_connect_accessory",
                        {
                            device_id: {{ $data['model']->id }},
                            accessory_id: accessory_id,
                            connect: checked

                        }, function(data) {

                        }
                ).fail(function(){
                    alert("Error bij het opslaan");
                });
            });

            $('.search_accessories').on('click', function() {
                var height = $('.accessory_overview').height();
                $('.accessory_overview').height(height);
                $('.accessory_overview').html('Accessoires laden...');
                $.post( "/admin/ajax/device/ajax_get_accessories",
                        {
                            device_id: {{ $data['model']->id }},
                            accessories_cat: $('.accessories_cat').val(),
                            accessories_search: $('.accessories_search').val(),
                            page: search_accessory_page

                        }, function(data) {
                            $('.accessory_overview').html(data);
                            calculate_item_height();
                        }).fail(function(){
                    $('.accessory_overview').html('Error bij het laden van accessoires.');
                }).always(function(){
                    $('.accessory_overview').css('height', 'auto');
                });
            }).click();


            /**
             * Repair
             */
            $(document).delegate('.repair_list input', 'change', function() {
                var $item = $(this).parents('.item');

                $item.find('.save > div').show();
            });

            function recalculate_image_repair_cat() {
                $('.repair_list .item').each(function () {
                    var $item = $(this);
                    var $height = $item.find('.price').height();
                    $item.find('img').css('height', $height + 'px').show();
                });
            }
            recalculate_image_repair_cat();

            // update
            $(document).delegate('.repair_list .save_repair', 'click keyup', function() {
                var $item = $(this).parents('.item');
                var $repair = $item.data('repair');
                var $price = $item.find('.price input').val();
                var $duration = $item.find('.duration input').val();

                $.post( "/admin/ajax/device/ajax_add_repair",
                        {
                            device_id: {{ $data['model']->id }},
                            repair_id: $repair,
                            price: $price,
                            duration: $duration
                        }, function(data) {
                            $item.find('.save > div').hide(200);
                        }
                ).fail(function(){
                    alert("Error bij het opslaan");
                });
            });

            // delete
            $(document).delegate('.repair_list .delete_repair', 'click keyup', function() {
                var $item = $(this).parents('.item');
                var $repair = $item.data('repair');
                var $price = 0;
                var $duration = 0;

                $.post( "/admin/ajax/device/ajax_add_repair",
                        {
                            device_id: {{ $data['model']->id }},
                            repair_id: $repair,
                            price: $price,
                            duration: $duration
                        }, function(data) {
                            $item.hide(200);
                            setTimeout(function(){
                                $item.addClass('hidden').show();
                            }, 200);
                        }
                ).fail(function(){
                    alert("Error bij het opslaan");
                });
            });

            // add
            $(document).delegate('.add_repair_cat', 'click keyup', function(){
                var $repair_id = $('select.repair_cat_list').val();

                if ($repair_id == 'null') { return; }

                $('.repair_list .item').each(function(){
                    if ($(this).data('repair') == $repair_id) {
                        if ($(this).hasClass('hidden')) {
                            $(this).hide().removeClass('hidden').show(200);
                            recalculate_image_repair_cat();
                        }
                    }
                });
            });
        @endif
    </script>
@endsection