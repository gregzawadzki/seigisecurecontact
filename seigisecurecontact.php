<?php

/*
 * Created by / Stworzono przez SEIGI http://pl.seigi.eu/
 * MIT License
 * Utworzono  : Feb 17, 2018
 * Author     : SEIGI - Grzegorz Zawadzki <kontakt@seigi.eu>
 */


if (!defined('_PS_VERSION_'))
	exit;

class seigisecurecontact extends Module {

	protected $_html = '';
	protected $_postErrors = array();

	public function __construct() {
		$this->name = 'seigisecurecontact';
		$this->tab = 'frontend';
		$this->version = '1.0';

		$this->author = 'SEIGI Grzegorz Zawadzki';
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.7');


		parent::__construct();

		$this->displayName = $this->l('Secure Contact Form');
		$this->description = $this->l('Secure Contact Form with Google ReCaptcha.');
		$this->confirmUninstall = $this->l('Are you sure about removing these details?');
	}
	public function install() {
		if(parent::install() && $this->registerHook('displayHeader'))
			return true;
		return false;
	}
	
	public function hookdisplayHeader($hook_args) {
		if($this->context->controller->php_self = 'contact'){
			$this->smarty->assign(array(
				'recap_public' => Configuration::get('SRECAP_PUBLIC')
			));
			return $this->display(__FILE__, 'hookheader.tpl');
		}
	}
	public function reCapchaErrorTrnslate($error_code) {
		$r = array(
			'invalid-input-secret' => $this->l('The secret parameter is missing.'),
			'missing-input-response' => $this->l('The secret parameter is invalid or malformed.'),
			'invalid-input-response' => $this->l('The response parameter is missing.'),
			'bad-request' => $this->l('The response parameter is invalid or malformed.'),
			'missing-input-secret' => $this->l('The request is invalid or malformed'),
			'timeout-or-duplicate' => $this->l('You have already submited this form or waited too long to submit it. Refresh page first')
		);
		return $r[$error_code];
	}
	public function verifyReCaptcha($param) {
        /**
		 * Taken from: https://github.com/google/recaptcha/blob/master/src/ReCaptcha/RequestMethod/Post.php
         * PHP 5.6.0 changed the way you specify the peer name for SSL context options.
         * Using "CN_name" will still work, but it will raise deprecated errors.
         */
        $peer_key = version_compare(PHP_VERSION, '5.6.0', '<') ? 'CN_name' : 'peer_name';
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($param),
                // Force the peer to validate (not needed in 5.6.0+, but still works)
                'verify_peer' => true,
                // Force the peer validation to use www.google.com
                $peer_key => 'www.google.com',
            ),
        );
        $context = stream_context_create($options);
        return json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context), true);
	}
	public function getContent() {
		$output = null;

		if (Tools::isSubmit('submit'.$this->name))
		{
			$conv_value = strval(Tools::getValue('SRECAP_SECRET'));
			if (!$conv_value
			  || empty($conv_value))
				$output .= $this->displayError($this->l('Invalid Configuration value'));
			else
			{
				Configuration::updateValue('SRECAP_SECRET', $conv_value);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
			
			$conv_value = strval(Tools::getValue('SRECAP_PUBLIC'));
			if (!$conv_value
			  || empty($conv_value))
				$output .= $this->displayError($this->l('Invalid Configuration value'));
			else
			{
				Configuration::updateValue('SRECAP_PUBLIC', $conv_value);
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
			
		}
		$output .= $this->displayForm();
		$output .= '<div style="font-size: 1.3em; padding: 15px;">';
		$output .= '<p>'.$this->l('This module adds Google reCaptcha scripts to your site. Your site must have google API access to use ReCaptcha').'</p>';
		$output .= '<p>'.$this->l('You can obtain them from this URL').' <a href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a></p>';
		$output .= '</div>';
		return $output;
	}
	public function displayForm() {
		// Get default language
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		// Init Fields form array
		$fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Site Key'),
					'name' => 'SRECAP_PUBLIC',
					'size' => 60,
					'required' => true
				),
				
				array(
					'type' => 'text',
					'label' => $this->l('Secret Key'),
					'name' => 'SRECAP_SECRET',
					'size' => 60,
					'required' => true
				)
				
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button btn btn-default pull-right'
			)
		);

		$helper = new HelperForm();

		// Module, token and currentIndex
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

		// Language
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;

		// Title and toolbar
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;        // false -> remove toolbar
		$helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
				'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);

		// Load current value
		$helper->fields_value['SRECAP_SECRET'] = Configuration::get('SRECAP_SECRET');
		$helper->fields_value['SRECAP_PUBLIC'] = Configuration::get('SRECAP_PUBLIC');
		
		return $helper->generateForm($fields_form);
	}
}
