@php
use Carbon\Carbon;
@endphp

<div>
    <h2>{{ Carbon::parse($date)->format('l') }}</h2>

    <div class="text-center text-white "></div>
</div>
