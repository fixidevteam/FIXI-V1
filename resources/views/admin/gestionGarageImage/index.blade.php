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
                              href="{{ route('admin.gestionGarages.index') }}"
                              class="inline-flex items-center text-sm font-medium text-gray-700   ">
                              Gestion des garages
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
                              class="inline-flex items-center text-sm font-medium text-gray-700   ">
                              Ajouter une galerie
                          </a>
                      </div>
                  </li>
              </ol>
          </nav>
      </div>
      {{-- content --}}
      <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
          {{-- content (slot on layouts/app.blade.php)--}}
          <div class=" px-5 py-3 text-gray-700 bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                <h2 class="mt-10  text-2xl font-bold leading-9 tracking-tight text-gray-900">Gérer les images du garage | {{ $garage->name }}</h2>
                <p class="mb-4">Image actuelles: {{ $images->count() }}/10</p>
                <form method="POST" action="{{ route('admin.gestionGarageImage.store', $garage->id) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <x-input-label for="images" :value="__('Télécharger des images (Max 10 au total)')" />
                        <x-file-input id="images" class="block mt-1 w-full" type="file" name="images[]" :value="old('photo')" multiple accept="image/*" />
                        <small>
                            Images actuelles : {{ $images->count() }}/10 - Vous pouvez télécharger {{ 10 - $images->count() }} images supplémentaires
                        </small>
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="flex justify-center rounded-[20px] bg-red-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                            {{ __('Ajouter à la galerie') }}
                        </x-primary-button>
                    </div>
                </form>
                {{-- images --}}
                @if($images->isEmpty())
                <div>Aucune image téléchargée pour le moment.</div>
                @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    @foreach($images->chunk(ceil($images->count()/4)) as $column)
                    <div class="grid gap-4">
                        @foreach($column as $image)
                        <div>
                            <img class="h-auto max-w-full rounded-lg" src="{{ asset('storage/' . $image->photo) }}" alt="Image du garage">
                            <form action="{{ route('admin.gestionGarageImage.destroy', ['garage' => $garage->id, 'photo' => $image->id]) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                @endif
                {{-- images close --}}
            </div>
        </div>
        {{-- contet close colse --}}
        {{-- footer --}}
        <div class="p-2 border-2 border-gray-200 border-dashed rounded-lg mt-4">
            @include('layouts.footer')
        </div>
    </div>
</x-admin-app-layout>