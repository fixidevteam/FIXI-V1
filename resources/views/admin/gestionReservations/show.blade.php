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
                Détails des reservations
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
          <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">Détails du rendez-vous</h2>
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
          {{-- update status --}}
          <form method="POST" action="{{ route('admin.reservations.updateStatus', $appointment->id) }}">
              @csrf
              @method('PATCH')
          
              <div class="flex space-x-4 mt-6">
                  <button type="submit" name="status" value="en_cour" class="px-4 py-2 bg-yellow-500 text-white rounded">
                      Marquer comme En attente
                  </button>
                  <button type="submit" name="status" value="Confirmed" class="px-4 py-2 bg-green-500 text-white rounded">
                      Marquer comme Confirmé
                  </button>
                  <button type="submit" name="status" value="cancelled" class="px-4 py-2 bg-red-500 text-white rounded">
                      Marquer comme Annulé
                  </button>
              </div>
          </form>
          {{-- end  --}}
        </div>
      </div>
    </div>
    {{-- contet close colse --}}
    {{-- footer --}}
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      @include('layouts.footer')
    </div>
  </div>
  </x-admin-app-layout>