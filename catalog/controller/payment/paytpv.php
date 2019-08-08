<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
class ControllerPaymentPayTPV extends Controller {

	public $url_paytpv = "https://api.paycomet.com/gateway/bnkgateway.php";
	private $_client = null;

	
	public function index() {
		$this->load->model('payment/paytpv');
    	$this->load->language('payment/paytpv');

    	$data['paytpv_iframe'] = $this->paytpv_iframe_URL();

    	$paytpv_client  = $this->config->get('paytpv_client');
    	$paytpv_terminal  = $this->config->get('paytpv_terminal');
    	$paytpv_password  = $this->config->get('paytpv_password');
    	$data['paytpv_commerce_password'] = $this->config->get('paytpv_commerce_password');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_wait'] = $this->language->get('text_wait');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}
		
		// Tarjetas Tokenizadas
		$data['text_credit_cards'] = $this->language->get('text_credit_cards');
		$data['text_new_card'] = $this->language->get('text_new_card');
		$data['text_pay'] = $this->language->get('text_pay');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_commerce_password'] = $this->language->get('text_commerce_password');

		$data['text_loading'] = $this->language->get('text_loading');
		

		$data['text_conditions1'] = $this->language->get('text_conditions1');
		$data['text_conditions2'] = $this->language->get('text_conditions2');
		$data['text_conditions3'] = $this->language->get('text_conditions3');
		$data['text_conditions4'] = $this->language->get('text_conditions4');
		$data['text_conditions5'] = $this->language->get('text_conditions5');
		$data['text_conditions6'] = $this->language->get('text_conditions6');
		$data['text_conditions7'] = $this->language->get('text_conditions7');
		$data['text_conditions8'] = $this->language->get('text_conditions8');
		$data['text_conditions9'] = $this->language->get('text_conditions9');
		$data['text_conditions10'] = $this->language->get('text_conditions10');
		$data['text_conditions11'] = $this->language->get('text_conditions11');
		$data['text_conditions12'] = $this->language->get('text_conditions12');
		$data['text_conditions13'] = $this->language->get('text_conditions13');
		$data['text_conditions14'] = $this->language->get('text_conditions14');
		$data['text_conditions15'] = $this->language->get('text_conditions15');
		$data['text_conditions16'] = $this->language->get('text_conditions16');
		$data['text_conditions17'] = $this->language->get('text_conditions17');
		$data['text_conditions18'] = $this->language->get('text_conditions18');


		$data["text_streamline"] = $this->language->get('text_streamline');
		$data["txt_remember"] = $this->language->get('txt_remember');
		$data["txt_terms"] = $this->language->get('txt_terms');


		$data['saved_cards'] = $this->model_payment_paytpv->getCards($this->customer->getId());

		$data['order_id'] = $this->session->data['order_id'];

		// Add Agree
		$this->model_payment_paytpv->addOrderAgree($this->session->data['order_id'],$this->session->data['customer_id']);
		if ($this->isOpencart2()){
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytpv.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/payment/paytpv.tpl', $data);
			} else {
				return $this->load->view('payment/paytpv.tpl', $data);
			}
		}else{
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytpv.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/paytpv.tpl';
			} else {
				$this->template = 'default/template/payment/sagepay_direct.tpl';
			}
			$this->data = $data;
			$this->render();

		}
	}


	public function paytpv_iframe_URL(){
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$amount = number_format($this->currency->format($order_info['total'], $order_info['currency_code'], false, false)*100,0, '.', '');
		$paytpv_client  = $this->config->get('paytpv_client');
    	$paytpv_terminal  = $this->config->get('paytpv_terminal');
    	$paytpv_password  = $this->config->get('paytpv_password');

    	$paytpv_client  = $this->config->get('paytpv_client');
    	$paytpv_terminal  = $this->config->get('paytpv_terminal');
    	$paytpv_password  = $this->config->get('paytpv_password');
    	

		$currency_iso_code = $order_info['currency_code'];
		$language = $this->config->get('config_language');

		$URLKO = HTTPS_SERVER . 'index.php?route=payment/paytpv/paymenterror';
		$URLOK = HTTPS_SERVER . 'index.php?route=payment/paytpv/paymentreturn';

		$paytpv_order_ref = $this->session->data['order_id'];

		$importe  = number_format($amount/ 100, 2, ".","");

		$secure_pay = $this->isSecureTransaction($importe,0)?1:0;

		$OPERATION = "1";
		
		// Cálculo Firma
		$signature = hash('sha512',$paytpv_client.$paytpv_terminal.$OPERATION.$paytpv_order_ref.$amount.$currency_iso_code.md5($paytpv_password));
		$fields = array
		(
			'MERCHANT_MERCHANTCODE' => $paytpv_client,
			'MERCHANT_TERMINAL' => $paytpv_terminal,
			'OPERATION' => $OPERATION,
			'LANGUAGE' => $language,
			'MERCHANT_MERCHANTSIGNATURE' => $signature,
			'MERCHANT_ORDER' => $paytpv_order_ref,
			'MERCHANT_AMOUNT' => $amount,
			'MERCHANT_CURRENCY' => $currency_iso_code,
			'URLOK' => $URLOK,
			'URLKO' => $URLKO,
			'3DSECURE' => $secure_pay
		);
		$query = http_build_query($fields);
		
		$url_paytpv = $this->url_paytpv . "?".$query;
		return $url_paytpv;
	}

	public function isSecureTransaction($importe,$card){
		$terminales = $this->config->get('paytpv_terminales');
        $tdfirst = $this->config->get('paytpv_tdfirst');
        $tdmin = $this->config->get('paytpv_tdmin');
        // Transaccion Segura:
        
        // Si solo tiene Terminal Seguro
        if ($terminales==0)
            return true;   
        // Si esta definido que el pago es 3d secure y no estamos usando una tarjeta tokenizada
        if ($tdfirst && $card==0)
            return true;
        // Si se supera el importe maximo para compra segura
        if ($terminales==2 && ($tdmin>0 && $tdmin < $importe))
            return true;
         // Si esta definido como que la primera compra es Segura y es la primera compra aunque este tokenizada
        if ($terminales==2 && $tdfirst && $card>0 && $this->isFirstPurchaseToken($this->session->data['customer_id'],$card))
            return true;
        
        return false;
    }

    public function isFirstPurchaseToken($customer_id,$card){
    	$customer_id = $this->session->data['customer_id'];
    	$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "paytpv_order WHERE id_customer = " . $customer_id . " AND paytpv_iduser = ". $card);
		return ($result->num_rows>0)?false:true;
		
    }

    public function saveOrderInfo(){
    	$this->load->model('checkout/order');

    	$paytpv_agree = $_POST["paytpv_agree"];
    	$order_id = $_POST["order_id"];

    	$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    	if ($this->session->data['customer_id']==$order_info["customer_id"]){
    		$this->load->model('payment/paytpv');
    		$this->model_payment_paytpv->saveOrderInfo($this->customer->getId(),$order_id,$paytpv_agree);
    	}   	
    }

    public function paymentReturn(){
    	$data['url'] = $this->url->link('checkout/checkout');
    	$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
    }

    public function paymentError(){
    	$data['url'] = $this->url->link('checkout/checkout');
		$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
    }


	public function callback()
	{
		$this->load->model('payment/paytpv');
		$this->load->model('checkout/order');

		$this->language->load('payment/paytpv');

		$error = false;

		if (isset($this->request->post['TransactionType']) &&  isset($this->request->post['Response'])){

			if ($this->request->post['TransactionType']==1){
				$importe  = number_format($this->request->post['Amount']/ 100, 2, ".","");
				$order_id = $this->request->post['Order'];
				$result = $this->request->post['Response']=='OK'?1:0;
				$sign = $this->request->post['ExtendedSignature'];
				$esURLOK = false;
				$paytpv_client  = $this->config->get('paytpv_client');
	    		$paytpv_terminal  = $this->config->get('paytpv_terminal');
	    		$pass  = $this->config->get('paytpv_password');

				$local_sign = md5($paytpv_client.$this->request->post['TpvID'].$this->request->post['TransactionType'].$order_id.$this->request->post['Amount'].$this->request->post['Currency'].md5($pass).$this->request->post['BankDateTime'].$this->request->post['Response']);
				
				// Check Signature
				if ($sign!=$local_sign)	die('Error 1');

				if($result == 1 && isset($this->request->post['IdUser']) && isset($this->request->post['TokenUser'])){

					if ($this->isOpencart2())
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paytpv_order_status_id'));
					else
						$this->model_checkout_order->confirm($order_id, $this->config->get('paytpv_order_status_id'));

					if ($this->request->post['TransactionType']==1){
						$data = array(
							'Order'       	=> $this->request->post['Order'],
							'IdUser'       	=> $this->request->post['IdUser'],
							'TokenUser' 	=> $this->request->post['TokenUser'],
							'TransactionType'      => $this->request->post['TransactionType'],
							'AuthCode'      => $this->request->post['AuthCode'],
							'Amount'      	=> $importe,
							'Response'    	=> $result
						);
						
						$this->model_payment_paytpv->updateOrder($data);

						$paytpv_order_info = $this->model_payment_paytpv->getOrderInfo($order_id);
						$saveCard = $paytpv_order_info["paytpvagree"];


						if ($saveCard && !empty($this->request->post['IdUser']) && !empty($this->request->post['TokenUser'])) {
							
							$result = $this->infoUser( $this->request->post['IdUser'],$this->request->post['TokenUser']);
							$order_info = $this->model_checkout_order->getOrder($order_id);


							$this->model_payment_paytpv->addCard($this->request->post['IdUser'],$this->request->post['TokenUser'],$result['DS_MERCHANT_PAN'],$result['DS_CARD_BRAND'],"",$order_info["customer_id"]);
						}
						print "Pedido procesado";
					}else{
						print "Procesado " . $this->request->post['TransactionType'];
					}
				}
			// Add User
			}else if ($this->request->post['TransactionType']==107){

				$ref = $this->request->post['Order'];
				$sign = $this->request->post['Signature'];
				$esURLOK = false;
				$pass = $this->config->get('paytpv_password');
				$local_sign = md5($this->config->get('paytpv_client').$this->config->get('paytpv_terminal').$this->request->post['TransactionType'].$ref.$this->request->post['DateTime'].md5($pass));
				// Check Signature
				if ($sign!=$local_sign)	die('Error 2');
				
				$result = $this->infoUser( $this->request->post['IdUser'],$this->request->post['TokenUser']);
				
				$this->model_payment_paytpv->addCard($this->request->post['IdUser'],$this->request->post['TokenUser'],$result['DS_MERCHANT_PAN'],$result['DS_CARD_BRAND'],"",$this->request->post['Order']);

				print "Add User procesado";
			}
		
		}else{
			print "Error. No Transaction. No Response";
		}
	}


	public function validPassword($customer_id,$passwd){
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . $customer_id . " AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $passwd . "'))))) OR password = '" . md5($passwd) . "') AND status = '1' AND approved = '1'");
		
		return ($result->num_rows>0)?true:false;
		
		
	}

	public function directPay()
	{

		$this->load->model('payment/paytpv');
		$this->load->model('checkout/order');
		$this->load->language('payment/paytpv');

		$card = $_POST["card"];
		$commerce_password = (isset($_POST["commerce_password"]))?$_POST["commerce_password"]:"";

		$customer_id = $this->session->data['customer_id'];

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($this->config->get('paytpv_commerce_password')){
	        if (!$this->validPassword($customer_id,$commerce_password)){
	        	$json['error'] = $this->language->get('err_commerce_password');
	        	$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));

	        }
	    }

		if (isset($this->session->data['order_id']) && $card!=""){

			$card_data = $this->model_payment_paytpv->getCard($this->session->data['customer_id'],$card);
			// Si la tarjeta no está asociada al usuario->Error
			if (empty($card_data)){
				$json['error'] = $this->language->get('err_card_user');
			}

			if (!isset($json['error'])){
				$order_id = $this->session->data['order_id'];
				$order_info = $this->model_checkout_order->getOrder($order_id);

				$amount = number_format($this->currency->format($order_info['total'], $order_info['currency_code'], false, false)*100,0, '.', '');	

				$importe  = number_format($amount/ 100, 2, ".","");
				
				$paytpv_client  = $this->config->get('paytpv_client');
	    		$paytpv_terminal  = $this->config->get('paytpv_terminal');
	    		$paytpv_password  = $this->config->get('paytpv_password');
	    		$currency_iso_code = $order_info['currency_code'];


	    		$secure_pay = $this->isSecureTransaction($importe,$card)?1:0;

	    		if ($secure_pay){
					$language = $this->config->get('config_language');

					$paytpv_order_ref = $this->session->data['order_id'];

					$URLKO = HTTPS_SERVER . 'index.php?route=payment/paytpv/paymenterror';
					$URLOK = HTTPS_SERVER . 'index.php?route=payment/paytpv/paymentreturn';

	    			$OPERATION = "109"; //exec_purchase_token
	    			// Cálculo Firma
	    			$signature = md5($paytpv_client.$card_data['paytpv_iduser'].$card_data['paytpv_tokenuser'].$paytpv_terminal.$OPERATION.$paytpv_order_ref.$amount.$currency_iso_code.md5($paytpv_password));
		
					$fields = array
					(
						'MERCHANT_MERCHANTCODE' => $paytpv_client,
						'MERCHANT_TERMINAL' => $paytpv_terminal,
						'OPERATION' => $OPERATION,
						'LANGUAGE' => $language,
						'MERCHANT_MERCHANTSIGNATURE' => $signature,
						'MERCHANT_ORDER' => $paytpv_order_ref,
						'MERCHANT_AMOUNT' => $amount,
						'MERCHANT_CURRENCY' => $currency_iso_code,
						'IDUSER' => $card_data['paytpv_iduser'],
						'TOKEN_USER' => $card_data['paytpv_tokenuser'],
						'3DSECURE' => 1,
						'URLOK' => $URLOK,
						'URLKO' => $URLKO
					);

					$query = http_build_query($fields);
					
					$url_paytpv = $this->url_paytpv . "?".$query;

					$json['ACSURL'] = $url_paytpv;
	    		}else{

		    		$charge = $this->executePurchase($card_data['paytpv_iduser'],$card_data['paytpv_tokenuser'],$paytpv_client,$paytpv_terminal,$paytpv_password,$currency_iso_code,$importe,$order_id);

		    		if ( ( int ) $charge[ 'DS_RESPONSE' ] == 1 ) {

		    			if ($this->isOpencart2())
		    				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('paytpv_order_status_id'));
		    			else
		    				$this->model_checkout_order->confirm($order_id, $this->config->get('paytpv_order_status_id'));
		    			$OPERATION = "1";

						$data = array(
							'Order'       	=> $order_id,
							'IdUser'       	=> $card_data['paytpv_iduser'],
							'TokenUser' 	=> $card_data['paytpv_tokenuser'],
							'TransactionType'   => $OPERATION,
							'AuthCode'      => $charge['DS_MERCHANT_AUTHCODE'],
							'Amount'      	=> $importe,
							'Response'    	=> $charge['DS_RESPONSE']
						);
						
						$this->model_payment_paytpv->updateOrder($data);

						$json['redirect']  = $this->url->link('checkout/success', '', 'SSL');
						
					}else{

						$json['error']  = $this->language->get('err_error') . $charge['DS_ERROR_ID'];

						$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
					}
				}
			}
		
		}else{
			$json['redirect']  = $this->url->link('account/login', '', 'SSL');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}


	private function getClient()
    {
        if (null == $this->_client)
            $this->_client = new SoapClient('https://api.paycomet.com/gateway/xml_bankstore.php?wsdl');
 
        return $this->_client;
    }

    private function infoUser($DS_IDUSER, $DS_TOKEN_USER)
    {
        $this->load->model('payment/paytpv');

        $DS_MERCHANT_MERCHANTCODE = $this->config->get('paytpv_client');
        $DS_MERCHANT_TERMINAL = $this->config->get('paytpv_terminal');
        $DS_MERCHANT_PASSWORD = $this->config->get('paytpv_password');
        $DS_MERCHANT_MERCHANTSIGNATURE = sha1($DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_MERCHANT_PASSWORD);
        
        return $this->getClient()->info_user(
            $DS_MERCHANT_MERCHANTCODE, $DS_MERCHANT_TERMINAL, $DS_IDUSER, $DS_TOKEN_USER, $DS_MERCHANT_MERCHANTSIGNATURE, $_SERVER['REMOTE_ADDR']);
    }


    private function executePurchase($paytpv_iduser, $paytpv_tokenuser, $paytpv_client,$paytpv_terminal,$paytpv_password,$currency_iso_code,$importe,$orderid)
    {
        
        $DS_MERCHANT_MERCHANTCODE = $paytpv_client;
        $DS_IDUSER = $paytpv_iduser;
        $DS_TOKEN_USER = $paytpv_tokenuser;
        $DS_MERCHANT_AMOUNT = round($importe * 100);
        $DS_MERCHANT_ORDER = $orderid;
        $DS_MERCHANT_CURRENCY = $currency_iso_code;
        $DS_MERCHANT_TERMINAL = $paytpv_terminal;
        $DS_MERCHANT_MERCHANTSIGNATURE = sha1($DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_MERCHANT_AMOUNT . $DS_MERCHANT_ORDER . $paytpv_password);
        $DS_ORIGINAL_IP = $_SERVER['REMOTE_ADDR'];
        $DS_MERCHANT_PRODUCTDESCRIPTION = $orderid;
        $DS_MERCHANT_OWNER = '';

        /*print_r(array($DS_MERCHANT_MERCHANTCODE,
            $DS_MERCHANT_TERMINAL,
            $DS_IDUSER,
            $DS_TOKEN_USER,
            $DS_MERCHANT_AMOUNT,
            $DS_MERCHANT_ORDER,
            $DS_MERCHANT_CURRENCY,
            $DS_MERCHANT_MERCHANTSIGNATURE,
            $DS_ORIGINAL_IP,
            $DS_MERCHANT_PRODUCTDESCRIPTION,
            $DS_MERCHANT_OWNER));

        exit;
		*/
        $DS_MERCHANT_OWNER = ''; /*@TODO: Set owner*/
        return $this->getClient()->execute_purchase(
            $DS_MERCHANT_MERCHANTCODE,
            $DS_MERCHANT_TERMINAL,
            $DS_IDUSER,
            $DS_TOKEN_USER,
            $DS_MERCHANT_AMOUNT,
            $DS_MERCHANT_ORDER,
            $DS_MERCHANT_CURRENCY,
            $DS_MERCHANT_MERCHANTSIGNATURE,
            $DS_ORIGINAL_IP,
            $DS_MERCHANT_PRODUCTDESCRIPTION,
            $DS_MERCHANT_OWNER
        );
    }


    /**
	 * @return bool
	 */
	protected function isOpencart2 ()
	{
		return version_compare(VERSION, 2, ">=");
	}
}
?>
