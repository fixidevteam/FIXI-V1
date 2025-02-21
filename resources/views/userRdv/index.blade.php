<x-app-layout :subtitle="'Liste des RDV'">
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
                            href="{{ route('dashboard') }}"
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
                                href="{{ route('RDV.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Mes RDV
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
                    <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Liste des RDV</h2>
                </div>
                {{-- table --}}
                <div class="my-5">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        @if($appointments->isEmpty())
                        <p class="p-4 text-gray-500 text-center">Aucune RDV disponible.</p>
                        @else
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <caption class="sr-only">Liste des RDV</caption>
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Garage
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Garage ref
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Categorie de service
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        appointment day
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        appointment time
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($appointments as $appointment)
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $appointment->garage_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{$appointment->garage_ref}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$appointment->categorie_de_service}}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{$appointment->appointment_day}}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-full border border-blue-400">
                                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                                            </svg>
                                            {{$appointment->appointment_time}}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($appointment->status === 'en_cour')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                En Cour
                                            </span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                Confirmed
                                            </span>
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('RDV.show',$appointment->id) }}" class="font-medium capitalize text-blue-600 dark:text-blue-500 hover:underline">Détails</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <!-- Pagination -->
                    <div class="my-4" id="paginationLinks">
                        {{ $appointments->links() }}
                    </div>
                </div>
                {{-- table close --}}
            </div>

        </div>
        {{-- contet close colse --}}
        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>
</x-app-layout>