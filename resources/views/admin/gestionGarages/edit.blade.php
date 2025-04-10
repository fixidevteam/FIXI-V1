<x-admin-app-layout>
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
                            href="{{ route('admin.dashboard') }}"
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
                                href="{{ route('admin.gestionGarages.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Gestion des garages
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
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Modifier garage
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
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Modifier Garage</h2>
                <form method="POST" action="{{ route('admin.gestionGarages.update',$garage->id) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" :value="__('Garage')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name') ?? $garage->name" autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="ref" :value="__('Ref')" />
                        <x-text-input id="ref" class="block mt-1 w-full" type="text" name="ref" :value="old('ref') ?? $garage->ref" autofocus autocomplete="ref" />
                        <x-input-error :messages="$errors->get('ref')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="localisation" :value="__('Adresse')" />
                        <x-text-input id="localisation" class="block mt-1 w-full" type="text" name="localisation" :value="old('localisation') ?? $garage->localisation" autofocus autocomplete="localisation" />
                        <x-input-error :messages="$errors->get('localisation')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="ville" :value="__('Ville')" />
                        <select id="ville" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="ville">
                            <option value="" {{ old('ville', $garage->ville) ? '' : 'selected' }}>
                                {{ __('Sélectionnez une Ville') }}
                            </option>
                            @foreach($villes as $ville)
                            <option value="{{ $ville->id }}" @if(old('ville', $garage->ville) == $ville->id || old('ville', $garage->ville) == $ville->ville) selected @endif>
                                {{ $ville->ville }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('ville')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quartier" :value="__('Quartier')" />
                        <select id="quartier" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="quartier">
                            <option value="" selected>
                                {{ __('Sélectionnez un Quartier (Optionnel)') }}
                            </option>
                            @if(old('ville', $garage->ville))
                            @foreach($quartiers as $quartier)
                            <option value="{{ $quartier->quartier }}" {{ old('quartier', $garage->quartier) == $quartier->quartier ? 'selected' : '' }}>
                                {{ $quartier->quartier }}
                            </option>
                            @endforeach
                            @endif
                        </select>
                        <x-input-error :messages="$errors->get('quartier')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="confirmation" :value="__('Confirmation')" />
                        <select id="confirmation" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="confirmation">
                            <option value="automatique" {{ old('confirmation', $garage->confirmation) === 'automatique' ? 'selected' : '' }}>Automatique</option>
                            <option value="manuelle" {{ old('confirmation', $garage->confirmation) === 'manuelle' ? 'selected' : '' }}>Manuelle</option>
                        </select>
                        <x-input-error :messages="$errors->get('confirmation')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="services" :value="__('Domaines')" />
                        <div class="mt-2 space-y-2">
                            @foreach($domains as $domain)
                            <div class="domain-group">
                                <label class="flex items-center">
                                    <input type="checkbox"
                                        name="domaines[]"
                                        value="{{ $domain->domaine }}"
                                        class="domain-checkbox w-4 h-4 text-black border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                        {{ in_array($domain->domaine, old('domaines', $garage->domaines ?? [])) ? 'checked' : '' }} />
                                    <span class="ml-2 text-sm text-gray-600 font-medium">{{ $domain->domaine }}</span>
                                </label>

                                <div class="services-container ml-6 mt-1 space-y-1" style="display: none;">
                                    @foreach($domain->services as $service)
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                            name="services[]"
                                            value="{{ $service->service }}"
                                            class="service-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                            {{ in_array($service->service, old('services', $garage->services ?? [])) ? 'checked' : '' }} />
                                        <span class="ml-2 text-sm text-gray-600">{{ $service->service }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <h3>📞 Coordonnées : </h3>
                    <div>
                        <x-input-label for="telephone" :value="__('Téléphone mobile')" />
                        <x-text-input id="telephone" class="block mt-1 w-full" type="text" name="telephone" :value="old('telephone') ?? $garage->telephone" autofocus autocomplete="telephone" />
                        <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="fixe" :value="__('Téléphone fixe')" />
                        <x-text-input id="fixe" class="block mt-1 w-full" type="text" name="fixe" :value="old('fixe') ?? $garage->fixe" autofocus autocomplete="fixe" />
                        <x-input-error :messages="$errors->get('fixe')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="whatsapp" :value="__('Téléphone whatsapp')" />
                        <x-text-input id="whatsapp" class="block mt-1 w-full" type="text" name="whatsapp" :value="old('whatsapp') ?? $garage->whatsapp" autofocus autocomplete="whatsapp" />
                        <x-input-error :messages="$errors->get('whatsapp')" class="mt-2" />
                    </div>

                    <h3>🌐 Réseaux sociaux :</h3>
                    <div>
                        <x-input-label for="instagram" :value="__('Instagram')" />
                        <x-text-input id="instagram" class="block mt-1 w-full" type="text" name="instagram" :value="old('instagram') ?? $garage->instagram" autofocus autocomplete="instagram" />
                        <x-input-error :messages="$errors->get('instagram')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="facebook" :value="__('Facebook')" />
                        <x-text-input id="facebook" class="block mt-1 w-full" type="text" name="facebook" :value="old('facebook') ?? $garage->facebook" autofocus autocomplete="facebook" />
                        <x-input-error :messages="$errors->get('facebook')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="tiktok" :value="__('TikTok')" />
                        <x-text-input id="tiktok" class="block mt-1 w-full" type="text" name="tiktok" :value="old('tiktok') ?? $garage->tiktok" autofocus autocomplete="tiktok" />
                        <x-input-error :messages="$errors->get('tiktok')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="linkedin" :value="__('LinkedIn')" />
                        <x-text-input id="linkedin" class="block mt-1 w-full" type="text" name="linkedin" :value="old('linkedin') ?? $garage->linkedin" autofocus autocomplete="linkedin" />
                        <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="virtualGarage" :value="__('Garage virtual')" />
                        <x-text-input id="virtualGarage" class="block mt-1 w-full" type="text" name="virtualGarage" :value="old('virtualGarage') ?? $garage->virtualGarage" autofocus autocomplete="virtualGarage" />
                        <x-input-error :messages="$errors->get('virtualGarage')" class="mt-2" />
                    </div>

                    <h3>📍 Localisation géographique </h3>
                    <div>
                        <x-input-label for="latitude" :value="__('Latitude')" />
                        <x-text-input id="latitude" class="block mt-1 w-full" type="text" name="latitude" :value="old('latitude') ?? $garage->latitude" autofocus autocomplete="latitude" />
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="longitude" :value="__('Longitude')" />
                        <x-text-input id="longitude" class="block mt-1 w-full" type="text" name="longitude" :value="old('longitude') ?? $garage->longitude" autofocus autocomplete="longitude" />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="presentation" :value="__('Présentation du garage')" />
                        <x-text-textarea name="presentation" id="presentation" placeholder="Presentation du garage">
                            {{ old('presentation') ?? $garage->presentation }}
                        </x-text-textarea>
                        <x-input-error :messages="$errors->get('presentation')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="flex justify-center rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            {{ __('Modifier le garage') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const villeSelect = document.getElementById('ville');
    const quartierSelect = document.getElementById('quartier');

    function fetchQuartiers(villeId, preSelectedQuartier = null) {
        if (!villeId) {
            quartierSelect.innerHTML = '<option value="">{{ __("Sélectionnez un Quartier (Optionnel)") }}</option>';
            return;
        }

        quartierSelect.innerHTML = '<option value="">{{ __("Chargement...") }}</option>';

        fetch(`/quartiers?ville_id=${villeId}`)
            .then(response => response.json())
            .then(data => {
                quartierSelect.innerHTML = '<option value="">{{ __("Sélectionnez un quartier (Optionnel)") }}</option>';
                data.forEach(quartier => {
                    const option = new Option(quartier.quartier, quartier.quartier);
                    if (preSelectedQuartier && quartier.quartier === preSelectedQuartier) {
                        option.selected = true;
                    }
                    quartierSelect.add(option);
                });
            })
            .catch(error => {
                console.error('Error fetching quartiers:', error);
                quartierSelect.innerHTML = '<option value="">{{ __("Erreur de chargement") }}</option>';
            });
    }

    // Handle ville selection change
    villeSelect.addEventListener('change', function() {
        fetchQuartiers(this.value);
    });

    // Initialize on page load
    const initialVilleId = villeSelect.value;
    const initialQuartier = "{{ old('quartier', $garage->quartier) }}";
    
    if (initialVilleId) {
        fetchQuartiers(initialVilleId, initialQuartier);
        }
    });

        // ------------
        document.addEventListener('DOMContentLoaded', function() {
            // Handle domain checkbox changes
            document.querySelectorAll('.domain-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const servicesContainer = this.closest('.domain-group').querySelector('.services-container');
                    const serviceCheckboxes = servicesContainer.querySelectorAll('.service-checkbox');

                    // Show/hide services
                    if (this.checked) {
                        servicesContainer.style.display = 'block';
                    } else {
                        servicesContainer.style.display = 'none';
                        // Uncheck all services when domain is unchecked
                        serviceCheckboxes.forEach(serviceCheckbox => {
                            serviceCheckbox.checked = false;
                        });
                    }
                });

                // Initialize visibility based on checked state
                if (checkbox.checked) {
                    checkbox.closest('.domain-group').querySelector('.services-container').style.display = 'block';
                }
            });

            // Optional: If you want to check domain when any of its services is checked
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const domainCheckbox = this.closest('.domain-group').querySelector('.domain-checkbox');
                    if (this.checked && !domainCheckbox.checked) {
                        domainCheckbox.checked = true;
                        domainCheckbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        });
    </script>
</x-admin-app-layout>