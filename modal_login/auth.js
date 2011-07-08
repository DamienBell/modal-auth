
$(document).ready(function(){
	//select all the a tag with name equal to modal
    $('a[name=modal]').click(function(e) {
    	e.preventDefault();
    	//Get the A tag
        var id = $(this).attr('href');
        
        //Get the screen height and width
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();
        
        //Set height and width to mask to fill up the whole screen
        $('#mask').css({'width':maskWidth,'height':maskHeight});
        
        //transition effect
        $('#mask').fadeIn("fast");
        $('#mask').fadeTo("fast", 0.7);

        //Get the window height and width
        var winH = $(window).height();
        var winW = $(window).width();

        //Set the popup window to center
        $(id).css('top',  winH/2-$(id).height()/2);
        $(id).css('left', winW/2-$(id).width()/2);

        //transition effect
        $(id).fadeIn("fast");
      });

      //if close button is clicked
      $('.window .close').click(function (e) {
          //Cancel the link behavior
          e.preventDefault();
          $('#mask, .window').hide();
        });

      //if mask is clicked
      $('#mask').click(function () {
      	  $(this).hide();
          $('.window').hide();
      });

      //bind button clicks for form
      $('#login_button').click(logByModal);
      $('#join_button').click(joinByModal);
      $('a[href="#logout"]').click(logoutByModal);

});

////modal auth stuff
function logByModal(){
    var url= siteRoot+"/auth_api?command=login";
    var data= $('#login_modal_form').serialize();

    $.post(url, data, function(json){
        if(json.status== 'success'){
            createCookie('pp_auth', json.auth, json.days);
            window.location.reload();
        }else{
            //some failure msg
        }
    }, 'json');

    return false;
}

function joinByModal(){
    var url= siteRoot+"/auth_api?command=join";
    var data= $('#join_modal_form').serialize();

    $.post(url, data, function(json){
        if(json.status== 'success'){
            createCookie('pp_auth', json.auth, json.days);
            window.location.reload();
        }else{
            //failure message
        }
    }, 'json');
    return false;
}

function logoutByModal(){
    var url= siteRoot+"/auth_api?command=logout";
    $.post(url, function(json){
        window.location.reload();
    });
}

//cookie stuff
function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}