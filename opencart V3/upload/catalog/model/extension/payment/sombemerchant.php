<?php

class ModelExtensionPaymentSombeMerchant extends Model {
  public function addOrder($data) {
    $this->db->query("INSERT INTO `" . DB_PREFIX . "sombemerchant_order` SET `order_id` = '" . (int)$data['order_id'] . "', `token` = '" . $this->db->escape($data['token']) . "', `sombe_invoice_id` = '" . $this->db->escape($data['sombe_invoice_id']) . "'");
  }

  public function getOrder($order_id) {
    $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "sombemerchant_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

    return $query->row;
  }

  public function getMethod($address, $total) {
    $this->load->language('extension/payment/sombemerchant');

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_sombemerchant_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

    if ($this->config->get('payment_sombemerchant_total') > 0 && $this->config->get('payment_sombemerchant_total') > $total) {
      $status = false;
    } elseif (!$this->config->get('payment_sombemerchant_geo_zone_id')) {
      $status = true;
    } elseif ($query->num_rows) {
      $status = true;
    } else {
      $status = false;
    }

    $method_data = array();

    if ($status) {
      $method_data = array(
        'code'		 => 'sombemerchant',
        'title'		 => $this->language->get('text_title'),
        'terms'		 => '',
        'sort_order' => $this->config->get('payment_sombemerchant_sort_order')
      );
    }

    return $method_data;
  }
}
