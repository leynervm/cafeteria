<x-app-layout>

    <div class="overflow-hidden w-full">
        <x-tittle class="mt-0 mb-3">MIS ORDENES PENDIENTES</x-tittle>
        {{-- @livewire('form-chat') --}}
        @livewire('admin.index')
    </div>
    <script>
        // document.addEventListener('livewire:load', function() {
        //     console.log("Livewire cargado");
        //     Livewire.on('messageAdded', message => {
        //         console.log('NUEVO MENSAJE RECIBIDO POR JAVASCRIPT : ' + message);
        //     })
        // });

        // document.addEventListener('recargarpagina', () => {
        //     console.log("REfresh");
        //     location.reload();

        // });

        // window.onload = function() {
        //Ha estado funcionando y lo pase a show-cocina
        // Echo.channel("chat").listen("MessageSent", (e) => {
        //     console.log(e);
        // });

        // window.Echo.channel("chat").listen("MessageSent", (data) => {
        //     console.log(data);
        //     Livewire.emit('render');
        //     document.dispatchEvent(new Event('recargarpagina'));
        // });

        //     Livewire.hook('message.sent', (message, component) => {
        //         console.log(message);
        //     })

        //     Livewire.hook('element.updated', (el, component) => {
        //         console.log(el);
        //     })

        // };
    </script>
</x-app-layout>
