<div id="cropper_container_module" class="container">
    <div class="header_module row">
        <div class="col">
            <h5 class="heading_text">Crop your image</h5>
        </div>
        <div class="col text-right">
            <span class="closing_button" aria-hidden="true" onclick="hideCropperContainer()">×</span>
        </div>
    </div>
    <div class="body_module row">
        <div class="col-md-9 text-center cropper-image-containter">
            <div>
                <img id="image_cropper" class="cropper-image" src="{{ asset('cropper_image.jpg') }}" alt="">
            </div>
        </div>
        <div class="col-md-3">
            <div class="preview mt-3 mt-sm-0"></div>
        </div>
    </div>
    <div class="footer_module row">
        <div class="col text-right">
            <button type="button" class="btn btn-secondary d-inline-flex"
                onclick="hideCropperContainer()">Cancel</button>
            <button type="button" class="btn btn-primary d-inline-flex" id="crop">Crop</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.css') }}" />

<script defer src="{{ asset('vendor/cropper/cropper.js') }}"></script>

<script defer>
    var cropper;

    window.addEventListener("load", function(){

        var $modal = $('#cropper_container_module');
        var image = document.getElementById('image_cropper');
        
        $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
                image.src = url;
                $modal.addClass('show');
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

            cropper = new Cropper(image, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                preview: '.preview',
                autoCropArea: 0.5,
            });

        });


        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 1920,
                height: 1080,
            });
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;

                    $('.cropped_image').remove();

                    var input = $("<input>")
                    .attr("type", "hidden")
                    .attr("name", "cropped_image")
                    .attr("class", "cropped_image")
                    .val(base64data);
                    $('form#cropper_form').append(input);

                    $modal.removeClass('show');
                }
            });
        })
    });

    function hideCropperContainer() {
        cropper.destroy();
        cropper = null;
        $('#cropper_container_module').removeClass('show');
    }
</script>