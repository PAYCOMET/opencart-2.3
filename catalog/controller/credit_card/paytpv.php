<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

class ControllerCreditCardPayTpv extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('credit_card/paytpv');

		$this->load->model('payment/paytpv');

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

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_type'] = $this->language->get('column_type');
		$data['column_digits'] = $this->language->get('column_digits');
		$data['column_action'] = $this->language->get('column_action');
		

		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_new_card'] = $this->language->get('button_new_card');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_back'] = $this->language->get('button_back');

		
		$data['cards'] = $this->model_payment_paytpv->getCards($this->customer->getId());
		$data['delete'] = $this->url->link('credit_card/paytpv/delete', 'card_id=', true);

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
		$pagination->url = $this->url->link('credit_card/paytpv', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cards_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($cards_total - 10)) ? $cards_total : ((($page - 1) * 10) + 10), $cards_total, ceil($cards_total / 10));
		
		$data['back'] = $this->url->link('account/account', '', true);
		$data['add'] = $this->url->link('credit_card/paytpv/add', '', true);

		// Add Agree
		
		if ($this->isOpencart2()){

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/credit_card/paytpv_list.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/credit_card/paytpv_list.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('credit_card/paytpv_list.tpl', $data));
			}
		}else{

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'		
			);


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/credit_card/paytpv_list_1.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/credit_card/paytpv_list_1.tpl';
			} else {
				$this->template = 'default/template/credit_card/paytpv_list_1.tpl';
			}
			$this->data = $data;
			$this->response->setOutput($this->render());


		}
	}

	public function add() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('credit_card/paytpv');

		$this->load->model('payment/paytpv');

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

		$data['heading_title'] = $this->language->get('heading_title');

		$data["text_streamline"] = $this->language->get('text_streamline');
		$data["txt_remember"] = $this->language->get('txt_remember');
		$data["txt_terms"] = $this->language->get('txt_terms');
		$data["txt_click_terms"] = $this->language->get('txt_click_terms');

		$data["txt_link_card"] = $this->language->get('txt_link_card');
		$data["txt_cancel"] = $this->language->get('txt_cancel');
		$data["txt_msg_accept"] = $this->language->get('txt_msg_accept');

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

		$data['button_back'] = $this->language->get('button_back');
		$data['button_add_card'] = $this->language->get('button_add_card');
		$data['back'] = $this->url->link('credit_card/paytpv', '', true);
		$data['add'] = $this->url->link('credit_card/paytpv/addCard', '', true);

		$data['txt_url_paytpv'] = $this->paytpv_iframe_URL();

		if ($this->isOpencart2()){

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/credit_card/paytpv_form.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/credit_card/paytpv_form.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('credit_card/paytpv_form.tpl', $data));
			}
		}else{

			
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'		
			);


			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/credit_card/paytpv_form.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/credit_card/paytpv_form.tpl';
			} else {
				$this->template = 'default/template/credit_card/paytpv_form.tpl';
			}
			$this->data = $data;
			$this->response->setOutput($this->render());
		}
	}


	public function paytpv_iframe_URL(){
		$this->load->model('payment/paytpv');

		$paytpv_client  = $this->config->get('paytpv_client');
    	$paytpv_terminal  = $this->config->get('paytpv_terminal');
    	$paytpv_password  = $this->config->get('paytpv_password');

		$language = $this->language->get('code');

		$URLKO = $this->url->link('credit_card/paytpv', '', true);
		$URLOK = $this->url->link('credit_card/paytpv', '', true);

		$paytpv_order_ref = $this->customer->getId();

		$OPERATION = "1";
	
		$operation = 107;

		// Cálculo Firma
		$signature = md5($paytpv_client.$paytpv_terminal.$operation.$paytpv_order_ref.md5($paytpv_password));
		$fields = array
		(
			'MERCHANT_MERCHANTCODE' => $paytpv_client,
			'MERCHANT_TERMINAL' => $paytpv_terminal,
			'OPERATION' => $operation,
			'LANGUAGE' => $language,
			'MERCHANT_MERCHANTSIGNATURE' => $signature,
			'MERCHANT_ORDER' => $paytpv_order_ref,
			'URLOK' => $URLOK,
		    'URLKO' => $URLKO,
		    '3DSECURE' => 1
		);
		$query = http_build_query($fields);

		$url_paytpv = $this->model_payment_paytpv->gerUrlPAYTPV() . "?".$query;
		return $url_paytpv;
	}

	public function delete() {
		$this->load->language('credit_card/paytpv');
		$this->load->model('payment/paytpv');

		$card = $this->model_payment_paytpv->getCard($this->customer->getId(),$this->request->get['card_id']);

		if (!empty($card['paytpv_iduser'])) {
			$this->model_payment_paytpv->deleteCard($this->request->get['card_id']);
			$this->session->data['success'] = $this->language->get('text_success_card');
		} else {
			$this->session->data['error_warning'] = $this->language->get('text_fail_card');
		}
		$this->response->redirect($this->url->link('credit_card/paytpv', '', true));
	}

	  /**
	 * @return bool
	 */
	protected function isOpencart2 ()
	{
		return version_compare(VERSION, 2, ">=");
	}

	
}
