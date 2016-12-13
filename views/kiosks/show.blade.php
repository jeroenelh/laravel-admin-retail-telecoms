@extends('laravel-admin-base-standalone::layouts.model.normal')

@section('page-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        Kiosk eigenschappen
                    </h2>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Kiosk naam</label><br />
                            {{ $kiosk->display_title }}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Laatste activiteit</label><br />
                            {{ \Carbon\Carbon::parse($kiosk->last_connection)->format('d-m-Y H:i:s') }}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Activatie code</label><br />
                            <button class="btn btn-xs btn-default get_activation_key">Opnieuw aanvragen</button>
                            <div class="activation_key lead no-margin" style="display: none;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Browser</label><br />
                            {{ $kiosk->browser_full }}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Besturingssysteem</label><br />
                            {{ $kiosk->os_full }}
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Apparaat</label><br />
                            {{ $kiosk->device }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Browser verversen</label><br />
                            <button class="btn btn-xs btn-default refresh_browser">Browser verversen aanvragen</button>
                            <div class="refresh_browser_result" style="display: none;">Verzoek ingediend</div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Afbeeldingen verversen</label><br />
                            <button class="btn btn-xs btn-default refresh_images">Afbeeldingen verversen aanvragen</button>
                            <div class="refresh_images_result" style="display: none;">Verzoek ingediend</div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Data verversen</label><br />
                            <button class="btn btn-xs btn-default refresh_accessories">Dataverversen aanvragen</button>
                            <div class="refresh_accessories_result" style="display: none;">Verzoek ingediend</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h2 class="box-title">
                        Statistieken
                    </h2>
                </div>
                <div class="box-body">
                    <div class="lead">Interacties (afgelopen 7 dagen)</div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gestart op</th>
                            <th>Geëindigd op</th>
                            <th>Aantal seconden</th>
                            <th>Aantal acties</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($kiosk->interactions()->where('created_at', '>=', date('Y-m-d', time()-7*24*60))->orderBy('created_at', 'desc')->get() as $interaction)
                            <tr>
                                <td>{{ $interaction->id }}</td>
                                <td>{{ $interaction->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $interaction->actions->last()->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $interaction->actions->last()->created_at->getTimestamp() - $interaction->created_at->getTimestamp() }}</td>
                                <td>{{ count($interaction->actions) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts_footer')
    @parent
    <script>
        $(document).ready(function(){
            $('.get_activation_key').click(function() {
                $this = $(this);
                $.post( "/admin/ajax/kiosk/ajax_get_activation_key",
                        {
                            kiosk_id: {{ $kiosk->id }}
                        }, function(data) {
                            $this.hide().parent().find('.activation_key').html(data).show();
                        }
                ).fail(function(){
                    alert("Error bij het ophalen van de activatie code");
                });
            });

            $('.refresh_browser').click(function() {
                $this = $(this);
                $.post( "/admin/ajax/kiosk/ajax_refresh",
                        {
                            kiosk_id: {{ $kiosk->id }},
                            category: 'browser',
                        }, function(data) {
                            $this.hide().parent().find('.refresh_browser_result').show();
                        }
                ).fail(function(){
                    alert("Error bij dit verzoek");
                });
            });

            $('.refresh_images').click(function() {
                $this = $(this);
                $.post( "/admin/ajax/kiosk/ajax_refresh",
                        {
                            kiosk_id: {{ $kiosk->id }},
                            category: 'images',
                        }, function(data) {
                            $this.hide().parent().find('.refresh_images_result').show();
                        }
                ).fail(function(){
                    alert("Error bij dit verzoek");
                });
            });

            $('.refresh_accessories').click(function() {
                $this = $(this);
                $.post( "/admin/ajax/kiosk/ajax_refresh",
                        {
                            kiosk_id: {{ $kiosk->id }},
                            category: 'accessories',
                        }, function(data) {
                            $this.hide().parent().find('.refresh_accessories_result').show();
                        }
                ).fail(function(){
                    alert("Error bij dit verzoek");
                });
            });
        });
    </script>
@endsection