<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

class ControllerExtensionCreditCardPaycomet extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/paycomet');

		$this->load->model('extension/payment/paycomet');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home'),
			'separator' => $this->language->get('text_separator')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true),
			'separator' => $this->language->get('text_separator')
		);


		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}
		
		$data['cards'] = $this->model_extension_payment_paycomet->getCards($this->customer->getId());
		$data['delete'] = $this->url->link('extension/credit_card/paycomet/delete', 'card_id=', true);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$cards_total = count($data['cards']);

		$pagination = new Pagination();
		$pagination->total = $cards_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('extension/credit_card/paycomet', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cards_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($cards_total - 10)) ? $cards_total : ((($page - 1) * 10) + 10), $cards_total, ceil($cards_total / 10));
		
		$data['back'] = $this->url->link('account/account', '', true);
		$data['add'] = $this->url->link('extension/credit_card/paycomet/add', '', true);

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/credit_card/paycomet_list', $data));

		// Add Agree
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('extension/credit_card/paycomet');

		$this->load->model('extension/payment/paycomet');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['back'] = $this->url->link('extension/credit_card/paycomet', '', true);
		$data['add'] = $this->url->link('extension/credit_card/paycomet/addCard', '', true);

		$data['txt_url_paycomet'] = $this->paycomet_iframe_URL();

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/credit_card/paycomet_form', $data));
	}


	public function paycomet_iframe_URL(){
		$this->load->model('extension/payment/paycomet');

		$paycomet_client  = $this->config->get('payment_paycomet_client');
    	$paycomet_terminal  = $this->config->get('payment_paycomet_terminal');
    	$paycomet_password  = $this->config->get('payment_paycomet_password');

		$language = $this->language->get('code');

		$URLKO = $this->url->link('extension/credit_card/paycomet', '', true);
		$URLOK = $this->url->link('extension/credit_card/paycomet', '', true);

		$paycomet_order_ref = $this->customer->getId();

		$OPERATION = "1";
	
		$operation = 107;

		// Cálculo Firma
		$signature = md5($paycomet_client.$paycomet_terminal.$operation.$paycomet_order_ref.md5($paycomet_password));
		$fields = array
		(
			'MERCHANT_MERCHANTCODE' => $paycomet_client,
			'MERCHANT_TERMINAL' => $paycomet_terminal,
			'OPERATION' => $operation,
			'LANGUAGE' => $language,
			'MERCHANT_MERCHANTSIGNATURE' => $signature,
			'MERCHANT_ORDER' => $paycomet_order_ref,
			'URLOK' => $URLOK,
		    'URLKO' => $URLKO,
		    '3DSECURE' => 1
		);
		$query = http_build_query($fields);

		$url_paycomet = $this->model_extension_payment_paycomet->gerUrlPAYCOMET() . "?".$query;
		return $url_paycomet;
	}

	public function delete() {
		$this->load->language('extension/credit_card/paycomet');
		$this->load->model('extension/payment/paycomet');

		$card = $this->model_extension_payment_paycomet->getCard($this->customer->getId(),$this->request->get['card_id']);

		if (!empty($card['paycomet_iduser'])) {
			$this->model_extension_payment_paycomet->deleteCard($this->request->get['card_id']);
			$this->session->data['success'] = $this->language->get('text_success_card');
		} else {
			$this->session->data['error_warning'] = $this->language->get('text_fail_card');
		}
		$this->response->redirect($this->url->link('extension/credit_card/paycomet', '', true));
	}

	
}
