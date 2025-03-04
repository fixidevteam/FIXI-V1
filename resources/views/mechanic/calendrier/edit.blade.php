<x-mechanic-app-layout :subtitle="'Modifier Paramètres'">
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
                                href="{{ route('mechanic.calendrier.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Planning de travail
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
                                Modifier Planning de travail
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
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Modifier Planning de travail</h2>
                <form method="POST" action="{{ route('mechanic.calendrier.update',$schedule->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                
                    <div>
                        <x-input-label for="available_day" :value="__('Jour disponible')" />
                        <select id="available_day" name="available_day" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 cursor-not-allowed" disabled readonly>
                            <option value="">Choisissez un jour</option>
                            @foreach ($daysOfWeek as $key => $value)
                                <option value="{{ $key }}" {{ old('available_day', $schedule->available_day) == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('available_day')" class="mt-2" />
                    </div>
                
                    <div>
                        <x-input-label for="available_from" :value="__('Heure de début')" />
                        <x-text-input 
                            id="available_from" 
                            class="block mt-1 w-full cursor-not-allowed" 
                            type="time" 
                            name="available_from" 
                            :value="old('available_from') ?? $schedule->available_from" 
                            disabled readonly
                        />
                        <x-input-error :messages="$errors->get('available_from')" class="mt-2" />
                    </div>
                
                    <div>
                        <x-input-label for="available_to" :value="__('Heure de fin')" />
                        <x-text-input 
                            id="available_to" 
                            class="block mt-1 w-full cursor-not-allowed" 
                            type="time" 
                            name="available_to" 
                            :value="old('available_to', $schedule->available_to)" 
                            disabled readonly
                        />
                        <x-input-error :messages="$errors->get('available_to')" class="mt-2" />
                    </div>
                
                    <div>
                        <h3 class="font-semibold text-lg">Indisponibilités (Facultatif)</h3>
                        <div id="unavailableTimes" class="space-y-6">
                            @foreach ($unavailableTimes as $unavailable)
                                <div class="unavailable-time mt-2 flex gap-2 items-center">
                                    <input type="hidden" name="unavailable_ids[]" value="{{ $unavailable->id }}">
                                    <input type="time" name="unavailable_from[]" value="{{ $unavailable->unavailable_from }}" class="unavailable_from rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600 block w-full">
                                    <input type="time" name="unavailable_to[]" value="{{ $unavailable->unavailable_to }}" class="unavailable_to rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600 block w-full">
                                    <button type="button" class="remove-unavailable text-red-600 hover:text-red-800 px-2">
                                        ❌
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="addUnavailable" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                            + Ajouter une autre indisponibilité
                        </button>
                    </div>
                
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            {{ __('Modifier calendrier') }}
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
            div.classList.add('unavailable-time', 'mt-2', 'flex', 'gap-2', 'items-center');
            div.innerHTML = `
                <input type="hidden" name="unavailable_ids[]" value="">
                <input type="time" name="unavailable_from[]" class="rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600 block w-full">
                <input type="time" name="unavailable_to[]" class="rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 focus:ring-2 focus:ring-indigo-600 block w-full">
                <button type="button" class="remove-unavailable text-red-600 hover:text-red-800 px-2">❌</button>
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
</x-mechanic-app-layout>