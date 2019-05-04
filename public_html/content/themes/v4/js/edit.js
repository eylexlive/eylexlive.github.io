$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
  
  var clipboard = new Clipboard('.ipbutton', {
    text: function() {
      return IP_ADRESS;
    }
  });

  clipboard.on('success', function(e) {
    swal({
      title: "Başarılı!!",
      text: "Serverin IP adresini başarılı bir şekilde kopyaladın.",
      type: "success",
      confirmButtonText: "Tamam"
    });
  });

  clipboard.on('error', function(e) {
    swal("Oppps!", "Kopyalama sırasında bir hata gerçekleşti.", "error")
  });
});