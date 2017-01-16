@extends('hadron-frontend::layouts.store')

@section('stylesheets')
    @parent
    {!! Minify::stylesheet('/css/card.css') !!}
@stop

@section('store-content')

    @include('hadron-frontend::profile.tabs')

    <div class="tabs-content">
        <div role="tabpanel" class="tab-pane tab-active">

            <div class="raw-margin-top-24">
                <div class='card-wrapper'></div>
            </div>

            <div class="col-md-6 col-md-offset-3 raw-margin-top-24 well">
                <form id="userPayment" method="post" action="{{ URL::to('store/account/card-change') }}">
                    {!! Form::token(); !!}

                    @include('hadron-frontend::profile.card.card-form')

                    <div class="row text-right">
                        <div class="col-md-12">
                            <button class="btn btn-primary" type="submit">Change Credit Card</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@section('pre-javascript')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script> Stripe.setPublishableKey('{{ Config::get("services.stripe.key") }}'); </script>
@stop

@section('javascript')
    @parent
    {!! Minify::javascript('/js/card.js') !!}
    {!! Minify::javascript('/js/purchases.js') !!}
@stop