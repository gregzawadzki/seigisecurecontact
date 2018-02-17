<?php

/* 
 * Wszelkie prawa zastrzeżone
 * Zabrania się modyfikacji używania i udostępniania kodu bez zgody lub odpowiedniej licencji.
 * Utworzono  : Feb 17, 2018
 * Author     : SEIGI - Grzegorz Zawadzki <kontakt@seigi.eu>
 */


class ContactController extends ContactControllerCore
{
	public function postProcess() {
		if (Tools::isSubmit('submitMessage'))
		{
			require_once _PS_MODULE_DIR_ . 'seigisecurecontact/seigisecurecontact.php';
			$m = new seigisecurecontact();
			$response = $m->verifyReCaptcha(array(
				'secret' => Configuration::get('SRECAP_SECRET'),
				'response' => Tools::getValue('g-recaptcha-response'),
				'remoteip' => $_SERVER["REMOTE_ADDR"],
			));
			var_dump($response);
			if($response['success']){
				parent::postProcess();
			} else {
				$this->errors[] = Tools::displayError('You did not pass verification of reCaptcha and thus your form was not submitted. Verify yourself with reCaptcha first');
				foreach ($response['error-codes'] as $erc) {
					$this->errors[] = $m->reCapchaErrorTrnslate($erc);
				}
			}

		}
	}
}