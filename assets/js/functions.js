function closeAlert() {
  $(".btn-close").parent().toggleClass("active");
}
$.fn.extend({
    toggleText: function(a, b){
        return this.text(this.text() == b ? a : b);
    }
});
$('li.dropdown').click(function(){
  $(this).find("ul.dropdown").toggleClass("active");
});
$('.seeMore').click(function(){
  var href = $(this).attr("data-href");
  $(this).toggleText('Zobacz', 'Ukryj');
  $("body").find('.seeMoreContent[data-content="'+href+'"]').toggleClass("active");
});
$('.seeModal').click(function(){
  if ($(".modal.active")[0]){
    $(".modal.active").removeClass("active");
  }
  $(this).next(".modal").toggleClass("active");
});
$('.close').click(function(){
  $(this).parent().parent().toggleClass("active");
});
$('button.upload').click(function(){
  $(this).next("input[type='file']").click();
});
