<x-app-layout>
    @can('admin.cocina')
        <x-tittle>LISTADO DE PEDIDOS</x-tittle>
        @livewire('admin.cocina.show-cocina')

        <audio id="reproductor">
            <source src="{{ asset('assets/sound/timbre.mp3') }}" type="audio/mpeg">
            Tu navegador no admite la reproduccion de audio.
        </audio>
    @endcan

    @section('js')
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                window.Echo.channel('orders').listen("LoadPedidos", (data) => {
                    console.log(data.mensaje);
                    reproducir();
                    Swal.fire({
                            title: data.mensaje.mensaje,
                            text: 'Datos actualizados, presione recargar para mostrar los datos actualizados.',
                            icon: 'question',
                            showCancelButton: false,
                            confirmButtonText: 'Recargar',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            cancelButtonText: 'Cancelar',
                        })
                        .then((result) => {
                            if (result.isConfirmed) {
                                Livewire.emitTo('admin.cocina.show-cocina', 'mount');
                            }
                        })
                });

                //     // window.onload = function() {
                // Echo.channel("chat").listen("MessageSent", (e) => {
                //     Livewire.emitTo('admin.cocina.show-cocina', 'render');
                //     console.log("Listen desde livewire.admin.cocina.show-cocina");
                //     console.log(e);
                //     reproducir();
                // });

                // window.Echo.channel("chat").listen("MessageSent", (data) => {
                // console.log(data);
                // Livewire.emitTo('admin.cocina.show-cocina', 'mount');
                // reproducir();
                // location.reload();
                // getPedidos();
                // try {
                //     $.ajax({
                //         type: "get",
                //         url: "{{ route('admin.consulta') }}",
                //         dataType: "json",
                //         success: function(response) {
                //             console.log(response);
                //         }
                //     });
                // } catch (e) {
                //     console.log(e.getMessage());
                // }

                // });

                //     // };

                // })

                async function getPedidos() {
                    try {
                        const response = await fetch('consulta-orders/');
                        console.log(response);
                    } catch (error) {
                        console.log('Error de red: ', error);
                    }
                }

                function reproducir() {
                    // var audio = new Audio('');
                    // audio.play();

                    var reproductor = document.getElementById('reproductor');
                    reproductor.play();
                }
            })
        </script>
    @endsection
</x-app-layout>
