<x-app-layout>
    <div class="w-full flex flex-col gap-3">
        <div class="tab_box w-full flex gap-2 flex-wrap font-medium text-center relative">

            @can('admin.orders.create')
                <x-radio-button id="mesaorder_carta" name="showpedido[]" value="carta" text="CARTA PRODUCTOS" active="true"
                    class="tab_btn_principal" />
            @endcan

            <x-radio-button id="mesaorder_pedidos" name="showpedido[]" value="pedidos" text="REGISTRAR PEDIDOS"
                class="tab_btn_principal" />

        </div>
        <div class="content_box w-full">
            <div class="content_principal transition-all ease-out duration-150 hidden active">
                @livewire('admin.orders.show-carta-productos', ['order' => null])
            </div>

            @can('admin.orders.create')
                <div class="content_principal hidden transition-all ease-out duration-150">
                    <div class="w-full">
                        @livewire('admin.orders.create-order', ['mesa' => $mesa], key($mesa->id))
                    </div>
                    <div class="w-full mt-5">
                        @livewire('admin.orders.show-pedidos', ['order' => null], key('pedidos-' . $mesa->id))
                    </div>
                </div>
            @endcan
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            console.log("Loaded...");
            showtabsprincipal();
            showtabs();

            // window.addEventListener('remove-active', () => {
            //     console.log('Remove tab active');
            //     showtabs();
            //     showtabsprincipal();
            // })

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
