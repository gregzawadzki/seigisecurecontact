/* 
 * Wszelkie prawa zastrzeżone
 * Zabrania się modyfikacji używania i udostępniania kodu bez zgody lub odpowiedniej licencji.
 * Utworzono  : Feb 19, 2018
 * Author     : SEIGI - Grzegorz Zawadzki <kontakt@seigi.eu>
 */


$(function(){
	$('#submitMessage').after('<div style="text-align: center; margin: 10px"><div class="g-recaptcha" data-sitekey="' + seigi_recap_pub + '" style="display: inline-block"></div></div>');
});