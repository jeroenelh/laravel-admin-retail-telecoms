@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        Prijs configuratie resultaat
                    </h2>
                </div>
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item" style="font-weight: bold;">
                            <div class="row">
                                <div class="col-sm-8">
                                    Accessoire naam
                                </div>
                                <div class="col-md-2">
                                    Oude prijs
                                </div>
                                <div class="col-md-2">
                                    Nieuwe prijs
                                </div>
                            </div>
                        </li>
                        @foreach($accessories as $accessory)
                        <li class="item">
                            <div class="row">
                                <div class="col-sm-8">
                                    {{ $accessory->display_title }}
                                </div>
                                <div class="col-md-2">
                                    &euro; {{ $accessory->getOriginal('price') }}
                                </div>
                                <div class="col-md-2">
                                    &euro; {{ $accessory->price }}
                                </div>
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