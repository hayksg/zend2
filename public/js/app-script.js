$(function(){

    /// For category  //////////////////////////////////////////////////////////////////////////

    $('.menu_vert').liMenuVert({
        delayShow:300,		//Задержка перед появлением выпадающего меню (ms)
        delayHide:300	    //Задержка перед исчезанием выпадающего меню (ms)
    });

    //  In order did not work parent element a  //

    $('ul.menu_vert li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

    //////////////////////////////////////////////////////////////////////////////////////////

});
