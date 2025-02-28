let availableDates = [];
let disabledDates = [];
let selectedDate = "";
let selectedTime = "";
let verificationData = null; // To store verification response

// Function to get query parameters from the URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Extract garage_ref from the URL
let garageRef = getQueryParam("garage_ref");

if (!garageRef) {
    showError("Garage reference is missing in the URL.");
} else {
    fetchAvailableDates();
}

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

// Function to update the progress indicator
function updateProgressIndicator(step) {
    const steps = [
        "step1-indicator",
        "step2-indicator",
        "step3-indicator",
        "step4-indicator",
    ];
    const checkIcons = [
        "step1-check",
        "step2-check",
        "step3-check",
        "step4-check",
    ];

    steps.forEach((stepId, index) => {
        const stepElement = document.getElementById(stepId);
        const checkIcon = document.getElementById(checkIcons[index]);

        if (index < step) {
            // Active step: Red color and show the check icon
            stepElement.classList.add("text-red-600");
            stepElement.classList.remove("text-gray-500");
        } else {
            // Inactive step: Gray color and hide the check icon
            stepElement.classList.add("text-gray-500");
            stepElement.classList.remove("text-red-600");
        }
    });
}

// Function to update the h2 title based on the current step
function updateStepTitle(step) {
    const stepTitles = {
        1: "Veuillez choisir la date du rendez-vous",
        2: "Veuillez préciser l'heure du rendez-vous",
        3: "Saisissez vos informations",
        4: "Vérification",
    };

    const h2Element = document.querySelector("h2");
    if (h2Element && stepTitles[step]) {
        h2Element.textContent = stepTitles[step];
    }
}

// Function to update the summary section
function updateSummary(date, time) {
    const summaryDiv = document.getElementById("summary");
    const selectedDateSpan = document.getElementById("selected-date");
    const selectedTimeSpan = document.getElementById("selected-time");

    if (date && time) {
        selectedDateSpan.textContent = date;
        selectedTimeSpan.textContent = time;
        summaryDiv.classList.remove("hidden"); // Show the summary section
    } else {
        summaryDiv.classList.add("hidden"); // Hide the summary section if no date/time is selected
    }
}

// Function to show a specific step
function showStep(stepId) {
    // Hide all steps
    document.querySelectorAll(".step").forEach((step) => {
        step.classList.add("hidden");
    });

    // Show the current step
    document.getElementById(stepId).classList.remove("hidden");

    // Update the progress indicator
    const stepNumber = Number(stepId.replace("step", ""));
    updateProgressIndicator(stepNumber);

    // Update the h2 title
    updateStepTitle(stepNumber);

    // Update the summary section if on Step 3 or Step 4
    if (stepNumber >= 3 && selectedDate && selectedTime) {
        updateSummary(selectedDate, selectedTime);
    } else {
        updateSummary("", ""); // Hide the summary section if no date/time is selected
    }

    // Clear errors
    clearError();

    // Ensure the "Next" button for the current step is visible
    if (stepNumber === 1) {
        document.getElementById("nextStep1").classList.remove("hidden");
    } else if (stepNumber === 2) {
        document.getElementById("nextStep2").classList.remove("hidden");
    }
}
// Event listener for the "Prev" button
document.getElementById("prev1").addEventListener("click", () => {
    showStep("step1"); // Go back to Step 1
});

// Function to toggle the modal
function toggleModal(show, modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.toggle("hidden", !show);
    }
}

// Function to handle confirmed Modify action
function confirmModify() {
    toggleModal(false, "confirmationModalModify"); // Hide the modal

    // Reset selected  time
    selectedTime = "";

    // Clear the time slots display
    document.getElementById("times").innerHTML = "";

    // Go back to Step 1 (Date Selection)
    showStep("step1");

    // Clear the summary section
    updateSummary("", "");

    // Show the "Next" button for Step 1
    document.getElementById("nextStep1").classList.remove("hidden");
    document.getElementById("step2").classList.add("hidden");
    document.getElementById("step3").classList.add("hidden");
}

// Function to handle confirmed Cancel action
function confirmCancel() {
    toggleModal(false, "confirmationModalCancel"); // Hide the modal
    // Redirect the user to the previous page (garage page)
    window.history.back();

    // Reset the form and selections
    selectedDate = "";
    selectedTime = "";
    document.getElementById("datePicker").value = "";
    document.getElementById("times").innerHTML = "";

    // Clear the summary section
    updateSummary("", "");

    // Go back to Step 1
    showStep("step1");

    // Show the "Next" button for Step 1
    document.getElementById("nextStep1").classList.remove("hidden");
    document.getElementById("step2").classList.add("hidden");
    document.getElementById("step3").classList.add("hidden");
}

// Event listener for the Modify button
document.getElementById("modify-btn").addEventListener("click", () => {
    toggleModal(true, "confirmationModalModify"); // Show the Modify confirmation modal
});

// Event listener for the Cancel button
document.getElementById("cancel-btn").addEventListener("click", () => {
    toggleModal(true, "confirmationModalCancel"); // Show the Cancel confirmation modal
});

// Fetch available dates from the API
function fetchAvailableDates() {
    showLoading(); // Show spinner
    fetch(`http://localhost:8000/api/available-dates?garage_ref=${garageRef}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.available_dates && data.available_dates.length > 0) {
                availableDates = data.available_dates;
                disabledDates = data.unavailable_dates;
                initDatePicker();
                // Populate services dropdown
                if (data.services && data.services.length > 0) {
                    populateServicesDropdown(data.services);
                }

                // Populate marques input with datalist
                if (data.marques && data.marques.length > 0) {
                    populateMarquesInput(data.marques);
                }
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

// Function to populate the services dropdown
function populateServicesDropdown(services) {
    const serviceMapping = {
        Mécanique: "Services d'un garage mécanique",
        Lavage: "Services d'un garage de lavage",
        Carrosserie: "Services d'un garage de carrosserie",
        Pneumatique: "Services d'un garage pneumatique",
        Dépannage: "Services d'un garage dépannage",
    };
    const servicesSelect = document.getElementById("categorie_de_service");
    servicesSelect.innerHTML = ""; // Clear existing options

    // Add services as options
    // Add a default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Choisir le domaine";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    servicesSelect.appendChild(defaultOption);

    services.forEach((service) => {
        const fullServiceName = serviceMapping[service] || service; // Use mapping or fallback to the original name
        const option = document.createElement("option");
        option.value = fullServiceName; // Use the full name as the value
        option.textContent = fullServiceName; // Display the full name
        servicesSelect.appendChild(option);
    });
}

// Function to populate the marques input with a datalist
function populateMarquesInput(marques) {
    const marquesInput = document.getElementById("modele");
    marquesInput.innerHTML = ""; // Clear existing options

    // Add a default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Sélectionnez une marque";
    defaultOption.disabled = true;
    defaultOption.selected = true;
    marquesInput.appendChild(defaultOption);

    // Add marques as options
    marques.forEach((marque) => {
        const option = document.createElement("option");
        option.value = marque;
        option.textContent = marque;
        marquesInput.appendChild(option);
    });
}

// Initialize the date picker
function initDatePicker() {
    flatpickr("#datePicker", {
        dateFormat: "Y-m-d",
        enable: [
            function (date) {
                const formattedDate = flatpickr.formatDate(date, "Y-m-d");
                return (
                    availableDates.includes(formattedDate) &&
                    !disabledDates.includes(formattedDate)
                );
            },
        ],
        onChange: function (selectedDates, dateStr) {
            selectedDate = dateStr;
            fetchTimeSlots(dateStr); // Fetch available time slots
            updateSummary(selectedDate, selectedTime); // Update the summary section
        },
    });
}

// Fetch time slots for the selected date
function fetchTimeSlots(date) {
    showLoading(); // Show spinner
    fetch(
        `http://localhost:8000/api/time-slots?garage_ref=${garageRef}&date=${date}`
    )
        .then((response) => response.json())
        .then((data) => {
            let timesDiv = document.getElementById("times");
            timesDiv.innerHTML = `<h3 class='text-lg font-medium text-gray-900 mb-4'>Choisir l'heure</h3>`;

            if (data.time_slots.length === 0) {
                timesDiv.innerHTML =
                    "<p class='text-red-600'>Aucune plage horaire disponible pour ce jour.</p>";
                // Show the "Prev" button
                document.getElementById("prev1").classList.remove("hidden");

                // Hide the "Next" button
                document.getElementById("nextStep2").classList.add("hidden");
            } else {
                data.time_slots.forEach((time) => {
                    let btn = document.createElement("button");
                    btn.innerText = time;
                    btn.classList.add(
                        "p-2.5",
                        "text-sm",
                        "font-medium",
                        "text-center",
                        "!bg-white",
                        "border",
                        "rounded-[20px]",
                        "cursor-pointer",
                        "!text-red-600",
                        "!border-red-600",
                        "!hover:text-white",
                        "!hover:bg-red-600"
                    );

                    btn.onclick = () => {
                        document
                            .querySelectorAll("#times button")
                            .forEach((button) => {
                                button.classList.remove(
                                    "!bg-red-700",
                                    "!text-white"
                                );
                            });
                        btn.classList.add("!bg-red-700", "!text-white");
                        selectedTime = time;
                        updateSummary(selectedDate, selectedTime); // Update the summary section
                    };

                    timesDiv.appendChild(btn);
                });
                // Hide the "Prev" button
                document.getElementById("prev1").classList.add("hidden");

                // Show the "Next" button
                document.getElementById("nextStep2").classList.remove("hidden");
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

// Event listener for "Next" button on Step 1
document.getElementById("nextStep1").addEventListener("click", () => {
    if (!selectedDate) {
        showError("Veuillez sélectionner une date.");
        document.getElementById("datePicker").classList.add("border-red-500");
        return;
    }
    document.getElementById("datePicker").classList.remove("border-red-500");
    document.getElementById("nextStep1").classList.add("hidden");
    fetchTimeSlots(selectedDate);
    showStep("step2"); // Move to Step 2 and update the title
});

// Event listener for "Next" button on Step 2
document.getElementById("nextStep2").addEventListener("click", () => {
    if (!selectedTime) {
        showError("Veuillez sélectionner un créneau horaire.");
        return;
    }
    document.getElementById("nextStep2").classList.add("hidden");
    showStep("step3"); // Move to Step 3 and update the title
});

// Handle form submission
document.getElementById("bookingForm").onsubmit = function (e) {
    e.preventDefault();

    // Get form values
    let fullName = document.getElementById("full_name").value;
    let phone = document.getElementById("phone").value;
    let email = document.getElementById("email").value;
    let categorie_de_service = document.getElementById(
        "categorie_de_service"
    ).value;
    let modele = document.getElementById("modele").value;
    let objet_du_RDV = document.getElementById("objet_du_RDV").value;

    // Validate form fields one by one
    if (fullName === "") {
        showError("Le nom est obligatoire.");
        document.getElementById("full_name").classList.add("border-red-500");
        return;
    } else {
        document.getElementById("full_name").classList.remove("border-red-500");
    }

    if (phone === "") {
        showError("Le numéro de téléphone est obligatoire.");
        document.getElementById("phone").classList.add("border-red-500");
        return;
    } else {
        document.getElementById("phone").classList.remove("border-red-500");
    }

    if (categorie_de_service === "") {
        showError("Le domaine est obligatoire.");
        document
            .getElementById("categorie_de_service")
            .classList.add("border-red-500");
        return;
    } else {
        document
            .getElementById("categorie_de_service")
            .classList.remove("border-red-500");
    }

    // If all fields are valid, proceed with form submission
    showLoading(); // Show spinner
    fetch("http://localhost:8000/api/book-appointment", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            full_name: fullName,
            phone: phone,
            email: email,
            categorie_de_service: categorie_de_service,
            modele: modele,
            objet_du_RDV: objet_du_RDV,
            garage_ref: garageRef,
            appointment_day: selectedDate,
            appointment_time: selectedTime,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === "verification_required") {
                showStep("step4");
            } else {
                showError(
                    data.message || "An error occurred. Please try again."
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError("An error occurred. Please try again.");
        })
        .finally(() => {
            hideLoading(); // Hide spinner
        });
};

// Handle verification code submission
document.getElementById("verifyCode").addEventListener("click", () => {
    showLoading(); // Show spinner

    let verificationCode = document
        .getElementById("verificationCode")
        .value.trim();

    if (!verificationCode) {
        showError("Veuillez entrer le code de vérification.");
        hideLoading(); // Hide spinner if validation fails
        return;
    }

    fetch("http://localhost:8000/api/appointments/verify", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            email: document.getElementById("email").value,
            verification_code: verificationCode.toString(),
            full_name: document.getElementById("full_name").value,
            phone: document.getElementById("phone").value,
            categorie_de_service: document.getElementById(
                "categorie_de_service"
            ).value,
            modele: document.getElementById("modele").value,
            objet_du_RDV: document.getElementById("objet_du_RDV").value,
            garage_ref: garageRef,
            appointment_day: selectedDate,
            appointment_time: selectedTime,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.message === "Appointment booked successfully!") {
                if (data.account) {
                    // Redirect to a success page
                    window.location.href =
                        "https://fixidev.com/success-page?ejkn2=hzne2";
                } else {
                    window.location.href =
                        "https://fixidev.com/success-page?ejkn2=kmal4";
                }
            } else {
                showError(data.message || "Code de vérification invalide.");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showError("An error occurred. Please try again.");
        })
        .finally(() => {
            hideLoading(); // Hide spinner
        });
});
