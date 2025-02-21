<x-admin-app-layout :subtitle="'Ajouter calendrier'">
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
                                href="{{ route('admin.gestionCalendrier.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Calendrier des rendez-vous
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
                                Ajouter Calendrier
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
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Ajouter Calendrier pour {{ $garage->name }}</h2>
                <form method="POST" action="{{ route('admin.gestionCalendrier.store',$garage->id) }}" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="available_day" :value="__('Jour disponible:')" />
                        <select id="available_day" name="available_day[]" multiple class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            @foreach ($daysOfWeek as $key => $value)
                            <option value="{{ $key }}" {{ in_array($key, old('available_day', [])) ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('available_day')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="available_from" :value="__('Heure de début:')" />
                        <x-text-input 
                            id="available_from" 
                            class="block mt-1 w-full" 
                            type="time" 
                            name="available_from" 
                            :value="old('available_from')" 
                            autofocus 
                            autocomplete="available_from" 
                        />
                        <x-input-error :messages="$errors->get('available_from')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="available_to" :value="__('Heure de fin:')" />
                        <x-text-input 
                            id="available_to" 
                            class="block mt-1 w-full" 
                            type="time" 
                            name="available_to" 
                            :value="old('available_to')" 
                            autofocus 
                            autocomplete="available_to" 
                        />
                        <x-input-error :messages="$errors->get('available_to')" class="mt-2" />
                    </div>
                    <div>
                        {{-- Unavailability Section --}}
                        <div>
                            <div>
                                <h3 class="font-semibold">Indisponibilités (Facultatif)</h3>
                                <div id="unavailableTimes">
                                    @if (old('unavailable_from'))
                                        @foreach (old('unavailable_from') as $key => $unavailable_from)
                                            <div class="flex gap-4 unavailable-item">
                                                <div class="w-full">
                                                    <label class="block text-sm font-medium text-gray-700">Début :</label>
                                                    <input type="time" name="unavailable_from[]" value="{{ $unavailable_from }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <div class="w-full">
                                                    <label class="block text-sm font-medium text-gray-700">Fin :</label>
                                                    <input type="time" name="unavailable_to[]" value="{{ old('unavailable_to.'.$key) }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <button type="button" class="remove-unavailable text-red-600 mt-7">❌</button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            <button type="button" id="addUnavailable" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">+ Ajouter indisponibilité</button>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="flex justify-center rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm  focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            {{ __('ajouter calendrier') }}
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
        document.getElementById('addUnavailable').addEventListener('click', function () {
            let div = document.createElement('div');
            div.classList.add('flex', 'gap-4', 'my-4','unavailable-item');
            div.innerHTML = `
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Début :</label>
                    <input type="time" name="unavailable_from[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="w-full">
                    <label class="block text-sm font-medium text-gray-700">Fin :</label>
                    <input type="time" name="unavailable_to[]" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <button type="button" class="remove-unavailable text-red-600 mt-7">❌</button>
            `;

            document.getElementById('unavailableTimes').appendChild(div);

            div.querySelector('.remove-unavailable').addEventListener('click', function () {
                div.remove();
            });
        });

        document.querySelectorAll('.remove-unavailable').forEach(button => {
            button.addEventListener('click', function () {
                this.parentElement.remove();
            });
        });
    </script>
</x-admin-app-layout>