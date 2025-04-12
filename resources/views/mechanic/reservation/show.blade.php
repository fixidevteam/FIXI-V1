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
                href="{{ route('mechanic.reservation.index') }}"
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
          @if($appointment->status === 'confirmé' ||$appointment->status === 'en cours' )
          <a href="{{route('mechanic.reservation.edit',$appointment )}}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9426 1.25L13.5 1.25C13.9142 1.25 14.25 1.58579 14.25 2C14.25 2.41421 13.9142 2.75 13.5 2.75H12C9.62177 2.75 7.91356 2.75159 6.61358 2.92637C5.33517 3.09825 4.56445 3.42514 3.9948 3.9948C3.42514 4.56445 3.09825 5.33517 2.92637 6.61358C2.75159 7.91356 2.75 9.62177 2.75 12C2.75 14.3782 2.75159 16.0864 2.92637 17.3864C3.09825 18.6648 3.42514 19.4355 3.9948 20.0052C4.56445 20.5749 5.33517 20.9018 6.61358 21.0736C7.91356 21.2484 9.62177 21.25 12 21.25C14.3782 21.25 16.0864 21.2484 17.3864 21.0736C18.6648 20.9018 19.4355 20.5749 20.0052 20.0052C20.5749 19.4355 20.9018 18.6648 21.0736 17.3864C21.2484 16.0864 21.25 14.3782 21.25 12V10.5C21.25 10.0858 21.5858 9.75 22 9.75C22.4142 9.75 22.75 10.0858 22.75 10.5V12.0574C22.75 14.3658 22.75 16.1748 22.5603 17.5863C22.366 19.031 21.9607 20.1711 21.0659 21.0659C20.1711 21.9607 19.031 22.366 17.5863 22.5603C16.1748 22.75 14.3658 22.75 12.0574 22.75H11.9426C9.63423 22.75 7.82519 22.75 6.41371 22.5603C4.96897 22.366 3.82895 21.9607 2.93414 21.0659C2.03933 20.1711 1.63399 19.031 1.43975 17.5863C1.24998 16.1748 1.24999 14.3658 1.25 12.0574V11.9426C1.24999 9.63423 1.24998 7.82519 1.43975 6.41371C1.63399 4.96897 2.03933 3.82895 2.93414 2.93414C3.82895 2.03933 4.96897 1.63399 6.41371 1.43975C7.82519 1.24998 9.63423 1.24999 11.9426 1.25ZM16.7705 2.27592C18.1384 0.908029 20.3562 0.908029 21.7241 2.27592C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27592ZM20.6634 3.33658C19.8813 2.55448 18.6133 2.55448 17.8312 3.33658L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33658ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z" fill="#2563EB" />
            </svg>
          </a>
          @endif
        </div>
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
        <div class="space-y-6">
          {{-- box 1 --}}
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Statut du rendez-vous</h3>
              <p class="text-lg font-semibold first-letter:uppercase">{{ $appointment->status }}</p>
            </div>
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Date du rendez-vous</h3>
              <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($appointment->appointment_day)->format('d/m/Y') }}</p>
            </div>
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Heure du rendez-vous</h3>
              <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
            </div>
          </div>
          {{-- box 2 --}}
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-8">
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Nom du client</h3>
              <p class="text-lg font-semibold">{{ $appointment->user_full_name }}</p>
            </div>
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Adresse E-Mail</h3>
              <p class="text-lg font-semibold">{{ $appointment->user_email ?? 'N/A'}}</p>
            </div>
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">N° de téléphone</h3>
              <p class="text-lg font-semibold">{{ $appointment->user_phone }}</p>
            </div>
          </div>
          {{-- box 3 --}}
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-8">
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Domaine</h3>
              <p class="text-lg font-semibold">{{ $appointment->categorie_de_service ?? 'N/A'}}</p>
            </div>
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Marque de la voiture</h3>
              <p class="text-lg font-semibold">{{ $appointment->modele ?? 'N/A'}}</p>
            </div>
          </div>
          {{-- box 4 --}}
          @if($appointment->objet_du_RDV)
          <div class="grid grid-cols-1">
            <div>
              <h3 class="mb-1 text-gray-500 md:text-lg">Message</h3>
              <p class="text-lg font-semibold">{{ $appointment->objet_du_RDV ?? 'N/A'}}</p>
            </div>
          </div>
          @endif
        </div>
        {{-- models for status --}}
        <div class="flex space-x-4 mt-6">
          {{-- Clôturer le RDV --}}
          @php
          $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_day.' '.$appointment->appointment_time);
          $now = \Carbon\Carbon::now();
          $isPast = $appointmentDateTime->lt($now); // lt() means "less than" (in the past)
          @endphp
          @if($isPast && in_array($appointment->status, ['en cours']))
          <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded" onclick="toggleModal('modal-cloturer', true)"">
              Clôturer le RDV
          </button>
            @elseif($appointment->status === 'en cours')
            <button type=" button" class="px-4 py-2 bg-green-500 text-white rounded" onclick="toggleModal('modal-confirmed', true)"">
              Confirmer
            </button>
            <button type=" button" class="px-4 py-2 bg-red-500 text-white rounded" onclick="toggleModal('modal-cancelled', true)"">
              Annuler
            </button>
            @elseif($appointment->status === 'confirmé')
            <button type=" button" class="px-4 py-2 bg-red-500 text-white rounded" onclick="toggleModal('modal-cancelled', true)"">
              Annuler
            </button>
            @elseif($appointment->status === 'clôturé')
            <a href='{{route("mechanic.reservation.convertForm",$appointment)}}' type=" button" class="px-4 py-2 bg-orange-500 text-white rounded">
            Conversion en opération
            </a>
            @endif
            {{-- Clôturer le RDV close --}}
        </div>

        <div id=" modal-en_cour" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Confirmation de mise à jour</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir marquer cette réservation comme "En attente" ?</p>
            <div class="flex justify-end mt-4">
              <button onclick="toggleModal('modal-en_cour', false)" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">Annuler</button>
              <form method="POST" action="{{ route('mechanic.reservation.updateStatus', $appointment->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" name="status" value="en cours" class="px-4 py-2 bg-yellow-500 text-white rounded">Confirmer </button>
              </form>
            </div>
          </div>
        </div>


        <div id="modal-confirmed" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Confirmation de mise à jour</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir marquer cette réservation comme "Confirmé" ?</p>
            <div class="flex justify-end mt-4">
              <button onclick="toggleModal('modal-confirmed', false)" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">Annuler</button>
              <form method="POST" action="{{ route('mechanic.reservation.updateStatus', $appointment->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" name="status" value="confirmé" class="px-4 py-2 bg-green-500 text-white rounded">Confirmer</button>
              </form>
            </div>
          </div>
        </div>

        <div id="modal-cancelled" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Confirmation de mise à jour</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir marquer cette réservation comme "Annulé" ? Cette action est irréversible.</p>
            <div class="flex justify-end mt-4">
              <button onclick="toggleModal('modal-cancelled', false)" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">Annuler</button>
              <form method="POST" action="{{ route('mechanic.reservation.updateStatus', $appointment->id) }}">
                @csrf
                @method('PATCH')
                <button type="submit" name="status" value="annulé" class="px-4 py-2 bg-red-500 text-white rounded">Confirmer</button>
              </form>
            </div>
          </div>
        </div>
        {{-- modele Clôturer le RDV --}}
        <div id="modal-cloturer" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-lg font-bold text-gray-800">Clôturer le rendez-vous</h2>
            <p class="text-gray-600 mt-2">Êtes-vous sûr de vouloir marquer cette réservation comme "Clôturer" ? Cette action est irréversible.</p>
            <div class="mt-4">
              <form action="{{ route('mechanic.reservation.close', $appointment->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Le client s'est-il présenté ?</label>
                  <div class="space-y-2">
                    <div class="flex items-center">
                      <input class="mr-2 w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 focus:ring-2"
                        type="radio" name="presence" id="present" value="present" required>
                      <label class="text-gray-700" for="present">
                        <span class="mr-1">✅</span> Présent
                      </label>
                    </div>
                    <div class="flex items-center">
                      <input class="mr-2 w-4 h-4 text-red-600 bg-gray-100 border-gray-300 focus:ring-red-500 focus:ring-2"
                        type="radio" name="presence" id="absent" value="absent" required>
                      <label class="text-gray-700" for="absent">
                        <span class="mr-1">❌</span> Absent
                      </label>
                    </div>
                  </div>
                </div>
                <div class="flex justify-end">
                  <button type="button" onclick="toggleModal('modal-cloturer', false)" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">Annuler</button>
                  <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Clôturer le RDV
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
        {{-- close --}}
      </div>
    </div>

    {{-- footer --}}
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      @include('layouts.footer')
    </div>
  </div>
  <script>
    function toggleModal(modalId, show) {
      document.getElementById(modalId).classList.toggle('hidden', !show);
    }
  </script>
</x-mechanic-app-layout>