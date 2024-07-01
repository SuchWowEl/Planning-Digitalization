<div class="h-[calc(100vh-3rem)] w-full bg-sky-500 text-white lg:px-32">
    <div class="lg:h-full w-full flex flex-row flex-wrap">
        <div class="lg:h-full h-36 lg:w-3/5 w-full text-white lg:text-5xl text-xl flex flex-col items-center justify-center rounded">
            <div class="mb-5">
                Welcome!
            </div>
            <div class="text-center">
                What would you like to do today?
            </div>
        </div>
        <!-- min-h-[600px] -->
        <div class="lg:h-full h-[600px] lg:w-2/5 w-full flex flex-col justify-evenly px-5">
            <!-- h-36  -->
            @php
            $pair = [
                [url('ppa'), 'Add another PPA'],
                [url('aip'), 'View AIP'],
                [backpack_url(''), 'Admin Dashboard']
            ]
            @endphp

            @foreach ($pair as $entry)
            <a href="{{$entry[0]}}" class="lg:h-24 min-h-36 bg-white hover:bg-sky-400 lg:text-2xl text-lg text-sky-600 hover:text-white transition flex flex-col items-center justify-center
            rounded hover:border-white hover:border-2">
                {{$entry[1]}}
            </a>
            @endforeach
        </div>
    </div>
</div>
