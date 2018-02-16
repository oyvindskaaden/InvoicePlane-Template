<!DOCTYPE html>
<html lang="<?php echo trans('cldr'); ?>">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>
		<?php echo get_setting('custom_title', 'InvoicePlane', true); ?>
		- <?php echo trans('invoice'); ?> <?php echo $invoice->invoice_number; ?>
	</title>

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<link rel="stylesheet"
		  href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom.css">

</head>
<body>

	<!--pre><?php print_r($custom_fields); ?></pre-->

	<!-- Code for crating array of family groups and the length of the lease defined in the invoice-->
	<?php 
	$families = array();

	foreach ($items as $item) {
		if (in_array($item->item_description, $families) == false) {
			$families[] = $item->item_description;
		} 
	}
	?>

	<!-- Code for summing the groups and their discount -->
	<?php 
	$sums = array();
	$d_sums = array();
	$key = 0;
	
	foreach ($families as $value) {
		$sums[$key] = 0;
		$d_sums[$key] = 0;
		foreach ($items as $item) {
			if ($item->item_description == $value){
				$sums[$key] += $item->item_total;
				$d_sums[$key] += $item->item_discount;
				//echo "<h3>" . $sums[$key] . " and " . $d_sums[$key] . " and " . $key . "</h3>";
			}
			
		}
		//echo "<h3>" . $sums[$key] . " and " . $d_sums[$key] . "</h3>";
		$key++;
	}
	$key = 0;
	?>

<div class="container">

	<div id="content">

		<div class="webpreview-header">

			<h2><?php echo trans('invoice') . ' ' . $invoice->invoice_number; ?></h2>

			<div class="btn-group">
                <?php if ($invoice->sumex_id == NULL) : ?>
                <a href="<?php echo site_url('guest/view/generate_invoice_pdf/' . $invoice_url_key); ?>"
                   class="btn btn-primary">
                    <?php else : ?>
                    <a href="<?php echo site_url('guest/view/generate_sumex_pdf/' . $invoice_url_key); ?>"
                       class="btn btn-primary">
                        <?php endif; ?>
                        <i class="fa fa-print"></i> <?php echo trans('download_pdf'); ?>
                    </a>
                    <?php if (get_setting('enable_online_payments') == 1 && $invoice->invoice_balance > 0) { ?>
                        <a href="<?php echo site_url('guest/payment_information/form/' . $invoice_url_key); ?>"
                           class="btn btn-success">
                            <i class="fa fa-credit-card"></i> <?php echo trans('pay_now'); ?>
                        </a>
                    <?php } ?>
            </div>

		</div>

		<hr>

		<?php echo $this->layout->load_view('layout/alerts'); ?>

		<?php if ($flash_message) { ?>
			<div class="alert alert-info">
				<?php echo $flash_message; ?>
			</div>
		<?php } else {
			echo '<br>';
		} ?>

		<div class="invoice">
			<div style="max-width: 30%; max-height: 20%">
				<?php
				if ($logo = invoice_logo()) {
					echo $logo . '<br><br>';
				}
				?>
			</div>


			<div class="row">
				<div class="col-xs-12 col-md-6 col-lg-5">
					<h3><?php _trans('client'); ?></h3>
					<hr>
					<h4><?php _htmlsc($invoice->client_name); ?></h4>
					<p><?php if ($invoice->client_vat_id) {
							echo lang("vat_id_short") . ": " . $invoice->client_vat_id . '<br>';
						} ?>
						<?php if ($invoice->client_tax_code) {
							echo lang("tax_code_short") . ": " . $invoice->client_tax_code . '<br>';
						} ?>
						<?php if ($invoice->client_address_1) {
							echo htmlsc($invoice->client_address_1) . '<br>';
						} ?>
						<?php if ($invoice->client_address_2) {
							echo htmlsc($invoice->client_address_2) . '<br>';
						} ?>
						<?php if ($invoice->client_city) {
							echo htmlsc($invoice->client_city) . ' ';
						} ?>
						<?php if ($invoice->client_state) {
							echo htmlsc($invoice->client_state) . ' ';
						} ?>
						<?php if ($invoice->client_zip) {
							echo htmlsc($invoice->client_zip) . '<br>';
						} ?>
						<?php if ($invoice->client_phone) {
							echo trans('phone_abbr') . ': ' . htmlsc($invoice->client_phone); ?>
							<br>
						<?php } ?>
					

				</div>
				<div class="col-lg-2"></div>
				<div class="col-xs-12 col-md-6 col-lg-5 text-right">
					<h3>Prosjektleder/Utleier</h3>
					<hr>
					<h4><?php _htmlsc($invoice->user_name); ?></h4>
					<p><?php if ($invoice->user_vat_id) {
							echo lang("vat_id_short") . ": " . $invoice->user_vat_id . '<br>';
						} ?>
						<?php if ($invoice->user_tax_code) {
							echo lang("tax_code_short") . ": " . $invoice->user_tax_code . '<br>';
						} ?>
						<?php if ($invoice->user_address_1) {
							echo htmlsc($invoice->user_address_1) . '<br>';
						} ?>
						<?php if ($invoice->user_address_2) {
							echo htmlsc($invoice->user_address_2) . '<br>';
						} ?>
						<?php if ($invoice->user_city) {
							echo htmlsc($invoice->user_city) . ' ';
						} ?>
						<?php if ($invoice->user_state) {
							echo htmlsc($invoice->user_state) . ' ';
						} ?>
						<?php if ($invoice->user_zip) {
							echo htmlsc($invoice->user_zip) . '<br>';
						} ?>
						<?php if ($invoice->user_phone) { ?><?php echo trans('phone_abbr'); ?>: <?php echo htmlsc($invoice->user_phone); ?>
							<br><?php } ?>
						<?php if ($invoice->user_fax) { ?><?php echo trans('fax_abbr'); ?>: <?php echo htmlsc($invoice->user_fax); ?><?php } ?>
					</p>
					</p>

					<br>

					<table class="table table-condensed">
						<tbody>
						<tr>
						<?php if ($invoice->quote_number) { ?>
							<td><?php _trans('invoice'); ?> for: </td>
							<td><b><?php echo trans('quote') . ' ' . $invoice->quote_number; ?></b></td>
						<?php } ?>
						</tr>

                        <tr>
                            <td><?php echo trans('invoice_date'); ?></td>
                            <td style="text-align:right;"><?php echo date_from_mysql($invoice->invoice_date_created); ?></td>
                        </tr>
                        <tr class="<?php echo($is_overdue ? 'overdue' : '') ?>">
                            <td><?php echo trans('due_date'); ?></td>
                            <td class="text-right">
                                <?php echo date_from_mysql($invoice->invoice_date_due); ?>
                            </td>
                        </tr>
                        <tr class="<?php echo($is_overdue ? 'overdue' : '') ?>">
                            <td><?php echo trans('amount_due'); ?></td>
                            <td style="text-align:right;"><?php echo format_currency($invoice->invoice_balance); ?></td>
                        </tr>
                        <?php if ($payment_method): ?>
                            <tr>
                                <td><?php echo trans('payment_method') . ': '; ?></td>
                                <td><?php _htmlsc($payment_method->payment_method_name); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
					</table>
					<?php if($is_overdue) { ?>
						<p class="<?php echo($is_overdue ? 'overdue' : '') ?>"><b style="font-size: x-large;">Fakturaen er forfalt!</b></p>
					<?php } ?>
				</div>
			</div>

			<br>

			<div class="invoice-items">
				<div class="table-responsive">
					<table class="table table-striped table-bordered">
						<thead>
						<tr>
							<th class="item-desc" style="width:80%"><?php _trans('description'); ?></th>
							<th class="item-price text-right" style="width:20%"><?php _trans('price'); ?></th>
						</tr>
						</thead>
						<tbody>
							<?php
							$key = 0;
							foreach ($families as $value) { ?>
								<tr>
									<td><?php echo _trans('Sum') . " " . $value; ?></td>
									<td class="text-right"><?php echo format_currency($sums[$key]); ?></td>
								</tr>

							<?php $key++; } ?>
						</tbody>
						<tbody>
						<tr>
							<td style="border-top: 2px solid #000;" class="text-right"><?php echo trans('subtotal'); ?>:</td>
							<td style="border-top: 2px solid #000;" class="amount"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
						</tr>

						<?php if ($invoice->invoice_item_tax_total > 0) { ?>
							<tr>
								<td class="text-right"><?php echo trans('item_tax'); ?></td>
								<td class="amount"><?php echo format_currency($invoice->invoice_item_tax_total); ?></td>
							</tr>
						<?php } ?>

						<?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
							<tr>
								<td class="text-right no-bottom-border">
									<?php echo $invoice_tax_rate->invoice_tax_rate_name . ' ' . format_amount($invoice_tax_rate->invoice_tax_rate_percent); ?>
									%
								</td>
								<td class="amount"><?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?></td>
							</tr>
						<?php endforeach ?>

						<tr>
							<td class="text-right no-bottom-border"><?php echo trans('discount'); ?>:</td>
							<td class="amount">
								<?php
								if ($invoice->invoice_discount_percent > 0) {
									echo format_amount($invoice->invoice_discount_percent) . ' %';
								} else {
									echo format_amount($invoice->invoice_discount_amount);
								}
								?>
							</td>
						</tr>

						<tr>
							<td class="text-right no-bottom-border"><b><?php echo trans('total'); ?></b></td>
							<td class="amount"><b style="border-bottom: 4px double;"><?php echo format_currency($invoice->invoice_total) ?></b></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div><!-- .invoice-items -->
			<div class="sign" style="font-size: large; text-align: right;">
				<br>
				<i><b>Dersom betaling gjøres her, via Stripe, gjelder ikke teksten under.</b></i>
				<br><br>
				Viktig at betalingen markeres med: <b style="color:red;"><?php echo $invoice->invoice_number; ?></b><br>
				
				Betales til kontonr: <b><?php echo $custom_fields['user']['Kontonr']?></b><br>
				Sum å betale: <b><?php echo format_currency($invoice->invoice_total); ?></b><br><br>
				<b>Dersom betaling ikke merkes med fakturanr (rød tekst),<br> vil ikke betalingen bli registrert.</b><br>
			</div>
		</div><!-- .invoice -->
	</div><!-- #content -->
</div>

</body>
</html>
