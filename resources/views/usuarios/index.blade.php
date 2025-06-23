<x-app-layout>
    <nav class="flex mb-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <x-content.nav-link href="/dashboard" class="inline-flex items-center">
                    <x-common.icon-home />
                    Home
                </x-content.nav-link>
            </li>
            <li class="flex items-center">
                <x-common.icon-row-nav />
                <x-content.nav-link href="{{ route('users.index') }}" class="ml-1 md:ml-2">Usuarios</x-content.nav-link>
            </li>
            <li class="flex items-center">
                <x-common.icon-row-nav />
                <x-content.nav-link-current>Lista</x-content.nav-link-current>
            </li>
        </ol>
    </nav>
    <div class="mt-2">
        <h1 class="text-xl mb-2 font-semibold text-gray-900 sm:text-2xl dark:text-white">
            Todos los Usuarios
        </h1>
        <div class="items-center block sm:flex pb-4">
            <div class="items-center mb-4 sm:mb-0">
                <form class="flex sm:pr-3" action="{{ route('users.index') }}" method="GET">
                    <div class="relative w-48 mt-1 sm:w-64 xl:w-96">
                        <x-common.input id="buscador" name="buscador" placeholder="Buscar"
                            value="{{ $textSearch }}" />
                    </div>
                    <div class="ml-2 w-48 mt-1 sm:w-48 xl:w-64">
                        <select id="filtro_user"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="0">Todos</option>
                            @foreach ($sRoles as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="flex items-center justify-end">
                        <div class="pl-2">
                            <x-common.button class="w-auto" type="submit">
                                <svg class="w-5 h-5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </x-common.button>
                        </div>
                    </div>

                </form>

            </div>

            <div class="flex items-center ml-auto space-x-3 sm:space-x-3">
                <x-common.button-link href="{{ route('users.index') }}">
                    <svg class="w-5 h-5 mr-2 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 18">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m2.133 2.6 5.856 6.9L8 14l4 3 .011-7.5 5.856-6.9a1 1 0 0 0-.804-1.6H2.937a1 1 0 0 0-.804 1.6Z" />
                    </svg>
                    Quitar filtros
                </x-common.button-link>
                <x-common.button-csv href="{{ route('users.export') }}">
                </x-common.button-csv>
                @can('agregar usuario')
                    <x-common.button-link href="{{ route('users.create') }}">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Agregar
                    </x-common.button-link>
                @endcan
            </div>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            <div class="col-md-12" id="tabla">
                @include('usuarios.tabla')
            </div>


            <div id="spinner-loading" class="ocultar spinner-container">
                <div class="custom-spinner"></div>
            </div>
        </div>

    </div>
    <x-views>
        Vistas: {{ getPageViews('usuarios_inicio') }}
    </x-views>

    <style>
        .ocultar {
            display: none !important;
        }

        .spinner-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.39);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgb(15, 205, 18), 22, 22);
        }

        .custom-spinner {
            border: 4px solid rgba(0, 0, 0, 0.2);
            border-top: 4px solid #007BFF;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    @push('scripts')
        <script>
            let currentRequestAjax = null;

            $(document).ready(function() {
                let page = 1;

                $(document).on('click', '.pagination a', function(e) {
                    e.preventDefault();
                    page = $(this).attr('href').split('page=')[1];
                    console.log('paginar...');
                    paginar(page);
                });

                function paginar(page) {
                    mostrarLoading();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('users.index') }}" + '?page=' + page,
                        data: {
                            buscador: $('#textSearch').val(),
                            filtro_user: $('#filtro_user').val()
                        },
                        success: function(response) {
                            ocultarLoading();
                            $('#tabla').html(response.Data);
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            ocultarLoading();
                            console.log(xhr);
                        }
                    });
                }

                $('#buscador').keyup(function(e) {
                    page = 1;
                    currentRequestAjax = $.ajax({
                        type: "GET",
                        url: window.location.href + '?page=' + page,
                        data: {
                            buscador: $('#buscador').val(),
                            filtro_user: $('#filtro_user').val()
                        },
                        beforeSend: function() {
                            if (currentRequestAjax != null) {
                                currentRequestAjax.abort();
                            }
                        },
                        success: function(response) {
                            console.log('response: ', response);
                            $('#tabla').html(response.Data);
                            
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.log(xhr);
                        }
                    });

                });


            });

            const loading = document.getElementById('spinner-loading');

            function mostrarLoading() {
                loading.classList.remove("ocultar");
            }

            function ocultarLoading() {
                loading.classList.add("ocultar");
            }

            $('#filtro_user').on('change', function() {
                mostrarLoading();

                $.ajax({
                    type: "GET",
                    url: "{{ route('users.index') }}",
                    data: {
                        filtro_user: $('#filtro_user').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Yess');
                        console.log('UsersAll ', response.UsersAll);
                        $('#tabla').html(response.Data);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.error('Error');
                    },
                    complete: function() {
                        ocultarLoading();
                    }
                });

            });

            function habilitarF(user_id) {
                let activo = document.getElementById('activo' + user_id);
                let inactivo = document.getElementById('inactivo' + user_id);
                let habilitar = document.getElementById('habilitar' + user_id);
                let inhabilitar = document.getElementById('inhabilitar' + user_id);
                $.ajax({
                    type: "POST",
                    url: "{{ route('users.cambiarEstado') }}",
                    data: {
                        id: user_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        activo.hidden = false;
                        inactivo.hidden = true;
                        habilitar.hidden = true;
                        inhabilitar.hidden = false;
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        };
                        toastr.success(response.message);
                    },
                    error: function(response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        };
                        toastr.error(response.error);
                    },
                });
            };

            function inhabilitarF(user_id) {
                let activo = document.getElementById('activo' + user_id);
                let inactivo = document.getElementById('inactivo' + user_id);
                let habilitar = document.getElementById('habilitar' + user_id);
                let inhabilitar = document.getElementById('inhabilitar' + user_id);
                $.ajax({
                    type: "POST",
                    url: "{{ route('users.cambiarEstado') }}",
                    data: {
                        id: user_id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        activo.hidden = true;
                        inactivo.hidden = false;
                        habilitar.hidden = false;
                        inhabilitar.hidden = true;
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        };
                        toastr.success(response.message);
                    },
                    error: function(response) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        };
                        toastr.error(response.error);
                    },
                });
            };
        </script>
    @endpush
</x-app-layout>
