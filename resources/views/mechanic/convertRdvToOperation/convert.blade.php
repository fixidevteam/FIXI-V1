<x-mechanic-app-layout :subtitle="'Conversion en opération'">


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
                                href="{{route('mechanic.reservation.show',$Appointment->id)}}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Détails du rendez-vous
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
                                href=""
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Conversion en visit
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
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Conversion en visit</h2>
                <form method="POST" action="{{ route('mechanic.reservation.convert') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="">
                        <x-input-label for="client_name" :value="__('Nom du client')" />
                        <x-text-input id="client_name" name="client_name" type="text" class="block mt-1 w-full bg-gray-200 cursor-not-allowed " value="{{ $Appointment->user_full_name }}" readonly />
                        <x-input-error :messages="$errors->get('client_name')" class="mt-2" />
                    </div>
                    <div class="">
                        <x-input-label for="client_tel" :value="__('Telephone du client')" />
                        <x-text-input id="client_tel" name="client_tel" type="text" class="block mt-1 w-full bg-gray-200 cursor-not-allowed " value="{{ $Appointment->user_phone }}" readonly />
                        <x-input-error :messages="$errors->get('client_tel')" class="mt-2" />
                    </div>
                    <div class="">
                        <x-input-label for="client_email" :value="__('Email du client')" />
                        <x-text-input id="client_email" name="client_email" type="text" class="block mt-1 w-full bg-gray-200 cursor-not-allowed " value="{{ $Appointment->user_email ?? NULL}}" placeholder="{{ $Appointment->user_email ?? 'N/A'}}" readonly />
                        <x-input-error :messages="$errors->get('client_email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_operation" :value="__('Date de l\'operation')" />
                        <x-text-input id="date_operation" class="block mt-1 w-full bg-gray-200 cursor-not-allowed " type="date" name="date_operation" :value="$Appointment->appointment_day" readonly autofocus autocomplete="date_operation" />
                        <x-input-error :messages="$errors->get('date_operation')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="categorie" :value="__('Service')" />
                        <x-text-input id="categorie" class="block mt-1 w-full bg-gray-200 cursor-not-allowed " type="text" name="categorie" :value="$Appointment->categorie_de_service" readonly autofocus autocomplete="categorie" /> <x-input-error :messages="$errors->get('categorie')" class="mt-2" />
                    </div>
                    @if($client == NULL || $client->voitures->isEmpty())

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                        <div>
                            <x-input-label for="part1" :value="__('Numero d\'immatriculation (6 chiffres)')" />
                            <x-text-input id="part1" class="block mt-1 w-full" type="text" name="part1" :value="old('part1')" autofocus autocomplete="part1" maxlength="6" placeholder="123456" />
                            <x-input-error :messages="$errors->get('part1')" class="mt-2" />
                        </div>
                        {{-- alpha --}}
                        <div>
                            <x-input-label for="part2" :value="__('Numero d\'immatriculation (Lettre en Arabe)')" />
                            <select id="part2" name="part2" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @foreach(['أ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'ه', 'و', 'ي'] as $letter)
                                <option value="{{ $letter }}"
                                    {{ old('part2', 'أ') == $letter ? 'selected' : '' }}>
                                    {{ $letter }}
                                </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('part2')" class="mt-2" />
                        </div>
                        {{-- numbers --}}
                        <!-- Partie 3 -->
                        <div>
                            <x-input-label for="part3" :value="__('Numero d\'immatriculation (2 chiffres)')" />
                            <select id="part3" name="part3" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                @for ($i = 1; $i <= 87; $i++)
                                    <option value="{{ $i }}" {{ old('part3', 26) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                    </option>
                                    @endfor
                            </select>
                        </div>
                        <x-input-error :messages="$errors->get('numero_immatriculation')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="marque" :value="__('Marque')" />
                        <select id="marque" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="marque" autofocus>
                            <option value="">{{ __('Sélectionner la marque') }}</option>
                            @foreach($marques as $marque)
                            <option value="{{ $marque->marque }}" {{ old('marque') == $marque->marque ? 'selected' : '' }}>
                                {{ $marque->marque }}
                            </option>
                            @endforeach
                            <option value="autre" {{ old('marque') == 'autre' ? 'selected' : '' }}>{{ __('Autre') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('marque')" class="mt-2" />
                    </div>
                    <div id="modeleDev">
                        <x-input-label for="modele" :value="__('Modele')" />
                        <select id="modele" name="modele" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">{{ __('Veuillez d`abord sélectionner une marque') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                    </div>
                    @else
                    <div>
                        <x-input-label for="voiture_id" :value="__('Sélectionnez une voiture')" />
                        <!-- Select Dropdown -->
                        <select id="voiture_id" name="voiture_id" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="" disabled selected>{{ __('Choisissez une voiture') }}</option>
                            @foreach($client->voitures as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('voiture_id') == $vehicle->id ? 'selected' : '' }}>
                                <span class="text-red-500">{{ explode('-', $vehicle->numero_immatriculation)[0] }}</span>-<span dir="rtl">{{ explode('-', $vehicle->numero_immatriculation)[1] }}</span>-<span>{{ explode('-', $vehicle->numero_immatriculation)[2] }} </span> {{ $vehicle->marque }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('voiture_id')" class="mt-2" />
                    </div>
                    @endif
                    <div>
                        <x-input-label for="description" :value="__('Description (Optionnel)')" />
                        <x-text-textarea id="description" class="block mt-1 w-full" name="description" autofocus autocomplete="description">
                            {{ old('description') }}
                        </x-text-textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{route('mechanic.reservation.show', $Appointment)}}" class="flex justify-center rounded-[20px] px-3 py-1.5 text-md font-semibold leading-6 text-gray hover:underline ">
                            {{ __('Ignorer cette étape') }}
                        </a>


                        <x-primary-button class="flex justify-center mx-3 rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            {{ __('ajouter la visit') }}
                        </x-primary-button>

                    </div>
                </form>

            </div>

        </div>
        {{-- contet close colse --}}
        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>
    <script>
        function fetchAndPopulateModeles(marqueId) {
            const modeleWrapper = $('#modeleDev');
            const oldModele = @json(old('modele')); // Safer than Blade in JS string

            if (marqueId && marqueId !== 'autre') {
                // If input exists, replace it with select again
                if ($('#modele').is('input')) {
                    $('#modele').remove();
                    modeleWrapper.append(`
                    <select id="modele" name="modele" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Sélectionner le modèle</option>
                    </select>
                `);
                }

                let modeleSelect = $('#modele');

                $.ajax({
                    url: '/api/modele/' + marqueId,
                    type: 'GET',
                    success: function(data) {
                        modeleSelect.empty();
                        modeleSelect.append('<option value="">Sélectionner le modèle</option>');
                        $.each(data, function(key, modele) {
                            const selected = (modele.modele === oldModele) ? 'selected' : '';
                            modeleSelect.append('<option value="' + modele.modele + '" ' + selected + '>' + modele.modele + '</option>');
                        });

                        const selectedModele = modeleSelect.data('selected');
                        if (selectedModele) {
                            modeleSelect.val(selectedModele);
                        }
                    }
                });
            } else {
                // If select exists, replace it with input
                if ($('#modele').is('select')) {
                    $('#modele').remove();
                    modeleWrapper.append(`<x-text-input type="text" id="modele" name="modele" value="${oldModele ?? ''}" class="block mt-1 w-full" placeholder="Entrer le modèle" />`);
                }
            }
        }

        $(document).ready(function() {
            $('#marque').on('change', function() {
                const selectedMarqueId = $(this).val();
                fetchAndPopulateModeles(selectedMarqueId);
            });

            const initialMarqueId = $('#marque').val();
            if (initialMarqueId) {
                fetchAndPopulateModeles(initialMarqueId);
            }
        });
    </script>
</x-mechanic-app-layout>