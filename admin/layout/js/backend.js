$(function(){
  'use strict';
  // triger the selectBoxIt

  //Dashboard
  $('.toggle-info').click(function(){
     $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
     if($(this).hasClass('selected')){
       $(this).html('<i class="fa fa-minus fa-lg"></i>');
     }else {
       $(this).html('<i class="fa fa-plus fa-lg"></i>');
     }
  });


  $("select").selectBoxIt({
    autoWidth: false
  });
  // hide place holder on focas
  $('[placeholder]').focus(function(){
    $(this).attr('data-text',$(this).attr('placeholder'));
    $(this).attr('placeholder','')
  }).blur(function(){
    $(this).attr('placeholder',$(this).attr('data-text'));
  });
  // add astrisk on required fields
  $('input').each(function(){
    if($(this).attr('required') === 'required'){
    $(this).after('<span class="asterisk">*</span>');
    }
  });
  // convert password field into text field on hover
 // var passField = $('.password');
 //  $('.show-pass').hover(function() {
 //    passField.attr('type', 'text');
 //  },function(){
 //    passField.attr('type', 'password');
 //
 //  });
//confirmation message on button
$('.confirm').click(function(){
  return confirm('Are You Sure?')
});

// category view option
$('.cat h3').click(function(){
$(this).next('.full-view').fadeToggle(200);
});
$('.option span').click(function(){
  $(this).addClass('active').siblings('span').removeClass('active');
  if($(this).data('view') === 'full'){
    $('.cat .full-view').fadeIn(200);
  }else{
    $('.cat .full-view').fadeOut(200);
  }
});

// show delete btn on child cats
$('.child-link').hover(function(){
  $(this).find('.show-delete').fadeIn(400);
}, function(){
  $(this).find('.show-delete').fadeOut(400);

});

});
