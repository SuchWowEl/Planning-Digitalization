<div class="min-h-[calc(100vh-4rem)] w-full bg-sky-500 lg:px-32 px-3 pt-4">
    <form class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg" x-data="{ ppa_stat: ''}" >
        <div class="flex flex-row flex-wrap w-full items-center justify-between">
            @php
            $ppa_statuses = [
                "new" => "New",
                "routine" => "Routine",
                "ongoing/for_completion" => "Ongoing/For completion"
            ];
            $ppa_info = [
                "sector" => "Sector",
                "subsector" => "Sub-sector",
                "proponent" => "Proponent/Originator",
                "mfo" => "MFO",
                "impl_office" => "Implementing Office",
                "aip_ref_code" => "AIP reference code",
            ];
            @endphp

            <div class="flex flex-row">
            <label for="status" class="mr-5">PPA status:</label>
            @foreach ($ppa_statuses as $entry => $value)
            <div>
                <input type="radio" id="{{ $entry }}" name="ppa_status" value="{{ $entry }}" x-model="ppa_stat" {!! $loop->first ? 'checked' : '' !!} required>
                <label for="{{ $entry }}" class="mr-3 align-middle">{{ $value }}</label><br>
            </div>
            @endforeach
            </div>

            <div>
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" class="border-b-2 w-20 text-center outline-none focus:border-gray-300 transition"></input>
            </div>

        </div>
        <div class="md:grid md:grid-cols-2 gap-3 mt-4" x-data="{ chosen_sector: 'Social Development' }">
            @foreach ($ppa_info as $entry => $value)

                @if ($loop->index == 3 or $loop->index == 0) <!-- checks if 1st or 4th element -->
                <div class="col-span-1 grid grid-cols-3 gap-1">
                @endif

                <label for="{{ $entry }}" class="col-span-1">{{ $value }}:</label>
                @if ($entry == 'sector')
                    <select id="{{ $entry }}" name="{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2" x-model="chosen_sector" >
                        @foreach ($sector_map as $sector => $subsector)
                        <option value="{{ $sector }}" {{ $loop->first ? 'selected' : ''}} >{{ $sector }}</option>
                        @endforeach
                    </select>
                @elseif ($entry == 'subsector')
                    <select id="{{ $entry }}" name="{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2">
                        <template x-for="sector in $wire.sector_map[chosen_sector]">
                            <option x-text="sector"></option>
                        </template>
                    </select>
                @else
                    <input type="text" id="{{ $entry }}" name="{{ $value }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2"></input>
                @endif

                @if ($loop->index == 2 or $loop->last) <!-- checks if 3rd or last element -->
                </div>
                @endif
            @endforeach
        </div>
    </form>
</div>
