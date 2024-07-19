<div class="border-2 border-white h-full w-full bg-white text-black rounded-md p-6 shadow-lg">
    <div class="border-b-2 pb-2 gap-4 flex flex-row flex-wrap justify-between">
        <div class="w-80">
            <!-- for peso: ₱ -->
            <div class="flex flex-row w-full gap-1">
                <label class="w-28 text-right">City fund:</label>
                <!-- x-model="" -->
                <input class="w-[calc(20rem-7rem)] border-2 rounded-md px-2 cursor-not-allowed" disabled value="0"></input>
            </div>
            <div class="flex flex-row w-full gap-1">
                <label class="w-28 text-right">Other funds:</label>
                <input class="w-[calc(20rem-7rem)] border-2 rounded-md px-2 cursor-not-allowed" disabled value="0"></input>
            </div>
        </div>
        <div class="w-80 flex items-end">
            <div class="flex flex-row w-full gap-1">
                <label class="w-28 text-right">Total Cost:</label>
                <input class="w-[calc(20rem-7rem)] border-2 rounded-md px-2 cursor-not-allowed" disabled value="0"></input>
            </div>
        </div>
    </div>
    <div>
        <div class="overflow-x-auto pt-2 pb-4" >
                @php
                    $headers_all = array_merge(['name' => 'PPA Component'], $headers);
                    $headers_all["o_actions"] = "Output Actions";
                    $headers_all["c_actions"] = "Component Actions";

                    $headers_200 = array_slice($headers_all, 1, 8);
                    $headers_250 = array_slice($headers_all, 9, 2);
                @endphp

                <div class="flex flex-row w-max divide-x-2 divide-black border-t-2 border-x-2 border-black bg-sky-800 text-white rounded-t-lg">
                    @foreach ($headers_all as $header)
                        <div
                            @class([
                                'h-20 font-medium grow-0 shrink-0 flex justify-center items-center text-center',
                                'w-[300px]' => $header == "PPA Component",
                                'w-[200px]' => in_array($header, $headers_200),
                                'w-[250px]' => in_array($header, $headers_250),
                                'w-[150px]' => $header == "Output Actions",
                                'w-[275px]' => $header == "Component Actions",
                            ])
                        >
                            {{ $header }}
                        </div>
                    @endforeach
                </div>

                <template x-for="(field, index) in comp" :key="index" x-init="console.log(comp)">
                    <!--  TODO: make bottom part of table rounded -->
                    <div :class="[(index % 2) ? 'bg-slate-200':'bg-slate-100', comp.length-1 == index ? 'rounded-b-lg border-b-2' : '']" class="flex flex-row min-h-16 w-[2829px] border-l-2 border-black">
                        <textarea type="text" x-model="comp[index]['name']" :name="'comp['+index+'][name]'" class="bg-inherit border-t-2 border-black shrink-0 grow-0 focus:outline-blue-400 w-[300px] min-h-full px-1 resize-none"></textarea>

                        <!-- excluding PPA component -->
                        <div class="flex flex-wrap w-[2250px] divide-y-2 divide-black border-pink-200">
                            <template x-for="(body,i) in comp[index]['component']" :key="i">
                                <div class="flex flex-row divide-x-2 divide-black bg-inherit">
                                <template x-for="(value, key) of $wire.headers" :key="key">
                                    <!-- excluding output & component actions -->
                                        <!-- details 🤓 -->
                                        <div
                                            :class="['source_of_fund', 'r_office', 'o_actions', 'c_actions'].includes(key) ? 'w-[250px]': 'w-[200px]' "
                                        >
                                            <template x-if="key == 'oe_acode'">
                                                <textarea class="w-full h-full cursor-pointer text-wrap bg-inherit min-h-24 w-full flex justify-center items-center text-center resize-none" :id="'comp['+index+'][component]['+i+']['+key+']'" :name="'comp['+index+'][component]['+i+']['+key+']'" type="text" @focus="comp_oe_onclick([index,i,key])" x-model="comp[index]['component'][i][key]">
                                                </textarea>
                                            </template>
                                            <template x-if="key == 'source_of_fund'">
                                                <select x-model="comp[index]['component'][i][value]" :name="'comp['+index+'][component]['+i+']['+key+']'"
                                                class="bg-inherit h-full w-full px-2"
                                                >
                                                    <!-- NOTE: This setup means that when a "source of fund" or sof gets unclicked, -->
                                                    <!-- it won't be removed from the alpine variable. This SHOULD be fine if data sent to server -->
                                                    <!-- will not rely on alpine and instead on the input fields. -->
                                                    <template x-for="chosen in ssof">
                                                        <option :value="chosen" x-text="chosen"></option>
                                                    </template>
                                                </select>
                                            </template>
                                            <template x-if="['ps', 'mooe', 'co'].includes(key)">
                                                <input x-model="comp[index]['component'][i][key]" :name="'comp['+index+'][component]['+i+']['+key+']'"
                                                @blur="row_total(index, i)"
                                                class="bg-inherit h-full w-full"
                                                x-init="set_price(index, i, key)"
                                                ></input>
                                            </template>
                                            <template x-if="key == 'total'">
                                                <input x-model="comp[index]['component'][i][key]" :name="'comp['+index+'][component]['+i+']['+key+']'"
                                                class="bg-inherit h-full w-full cursor-not-allowed"
                                                x-init="row_total(index, i)"
                                                disabled=true
                                                ></input>
                                            </template>
                                            <template x-if="! ['oe_acode', 'ps', 'mooe', 'co', 'source_of_fund', 'total'].includes(key)">
                                                <textarea x-model="comp[index]['component'][i][key]" :name="'comp['+index+'][component]['+i+']['+key+']'"
                                                class="bg-inherit min-h-24 w-full flex justify-center items-center resize-none"
                                                ></textarea>
                                            </template>
                                        </div>
                                    </template>
                                    <!-- output actions -->
                                    <div class="w-[150px] min-h-16 bg-inherit flex justify-center items-center border-black" :id="index+'.'+i+'.output'">
                                        <button @click="remove_output(index, i)" :disabled="comp[index]['component'].length == 1" :class="Object.keys(comp[index]['component']).length == 1 ? 'bg-red-200 cursor-not-allowed' : 'bg-red-500 outline-none outline-offset-0 hover:outline-2 hover:outline-red-300'" class="w-min p-2 flex flex-row justify-center items-center gap-1 text-white text-center rounded-md transition transition-all" type="button">
                                            <i class="fa-solid fa-trash-can"></i> Output
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="w-[300px] h-auto flex justify-center items-center border-x-2 border-t-2 border-black">
                            <!-- component actions -->
                            <div class="p-4 gap-3 bg-inherit flex flex-row justify-center items-center" :id="index+'.'+'.component'">
                                <button @click="add_output(index)" class="bg-green-500 w-min p-2 flex flex-row justify-center items-center gap-1 text-white text-center rounded-md outline-none outline-offset-0 hover:outline-2 hover:outline-red-300 transition transition-all" type="button">
                                    <i class="fa-solid fa-plus"></i> Output
                                </button>
                                <button @click="remove_comp(index)" :disabled="Object.keys(comp).length == 1" :class="Object.keys(comp).length == 1 ? 'bg-red-200 cursor-not-allowed' : 'bg-red-500 outline-none outline-offset-0 hover:outline-2 hover:outline-red-300'" class="w-min p-2 flex flex-row justify-center items-center gap-1 text-white text-center rounded-md transition transition-all" type="button">
                                    <i class="fa-solid fa-trash-can"></i> Component
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
        </div>
        <!-- + component -->
        <div class="flex flex-row-reverse mb-5 pb-2 border-b-2">
            <button type="button" @click="add_comp()" class="bg-green-500 w-min p-2 flex flex-row justify-center items-center gap-1 text-white text-center rounded-md outline-none outline-offset-0 hover:outline-2 hover:outline-red-300 transition transition-all">
                <i class="fa-solid fa-plus"></i> Component
            </button>
            <button @click="console.log(JSON.stringify(comp)+' '+JSON.stringify(oe_var))" class="bg-pink-500 w-min p-2 flex flex-row justify-center items-center gap-1 text-white text-center rounded-md outline-none outline-offset-0 hover:outline-2 hover:outline-red-300 transition transition-all" type="button">
            <i class="fa-solid fa-plus"></i> Component
            </button>
        </div>

        <div class="overflow-x-auto pt-2 pb-4">
            <div class="flex flex-col w-[2825px] h-auto divide-y-2 divide-black border-2 border-black rounded-lg">
                <!-- Summary Headers -->
                <div class="grid grid-cols-[repeat(20,_minmax(0,_1fr))] min-h-24 w-full bg-sky-800 text-white divide-x-2 divide-black">
                    <div class="col-span-4 flex items-center justify-center">
                        Object of Expenditure/Account Title
                    </div>
                    <div class="col-[span_12_/_span_12] grid grid-rows-4 grid-cols-12 divide-y-2 divide-black">
                        @php
                            $mini_headers = [
                                "Source of Fund",
                                "General Fund",
                                "TF",
                                "SEF",
                                "GF-Proper",
                                "GF -Economic Enterprise",
                                "Regular",
                                "20% LDF",
                                "DRRMF",
                                "Hospital",
                                "Slaughter House",
                                "Terminal",
                            ];
                        @endphp
                        @foreach ($mini_headers as $m)
                            <div
                                @class([
                                    'flex py-4 items-center justify-center w-full',
                                    'border-l-2' => in_array($m, ['TF', 'SEF', 'GF -Economic Enterprise', '20% LDF', 'DRRMF', 'Hospital', 'Slaughter House', 'Terminal']),
                                    'col-span-full' => $m == 'Source of Fund',
                                    'col-span-8' => $m == 'General Fund',
                                    'col-span-4' => in_array($m, ['GF-Proper', 'GF -Economic Enterprise']),
                                    'col-span-2' => in_array($m, ['TF', 'SEF', '20% LDF', 'Slaughter House']),
                                    'col-start-9 row-start-2 row-end-5' => $m == 'TF',
                                    'col-start-11 row-start-2 row-end-5' => $m == 'SEF',
                                    'col-span-1' => in_array($m, ['Regular', 'DRRMF', 'Hospital', 'Terminal']),
                                ]) >{{ $m }}</div>
                        @endforeach
                    </div>
                    <div class="col-span-4 flex items-center justify-center">
                        Total
                    </div>
                </div>
                <template x-for="oe in oe_var">
                    <input type="text" class="grid grid-cols-[repeat(20,_minmax(0,_1fr))] min-h-24 w-full bg-slate-100 divide-x-2 divide-black" x-model="oe">
                    </input>
                </template>
            </div>
        </div>

    </div>
</div>
