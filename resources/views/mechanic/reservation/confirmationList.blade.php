<x-mechanic-app-layout :subtitle="'Confirmation des rendez-vous'">
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
                                href="{{ route('mechanic.reservation.list') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Mes rendez-vous
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
                                href="{{ route('mechanic.confirmation') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Confirmation des rendez-vous
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
                    <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Liste des demandes de rendez-vous</h2>
                </div>
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
                        <p class="p-4 text-gray-500 text-center">Aucune demande de rendez-vous disponible.</p>
                        @else
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <caption class="sr-only">Liste des demandes de rendez-vous</caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Nom du client
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
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
                                        {{ $appointment->user_email ?? "N/A"}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->user_phone }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->categorie_de_service }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $appointment->appointment_day }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-full border border-blue-400">
                                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mx-2 px-2.5 py-0.5 rounded-full">
                                            En cours
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 flex items-center justify-center space-x-2">
                                        <button onclick="toggleModal(true, 'acceptation-{{$appointment->id}}')" class="text-red-500 flex items-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="2" width="20" height="20" rx="5" stroke="#34C759" stroke-width="1.5" />
                                                <path d="M9.5 11.5L11.5 13.5L15.5 9.5" stroke="#34C759" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>

                                        <button onclick="toggleModal(true, 'annuler-{{$appointment->id}}')" class="ml-5 text-red-500 flex items-center">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="2" width="20" height="20" rx="5" stroke="#FF3B30" stroke-width="1.5" />
                                                <path d="M9.8787 14.1215L14.1213 9.87891" stroke="#FF3B30" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9.8787 9.87894L14.1213 14.1216" stroke="#FF3B30" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
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
                        {{ $appointments->links() }}
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
    @foreach($appointments as $appointment)
    <div id="acceptation-{{ $appointment->id }}" class="fixed inset-0 bg-white bg-opacity-30 backdrop-blur-[2px] flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Confirmation d'acceptation</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir accepter ce rendez-vous ? Cette action ne peut pas être annulée.</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal(false, 'acceptation-{{ $appointment->id }}')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">
                    Annuler
                </button>
                <form action="{{ route('mechanic.confirmation.accepter', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @foreach($appointments as $appointment)
    <div id="annuler-{{ $appointment->id }}" class="fixed inset-0 bg-white bg-opacity-30 backdrop-blur-[2px] flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-96 shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Confirmer l'annulation</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action ne peut pas être annulée.</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal(false, 'annuler-{{ $appointment->id }}')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">
                    Annuler
                </button>
                <form action="{{ route('mechanic.confirmation.annuler', $appointment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Confirmer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
    <script>
        function toggleModal(show, modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden', !show);
            }
        }
    </script>
</x-mechanic-app-layout>