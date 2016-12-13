<?php

namespace Microit\LaravelAdminRetailTelecoms\Controllers;

use Microit\LaravelAdminRetailTelecoms\Models\Accessory;
use Microit\LaravelAdminRetailTelecoms\Models\AccessoryCategory;
use Microit\LaravelAdminRetailTelecoms\Models\PriceConfigurator;
use Microit\LaravelAdminRetailTelecoms\Models\PriceConfiguratorRule;
use Microit\LaravelAdminRetailTelecoms\Requests\PriceConfiguratorRequest;

/**
 * Handles Website create/update/delete actions
 *
 * @todo refactor this file/flow. The user experience doesn't feel good!
 * @package Microit\LaravelAdminCms
 */
class PriceConfigurationController extends Controller
{
    public function overview()
    {
        $priceConfigurators = PriceConfigurator::all();
        return view('laravel-admin-retail-telecoms::price_configuration.overview', ['priceConfigurators' => $priceConfigurators]);
    }

    public function edit($id)
    {
        $priceConfigurator = PriceConfigurator::whereId($id)->firstOrFail();
        $categories = [];
        foreach (AccessoryCategory::orderBy('display_title')->get() as $category) {
            $categories[$category->id] = $category->display_title;
        }

        return view('laravel-admin-retail-telecoms::price_configuration.edit', ['configurator' => $priceConfigurator, 'categories' => $categories]);
    }
    public function save(PriceConfiguratorRequest $request, $id)
    {
        $priceConfigurator = PriceConfigurator::whereId($id)->firstOrFail();

        $priceConfigurator->update([
            'display_title' => $request->display_title,
            'price' => $request->price,
        ]);

        // Rules
        foreach ($request->input('filter_field') as $index => $field) {
            if (trim($field) == "" || trim($request->input('filter_value')[$index]) == "") {
                continue;
            }
            $operation = $request->input('filter_operation')[$index];
            switch ($operation) {
                case 1:
                    $operation = '=';
                    break;
                case 2:
                    $operation = 'LIKE';
                    break;
            }

            $rule = PriceConfiguratorRule::create([
                'price_configurator_id' => $priceConfigurator->id,
                'field' => $field,
                'operation' => $operation,
                'value' => $request->input('filter_value')[$index],
            ]);

            //$priceConfigurator->rules()->attach($rule);
        }

        return redirect()->route('admin.telecoms.price_configuration');
    }

    public function run($id)
    {
        $priceConfigurator = PriceConfigurator::whereId($id)->firstOrFail();

        $accessories = Accessory::where('price', '!=', $priceConfigurator->price);

        foreach ($priceConfigurator->rules as $rule) {
            $accessories->where(function ($query) use ($rule) {
                $values = explode(";", $rule->value);
                foreach ($values as $value) {
                    switch ($rule->operation) {
                        case '=':
                            $query->orWhere($rule->field, '=', $value);
                            break;
                        case 'LIKE':
                            $query->orWhere($rule->field, 'LIKE', '%'.$value.'%');
                            break;
                        default:
                            throw new \Exception('Operation not found: ' . $rule->operation);
                    }
                }
            });
        }

        $accessories = $accessories->get();

        foreach ($accessories as $accessory) {
            $accessory->update([
                'price' => $priceConfigurator->price,
            ]);
        }

        return view('laravel-admin-retail-telecoms::price_configuration.run', ['accessories' => $accessories]);

    }
}