<div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
    @php
        $ext_string = [
            "donor" => "Name of external donor",
            "ppa" => "Specific PPA component provided Assistance",
            "amount" => "Amount, in kind or technical assistance provided",
        ];
    @endphp

    <!-- ext_fund -->
    <div x-data="{ show_ext: 'false' }" class="mb-2">
        <div class="flex flex-row flex-wrap gap-4">
            <label class="w-auto">11. If with External Funding/Assistance:</label>
            <div class="flex flex-row gap-2">
                <template x-for="text in [['yes', true], ['no', false]]">
                    <div class="flex flex-row gap-1">
                        <input :id="'ext_'+text[0]" type="radio" name="extra[ext_include]" x-model="show_ext" :value="text[1] === true" class="rounded-sm"></input>
                        <label :for="'ext_'+text[0]" x-text="text[0]"></label>
                    </div>
                </template>
            </div>
        </div>
        <template x-if="show_ext == 'true'">
            <div class="w-full bg-slate-100 rounded-md p-2" x-data="{
                ext_comp: 1,
                ext_focus: false,
                ext_query: '',
                add_ext(ppa) {
                    console.log('add triggered '+ppa);
                    this.ext_comp = ppa;
                    this.ext_focus = false;
                    this.ext_query = $data.comp[ppa]['name'];
                },
                check_blur(e) {
                    // TODO: check if text is 'valid'
                    if(e.relatedTarget == null || !(e.relatedTarget.classList.contains('choice_button'))){
                        this.ext_focus = false;
                        if(this.ext_query != $data.comp[this.ext_comp]['name']){
                            this.ext_query = $data.comp[this.ext_comp]['name'];
                        }
                    }
                },
            }"
            x-init="
            ext_comp = Object.keys($data.comp)[0];
            $watch('$data.comp', () => {
                console.log('$data watch log');
                if (! Object.keys($data.comp).includes(ext_comp)){
                    ext_comp = Object.keys($data.comp)[0];
                    console.log('json is empty- resetting ext_comp: '+Object.keys($data.comp)[0]);
                }
                ext_query = $data.comp[ext_comp]['name'];
            })"
            >
                @foreach ($ext_string as $name => $title)
                    <label for="{{ $name }}" class="w-full">{{ $title }}:</label>
                    @if ($name == "ppa")
                        <div class="w-full relative">
                            <input type="text" x-model="ext_query" @focus="ext_focus = true" @blur="check_blur($event)" id="{{ $name }}" name="extra[ext_fund][{{ $name }}]" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                            <div x-show="ext_focus" @focusout="check_blur($event)" class="transition z-10 bg-slate-200 absolute top-8 w-full rounded-md ext_div">
                                <template x-for="(choice,i) in comp" :key="i">
                                    <button type="button" @click.stop="add_ext(i)" x-show="choice['name'] != '' && choice['name'].toLowerCase().includes(ext_query.toLowerCase())" x-text="choice['name']" class="choice_button w-full text-left focus:bg-slate-300 hover:bg-slate-300 py-1 text-wrap px-2"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    @else
                    <input type="text" id="{{ $name }}" name="extra[ext_fund][{{ $name }}]" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                    @endif
                @endforeach
            </div>
        </template>
    </div>

    <!-- gad -->
    <div x-data="{ show_gad: 'false' }" class="mb-2">
        <div class="flex flex-row flex-wrap gap-4">
            <label class="w-auto">12. Gender and Development (GAD) mainstreaming:</label>
            <div class="flex flex-row gap-2">
                <template x-for="text in [['yes', true], ['no', false]]">
                    <div class="flex flex-row gap-1">
                        <input :id="'gad_'+text[0]" type="radio" name="extra[gad_include]" x-model="show_gad" :value="text[1] === true" class="rounded-sm"></input>
                        <label :for="'gad_'+text[0]" x-text="text[0]"></label>
                    </div>
                </template>
            </div>
        </div>
        <!-- <label class="w-full">12. Gender and Development (GAD) mainstreaming:</label> -->
        <template x-if="show_gad == 'true'">
            <div class="grid grid-cols-12 gap-1 mb-6" x-data="{
                gad_comp: [],
                search_focus: false,
                gad_query: '',
                add_gad(ppa) {
                    console.log('add triggered '+ppa);
                    if(! this.gad_comp.includes(ppa)) {
                        this.gad_comp.push(ppa);
                    }
                    this.search_focus = false;
                },
                remove_gad(index) {
                    this.gad_comp.splice(index, 1);
                    if(document.getElementById('gad.cbox'+index) != null) {
                        document.getElementById('gad.cbox'+index).checked = true;
                    }
                },
                check_blur(e) {
                    if(e.relatedTarget == null || !(e.relatedTarget.classList.contains('choice_button'))){
                        console.log('should blur');
                        this.search_focus = false;
                    }
                }
            }">
                <div class="md:col-span-6 col-span-full flex flex-row flex-wrap">
                    <label for="gad.name" class="w-[200px]">Search PPA component/s:</label>
                    <div class="w-full relative">
                        <input @focus="search_focus = true" @blur="check_blur($event)" type="text" id="gad.name" x-model="gad_query" name="extra[gad][name]" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                        <div x-show="search_focus" @focusout="check_blur($event)" class="transition z-10 bg-slate-200 absolute top-8 w-full rounded-md">
                            <template x-for="(choice,i) in comp" :key="i">
                                <button type="button" @click.stop="add_gad(i)" x-show="choice['name'] != '' && choice['name'].toLowerCase().includes(gad_query.toLowerCase())" x-text="choice['name']" class="choice_button w-full text-left focus:bg-slate-300 hover:bg-slate-300 py-1 px-2 text-wrap"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-3 col-span-6">
                    <label class="w-full">Output:</label>
                    <input name="extra[gad][output]" id="gad.output" class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block rounded-md"></input>
                </div>
                <div class="md:col-span-3 col-span-6">
                    <label class="w-full">Cost:</label>
                    <div class="flex flex-row justify-center items-center">
                        <span class="text-xl">₱</span>
                        <input name="extra[gad][cost]" id="gad.cost" class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block rounded-md"></input>
                    </div>
                </div>
                <div class="col-span-full flex flex-row flex-wrap justify-evenly items-center bg-slate-100 rounded-md py-4 relative">
                    <div class="absolute top-2 left-2 border-gray-200 border-b-2">
                        PPA Components:
                    </div>
                    <div class="p-4 flex flex-row flex-wrap gap-3">
                        <template x-for="(item, index) in gad_comp" :key>
                            <template x-if="comp[item]">
                                <div class="flex flex-row gap-1 w-min">
                                    <input :id="'gad.cbox'+index" type="checkbox" name="extra[gad][gad][]" :value="item" @change="remove_gad(index)" checked></input>
                                    <label x-text="comp[item]['name']" :for="'gad.cbox'+index" ></label>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- cc -->
    <div x-data="{ show_cc: 'false' }" class="mb-2 gap-1">
        <div class="flex flex-row flex-wrap gap-4">
            <label class="w-auto">13. If PPA has component/s that address Climate Change (CC) Adaptation or Mitigation:</label>
            <div class="flex flex-row gap-2">
                <template x-for="text in [['yes', true], ['no', false]]">
                    <div class="flex flex-row gap-1">
                        <input :id="'cc_'+text[0]" type="radio" name="extra[cc_include]" x-model="show_cc" :value="text[1] === true" class="rounded-sm"></input>
                        <label :for="'cc_'+text[0]" x-text="text[0]"></label>
                    </div>
                </template>
            </div>
        </div>
        <template x-if="show_cc == 'true'">
            <div class="w-full" x-data="{
                cc_fields: {
                    'obj': 'CC objective',
                    'risks': 'Climate risks being addressed',
                    'info': 'Climate information used',
                    'expenditure': 'Estimated amount of CC expenditures',
                },
                cc_comps: [],
                search_focus: false,
                cc_query: '',
                add_cc(ppa) {
                    if (! this.cc_comps.includes(ppa)){
                        this.cc_comps.push(ppa);
                    };
                    this.search_focus = false;
                },
                remove_cc(index) {
                    this.cc_comps.splice(index, 1);
                },
                check_blur(e) {
                    if(e.relatedTarget == null || !(e.relatedTarget.classList.contains('choice_button'))){
                        console.log('should blur');
                        this.search_focus = false;
                    }
                },
                typo_type: 'Adaptation',
                typo_codes: {{ Js::from($cc_typo_json) }},
                typo_string: '',
                typo_focus: false,
                typo_query: '',
                add_typo(choice) {
                    console.log('adding typo: '+choice);
                    this.typo_query = this.typo_string = choice;
                    this.typo_focus = false;
                    // $refs.cc_typo.value = ;
                },
                check_typo_blur(e) {
                    // TODO: check if text is 'valid'
                    if(e.relatedTarget == null || !(e.relatedTarget.classList.contains('choice_button'))){
                        this.typo_focus = false;
                        if(this.typo_query != this.typo_string){
                            this.typo_query = this.typo_string;
                        }
                    }
                },
            }"
            x-init="
            $watch('typo_type', () => {
                typo_string = typo_query = '';
            })">
                    <div class="flex flex-row flex-wrap w-full justify-evenly mb-2 gap-2">
                        <div class="flex flex-row flex-wrap gap-2">
                            <template x-for="text in ['Adaptation', 'Mitigation']">
                                <div class="flex flex-row gap-1 items-center">
                                    <input :id="'cc_'+text" type="radio" name="extra[cc_am]" x-model="typo_type" :value="text" class="rounded-sm" required></input>
                                    <label :for="'cc_'+text" x-text="text"></label>
                                </div>
                            </template>
                        </div>
                        <div class="flex flex-row w-[300px] relative">
                            <label for="cc.typo" class="w-[200px]">CC Typology:</label>
                            <input x-model="typo_query" @focus="typo_focus = true" @blur="check_typo_blur($event)" type="text" id="cc.typo" name="extra[cc][typo]" class="border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                            <div x-show="typo_focus" @focusout="check_typo_blur($event)" class="choice_button transition z-10 bg-slate-200 absolute top-8 left-[117px] w-[calc(100%-117px)] max-h-[175px] overflow-y-auto rounded-md">
                                <template x-for="(choice, i) in typo_codes">
                                    <button type="button" @click.stop="add_typo(choice['key'])" x-show="typo_type[0] == choice['key'][0] && choice['key'] != '' && choice['key'].toLowerCase().includes(typo_query.toLowerCase())" x-text="choice['key']" class="choice_button w-full text-left focus:bg-slate-300 hover:bg-slate-300 py-1 px-2 text-wrap"
                                    ></button>
                                </template>
                            </div>
                        </div>
                        <div class="flex flex-row w-[600px] relative">
                            <label class="w-[210px]">Add CC component:</label>
                            <input type="text" @focus="search_focus = true" @blur="check_blur($event)" x-model="cc_query" class="border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
                            <div x-show="search_focus" @focusout="check_blur($event)" class="transition z-10 bg-slate-200 absolute top-8 left-[154px] w-[calc(100%-154px)] max-h-[175px] overflow-y-auto rounded-md">
                                <template x-for="(choice, i) in comp">
                                    <button type="button" @click.stop="add_cc(i)" x-show="choice['name'] != '' && choice['name'].toLowerCase().includes(cc_query.toLowerCase())" x-text="choice['name']" class="choice_button w-full text-left focus:bg-slate-300 hover:bg-slate-300 py-1 px-2 text-wrap"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="gap-2 flex flex-col">
                        <template x-for="(a_cc, index) in cc_comps">
                            <template x-if="comp[a_cc]">
                                <div class="bg-slate-100 rounded-md p-2">
                                    <div class="flex flex-row py-2 justify-center items-center">
                                        <label :for="'cc.name.'+index" class="w-full"> Name of CC component :</label>
                                        <button @click="remove_cc(index)" type="button" class="bg-red-500 gap-2 w-20 text-white rounded-md flex flex-row justify-center items-center outline-none outline-offset-0 hover:outline-2 hover:outline-red-300">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    <input type="text" x-model="comp[a_cc]['name']" :id="'cc.name.'+index" :name="'extra[cc]['+index+'][name]'" class="mb-2 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md cursor-not-allowed" disabled></input>
                                    <template x-for="(title, name) of cc_fields">
                                        <div>
                                            <label :for="name+'.'+index" class="w-full" x-text="title+':'"></label>
                                            <textarea type="text" :id="name+'.'+index" :name="'extra[cc]['+index+']['+name+']'" class="mb-2 min-h-[48px] border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </template>
                    </div>
            </div>
        </template>
    </div>

    <!-- response -->
    <label class="w-full">14. Expected Private Sector Response after PPA is completed:</label>
    <div class="w-full mt-2">
        <textarea type="text" id="response" name="extra[response]" class="mb-6 min-h-12 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

    <!-- risks -->
    <label class="w-full">15. Possible Risks that may impede the success of the PPA:</label>
    <div class="w-full mt-2">
        <textarea type="text" id="risks" name="extra[risks]" class="mb-6 min-h-12 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

    <!-- support -->
    <label class="w-full">16. Legislative Support:</label>
    <div class="w-full mt-2">
        <textarea type="text" id="support" name="extra[support]" class="mb-6 min-h-12 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

</div>
