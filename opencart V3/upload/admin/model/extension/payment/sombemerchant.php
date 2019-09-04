<?php

class ModelExtensionPaymentSombeMerchant extends Model{
    
    public function install(){
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "sombemerchant_order` (
                `sombemerchant_order_id` INT(11) NOT NULL AUTO_INCREMENT,
                `order_id` INT(11) NOT NULL,
                `sombe_invoice_id` VARCHAR(120),
                `token` VARCHAR(100) NOT NULL,
                PRIMARY KEY (`sombemerchant_order_id`)
            ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
        ");

        $this->load->model('setting/setting');

        $defaults = array();

        $defaults['payment_sombemerchant_pending_status_id'] = 1;
        $defaults['payment_sombemerchant_paid_status_id'] = 2;
        $defaults['payment_sombemerchant_invalid_status_id'] = 10;
        $defaults['payment_sombemerchant_expired_status_id'] = 14;
        $defaults['payment_sombemerchant_canceled_status_id'] = 7;
        $defaults['payment_sombemerchant_refunded_status_id'] = 11;

        $this->model_setting_setting->editSetting('payment_sombemerchant', $defaults);
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sombemerchant_order`;");
    }


}