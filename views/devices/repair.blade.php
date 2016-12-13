<div class="col-md-12">
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title">Reparaties</h2>
        </div>
        <div class="box-body">
            <ul class="products-list product-list-in-box repair_list">
                @foreach(\Microit\LaravelAdminRetailTelecoms\Models\RepairCategory::orderBy('display_title')->with('medium')->get() as $repairCat)
                    <?php
                    $price = 0;
                    $duration = 0;
                    $class = 'hidden';
                    if ($data['model']->repairs->contains($repairCat)) {
                        $class = '';
                        $price = $data['model']->repairs()->find($repairCat->id)->pivot->price;
                        $duration = $data['model']->repairs()->find($repairCat->id)->pivot->duration;
                    }
                    ?>

                    <li class="item {{ $class }}" data-repair="{{ $repairCat->id }}">
                        <h4>{{ $repairCat->display_title }}</h4>
                        <div class="row">
                            <div class="col-md-1 text-center">
                                <img src="{{ $repairCat->medium->getUrl() }}" style="display: none;" />
                            </div>
                            <div class="col-md-2 price">
                                <label>Prijs</label>
                                <div class="input-group">
                                    <span class="input-group-addon">&euro;</span>
                                    <input type="number" value="{{ $price }}" step="0.01" min="0" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-2 duration">
                                <label>Werktijd</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                    <input type="number" value="{{ $duration }}" class="form-control" />
                                    <span class="input-group-addon">min</span>
                                </div>
                            </div>
                            <div class="col-md-2 save">
                                <div style="display:none;">
                                    <label>&nbsp;</label>
                                    <span class="btn btn-primary btn-flat form-control save_repair">Reparatie opslaan</span>
                                </div>
                            </div>
                            <div class="col-md-1 delete">
                                <label>&nbsp;</label>
                                        <span class="btn btn-danger btn-flat form-control delete_repair">
                                            <i class="fa fa-trash"></i>
                                        </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="row" style="padding-top: 20px;">
                <div class="col-md-4">
                    <div class="lead">Categorie toevoegen</div>
                    <select class="form-control repair_cat_list">
                        <option value="null">Reparatie categorieën</option>
                        @foreach(\Microit\LaravelAdminRetailTelecoms\Models\RepairCategory::orderBy('display_title')->get() as $repairCat)
                            <option value="{{ $repairCat->id }}">
                                {{ $repairCat->display_title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="lead">&nbsp;</div>
                    <span class="btn btn-primary btn-flat add_repair_cat">Toevoegen</span>
                </div>
            </div>
        </div>
    </div>
</div>