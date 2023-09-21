if ($(window).width() >= 992) {
$('.sidebar-toggle').on('click',function(){
    var cls =  $('body').hasClass('sidebar-collapse');
    if(cls == true){
        localStorage.setItem('collapse',0);
    } else {
        localStorage.setItem('collapse',1);
    }

});

window.onload = function() {
    var collapse = localStorage.getItem('collapse');
    if(collapse == true){
        $('body').addClass('sidebar-collapse');
    } else if(collapse == false) {
        $('body').removeClass('sidebar-collapse');
    }
}
}