
jQuery(document).ready(function(){jQuery('#abh_settings').find('input[type=radio]').bind('click',function(){abh_submitSettings();});jQuery('#abh_settings').find('.abh_show_extra_description').bind('click',function(){jQuery('#abh_settings').find('.abh_extra_description').show();jQuery('#abh_settings').find('.abh_show_extra_description').hide();});jQuery('form').attr('enctype','multipart/form-data');if(jQuery('#description').length>0){jQuery('#description').parents('.form-table:last').before(jQuery('#abh_settings'));jQuery('.abh_description_author').append('<table></table>');jQuery('.abh_description_author').find('table').append(jQuery('#description').parents('tr:last'));}
jQuery('#abh_subscribe_subscribe').bind('click',function(event){if(event)
event.preventDefault();if(abh_validateEmail(jQuery('#abh_subscribe_email').val())){jQuery.getJSON('https://api.squirrly.co/sq/users/subscribe?callback=?',{email:jQuery('#abh_subscribe_email').val(),url:jQuery('#abh_subscribe_url').val()},function(data){jQuery.getJSON(abh_Query.ajaxurl,{action:'abh_settings_subscribe',nonce:abh_Query.nonce});jQuery('#abh_option_subscribe').hide();jQuery('#abh_option_social').show();if(data.result=="success"){jQuery('#abh_option_social').prepend('<div id="abh_subscribe_confirmation">Thank you!</div>');}});}else{alert('The email is not valid! Please enter a valid email address. Thank you');}});jQuery('#abh_theme_select').bind('change',function(){jQuery('#abh_box_preview').addClass('abh_loading');jQuery('#abh_box_preview').html('');jQuery.getJSON(abh_Query.ajaxurl,{action:'abh_get_box',user_id:jQuery('#user_id').val(),abh_theme:jQuery('#abh_theme_select').find(":selected").val(),nonce:abh_Query.nonce},function(data){jQuery('#abh_box_preview').removeClass('abh_loading');if(typeof data.box!=="undefined"){jQuery('#abh_box_preview').html(data.box);}});});});function abh_validateEmail($email){var emailReg=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;if(!emailReg.test($email)){return false;}else{return true;}}
function abh_submitSettings(){jQuery.getJSON(abh_Query.ajaxurl,{action:'abh_settings_update',data:jQuery('#abh_settings').find('form').serialize(),nonce:abh_Query.nonce});}
function abh_getBox(){}