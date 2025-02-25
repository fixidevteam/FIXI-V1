<x-app-layout :subtitle="'Détails de RDV'">
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
                                href=""
                                class="inline-flex items-center text-sm font-medium text-gray-700">
                                Modifier le Rendez-vous
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            {{-- content (slot on layouts/app.blade.php)--}}
            <div class=" px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-between items-center my-6">
                    <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Modifier le rendez-vous</h2>
                </div>
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
                    {{-- alert close --}}
                    <div>
                        <form method="POST" action="{{route('RDV.update',$appointment->id)}}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div>
                                <x-input-label for="note" :value="__('Garage')" />
                                <x-text-input id="garage" class="block bg-gray-200 mt-1 w-full cursor-not-allowed" type="text" name="garage" :value="old('garage') ?? $garage->name" autofocus autocomplete="garage" disabled readonly />
                                <x-input-error :messages="$errors->get('garage')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="note" :value="__('Garage ref')" />
                                <x-text-input id="garage_ref" class="block bg-gray-200 mt-1 w-full cursor-not-allowed " type="text" name="garage_ref" :value="old('garage_ref') ?? $garage->ref" autofocus autocomplete="garage_ref" disabled readonly />
                                <x-input-error :messages="$errors->get('garage_ref')" class="mt-2" />
                            </div>
                            <div class="flex justify-center ">
                                <hr class="h-[2px]  bg-gray-200 border-0 w-[90%]">
                            </div>
                            <div id="step1" class="step">
                                <label
                                    for="datePicker"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Selecter la date:
                                </label>
                                <input
                                    type="text"
                                    id="datePicker"
                                    class="block mt-1 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    placeholder="Choose a date"
                                    name="appointment_day"
                                    readonly />
                            </div>
                            <div id="step2" class="step">
                                <div id="times" class="mt-6 grid grid-cols-2 gap-4">
                                    <h3 class='text-lg font-medium text-gray-900 mb-4'>Sélectionnez une heure : <x-input-error :messages="$errors->get('appointment_time')" class="mt-2" /></h3>
                                </div>
                                <div id="timesbtn" class="flex grid grid-cols-2 gap-4">
                                </div>

                                <input type="hidden" name="appointment_time" id="input-time" value="{{old('appointment_time') ?? $appointment->appointment_time}}">
                            </div>

                            <div>
                                <x-input-label for="categorie" :value="__('Categorie de l\'opération')" />
                                <select id="categorie" name="categorie_de_service" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="Services d'un garage mécanique" {{ old('categorie_de_service', $appointment->categorie_de_service) == "Services d'un garage mécanique" ? 'selected' : '' }}>
                                        Services d'un garage mécanique
                                    </option>
                                    <option value="Services d'un garage de lavage" {{ old('categorie_de_service', $appointment->categorie_de_service) == "Services d'un garage de lavage" ? 'selected' : '' }}>
                                        Services d'un garage de lavage
                                    </option>
                                    <option value="Services d'un garage de carrosserie" {{ old('categorie_de_service', $appointment->categorie_de_service) == "Services d'un garage de carrosserie" ? 'selected' : '' }}>
                                        Services d'un garage de carrosserie
                                    </option>
                                    <option value="Services d'un garage pneumatique" {{ old('categorie_de_service', $appointment->categorie_de_service) == "Services d'un garage pneumatique" ? 'selected' : '' }}>
                                        Services d'un garage pneumatique
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('categorie')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="note" :value="__('Modele')" />
                                <x-text-input id="modele" class="block mt-1 w-full" type="text" name="modele" :value="old('modele') ?? $appointment->modele" autofocus autocomplete="modele" />
                                <x-input-error :messages="$errors->get('modele')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="note" :value="__('Objectif de rendez-vous')" />
                                <x-text-input id="objet_du_RDV" class="block mt-1 w-full" type="text" name="objet_du_RDV" :value="old('objet_du_RDV') ?? $appointment->objet_du_RDV_du_RDV" autofocus autocomplete="objet_du_RDV" />
                                <x-input-error :messages="$errors->get('objet_du_RDV')" class="mt-2" />
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button class="">
                                    {{ __('Modifier le Rendez-vous') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Loading Spinner -->
        <div
            id="loading-spinner"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
            <div
                class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-red-600"></div>
        </div>

        {{-- contet close colse --}}
        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>

    <script>
        let availableDates = [];
        let disabledDates = [];
        let selectedDate = `{{old('appointment_day') ?? $appointment->appointment_day}}`;
        let selectedTime = "";
        let inputTime = document.getElementById("input-time");
        fetchAvailableDates();
        fetchTimeSlots(selectedDate);
        // Function to show error messages
        function showError(message) {
            const errorMessageDiv = document.getElementById("error-message");
            errorMessageDiv.innerText = message;
            errorMessageDiv.classList.remove("hidden");
        }
        // Function to clear error messages
        function clearError() {
            const errorMessageDiv = document.getElementById("error-message");
            errorMessageDiv.innerText = "";
            errorMessageDiv.classList.add("hidden");
        }
        // Function to show the loading spinner
        function showLoading() {
            document.getElementById("loading-spinner").classList.remove("hidden");
        }
        // Function to hide the loading spinner
        function hideLoading() {
            document.getElementById("loading-spinner").classList.add("hidden");
        }

        function fetchAvailableDates() {
            showLoading(); // Show spinner
            fetch(`http://localhost:8000/api/available-dates?garage_ref={{$garage->ref}}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.available_dates && data.available_dates.length > 0) {
                        availableDates = data.available_dates;
                        disabledDates = data.unavailable_dates;
                        initDatePicker();
                    } else {
                        showError("Aucune date disponible pour ce garage.");
                    }
                })
                .catch((error) => {
                    console.error("Error fetching available dates:", error);
                    showError("Échec du chargement des dates disponibles.");
                })
                .finally(() => {
                    hideLoading(); // Hide spinner
                });
        }

        // Fetch time slots for the selected date

        function initDatePicker() {
            flatpickr("#datePicker", {
                dateFormat: "Y-m-d",
                defaultDate: "{{old('appointment_day') ?? $appointment->appointment_day}}",
                enable: [
                    function(date) {
                        // Use flatpickr's formatDate to avoid timezone issues
                        const formattedDate = flatpickr.formatDate(date, "Y-m-d");

                        // Enable only if the date is in availableDates AND NOT in disabledDates
                        return (
                            availableDates.includes(formattedDate) &&
                            !disabledDates.includes(formattedDate)
                        );
                    },
                ],
                onChange: function(selectedDates, dateStr) {
                    selectedDate = dateStr;
                    fetchTimeSlots(selectedDate);
                    inputTime.value = '';
                    // Fetch available time slots when a date is selected
                },
            });
        }

        function fetchTimeSlots(date) {
            showLoading(); // Show spinner
            fetch(
                    `http://localhost:8000/api/time-slots?garage_ref={{$garage->ref}}&date=${date}`
                )
                .then((response) => response.json())
                .then((data) => {
                    let timesDiv = document.getElementById("times");
                    let timesDivBtn = document.getElementById("timesbtn");
                    if (data.time_slots.length === 0) {
                        timesDiv.innerHTML = +
                            "<p class='text-red-600'>Aucune plage horaire disponible pour ce jour.</p>";
                    } else {
                        timesDivBtn.innerHTML = "";
                        if (date == `{{$appointment->appointment_day}}`) {
                            let btn = document.createElement("button");
                            btn.innerText = `{{$appointment->appointment_time}}`;
                            btn.type = 'button';
                            btn.classList.add(
                                "p-2.5",
                                "text-sm",
                                "font-medium",
                                "text-center",
                                "bg-white",
                                "border",
                                "rounded-[20px]",
                                "cursor-pointer",
                                "!text-red-600",
                                "!border-red-600",
                                "!hover:text-white",
                                "!hover:bg-red-600",
                                "!bg-red-700",
                                "!text-white"
                            );
                            btn.onclick = () => {
                                document
                                    .querySelectorAll("#timesbtn button")
                                    .forEach((button) => {
                                        button.classList.remove(
                                            "!bg-red-700",
                                            "!text-white"
                                        );
                                    });
                                btn.classList.add("!bg-red-700", "!text-white");
                                selectedTime = `{{$appointment->appointment_time}}`;
                                inputTime.value = `{{$appointment->appointment_time}}`;
                            };
                            timesDivBtn.appendChild(btn);
                        }
                        data.time_slots.forEach((time) => {
                            let btn = document.createElement("button");
                            btn.innerText = time;
                            btn.type = 'button';
                            btn.classList.add(
                                "p-2.5",
                                "text-sm",
                                "font-medium",
                                "text-center",
                                "bg-white",
                                "border",
                                "rounded-[20px]",
                                "cursor-pointer",
                                "!text-red-600",
                                "!border-red-600",
                                "!hover:text-white",
                                "!hover:bg-red-600",

                            );
                            btn.onclick = () => {
                                document
                                    .querySelectorAll("#timesbtn button")
                                    .forEach((button) => {
                                        button.classList.remove(
                                            "!bg-red-700",
                                            "!text-white"
                                        );
                                    });
                                btn.classList.add("!bg-red-700", "!text-white");
                                selectedTime = time;
                                inputTime.value = time;

                            };

                            timesDivBtn.appendChild(btn);
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error fetching time slots:", error);
                    showError("Échec du chargement des créneaux horaires.");
                })
                .finally(() => {
                    hideLoading(); // Hide spinner
                });
        }
    </script>
</x-app-layout>