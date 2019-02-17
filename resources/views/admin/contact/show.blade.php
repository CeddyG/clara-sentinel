@extends('admin/dashboard')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <br>
            <div class="box box-info">	
                <div class="box-header with-border">
                    <h3 class="box-title">Message</h3>
                </div>
                <div class="box-body">
                    <strong>{{ __('clara-contact::contact.mail') }}</strong><br />
                    <span>{{ $oItem->mail_contact }}</span>
                    <hr>
                    
                    <strong>{{ __('clara-contact::contact.fk_contact_category') }}</strong><br />
                    <span>{{ $oItem->contact_category->name_contact_category }}</span>
                    <hr>
                    
                    <strong>{{ __('clara-contact::contact.subject_contact') }}</strong><br />
                    <span>{{ $oItem->subject_contact }}</span>
                    <hr>
                    
                    <strong>{{ __('clara-contact::contact.text_contact') }}</strong>
                    <p>{{ $oItem->text_contact }}</p>
                </div>
            </div>
            <a href="javascript:history.back()" class="btn btn-primary">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
            </a>
        </div>
    </div>
@stop