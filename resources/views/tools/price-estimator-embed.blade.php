@extends('layouts.embed')

@section('content')
<div class="max-w-2xl mx-auto">
    @include('tools.partials.price-estimator-widget', ['config' => $config, 'showEmbedLink' => true, 'priceHistoryLast10' => $priceHistoryLast10 ?? collect()])
</div>
@endsection
