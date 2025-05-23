<x-app-layout :subtitle="'Ajouter une voiture'">
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
                                href="{{ route('voiture.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Mes voitures
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
                                href="{{ route('voiture.create') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Ajouter voiture
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
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Ajouter Voiture</h2>
                <form method="POST" action="{{ route('voiture.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
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
                    <!-- 
                    <div>
                        <x-input-label for="modele" :value="__('Modele')" />
                        <x-text-input id="modele" class="block mt-1 w-full" type="text" name="modele" :value="old('modele')" autofocus autocomplete="modele" />
                        <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                    </div> -->
                    <div>
                        <x-input-label for="date_de_première_mise_en_circulation" :value="__('Date de première mise en circulation (Optionnel)')" />
                        <x-text-input id="date_de_première_mise_en_circulation" class="block mt-1 w-full" type="date" name="date_de_première_mise_en_circulation" :value="old('date_de_première_mise_en_circulation')" autofocus autocomplete="date_de_première_mise_en_circulation" />
                        <x-input-error :messages="$errors->get('date_de_première_mise_en_circulation')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_achat" :value="__('Date d\'achat (Optionnel)')" />
                        <x-text-input id="date_achat" class="block mt-1 w-full" type="date" name="date_achat" :value="old('date_achat')" autofocus autocomplete="date_achat" />
                        <x-input-error :messages="$errors->get('date_achat')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="date_de_dédouanement" :value="__('Date de dédouanement (Optionnel)')" />
                        <x-text-input id="date_de_dédouanement" class="block mt-1 w-full" type="date" name="date_de_dédouanement" :value="old('date_de_dédouanement')" autofocus autocomplete="date_de_dédouanement" />
                        <x-input-error :messages="$errors->get('date_de_dédouanement')" class="mt-2" />
                    </div>
                    <div class="shrink-0">
                        <img id='preview_img' class="h-16 w-16 object-cover rounded-full" src="{{session('temp_photo_voiture') ? asset('storage/'.session('temp_photo_voiture')) : asset('./images/defaultimage.jpg')}}" alt="Current profile photo" />
                    </div>
                    <div class="">
                        <label class="block">
                            <x-input-label for="file_input" :value="__('Photo (Optionnel)')" />
                            <x-file-input id="file_input" onchange="loadFile(event)" class="block mt-1 w-full" type="file" name="photo" :value="old('photo')" autofocus autocomplete="photo" accept="image/jpeg,png" />
                            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                            @if(session('temp_photo_voiture'))
                            <input type="hidden" id="inputImage2" name="temp_photo_voiture" value="{{ session('temp_photo_voiture') }}">
                            @endif
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="">
                            {{ __('Ajouter la voiture') }}
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
        var loadFile = function(event) {
            var input = event.target;
            var file = input.files[0];
            var type = file.type;
            var output = document.getElementById('preview_img');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) // free memory
            }
        };



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
</x-app-layout>