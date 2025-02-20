let availableDates = [];
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
  const stepTexts = ["step1-text", "step2-text", "step3-text", "step4-text"];

  steps.forEach((stepId, index) => {
    const stepElement = document.getElementById(stepId);
    const checkIcon = document.getElementById(checkIcons[index]);
    const stepText = document.getElementById(stepTexts[index]);

    if (index < step) {
      stepElement.classList.add("text-red-600");
      stepElement.classList.remove("text-gray-500");
      checkIcon.classList.remove("hidden");
      stepText.classList.add("hidden");
    } else {
      stepElement.classList.add("text-gray-500");
      stepElement.classList.remove("text-red-600");
      checkIcon.classList.add("hidden");
      stepText.classList.remove("hidden");
    }
  });
}

// Fetch available dates from the API
function fetchAvailableDates() {
  showLoading(); // Show spinner
  fetch(`http://localhost:8000/api/available-dates?garage_ref=${garageRef}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.available_dates && data.available_dates.length > 0) {
        availableDates = data.available_dates;
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

// Initialize the date picker
function initDatePicker() {
  flatpickr("#datePicker", {
    dateFormat: "Y-m-d",
    enable: availableDates,
    onChange: function (selectedDates, dateStr) {
      selectedDate = dateStr;
      fetchTimeSlots(dateStr); // Fetch available time slots when a date is selected
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
      timesDiv.innerHTML =
        "<h3 class='text-lg font-medium text-gray-900 mb-4'>Sélectionnez une heure</h3>";

      if (data.time_slots.length === 0) {
        timesDiv.innerHTML =
          "<p class='text-red-600'>Aucune plage horaire disponible pour ce jour.</p>";
      } else {
        data.time_slots.forEach((time) => {
          let btn = document.createElement("button");
          btn.innerText = time;
          btn.classList.add(
            "p-2.5",
            "text-sm",
            "font-medium",
            "text-center",
            "bg-white",
            "border",
            "rounded-[20px]",
            "cursor-pointer",
            "text-red-600",
            "border-red-600",
            "hover:text-white",
            "hover:bg-red-600"
          );

          btn.onclick = () => {
            document.querySelectorAll("#times button").forEach((button) => {
              button.classList.remove("bg-red-700", "text-white");
            });
            btn.classList.add("bg-red-700", "text-white");
            selectedTime = time;
          };

          timesDiv.appendChild(btn);
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

// Show a specific step and update the progress indicator
function showStep(stepId) {
  document.querySelectorAll(".step").forEach((step) => {
    step.classList.add("hidden");
  });
  document.getElementById(stepId).classList.remove("hidden");
  updateProgressIndicator(Number(stepId.replace("step", "")));
  clearError(); // Clear errors when switching steps
}

// Event listeners for navigation buttons
document.getElementById("nextStep1").addEventListener("click", () => {
  if (!selectedDate) {
    showError("Please select a date.");
    return;
  }
  fetchTimeSlots(selectedDate);
  showStep("step2");
});

document.getElementById("prevStep2").addEventListener("click", () => {
  showStep("step1");
});

document.getElementById("nextStep2").addEventListener("click", () => {
  if (!selectedTime) {
    showError("Please select a time slot.");
    return;
  }
  showStep("step3");
});

document.getElementById("prevStep3").addEventListener("click", () => {
  showStep("step2");
});

// Handle form submission
document.getElementById("bookingForm").onsubmit = function (e) {
  e.preventDefault();
  showLoading(); // Show spinner

  let fullName = document.getElementById("full_name").value;
  let phone = document.getElementById("phone").value;
  let email = document.getElementById("email").value;
  let categorie_de_service = document.getElementById(
    "categorie_de_service"
  ).value;
  let numero_immatriculation = document.getElementById(
    "numero_immatriculation"
  ).value;
  let modele = document.getElementById("modele").value;
  let objet_du_RDV = document.getElementById("objet_du_RDV").value;

  fetch("http://localhost:8000/api/book-appointment", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      full_name: fullName,
      phone: phone,
      email: email,
      categorie_de_service: categorie_de_service,
      numero_immatriculation: numero_immatriculation,
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
        showError(data.message || "An error occurred. Please try again.");
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

  let verificationCode = document.getElementById("verificationCode").value;

  if (!verificationCode) {
    showError("Please enter the verification code.");
    hideLoading(); // Hide spinner if validation fails
    return;
  }

  fetch("http://localhost:8000/api/appointments/verify", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      email: document.getElementById("email").value,
      verification_code: verificationCode,
      full_name: document.getElementById("full_name").value,
      phone: document.getElementById("phone").value,
      categorie_de_service: document.getElementById("categorie_de_service")
        .value,
      numero_immatriculation: document.getElementById("numero_immatriculation")
        .value,
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
        alert(data.message); // Replace with a success message on the page
        window.location.href = "/success-page"; // Redirect to a success page
      } else {
        showError(data.message || "Invalid verification code.");
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

document.getElementById("prevStep4").addEventListener("click", () => {
  showStep("step3");
});
