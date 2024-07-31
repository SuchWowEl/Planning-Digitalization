<div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
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
        <input type="text" id="{{ $name }}" wire:model="section2.{{ $name }}" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md">
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
            <input type="text" id="type" wire:model="section2.type" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
        </div>
        @php $genders = ["male", "female"]; @endphp
        @foreach ($genders as $g)
            <div class="col-span-2">
                <label for="{{ $g }}" class="w-full">{{ ucfirst($g) }}:</label>
                <input @blur="add_mf()" placeholder="0" min="0" type="number" id="{{ $g }}" x-ref="{{ $g }}" wire:model="section2.{{ $g  }}"  class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md" required value></input>
            </div>
        @endforeach
        <div class="md:col-span-1 col-span-2">
            <label class="w-full">Total:</label>
            <div class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block rounded-md cursor-not-allowed flex flex-col justify-center" x-text="btotal" x-init="add_mf()"></div>
        </div>
    </div>

    <div class="w-full flex flex-col mb-6">
        <label class="w-full">7. Implementation Period</label>
        <template x-if="ppa_stat == '25' || ppa_stat == '' || ppa_stat == null">
            <div class="w-full">
                <div class="flex flex-row justify-center text-wrap">
                    N/A since PPA is routine/continuing (or PPA Status hasn't been set yet)
                </div>
                <input x-ref="impl1" type="date" id="start" wire:model="section2.start" value="none" class="hidden min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                <input x-ref="impl2" type="date" id="end" wire:model="section2.end" value="none" class="hidden min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
            </div>
        </template>
        <template x-if="ppa_stat != '25' && ppa_stat != '' && ppa_stat != null"">
            <div class="grid grid-cols-6 gap-1">
                <div class="md:col-start-2 md:col-span-2 col-span-3">
                    <label for="start">Start:</label>
                    <input x-ref="impl1" type="date" id="start" wire:model="section2.start" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                    @error('section2.start') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-start-4 md:col-span-2 col-span-3">
                    <label for="end">End:</label>
                    <input x-ref="impl2" type="date" id="end" wire:model="section2.end" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                    @error('section2.end') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </template>
    </div>

    <label class="w-full">8. Major Outputs or Success Indicators</label>
    <div class="grid grid-cols-6 gap-1 mb-6">
        <div class="md:col-start-2 md:col-span-4 col-span-full"
        x-data="{
            ind: $wire.section2.indicators,
            add_row() {
                this.ind.push('');
                // this.ind[this.ind_last_count] = '';
                // this.ind_last_count++;
            },
            remove_row(index) {
                // delete this.ind[index];
                this.ind.splice(index, 1);
            }
        }"
        x-model='$wire.section2.indicators' x-modelable='ind'
        >
            <div class="flex flex-row w-full justify-between mb-2 items-center">
                <label>Performance measure & target:</label>
                <button @click="add_row()" class="border-2 border-green-500 w-8 rounded-md h-9 bg-green-500 text-white outline-none outline-offset-0 hover:outline-2 hover:outline-green-300 transition transition-all" type="button">
                    +
                </button>
            </div>
            <template x-for="(field, index) in ind" :key="index">
                <div class="flex flex-row w-full mb-2 gap-1">
                        <input type="text" x-model="ind[index]" :name="'details[goal]['+index+']'" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                        <button x-show="Object.keys(ind).length > 1" @click="remove_row(index)" class="bg-red-500 w-8 rounded-md text-white outline-none outline-offset-0 hover:outline-2 hover:outline-red-300 transition transition-all" type="button">
                        x
                        </button>
                </div>
            </template>
        </div>
    </div>

    <div class="w-full">
        <label class="w-full">9. Source of Fund</label>
        @error('section2.subfund') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
    <div class="grid grid-cols-7 gap-1 mb-6 mx-8 p-2 border-2 rounded-md  lg:divide-y-0 divide-y lg:divide-x divide-x-0 bg-slate-100"
    x-data="{
        sof_onclick(el, to_check) {
            console.log('el.value: '+el.value);
            let subsec_filtered = (el.value == 'General Fund Proper') ? gfp_arr.filter(x => ssof.includes(x)) : tf_arr.filter(x => ssof.includes(x));
            console.log('subsec: '+subsec_filtered);
            if (el.checked && subsec_filtered.length==0) {
                ssof.push(to_check);
            } else {
                console.log('removing');
                ssof = ssof.filter(x => !subsec_filtered.includes(x));
                console.log(ssof);
            }
        },
    }"
    >
        <div class="lg:col-span-3 lg:grid lg:grid-cols-12 col-span-full flex flex-col w-full">
            <div class="gap-1 col-span-full">
                <input id="gfp" x-ref="gfp" type="checkbox" wire:model="section2.fund" value="General Fund Proper" @click="sof_onclick($el, 'GF - Proper')" x-model="sof" x-modelable="$wire.section2.fund">
                <label for="gfp">General Fund Proper</label>
            </div>
            <div class="grid grid-cols-12 col-span-full justify-between">
                <div class="gap-1 col-start-2 lg:col-start-3 w-[120px]">
                    <input id="gf" type="checkbox" wire:model="section2.subfund" value="GF - Proper" x-model="ssof" x-modelable="$wire.section2.subfund"></input>
                    <label for="gf">GF - Proper</label>
                </div>
                <div class="lg:col-start-2 col-end-13 col-span-7">
                    <div class="w-full">
                    @foreach (array_keys($section2->fund_gfp) as $name)
                        @if (in_array($loop->iteration, [1,($loop->count/2+1)]))
                            <div class="lg:col-start-2 col-span-full">
                        @endif
                        <div class="gap-1 lg:col-start-2 lg:col-span-8 col-span-full">
                            <input id="{{ $name }}" type="checkbox" wire:model="section2.subfund" value="{{ $name }}" x-model="ssof" x-modelable="$wire.section2.subfund"></input>
                            <label for="{{ $name }}">{{ $name }}</label>
                        </div>
                        @if (in_array($loop->iteration, [($loop->count/2),$loop->count]))
                            </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-2 grid grid-cols-12 col-span-full px-1">
            <div class="gap-1 col-span-full h-min">
                <input id="trust_f" x-ref="tf" type="checkbox" wire:model="section2.fund" value="Trust Fund" @click="sof_onclick($el, 'Financial Assistance')" x-model="sof" x-modelable="$wire.section2.fund"></input>
                <label for="trust_f">Trust Fund</label>
            </div>
            <div class="col-start-2 col-span-full">
                <div class="w-full">
                @foreach (array_keys($section2->fund_tf) as $name)
                    <div class="gap-1">
                        <input id="{{ $name }}" type="checkbox" wire:model="section2.subfund" value="{{ $name }}" x-model="ssof" x-modelable="$wire.section2.subfund"></input>
                        <label for="{{ $name }}">{{ $name }}</label>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        <div class="lg:col-span-2 col-span-full px-1">
            <div class="gap-1">
                <input id="sef" type="checkbox" wire:model="section2.subfund" value="Special Education Fund" x-model="ssof" x-modelable="$wire.section2.subfund"></input>
                <label for="sef">Special Education Fund</label>
            </div>
        </div>
    </div>

</div>
