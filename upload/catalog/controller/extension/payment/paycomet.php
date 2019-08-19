<?php


class ControllerExtensionPaymentPaycomet extends Controller {

	public $url_paycomet = "https://api.paycomet.com/gateway/bnkgateway.php";
	private $_client = null;

	
	public function index() {
		$this->load->model('extension/payment/paycomet');
    	$this->load->language('extension/payment/paycomet');

    	$data['paycomet_iframe'] = $this->paycomet_iframe_URL();

    	$paycomet_client  = $this->config->get('payment_paycomet_client');
    	$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
    	$paycomet_password  = $this->config->get('payment_paycomet_password');
    	$data['paycomet_commerce_password'] = $this->config->get('payment_paycomet_commerce_password');

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

		$data['saved_cards'] = $this->model_extension_payment_paycomet->getCards($this->customer->getId());

		$data['order_id'] = $this->session->data['order_id'];

		// Add Agree
		$customer_id = (isset($this->session->data['customer_id']))?$this->session->data['customer_id']:0;
		$data['show_savecard'] = ($customer_id>0)?1:0;

		$this->model_extension_payment_paycomet->addOrderAgree($this->session->data['order_id'],$customer_id);
		
        return $this->load->view('extension/payment/paycomet', $data);
	}


	public function paycomet_iframe_URL(){
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$amount = number_format($this->currency->format($order_info['total'], $order_info['currency_code'], false, false)*100,0, '.', '');
		$paycomet_client  = $this->config->get('payment_paycomet_client');
    	$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
    	$paycomet_password  = $this->config->get('payment_paycomet_password');

    	$paycomet_client  = $this->config->get('payment_paycomet_client');
    	$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
    	$paycomet_password  = $this->config->get('payment_paycomet_password');
    	

		$currency_iso_code = $order_info['currency_code'];
		$language = $this->config->get('config_language');

		$URLKO = HTTPS_SERVER . 'index.php?route=extension/payment/paycomet/paymenterror';
		$URLOK = HTTPS_SERVER . 'index.php?route=extension/payment/paycomet/paymentreturn';

		$paycomet_order_ref = $this->session->data['order_id'];

		$importe  = number_format($amount/ 100, 2, ".","");

		$secure_pay = $this->isSecureTransaction($importe,0)?1:0;

		$OPERATION = "1";
		
		// Cálculo Firma
		$signature = md5($paycomet_client.$paycomet_terminal.$OPERATION.$paycomet_order_ref.$amount.$currency_iso_code.md5($paycomet_password));
		$fields = array
		(
			'MERCHANT_MERCHANTCODE' => $paycomet_client,
			'MERCHANT_TERMINAL' => $paycomet_terminal,
			'OPERATION' => $OPERATION,
			'LANGUAGE' => $language,
			'MERCHANT_MERCHANTSIGNATURE' => $signature,
			'MERCHANT_ORDER' => $paycomet_order_ref,
			'MERCHANT_AMOUNT' => $amount,
			'MERCHANT_CURRENCY' => $currency_iso_code,
			'URLOK' => $URLOK,
			'URLKO' => $URLKO,
			'3DSECURE' => $secure_pay
		);
		$query = http_build_query($fields);
		
		$url_paycomet = $this->url_paycomet . "?".$query;
		return $url_paycomet;
	}

	public function isSecureTransaction($importe,$card){
		$terminales = $this->config->get('payment_paycomet_terminales');
        $tdfirst = $this->config->get('payment_paycomet_tdfirst');
        $tdmin = $this->config->get('payment_paycomet_tdmin');
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

    public function isFirstPurchaseToken($customer_id, $card){
    	$customer_id = $this->session->data['customer_id'];
    	$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "paycomet_order WHERE id_customer = " . $customer_id . " AND paycomet_iduser = ". $card);
		return ($result->num_rows>0)?false:true;
		
    }

    public function saveOrderInfo(){
    	$this->load->model('checkout/order');

    	$paycomet_agree = $_POST["paycomet_agree"];
    	$order_id = $_POST["order_id"];

    	$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    	if ($this->session->data['customer_id']==$order_info["customer_id"]){
    		$this->load->model('extension/payment/paycomet');
    		$this->model_extension_payment_paycomet->saveOrderInfo($this->customer->getId(),$order_id,$paycomet_agree);
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
		$this->load->model('extension/payment/paycomet');
		$this->load->model('checkout/order');

		$this->language->load('extension/payment/paycomet');

		$error = false;

		if (isset($this->request->post['TransactionType']) &&  isset($this->request->post['Response'])){

			if ($this->request->post['TransactionType']==1){
				$importe  = number_format($this->request->post['Amount']/ 100, 2, ".","");
				$order_id = $this->request->post['Order'];
				$result = $this->request->post['Response']=='OK'?1:0;
				$sign = $this->request->post['ExtendedSignature'];
				$esURLOK = false;
				$paycomet_client  = $this->config->get('payment_paycomet_client');
	    		$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
	    		$pass  = $this->config->get('payment_paycomet_password');

				$local_sign = md5($paycomet_client.$this->request->post['TpvID'].$this->request->post['TransactionType'].$order_id.$this->request->post['Amount'].$this->request->post['Currency'].md5($pass).$this->request->post['BankDateTime'].$this->request->post['Response']);
				
				// Check Signature
				if ($sign!=$local_sign)	die('Error 1');

				if($result == 1 && isset($this->request->post['IdUser']) && isset($this->request->post['TokenUser'])){

					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_paycomet_order_status_id'));
					
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
						
						$this->model_extension_payment_paycomet->updateOrder($data);

						$paycomet_order_info = $this->model_extension_payment_paycomet->getOrderInfo($order_id);
						$saveCard = ($paycomet_order_info["paycometagree"] && $paycomet_order_info["id_customer"]>0);


						if ($saveCard && !empty($this->request->post['IdUser']) && !empty($this->request->post['TokenUser'])) {
							
							$result = $this->infoUser( $this->request->post['IdUser'],$this->request->post['TokenUser']);
							$order_info = $this->model_checkout_order->getOrder($order_id);


							$this->model_extension_payment_paycomet->addCard($this->request->post['IdUser'],$this->request->post['TokenUser'],$result['DS_MERCHANT_PAN'],$result['DS_CARD_BRAND'],"",$order_info["customer_id"]);
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
				$pass = $this->config->get('payment_paycomet_password');
				$local_sign = md5($this->config->get('payment_paycomet_client').$this->config->get('payment_paycomet_terminal').$this->request->post['TransactionType'].$ref.$this->request->post['DateTime'].md5($pass));
				// Check Signature
				if ($sign!=$local_sign)	die('Error 2');
				
				$result = $this->infoUser( $this->request->post['IdUser'],$this->request->post['TokenUser']);
				
				$this->model_extension_payment_paycomet->addCard($this->request->post['IdUser'],$this->request->post['TokenUser'],$result['DS_MERCHANT_PAN'],$result['DS_CARD_BRAND'],"",$this->request->post['Order']);

				print "Add User procesado";
			}
		
		}else{
			print "Error. No Transaction. No Response";
		}
	}


	public function validPassword($customer_id,$passwd){
		$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = " . $customer_id . " AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $passwd . "'))))) OR password = '" . md5($passwd) . "') AND status = '1'");
		
		return ($result->num_rows>0)?true:false;
		
	}

	public function directPay()
	{

		$this->load->model('extension/payment/paycomet');
		$this->load->model('checkout/order');
		$this->load->language('extension/payment/paycomet');

		$card = $_POST["card"];
		$commerce_password = (isset($_POST["commerce_password"]))?$_POST["commerce_password"]:"";

		$customer_id = $this->session->data['customer_id'];

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	

		if ($this->config->get('payment_paycomet_commerce_password')){
	        if (!$this->validPassword($customer_id,$commerce_password)){
	        	$json['error'] = $this->language->get('err_commerce_password');
	        	$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));

	        }
	    }

		if (isset($this->session->data['order_id']) && $card!=""){

			$card_data = $this->model_extension_payment_paycomet->getCard($this->session->data['customer_id'],$card);
			// Si la tarjeta no está asociada al usuario->Error
			if (empty($card_data)){
				$json['error'] = $this->language->get('err_card_user');
			}

			if (!isset($json['error'])){
				$order_id = $this->session->data['order_id'];
				$order_info = $this->model_checkout_order->getOrder($order_id);

				$amount = number_format($this->currency->format($order_info['total'], $order_info['currency_code'], false, false)*100,0, '.', '');	

				$importe  = number_format($amount/ 100, 2, ".","");
				
				$paycomet_client  = $this->config->get('payment_paycomet_client');
	    		$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
	    		$paycomet_password  = $this->config->get('payment_paycomet_password');
	    		$currency_iso_code = $order_info['currency_code'];


	    		$secure_pay = $this->isSecureTransaction($importe,$card)?1:0;

	    		if ($secure_pay){
					$language = $this->config->get('config_language');

					$paycomet_order_ref = $this->session->data['order_id'];

					$URLKO = HTTPS_SERVER . 'index.php?route=extension/payment/paycomet/paymenterror';
					$URLOK = HTTPS_SERVER . 'index.php?route=extension/payment/paycomet/paymentreturn';

	    			$OPERATION = "109"; //exec_purchase_token
	    			// Cálculo Firma
	    			$signature = md5($paycomet_client.$card_data['paycomet_iduser'].$card_data['paycomet_tokenuser'].$paycomet_terminal.$OPERATION.$paycomet_order_ref.$amount.$currency_iso_code.md5($paycomet_password));
		
					$fields = array
					(
						'MERCHANT_MERCHANTCODE' => $paycomet_client,
						'MERCHANT_TERMINAL' => $paycomet_terminal,
						'OPERATION' => $OPERATION,
						'LANGUAGE' => $language,
						'MERCHANT_MERCHANTSIGNATURE' => $signature,
						'MERCHANT_ORDER' => $paycomet_order_ref,
						'MERCHANT_AMOUNT' => $amount,
						'MERCHANT_CURRENCY' => $currency_iso_code,
						'IDUSER' => $card_data['paycomet_iduser'],
						'TOKEN_USER' => $card_data['paycomet_tokenuser'],
						'3DSECURE' => 1,
						'URLOK' => $URLOK,
						'URLKO' => $URLKO
					);

					$query = http_build_query($fields);
					
					$url_paycomet = $this->url_paycomet . "?".$query;

					$json['ACSURL'] = $url_paycomet;
	    		}else{

		    		$charge = $this->executePurchase($card_data['paycomet_iduser'],$card_data['paycomet_tokenuser'],$paycomet_client,$paycomet_terminal,$paycomet_password,$currency_iso_code,$importe,$order_id);

		    		if ( ( int ) $charge[ 'DS_RESPONSE' ] == 1 ) {

		    			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_paycomet_order_status_id'));
		    			$OPERATION = "1";

						$data = array(
							'Order'       	=> $order_id,
							'IdUser'       	=> $card_data['paycomet_iduser'],
							'TokenUser' 	=> $card_data['paycomet_tokenuser'],
							'TransactionType'   => $OPERATION,
							'AuthCode'      => $charge['DS_MERCHANT_AUTHCODE'],
							'Amount'      	=> $importe,
							'Response'    	=> $charge['DS_RESPONSE']
						);
						
						$this->model_extension_payment_paycomet->updateOrder($data);

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
		// Para entornos de desarrollo con certificados propios
		$context = stream_context_create(array(
			'ssl' => array(
				'verify_peer' => true,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		));

        if (null == $this->_client)
            $this->_client = new SoapClient('https://api.paycomet.com/gateway/xml_bankstore.php?wsdl', array('stream_context' => $context));
 
        return $this->_client;
    }

    private function infoUser($DS_IDUSER, $DS_TOKEN_USER)
    {
        $this->load->model('extension/payment/paycomet');

        $DS_MERCHANT_MERCHANTCODE = $this->config->get('payment_paycomet_client');
        $DS_MERCHANT_TERMINAL = $this->config->get('payment_paycomet_terminal');
        $DS_MERCHANT_PASSWORD = $this->config->get('payment_paycomet_password');
        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_MERCHANT_PASSWORD);
        
        return $this->getClient()->info_user(
            $DS_MERCHANT_MERCHANTCODE, $DS_MERCHANT_TERMINAL, $DS_IDUSER, $DS_TOKEN_USER, $DS_MERCHANT_MERCHANTSIGNATURE, $_SERVER['REMOTE_ADDR']);
    }


    private function executePurchase($paycomet_iduser, $paycomet_tokenuser, $paycomet_client,$paycomet_terminal,$paycomet_password,$currency_iso_code,$importe,$orderid)
    {
        
        $DS_MERCHANT_MERCHANTCODE = $paycomet_client;
        $DS_IDUSER = $paycomet_iduser;
        $DS_TOKEN_USER = $paycomet_tokenuser;
        $DS_MERCHANT_AMOUNT = round($importe * 100);
        $DS_MERCHANT_ORDER = $orderid;
        $DS_MERCHANT_CURRENCY = $currency_iso_code;
        $DS_MERCHANT_TERMINAL = $paycomet_terminal;
        $DS_MERCHANT_MERCHANTSIGNATURE = hash('sha512', $DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_MERCHANT_AMOUNT . $DS_MERCHANT_ORDER . $paycomet_password);
        $DS_ORIGINAL_IP = $_SERVER['REMOTE_ADDR'];
        $DS_MERCHANT_PRODUCTDESCRIPTION = $orderid;
        $DS_MERCHANT_OWNER = '';

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

}
