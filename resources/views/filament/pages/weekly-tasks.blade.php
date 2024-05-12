@php
use Carbon\Carbon;
@endphp

<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            This week
        </x-slot>

        <table class="table table-fixed w-full text-center">
            <thead>
            <tr>
                <th>Type</th>
                @for($date = $startOfWeek; $date->lte($endOfWeek); $date->addDay())
                    <th>{{ $date->format('l') }}</th>
                @endfor
            </tr>
            </thead>
            <tbody>
            @foreach($tasksByTypeAndDate as $typeId => $typeData)
                <tr>
                    <td>{{ $typeData['type_name'] }}</td>
                    @foreach($typeData['days'] as $date => $wordCount)
                        <td>{{ $wordCount }} <br> {{ ceil($wordCount*$averageTimePerWordByType[$typeId]) }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>


{{--        <div class="grid grid-cols-7 gap-4">--}}
{{--            @foreach( $weekDates as $date)--}}
{{--                <div>--}}
{{--                    <livewire:DailySummary :date="$date" />--}}
{{--                </div>--}}


{{--            @endforeach--}}
{{--        </div>--}}

{{--        <table class="table-fixed w-full text-center">--}}
{{--            <thead>--}}
{{--            <tr>--}}
{{--                <th class="w-2/6">Type</th>--}}
{{--                <th class="w-1/6">Mon</th>--}}
{{--                <th class="w-1/6">Tue</th>--}}
{{--                <th class="w-1/6">Wed</th>--}}
{{--                <th class="w-1/6">Thu</th>--}}
{{--                <th class="w-1/6">Fri</th>--}}
{{--                <th class="w-1/6">End</th>--}}
{{--            </tr>--}}
{{--            </thead>--}}
{{--            <tbody>--}}
{{--            <tr>--}}
{{--                <th>Translations</th>--}}
{{--                <td>1.500</td>--}}
{{--                <td>2.500</td>--}}
{{--                <td>856</td>--}}
{{--                <td>956</td>--}}
{{--                <td>2.658</td>--}}
{{--                <td >5.546</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Proofreading</th>--}}
{{--                <td>1.500</td>--}}
{{--                <td>2.500</td>--}}
{{--                <td>856</td>--}}
{{--                <td>956</td>--}}
{{--                <td>2.658</td>--}}
{{--                <td >5.546</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>LSO</th>--}}
{{--                <td>1.500</td>--}}
{{--                <td>2.500</td>--}}
{{--                <td>856</td>--}}
{{--                <td>956</td>--}}
{{--                <td>2.658</td>--}}
{{--                <td >5.546</td>--}}
{{--            </tr>--}}
{{--            <tr>--}}
{{--                <th>Time</th>--}}
{{--                <td>2h</td>--}}
{{--                <td>3.5h</td>--}}
{{--                <td>4h</td>--}}
{{--                <td>1h</td>--}}
{{--                <td>30m</td>--}}
{{--                <td>6h</td>--}}
{{--            </tbody>--}}
{{--        </table>--}}

{{--        Timer is {{ $timer }}--}}
{{--        <div x-data="dropdown">--}}
{{--            <button @click="toggle">Toggle Content</button>--}}
{{--            <button @click="$wire.setTimer(seconds)">Stop Timer</button>--}}

{{--            <div x-show="open">--}}
{{--                Content...--}}
{{--                <div x-text="seconds"></div>--}}
{{--            </div>--}}
{{--        </div>--}}

    </x-filament::section>
</x-filament-panels::page>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropdown', () => ({
            running: true,
            seconds: 0,
            minutes: 0,
            hours: 0,

            toggle() {
                if (this.running) {
                    this.interval = setInterval(() => {
                        this.seconds++;
                    }, 1000);
                } else {
                    clearInterval(this.interval);
                    console.log('Stop');
                    this.$wire.time = this.seconds;
                }
                this.running = !this.running
                console.log ("Toggle" + this.running)

            },
        }))
    })
</script>
