<x-app-layout>
    @can('admin.orders')
        <div class="w-full">
            @livewire('admin.orders.show-notapedido', ['order' => $order], key($order->id))
        </div>
    @endcan
</x-app-layout>
