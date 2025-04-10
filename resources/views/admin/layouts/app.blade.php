<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />

    <!--  Leaflet's  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <style>
        @media (max-width: 768px) {
            .fc-toolbar-chunk .fc-today-button {
                margin-left: 6px !important;
                /* Add left margin */
                margin-top: 0 !important;
                /* Remove top margin */
            }

            /* Hide the Liste button */
            .fc-toolbar-chunk .fc-listWeek-button {
                display: none !important;
            }
        }

        @media (max-width: 320px) {
            .fc-toolbar-chunk .fc-today-button {
                margin-left: 0 !important;
                /* Add left margin */
                margin-top: 6px !important;
                /* Remove top margin */
            }
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#F1F1F1]">
        @include('admin.layouts.n')
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>
<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

<script>
    function toggleModal(show, form) {
        const modal = document.getElementById(form); // Get the modal element by ID
        if (show) {
            modal.classList.remove('hidden'); // Remove 'hidden' to show the modal
        } else {
            modal.classList.add('hidden'); // Add 'hidden' to hide the modal
        }
    }

    function toggleModalDelete(show) {
        const modal = document.getElementById('confirmationModal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
</script>

</html>