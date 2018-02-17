{*
*
* Created by / Stworzono przez SEIGI http://pl.seigi.eu/
* MIT License
* Utworzono  : Feb 17, 2018
* Author     : SEIGI - Grzegorz Zawadzki <kontakt@seigi.eu>
* 
*}


<script type="text/javascript">
	$(function(){
		$('#submitMessage').after('<div style="text-align: center; margin: 10px"><div class="g-recaptcha" data-sitekey="{$recap_public}" style="display: inline-block"></div></div>');
	});
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>