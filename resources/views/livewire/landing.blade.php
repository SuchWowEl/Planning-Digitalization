<div class="h-[calc(100vh-4rem)] w-full bg-sky-500 text-white lg:px-32 sm:px-24">
    <div class="md:h-full w-full flex flex-row flex-wrap">
        <div class="md:h-full h-36 md:w-3/5 w-full text-white md:text-5xl text-3xl flex flex-col items-center justify-center rounded">
            <div class="mb-5">
                Welcome!
            </div>
            <div class="text-center">
                What would you like to do today?
            </div>
        </div>
        <!-- min-h-[600px] -->
        <div class="md:h-full h-[600px] md:w-2/5 w-full flex flex-col justify-evenly px-5">
            <!-- h-36  -->
            @php
            $pair = [
                [url('ppa'), 'Add PPA'],
                [url('aip'), 'View AIP'],
                [backpack_url(''), 'Admin Dashboard']
            ]
            @endphp

            @foreach ($pair as $entry)
            <a href="{{$entry[0]}}" class="md:h-24 min-h-36 bg-white hover:bg-green-600 md:text-2xl text-3xl text-sky-600 hover:text-white transition flex flex-col items-center justify-center
            rounded border-2 hover:border-white text-center">
                {{$entry[1]}}
            </a>
            @endforeach
        </div>
    </div>
</div>
