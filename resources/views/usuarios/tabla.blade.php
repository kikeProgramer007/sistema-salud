<table id="datatable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
            <th scope="col" class="p-4">
                CI
            </th>
            <th scope="col" class="px-6 py-3">
                Nombre
            </th>
            <th scope="col" class="px-4 py-3">
                Rol
            </th>
            <th scope="col" class="px-6 py-3">
                Estado
            </th>
            <th scope="col" class="px-6 py-3">
                Acción
            </th>
        </tr>
    </thead>
    <tbody>
        @if (count($users) <= 0)
            <tr>
                <td colspan="5" class="pt-3 text-center">
                    No se encontraron resultados
                </td>
            </tr>
        @else
            @foreach ($users as $user)
                <tr
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        {{ $user->ci }}
                    </td>
                    <th scope="row"
                        class="flex items-center px-2 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($user->profile_photo_path)
                            <img class="w-8 h-8 rounded-full object-cover"
                                src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="user photo">
                        @else
                            <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="user photo">
                        @endif
                        <div class="pl-3">
                            <div class="text-base font-semibold">
                                {{ $user->name . ' ' . $user->ap_paterno . ' ' . $user->ap_materno }}</div>
                            <div class="font-normal text-gray-500">{{ $user->email }}</div>
                        </div>
                    </th>
                    <td class="px-4 py-4">
                        @forelse ($user->roles as $role)
                            {{ $role->name }}
                        @empty
                            Sin rol
                        @endforelse
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if ($user->estado == 1)
                                <span id="activo{{ $user->id }}"
                                    class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    Activo</span>
                                <span id="inactivo{{ $user->id }}" hidden
                                    class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                    Inactivo</span>
                            @else
                                <span id="activo{{ $user->id }}" hidden
                                    class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                    Activo</span>
                                <span id="inactivo{{ $user->id }}"
                                    class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                    Inactivo</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="mr-2 font-medium text-blue-600 dark:text-blue-500 hover:underline">Editar</a>
                        @can('cambiar estado usuario')
                            @if ($user->estado == 1)
                                <a id="inhabilitar{{ $user->id }}" onclick="inhabilitarF({{ $user->id }})"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Inhabilitar</a>
                                <a id="habilitar{{ $user->id }}" hidden onclick="habilitarF({{ $user->id }})"
                                    class="mr-2 font-medium text-green-600 dark:text-green-500 hover:underline">Habilitar</a>
                            @else
                                <a id="habilitar{{ $user->id }}" onclick="habilitarF({{ $user->id }})"
                                    class="mr-2 font-medium text-green-600 dark:text-green-500 hover:underline">Habilitar</a>
                                <a id="inhabilitar{{ $user->id }}" hidden onclick="inhabilitarF({{ $user->id }})"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline">Inhabilitar</a>
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="pagination">
    {{ $users->links() }}
</div>