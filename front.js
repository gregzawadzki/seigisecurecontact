/*
*
* Created by / Stworzono przez SEIGI http://pl.seigi.eu/
* MIT License
* Utworzono  : Feb 17, 2018
* Author     : SEIGI - Grzegorz Zawadzki <kontakt@seigi.eu>
* 
*/


$(function(){
	$('#submitMessage').after('<div style="text-align: center; margin: 10px"><div class="g-recaptcha" data-sitekey="' + seigi_recap_pub + '" style="display: inline-block"></div></div>');
});