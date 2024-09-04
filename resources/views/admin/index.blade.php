<x-app-layout>
    <div class="w-full flex flex-col gap-3">
        <div class="tab_box w-full flex gap-1 flex-wrap font-medium text-center relative">
            <x-radio-button id="mesaorder_carta" name="showpedido[]" value="carta" text="CARTA PRODUCTOS" active="true"
                class="tab_btn_principal" />

            <x-radio-button id="mesaorder_pedidos" name="showpedido[]" value="pedidos" text="RESUMEN PEDIDOS"
                class="tab_btn_principal" />
        </div>
        <div class="content_box w-full">
            <div class="content_principal transition-all ease-out duration-150 hidden active">
                @can('admin.orders')
                    @livewire('admin.orders.show-carta-productos', ['order' => $order], key($order->id))
                @endcan
            </div>

            <div class="content_principal hidden w-full transition-all ease-out duration-150">
                <div class="w-full flex flex-col gap-3 bg-fondoform text-colorform p-5 shadow-md">
                    <div class="w-full flex items-center justify-between">
                        <h1 class="font-semibold text-xl">ORD-{{ $order->id }} \ {{ $order->name }} \
                            {{ $order->mesa->name }}</h1>
                        <h2 class="font-semibold text-xl">{{ $order->pedidos->count() }} Pedidos</h2>
                    </div>
                </div>
                <div class="w-full mt-5">
                    @livewire('admin.orders.show-pedidos', ['order' => $order], key('detalle' . $order->id))
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            showtabsprincipal();
            showtabs();

            function showtabsprincipal() {
                const tabs = document.querySelectorAll('.tab_btn_principal');
                const all_content = document.querySelectorAll('.content_principal');

                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', (e) => {

                        tabs.forEach(tab => {
                            tab.classList.remove('active')
                        });
                        tab.classList.add('active');
                        tab.style.background = e.target.offsetBackground;

                        all_content.forEach(content => {
                            content.classList.remove('active')
                        });
                        all_content[index].classList.add('active');
                    })
                });
            }

            function showtabs() {
                const tabs = document.querySelectorAll('.tab_btn');
                const all_content = document.querySelectorAll('.content');

                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', (e) => {

                        tabs.forEach(tab => {
                            tab.classList.remove('active')
                        });
                        tab.classList.add('active');
                        tab.style.background = e.target.offsetBackground;

                        all_content.forEach(content => {
                            content.classList.remove('active')
                        });
                        all_content[index].classList.add('active');
                    })
                });
            }
        });
    </script>
</x-app-layout>
