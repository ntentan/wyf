function resizer(){
  $('#menu > div').height($(window).height() - $('#header').height());
}

$(function(){
  $(window).resize(resizer);  
  resizer();
});