<!-- resources/views/tweet/edit.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Tweet') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-8/12 md:w-1/2 lg:w-5/12">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          @include('common.errors')
          <form class="mb-6" action="{{ route('post.update',$post->id) }}" method="POST">
            @method('put')
            @csrf
            <div class="flex flex-col mb-4">
              <p class="mb-2 uppercase font-bold text-lg text-grey-darkest">Category</p>
              <ul class="flex">
              @foreach ($categories as $category)
                <li class="mr-2 ml-2">
                  <label class="flex text-sm">
                    <input class="border text-grey-darkest" type="checkbox" name="category_ids[]" value="{{ $category['id'] }}" {{ $post->categories->contains($category->id) ? 'checked' : '' }}>
                    <span>{{ $category['name'] }}</span>
                  </label>
                </li>
              @endforeach
              </ul>
            </div>
            <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="title">Title</label>
              <input class="border py-2 px-3 text-grey-darkest" type="text" name="title" id="title" value="{{$post->title}}">
            </div>
            <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="body">Body</label>
              <input class="border py-2 px-3 text-grey-darkest" type="text" name="body" id="body" value="{{$post->body}}">
            </div>
            <div class="flex justify-evenly">
              <a href="{{ route('post.index') }}" class="block text-center w-5/12 py-3 mt-6 font-medium tracking-widest text-black uppercase bg-gray-100 shadow-sm focus:outline-none hover:bg-gray-200 hover:shadow-none">
                Back
              </a>
              <button type="submit" class="w-5/12 py-3 mt-6 font-medium tracking-widest text-white uppercase bg-black shadow-lg focus:outline-none hover:bg-gray-900 hover:shadow-none">
                Update
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

