<style>
  /* Keyframes for the animation */
  @keyframes boxShadowAnimation {

    0%,
    50%,
    100% {
      box-shadow: none;
      /* No shadow */
    }

    25%,
    75% {
      box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.5), 0 2px 4px -2px rgba(220, 38, 38, 0.5);
    }
  }

  /* Animated Box */
  .animated-box {
    animation: boxShadowAnimation 2s ease-in-out 3;
    /* 2s animation, runs 2 times */
  }
</style>
<x-app-layout :subtitle="'Accueil'">
  <div class="p-4 sm:ml-64">
    @if(!$promotions->isEmpty())
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-14">
      <div id="default-carousel" class="relative w-full m-auto" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-32 md:h-40 lg:h-48 overflow-hidden rounded-lg">
          @foreach($promotions as $promotion)
          <!-- Item 1 -->
          <div class="hidden duration-1000 ease-in-out" data-carousel-item>
            <a href="{{$promotion->lien_promotion}}" target="_blank" rel="noopener">
              <img src="{{asset('storage/'.$promotion->photo_promotion)}}" class="absolute block w-full h-full object-cover" alt="{{$promotion->nom_promotion}}">
            </a>
          </div>
          @endforeach
          <!-- Item 2 -->
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-3 left-1/2 space-x-3 rtl:space-x-reverse">
          @foreach($promotions as $promotion)
          <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="true" aria-label="Slide {{$promotion->id}}" data-carousel-slide-to="{{$promotion->id}}"></button>
          @endforeach
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 left-0 z-30 flex items-center justify-center h-full px-3 cursor-pointer group focus:outline-none" data-carousel-prev>
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-800/50 text-white">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
            </svg>
            <span class="sr-only">Previous</span>
          </span>
        </button>
        <button type="button" class="absolute top-0 right-0 z-30 flex items-center justify-center h-full px-3 cursor-pointer group focus:outline-none" data-carousel-next>
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-800/50 text-white">
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 9l4-4-4-4" />
            </svg>
            <span class="sr-only">Next</span>
          </span>
        </button>
      </div>
    </div>
    @endif
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg {{ $promotions->isEmpty() ? 'mt-14' : 'mt-4' }}">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div class="w-full grid grid-cols-1 md:grid-cols-10 gap-0 md:gap-4">
        <div class="{{Auth::user()->unreadNotifications->isEmpty() ? 'col-span-10 md:col-span-10' : 'col-span-6'}} p-6 text-gray-900 bg-white p-8 rounded-lg shadow align-item-center">
          <h1 class="text-lg font-medium text-gray-900">Bonjour, {{ Auth::user()->name }} </h1>
          <p class="text-sm text-gray-600 md:w-[300px] sm:w-full mx-0 text-left">Ajoutez vos informations en quelques clics,et accédez à une vue d’ensemble claire et sécurisée de toutes vos données importantes.</p>
          <div class="mt-4">
            <a href="{{ route('voiture.create') }}">
              <x-primary-button>Ajouter une voiture</x-primary-button>
            </a>
          </div>
        </div>
        @if(Auth::user()->unreadNotifications->isNotEmpty())
        <div id="" class="animated-box col-span-10 md:col-span-4 my-3 md:my-0 bg-white p-6 rounded-lg shadow">
          <!-- Notifications -->
          <div class="px-4 pb-2 text-sm font-bold text-gray-700 border-b flex justify-between">
            Notifications
            <a href="{{route('notifications.index')}}" class=" text-center  text-sm font-medium text-blue-500 hover:text-blue-700">Afficher tous</a>
          </div>
          <div class="overflow-y-auto">
            @foreach(Auth::user()->unreadNotifications->take(3) as $notification)
            <a href="{{route('notifications.markAsRead',$notification->id)}}" class=" rounded-lg flex items-center gap-4 px-2 py-2 mt-1 text-sm hover:bg-gray-100 {{ $notification->read_at ? 'bg-gray-100' : 'bg-white' }}">
              <!-- Icon -->
              <div class="flex-shrink-0">
                @if(strpos($notification->data['unique_key'], 'ajouter') !== false)
                <svg class="text-green-500" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.0595 10.4995C15.3353 10.1905 15.3085 9.71642 14.9995 9.44055C14.6905 9.16467 14.2164 9.19151 13.9405 9.50049L10.9286 12.8739L10.0595 11.9005C9.78358 11.5915 9.30947 11.5647 9.00049 11.8405C8.69151 12.1164 8.66467 12.5905 8.94055 12.8995L10.3691 14.4995C10.5114 14.6589 10.7149 14.75 10.9286 14.75C11.1422 14.75 11.3457 14.6589 11.488 14.4995L15.0595 10.4995Z" fill="currentColor"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C11.0625 1.25 10.1673 1.55658 8.72339 2.05112L7.99595 2.30014C6.51462 2.8072 5.3714 3.19852 4.55303 3.53099C4.14078 3.69846 3.78637 3.86067 3.50098 4.02641C3.22634 4.1859 2.95082 4.38484 2.76363 4.65154C2.5786 4.91516 2.48293 5.23924 2.42281 5.55122C2.36031 5.87556 2.32262 6.26464 2.2983 6.71136C2.25 7.59836 2.25 8.81351 2.25 10.3896V11.9914C2.25 18.0924 6.85803 21.0175 9.59833 22.2146L9.62543 22.2264C9.96523 22.3749 10.2846 22.5144 10.6516 22.6084C11.0391 22.7076 11.4507 22.75 12 22.75C12.5493 22.75 12.9609 22.7076 13.3484 22.6084C13.7154 22.5144 14.0348 22.3749 14.3745 22.2264L14.4017 22.2146C17.142 21.0175 21.75 18.0924 21.75 11.9914V10.3898C21.75 8.81361 21.75 7.5984 21.7017 6.71136C21.6774 6.26464 21.6397 5.87556 21.5772 5.55122C21.5171 5.23924 21.4214 4.91516 21.2364 4.65154C21.0492 4.38484 20.7737 4.1859 20.499 4.02641C20.2136 3.86067 19.8592 3.69846 19.447 3.53099C18.6286 3.19852 17.4854 2.8072 16.004 2.30013L15.2766 2.05112C13.8327 1.55658 12.9375 1.25 12 1.25ZM9.08062 3.5143C10.6951 2.96164 11.3423 2.75 12 2.75C12.6577 2.75 13.3049 2.96164 14.9194 3.5143L15.4922 3.71037C17.0048 4.22814 18.1079 4.60605 18.8824 4.92069C19.269 5.07774 19.5491 5.20935 19.7457 5.32353C19.8428 5.3799 19.9097 5.42642 19.9543 5.46273C19.9922 5.49349 20.0066 5.51092 20.0087 5.51348C20.0106 5.5166 20.0231 5.53737 20.0406 5.58654C20.0606 5.64265 20.0827 5.72309 20.1043 5.83506C20.148 6.06169 20.1811 6.37301 20.2039 6.79292C20.2497 7.63411 20.25 8.80833 20.25 10.4167V11.9914C20.25 17.1665 16.3801 19.7135 13.8012 20.84C13.4297 21.0023 13.2152 21.0941 12.9764 21.1552C12.7483 21.2136 12.47 21.25 12 21.25C11.53 21.25 11.2517 21.2136 11.0236 21.1552C10.7848 21.0941 10.5703 21.0023 10.1988 20.84C7.6199 19.7135 3.75 17.1665 3.75 11.9914V10.4167C3.75 8.80833 3.75028 7.63411 3.79608 6.79292C3.81894 6.37301 3.85204 6.06169 3.89571 5.83506C3.91729 5.72309 3.93944 5.64265 3.95943 5.58654C3.97693 5.5374 3.98936 5.51663 3.99129 5.51349C3.99336 5.51095 4.0078 5.49351 4.04567 5.46273C4.09034 5.42642 4.15722 5.3799 4.25429 5.32353C4.4509 5.20935 4.731 5.07774 5.11759 4.92069C5.8921 4.60605 6.99521 4.22814 8.5078 3.71037L9.08062 3.5143Z" fill="currentColor"/>
                  </svg>                  
                @else
                <svg class="text-yellow-500" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V12C12.75 12.4142 12.4142 12.75 12 12.75C11.5858 12.75 11.25 12.4142 11.25 12V8C11.25 7.58579 11.5858 7.25 12 7.25Z" fill="currentColor" />
                  <path d="M12 16C12.5523 16 13 15.5523 13 15C13 14.4477 12.5523 14 12 14C11.4477 14 11 14.4477 11 15C11 15.5523 11.4477 16 12 16Z" fill="currentColor" />
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M8.72339 2.05112C10.1673 1.55658 11.0625 1.25 12 1.25C12.9375 1.25 13.8327 1.55658 15.2766 2.05112L16.004 2.30013C17.4854 2.8072 18.6286 3.19852 19.447 3.53099C19.8592 3.69846 20.2136 3.86067 20.499 4.02641C20.7737 4.1859 21.0492 4.38484 21.2364 4.65154C21.4214 4.91516 21.5171 5.23924 21.5772 5.55122C21.6397 5.87556 21.6774 6.26464 21.7017 6.71136C21.75 7.5984 21.75 8.81361 21.75 10.3898V11.9914C21.75 18.0924 17.142 21.0175 14.4017 22.2146L14.3746 22.2264C14.0348 22.3749 13.7154 22.5144 13.3484 22.6084C12.9609 22.7076 12.5493 22.75 12 22.75C11.4507 22.75 11.0391 22.7076 10.6516 22.6084C10.2846 22.5144 9.96523 22.3749 9.62543 22.2264L9.59833 22.2146C6.85803 21.0175 2.25 18.0924 2.25 11.9914V10.3899C2.25 8.81366 2.25 7.59841 2.2983 6.71136C2.32262 6.26464 2.36031 5.87556 2.42281 5.55122C2.48293 5.23924 2.5786 4.91516 2.76363 4.65154C2.95082 4.38484 3.22634 4.1859 3.50098 4.02641C3.78637 3.86067 4.14078 3.69846 4.55303 3.53099C5.3714 3.19852 6.51462 2.8072 7.99595 2.30014L8.72339 2.05112ZM12 2.75C11.3423 2.75 10.6951 2.96164 9.08062 3.5143L8.5078 3.71037C6.99521 4.22814 5.8921 4.60605 5.11759 4.92069C4.731 5.07774 4.4509 5.20935 4.25429 5.32353C4.15722 5.3799 4.09034 5.42642 4.04567 5.46273C4.0078 5.49351 3.99336 5.51095 3.99129 5.51349C3.98936 5.51663 3.97693 5.5374 3.95943 5.58654C3.93944 5.64265 3.91729 5.72309 3.89571 5.83506C3.85204 6.06169 3.81894 6.37301 3.79608 6.79292C3.75028 7.63411 3.75 8.80833 3.75 10.4167V11.9914C3.75 17.1665 7.6199 19.7135 10.1988 20.84C10.5703 21.0023 10.7848 21.0941 11.0236 21.1552C11.2517 21.2136 11.53 21.25 12 21.25C12.47 21.25 12.7483 21.2136 12.9764 21.1552C13.2152 21.0941 13.4297 21.0023 13.8012 20.84C16.3801 19.7135 20.25 17.1665 20.25 11.9914V10.4167C20.25 8.80833 20.2497 7.63411 20.2039 6.79292C20.1811 6.37301 20.148 6.06169 20.1043 5.83506C20.0827 5.72309 20.0606 5.64265 20.0406 5.58654C20.0231 5.53737 20.0106 5.5166 20.0087 5.51348C20.0066 5.51092 19.9922 5.49349 19.9543 5.46273C19.9097 5.42642 19.8428 5.3799 19.7457 5.32353C19.5491 5.20935 19.269 5.07774 18.8824 4.92069C18.1079 4.60605 17.0048 4.22814 15.4922 3.71037L14.9194 3.5143C13.3049 2.96164 12.6577 2.75 12 2.75Z" fill="currentColor" />
                </svg>
                @endif
              </div>
              <!-- Notification Message -->
              <div class="flex-1 text-md text-gray-800">
                {{ $notification->data['message'] }}
                <small class="text-gray-500 block">{{ $notification->created_at->diffForHumans() }}</small>
              </div>

            </a>
            @endforeach
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- content --}}
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div>
        <div>
          <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            {{-- box 1 --}}
            @php
                $appointmentCount = \App\Models\Appointment::where('user_email', Auth::user()->email)->count();
            @endphp
            <a href="{{route('RDV.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ $appointmentCount }}</span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des RDV's</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M17 14C17.5523 14 18 13.5523 18 13C18 12.4477 17.5523 12 17 12C16.4477 12 16 12.4477 16 13C16 13.5523 16.4477 14 17 14Z" fill="currentColor"/>
                  <path d="M17 18C17.5523 18 18 17.5523 18 17C18 16.4477 17.5523 16 17 16C16.4477 16 16 16.4477 16 17C16 17.5523 16.4477 18 17 18Z" fill="currentColor"/>
                  <path d="M13 13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13C11 12.4477 11.4477 12 12 12C12.5523 12 13 12.4477 13 13Z" fill="currentColor"/>
                  <path d="M13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17Z" fill="currentColor"/>
                  <path d="M7 14C7.55229 14 8 13.5523 8 13C8 12.4477 7.55229 12 7 12C6.44772 12 6 12.4477 6 13C6 13.5523 6.44772 14 7 14Z" fill="currentColor"/>
                  <path d="M7 18C7.55229 18 8 17.5523 8 17C8 16.4477 7.55229 16 7 16C6.44772 16 6 16.4477 6 17C6 17.5523 6.44772 18 7 18Z" fill="currentColor"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7 1.75C7.41421 1.75 7.75 2.08579 7.75 2.5V3.26272C8.412 3.24999 9.14133 3.24999 9.94346 3.25H14.0564C14.8586 3.24999 15.588 3.24999 16.25 3.26272V2.5C16.25 2.08579 16.5858 1.75 17 1.75C17.4142 1.75 17.75 2.08579 17.75 2.5V3.32709C18.0099 3.34691 18.2561 3.37182 18.489 3.40313C19.6614 3.56076 20.6104 3.89288 21.3588 4.64124C22.1071 5.38961 22.4392 6.33855 22.5969 7.51098C22.75 8.65018 22.75 10.1058 22.75 11.9435V14.0564C22.75 15.8941 22.75 17.3498 22.5969 18.489C22.4392 19.6614 22.1071 20.6104 21.3588 21.3588C20.6104 22.1071 19.6614 22.4392 18.489 22.5969C17.3498 22.75 15.8942 22.75 14.0565 22.75H9.94359C8.10585 22.75 6.65018 22.75 5.51098 22.5969C4.33856 22.4392 3.38961 22.1071 2.64124 21.3588C1.89288 20.6104 1.56076 19.6614 1.40314 18.489C1.24997 17.3498 1.24998 15.8942 1.25 14.0564V11.9436C1.24998 10.1058 1.24997 8.65019 1.40314 7.51098C1.56076 6.33855 1.89288 5.38961 2.64124 4.64124C3.38961 3.89288 4.33856 3.56076 5.51098 3.40313C5.7439 3.37182 5.99006 3.34691 6.25 3.32709V2.5C6.25 2.08579 6.58579 1.75 7 1.75ZM5.71085 4.88976C4.70476 5.02502 4.12511 5.27869 3.7019 5.7019C3.27869 6.12511 3.02502 6.70476 2.88976 7.71085C2.86685 7.88123 2.8477 8.06061 2.83168 8.25H21.1683C21.1523 8.06061 21.1331 7.88124 21.1102 7.71085C20.975 6.70476 20.7213 6.12511 20.2981 5.7019C19.8749 5.27869 19.2952 5.02502 18.2892 4.88976C17.2615 4.75159 15.9068 4.75 14 4.75H10C8.09318 4.75 6.73851 4.75159 5.71085 4.88976ZM2.75 12C2.75 11.146 2.75032 10.4027 2.76309 9.75H21.2369C21.2497 10.4027 21.25 11.146 21.25 12V14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25H10C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14V12Z" fill="currentColor"/>
              </svg>
              </div>
            </a>
            {{-- box 2 --}}
            <a href="{{route('voiture.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ Auth::user()->voitures->count() }}</span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des voitures</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 6.89137 17.1086 2.75 12 2.75ZM1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C17.9371 1.25 22.75 6.06294 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM6.80317 11.25H9.35352C9.47932 10.8052 9.71426 10.4062 10.0276 10.0838L8.75225 7.87485C7.7184 8.68992 6.99837 9.88531 6.80317 11.25ZM10.0507 7.1238L11.3262 9.33314C11.5418 9.27884 11.7676 9.25 12 9.25C12.2324 9.25 12.4581 9.27883 12.6737 9.33312L13.9493 7.12378C13.3466 6.88264 12.6888 6.75 12 6.75C11.3112 6.75 10.6534 6.88265 10.0507 7.1238ZM15.2477 7.87481L13.9724 10.0837C14.2857 10.4062 14.5207 10.8052 14.6465 11.25H17.1968C17.0016 9.88529 16.2816 8.68988 15.2477 7.87481ZM17.1968 12.75H14.6465C14.5207 13.1949 14.2857 13.5939 13.9723 13.9163L15.2477 16.1252C16.2816 15.3102 17.0016 14.1147 17.1968 12.75ZM13.9492 16.8762L12.6736 14.6669C12.4581 14.7212 12.2324 14.75 12 14.75C11.7676 14.75 11.5419 14.7212 11.3263 14.6669L10.0507 16.8762C10.6534 17.1174 11.3112 17.25 12 17.25C12.6888 17.25 13.3465 17.1174 13.9492 16.8762ZM8.75229 16.1252L10.0276 13.9163C9.71428 13.5938 9.47933 13.1948 9.35352 12.75H6.80317C6.99837 14.1147 7.71842 15.3101 8.75229 16.1252ZM11.3859 13.089C11.3823 13.0869 11.3787 13.0847 11.375 13.0826C11.3715 13.0806 11.368 13.0786 11.3645 13.0766C10.9967 12.859 10.75 12.4583 10.75 12C10.75 11.5434 10.9949 11.1439 11.3605 10.9258C11.3653 10.9231 11.3702 10.9204 11.375 10.9176C11.3801 10.9146 11.3851 10.9116 11.3902 10.9086C11.5705 10.8076 11.7785 10.75 12 10.75C12.2204 10.75 12.4275 10.8071 12.6073 10.9072C12.6131 10.9107 12.619 10.9142 12.6249 10.9177C12.6306 10.9209 12.6362 10.9241 12.642 10.9272C13.0062 11.1457 13.25 11.5444 13.25 12C13.25 12.4595 13.0021 12.8611 12.6327 13.0783C12.6301 13.0797 12.6276 13.0812 12.625 13.0827C12.6222 13.0843 12.6194 13.0859 12.6166 13.0876C12.4347 13.191 12.2242 13.25 12 13.25C11.7768 13.25 11.5673 13.1915 11.3859 13.089ZM5.25 12C5.25 8.27208 8.27208 5.25 12 5.25C15.7279 5.25 18.75 8.27208 18.75 12C18.75 15.7279 15.7279 18.75 12 18.75C8.27208 18.75 5.25 15.7279 5.25 12Z" fill="currentColor" />
                </svg>
              </div>
            </a>
            {{-- box 3 --}}
            {{-- count all operations that made on the cars --}}
            @php
            $operationsCount = Auth::user()->voitures->sum(function ($voiture) {
            return $voiture->operations->count();
            });
            @endphp
            <a href="{{route('operation.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ $operationsCount }}</span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des operations</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M14 20C14 21.1046 13.1046 22 12 22C10.8954 22 10 21.1046 10 20C10 18.8954 10.8954 18 12 18C13.1046 18 14 18.8954 14 20Z" stroke="currentColor" stroke-width="1.5" />
                  <path d="M6 4C6 3.05719 6 2.58579 6.29289 2.29289C6.58579 2 7.05719 2 8 2H16C16.9428 2 17.4142 2 17.7071 2.29289C18 2.58579 18 3.05719 18 4C18 4.94281 18 5.41421 17.7071 5.70711C17.4142 6 16.9428 6 16 6H8C7.05719 6 6.58579 6 6.29289 5.70711C6 5.41421 6 4.94281 6 4Z" stroke="currentColor" stroke-width="1.5" />
                  <path d="M8.5 16.5C8.5 15.6716 9.17157 15 10 15H14C14.8284 15 15.5 15.6716 15.5 16.5C15.5 17.3284 14.8284 18 14 18H10C9.17157 18 8.5 17.3284 8.5 16.5Z" stroke="currentColor" stroke-width="1.5" />
                  <path d="M14 15.5V5.5" stroke="currentColor" stroke-width="1.5" />
                  <path d="M10 15.5V6" stroke="currentColor" stroke-width="1.5" />
                  <path d="M8 8L16 10M8 11.5L16 13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                  <path d="M20 11.4994L22 11.4994M4 11.5004H2M19.0713 14.2999L19.7784 14.9999M4.92871 14.2999L4.2216 14.9999M19.0713 8.69984L19.7784 7.99988M4.92871 8.69984L4.2216 7.99988" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div>
        <div>
          <div class="grid grid-cols-1 2xl:grid-cols-2 gap-4">
            {{-- box 1 --}}
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold leading-none text-gray-900 first-letter:capitalize">Mes voitures</h3>
                <a href="{{ route('voiture.index') }}" class="text-sm font-medium text-blue-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2 first-letter:capitalize">
                  Afficher tout
                </a>
              </div>
              <div class="flow-root">
                @if(Auth::user()->voitures ->isEmpty())
                <p class="p-4 text-gray-500 text-center">Aucun voiture disponible.</p>
                @else
                <ul role="list" class="divide-y divide-gray-200">
                  @foreach (Auth::user()->voitures->take(5) as $voiture)
                  <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0">
                        @if($voiture->photo !== NULL)
                        <img class="rounded-full w-8 h-8 object-cover" src="{{asset('storage/'.$voiture->photo)}}" alt="image description">
                        @else
                        <img class="rounded-full w-8 h-8 object-cover" src="/images/defaultimage.jpg" alt="image description">
                        @endif
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          {{$voiture->marque ." ". $voiture->modele}}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          <span>{{ explode('-', $voiture->numero_immatriculation)[0] }}</span>-<span dir="rtl">{{ explode('-', $voiture->numero_immatriculation)[1] }}</span>-<span>{{ explode('-', $voiture->numero_immatriculation)[2] }}</span>
                        </p>
                      </div>
                      <div class="inline-flex items-center text-base font-semibold text-gray-900">
                        <a href="{{ route('voiture.show',$voiture->id) }}" class="text-sm font-medium text-blue-600  inline-flex items-center p-2 capitalize hover:underline">details</a>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
                @endif
              </div>
            </div>
            {{-- box 1 close --}}
            {{-- box 2 --}}
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold leading-none text-gray-900 first-letter:capitalize">Mes papiers personnels</h3>
                <a href="{{ route('paiperPersonnel.index') }}" class="text-sm font-medium text-blue-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2 first-letter:capitalize">
                  Afficher tout
                </a>
              </div>
              <div class="flow-root">
                @if(Auth::user()->papiersUsers->isEmpty())
                <p class="p-4 text-gray-500 text-center">Aucun papier disponible.</p>
                @else
                <ul role="list" class="divide-y divide-gray-200">
                  @foreach (Auth::user()->papiersUsers->take(5) as $papier)
                  <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0">
                        @if($papier->photo !== NULL)
                        @php
                        $fileExtension = pathinfo($papier->photo, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                        <!-- Display the actual photo -->
                        <img class="rounded-full w-8 h-8 object-cover" src="{{ asset('storage/' . $papier->photo) }}" alt="image description">
                        @elseif(strtolower($fileExtension) === 'pdf')
                        <!-- Display the default image for PDFs -->
                        <img class="rounded-full w-8 h-8 object-cover" src="{{ asset('/images/file.png') }}" alt="default image">
                        @else
                        <!-- Display the default image for unsupported formats -->
                        <img class="rounded-full w-8 h-8 object-cover" src="{{ asset('/images/defaultimage.jpg') }}" alt="default image">
                        @endif
                        @else
                        <!-- Display the default image if no photo is provided -->
                        <img class="rounded-full w-8 h-8 object-cover" src="{{ asset('/images/defaultimage.jpg') }}" alt="default image">
                        @endif
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          {{$papier->type}}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          {{$papier->date_debut }} / <span class="text-red-600">{{$papier->date_fin}}</span>
                        </p>
                      </div>
                      <div class="inline-flex items-center text-base font-semibold text-gray-900">
                        <a href="{{route('paiperPersonnel.show',$papier->id)}}" class="text-sm font-medium text-blue-600 inline-flex items-center p-2 capitalize hover:underline">Details</a>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
                @endif
              </div>
            </div>
            {{-- box 2 close --}}
          </div>
        </div>
      </div>
    </div>
    {{-- contet close colse --}}
    {{-- footer --}}

    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      @include('layouts.footer')
    </div>
  </div>
</x-app-layout>