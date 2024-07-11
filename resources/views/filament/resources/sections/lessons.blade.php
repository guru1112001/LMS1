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
    <div style="width: 100%">
        <div style="width: 48%;float: left;">
            <div>
                <select wire:model="selectedSection" wire:change="selectSection">
                    <option>--Select--</option>
                    @foreach($record->sections as $section)
                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-top: 20px;">
                <h2 class="card">
                    CONTENTS
                </h2>
                @if($materials)
                    <table id="filamentTable">
                        <tbody>
                        @foreach($materials as $material)
                            <tr wire:click="selectMaterial({{ $material->id }})">
                                <td>{{ $material->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div style="width: 48%;float: right;">
            @if($record->material_source == "other")
                <iframe src="{{ asset("storage/".$record->file) }}" height="600px" width="100%">

                </iframe>
            @endif
        </div>
    </div>
</x-filament::page>
