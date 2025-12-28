<script src="<?= base_url('/web/assets/plugins/datatables/jquery.dataTables.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>" type="text/javascript"></script>

<script src="<?= base_url('/web/assets/plugins/jcrop/js/jquery.color.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/jcrop/js/jquery.Jcrop.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/bootstrap-fileinput/bootstrap-fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('/web/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function() {
        id = $("#user_id").val();

        $('#dob').datetimepicker({
            format: 'L'
        });
    });
 
</script>

<script type="text/javascript" id="script-upload-profile">
    // update info by cropping (onChange and onSelect events handler)
    function updateInfo(e) {
        $('#x1').val(e.x);
        $('#y1').val(e.y);
        $('#x2').val(e.x2);
        $('#y2').val(e.y2);
        $('#wh').val(e.w +' x '+ e.h).trigger('change');
        $('#w').val(e.w);
        $('#h').val(e.h);
    };

    // clear info by cropping (onRelease event handler)
    function clearInfo() {
        $('#wh').val('').trigger('change');
        $('#w').val('');
        $('#h').val('');
        $('#x1').val('');
        $('#y1').val('');
        $('#x2').val('');
        $('#y2').val('');
    };

    // Create variables (in this scope) to hold the Jcrop API and image size
    var jcrop_api, boundx, boundy;

    function fileSelectHandler() {
        // get selected file
        var oFile = $('#file-profile')[0].files[0];

        // hide all errors
        $('.error').hide();
        $('.jcrop-info').hide();
        $('#profile-jcrop').removeAttr('src');
        $('#old_w').val('');
        $('#old_h').val('');

        // destroy Jcrop if it is existed
        if (jcrop_api != null) {
            jcrop_api.destroy();
            jcrop_api = null;
            $('#profile-jcrop').prop('style', 'margin: 0 auto;display:none;');
        }

        if (typeof oFile === 'undefined') {
            return false;
        }

        // check for image type (jpg and png are allowed)
        var rFilter = /^(image\/jpeg|image\/png)$/i;
        if (! rFilter.test(oFile.type)) {
            $('.error').html('File yang dipilih harus berupa gambar (jpeg/png)').show();
            return;
        }

        // preview element
        var oImage = document.getElementById('profile-jcrop');
        // prepare HTML5 FileReader
        var oReader = new FileReader();
            oReader.onload = function(e) {
            // e.target.result contains the DataURL which we can use as a source of the image
            oImage.src = e.target.result;
            oImage.onload = function () { // onload event handler
                // display step 2
                $('.step2').fadeIn(500);
                $('.jcrop-info').show();
                $('#profile-jcrop').show();

                // display some basic image info
                var sResultFileSize = bytesToSize(oFile.size);
                $('#filesize').val(sResultFileSize);
                $('#filetype').val(oFile.type);
                $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);

                setTimeout(function(){
                    // initialize Jcrop
                    $('#profile-jcrop').Jcrop({
                        minSize: [32, 32], // min crop size
                        aspectRatio : 1, // keep aspect ratio 1:1
                        bgFade: true, // use fade effect
                        bgOpacity: .3, // fade opacity
                        onChange: updateInfo,
                        onSelect: updateInfo,
                        onRelease: clearInfo,
                        setSelec: [0,0,110,110],
                        trueSize: [oImage.naturalWidth, oImage.naturalHeight]
                    }, function(){
                        // use the Jcrop API to get the real image size
                        var bounds = this.getBounds();
                        boundx = bounds[0];
                        boundy = bounds[1];
                        // Store the Jcrop API in the jcrop_api variable
                        jcrop_api = this;
                    });
                }, 100);
            };
        };
        // read selected file as DataURL
        oReader.readAsDataURL(oFile);
    }

    $(document).on('change', "#wh", function(event) {
        if ($(this).val().length > 0) {
            $("#btn-simpan-foto").show();
        } else {
            $("#btn-simpan-foto").hide();
        }
    });

    $(document).on('submit', '#form-photo', function(event) {
        event.preventDefault();
        /* Act on the event */

        formData = new FormData($(this)[0]);
        // var blob = dataURLtoBlob(canvas.toDataURL('image/png'));
        // formData.append("cropped_image", blob, 'blob.png');

        $.ajax({
            url: '<?= base_url("/profil/ubah-foto?id=") ?>'+id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        })
        .done(function(data) {
            if (data.success == true) {
                customConfirmation({message: 'Ada perubahan foto profil, muat ulang halaman?'}, (confirmed) => {
                    if (confirmed) {
                        window.location.reload(true);
                    }
                })

                $("#img-profile").attr('src', "<?= base_url() ?>"+data.images);
            } else {
                swalert(data.message);
            }
        })
        .fail(function(err) {
            console.error(err.error);
        })
        .always(function() {
            $('#modal-profile').modal('hide');
        });
        
    });

    $('#modal-profile').on('hidden.bs.modal', function () {
        $('#file-profile').val('').trigger('change');
    });
</script>
