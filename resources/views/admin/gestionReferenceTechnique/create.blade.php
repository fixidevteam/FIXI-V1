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
                                href="{{ route('admin.gestionMarque.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Gestion des marques du voiture
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
                                href="{{ route('admin.gestionReferenceTechnique.create') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Ajouter reference technique
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
            <h2 class="mt-10 text-2xl font-bold leading-9 tracking-tight text-gray-900">Ajouter une reference technique</h2>
            <form method="POST" action="{{ route('admin.gestionReferenceTechnique.store') }}" class="space-y-6">
                @csrf
                <div>
                    <x-input-label for="modele" :value="__('Modele')" />
                    <select id="modele" name="modele_id" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @foreach ($modeles as $modele)
                        <option value="{{ $modele->id }}" @if(old('modele_id')==$modele->id) selected @endif >{{ $modele->modele }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('modele_id')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="reference_technique" :value="__('Reference technique')" />
                    <x-text-input id="reference_technique" class="block mt-1 w-full" type="text" name="reference_technique" :value="old('reference_technique')" autofocus autocomplete="reference_technique" />
                    <x-input-error :messages="$errors->get('reference_technique')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="motorisation" :value="__('Motorisation')" />
                    <select id="motorisation" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="motorisation">
                        <option value="diesel" {{old('motorisation') === 'diesel' ? 'selected':''}}>Diesel</option>
                        <option value="essence" {{old('motorisation') === 'essence' ? 'selected':''}}>Essence</option>
                        <option value="hybride" {{old('motorisation') === 'hybride' ? 'selected':''}}>Hybride</option>
                        <option value="electrique" {{old('motorisation') === 'electrique' ? 'selected':''}}>Électrique</option>
                    </select>
                    <x-input-error :messages="$errors->get('motorisation')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="boite_vitesse" :value="__('Boite vitesse')" />
                    <select id="boite_vitesse" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" name="boite_vitesse">
                        <option value="automatique" {{old('boite_vitesse') === 'automatique' ? 'selected':''}}>Automatique</option>
                        <option value="manuelle" {{old('boite_vitesse') === 'manuelle' ? 'selected':''}}>Manuelle</option>
                    </select>
                    <x-input-error :messages="$errors->get('boite_vitesse')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="puissance_thermique" :value="__('Puissance thermique')" />
                    <x-text-input id="puissance_thermique" class="block mt-1 w-full" type="number" name="puissance_thermique" :value="old('puissance_thermique')" autofocus autocomplete="puissance_thermique" />
                    <x-input-error :messages="$errors->get('puissance_thermique')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="puissance_fiscale" :value="__('Puissance fiscale')" />
                    <x-text-input id="puissance_fiscale" class="block mt-1 w-full" type="number" name="puissance_fiscale" :value="old('puissance_fiscale')" autofocus autocomplete="puissance_fiscale" />
                    <x-input-error :messages="$errors->get('puissance_fiscale')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="cylindree" :value="__('Cylindree')" />
                    <x-text-input id="cylindree" class="block mt-1 w-full" type="number" name="cylindree" :value="old('cylindree')" autofocus autocomplete="cylindree" step="0.1"/>
                    <x-input-error :messages="$errors->get('cylindree')" class="mt-2" />
                </div>
            
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="flex justify-center rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                        {{ __('Ajouter Reference Technique') }}
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
</x-admin-app-layout>