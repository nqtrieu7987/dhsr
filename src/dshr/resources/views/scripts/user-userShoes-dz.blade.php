<script type="text/javascript">
Dropzone.autoDiscover = false;
$(function() {
   Dropzone.options.userShoesDZ = {
        paramName: 'file',
        maxFilesize: 10, // MB
        addRemoveLinks: true,
        maxFiles: 1,
        acceptedFiles: ".jpeg,.jpg,.png",
        renameFile: 'nric.jpg',
        headers: {
            "Pragma": "no-cache"
        },
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                //
            });
            this.on("maxfilesreached", function(file) {
                //
            });
            this.on("uploadprogress", function(file) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">';
                html += '</div>';
                html += '</div>';
                $('#userShoesDZ .dz-message').html(html).show();
            });
            this.on("maxfilesreached", function(file) {});
            this.on("complete", function(file) {
                this.removeFile(file);
            });
            this.on("success", function(file, response) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Successful...';
                html += '</div>';
                html += '</div>';
                $('#userShoesDZ .dz-message').html(html).show();
                setTimeout(function() {
                    $('#userShoesDZ .dz-message').html('<span style="position: relative;top: -44px;right: -20px;"><img src="{{MEDIADOMAIN}}/images/camera.png"></span>').show();
                }, 2000);
                $('.userShoes').attr('src', '/uploads/users/id/{{ $user->id }}/'+file.upload.filename+'?' + new Date().getTime());
            });
            this.on("error", function(file, res) {
                var html = '<div class="progress">';
                html += '<div class="progress-bar progress-bar-striped bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">';
                html += 'Upload Failed...';
                html += '</div>';
                html += '</div>';
                $('#userShoesDZ .dz-message').html(html).show();
                setTimeout(function() {
                    $('#userShoesDZ .dz-message').html('<span style="position: relative;top: -44px;right: -20px;"><img src="{{MEDIADOMAIN}}/images/camera.png"></span>').show();
                }, 2000);
            });
        }
    };
    var userShoesDZ = new Dropzone("#userShoesDZ");
});
</script>