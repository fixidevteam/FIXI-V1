<?php


namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\garage;
use App\Models\jour_indisponible;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class MechanicDashboardController extends Controller
{


    public function index(Request $request)
    {
        // // Set locale to French for Carbon
        // Carbon::setLocale('fr');

        // // Get the selected year, default to the current year
        // $selectedYear = $request->input('year', now()->year);

        // // Get operations for the mechanic's garage, filtered by the selected year
        // $operations = Auth::user()->garage->operations->filter(function ($operation) use ($selectedYear) {
        //     return Carbon::parse($operation->date_operation)->year == $selectedYear;
        // });

        // // Group operations by month and count them, using French month names
        // $operationsByMonth = $operations->groupBy(function ($operation) {
        //     return Carbon::parse($operation->date_operation)->translatedFormat('F'); // Month name in French
        // })->map(function ($group) {
        //     return $group->count();
        // });

        // // Create a list of all months in French
        // $allMonths = collect([
        //     'janvier',
        //     'février',
        //     'mars',
        //     'avril',
        //     'mai',
        //     'juin',
        //     'juillet',
        //     'août',
        //     'septembre',
        //     'octobre',
        //     'novembre',
        //     'décembre'
        // ]);

        // // Merge all months with operations, setting missing months to 0
        // $operationsByMonth = $allMonths->mapWithKeys(function ($month) use ($operationsByMonth) {
        //     return [$month => $operationsByMonth->get($month, 0)];
        // });

        // // Get distinct years for filtering
        // $years = Auth::user()->garage->operations
        //     ->map(function ($operation) {
        //         return Carbon::parse($operation->date_operation)->year;
        //     })
        //     ->unique()
        //     ->sort()
        //     ->values();

        // // Pass data to the view

        // count of RDV 
        $Rdvcount = Appointment::where('garage_ref', Auth::user()->garage->ref)->count();
        // ---
        // add calendrie 
            $user = Auth::user();
            // Fetch the garage associated with the mechanic
            $garage = garage::where('id', $user->garage_id)->first();
            
            // Check if the garage exists
            if (!$garage) {
                return redirect()->back()->with('error', 'Garage not found.');
            }

            // Fetch appointments for the garage
            $appointments = Appointment::where('garage_ref', $garage->ref)
                ->get(['id', 'user_full_name', 'appointment_day', 'appointment_time', 'status'])
                ->map(function ($appointment) {
                    $color = match ($appointment->status) {
                        'en cours' => 'orange',
                        'confirmé' => 'green',
                        'annulé' => 'red',
                        default => 'blue'
                    };

                    return [
                        'title' => 'Reservation: ' . $appointment->user_full_name,
                        'start' => $appointment->appointment_day . 'T' . $appointment->appointment_time,
                        'url' => route('mechanic.reservation.show', $appointment->id),
                        'time' => $appointment->appointment_time,
                        'color' => $color
                    ];
                })
                ->toArray();

            // Fetch unavailable days from jour_indisponibles table
            $unavailableDays = jour_indisponible::where('garage_ref', $garage->ref)
                ->pluck('date'); // Assuming 'day' is stored in 'YYYY-MM-DD' format

            // Add unavailable days as gray events
            foreach ($unavailableDays as $day) {
                $appointments[] = [
                    'title' => 'Indisponible',
                    'start' => $day,
                    'color' => 'gray',
                    'display' => 'background' // Make it appear as a background event
                ];
            }

        // 
        return view('mechanic.dashboard', [
            // 'labels' => $operationsByMonth->keys()->toArray(), // French month names
            // 'values' => $operationsByMonth->values()->toArray(), // Operation counts
            // 'years' => $years, // Available years
            // 'selectedYear' => $selectedYear,
            'Rdvcount' => $Rdvcount,
            'appointments'=>$appointments // Current selected year
        ]);
    }
}
