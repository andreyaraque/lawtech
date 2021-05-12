$(document).ready(function() {
  // enable menu
  $('#nav').burgerMenu({
    translate: true,
    menuWidth: '50%',
    menuHeight: '100%',
    menuBorder: 'none',
    animateSpeed: 500,
    linkBorderBottom: 'none',
    keepButtonNextToMenu: true,
    overlay: '#ffffff'
  });

  // send the from through AJAX
  $('#contactForm').submit(function(e) {
    e.preventDefault();
    let contactFormData = $('#contactForm').serialize();

    // Fire off the request to /form.php
    $.ajax({
      url: "enviar.php",
      type: "post",
      data: contactFormData,
      error: function (xhr, ajaxOptions, thrownError) {
        $("#mail-message").html('<p class="error">El correo electrónico no era possible enviar</p>');
      }
    }).done(function(response) {
      console.log(response);
      console.log(response.status);

      if (response.status === 'sent') {
        $("#mail-message").html('<p class="success">El correo electrónico esta enviado</p>');
        $('#contactForm').trigger("reset");
      } else {
        $("#mail-message").html(`<p class="error">${response.message}</p>`);
      }
    });

    //console.log(a);
    //console.log($('#contactForm').serializeArray());
  });

});
