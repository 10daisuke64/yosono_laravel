<!-- resources/views/tweet/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Profile') }}
    </h2>
  </x-slot>
  
  <div class="py-12 pb-1">
    <div class="max-w-7xl mx-auto sm:w-8/12 md:w-1/2 lg:w-5/12">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          
          @include('common.errors')
          
          <!-- modal -->
          <div id="js-modal" class="modal">
            <div class="modal-wrapper">
              <div class="modal__content">
                <img id="js-modal__image" src="">
                <button class="modal__save" id="js-modal__save" data-target="">保存</button>
                <button class="modal__cancel" id="js-modal__cancel" data-target="">キャンセル</button>
              </div>
            </div>
          </div>
          <!-- //modal -->
          
          <form class="mb-6" action="{{ route('profile.update',$user->id) }}" method="POST">
            @csrf
            <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="profile_image">Profile Image</label>
              <div class="thumbnail" data-target="profile">
                @if ($user->profile_image !== null)
                  <img src="{{ \Storage::url('profiles/'.$user->profile_image) }}" width="100" height="100">
                @else
                  <img src="{{ \Storage::url('profiles/default.png') }}" width="100" height="100">
                @endif
              </div>
              <input type="file" name="image" class="input-image" data-target="profile" readonly="readonly">
              <input type="text" name="profile_image" value="{{ $user->profile_image }}" hidden>
            </div>
            <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="profile_name">profile_name</label>
              <input class="border py-2 px-3 text-grey-darkest" type="text" name="profile_name" id="profile_name" value="{{$user->profile_name}}">
            </div>
            <div class="flex flex-col mb-4">
              <label class="mb-2 uppercase font-bold text-lg text-grey-darkest" for="profile_text">profile_text</label>
              <textarea class="border py-2 px-3 text-grey-darkest" name="profile_text" id="profile_text">{{$user->profile_text}}</textarea>
            </div>
            <div class="flex justify-evenly">
              <a href="{{ route('post.mypage') }}" class="block text-center w-5/12 py-3 mt-6 font-medium tracking-widest text-black uppercase bg-gray-100 shadow-sm focus:outline-none hover:bg-gray-200 hover:shadow-none">
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
  <script>
  var $modal = $('#js-modal');
  var image = document.getElementById('js-modal__image');
  var cropper;

  $(".input-image").on("change", function(e) {
    var files = e.target.files;
    var target = $(this).data('target');
    $("#js-modal__save, #js-modal__cancel").data('target', target);
    var done = function(url) {
      image.src = url;
      $modal.fadeIn(200, function() {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 1,
        });
      });
    };
    var reader;
    var file;
    var url;
    if (files && files.length > 0) {
      file = files[0];
      if (URL) {
        done(URL.createObjectURL(file));
      } else if (FileReader) {
        reader = new FileReader();
        reader.onload = function(e) {
          done(reader.result);
        };
        reader.readAsDataURL(file);
      }
    }
  });

  // modal -> cancel
  $("#js-modal__cancel").on("click", function() {
    var target = $(this).data('target');
    var target_input = `input[data-target="${target}"]`;
    $modal.fadeOut();
    cropper.destroy();
    cropper = null;
    $(target_input).val("");
  })

  // modal -> save
  $("#js-modal__save").click(function() {
    $modal.fadeOut();
    var target = $(this).data('target');
    var target_input = `input[data-target="${target}"]`;
    var target_thumbnail = `.thumbnail[data-target="${target}"]`;
    var target_file = `input[name="${target}_image"]`;

    canvas = cropper.getCroppedCanvas({
      width: 100,
      height: 100,
    });
    canvas.toBlob(function(blob) {
      url = URL.createObjectURL(blob);
      var reader = new FileReader();
      reader.readAsDataURL(blob);

      reader.onloadend = function() {
        var base64data = reader.result;
        $.ajax({
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          type: "POST",
          dataType: "json",
          url: "{{ route('profile.upload') }}",
          data: base64data,
          cache: false,
          contentType: false,
          processData: false
        }).done(function(response) {
          $(target_file).val(response['src']);
          let thumbnail = `
            <img src="/storage/profiles/${response['src']}">
          `;
          $(target_thumbnail).html(thumbnail);
        }).fail(function(response) {
          alert("アップロード時にエラーが発生しました");
        });
      }
    });
    cropper.destroy();
    cropper = null;
  });
</script>
</x-app-layout>

