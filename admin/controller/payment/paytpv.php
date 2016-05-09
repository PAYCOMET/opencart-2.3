	<?php
class Controllerpaymentpaytpv extends Controller {
	private $error = array();
	public $url_paytpv = "https://secure.paytpv.com/gateway/bnkgateway.php";
	private $_client = null;

	public function index() {

		$this->load->language('payment/paytpv');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');


		/* END ERRORS */
		

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paytpv', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if ($this->isOpencart2())
				$this->response->redirect($this->url->link("extension/payment", "token=" . $this->session->data['token'], "SSL"));
			else
				$this->redirect($this->url->link("extension/payment", "token=" . $this->session->data['token'], "SSL"));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['breadcrumbs'] = array();

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home')
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_payment')
   		);

   		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('payment/paytpv', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title')
   		);

   		
   		$data['action'] = $this->url->link('payment/paytpv', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->id       = 'content';
		

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		/* END COMMON STUFF */


		/* START FIELDS */
		$data['extension_class'] = 'payment';
		$data['tab_class'] = 'htabs';

		if (isset($this->request->post['paytpv_status'])) {
			$data['paytpv_status'] = $this->request->post['paytpv_status'];
		} else {
			$data['paytpv_status'] = $this->config->get('paytpv_status');
		}

		if (isset($this->request->post['paytpv_client'])) {
			$data['paytpv_client'] = $this->request->post['paytpv_client'];
		} else {
			$data['paytpv_client'] = $this->config->get('paytpv_client');
		}

		if (isset($this->request->post['paytpv_terminal'])) {
			$data['paytpv_terminal'] = $this->request->post['paytpv_terminal'];
		} else {
			$data['paytpv_terminal'] = $this->config->get('paytpv_terminal');
		}

		if (isset($this->request->post['paytpv_password'])) {
			$data['paytpv_password'] = $this->request->post['paytpv_password'];
		} else {
			$data['paytpv_password'] = $this->config->get('paytpv_password');
		}

		if (isset($this->request->post['paytpv_terminales'])) {
			$data['paytpv_terminales'] = $this->request->post['paytpv_terminales'];
		} else {
			$data['paytpv_terminales'] = $this->config->get('paytpv_terminales');
		}

		if (isset($this->request->post['paytpv_tdfirst'])) {
			$data['paytpv_tdfirst'] = $this->request->post['paytpv_tdfirst'];
		} else {
			$data['paytpv_tdfirst'] = $this->config->get('paytpv_tdfirst');
		}

		if (isset($this->request->post['paytpv_tdmin'])) {
			$data['paytpv_tdmin'] = $this->request->post['paytpv_tdmin'];
		} else {
			$data['paytpv_tdmin'] = $this->config->get('paytpv_tdmin');
		}

		if (isset($this->request->post['paytpv'])) {
			$data['paytpv'] = $this->request->post['paytpv'];
		} else {
			$data['paytpv'] = $this->config->get('paytpv');
		}

		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paytpv_order_status_id'])) {
			$data['paytpv_order_status_id'] = $this->request->post['paytpv_order_status_id'];
		} else {
			$data['paytpv_order_status_id'] = $this->config->get('paytpv_order_status_id');
		}

		if (isset($this->request->post['paytpv_sort_order'])) {
			$data['paytpv_sort_order'] = $this->request->post['paytpv_sort_order'];
		} else {
			$data['paytpv_sort_order'] = $this->config->get('paytpv_sort_order');
		}

		if (isset($this->request->post['paytpv_commerce_password'])) {
			$data['paytpv_commerce_password'] = $this->request->post['paytpv_commerce_password'];
		} else {
			$data['paytpv_commerce_password'] = $this->config->get('paytpv_commerce_password');
		}

		$data['entry_client'] = $this->language->get('entry_client');
		$data['entry_terminal'] = $this->language->get('entry_terminal');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_terminales'] = $this->language->get('entry_terminales');
		$data['entry_tdfirst'] = $this->language->get('entry_tdfirst');
		$data['entry_tdmin'] = $this->language->get('entry_tdmin');
		$data['entry_commerce_password'] = $this->language->get('entry_commerce_password');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_stored_cards'] = $this->language->get('entry_stored_cards');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['text_tdfirst'] = $this->language->get('text_tdfirst');

		$data['text_secure'] = $this->language->get('text_secure');
		$data['text_nosecure'] = $this->language->get('text_nosecure');
		$data['text_both'] = $this->language->get('text_both');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
			
		$data['help_client'] = $this->language->get('help_client');
		$data['help_terminal'] = $this->language->get('help_terminal');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_tdfirst'] = $this->language->get('help_tdfirst');
		$data['help_tdmin'] = $this->language->get('help_tdmin');
		$data['help_commerce_password'] = $this->language->get('help_commerce_password');

		if (isset($this->error['client'])) {
			$data['error_client'] = $this->error['client'];
		} else {
			$data['error_client'] = '';
		}

		if (isset($this->error['terminal'])) {
			$data['error_terminal'] = $this->error['terminal'];
		} else {
			$data['error_terminal'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['tdmin'])) {
			$data['error_tdmin'] = $this->error['tdmin'];
		} else {
			$data['error_tdmin'] = '';
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paytpv_order_status_id'])) {
			$data['paytpv_order_status_id'] = $this->request->post['paytpv_order_status_id'];
		} else {
			$data['paytpv_order_status_id'] = $this->config->get('paytpv_order_status_id');
		}

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		/*
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		*/
		
		// Set different template for Opencart 2 as it uses Bootstrap and a left column
		if ($this->isOpencart2())
		{
			$this->renderTemplate("payment/paytpv.tpl", $data, array(
				"header",
				"column_left",
				"footer",
			));
		}
		else
		{
			$this->renderTemplate("payment/paytpv_1.tpl", $data, array(
				"header",
				"footer",
			));
		}
        //$this->response->setOutput($this->load->view('payment/paytpv.tpl', $data));


	}

	private function validate($errors = array()) {
		if (!$this->user->hasPermission('modify', 'payment/paytpv')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paytpv_client']) {
			$this->error['client'] = $this->language->get('error_client');
		}

		if (!$this->request->post['paytpv_terminal']) {
			$this->error['terminal'] = $this->language->get('error_terminal');
		}

		if (!$this->request->post['paytpv_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['paytpv_tdmin']) {
			if (!is_numeric($this->request->post['paytpv_tdmin']))
			$this->error['tdmin'] = $this->language->get('error_tdmin');
		}


		foreach ($errors as $error) {
			if (isset($this->request->post[$this->name . '_' . $error]) && !$this->request->post[$this->name . '_' . $error]) {
				$this->error[$error] = $this->language->get('error_' . $error);
			}
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	public function install() {
		$this->load->model('payment/paytpv');
		$this->model_payment_paytpv->install();
	}

	public function uninstall(){
		$this->load->model('payment/paytpv');
		$this->model_payment_paytpv->uninstall();
	}

	public function order() {
		if ($this->config->get('paytpv_status')) {
			$this->load->language('payment/paytpv_order');

			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('payment/paytpv');

			$paytpv_info = $this->model_payment_paytpv->getPaytpvOrder($order_id);

			if ($paytpv_info) {
				$data['text_payment'] = $this->language->get('text_payment');
				$data['text_capture'] = $this->language->get('text_capture');
				$data['text_transaction'] = $this->language->get('text_transaction');
				$data['text_capture_status'] = $this->language->get('text_capture_status');
				$data['text_amount_authorised'] = $this->language->get('text_amount_authorised');
				$data['text_amount_captured'] = $this->language->get('text_amount_captured');
				$data['text_amount_refunded'] = $this->language->get('text_amount_refunded');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_full_refund'] = $this->language->get('text_full_refund');
				$data['text_partial_refund'] = $this->language->get('text_partial_refund');
				$data['text_loading'] = $this->language->get('text_loading');

				$data['entry_capture_amount'] = $this->language->get('entry_capture_amount');
				$data['entry_capture_complete'] = $this->language->get('entry_capture_complete');
				$data['entry_full_refund'] = $this->language->get('entry_full_refund');
				$data['entry_note'] = $this->language->get('entry_note');
				$data['entry_amount'] = $this->language->get('entry_amount');

				$data['button_capture'] = $this->language->get('button_capture');
				$data['button_refund'] = $this->language->get('button_refund');
				$data['button_void'] = $this->language->get('button_void');

				$data['tab_capture'] = $this->language->get('tab_capture');
				$data['tab_refund'] = $this->language->get('tab_refund');

				$data['token'] = $this->session->data['token'];

				$data['order_id'] = $this->request->get['order_id'];

				$data['capture_status'] = ($paytpv_info['result'])?"OK":"KO";

				$data['total'] = $paytpv_info['amount'];

				$captured = number_format($this->model_payment_paytpv->totalCaptured($this->request->get['order_id']), 2);

				$data['captured'] = $captured;

				$data['capture_remaining'] = number_format($paytpv_info['amount'] - $captured, 2);

				$refunded = number_format($this->model_payment_paytpv->totalRefundedOrder($this->request->get['order_id']), 2);

				$data['refunded'] = $refunded;

				return $this->load->view('payment/paytpv_order', $data);
			}
		}
	}

	public function transaction() {
		$this->load->language('payment/paytpv_order');
		//$this->load->language('payment/paytpv_transaction');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_transaction'] = $this->language->get('column_transaction');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_type'] = $this->language->get('column_type');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_pending_reason'] = $this->language->get('column_pending_reason');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_partial_refund'] = $this->language->get('button_partial_refund');
		$data['button_total_refund'] = $this->language->get('button_total_refund');
		$data['button_resend'] = $this->language->get('button_resend');

		$data['transactions'] = array();

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('payment/paytpv');

		$paytpv_info = $this->model_payment_paytpv->getOrder($order_id);

		if ($paytpv_info){

			$results = $this->model_payment_paytpv->getPaytpvOrderRefund($order_id);
			$total_refunded = 0;
			$data2 = array();
			if ($results){
				
				foreach ($results as $result) {

					$data2[] = array(
						'transaction_id' => $result['authcode'],
						'amount'         => $result['amount'],
						'date'           => $result['date'],
						'type'           => "refund",
						'type_text'           => $this->language->get('txt_refund'),
						'refund'         => ""
					);
					$total_refunded += $result['amount'];
				}
			}


			$data['transactions'][] = array(
					'total_refunded'  => $total_refunded,
					'transaction_id' => $paytpv_info['authcode'],
					'amount'         => $paytpv_info['amount'],
					'date'           => $paytpv_info['date'],
					'type'           => "capture",
					'type_text'           => $this->language->get('txt_payment'),
					'refund'         => $this->url->link('payment/paytpv/refund', 'token=' . $this->session->data['token'] . '&order_id=' . $order_id, true),
					
				);


			$data['transactions'] = array_merge($data['transactions'],$data2);

		}

		if ($this->isOpencart2())
			$this->response->setOutput($this->load->view('payment/paytpv_transaction.tpl', $data));
		else{
			$this->template = 'payment/paytpv_transaction.tpl';
			$this->data = $data;
	        $this->children = array(
	            'common/header',
	            'common/footer'
	        );
	        
	        $this->response->setOutput($this->render());
		}
	}

	public function refund(){
		
		$this->load->model('payment/paytpv');
		$this->load->model('sale/order');
		$this->load->language('payment/paytpv');

		$transaction = $this->model_payment_paytpv->getOrder($this->request->get['order_id']);

		if ($transaction) {
			$this->document->setTitle($this->language->get('heading_refund'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_payment'),
				'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('payment/paytpv', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_refund'),
				'href' => $this->url->link('payment/paytpv/refund', 'order_id=' . $this->request->get['order_id'] . '&token=' . $this->session->data['token'], 'SSL')
			);

			$data['transaction_reference'] = $transaction['order_id'];
			$data['transaction_amount'] = number_format($transaction['amount'], 2);
			$data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $transaction['order_id'], 'SSL');

			$data['token'] = $this->session->data['token'];

			$data['heading_refund'] = $this->language->get('heading_refund');

			$data['entry_transaction_reference'] = $this->language->get('entry_transaction_reference');
			$data['entry_transaction_amount'] = $this->language->get('entry_transaction_amount');
			$data['entry_refund_amount'] = $this->language->get('entry_refund_amount');

			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_refund'] = $this->language->get('button_refund');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('payment/paytpv_refund.tpl', $data));
		} else {
			return $this->forward('error/not_found');
		}
	}

	public function doRefund(){
		$this->load->model('payment/paytpv');
		$this->load->language('payment/paytpv');
		$this->load->model('sale/order');


		$json = array();

		if (isset($this->request->post['order_id']) && isset($this->request->post['amount'])) {

			$order_id = $this->request->post['order_id'];
			$totalRefund = $this->request->post['totalRefund'];
			
			$order_info = $this->model_sale_order->getOrder($order_id);
			$paytpv_order_info = $this->model_payment_paytpv->getOrderInfo($order_id);

			if ($order_info) {

				$total_refunded = 0;
				$results = $this->model_payment_paytpv->getPaytpvOrderRefund($order_id);
				if ($results){
					
					foreach ($results as $result) {
						$total_refunded += $result['amount'];
					}
				}

				// Partial Refund
				if ($totalRefund==0)
					$amount = $this->request->post['amount'];
				// Total Refund
				else{
					$amount = $paytpv_order_info["amount"] - $total_refunded;
				}

				if ($amount>0){

	    			$currency_iso_code = $order_info['currency_code'];

					$DS_MERCHANT_MERCHANTCODE = $this->config->get('paytpv_client');
			        $DS_IDUSER = $paytpv_order_info["paytpv_iduser"];
			        $DS_TOKEN_USER = $paytpv_order_info["paytpv_tokenuser"];
			        $DS_MERCHANT_ORDER = $order_id;
			        $DS_MERCHANT_CURRENCY = $currency_iso_code;
			        $DS_MERCHANT_TERMINAL = $this->config->get('paytpv_terminal');
			        $DS_MERCHANT_AUTHCODE = $paytpv_order_info["authcode"];
			        $DS_MERCHANT_MERCHANTSIGNATURE = sha1($DS_MERCHANT_MERCHANTCODE . $DS_IDUSER . $DS_TOKEN_USER . $DS_MERCHANT_TERMINAL . $DS_MERCHANT_AUTHCODE . $DS_MERCHANT_ORDER . $this->config->get('paytpv_password'));
			        $DS_ORIGINAL_IP = $_SERVER['REMOTE_ADDR'];
			        $DS_MERCHANT_AMOUNT = round($amount * 100);

					$result = $this->getClient()->execute_refund(
			            $DS_MERCHANT_MERCHANTCODE,
			            $DS_MERCHANT_TERMINAL,
			            $DS_IDUSER,
			            $DS_TOKEN_USER,
			            $DS_MERCHANT_AUTHCODE,
			            $DS_MERCHANT_ORDER,
			            $DS_MERCHANT_CURRENCY,
			            $DS_MERCHANT_MERCHANTSIGNATURE,
			            $DS_ORIGINAL_IP,
			            $DS_MERCHANT_AMOUNT
			        );

					if ($result[ 'DS_RESPONSE']==1){
						$json['success'] = $this->language->get('text_refund_issued');
						$total_refunded += $amount;

						$json['total_refunded'] = number_format($total_refunded,2,".","");

						$data = array(
							'order_id' => $order_id,
							'authcode' => $result['DS_MERCHANT_AUTHCODE'],
							'result' => $result[ 'DS_RESPONSE'],
							'amount' => $amount,
						);

						$this->model_payment_paytpv->addRefund($data);
					} else {
						$json['error'] = "Error (".$result['DS_ERROR_ID'].")";
					}
				}else{
					$json['error'] = $this->language->get('error_min_amount_refund');
				}
			} else {
				$json['error'] = $this->language->get('error_missing_order');
			}
		} else {
			$json['error'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	

	public function orderAction() {
		if ($this->config->get('paytpv_status')) {
			
			if (isset($this->request->get['order_id'])) {
				$order_id = $this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			$this->load->model('payment/paytpv');

			$paytpv_info = $this->model_payment_paytpv->getPaytpvOrder($order_id);

			if ($paytpv_info) {
				$data['text_payment'] = $this->language->get('text_payment');
				$data['text_capture'] = $this->language->get('text_capture');
				$data['text_transaction'] = $this->language->get('text_transaction');
				$data['text_capture_status'] = $this->language->get('text_capture_status');
				$data['text_amount_authorised'] = $this->language->get('text_amount_authorised');
				$data['text_amount_captured'] = $this->language->get('text_amount_captured');
				$data['text_amount_refunded'] = $this->language->get('text_amount_refunded');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_full_refund'] = $this->language->get('text_full_refund');
				$data['text_partial_refund'] = $this->language->get('text_partial_refund');
				$data['text_loading'] = $this->language->get('text_loading');

				$data['entry_capture_amount'] = $this->language->get('entry_capture_amount');
				$data['entry_capture_complete'] = $this->language->get('entry_capture_complete');
				$data['entry_full_refund'] = $this->language->get('entry_full_refund');
				$data['entry_note'] = $this->language->get('entry_note');
				$data['entry_amount'] = $this->language->get('entry_amount');

				$data['button_capture'] = $this->language->get('button_capture');
				$data['button_refund'] = $this->language->get('button_refund');
				$data['button_void'] = $this->language->get('button_void');

				$data['tab_capture'] = $this->language->get('tab_capture');
				$data['tab_refund'] = $this->language->get('tab_refund');

				$data['token'] = $this->session->data['token'];

				$data['order_id'] = $this->request->get['order_id'];

				$data['capture_status'] = ($paytpv_info['result'])?"OK":"KO";

				$data['total'] = $paytpv_info['amount'];

				$captured = number_format($this->model_payment_paytpv->totalCaptured($this->request->get['order_id']), 2);

				$data['captured'] = $captured;

				$data['capture_remaining'] = number_format($paytpv_info['amount'] - $captured, 2);

				$refunded = number_format($this->model_payment_paytpv->totalRefundedOrder($this->request->get['order_id']), 2);

				$data['refunded'] = $refunded;

				if ($this->isOpencart2()){
					return $this->load->view('payment/paytpv_order.tpl', $data);
				}else{
					$data['refund_link'] = $this->url->link('payment/paytpv/refund', 'token=' . $this->session->data['token'], 'SSL');
					$this->data = $data;
					$this->template = 'payment/paytpv_order.tpl';
            		$this->response->setOutput($this->render());
				}
			}
		}
	}

	private function getClient()
    {
        if (null == $this->_client)
            $this->_client = new SoapClient('https://secure.paytpv.com/gateway/xml_bankstore.php?wsdl');
 
        return $this->_client;
    }

    /**
	 * Map template handling for different Opencart versions
	 *
	 * @param string $template
	 * @param array  $data
	 * @param array  $common_children
	 * @param bool   $echo
	 */
	protected function renderTemplate ($template, $data, $common_children = array(), $echo = TRUE)
	{
		if ($this->isOpencart2())
		{
			foreach ($common_children as $child)
			{
				$data[$child] = $this->load->controller("common/" . $child);
			}

			$html = $this->load->view($template, $data);
		}
		else
		{
			foreach ($data as $field => $value)
			{
				$this->data[$field] = $value;
			}

			$this->template = $template;

			$this->children = array();

			foreach ($common_children as $child)
			{
				$this->children[] = "common/" . $child;
			}

			$html = $this->render();
		}

		if ($echo)
		{
			return $this->response->setOutput($html);
		}

		return $html;
	}

	/**
	 * @param string $url
	 * @param int    $status
	 */
	protected function redirect ($url, $status = 302)
	{
		if ($this->isOpencart2())
		{
			$this->response->redirect($url, $status);
		}
		else
		{
			parent::redirect($url, $status);
		}
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