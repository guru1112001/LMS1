<x-filament::page>
    <style>
        /* Add your CSS styles here */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
    </style>
    <div>
        @if($reorder)
            {{ $this->table }}
        @endif
    </div>
    <div class="grid flex-1 auto-cols-fr gap-y-8">
        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
            <table id="filamentTable">
                <thead>
                <tr>
                    <th>Id</th>
                    <th colspan="3">Sections</th>
                    {{--                    <th>Price</th>--}}
                    {{--                    <th>Stock</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($record->sections as $section)
                    <tr style="cursor: pointer"
                        wire:click="selectSection({{ $section->id }})"
                    >
                        <td>{{ $section->name }}</td>
                        <td>
                            <x-filament::icon
                                icon="heroicon-m-eye"
                                class="h-5 w-5 text-gray-500 dark:text-gray-400"
                            />
{{--                            {{--}}

{{--    \Filament\Actions\EditAction::make()--}}
{{--        ->action($section)--}}
{{--        ->record($section)--}}
{{--            ->form([--}}
{{--                \Filament\Forms\Components\TextInput::make('name')--}}
{{--                    ->required()--}}
{{--                    ->maxLength(255),--}}
{{--                // ...--}}
{{--            ])--}}


{{--}}--}}
{{--                            {{--}}
{{--                                \Filament\Tables\Actions\ActionGroup::make([--}}
{{--                                    \Filament\Tables\Actions\Action::make('edit')--}}
{{--                                    ->record($section)--}}
{{--                                    ->form(--}}
{{--                                        [\Filament\Forms\Components\TextInput::make('name')--}}
{{--                                                ->required()--}}
{{--                                                ->maxLength(255)]--}}
{{--                                    ),--}}
{{--                                \Filament\Tables\Actions\Action::make('view'),--}}

{{--                                \Filament\Tables\Actions\Action::make('delete'),--}}
{{--                            ])--}}
{{--                            }}--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{--@foreach($sections as $section)
                <div wire:click="selectSection({{ $section->id }})">
                    {{ $section->name }}
                </div>
            @endforeach--}}

        </div>
        <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
            @if($materials)
                <table id="filamentTable">
                    <thead>
                    <tr>
                        {{--<th>ID</th>--}}
                        <th>{{ $section->name }}</th>
                        {{--<th>Price</th>
                        <th>Stock</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($materials as $material)
                        <tr wire:click="selectMaterial({{ $material->id }})">
                            <td>{{ $material->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            {{--@foreach($materials as $material)
                <div>
                    {{ $material->name }}
                </div>
            @endforeach--}}
            {{--@livewire(\App\Livewire\Material::class)--}}
            {{--<livewire:material />--}}
            {{--{{  }}--}}
            {{--@livewire(\App\Filament\Resources\TeachingMaterialResource\Widgets\TeachingMaterial::class)--}}
        </div>
    </div>
</x-filament::page>
