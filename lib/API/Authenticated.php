<?php

abstract class API_Authenticated extends API_API {

	protected $auth, $user;

	function __construct($config, $parameters) {
		parent::__construct($config, $parameters);
		

	}
	
	protected function requireOAuth() {

		$store = new sspmod_oauth_OAuthStore();
		$server = new sspmod_oauth_OAuthServer($store);
		
		$hmac_method = new OAuthSignatureMethod_HMAC_SHA1();
		$plaintext_method = new OAuthSignatureMethod_PLAINTEXT();
		
		$server->add_signature_method($hmac_method);
		$server->add_signature_method($plaintext_method);
		
		$req = OAuthRequest::from_request();
		list($consumer, $token) = $server->verify_request($req);
		
		$data = $store->getAuthorizedData($token->key);
		
		print_r($data); exit;

	}
	
	// Authenticate the user
	protected function auth() {
		$this->auth = new FoodleAuth($this->fdb);
		$this->auth->requireAuth(TRUE);
		
		if ($this->auth->isAuth()) {
			$this->user = $this->auth->getUser();
			return;
		}
		
		$this->requireOAuth();
		
	}
		
	protected function prepare() {
		$this->auth();
	}
	
}

