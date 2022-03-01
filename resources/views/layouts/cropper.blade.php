<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <script>
          // base64 -> file
          function _convertToFile(imgData, file) {
            const blob = atob(imgData.replace(/^.*,/, ''));
            let buffer = new Uint8Array(blob.length);
            for (let i = 0; i < blob.length; i++) {
              buffer[i] = blob.charCodeAt(i);
            }
            return new File([buffer.buffer], file.name, {
              type: file.type
            });
          }
        
          if ($('#js-modal').length) {
            var $modal = $('#js-modal');
            var image = document.getElementById('js-modal__image');
            var cropper;
          }
        
          $(".input-image").on("change", function(e) {
            var files = e.target.files;
            var target = $(this).data('target');
            $("#js-modal__save, #js-modal__cancel").data('target', target);
            var done = function(url) {
              image.src = url;
              $modal.fadeIn(200, function() {
                cropper = new Cropper(image, {
                  aspectRatio: 4 / 3,
                  viewMode: 1
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
            var target = $(this).data('target');
            var target_input = `input[data-target="${target}"]`;
            var target_thumbnail = `.thumbnail[data-target="${target}"]`;
            var target_file = `input[name="image_${target}"]`;
        
            canvas = cropper.getCroppedCanvas({
              width: 560,
              height: 420,
            });
            canvas.toBlob(function(blob) {
              url = URL.createObjectURL(blob);
              var reader = new FileReader();
              reader.readAsDataURL(blob);
      
              reader.onloadend = function() {
                var base64data = reader.result;
                
                $.ajax({
                  type: "POST",
                  dataType: "json",
                  url: "{{ route('post.upload') }}",
                  data: {
                    '_token': $('meta[name="_token"]').attr('content'),
                    'image': base64data
                  },
                  cache: false,
                  contentType: false,
                  processData: false
                }).done(function(response) {
                  let thumbnail = `
                    <img src="/storage${response}">
                  `;
                  $(target_thumbnail).html(thumbnail);
                  $(target_file).val(response);
                  fd.delete('image');
                  $modal.fadeOut();
                }).fail(function(response) {
                  alert("エラー");
                });
              }
            });
            cropper.destroy();
            cropper = null;
          });
        </script>
    </body>
</html>
