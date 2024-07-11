<x-filament-panels::page layout="" header="">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="">
        <div style="width: 30%;float: left;"
        >
            <section class="grid auto-cols-fr gap-y-6 pb-4">
                <x-filament-panels::header.simple
                    :heading="$subheading ??= $this->getSubHeading()"
                />

                <div
                    class="min-h-[theme(spacing.48)] bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">


                    <div class="bg-white rounded-lg shadow-md p-4">

                        <div class="flex justify-between mb-4">
                            <x-filament::link
                                color="gray"
                                href="/administrator"
                                icon="heroicon-o-chevron-left"
                                rel="noopener noreferrer"
                            >
                                {{ __('Go To Dashboard') }}
                            </x-filament::link>
                            {{--                            <a href="#" class="text-orange-500 hover:text-orange-700">View Rating</a>--}}
                        </div>
                        <h2 class="text-xl font-semibold text-gray-700">{{ $this->course->name }}</h2>
                        <p class="text-gray-600">{{ $this->record->name }}</p>

                        <div class="pt-4">
                            <label for="subject" class="block text-sm font-medium text-gray-700">Select Subject</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                {{--                                <select--}}
                                {{--                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"--}}
                                {{--                                    wire:model="curriculum"--}}
                                {{--                                    wire:change="updatedCurriculum($event.target.value)"--}}
                                {{--                                >--}}
                                {{--                                    @foreach($curriculums as $curriculum)--}}
                                {{--                                        <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>--}}
                                {{--                                    @endforeach--}}
                                {{--                                </select>--}}

                                {{--                                <x-filament::input.wrapper>--}}
                                {{--                                    <x-filament::input.select wire:model="curriculum">--}}
                                {{--                                        @foreach($curriculums as $curriculum)--}}
                                {{--                                            <option value="{{ $curriculum->id }}">{{ $curriculum->name }}</option>--}}
                                {{--                                        @endforeach--}}
                                {{--                                    </x-filament::input.select>--}}
                                {{--                                </x-filament::input.wrapper>--}}

                                {{ $this->form }}
                            </div>
                            {{--                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">--}}
                            {{--                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 0%"></div>--}}
                            {{--                            </div>--}}
                        </div>

                        {{--                        <div class="progress">--}}
                        {{--                            <div class="progress-bar" role="progressbar" style="width: {{ $percentageCompleted }}%;"--}}
                        {{--                                 aria-valuenow="{{ $percentageCompleted }}" aria-valuemin="0" aria-valuemax="100">--}}
                        {{--                                {{ number_format($percentageCompleted, 2) }}%--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}


                        <div class="relative pt-5">
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span
                                        class="text-xs font-semibold inline-block py-1 px-2 rounded-full text-white bg-primary-600">
                                        Progress
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold inline-block text-primary-600">
                                        {{ number_format($percentageCompleted, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                                <div style="width:{{ $percentageCompleted }}%"
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500"></div>
                            </div>
                        </div>
                    </div>

            </section>
            <section class="grid auto-cols-fr gap-y-6 pb-4">
                <div
                    class="min-h-[theme(spacing.48)] bg-white shadow-sm ring-1
                    ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">

                    <div class="mt-4">
                        <div class="p-4 flex justify-between items-center">
                            <h3 class="text-gray-700 font-medium">CONTENTS</h3>
                        </div>
                        @if($this->sections)
                        <x-zeus-accordion::accordion activeAccordion="3" class="p-0">
                            @foreach($this->sections->sortBy('sort') as $section)
                                @php
                                    $batchId = $this->record->id;
                                    $teaching_materials = \App\Models\TeachingMaterial::where('section_id', $section->id)
                                        ->whereHas('batches', function($query) use ($batchId) {
                                            $query->where('batch_id', $batchId);
                                        })
                                    ->get();

                                    $section_complete_status = $this->materialStatus->whereIn('teaching_material_id',$teaching_materials->pluck('id'))->count();
                                @endphp
                                <x-zeus-accordion::accordion.item
                                    :isIsolated="true"
                                    :label="$section->name"
                                    icon="heroicon-o-check-circle"
                                    :badge="$section_complete_status.' / ' . $teaching_materials->count()"
                                    badgeColor="danger"
                                    :id="$section->id"
                                >
                                    <div class="bg-white">

                                        @foreach($teaching_materials->sortBy('sort') as $material)
                                            <p style="cursor: pointer; margin-left: -5%; margin-right: -5%;"
                                               wire:click="selectMaterial({{  $material->id }})"
                                               class="bg-white p-2 pl-6 flex @if(isset($view_material) && $view_material->id == $material->id) active @endif">
                                                <svg
                                                    style="@if(!$this->materialStatus->where('teaching_material_id', $material->id)->count()) visibility: hidden @endif"
                                                    class="fi-accordion-item-icon filepond--image-preview-overlay-success h-6 w-6 p-1 shrink-0 transition duration-75"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    aria-hidden="true" data-slot="icon">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                                                </svg>

                                                <span style="padding-left: 5px;">
                                                    <span class="font-semibold">{{ $material->name }}</span><br> {{ $material->material_source }}
                                                </span>
                                            </p>
                                        @endforeach
                                    </div>
                                </x-zeus-accordion::accordion.item>
                            @endforeach

                        </x-zeus-accordion::accordion>
                        @endif
                        {{--                        <div class="bg-gray-100">--}}
                        {{--                            <div class="rounded-lg shadow-md">--}}
                        {{--                                @foreach($this->sections as $section)--}}
                        {{--                                    <div class="flex items-center justify-between p-4">--}}
                        {{--                                        <h4 class="font-medium text-gray-600">{{ $section->name }}</h4>--}}
                        {{--                                        <span--}}
                        {{--                                            class="text-sm text-gray-500">0/{{ $section->teaching_material->count() }}</span>--}}
                        {{--                                    </div>--}}
                        {{--                                    @foreach($section->teaching_material as $material)--}}
                        {{--                                        <div class="bg-white p-2 left-3">--}}
                        {{--                                            {{ $material->name }}--}}
                        {{--                                        </div>--}}
                        {{--                                    @endforeach--}}
                        {{--                                @endforeach--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </section>
        </div>
        {{--        @if($selectedSection)--}}
        {{--            <div style="width: 49%;float: right;"--}}
        {{--                 class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">--}}
        {{--                <livewire:teaching-material.list-material :section="$section" :wire:key="$selectedSection['id']">--}}
        {{--            </div>--}}
        {{--        @endif--}}

        @if($view_material)
            <div style="width: 69%;float: right;"
                 class="sticky fi-ta-ctn divide-y
                 divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5
                  dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 p-6">
                {{--                {{ $view_material }}--}}


                <div class="flex justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">{{ $view_material->name }}</h2>

                    @if($view_material->privacy_downloadable)
                        <a download="{{ $view_material->name }}"
                           style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                           class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75
                             focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md
                              gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50
                               dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
                           type="button"
                           href="{{ url('storage/'. $view_material->file) }}"
                           target="_blank">

                            <span class="fi-btn-label">Download</span>
                        </a>
                    @endif
                </div>

                @if($view_material->material_source == "url")
                    <iframe src="{{ $view_material->content }}" width="100%"
                            height="400px"></iframe>
                @elseif($view_material->material_source == "content")
                    {{ $view_material->content }}
                @elseif(!empty($view_material->file))
                    <iframe src="{{ url('storage/'. $view_material->file) }}#toolbar=0" width="100%"
                            height="700px"></iframe>
                @endif


                <div class="mt-6 border-2"></div>
                <div class="pt-6 pb-6">
                    {!! $view_material->description  !!}
                </div>

                <div class="mt-6 border-2"></div>

                <div class="pt-6 pb-6">
                @if($view_material->doc_type == 2)
                    <livewire:teaching-material-status.submit-assignment :teaching_material_id="$view_material->id" :batch_id="$batch">
                @endif
                </div>

                @if($assignmentSubmissionAlert)
                    <div class="alert flex justify-end text-danger-600 dark:checked:bg-danger-500 bg-gray-50">
                         Please submit assignment first !!!
                    </div>
                @endif

                <div class="flex justify-end pt-4">

                    <button style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                            class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75
                             focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md
                              gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50
                               dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action"
                            type="button"
                            wire:click="completeMaterial({{ $view_material->id }})">

                        <span class="fi-btn-label">Complete & Continue</span>
                    </button>
                </div>

            </div>
        @endif

{{--        <div class="fixed flex items-center justify-center bg-gray-100">--}}
{{--            <div class="bg-white p-6 rounded-lg shadow-lg">--}}
{{--                <h2 class="text-xl font-bold text-red-600">Warning!</h2>--}}
{{--                <p class="mt-4 text-gray-700">Unable to Continue</p>--}}
{{--            </div>--}}
{{--        </div>--}}


    </div>
    <style type="text/css">

        .fi-main-sidebar {
            display: none;
        }

        .fi-topbar {
            display: none;
        }

        div.sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
        }

        main div section {
            padding: 0px;
        }
    </style>

</x-filament-panels::page>
