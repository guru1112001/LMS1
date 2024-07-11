<div wire:click="\$dispatch('selectSection({{ $getRecord()->id }}, \'{{ $getRecord()->name }}\')')">
    {{ $getState() }}
</div>
