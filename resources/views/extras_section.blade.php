<div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
    @php
        $ext_string = [
            "ext_fund.donor" => "Name of external donor",
            "ext_fund.ppa" => "Specific PPA component provided Assistance",
            "ext_fund.amount" => "Amount, in kind or technical assistance provided",
        ];
    @endphp

    <!-- ext_fund -->
    <div x-data="{ show_ext: 'false' }" class="mb-2">
        <div class="flex flex-row flex-wrap gap-4">
            <label class="w-auto">11. If with External Funding/Assistance:</label>
            <div class="flex flex-row gap-2">
                <template x-for="text in [['yes', true], ['no', false]]">
                    <div class="flex flex-row gap-1">
                        <input :id="'ext_'+text[0]" type="radio" name="ext_include" x-model="show_ext" :value="text[1] === true" class="rounded-sm"></input>
                        <label :for="'ext_'+text[0]" x-text="text[0]"></label>
                    </div>
                </template>
            </div>
        </div>
        <template x-if="show_ext == 'true'">
            <div class="w-full bg-slate-100 rounded-md p-2" x-data="{
                ext_comp: 0,
                search_focus: false,
                ext_query: '',
                add_ext(ppa) {
                    console.log('add triggered '+ppa);
                    this.ext_comp = ppa;
                    this.search_focus = false;
                },
                check_blur(e) {
                    if(e.relatedTarget == null || !(e.relatedTarget.classList.contains('choice_button'))){
                        console.log('should blur');
                        this.search_focus = false;
                    }
                }
            }">
                @foreach ($ext_string as $name => $title)
                    <label for="{{ $name }}" class="w-full">{{ $title }}:</label>
                    @if ($name == "ext_fund.ppa")
                        <div class="w-full relative">
                            <input type="text" @focus="search_focus = true" @blur="check_blur($event)" x-model="comp[ext_comp]['name']" id="{{ $name }}" name="{{ $name }}" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
                            <div x-show="search_focus" @focusout="check_blur($event)" class="transition z-10 bg-slate-200 absolute top-8 w-full rounded-md">
                                <template x-for="(choice,i) in comp" :key="i">
                                    <button type="button" @click.stop="add_ext(i)" x-show="choice['name'] != '' && choice['name'].toLowerCase().includes(ext_query.toLowerCase())" x-text="choice['name']" class="choice_button w-full text-left focus:bg-slate-300 hover:bg-slate-300 py-1 text-wrap px-2"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    @else
                    <input type="text" id="{{ $name }}" name="{{ $name }}" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
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
                        <input :id="'gad_'+text[0]" type="radio" name="gad_include" x-model="show_gad" :value="text[1] === true" class="rounded-sm"></input>
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
                        <input @focus="search_focus = true" @blur="check_blur($event)" type="text" id="gad.name" name="gad.name" class="min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
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
                    <input name="gad.output" id="gad.output" class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block rounded-md"></input>
                </div>
                <div class="md:col-span-3 col-span-6">
                    <label class="w-full">Cost:</label>
                    <div class="flex flex-row justify-center items-center">
                        <span class="text-xl">₱</span>
                        <input name="gad.cost" id="gad.cost" class="min-h-9 w-full outline-none transition col-span-2 px-2 border-2 inline-block rounded-md"></input>
                    </div>
                </div>
                <div class="col-span-full flex flex-row flex-wrap justify-evenly items-center bg-slate-100 rounded-md">
                    <template x-for="(item, index) in gad_comp" :key>
                        <template x-if="comp[item]">
                            <div class="flex flex-row gap-1">
                                <input :id="item+'.'+index+'.gad'" type="checkbox" name="gad[]" :value="item" @change="remove_gad(index)" checked></input>
                                <label x-text="comp[item]['name']" :for="item+'.'+index+'.gad'" ></label>
                            </div>
                        </template>
                    </template>
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
                        <input :id="'cc_'+text[0]" type="radio" name="cc_include" x-model="show_cc" :value="text[1] === true" class="rounded-sm"></input>
                        <label :for="'cc_'+text[0]" x-text="text[0]"></label>
                    </div>
                </template>
            </div>
        </div>
        <template x-if="show_cc == 'true'">
            <div class="w-full" x-data="{
                cc_fields: {
                    'cc.obj': 'CC objective',
                    'cc.risks': 'Climate risks being addressed',
                    'cc.info': 'Climate information used',
                    'cc.expenditure': 'Estimated amount of CC expenditures',
                },
                cc_comps: [],
                search_focus: false,
                cc_query: '',
                add_cc(ppa) {
                    console.log('add triggered');
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
                }
            }">
                    <div class="flex flex-row flex-wrap w-full justify-evenly mb-2 gap-2">
                        <div class="flex flex-row w-[300px]">
                            <label for="cc.typo" class="w-[200px]">CC Typology:</label>
                            <input type="text" id="cc.typo" name="cc.typo" class="border-2 w-full outline-none focus:border-gray-300 transition px-2 rounded-md"></input>
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
                                <div class="bg-slate-300 rounded-md p-2">
                                    <div class="flex flex-row py-2 justify-center items-center">
                                        <label :for="'cc.name.'+index" class="w-full"> Name of CC component :</label>
                                        <button @click="remove_cc(index)" type="button" class="bg-red-500 gap-2 w-20 text-white rounded-md flex flex-row justify-center items-center outline-none outline-offset-0 hover:outline-2 hover:outline-red-300">
                                            <i class="fa-solid fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    <input type="text" x-model="comp[a_cc]['name']" :id="'cc.name.'+index" :name="'cc.name.'+index" class="mb-2 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md cursor-not-allowed" disabled></input>
                                    <template x-for="(title, name) of cc_fields">
                                        <div>
                                            <label :for="name+'.'+index" class="w-full" x-text="title+':'"></label>
                                            <input type="text" :id="name+'.'+index" :name="name+'.'+index" class="mb-2 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></input>
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
        <textarea type="text" id="response" name="response" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

    <!-- risks -->
    <label class="w-full">15. Possible Risks that may impede the success of the PPA:</label>
    <div class="w-full mt-2">
        <textarea type="text" id="risks" name="risks" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

    <!-- support -->
    <label class="w-full">16. Legislative Support:</label>
    <div class="w-full mt-2">
        <textarea type="text" id="support" name="support" class="mb-6 min-h-9 border-2 w-full outline-none focus:border-gray-300 transition col-span-2 px-2 rounded-md"></textarea>
    </div>

    <div class="flex flex-row-reverse w-full">
        <button class="p-3 rounded-md bg-green-500 text-white outline outline-offset-2 hover:outline-2 hover:outline-green-300">Submit!</button>
    </div>
</div>
