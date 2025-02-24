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
                                href="{{ route('admin.gestionReservations.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                Gestion des reservations
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
                    <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Liste des garages</h2>
                </div>
                {{-- Search Form --}}
                <form action="{{ route('admin.gestionReservations.index') }}" method="GET" class="flex items-center w-full mx-auto">
                    <select name="garage_ref" id="garage_ref" onchange="this.form.submit()" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option value="">Tous les Garages</option>
                        @foreach ($garages as $garage)
                            <option value="{{ $garage->ref }}" {{ request('garage_ref') == $garage->ref ? 'selected' : '' }}>
                                {{ $garage->name }} ({{ $garage->ref }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="p-2.5 ms-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800">
                        <svg class="w-4 h-4" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75ZM1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5C21.75 14.0605 20.8111 16.4017 19.2589 18.1982L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L18.1982 19.2589C16.4017 20.8111 14.0605 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5Z" fill="currentColor"/>
                        </svg>                            
                        <span class="sr-only">Search</span>
                    </button>
                    {{-- Reset Button --}}
                    <a href="{{ route('admin.gestionReservations.index') }}" class="hidden md:block p-2.5 ms-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300">
                        Réinitialiser
                    </a>
                    <a href="{{ route('admin.gestionReservations.index') }}" class="md:hidden p-2.5 ms-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 font-semibold">
                        <svg class="w-4 h-4" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75ZM1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5C21.75 14.0605 20.8111 16.4017 19.2589 18.1982L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L18.1982 19.2589C16.4017 20.8111 14.0605 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5ZM8.25 11.5C8.25 11.0858 8.58579 10.75 9 10.75H14C14.4142 10.75 14.75 11.0858 14.75 11.5C14.75 11.9142 14.4142 12.25 14 12.25H9C8.58579 12.25 8.25 11.9142 8.25 11.5Z" fill="currentColor"/>
                        </svg>                            
                        <span class="sr-only">Réinitialiser Search</span>
                    </a>
                </form>

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
                        x-init="setTimeout(() => show = false, 3000)" 
                        >
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
                                    class="size-6"
                                    >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                    </svg>
                                    @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6.25C12.4142 6.25 12.75 6.58579 12.75 7V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V7C11.25 6.58579 11.5858 6.25 12 6.25Z" fill="currentColor"/>
                                        <path d="M12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="currentColor"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 6.89137 17.1086 2.75 12 2.75Z" fill="currentColor"/>
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
                     <!-- Calendar Container -->
                    <div id="calendar"></div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
        
            // Check screen width for responsive view options
            var isMobile = window.innerWidth < 768;
        
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr', // Set calendar language to French
                initialView: isMobile ? 'listWeek' : 'dayGridMonth', // Responsive initial view
                headerToolbar: isMobile ? {
                    left: 'prev,next today',
                    center: '',
                    right: 'listWeek'
                } : {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: "Aujourd’hui",
                    month: "Mois",
                    week: "Semaine",
                    day: "Jour",
                    list: "Liste",
                    next: "Suivant",
                    prev: "Précédent"
                },
                events: @json($events),
                eventTimeFormat: { // 24-hour format for event times
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                nowIndicator: true, // Show the current time indicator
            });
        
            calendar.render();
        
            // Optional: Reload on window resize to adjust toolbar layout
            window.addEventListener('resize', function() {
                var newIsMobile = window.innerWidth < 768;
                if (newIsMobile !== isMobile) {
                    location.reload();
                }
            });
        });
        </script>
</x-admin-app-layout>