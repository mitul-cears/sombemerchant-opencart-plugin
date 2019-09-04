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

        $defaults['sombemerchant_pending_status_id'] = 1;
        $defaults['sombemerchant_paid_status_id'] = 2;
        $defaults['sombemerchant_invalid_status_id'] = 10;
        $defaults['sombemerchant_expired_status_id'] = 14;
        $defaults['sombemerchant_canceled_status_id'] = 7;
        $defaults['sombemerchant_refunded_status_id'] = 11;

        $this->model_setting_setting->editSetting('sombemerchant', $defaults);
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "sombemerchant_order`;");
    }


}