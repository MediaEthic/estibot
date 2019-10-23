@component('mail::message')
{{--# Votre demande de devis--}}

{{--@component('mail::button', ['url' => ''])--}}
{{--    Voir mon devis--}}
{{--@endcomponent--}}


@component('mail::panel', ['url' => ''])
    {{ $quotation->body_email }}
@endcomponent


@endcomponent
