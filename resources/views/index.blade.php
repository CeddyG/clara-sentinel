@extends('abricot/layout')

@section('CSS')
    <!-- Select 2 -->
    {!! Html::style('bower_components/select2/dist/css/select2.min.css') !!}
    
    <style>
        .select2-container--default .select2-selection--single, 
        .select2-selection .select2-selection--single 
        {
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0;
            padding: 10px 5px;
            height: 50px;
        }
        
        .select2-container--default .select2-selection--single:active, 
        .select2-selection .select2-selection--single:active
        {
            outline: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            border-color: #00653F;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow 
        {
            height: 50px;
            right: 5px;
        }
        
        textarea
        {
            resize: none;
        }
    </style>
@stop

@section('content')
    <div id="colorlib-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="heading-2">Nos coordonnées</h2>
                    <div class="row contact-info-wrap row-pb-lg">
                        <div class="col-md-3">
                            <p><span><i class="icon-location-2"></i></span> {{ param('adresse') }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><span><i class="icon-phone3"></i></span> <a href="tel://{{ param('telephone') }}">{{ param('telephone') }}</a></p>
                        </div>
                        <div class="col-md-3">
                            <p><span><i class="icon-paperplane"></i></span> <a href="mailto:{{ param('mail') }}">{{ param('mail') }}</a></p>
                        </div>
                        <div class="col-md-3">
                            <p><span><i class="icon-globe"></i></span> <a href="{{ param('site') }}">{{ param('site') }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="map" class="colorlib-map"></div>
                        </div>
                        <div class="col-md-6">
                            <h2 class="heading-2">Restons en contact</h2>
                            {!! BootForm::open()->action(route('contact.store'))->post() !!}
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        {!! BootForm::select(__('clara-contact::contact-category.contact_category'), 'fk_contact_category')
                                            ->class('select2 form-control')
                                            ->options($oCategories->pluck('name_contact_category', 'id_contact_category'))
                                        !!}
                                    </div>
                                </div>
                            
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        {!! BootForm::text(__('clara-contact::contact.subject_contact'), 'subject_contact')->placeHolder('Sujet de votre message') !!}
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        {!! BootForm::textarea(__('clara-contact::contact.text_contact'), 'text_contact')->placeHolder('Votre message') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Envoyer" class="btn btn-primary">
                                </div>
                            {!! BootForm::close() !!}
                        </div>

                        <div class="col-lg-7 col-md-12">
                            <!--The div element for the map -->
                            <div id="map"></div></div>
                        <script>
                            // Initialize and add the map
                            function initMap() {
                                var loc = {lat: 49.4730416, lng: 1.0933853};
                                // The map, centered at Uluru
                                var map = new google.maps.Map(
                                    document.getElementById('map'), {zoom: 16, center: loc});
                                // The marker, positioned at Uluru
                                var marker = new google.maps.Marker({position: loc, map: map});
                            }
                        </script>
                        <script async defer
                                src="https://maps.googleapis.com/maps/api/js?key={{ param('google-map') }}&callback=initMap">
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('JS')
    <!-- Select 2 -->
    {!! Html::script('bower_components/select2/dist/js/select2.full.min.js') !!}
    
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script> 

@stop