var f = ["background: linear-gradient(135deg, rgba(248,97,97,1) 0%,rgba(105,81,255,1) 100%)","color: white","padding: 13px 70px","line-height: 50px"].join(";");
console.log('%cCraftWeb 2018 v5 | coded by BerkPW @ CraftWeb Software Team',f);
// console.log('%cDur!','color: #ff0000;font-size: 50px;text-shadow: 0 0 2px #000;font-family: Arial, Helvetica, sans-serif;');
// console.log('%cBu, geliştiriciler için tasarlanmış bir tarayıcı özelliğidir. Biri sana bir özelliği etkinleştirmek veya birinin hesabını ele geçirmek için bir şeyi kopyalayıp buraya yapıştırmanı söylediyse, bu bir dolandırıcılıktır ve bunu yapmanı söyleyen kişi sen bunu yaptığında senin hesabına erişebilecektir!','color: #000;font-size: 20px;font-family: Arial, Helvetica, sans-serif;');
// console.log('%cDaha fazla bilgi almak için https://help.craftweb.co/ adresine göz atın.','color: #000;font-size: 20px;font-family: Arial, Helvetica, sans-serif;');
//console.log('');

function modalGoster(url) {
  $.ajax({
    url: url,
    success: function(data) {
      $('#pModal').html(data);
      $('#pModal').modal("show");
    },
  });
  return false;
}
$("body").append('<div id="pModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>');