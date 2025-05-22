@props(['subtitle' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $subtitle ?? '' }} | FIXI PRO</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/faviconpro.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <!-- flatpickr -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
        
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-[#F1F1F1]">
        @include('mechanic.layouts.n')
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const operationSelect = document.getElementById('operation');
            const newOperationWrapper = document.getElementById('newOperationWrapper');
            const newOperationInput = document.getElementById('new_operation');

            const initialCategorie = document.getElementById('categorie').value;

            if (initialCategorie) {
                loadOperations(initialCategorie); // Load operations based on the initial category
            }

            document.getElementById('categorie').addEventListener('change', function() {
                loadOperations(this.value); // Refresh operations when category changes
            });

            operationSelect.addEventListener('change', function() {
                if (this.value === 'autre') {
                    // Show the custom operation input
                    newOperationWrapper.classList.remove('hidden');
                } else {
                    // Hide the custom operation input
                    newOperationWrapper.classList.add('hidden');
                    newOperationInput.removeAttribute('required');
                    newOperationInput.value = ''; // Clear the input field
                }

                if (this.value) {
                    loadsousOperations(this.value); // Load sous-operations when an operation is selected
                } else {
                    clearSousOperations(); // Clear checkboxes if no operation is selected
                }
            });
        });


        function loadOperations(categorieId) {
            const existingOperationElement = document.getElementById('existingOperationId');
            const existingOperationId = existingOperationElement ? existingOperationElement.value : "";

            if (categorieId) {
                fetch(`/api/operations/${categorieId}`)
                    .then(response => response.json())
                    .then(data => {
                        const operationSelect = document.getElementById('operation');
                        operationSelect.innerHTML = '<option value="">Select catégorie</option>';

                        data.forEach(operation => {
                            const option = document.createElement('option');
                            option.value = operation.id;
                            option.textContent = operation.nom_operation;

                            // Auto-select the existing operation if it matches
                            if (operation.id == existingOperationId) {
                                option.selected = true;
                            }

                            operationSelect.appendChild(option);
                        });
                        // Add the static "Autre" option
                        const autreOption = document.createElement('option');
                        autreOption.value = 'autre';
                        autreOption.textContent = 'Autre';
                        if (existingOperationId === 'autre') {
                            autreOption.selected = true;
                            newOperationWrapper.classList.remove('hidden');
                        }
                        operationSelect.appendChild(autreOption);

                        // Load sous-operations if an operation is selected
                        if (operationSelect.value) {
                            loadsousOperations(operationSelect.value);
                        } else {
                            clearSousOperations();
                        }
                    })
                    .catch(error => console.error("Error loading operations:", error));
            } else {
                clearSousOperations();
            }
        }

        function loadsousOperations(operationId) {
            // Parse existingSousOperations safely and normalize to integers
            const existingSousOperations = JSON.parse(document.getElementById('existingSousOperations')?.value || '[]').map(Number);
            const sousOperationContainer = document.getElementById('sousOperationCheckboxes');
            sousOperationContainer.innerHTML = ""; // Clear existing checkboxes

            if (operationId) {
                fetch(`/api/sous-operations/${operationId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const p = document.createElement('p');
                            p.textContent = 'Sous opération';
                            p.className = 'block text-sm font-medium leading-6 text-gray-900';
                            sousOperationContainer.appendChild(p);

                            data.forEach(sousOperation => {
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.id = `sousOperation_${sousOperation.id}`;
                                checkbox.name = 'sousOperations[]';
                                checkbox.value = sousOperation.id;
                                checkbox.className = 'w-4 h-4 text-black border-gray-300 rounded focus:ring-blue-500 focus:ring-2';

                                // Mark checkbox as checked if sousOperation.id exists in existingSousOperations
                                if (existingSousOperations.includes(Number(sousOperation.id))) {
                                    checkbox.checked = true;
                                }

                                const label = document.createElement('label');
                                label.htmlFor = `sousOperation_${sousOperation.id}`;
                                label.textContent = sousOperation.nom_sous_operation;
                                label.className = 'ms-2 text-gray-900 text-sm font-medium';

                                const checkboxWrapper = document.createElement('div');
                                checkboxWrapper.appendChild(checkbox);
                                checkboxWrapper.appendChild(label);

                                sousOperationContainer.appendChild(checkboxWrapper);
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Error loading sous-operations:", error);
                    });
            }
        }

        function clearSousOperations() {
            document.getElementById('sousOperationCheckboxes').innerHTML = ""; // Clear sous-operations container
        }
        // copy garage ref logique
        document.getElementById('garage-ref').addEventListener('click', function() {
            const refText = this.textContent.trim(); // Get the text content
            const copyIcon = document.getElementById('copy-icon');
            const checkIcon = document.getElementById('check-icon');

            navigator.clipboard.writeText(refText) // Copy to clipboard
                .then(() => {
                    // Toggle icons
                    copyIcon.classList.add('hidden');
                    checkIcon.classList.remove('hidden');

                    // Revert back to copy icon after 3 seconds
                    setTimeout(() => {
                        copyIcon.classList.remove('hidden');
                        checkIcon.classList.add('hidden');
                    }, 3000);
                })
                .catch(err => {
                    console.error('Error copying text: ', err);
                });
        });
    </script>
</body>

</html>