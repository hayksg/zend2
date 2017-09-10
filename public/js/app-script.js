$(function(){

    //  For category  /////////////////////////////////////////////////////////////////////////////

    $('.menu_vert').liMenuVert({
        delayShow:300,		//Задержка перед появлением выпадающего меню (ms)
        delayHide:300	    //Задержка перед исчезанием выпадающего меню (ms)
    });

    //  In order did not work parent element a  ///////////////////////////////////////////////////

    $('ul.menu_vert li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

    //  Do not show single breagcrumb  ////////////////////////////////////////////////////////////

    if ($('ul.breadcrumb li').length == 0) {
        $('ul.breadcrumb').remove();
    }

    //  For confirm plugin  ///////////////////////////////////////////////////////////////////////

    $('.confirm-plugin').jConfirmAction({
        question: 'Are you sure?',
        noText: 'Cancel'
    });

    //  For input file field on the form  /////////////////////////////////////////////////////////

    if ($(document).width() < 479) {
        $(":file").jfilestyle({inputSize: "100%"});
        $('form div.jfilestyle').removeClass('jfilestyle-corner');
        $('form div.jfilestyle input:first-child').css('margin-bottom', '10px');

    } else {
        $(":file").jfilestyle({inputSize: "350px"});
    }

    // back-to-top  ///////////////////////////////////////////////////////////////////////////////

    $(window).scroll(function () {
        if ($(this).scrollTop() > 1500) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });

    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    $('#back-to-top').tooltip('show');

    //  End  //////////////////////////////////////////////////////////////////////////////////////
});
