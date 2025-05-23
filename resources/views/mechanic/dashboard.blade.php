<x-mechanic-app-layout :subtitle="'Accueil'">
  <div class="p-4 sm:ml-64">
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-14">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <div class="flex flex-col md:flex-row md:justify-between md:items-center">
            <h1 class="text-lg font-medium text-gray-900">Bonjour, {{ Auth::user()->name }} </h1>
            <div>
              <h2 class="text-lg font-medium text-gray-900 flex items-center">
                REF:
                <span id="garage-ref" class="cursor-pointer ml-2 flex items-center">
                  {{ Auth::user()->garage?->ref }}
                  <!-- Copy Icon -->
                  <svg
                    id="copy-icon"
                    class="ml-1 w-4 h-4 text-gray-700 hover:text-gray-900"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none">
                    <path
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M7.2626 3.26045C7.38219 2.13044 8.33828 1.25 9.5 1.25H14.5C15.6617 1.25 16.6178 2.13044 16.7374 3.26045C17.5005 3.27599 18.1603 3.31546 18.7236 3.41895C19.4816 3.55818 20.1267 3.82342 20.6517 4.34835C21.2536 4.95027 21.5125 5.70814 21.6335 6.60825C21.75 7.47522 21.75 8.57754 21.75 9.94513V16.0549C21.75 17.4225 21.75 18.5248 21.6335 19.3918C21.5125 20.2919 21.2536 21.0497 20.6517 21.6517C20.0497 22.2536 19.2919 22.5125 18.3918 22.6335C17.5248 22.75 16.4225 22.75 15.0549 22.75H8.94513C7.57754 22.75 6.47522 22.75 5.60825 22.6335C4.70814 22.5125 3.95027 22.2536 3.34835 21.6517C2.74643 21.0497 2.48754 20.2919 2.36652 19.3918C2.24996 18.5248 2.24998 17.4225 2.25 16.0549V9.94513C2.24998 8.57754 2.24996 7.47522 2.36652 6.60825C2.48754 5.70814 2.74643 4.95027 3.34835 4.34835C3.87328 3.82342 4.51835 3.55818 5.27635 3.41895C5.83973 3.31546 6.49952 3.27599 7.2626 3.26045ZM7.26496 4.76087C6.54678 4.7762 5.99336 4.81234 5.54735 4.89426C4.98054 4.99838 4.65246 5.16556 4.40901 5.40901C4.13225 5.68577 3.9518 6.07435 3.85315 6.80812C3.75159 7.56347 3.75 8.56458 3.75 10V16C3.75 17.4354 3.75159 18.4365 3.85315 19.1919C3.9518 19.9257 4.13225 20.3142 4.40901 20.591C4.68577 20.8678 5.07435 21.0482 5.80812 21.1469C6.56347 21.2484 7.56458 21.25 9 21.25H15C16.4354 21.25 17.4365 21.2484 18.1919 21.1469C18.9257 21.0482 19.3142 20.8678 19.591 20.591C19.8678 20.3142 20.0482 19.9257 20.1469 19.1919C20.2484 18.4365 20.25 17.4354 20.25 16V10C20.25 8.56458 20.2484 7.56347 20.1469 6.80812C20.0482 6.07434 19.8678 5.68577 19.591 5.40901C19.3475 5.16556 19.0195 4.99838 18.4527 4.89426C18.0066 4.81234 17.4532 4.7762 16.735 4.76087C16.6058 5.88062 15.6544 6.75 14.5 6.75H9.5C8.34559 6.75 7.39424 5.88062 7.26496 4.76087ZM9.5 2.75C9.08579 2.75 8.75 3.08579 8.75 3.5V4.5C8.75 4.91421 9.08579 5.25 9.5 5.25H14.5C14.9142 5.25 15.25 4.91421 15.25 4.5V3.5C15.25 3.08579 14.9142 2.75 14.5 2.75H9.5ZM6.25 10.5C6.25 10.0858 6.58579 9.75 7 9.75H17C17.4142 9.75 17.75 10.0858 17.75 10.5C17.75 10.9142 17.4142 11.25 17 11.25H7C6.58579 11.25 6.25 10.9142 6.25 10.5ZM7.25 14C7.25 13.5858 7.58579 13.25 8 13.25H16C16.4142 13.25 16.75 13.5858 16.75 14C16.75 14.4142 16.4142 14.75 16 14.75H8C7.58579 14.75 7.25 14.4142 7.25 14ZM8.25 17.5C8.25 17.0858 8.58579 16.75 9 16.75H15C15.4142 16.75 15.75 17.0858 15.75 17.5C15.75 17.9142 15.4142 18.25 15 18.25H9C8.58579 18.25 8.25 17.9142 8.25 17.5Z"
                      fill="currentColor" />
                  </svg>
                  <!-- Check Icon (hidden by default) -->
                  <svg id="check-icon"
                    class="ml-1 w-4 h-4 text-green-600 hidden"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </span>
              </h2>
            </div>
          </div>
          <p class="text-sm text-gray-600 md:w-[300px] sm:w-full mx-0 text-left">Ajoutez vos informations en quelques clics,et accédez à une vue d’ensemble claire et sécurisée de toutes vos données importantes.</p>
          <div class="mt-4">
            <a href="{{ route('mechanic.operations.index') }}">
              <x-primary-button>mes opération</x-primary-button>
            </a>
          </div>
        </div>
      </div>
    </div>
    {{-- content --}}
    <!-- CHART SECTION -->
    <!-- END OF CHART SECTION -->


    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div>
        <div>
          <div class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- box 1 --}}
            <a href="{{route('mechanic.reservation.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">
                  {{$Rdvcount}} </span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des RDV's</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ Route::is('mechanic.reservation.*') ? 'text-gray-900' : '' }}" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.90049C11.8811 3.07699 11.7346 3.33717 11.5164 3.72853L11.3853 3.96367C11.3756 3.98113 11.3655 3.99938 11.3551 4.01827C11.2466 4.21557 11.0991 4.48359 10.8555 4.66849C10.6074 4.85681 10.3083 4.92266 10.0944 4.96974C10.0741 4.97421 10.0546 4.97851 10.0359 4.98273L9.78136 5.04032C9.31618 5.14557 9.02629 5.21294 8.83149 5.28104C8.95194 5.462 9.15411 5.70102 9.48207 6.08453L9.6556 6.28745C9.66837 6.30237 9.68167 6.31778 9.69541 6.33367C9.84315 6.50471 10.0397 6.73224 10.1306 7.02438C10.2205 7.31348 10.19 7.61259 10.1668 7.84122C10.1646 7.86257 10.1625 7.88331 10.1605 7.90336L10.1343 8.1741C10.0874 8.65774 10.0586 8.9706 10.0594 9.19191C10.2516 9.12379 10.5149 9.0042 10.9166 8.81921L11.155 8.70948C11.1725 8.7014 11.1909 8.69281 11.21 8.68386C11.4078 8.59128 11.6885 8.45994 12 8.45994C12.3116 8.45994 12.5922 8.59128 12.7901 8.68386C12.8092 8.69281 12.8276 8.7014 12.8451 8.70948L13.0834 8.81921C13.4852 9.00421 13.7485 9.1238 13.9407 9.19191C13.9415 8.9706 13.9126 8.65774 13.8658 8.1741L13.8395 7.90336C13.8376 7.88331 13.8355 7.86257 13.8333 7.84123C13.8101 7.61259 13.7796 7.31348 13.8695 7.02438C13.9604 6.73224 14.1569 6.5047 14.3047 6.33367C14.3184 6.31778 14.3317 6.30237 14.3445 6.28744L14.518 6.08453C14.846 5.70102 15.0481 5.462 15.1686 5.28104C14.9738 5.21294 14.6839 5.14557 14.2187 5.04032L13.9642 4.98273C13.9455 4.97851 13.926 4.97421 13.9057 4.96974C13.6918 4.92266 13.3926 4.85681 13.1446 4.66849C12.901 4.48359 12.7535 4.21556 12.6449 4.01826C12.6345 3.99937 12.6245 3.98113 12.6148 3.96367L12.4837 3.72853C12.2655 3.33717 12.119 3.07699 12 2.90049ZM14.153 9.2502C14.1541 9.25023 14.1547 9.25028 14.1547 9.25029L14.153 9.2502ZM9.84536 9.25029C9.84538 9.25028 9.84597 9.25023 9.8471 9.2502L9.84536 9.25029ZM10.8832 1.88484C11.0989 1.60321 11.451 1.25 12 1.25C12.5491 1.25 12.9012 1.60321 13.1169 1.88484C13.3246 2.15604 13.5372 2.53751 13.7683 2.9524C13.7768 2.96761 13.7853 2.98287 13.7939 2.99817L13.9249 3.23332C13.9617 3.29934 13.9902 3.35037 14.0155 3.39412C14.0325 3.42352 14.0458 3.44573 14.0565 3.46286C14.0733 3.46751 14.0946 3.47303 14.1219 3.47967C14.1689 3.49111 14.2236 3.50351 14.2952 3.51971L14.5497 3.5773C14.5671 3.58123 14.5844 3.58514 14.6017 3.58905C15.0486 3.69009 15.4657 3.78439 15.7807 3.90852C16.122 4.04302 16.5343 4.28543 16.6932 4.79638C16.8496 5.29916 16.6571 5.73321 16.4627 6.0421C16.28 6.3323 15.998 6.66198 15.6916 7.02013L15.4845 7.26233C15.4357 7.31933 15.3982 7.36328 15.3663 7.40179C15.3401 7.43338 15.322 7.45619 15.3091 7.4734C15.3108 7.5291 15.3179 7.60752 15.3325 7.75868L15.3637 8.08034C15.4102 8.55958 15.4527 8.997 15.4373 9.3415C15.4214 9.69943 15.3369 10.1785 14.9115 10.5015C14.4737 10.8337 13.9847 10.7689 13.6378 10.6695C13.3132 10.5765 12.9231 10.3969 12.5049 10.2042C12.4887 10.1967 12.4724 10.1892 12.4561 10.1817L12.2178 10.072C12.1507 10.0411 12.0994 10.0175 12.0549 9.99788C12.0329 9.9882 12.0149 9.98052 12 9.97438C11.9851 9.98052 11.9671 9.9882 11.9452 9.99788C11.9006 10.0175 11.8493 10.0411 11.7823 10.072L11.544 10.1817C11.5277 10.1892 11.5114 10.1967 11.4952 10.2042C11.0769 10.3969 10.6869 10.5765 10.3622 10.6695C10.0154 10.7689 9.52632 10.8337 9.08861 10.5015C8.66314 10.1785 8.5787 9.69943 8.56276 9.3415C8.54741 8.997 8.58985 8.5596 8.63635 8.08037C8.63799 8.06344 8.63964 8.04646 8.64129 8.02942L8.66753 7.75868C8.68217 7.60752 8.68932 7.5291 8.69101 7.47341C8.67807 7.45619 8.65998 7.43338 8.63382 7.4018C8.6019 7.36328 8.56434 7.31933 8.5156 7.26233L8.34207 7.05941C8.33084 7.04628 8.31963 7.03318 8.30846 7.02011C8.00211 6.66197 7.7201 6.33229 7.53742 6.0421C7.34297 5.73321 7.15048 5.29916 7.30683 4.79638C7.46572 4.28543 7.87809 4.04302 8.21938 3.90852C8.53436 3.78439 8.95149 3.69009 9.39839 3.58905C9.41566 3.58515 9.43298 3.58123 9.45034 3.5773L9.70488 3.51971C9.77649 3.50351 9.83117 3.49112 9.87817 3.47967C9.90546 3.47302 9.92673 3.46751 9.94355 3.46287C9.95428 3.44573 9.96759 3.42352 9.98458 3.39412C10.0099 3.35037 10.0383 3.29934 10.0751 3.23332L10.2062 2.99817C10.2147 2.98287 10.2232 2.96761 10.2317 2.9524C10.4629 2.53751 10.6755 2.15604 10.8832 1.88484ZM4.00004 8.20162C4.0522 8.29794 4.16326 8.49454 4.34556 8.63293C4.54147 8.78165 4.77662 8.83117 4.87434 8.85175C4.88411 8.8538 4.8925 8.85557 4.89935 8.85712L4.91994 8.86178L4.88725 8.9C4.88223 8.90587 4.87618 8.91277 4.86926 8.92065C4.80069 8.99878 4.64741 9.17342 4.57669 9.40084C4.50694 9.62513 4.53208 9.85483 4.54356 9.95978C4.54473 9.97048 4.54576 9.97988 4.54654 9.98785L4.54761 9.99898C4.45331 9.95385 4.23951 9.85497 4.00004 9.85497C3.76056 9.85497 3.54676 9.95385 3.45246 9.99898L3.45354 9.98785C3.45431 9.97988 3.45534 9.97048 3.45651 9.95978C3.46799 9.85483 3.49313 9.62513 3.42338 9.40084C3.35266 9.17342 3.19938 8.99878 3.13081 8.92065C3.12389 8.91277 3.11784 8.90587 3.11282 8.9L3.08013 8.86178L3.10072 8.85712C3.10757 8.85557 3.11596 8.8538 3.12573 8.85175C3.22345 8.83117 3.4586 8.78165 3.65451 8.63293C3.83681 8.49454 3.94787 8.29794 4.00004 8.20162ZM2.93521 8.12561L2.79333 7.4985L2.93521 8.12561ZM3.40604 10.6324C3.40625 10.6338 3.40632 10.6346 3.40631 10.6346L3.40604 10.6324ZM4.59377 10.6346C4.59375 10.6346 4.59382 10.6338 4.59403 10.6324L4.59377 10.6346ZM3.14388 6.71442C3.27255 6.54641 3.54568 6.25 4.00004 6.25C4.45439 6.25 4.72752 6.54641 4.85619 6.71442C4.97727 6.87253 5.09592 7.08561 5.20509 7.28166C5.21159 7.29334 5.21806 7.30496 5.22449 7.3165L5.27315 7.40378L5.35764 7.4229C5.37051 7.42581 5.38349 7.42874 5.39656 7.43169C5.60745 7.47928 5.84248 7.53232 6.02785 7.60538C6.23885 7.68853 6.57509 7.86998 6.70472 8.28684C6.83181 8.69552 6.66662 9.03351 6.5487 9.22083C6.44219 9.39002 6.28446 9.5743 6.1396 9.74353C6.13102 9.75355 6.12249 9.76352 6.11402 9.77343L6.04105 9.85875L6.05266 9.97854C6.05392 9.99151 6.05519 10.0046 6.05646 10.0177C6.07852 10.2443 6.1022 10.4877 6.0933 10.6874C6.08383 10.9 6.03136 11.2846 5.68249 11.5494C5.32138 11.8235 4.92822 11.7562 4.71564 11.6953C4.5246 11.6405 4.30505 11.5393 4.1078 11.4483C4.09552 11.4427 4.08333 11.4371 4.07123 11.4315L4.00003 11.3987L3.92884 11.4315C3.91675 11.4371 3.90455 11.4427 3.89227 11.4483C3.69502 11.5393 3.47547 11.6405 3.28443 11.6953C3.07186 11.7562 2.67869 11.8235 2.31758 11.5494C1.96872 11.2846 1.91624 10.9 1.90677 10.6874C1.89787 10.4877 1.92155 10.2443 1.94361 10.0177C1.94488 10.0046 1.94615 9.99152 1.94741 9.97854L1.95902 9.85875L1.88605 9.77343C1.87758 9.76352 1.86905 9.75355 1.86047 9.74353C1.71561 9.5743 1.55788 9.39002 1.45137 9.22083C1.33345 9.03351 1.16826 8.69552 1.29535 8.28684C1.42498 7.86998 1.76122 7.68853 1.97222 7.60538C2.15759 7.53232 2.39262 7.47928 2.60351 7.43169C2.61658 7.42874 2.62956 7.42581 2.64243 7.4229L2.72692 7.40378L2.77558 7.3165C2.78201 7.30496 2.78848 7.29334 2.79498 7.28167C2.90415 7.08562 3.0228 6.87253 3.14388 6.71442ZM20 8.20162C20.0522 8.29793 20.1633 8.49454 20.3456 8.63293C20.5415 8.78165 20.7766 8.83117 20.8743 8.85174C20.8841 8.8538 20.8925 8.85557 20.8993 8.85712L20.9199 8.86178L20.8873 8.9C20.8822 8.90587 20.8762 8.91277 20.8693 8.92065C20.8007 8.99878 20.6474 9.17342 20.5767 9.40084C20.5069 9.62513 20.5321 9.85482 20.5436 9.95978C20.5447 9.97048 20.5458 9.97988 20.5465 9.98785L20.5476 9.99898C20.4533 9.95385 20.2395 9.85497 20 9.85497C19.7606 9.85497 19.5468 9.95385 19.4525 9.99898L19.4535 9.98785C19.4543 9.97988 19.4553 9.97048 19.4565 9.95978C19.468 9.85483 19.4931 9.62513 19.4234 9.40084C19.3527 9.17342 19.1994 8.99878 19.1308 8.92065C19.1239 8.91277 19.1178 8.90587 19.1128 8.90001L19.0801 8.86178L19.1007 8.85712C19.1076 8.85557 19.116 8.8538 19.1257 8.85175C19.2234 8.83117 19.4586 8.78165 19.6545 8.63293C19.8368 8.49454 19.9479 8.29793 20 8.20162ZM19.406 10.6324C19.4063 10.634 19.4063 10.6347 19.4063 10.6346L19.406 10.6324ZM19.1439 6.71442C19.2725 6.54641 19.5457 6.25 20 6.25C20.4544 6.25 20.7275 6.54641 20.8562 6.71442C20.9773 6.87253 21.0959 7.08561 21.2051 7.28166C21.2116 7.29334 21.2181 7.30496 21.2245 7.3165L21.2731 7.40378L21.3576 7.4229C21.3705 7.42581 21.3835 7.42874 21.3966 7.43169C21.6075 7.47928 21.8425 7.53233 22.0279 7.60538C22.2388 7.68853 22.5751 7.86998 22.7047 8.28684C22.8318 8.69552 22.6666 9.03351 22.5487 9.22083C22.4422 9.39003 22.2844 9.57431 22.1396 9.74354C22.131 9.75356 22.1225 9.76352 22.114 9.77343L22.041 9.85875L22.0527 9.97854C22.0539 9.99151 22.0552 10.0046 22.0565 10.0176C22.0785 10.2443 22.1022 10.4877 22.0933 10.6874C22.0838 10.9 22.0314 11.2846 21.6825 11.5494C21.3214 11.8235 20.9282 11.7562 20.7156 11.6953C20.5246 11.6405 20.3051 11.5393 20.1078 11.4484C20.0955 11.4427 20.0833 11.4371 20.0712 11.4315L20 11.3987L19.9288 11.4315C19.9167 11.4371 19.9046 11.4427 19.8923 11.4483C19.695 11.5393 19.4755 11.6405 19.2844 11.6953C19.0719 11.7562 18.6787 11.8235 18.3176 11.5494C17.9687 11.2846 17.9162 10.9 17.9068 10.6874C17.8979 10.4877 17.9215 10.2443 17.9436 10.0177C17.9449 10.0046 17.9462 9.99152 17.9474 9.97855L17.959 9.85875L17.8861 9.77343C17.8776 9.76352 17.869 9.75354 17.8605 9.74352C17.7156 9.57429 17.5579 9.39002 17.4514 9.22083C17.3335 9.03351 17.1683 8.69552 17.2953 8.28684C17.425 7.86998 17.7612 7.68853 17.9722 7.60538C18.1576 7.53232 18.3926 7.47928 18.6035 7.43169C18.6166 7.42874 18.6296 7.42581 18.6424 7.4229L18.7269 7.40378L18.7756 7.3165C18.782 7.30496 18.7885 7.29334 18.795 7.28167C18.9041 7.08562 19.0228 6.87253 19.1439 6.71442ZM20.5938 10.6346C20.5938 10.6346 20.5938 10.6338 20.594 10.6324L20.5938 10.6346ZM8.68395 14.4482C10.5498 14.0867 12.5471 14.1678 14.1633 15.1318C14.3903 15.2672 14.6031 15.4359 14.7888 15.6444C15.1646 16.0666 15.3587 16.5913 15.3679 17.1174C15.5592 16.994 15.7508 16.857 15.9454 16.71L17.7526 15.3448C18.6572 14.6615 19.9718 14.6614 20.8765 15.3445C21.7124 15.9757 22.0457 17.1085 21.4473 18.0677C21.022 18.7495 20.3814 19.6924 19.7296 20.2962C19.0707 20.9065 18.1329 21.4196 17.4235 21.762C16.562 22.1778 15.6316 22.4077 14.7268 22.5541C12.8776 22.8534 10.9535 22.8076 9.12503 22.431C8.19062 22.2384 7.21958 22.1384 6.25997 22.1384H4.00004C3.58582 22.1384 3.25004 21.8026 3.25004 21.3884C3.25004 20.9742 3.58582 20.6384 4.00004 20.6384H6.25997C7.32208 20.6384 8.39451 20.749 9.4277 20.9618C11.0797 21.3022 12.8201 21.3432 14.4871 21.0734C15.3161 20.9392 16.0901 20.74 16.7715 20.4111C17.4549 20.0812 18.2233 19.6468 18.7103 19.1957C19.2028 18.7395 19.7541 17.9479 20.1746 17.2738C20.3016 17.0703 20.284 16.7767 19.9726 16.5416C19.6029 16.2624 19.0264 16.2625 18.6567 16.5417L16.8495 17.9069C16.1281 18.4518 15.2401 19.0349 14.1387 19.2106C14.0276 19.2283 13.9119 19.2445 13.7918 19.2588C13.7345 19.2692 13.6749 19.276 13.6133 19.2783C13.051 19.3342 12.3998 19.3472 11.6813 19.2793C11.2689 19.2404 10.9662 18.8745 11.0051 18.4621C11.0441 18.0497 11.4099 17.747 11.8223 17.786C12.4497 17.8452 13.0127 17.8321 13.4903 17.7831C13.4999 17.7821 13.5096 17.7811 13.5192 17.7801C13.5392 17.7685 13.5696 17.7474 13.6096 17.7125C13.9291 17.4336 13.9576 16.9667 13.6684 16.6418C13.5951 16.5595 13.5048 16.4856 13.3949 16.42C12.2138 15.7155 10.6363 15.5978 8.96925 15.9208C7.31172 16.2419 5.66494 16.9817 4.43389 17.8547C4.09602 18.0943 3.62787 18.0146 3.38826 17.6768C3.14865 17.3389 3.22831 16.8708 3.56618 16.6311C4.96454 15.6395 6.80863 14.8115 8.68395 14.4482Z" fill="currentColor" />
                </svg>
              </div>
            </a>
            {{-- box 2 --}}
            <a href="{{route('mechanic.operations.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">{{ Auth::user()->garage->operations()->count() }}</span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des opérations</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
            {{-- box 3 --}}
            <a href="{{route('mechanic.clients.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">
                  {{$clientsCount}} </span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des clients</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M9 1.25C6.37665 1.25 4.25 3.37665 4.25 6C4.25 8.62335 6.37665 10.75 9 10.75C11.6234 10.75 13.75 8.62335 13.75 6C13.75 3.37665 11.6234 1.25 9 1.25ZM5.75 6C5.75 4.20507 7.20507 2.75 9 2.75C10.7949 2.75 12.25 4.20507 12.25 6C12.25 7.79493 10.7949 9.25 9 9.25C7.20507 9.25 5.75 7.79493 5.75 6Z" fill="currentColor" />
                  <path d="M15 2.25C14.5858 2.25 14.25 2.58579 14.25 3C14.25 3.41421 14.5858 3.75 15 3.75C16.2426 3.75 17.25 4.75736 17.25 6C17.25 7.24264 16.2426 8.25 15 8.25C14.5858 8.25 14.25 8.58579 14.25 9C14.25 9.41421 14.5858 9.75 15 9.75C17.0711 9.75 18.75 8.07107 18.75 6C18.75 3.92893 17.0711 2.25 15 2.25Z" fill="currentColor" />
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M3.67815 13.5204C5.07752 12.7208 6.96067 12.25 9 12.25C11.0393 12.25 12.9225 12.7208 14.3219 13.5204C15.7 14.3079 16.75 15.5101 16.75 17C16.75 18.4899 15.7 19.6921 14.3219 20.4796C12.9225 21.2792 11.0393 21.75 9 21.75C6.96067 21.75 5.07752 21.2792 3.67815 20.4796C2.3 19.6921 1.25 18.4899 1.25 17C1.25 15.5101 2.3 14.3079 3.67815 13.5204ZM4.42236 14.8228C3.26701 15.483 2.75 16.2807 2.75 17C2.75 17.7193 3.26701 18.517 4.42236 19.1772C5.55649 19.8253 7.17334 20.25 9 20.25C10.8267 20.25 12.4435 19.8253 13.5776 19.1772C14.733 18.517 15.25 17.7193 15.25 17C15.25 16.2807 14.733 15.483 13.5776 14.8228C12.4435 14.1747 10.8267 13.75 9 13.75C7.17334 13.75 5.55649 14.1747 4.42236 14.8228Z" fill="currentColor" />
                  <path d="M18.1607 13.2674C17.7561 13.1787 17.3561 13.4347 17.2674 13.8393C17.1787 14.2439 17.4347 14.6439 17.8393 14.7326C18.6317 14.9064 19.2649 15.2048 19.6829 15.5468C20.1014 15.8892 20.25 16.2237 20.25 16.5C20.25 16.7507 20.1294 17.045 19.7969 17.3539C19.462 17.665 18.9475 17.9524 18.2838 18.1523C17.8871 18.2717 17.6624 18.69 17.7818 19.0867C17.9013 19.4833 18.3196 19.708 18.7162 19.5886C19.5388 19.3409 20.2743 18.9578 20.8178 18.4529C21.3637 17.9457 21.75 17.2786 21.75 16.5C21.75 15.6352 21.2758 14.912 20.6328 14.3859C19.9893 13.8593 19.1225 13.4783 18.1607 13.2674Z" fill="currentColor" />
                </svg>
              </div>
            </a>
            {{-- box 4 --}}
            <a href="{{route('mechanic.voitures.index')}}" class="flex items-center bg-white p-8 rounded-lg shadow hover:bg-gray-100">
              <div class="flex-shrink-0">
                <span class="text-2xl sm:text-3xl leading-none font-bold text-gray-900">
                  {{$voituresCount}}
                </span>
                <h3 class="text-base font-normal text-gray-500 first-letter:capitalize">nombre des véhicules</h3>
              </div>
              <div class="ml-5 w-0 flex items-center justify-end flex-1 text-gray-600 text-base font-bold">
                <svg class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3939 2.02121L16.4604 2.03904C17.5598 2.33361 18.431 2.56704 19.1162 2.81458C19.8172 3.06779 20.3888 3.35744 20.8597 3.79847C21.5453 4.44068 22.0252 5.27179 22.2385 6.18671C22.385 6.81503 22.3501 7.45486 22.2189 8.18849C22.0906 8.90573 21.8572 9.77697 21.5626 10.8764L21.0271 12.8747C20.7326 13.974 20.4991 14.8452 20.2516 15.5305C19.9984 16.2314 19.7087 16.803 19.2677 17.2739C18.6459 17.9377 17.8471 18.4087 16.9665 18.6316C16.7093 19.2213 16.3336 19.7554 15.8597 20.1993C15.3888 20.6403 14.8172 20.9299 14.1162 21.1832C13.431 21.4307 12.5598 21.6641 11.4605 21.9587L11.394 21.9765C10.2946 22.2711 9.42337 22.5045 8.70613 22.6328C7.9725 22.764 7.33266 22.7989 6.70435 22.6524C5.78943 22.4391 4.95832 21.9592 4.31611 21.2736C3.87508 20.8027 3.58542 20.2311 3.33222 19.5302C3.08468 18.8449 2.85124 17.9737 2.55667 16.8743L2.02122 14.876C1.72664 13.7766 1.4932 12.9054 1.36495 12.1882C1.23376 11.4546 1.19881 10.8147 1.34531 10.1864C1.55864 9.27149 2.03849 8.44038 2.72417 7.79817C3.19505 7.35714 3.76664 7.06749 4.46758 6.81428C5.15283 6.56674 6.02404 6.3333 7.12341 6.03873L7.15665 6.02983C7.42112 5.95896 7.67134 5.89203 7.90825 5.82944C8.29986 4.43031 8.64448 3.44126 9.31611 2.72417C9.95831 2.03849 10.7894 1.55864 11.7043 1.34531C12.3327 1.19881 12.9725 1.23376 13.7061 1.36495C14.4233 1.49319 15.2945 1.72664 16.3939 2.02121ZM7.45502 7.5028C6.36214 7.79571 5.57905 8.00764 4.9772 8.22505C4.36778 8.4452 4.00995 8.64907 3.74955 8.89296C3.2804 9.33237 2.95209 9.90103 2.80613 10.527C2.72511 10.8745 2.72747 11.2863 2.84152 11.9242C2.95723 12.5712 3.17355 13.381 3.47902 14.521L3.99666 16.4529C4.30212 17.5929 4.51967 18.4023 4.74299 19.0205C4.96314 19.63 5.16701 19.9878 5.4109 20.2482C5.85031 20.7173 6.41897 21.0456 7.04496 21.1916C7.39242 21.2726 7.80425 21.2703 8.4421 21.1562C9.08915 21.0405 9.89893 20.8242 11.0389 20.5187C12.1789 20.2132 12.9884 19.9957 13.6066 19.7724C14.216 19.5522 14.5739 19.3484 14.8343 19.1045C14.9719 18.9756 15.0973 18.8357 15.2096 18.6865C15.0306 18.6612 14.8463 18.629 14.6557 18.5911C13.9839 18.4575 13.1769 18.2413 12.1808 17.9744L12.1234 17.959C11.024 17.6644 10.1528 17.431 9.46758 17.1835C8.76664 16.9302 8.19505 16.6406 7.72416 16.1996C7.03849 15.5574 6.55864 14.7262 6.34531 13.8113C6.19881 13.183 6.23376 12.5432 6.36494 11.8095C6.4932 11.0923 6.72664 10.2211 7.02122 9.12174L7.45502 7.5028ZM13.4421 2.84152C12.8042 2.72747 12.3924 2.72511 12.045 2.80613C11.419 2.95209 10.8503 3.2804 10.4109 3.74955C9.97479 4.21518 9.70642 4.93452 9.2397 6.64323C9.16384 6.92093 9.08365 7.22023 8.99665 7.54488L8.47902 9.47673C8.17355 10.6167 7.95723 11.4265 7.84152 12.0736C7.72747 12.7114 7.72511 13.1232 7.80613 13.4707C7.95209 14.0967 8.2804 14.6654 8.74955 15.1048C9.00995 15.3487 9.36778 15.5525 9.9772 15.7727C10.5954 15.996 11.4049 16.2136 12.5449 16.519C13.5703 16.7938 14.3303 16.997 14.9482 17.1199C15.5635 17.2422 15.981 17.2723 16.3232 17.23C16.3976 17.2209 16.4691 17.2082 16.5389 17.1919C17.1649 17.0459 17.7335 16.7176 18.1729 16.2485C18.4168 15.9881 18.6207 15.6303 18.8408 15.0208C19.0642 14.4026 19.2817 13.5932 19.5872 12.4532L20.1048 10.5213C20.4103 9.38129 20.6266 8.57151 20.7423 7.92446C20.8564 7.28661 20.8587 6.87479 20.7777 6.52733C20.6317 5.90133 20.3034 5.33267 19.8343 4.89327C19.5739 4.64937 19.216 4.4455 18.6066 4.22535C17.9884 4.00203 17.1789 3.78448 16.0389 3.47902C14.8989 3.17355 14.0892 2.95723 13.4421 2.84152Z" fill="currentColor" />
                </svg>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- calendrie SECTION -->
    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      <div class="bg-white p-8 rounded-lg shadow w-full">
        <div class="flex justify-between items-center mb-3">
          <h2 class="text-2xl font-bold leading-9 tracking-tight text-gray-900"> Calendrier des RDV’s</h2>
        </div>
        <div id="calendar" class="w-full"></div>
      </div>
    </div>
    <!-- END OF calendrie SECTION -->



    <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
      {{-- content (slot on layouts/app.blade.php)--}}
      <div>
        <div>
          <div class="grid grid-cols-1 2xl:grid-cols-2 gap-4">
            {{-- box 1 --}}
            <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold leading-none text-gray-900 first-letter:capitalize">la liste des operations</h3>
                <a href="{{ route('mechanic.operations.index') }}" class="text-sm font-medium text-blue-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
                  Afficher tout
                </a>
              </div>
              <div class="flow-root">
                @if(Auth::user()->garage->operations->isEmpty())
                <p class="p-4 text-gray-500 text-center">Aucun operation disponible.</p>
                @else
                <ul role="list" class="divide-y divide-gray-200">
                  @foreach (Auth::user()->garage->operations->take(5) as $operation)
                  <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0">
                        @if($operation->photo !== NULL)
                        <img class="h-8 w-8 rounded-full object-cover" src="{{asset('storage/'.$operation->photo)}}" alt="Neil image">
                        @else
                        <img class="rounded-full w-8 h-8 object-cover" src="/images/defaultimage.jpg" alt="image description">
                        @endif
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          @php
                          $categorie = App\Models\nom_categorie::find($operation->categorie);
                          @endphp
                          {{$categorie->nom_categorie ??  $operation->categorie}}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          @php
                          $nomOp = $operation->nom === 'Autre'
                          ? "Autre" // Display "autre" or a default
                          : App\Models\nom_operation::find($operation->nom);
                          @endphp
                          {{ is_string($nomOp) ? $nomOp : ($nomOp->nom_operation ?? 'N/A') }}
                        </p>
                      </div>
                      <div class="inline-flex items-center text-base font-semibold text-gray-900">
                        <a href="{{ route('mechanic.operations.show',$operation->id) }}" class="text-sm font-medium text-blue-600  inline-flex items-center p-2 capitalize hover:underline">Détails</a>
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
                <h3 class="text-xl font-bold leading-none text-gray-900 first-letter:capitalize">la liste des véhicules</h3>
                <a href="{{ route('mechanic.voitures.index') }}" class="text-sm font-medium text-blue-600 hover:bg-gray-100 rounded-lg inline-flex items-center p-2">
                  Afficher tout
                </a>
              </div>
              <div class="flow-root">
                @if(Auth::user()->garage->operations->isEmpty())
                <p class="p-4 text-gray-500 text-center">Aucun voiture disponible.</p>
                @else
                <ul role="list" class="divide-y divide-gray-200">
                  @foreach (Auth::user()->garage->operations->unique('voiture.id')->take(5) as $operation)
                  <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex-shrink-0">
                        @if($operation->voiture->photo !== NULL)
                        <img class="h-8 w-8 rounded-full object-cover" src="{{asset('storage/'.$operation->voiture->photo)}}" alt="Neil image">
                        @else
                        <img class="rounded-full w-8 h-8 object-cover" src="/images/defaultimage.jpg" alt="image description">
                        @endif
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                          {{$operation->voiture->marque ." ". $operation->voiture->modele}}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                          <span>{{ explode('-', $operation->voiture->numero_immatriculation)[0] }}</span>-<span dir="rtl">{{ explode('-', $operation->voiture->numero_immatriculation)[1] }}</span>-<span>{{ explode('-', $operation->voiture->numero_immatriculation)[2] }}</span>
                        </p>
                      </div>
                      <div class="inline-flex items-center text-base font-semibold text-gray-900">
                        <a href="{{ route('mechanic.voitures.show',$operation->voiture->id) }}" class="text-sm font-medium text-blue-600  inline-flex items-center p-2 capitalize hover:underline">Détails</a>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      // Detect screen size for responsive header buttons
      var isMobile = window.innerWidth < 768;

      var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'fr', // Set language to French
        initialView: isMobile ? 'listWeek' : 'dayGridMonth', // Responsive views
        headerToolbar: isMobile ? {
          left: 'prev,next today', // Only navigation on mobile
          center: '',
          right: 'listWeek' // Show only list view on mobile
        } : {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: { // Manually set button labels in French
          today: "Aujourd’hui",
          month: "Mois",
          week: "Semaine",
          day: "Jour",
          list: "Liste",
          next: "Suivant",
          prev: "Précédent"
        },
        events: @json($appointments),
        eventTimeFormat: { // Display time in 24-hour format
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        },
        nowIndicator: true, // Highlights the current time
      });

      calendar.render();

      // Optional: Adjust layout when window resizes
      window.addEventListener('resize', function() {
        var newIsMobile = window.innerWidth < 768;
        if (newIsMobile !== isMobile) {
          location.reload(); // Reload to apply new toolbar layout
        }
      });
    });
  </script>

</x-mechanic-app-layout>