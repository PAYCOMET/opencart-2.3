<?php
class ModelPaymentPayTpv extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paytpv_customer` (
			  	`paytpv_iduser` int(11) UNSIGNED NOT NULL,
				`paytpv_tokenuser` VARCHAR(64) NOT NULL,
				`paytpv_cc` VARCHAR(32) NOT NULL,
				`paytpv_brand` VARCHAR(32) NULL,
				`id_customer` int(10) unsigned NOT NULL,
				`date` DATETIME NOT NULL,
				`card_desc` VARCHAR(32) NULL DEFAULT NULL,
			  PRIMARY KEY (`paytpv_iduser`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paytpv_order` (
			  	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  	`order_id` INT(11) NOT NULL,
			  	`id_customer` int(10) unsigned NOT NULL,
			  	`paytpv_iduser` int(11) UNSIGNED,
			  	`paytpv_tokenuser` VARCHAR(64),
			  	`paytpvagree` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
			  	`transaction_type` INT(11),
			  	`authcode` VARCHAR(254),
			  	`amount` DECIMAL( 10, 2 ) NOT NULL,
			  	`result` INT(11) NOT NULL DEFAULT 0,
			  	`date` DATETIME NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "paytpv_order_refund` (
			  	`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			  	`order_id` INT(11) NOT NULL,
			  	`authcode` VARCHAR(254),
			  	`amount` DECIMAL( 10, 2 ) NOT NULL,
			  	`result` INT(11) NOT NULL DEFAULT 0,
			  	`date` DATETIME NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paytpv_customer`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "paytpv_order`;");
	}

	public function getPaytpvOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytpv_order` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getPaytpvOrderRefund($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytpv_order_refund` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function totalCaptured($order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paytpv_order` WHERE `order_id` = '" . (int)$order_id . "' AND `result` = 1");

		return $qry->row['amount'];
	}

	public function totalRefundedOrder($order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paytpv_order_refund` WHERE `order_id` = '" . (int)$order_id . "'");

		return $qry->row['amount'];
	}

	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paytpv_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			return $order;
		} else {
			return false;
		}
	}

	
	public function addRefund($refund_data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paytpv_order_refund` SET `order_id` = '" . (int)$refund_data['order_id'] . "', `authcode` = '" . $this->db->escape($refund_data['authcode']) . "', `amount` = '" . $this->db->escape($refund_data['amount']) . "', `result` = '" . $this->db->escape($refund_data['result']) . "',`date` = NOW()");

		$paytpv_refund_id = $this->db->getLastId();

		return $paytpv_refund_id;
	}

	public function log($data, $title = null) {
		if ($this->config->get('paytpv_debug')) {
			$log = new Log('paytpv.log');
			$log->write($title . ': ' . json_encode($data));
		}
	}

	public function getTransaction($transaction_id) {
		$call_data = array(
			'METHOD' => 'GetTransactionDetails',
			'TRANSACTIONID' => $transaction_id,
		);

		return $this->call($call_data);
	}

	

	public function getOrderInfo($order_id){

      $query= $this->db->query("SELECT * FROM " . DB_PREFIX . "paytpv_order where order_id = " . $order_id);
      $row = current($query->rows);
      return $row;

    }

	
	protected function cleanReturn($data) {
		$data = explode('&', $data);

		$arr = array();

		foreach ($data as $k => $v) {
			$tmp = explode('=', $v);
			$arr[$tmp[0]] = urldecode($tmp[1]);
		}

		return $arr;
	}
}