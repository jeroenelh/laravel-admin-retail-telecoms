@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                {{ Form::open(['class' => 'form-horizontal']) }}
                <div class="box-header with-border">
                    <h2 class="box-title">
                        Prijs configuratie aanpassen
                    </h2>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Naam prijs configuratie
                        </label>
                        <div class="col-sm-8">
                            {{ Form::text('display_title', $configurator->display_title, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <hr />


                    <div class="rules">


                    </div>
                    <div class="row">
                        <div class="col-sm-2 pull-right">
                            <button class="btn btn-success add-rule" type="button" style="width: 100%;">
                                <i class="fa fa-plus"></i> &nbsp; Add rule
                            </button>
                        </div>
                    </div>
                    <div class="empty_rule" style="display:none;">
                        <div class="form-group rule">
                            <label class="col-sm-4">
                                <div class="row">
                                    <div class=" col-sm-6 field_container">
                                        {{ Form::select('filter_field[]', [
                                                null => '' ,
                                                'display_title' => 'Titel',
                                                'description' => 'Omschrijving',
                                                'accessory_category_id' => 'Accessoire categorie',
                                            ], null, ['class' => 'form-control field']) }}
                                    </div>
                                    <div class=" col-sm-6 operation_container">
                                        {{ Form::select('filter_operation[]', [0 => ''], null, ['class' => 'form-control operation']) }}
                                    </div>
                                </div>
                            </label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-11 value_container">
                                        {{ Form::text('filter_value[]', '', ['class' => 'form-control']) }}
                                    </div>
                                    <div class="col-sm-1">
                                        <button class="btn btn-danger remove-rule" type="button" style="width: 100%;">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="operations" style="display: none;">
                        <div class="operation_">
                            {{ Form::select('filter_operation[]', [], null, ['class' => 'form-control operation']) }}
                        </div>
                        <div class="operation_display_title">
                            {{ Form::select('filter_operation[]', [2 => 'Bevat', 1=> 'Is gelijk aan'], null, ['class' => 'form-control operation']) }}
                        </div>
                        <div class="operation_description">
                            {{ Form::select('filter_operation[]', [2 => 'Bevat'], null, ['class' => 'form-control operation']) }}
                        </div>
                        <div class="operation_accessory_category_id">
                            {{ Form::select('filter_operation[]', [1 => 'Is'], null, ['class' => 'form-control operation']) }}
                        </div>
                    </div>

                    <div class="values" style="display: none;">
                        <div class="value_">
                            {{ Form::text('filter_value[]', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="value_display_title">
                            {{ Form::text('filter_value[]', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="value_description">
                            {{ Form::text('filter_value[]', '', ['class' => 'form-control']) }}
                        </div>
                        <div class="value_accessory_category_id">
                            {{ Form::select('filter_value[]', $categories, null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    {{--<div class="form-group">
                        <label class="col-sm-4">
                            <div class="row">
                                <div class=" col-sm-6">
                                    {{ Form::select('filter_field[]', ['' ,'Titel', 'Omschrijving'], null, ['class' => 'form-control']) }}
                                </div>
                                <div class=" col-sm-6">
                                    {{ Form::select('filter_field[]', ['Bevat', 'Is gelijk aan'], null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-10">
                                    {{ Form::text('name', 'Flip', ['class' => 'form-control']) }}<br />
                                    {{ Form::text('name', 'Flip', ['class' => 'form-control']) }}<br />
                                    {{ Form::text('name', 'Flip', ['class' => 'form-control']) }}
                                </div>
                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-success" type="button" style="width: 100%;">
                                        <i class="fa fa-plus"></i> &nbsp; Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>--}}

                    {{--<div class="form-group">
                        <label class="col-sm-4">
                            <div class="row">
                                <div class=" col-sm-6">
                                    {{ Form::select('filter_field[]', ['' ,'Titel', 'Omschrijving', 'Categorie'], null, ['class' => 'form-control']) }}
                                </div>
                                <div class=" col-sm-6">
                                    {{ Form::select('filter_field[]', ['Bevat', 'Is gelijk aan'], null, ['class' => 'form-control']) }}
                                </div>
                            </div>
                        </label>
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-10">
                                    {{ Form::select('name', ['Hoesjes'], null, ['class' => 'form-control']) }}
                                </div>
                                <div class="col-sm-2 text-right">
                                    <button class="btn btn-success" type="button" style="width: 100%;">
                                        <i class="fa fa-plus"></i> &nbsp; Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>--}}

                    <hr />

                    <div class="form-group">
                        <label class="col-sm-4 control-label">
                            Prijs
                        </label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">&euro;</span>
                                {{ Form::text('price', $configurator->price, ['class' => 'form-control currency']) }}
                            </div>
                        </div>
                    </div>


                </div>

                <div class="box-footer">
                    <button class="btn btn-primary pull-right" type="submit">Opslaan</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@section('scripts_footer')
    @parent
    <script>
        $(document).ready(function(){
            var active_rules = $('.rules .rule');
            var empty_rule = $('.empty_rule > div');

            // No rules? copy new rule
            if (active_rules.length == 0) {
                copy_new_rule();
            }

            $(document).delegate(".rule .field", 'change', function() {
                var $rule = $(this).parents(".rule");
                var $operation_container = $rule.find('.operation_container');
                var $value_container = $rule.find('.value_container');

                // Empty container
                $operation_container.html('');
                $(".operations .operation_" + $(this).val()).clone().appendTo($operation_container);
                $value_container.html('');
                $(".values .value_" + $(this).val()).clone().appendTo($value_container);

            });

            $(document).delegate('.add-rule', 'click', function() {
                copy_new_rule();
            });

            $(document).delegate('.remove-rule', 'click', function() {
                $(this).parents('.rule').remove();
            });


            function copy_new_rule() {
                empty_rule.clone().appendTo(".rules");
            }
        });
    </script>
@endsection