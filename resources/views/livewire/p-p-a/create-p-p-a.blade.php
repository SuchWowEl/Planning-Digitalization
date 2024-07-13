<div class="min-h-[calc(100vh-4rem)] w-full bg-sky-200 lg:px-32 px-3 pt-4">
    <form class="gap-4 flex flex-col" x-data="{ ppa_stat: '', sof: [] }">
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
        <!-- to share component state -->
        <div class="gap-4 flex flex-col"
        x-data="{
            comp: {0: {'name': '', 'component': [{}]}},
            // Object.keys(comp).length;
            comp_last_index: 0,
            oe_var: {},
            add_comp() {
                // this.comp.push({'name': '', 'component': [{}]});
                this.comp_last_index += 1;
                this.comp[this.comp_last_index] = {'name': '', 'component': [{}]};
            },
            add_output(index) {
                this.comp[index]['component'].push({});
            },
            remove_output(index, i) {
                this.comp[index]['component'].splice(i, 1);
            },
            remove_comp(index) {
                // this.comp.splice(index, 1);
                delete this.comp[index];
                console.log(this.comp);
            },
            set_price(comp_index, total_index, price){
                this.comp[comp_index]['component'][total_index][price] = 0;
            },
            row_total(comp_index, total_index) {
                console.log(comp_index + ' '+ total_index);
                let ps = this.comp[comp_index]['component'][total_index]['ps'];
                let mooe = this.comp[comp_index]['component'][total_index]['mooe'];
                let co = this.comp[comp_index]['component'][total_index]['co'];
                if ( ps == '') { ps = this.comp[comp_index]['component'][total_index]['ps'] = 0; }
                else if ( /^0/.test(ps) ) { this.comp[comp_index]['component'][total_index]['ps'] = parseFloat(ps); }
                if ( mooe == '') { mooe = this.comp[comp_index]['component'][total_index]['mooe'] = 0; }
                else if ( /^0/.test(mooe) ) { this.comp[comp_index]['component'][total_index]['mooe'] = parseFloat(mooe); }
                if ( co == '') { co = this.comp[comp_index]['component'][total_index]['co'] = 0; }
                else if ( /^0/.test(co) ) { this.comp[comp_index]['component'][total_index]['co'] = parseFloat(co); }
                console.log(ps + ' '+ mooe + ' ' + co);
                this.comp[comp_index]['component'][total_index]['total'] = parseFloat(ps) + parseFloat(mooe) + parseFloat(co);
            },
            isEmptyObject(obj) {
                return Object.keys(obj).length === 0 && obj.constructor === Object;
            },
            oe_push(comp_index, oe_index, text) {
                // oe_var: {},
                console.log(text.value);
                if (text.value != ''){
                    if (typeof this.oe_var[text.value] == 'undefined' || this.isEmptyObject(this.oe_var[text.value])) {
                        console.log('ok1');
                        this.oe_var[text.value] = [];
                        console.log('ok2');
                    }
                    console.log('ok3');
                    this.oe_var[text.value].push([comp_index, oe_index]);
                }
            },
        }">
            @include('component_section')
            @include('extras_section')
        </div>
    </form>
</div>
