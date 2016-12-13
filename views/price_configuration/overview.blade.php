@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        Prijs configuraties
                    </h2>
                </div>
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @foreach($priceConfigurators as $configurator)
                            <li class="item">
                                <div class="row">
                                    <div class="lead pull-left col-sm-8">
                                        {{ $configurator->display_title }}
                                    </div>
                                    <div class="col-sm-4 text-right">
                                        <a href="{{ route('admin.telecoms.price_configuration.edit', [$configurator->id]) }}" class="btn btn-primary" type="button">
                                            <i class="fa fa-pencil"></i> &nbsp; Edit
                                        </a>
                                        <a href="{{ route('admin.telecoms.price_configuration.run', [$configurator->id]) }}" class="btn btn-success" type="button">
                                            <i class="fa fa-play"></i> &nbsp; Run
                                        </a>
                                    </div>
                                </div>

                                <strong>Filters</strong><br />
                                @foreach($configurator->rules as $rule)
                                    <?php
                                    switch($rule->field) {
                                        case 'accessory_category_id':
                                            echo 'Categorie ';
                                            break;
                                        case 'display_title':
                                            echo 'Titel ';
                                            break;
                                    }

                                    switch($rule->operation) {
                                        case '=':
                                            echo 'is gelijk aan ';
                                            break;
                                        case 'LIKE':
                                            echo 'bevat ';
                                            break;
                                    }

                                    switch($rule->field) {
                                        case 'accessory_category_id':
                                            $category = \Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory::whereId($rule->value)->first();
                                            if (is_null($category)) {
                                                echo "<b>WAARDE NIET GEVONDEN!</b>";
                                            } else {
                                                echo '"'.$category->display_title.'"';
                                            }
                                            break;
                                        case 'display_title':
                                            $value = explode(";", $rule->value);
                                            if (count($value) > 1) {
                                                $last = end($value);
                                                unset($value[count($value)-1]);
                                                echo '"'.join('", "', $value).'" of "'.$last.'"';
                                            } else {
                                                echo $value[0];
                                            }
                                    }
                                    ?>.<br />
                                @endforeach

                                <div class="h4">
                                    Prijs: <strong>&euro; {{ $configurator->price }}</strong>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_footer')
    @parent
@endsection