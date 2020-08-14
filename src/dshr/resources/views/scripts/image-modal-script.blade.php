<div id="imgModal" class="modal">
    <span class="close" onclick="modal.style.display = 'none';">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>

<script type="text/javascript">
    var modal = document.getElementById("imgModal");
    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");

    function showModalImage(img){
    	var image = img.src;
        var name = (img).getAttribute("alt");

        modal.style.display = "block";
        modalImg.src = image;
        captionText.innerHTML = name;
    }

    $(document).keyup(function(e) {
        if (e.keyCode == 27) { // esc keycode
            modal.style.display = "none";
        }
    });

    $(document).click(function (e) {
        if ($(e.target).is('#imgModal')) {
            $('#imgModal').fadeOut(500);
        }
    });
</script>