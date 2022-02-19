<!-- resources/views/tweet/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('My Page') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-8/12 md:w-1/2 lg:w-5/12">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <table class="text-center w-full border-collapse">
            <thead>
              <tr>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-lg text-grey-dark border-b border-grey-light">my post</th>
              </tr>
            </thead>
            <tbody>
              @if (!$posts->isEmpty())
                @foreach ($posts as $post)
                <tr class="hover:bg-grey-lighter">
                  <td class="py-4 px-6 border-b border-grey-light">
                    <a href="{{ route('post.show',$post->id) }}">
                      <ul class="flex">
                      @foreach ($post->categories as $category)
                        <li class="mr-2 ml-2 text-sm">{{$category->name}}</li>
                      @endforeach
                      </ul>
                      <p class="text-left text-grey-dark">{{$post->user->name}}</p>
                      @if ($post->main_image !== null)
                        <img src="{{ \Storage::url($post->main_image) }}" class='w-100 mb-3'>
                      @else
                        <img src="{{ \Storage::url('no_image.png') }}" class='w-100 mb-3'>
                      @endif
                      <h3 class="text-left font-bold text-lg text-grey-dark">{{$post->title}}</h3>
                    </a>
                    <div class="flex">
                      
                      @if($post->users()->where('user_id', Auth::id())->exists())
                      <!-- unfavorite ボタン -->
                      <form action="{{ route('unfavorites',$post) }}" method="POST" class="text-left">
                        @csrf
                        <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-red py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-red-500" fill="red" viewBox="0 0 24 24" stroke="red">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                          </svg>
                          {{ $post->users()->count() }}
                        </button>
                      </form>
                      @else
                      <!-- favorite ボタン -->
                      <form action="{{ route('favorites',$post) }}" method="POST" class="text-left">
                        @csrf
                        <button type="submit" class="flex mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-black py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                          </svg>
                          {{ $post->users()->count() }}
                        </button>
                      </form>
                      @endif
                      
                      @if ($post->user_id === Auth::user()->id)
                      <!-- 更新ボタン -->
                      <form action="{{ route('post.edit',$post->id) }}" method="GET" class="text-left">
                        @csrf
                        <button type="submit" class="mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-white py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                          </svg>
                        </button>
                      </form>
                      <!-- 削除ボタン -->
                      <form action="{{ route('post.destroy',$post->id) }}" method="POST" class="text-left">
                        @method('delete')
                        @csrf
                        <button type="submit" class="mr-2 ml-2 text-sm hover:bg-gray-200 hover:shadow-none text-white py-1 px-2 focus:outline-none focus:shadow-outline">
                          <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="black">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                        </button>
                      </form>
                      @endif
                    </div>
                  </td>
                </tr>
                @endforeach
              @else
                <tr class="hover:bg-grey-lighter">
                  <td class="py-4 px-6 border-b border-grey-light">
                    <h3 class="text-left font-bold text-lg text-grey-dark">You have no post.</h3>
                  </td>
                </tr>
              @endif
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

