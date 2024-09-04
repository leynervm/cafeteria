@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="flex items-center justify-between p-2 bg-fondoheadermodal text-colorheadermodal">
        {{ $title }}
    </div>

    <div class="p-3">
        {{ $content }}
    </div>

    @if (isset($footer))
        <div class="flex gap-3 flex-row justify-center p-2 text-center my-3">
            {{ $footer }}
        </div>
    @endif

</x-jet-modal>
