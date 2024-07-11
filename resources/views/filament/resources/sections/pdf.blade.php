<x-filament::page>
    @if($record->material_source == "other")
        <iframe src="{{ asset("storage/".$record->file) }}" height="600px" width="100%">

        </iframe>
    @endif
    @if($record->material_source == "url")
        <iframe src="{{ $record->content }}" height="600px" width="100%"></iframe>
    @endif
</x-filament::page>
