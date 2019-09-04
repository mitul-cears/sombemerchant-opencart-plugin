<?= $header; ?>

<?= $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-payment" data-toggle="tooltip" title="<?= $button_save ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel ?>" data-toggle="tooltip" title="<?= $button_cancel ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?= $heading_title ?></h1>
      <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['text'] ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
  <?php if ($error_warning) { ?>
      <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?= $error_warning ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i></h3>
      </div>

      <div class="panel-body">
        <form action="<?= $action ?>" method="post" enctype="multipart/form-data" id="form-payment" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?= $entry_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_status" id="input-status" class="form-control">
              <?php if ($sombemerchant_status) { ?>
                  <option value="1" selected="selected"><?= $text_enabled ?></option>
                  <option value="0"><?= $text_disabled ?></option>
              <?php } else { ?>
                  <option value="1"><?= $text_enabled ?></option>
                  <option value="0" selected="selected"><?= $text_disabled ?></option>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-api-auth-token"><?= $entry_api_auth_token ?></label>
            <div class="col-sm-10">
              <input type="text" name="sombemerchant_api_auth_token" value="<?= $sombemerchant_api_auth_token ?>" placeholder="<?= $entry_api_auth_token ?>" id="input-api-auth-token" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-api-secret"><?= $entry_api_secret ?></label>
            <div class="col-sm-10">
              <input type="text" name="sombemerchant_api_secret" value="<?= $sombemerchant_api_secret ?>" placeholder="<?= $entry_api_secret ?>" id="input-api-secret" class="form-control" />
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pending-status"><?= $entry_pending_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_pending_status_id" id="input-pending-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_pending_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paid-status"><?= $entry_paid_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_paid_status_id" id="input-paid-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_paid_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invalid-status"><?= $entry_invalid_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_invalid_status_id" id="input-invalid-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_invalid_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-expired-status"><?= $entry_expired_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_expired_status_id" id="input-expired-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_expired_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-canceled-status"><?= $entry_canceled_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_canceled_status_id" id="input-canceled-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_canceled_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-refunded-status"><?= $entry_refunded_status ?></label>
            <div class="col-sm-10">
              <select name="sombemerchant_refunded_status_id" id="input-refunded-status" class="form-control">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $sombemerchant_refunded_status_id) { ?>
                <option value="<?= $order_status['order_status_id'] ?>" selected="selected"><?= $order_status['name'] ?></option>
                <?php } else { ?>
                <option value="<?= $order_status['order_status_id'] ?>"><?= $order_status['name'] ?></option>
                <?php } ?>
              <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?= $help_total ?>"><?= $entry_total ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="sombemerchant_total" value="<?= $sombemerchant_total ?>" placeholder="<?= $entry_total ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
            
            <select name="sombemerchant_geo_zone_id" id="input-geo-zone" class="form-control">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $sombemerchant_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"
                      selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer ?>
