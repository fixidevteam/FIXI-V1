<x-mechanic-app-layout :subtitle="'calendrier'">

    <style>
        /* Calendar Grid - Responsive */
        #calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }

        @media (max-width: 1024px) {
            #calendar {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 640px) {
            #calendar {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Day Box Styling */
        .day {
            padding: 12px;
            background: #f9f9f9;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .day:hover {
            transform: scale(1.002);
            background: #f0f0f0;
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Status Labels */
        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 2px;
        }

        .status-done {
            background: #10b981;
            /* Green */
        }

        .status-not-done {
            background: rgb(239, 179, 68);
            /* Red */
        }

        .status-cancelled {
            background: red;
        }

        /* Dots Container */
        .dots-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4px;
            margin-top: 8px;
        }

        .dots-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
    </style>

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
                                href=""
                                class="inline-flex items-center text-sm font-medium text-gray-700   ">
                                agenda
                            </a>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>


        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg ">
            <div class="flex px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">{{ __('agenda ') }}</h2>
            </div>
        </div>
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg">
            <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-300">

                <!-- Month & Year Navigation -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <!-- Month Selector -->
                    <select id="monthSelect" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></select>

                    <!-- Year Selector -->
                    <select id="yearSelect" class="block mt-1 w-full rounded-md border-0 py-1.5 text-sm text-gray-900  shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></select>
                </div>

                <!-- Calendar Grid -->
                <div id="calendar"></div>
            </div>
        </div>



    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const calendarEl = document.getElementById("calendar");
            const monthSelect = document.getElementById("monthSelect");
            const yearSelect = document.getElementById("yearSelect");

            let currentDate = new Date();

            // Populate months
            const monthNames = [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ];

            monthNames.forEach((month, index) => {
                let option = document.createElement("option");
                option.value = index;
                option.textContent = month;
                monthSelect.appendChild(option);
            });

            // Populate years (last 5 years + next 10 years)
            let currentYear = currentDate.getFullYear();
            for (let i = currentYear - 5; i <= currentYear + 10; i++) {
                let option = document.createElement("option");
                option.value = i;
                option.textContent = i;
                yearSelect.appendChild(option);
            }

            // Set initial values
            monthSelect.value = currentDate.getMonth();
            yearSelect.value = currentDate.getFullYear();

            // Fetch reservations for the selected month and year
            async function fetchReservations(year, month) {
                try {
                    const response = await fetch(`/fixi-pro/api/reservations/${year}/${month + 1}`);
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error("Error fetching reservations:", error);
                    return [];
                }
            }

            // Render the calendar with reservations
            async function renderCalendar() {
                calendarEl.innerHTML = "";
                let selectedYear = parseInt(yearSelect.value);
                let selectedMonth = parseInt(monthSelect.value);

                let firstDay = new Date(selectedYear, selectedMonth, 1);
                let lastDay = new Date(selectedYear, selectedMonth + 1, 0);

                // Fetch reservations for the selected month and year
                const reservations = await fetchReservations(selectedYear, selectedMonth);

                // Group reservations by day
                const reservationsByDay = {};
                reservations.forEach(reservation => {
                    const day = new Date(reservation.appointment_day).getDate(); // Extract day from date string
                    if (!reservationsByDay[day]) {
                        reservationsByDay[day] = [];
                    }
                    reservationsByDay[day].push(reservation);
                });

                // Create empty spaces for previous month days
                let startDay = firstDay.getDay(); // 0 (Sunday) - 6 (Saturday)
                for (let i = 0; i < startDay; i++) {
                    let emptyDiv = document.createElement("div");
                    emptyDiv.classList.add("opacity-0"); // Hide empty days
                    calendarEl.appendChild(emptyDiv);
                }

                // Generate days
                for (let day = 1; day <= lastDay.getDate(); day++) {
                    let dayDiv = document.createElement("div");
                    dayDiv.textContent = day;
                    dayDiv.classList.add("day");

                    // Add reservations to the day box
                    if (reservationsByDay[day]) {
                        const dotsContainer = document.createElement("div");
                        dotsContainer.classList.add("dots-container");

                        // Split reservations into two columns
                        const column1 = reservationsByDay[day].slice(0, 4); // First 4 reservations
                        const column2 = reservationsByDay[day].slice(4, 8); // Next 4 reservations

                        // Create first column
                        const column1Div = document.createElement("div");
                        column1Div.classList.add("dots-column");
                        column1.forEach(reservation => {
                            const reservationDot = document.createElement("div");
                            reservationDot.classList.add("status-dot");

                            // Apply styles based on status
                            if (reservation.status === "confirmed") {
                                reservationDot.classList.add("status-done");
                            } else if (reservation.status === "en_cour") {
                                reservationDot.classList.add("status-not-done");
                            } else {
                                reservationDot.classList.add("status-cancelled");
                            }

                            column1Div.appendChild(reservationDot);
                        });
                        dotsContainer.appendChild(column1Div);

                        // Create second column
                        const column2Div = document.createElement("div");
                        column2Div.classList.add("dots-column");
                        column2.forEach(reservation => {
                            const reservationDot = document.createElement("div");
                            reservationDot.classList.add("status-dot");

                            // Apply styles based on status
                            if (reservation.status === "done") {
                                reservationDot.classList.add("status-done");
                            } else if (reservation.status === "en_cour") {
                                reservationDot.classList.add("status-not-done");
                            } else {
                                reservationDot.classList.add("status-cancelled");
                            }

                            column2Div.appendChild(reservationDot);
                        });
                        dotsContainer.appendChild(column2Div);

                        dayDiv.appendChild(dotsContainer);
                    }

                    // Click event to redirect
                    dayDiv.addEventListener("click", function() {
                        let formattedDate = `${day.toString().padStart(2, "0")}-${(selectedMonth + 1).toString().padStart(2, "0")}-${selectedYear}`;
                        window.location.href = `/rdv/${formattedDate}`;
                    });

                    calendarEl.appendChild(dayDiv);
                }
            }

            // Event listeners for selects
            monthSelect.addEventListener("change", renderCalendar);
            yearSelect.addEventListener("change", renderCalendar);

            // Initial render
            renderCalendar();
        });
    </script>
</x-mechanic-app-layout>