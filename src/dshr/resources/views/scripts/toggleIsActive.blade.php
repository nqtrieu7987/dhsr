  <script>
    $(document).ready(function(){
      $('.switch').click(function(){
        $(this).toggleClass('checked');
        $('input[name="is_active"]').not(':checked').prop("checked", true);
        $('input[name="activated"]').not(':checked').prop("checked", true);
      });

      $('.switch_hot').click(function(){
        $(this).toggleClass('checked');
        $('input[name="is_hot"]').not(':checked').prop("checked", true);
      });

      $('.switch_userPantsApproved').click(function(){
        $(this).toggleClass('checked');
        $('input[name="userPantsApproved"]').not(':checked').prop("checked", true);
      });

      $('.switch_userShoesApproved').click(function(){
        $(this).toggleClass('checked');
        $('input[name="userShoesApproved"]').not(':checked').prop("checked", true);
      });

      $('.switch_userGender').click(function(){
        $(this).toggleClass('checked');
        $('input[name="userGender"]').not(':checked').prop("checked", true);
      });

      $('.switch_dyedHair').click(function(){
        $(this).toggleClass('checked');
        $('input[name="dyedHair"]').not(':checked').prop("checked", true);
      });

      $('.switch_visibleTattoo').click(function(){
        $(this).toggleClass('checked');
        $('input[name="visibleTattoo"]').not(':checked').prop("checked", true);
      });

      $('.switch_studentType').click(function(){
        $(this).toggleClass('checked');
        $('input[name="studentType"]').not(':checked').prop("checked", true);
      });

      $('.switch_studentStatus').click(function(){
        $(this).toggleClass('checked');
        $('input[name="studentStatus"]').not(':checked').prop("checked", true);
      });

      $('.switch_isFavourite').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isFavourite"]').not(':checked').prop("checked", true);
      });

      $('.switch_isWarned').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isWarned"]').not(':checked').prop("checked", true);
      });

      $('.switch_isDiamond').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isDiamond"]').not(':checked').prop("checked", true);
      });

      $('.switch_TCC').click(function(){
        $(this).toggleClass('checked');
        $('input[name="TCC"]').not(':checked').prop("checked", true);
      });

      $('.switch_isW').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isW"]').not(':checked').prop("checked", true);
      });

      $('.switch_isMO').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isMO"]').not(':checked').prop("checked", true);
      });

      $('.switch_isMC').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isMC"]').not(':checked').prop("checked", true);
      });

      $('.switch_isRWS').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isRWS"]').not(':checked').prop("checked", true);
      });

      $('.switch_isKempinski').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isKempinski"]').not(':checked').prop("checked", true);
      });

      $('.switch_isHilton').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isHilton"]').not(':checked').prop("checked", true);
      });

      $('.switch_isGWP').click(function(){
        $(this).toggleClass('checked');
        $('input[name="isGWP"]').not(':checked').prop("checked", true);
      });

    });
  </script>