<x-mechanic-app-layout :subtitle="'Détails du rendez-vous'">
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
                href="{{ route('mechanic.clients.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-700">
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
                Détails du rendez-vous
              </a>
            </div>
          </li>
        </ol>
      </nav>
    </div>
    {{-- content --}}
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div class="px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="flex justify-between items-center my-6">
          <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Détails du rendez-vous</h2>
        </div>
        
        <div>
          <dl class="w-full text-gray-900 divide-y divide-gray-200">
            <div class="flex flex-col pb-3">
                <dt class="mb-1 text-gray-500 md:text-lg">Client Name</dt>
                <dd class="text-lg font-semibold">{{ $appointment->user_full_name }}</dd>
            </div>
            <div class="flex flex-col py-3">
                <dt class="mb-1 text-gray-500 md:text-lg">Email</dt>
                <dd class="text-lg font-semibold">{{ $appointment->user_email ?? 'N/A'}}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">Phone number</dt>
                <dd class="text-lg font-semibold">{{ $appointment->user_phone }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">categorie de service</dt>
                <dd class="text-lg font-semibold">{{ $appointment->categorie_de_service ?? 'N/A'}}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">numero immatriculation</dt>
                <dd class="text-lg font-semibold">{{ $appointment->numero_immatriculation ?? 'N/A'}}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">modele</dt>
                <dd class="text-lg font-semibold">{{ $appointment->modele ?? 'N/A'}}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">appointment day</dt>
                <dd class="text-lg font-semibold">{{ $appointment->appointment_day }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">appointment time</dt>
                <dd class="text-lg font-semibold">{{ $appointment->appointment_time }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">status</dt>
                <dd class="text-lg font-semibold">{{ $appointment->status }}</dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg">objet du RDV</dt>
                <dd class="text-lg font-semibold">{{ $appointment->objet_du_RDV ?? 'N/A'}}</dd>
            </div>
          </dl>
        </div>

      </div>
    </div>
    
    {{-- footer --}}
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      @include('layouts.footer')
    </div>
  </div>
</x-mechanic-app-layout>