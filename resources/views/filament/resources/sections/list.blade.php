<x-filament::page>


    <div class="">
        <div style="width: 49%;float: left;"
             class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            {{ $this->table }}
        </div>
        @if($selectedSection)
            <div style="width: 49%;float: right;"
                 class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
{{--                @livewire('material-list', ['selectedSectionId' => $selectedSection['id'],'selectedSectionName' => $selectedSection['name']])--}}
{{--                @livewire('teaching-material.list-material',['section'=>$selectedSection])--}}

                <livewire:teaching-material.list-material :section="$section" :wire:key="$selectedSection['id']">

            </div>


            {{--            <div style="width: 49%;float: right;"--}}
            {{--                 class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">--}}
            {{--                <table id="filamentTable"--}}
            {{--                       class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">--}}
            {{--                    <thead class="divide-y divide-gray-200 dark:divide-white/5">--}}
            {{--                    <tr class="bg-gray-50 dark:bg-white/5">--}}

            {{--                        <th colspan="4" class="text-left">--}}
            {{--                            <div class="px-3 py-4">--}}
            {{--                                {{ $section->name }}--}}
            {{--                            </div>--}}
            {{--                        </th>--}}
            {{--                        --}}{{--<th>ID</th>--}}

            {{--                        --}}{{--<th>Price</th>--}}
            {{--                        <th>Stock</th>--}}
            {{--                    </tr>--}}
            {{--                    </thead>--}}
            {{--                    <tbody>--}}
            {{--                    @foreach($materials as $material)--}}
            {{--                        <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 hover:bg-gray-50 dark:hover:bg-white/5"--}}
            {{--                            wire:click="selectMaterial({{ $material->id }})">--}}
            {{--                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-ta-selection-cell w-1">--}}
            {{--                                <div class="px-3 py-4">--}}
            {{--                                    {{ $material->sort }}--}}
            {{--                                </div>--}}
            {{--                            </td>--}}
            {{--                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-ta-selection-cell w-1">--}}
            {{--                                <div class="px-3 py-4">--}}
            {{--                                    <x-filament::icon--}}
            {{--                                        icon="heroicon-m-document-text"--}}
            {{--                                        class="h-10 w-10 text-gray-500 dark:text-gray-400"--}}
            {{--                                    />--}}
            {{--                                </div>--}}
            {{--                            </td>--}}
            {{--                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-ta-selection-cell w-1">--}}
            {{--                                <div class="px-3 py-4">--}}

            {{--                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">--}}
            {{--                                        <div class="flex ">--}}
            {{--                                            <div class="flex max-w-max" style="">--}}
            {{--                                                <div class="fi-ta-text-item inline-flex items-center gap-1.5  ">--}}
            {{--                                                        <span--}}
            {{--                                                            class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  "--}}
            {{--                                                            style="">{{ $material->name }}</span>--}}
            {{--                                                </div>--}}
            {{--                                            </div>--}}
            {{--                                        </div>--}}
            {{--                                        <p class="text-sm text-gray-500 dark:text-gray-400">--}}
            {{--                                            {{ $material->file }}--}}
            {{--                                        </p>--}}
            {{--                                    </div>--}}

            {{--                                </div>--}}
            {{--                            </td>--}}
            {{--                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-ta-selection-cell w-1"--}}
            {{--                                style="cursor: pointer">--}}
            {{--                                <div class="px-3 py-4">--}}
            {{--                                    <x-filament::icon--}}
            {{--                                        icon="heroicon-m-eye"--}}
            {{--                                        class="h-5 w-5 text-gray-500 dark:text-gray-400"--}}
            {{--                                    />--}}
            {{--                                </div>--}}
            {{--                            </td>--}}
            {{--                        </tr>--}}
            {{--                    @endforeach--}}
            {{--                    </tbody>--}}
            {{--                </table>--}}

            {{--                --}}{{--@foreach($materials as $material)--}}
            {{--                    <div>--}}
            {{--                        {{ $material->name }}--}}
            {{--                    </div>--}}
            {{--                @endforeach--}}
            {{--                @livewire(\App\Livewire\Material::class)--}}

            {{--                {{  }}--}}

            {{--            </div>--}}
        @endif

    </div>

</x-filament::page>
