    <form class="gap-4 flex flex-col" x-data="{ ppa_stat: ''}" x-init="$watch('ppa_stat',
            value => ( (value == 'routine') ?
                        (   $refs.impl1.disabled=true,
                            $refs.impl2.disabled=true
                        ) :
                        (   $refs.impl1.disabled=false,
                            $refs.impl2.disabled=false
                        ) )
        )">
<div class="min-h-[calc(100vh-4rem)] w-full bg-sky-200 lg:px-32 px-3 pt-4">
        <div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
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
                <label for="status" class="mr-5">PPA Status:</label>
                @foreach ($ppa_statuses as $entry => $value)
                <div class="flex align-middle">
                    <input type="radio" id="{{ $entry }}" name="ppa_status" value="{{ $entry }}" x-model="ppa_stat" required>
                    <label for="{{ $entry }}" class="ml-1 mr-3 align-middle">{{ $value }}</label><br>
                </div>
                @endforeach
                </div>

                <div>
                    <label for="year">Year:</label>
                    <input :value="new Date().getFullYear()" type="number" id="year" name="year" class="border-b-2 w-20 text-center outline-none focus:border-gray-300 transition"></input>
                </div>

            </div>
            <div class="md:grid md:grid-cols-2 gap-3 mt-4" x-data="{ chosen_sector: 'Social Development' }">
                @foreach ($ppa_info as $entry => $value)

                    @if ($loop->index == 3 or $loop->index == 0) <!-- checks if 1st or 4th element -->
                    <div class="col-span-1 grid grid-cols-3 gap-1 md:mt-0 mt-1">
                    @endif

                    <label for="{{ $entry }}" class="col-span-1">{{ $value }}:</label>
                    @if ($entry == 'sector')
                        <select id="{{ $entry }}" name="{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md" x-model="chosen_sector" >
                            @foreach ($sector_map as $sector => $subsector)
                            <option value="{{ $sector }}" {{ $loop->first ? 'selected' : ''}} >{{ $sector }}</option>
                            @endforeach
                        </select>
                    @elseif ($entry == 'subsector')
                        <select id="{{ $entry }}" name="{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md">
                            <template x-for="sector in $wire.sector_map[chosen_sector]">
                                <option x-text="sector"></option>
                            </template>
                        </select>
                    @else
                        <input type="text" id="{{ $entry }}" name="{{ $value }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                    @endif

                    @if ($loop->index == 2 or $loop->last) <!-- checks if 3rd or last element -->
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @include('details_section')
            @php
                $details_string = [
                    "title" => "PPA Name/Title",
                    "location" => "PPA Location",
                    "justification" => "Justification of the PPA",
                    "objective" => "PPA Objective",
                    "desc" => "Brief Description",
                ];
            @endphp

            @foreach ($details_string as $name => $title)
                <label for="{{ $name }}" class="w-full">{{$loop->iteration}}. {{ $title }}:</label>
                <input type="text" id="{{ $name }}" name="{{ $name }}" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
            @endforeach

            <label class="w-full">6. Intended Beneficiaries by Type:</label>
            <div class="grid grid-cols-12 gap-1 mb-6" x-data="{ btotal: 0, add_mf() {
                let male = $refs.male.value;
                let female = $refs.female.value;
                if ( male == '') { male = 0; }
                else if ( /^0/.test(male) ) { $refs.male.value = male = parseInt(male); }
                if (female == '') { female = 0; }
                else if ( /^0/.test(female) ) { $refs.female.value = female = parseInt(female); }
                this.btotal = parseInt(male) + parseInt(female);
            }}">
                <div class="md:col-span-7 col-span-6">
                    <label for="type" class="w-full">Type:</label>
                    <input type="text" id="type" name="type" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                </div>
                @php $genders = ["male", "female"]; @endphp
                @foreach ($genders as $g)
                    <div class="col-span-2">
                        <label for="{{ $g }}" class="w-full">{{ ucfirst($g) }}:</label>
                        <input @blur="add_mf()" placeholder="0" type="number" id="{{ $g }}" x-ref="{{ $g }}" name="{{ $g }}" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                    </div>
                @endforeach
                <div class="md:col-span-1 col-span-2">
                    <label class="w-full">Total:</label>
                    <div name="total" class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block align-text-bottom" x-text="btotal"></div>
                </div>
            </div>

            <label class="w-full">7. Implementation Period</label>
            <div class="grid grid-cols-6 gap-1 mb-6">
                <div class="md:col-start-2 md:col-span-2 col-span-3">
                    <label for="start">Start:</label>
                    <input x-ref="impl1" disabled=true type="date" id="start" name="start" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                </div>
                <div class="md:col-start-4 md:col-span-2 col-span-3">
                    <label for="end">End:</label>
                    <input x-ref="impl2" disabled=true type="date" id="end" name="end" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                </div>
            </div>

            <label class="w-full">8. Major Outputs or Success Indicators</label>
            <div class="grid grid-cols-6 gap-1 mb-6">
                <div class="md:col-start-2 md:col-span-4 col-span-full" x-data="{
                    ind: [''],
                    add_row() {
                        this.ind.push('');
                    },
                    remove_row(index) {
                        this.ind.splice(index, 1);
                        console.log(this.ind);
                    }
                }">
                    <div class="flex flex-row w-full justify-between mb-2 items-center">
                        <label>Performance measure & target:</label>
                        <button @click="add_row()" class="border-2 w-8 rounded-md h-9" type="button">
                            +
                        </button>
                    </div>
                    <template x-for="(field, index) in ind" :key="index">
                        <div class="flex flex-row w-full mb-2 gap-1">
                             <input type="text" x-model="ind[index]" name="goal[]" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                             <button x-show="ind.length > 1" @click="remove_row(index)" class="bg-red-500 w-8 rounded-md text-white" type="button">
                                x
                             </button>
                        </div>
                    </template>
                </div>
            </div>

            <label class="w-full">9. Source of Fund</label>
            <div class="grid grid-cols-7 gap-1 mb-6 px-8 pt-2">
                <div class="lg:col-span-3 lg:grid lg:grid-cols-12 col-span-full flex flex-col w-full">
                    <div class="gap-1 col-span-full">
                        <input id="gfp" type="checkbox" name="fund[]"></input>
                        <label for="gfp">General Fund Proper</label>
                    </div>
                    @php
                        $fund_gfp = [
                            "reg" => 'Regular',
                            'df' => '20% Development Fund',
                            'drrm' => 'DRRM Fund',
                            'ee' => 'Economic Enterprise',
                            'hospt' => 'Hospital',
                            'market' => 'Market',
                            'sl' => 'Slaughterhouse',
                            'term' => 'Terminal'
                        ];
                    @endphp
                    <div class="grid grid-cols-12 col-span-full justify-between">
                        <div class="gap-1 col-start-2 lg:col-start-3 col-span-4">
                            <input id="gf" type="checkbox" name="fund[]"></input>
                            <label for="gf">GF - Proper</label>
                        </div>
                        <div class="lg:col-start-2 col-end-13 col-span-7">
                            @foreach ($fund_gfp as $name => $check_box)
                                @if (in_array($loop->iteration, [1,($loop->count/2+1)]))
                                    <div class="lg:col-start-2 col-span-full">
                                @endif
                                <div class="gap-1 lg:col-start-2 lg:col-span-8 col-span-full">
                                    <input id="{{ $name }}" type="checkbox" name="fund[]" value="{{ $name }}"></input>
                                    <label for="{{ $name }}">{{ $check_box }}</label>
                                </div>
                                @if (in_array($loop->iteration, [($loop->count/2),$loop->count]))
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-2 grid grid-cols-12 col-span-full">
                    <div class="gap-1 col-span-full h-min">
                        <input id="gfp" type="checkbox" name="fund[]"></input>
                        <label for="gfp">Trust Fund</label>
                    </div>
                    @php
                        $fund_tf = [
                            'fa' => 'Financial Assistance',
                            'ang' => 'Aid from National Government',
                            'o_lgu' => 'Financial Assistance from other LGUs',
                            'grants' => 'Grants from outside sources',
                            'lgu_ft' => 'LGU Fund Transfer',
                            'o_receipts' => 'Other Receipts'
                        ];
                    @endphp
                    <div class="col-start-2 col-span-full">
                        @foreach ($fund_tf as $name => $check_box)
                            <div class="gap-1">
                                <input id="{{ $name }}" type="checkbox" name="fund[]" value="{{ $name }}"></input>
                                <label for="{{ $name }}">{{ $check_box }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="lg:col-span-2 col-span-full">
                    <div class="gap-1">
                        <input id="sef" type="checkbox" name="fund[]"></input>
                        <label for="sef">Special Education Fund</label>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
