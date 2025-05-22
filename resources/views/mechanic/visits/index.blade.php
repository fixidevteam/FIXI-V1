<x-mechanic-app-layout :subtitle="'Liste des visites'">
    <div class="p-4 sm:ml-64">
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-14">
            {{-- Breadcrumb --}}
            <nav class="flex px-5 py-3 text-gray-700 bg-white shadow-sm sm:rounded-lg">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('mechanic.dashboard') }}" class="text-sm font-medium text-gray-700">
                            Accueil
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="{{ route('mechanic.visits.index') }}" class="text-sm font-medium text-gray-700">
                                Mes visites
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            {{-- Header --}}
            <div class="px-5 py-3 bg-white shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center my-6">
                    <h2 class="text-2xl font-bold text-gray-900">Liste des visites</h2>
                </div>
                {{-- Table --}}
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg my-5">
                    @if($visits->isEmpty())
                        <p class="p-4 text-gray-500 text-center">Aucun visite disponible.</p>
                    @else
                        <table class="w-full text-sm text-left text-gray-500">
                            <caption class="sr-only">Liste des visites</caption>
                            <thead class="text-xs uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">visit</th>
                                    <th scope="col" class="px-6 py-3">numero d'immatriculation</th>
                                    <th scope="col" class="px-6 py-3">nom</th>
                                    <th scope="col" class="px-6 py-3">tel</th>
                                    <th scope="col" class="px-6 py-3">Date du visit</th>
                                    <th scope="col" class="px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visits as $visit)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <a href="{{ route('mechanic.visits.show',$visit->id) }}">
                                            <span>visit {{$visit->id}}</span>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span>{{ explode('-', $visit->voiture->numero_immatriculation)[0] }}</span>-<span dir="rtl">{{ explode('-', $visit->voiture->numero_immatriculation)[1] }}</span>-<span>{{ explode('-', $visit->voiture->numero_immatriculation)[2] }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $visit->voiture->user->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $visit->voiture->user->telephone ?? "N/A"}}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $visit->date }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('mechanic.visits.show',$visit->id) }}" class="text-blue-600 hover:underline">Détails</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <!-- Pagination -->
                <div class="my-4" id="paginationLinks">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</x-mechanic-app-layout>
