@extends('layouts.staff')

@section('content')
    @include('components.claim-ticket', [
        'delivery' => $delivery,
        'backUrl' => url('/staff/delivery-details/' . $delivery->id),
    ])
@endsection

@if(request('print') == 1)
    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
@endif
