<div class="min-h-[calc(100vh-4rem)] w-full bg-sky-200 lg:px-32 px-3 pt-4">
    @if (session('status'))
        <div class="alert alert-success">
            {{ implode(',', array_keys(session('status'))) }}
            {{ implode(',', session('status')) }}
        </div>
    @endif
    <form class="gap-4 flex flex-col" method="post" wire:submit="save"
        onkeydown="if(event.keyCode === 13) {
            return false;
        }"
        @submit="
            if (ssof.length == 0){
                alert('Please choose a Source of Fund');
                event.preventDefault();
            }
        "
        x-data="{
            ppa_stat: '',
            sof: [],
            ssof: [],
        }"
    >
        @csrf
        <div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
            <div class="flex flex-row flex-wrap w-full items-center justify-between">
                @php
                $ppa_statuses = [
                    24 => "New",
                    25 => "Routine",
                    26 => "Ongoing/For completion"
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
                <label class="mr-5">PPA Status:</label>
                @foreach ($ppa_statuses as $entry => $value)
                <div class="flex align-middle">
                    <input type="radio" id="{{ $entry }}" wire:model="section1.status" value="{{ $entry }}" required>
                    <label for="{{ $entry }}" class="ml-1 mr-3 align-middle">{{ $value }}</label><br>
                </div>
                @endforeach
                </div>

                <div>
                    <label for="year">Year:</label>
                    <!-- TODO: should user choose what year to use prior viewing this form or let them choose as they see this form?
                    -->
                    <input wire:model="section1.aip_key" type="number" id="year" name="ppa[year]" class="border-b-2 w-20 text-center outline-none focus:border-gray-300 transition" disabled></input>
                </div>

            </div>
            <div class="md:grid md:grid-cols-2 gap-3 mt-4" x-data="{ chosen_sector: 1, chosen_subsector: 6 }"
            x-init="$watch('chosen_sector', () => chosen_subsector = $refs.subsector_select.value)"
            >
                @foreach ($ppa_info as $entry => $value)

                    @if ($loop->index == 3 or $loop->index == 0) <!-- checks if 1st or 4th element -->
                    <div class="col-span-1 grid grid-cols-3 gap-1 md:mt-0 mt-1">
                    @endif

                    <label for="{{ $entry }}" class="col-span-1">{{ $value }}:</label>
                    @if ($entry == 'sector')
                        <select id="{{ $entry }}" wire:model="section1.{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md" x-modelable="chosen_sector" x-model="$wire.section1.{{ $entry }}">
                            @foreach ($sector_map as $index => $sector)
                                <option value="{{ $index }}">{{ $sector }}</option>
                            @endforeach
                        </select>
                    @elseif ($entry == 'subsector')
                        <select id="{{ $entry }}" x-ref="subsector_select" wire:model="section1.{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md" x-modelable="chosen_subsector" x-model="$wire.section1.subsector">
                            <template x-for="(subsector, index) in $wire.subsector_map[chosen_sector]">
                                <option x-text="subsector" :value="index" :selected="$wire.section1.subsector == index" ></option>
                            </template>
                        </select>
                    @else
                        <input type="text" id="{{ $entry }}" wire:model="section1.{{ $entry }}" class="border-2 outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                    @endif

                    @if ($loop->index == 2 or $loop->last) <!-- checks if 3rd or last element -->
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @include('details_section')
        <!-- to share component state -->
        <!-- x-init="if (comp[0]['component'].length==0){comp[0]['component'].push({});console.log('awit');}" -->
        <div class="gap-4 flex flex-col"
        x-data="{
            comp: {{ json_encode($comp) }},
            // Object.keys(comp).length;
            comp_last_index: 1,
            oe_var: {},
            oe_query: '',
            oe_cords: {},
            comp_oe_onclick(array){
                // key, i
                // {this.oe_cords['key']: array[index], this.oe_cords['i']: array[i], this.oe_cords['id']: array[key]} = array;
                this.oe_cords['key'] = array[0];
                this.oe_cords['i'] = array[1];
                this.oe_cords['id'] = array[2];
                console.log(this.oe_cords);
                // this.oe_cords = el;
                this.modal_toggle();
            },
            modal_onclick(value, {comp}) {
                // oe_elem = document.getElementById(this.oe_cords);
                // oe_elem.value = oe_elem.innerText = value;
                comp[this.oe_cords['key']]['component'][this.oe_cords['i']][this.oe_cords['id']] = value;
                this.modal_toggle();
                console.log(comp);
            },
            modal_toggle() {
                let list = $refs.modal.classList;
                if (list.contains('hidden')){
                    $refs.modal.classList.remove('hidden');
                }
                else {
                    $refs.modal.classList.add('hidden');
                    this.oe_cords = {};
                }
            },
            add_comp() {
                // this.comp.push({'name': '', 'component': [{}]});
                this.comp_last_index += 1;
                this.comp[this.comp_last_index] = {'name': '', 'component': {0: {}}};
            },
            add_output(index) {
                let array = Object.keys(this.comp[index]['component']);
                value = Math.max.apply(null, array)+1;
                this.comp[index]['component'][value]= {};
            },
            remove_output(index, i) {
                // this.comp[index]['component'].splice(i, 1);
                delete this.comp[index]['component'][i];
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
            <div id="oe_acode_modal" x-ref="modal" class="fixed hidden z-10 left-0 top-0 bg-[rgba(0,0,0,0.4)] w-full h-full md:px-36 px-4 pt-4 flex flex-col justify-center text-center">
                <div class="flex flex-col justify-start text-center bg-slate-100 h-[305px] rounded-md shadow-lg relative gap-4">
                    <button type="button" @click="modal_toggle()">
                        <i class="fa-solid fa-x fa-xl absolute top-2 right-2 text-white p-2 rounded-md bg-red-500 outline-none transition hover:text-red-500 hover:bg-white hover:outline-2 hover:outline-red-200 "></i>
                    </button>
                    <h1 class="lg:text-4xl md:text-3xl text-2xl flex flex-row justify-center items-center text-underline">
                        Search Account Code:
                    </h1>
                    <div class="flex flex-row w-full relative">
                        <div class="w-full lg:px-28 md:px-12 px-4">
                            <input type="text" x-model="oe_query" class="border-2 w-full h-12 outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                            <div class="transition z-10 bg-slate-200 absolute lg:w-[calc(100%-14rem)] md:w-[calc(100%-6rem)] w-[calc(100%-2rem)] max-h-[175px] overflow-y-auto rounded-md">
                                @foreach ($oe_acode_json as $code)
                                    <button type="button" class="choice_button w-full text-center focus:bg-slate-300 hover:bg-slate-300 py-1 px-2 text-wrap"
                                    @click="modal_onclick('{{ $code['value'] }} ({{ $code['key'] }})', $data)"
                                    x-show="'{{ $code['key'] }} {{ $code['value'] }}'.toLowerCase().includes(oe_query.toLowerCase())"
                                    >{{ $code['value'] }} ({{ $code['key'] }})</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('component_section')
            @include('extras_section')
        </div>
        <div class="flex flex-row-reverse w-full mb-2">
            <button class="p-3 rounded-md bg-green-500 text-white hover:bg-white hover:text-green-500 transition outline-none outline-offset-0 hover:outline-2 hover:outline-green-300">Submit!</button>
        </div>
    </form>
</div>
