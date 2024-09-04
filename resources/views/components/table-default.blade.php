<table class="w-full text-xs">
    <thead class="shadow bg-bgheadertable text-coloheadertable">

        {{ $headers }}

    </thead>
    <tbody class="bg-bgtable text-colortable">

        @if (isset($rows))
            {{ $rows }}
        @endif

    </tbody>
</table>
