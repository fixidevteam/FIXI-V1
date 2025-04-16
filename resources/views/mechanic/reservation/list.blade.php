<x-mechanic-app-layout :subtitle="'Historique des RDV’s'">
    <div class="p-4 sm:ml-64">
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-14">
            {{-- content (slot on layouts/app.blade.php)--}}
            <nav
                class="flex px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg "
                aria-label="Breadcrumb">
                <ol
                    class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a
                            href="{{ route('mechanic.dashboard') }}"
                            class="inline-flex items-center text-sm font-medium text-gray-700">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg
                                class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 6 10">
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a
                                href="{{ route('mechanic.reservation.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Gestion des RDV's
                            </a>
                        </div>
                    </li>

                    <li>
                        <div class="flex items-center">
                            <svg
                                class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 6 10">
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="m1 9 4-4-4-4" />
                            </svg>
                            <a
                                href="{{ route('mechanic.reservation.list') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Historique des RDV’s
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        {{-- content --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            {{-- content (slot on layouts/app.blade.php)--}}
            <div class=" px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center my-6">
                    <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">List des rendez-vous</h2>
                </div>
                <form method="GET" action="{{ route('mechanic.reservation.list') }}" class="mb-4">
                    <div class="flex items-center space-x-2">
                        <!-- Select Dropdown for Appointment Filter -->
                        <select
                            name="filter"
                            class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            onchange="this.form.submit()">
                            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>RDV actifs</option>
                            <option value="to_close" {{ request('filter') == 'to_close' ? 'selected' : '' }}>RDV à clôturer</option>
                        </select>

                        <!-- Search Button (optional) -->
                        <button type="submit" class="p-2.5 ms-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                            <svg class="w-4 h-4" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75ZM1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5C21.75 14.0605 20.8111 16.4017 19.2589 18.1982L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L18.1982 19.2589C16.4017 20.8111 14.0605 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5Z" fill="currentColor" />
                            </svg>
                            <span class="sr-only">Filtrer</span>
                        </button>

                        <!-- Reset Button -->
                        <a href="{{ route('mechanic.reservation.list') }}" class="hidden md:block p-2.5 ms-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300">
                            Réinitialiser
                        </a>
                        <a href="{{ route('mechanic.reservation.list') }}" class="md:hidden p-2.5 ms-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 font-semibold">
                            <svg class="w-4 h-4" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75ZM1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5C21.75 14.0605 20.8111 16.4017 19.2589 18.1982L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L18.1982 19.2589C16.4017 20.8111 14.0605 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5ZM8.25 11.5C8.25 11.0858 8.58579 10.75 9 10.75H14C14.4142 10.75 14.75 11.0858 14.75 11.5C14.75 11.9142 14.4142 12.25 14 12.25H9C8.58579 12.25 8.25 11.9142 8.25 11.5Z" fill="currentColor" />
                            </svg>
                            <span class="sr-only">Réinitialiser</span>
                        </a>
                    </div>
                </form>
                {{-- table --}}
                <div class="my-5">
                    {{-- alert --}}
                    @foreach (['success', 'error'] as $type)
                    @if (session($type))
                    <div class="fixed top-20 right-4 mb-5 flex justify-end z-10"
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:leave="transition ease-in duration-1000"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        x-init="setTimeout(() => show = false, 3000)">
                        <div role="alert" class="rounded-xl border border-gray-100 bg-white p-4 shadow-md">
                            <div class="flex items-start gap-4">
                                <span class="{{ $type === 'success' ? 'text-green-600' : 'text-red-600' }}">
                                    @if ($type === 'success')
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-6">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25Z" fill="currentColor" />
                                        <path d="M12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 6.89137 17.1086 2.75 12 2.75Z" fill="currentColor" />
                                    </svg>
                                    @endif
                                </span>
                                <div class="flex-1">
                                    <strong class="block font-medium text-gray-900"> {{ session($type) }} </strong>
                                    <p class="mt-1 text-sm text-gray-700">{{ session('subtitle') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    {{-- table --}}
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @if($appointments->isEmpty())
                        <p class="p-4 text-gray-500 text-center">Aucun rendez-vous disponible.</p>
                        @else
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <caption class="sr-only">List des Rendez-vous</caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nom du client
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        tel
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        categorie de service
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date du rendez-vous
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Heure du rendez-vous
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date de réception
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $appointment->user_full_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $appointment->user_phone }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->categorie_de_service }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-full border border-blue-400">
                                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($appointment->status === 'en cours')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                            En cours
                                        </span>
                                        @elseif($appointment->status === 'confirmé')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                            Confirmé
                                        </span>
                                        @elseif($appointment->status === 'annulé')
                                        <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                            Annulé
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{route('mechanic.reservation.show',$appointment->id)}}" class="font-medium capitalize text-blue-600 dark:text-blue-500 hover:underline">détails</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    {{-- close table --}}
                    <!-- Pagination -->
                    <div class="my-4" id="paginationLinks">
                        {{ $appointments->appends(['filter' => $filter])->links() }}
                    </div>
                </div>
            </div>
        </div>
        {{-- contet close colse --}}
        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>
</x-mechanic-app-layout>