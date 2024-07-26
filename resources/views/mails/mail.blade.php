{{-- 
    This file will work as an abstract layer to all other email template file and also can be used if we have a scenrio where we dont want to creaate any template file.
    In these scenrios pass the text or html in the $header, $body and $footer variables. 
--}}
@php
    /** To check if any custom layout is passed if not then assigning the default layout for mail*/
    if (!isset($parentTemplate)) 
    {
        $parentTemplate = 'mails.layout';
    }
@endphp
<!-- This file will help you when you want to override only one section of mail either body of header and footer  -->

{{-- To extend any custom parent layout  --}}
@extends($parentTemplate)


{{-- Header will be replace with the current values in $header if passed   --}}
@isset($header)
    @section('header')
        {!! $header !!}
    @endsection
@endisset

{{-- Body will be replace with the current values in $body if passed   --}}
@isset($body)
    @section('body')
        {!! $body !!}
    @endsection
@endisset


{{-- Footer will be replace with the current values in $footer if passed   --}}
@isset($footer)
    @section('footer')
        {!! $footer !!}
    @endsection
@endisset
