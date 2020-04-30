<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">

<head>
	<meta charset="utf-8">
	<title><?php echo $invoice->user_company ?> - <?php _trans('invoice'); echo " " . $invoice->invoice_number; ?> - <?php echo $custom_fields['invoice']['Oppdrag']?></title>
	<link rel="stylesheet"
		  href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/templates.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom-pdf.css">
</head>
<body>

	<!-- Code for crating array of family groups and the length of the lease defined in the quote-->
	<?php 
	$families = array();

	foreach ($items as $item) {
		if (in_array($item->item_description, $families) == false) {
			$families[] = $item->item_description;
		} 
	}

	if ($invoice->quote_number) {
		$datediff = date_from_mysql($custom_fields['quote']['Til Dato']) - date_from_mysql($custom_fields['quote']['Fra Dato']);
		$days_total = $datediff + 1;
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


	<htmlpageheader name="header">
		<div id="toptextL">
			<div id="logo"><?php echo invoice_logo_pdf(); ?></div>
		</div>
		<div id="toptextR">
			<h2><b><?php echo trans('invoice') . ' ' . $invoice->invoice_number; ?></b></h2>
			<table class="info">
				<tr>
					<td class="info"><b>Oppdrag:</b></td>
					<td><b><?php echo $custom_fields['invoice']['Oppdrag']?></b></td>
				</tr>
				<tr>
					<td class="info"><b><?php echo trans('invoice_date') . ':'; ?></b></td>
					<td><b><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></b></td>
				</tr>
				<tr>
					<td class="info"><b><?php echo trans('due_date') . ': '; ?></b></td>
					<td><b><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></b></td>
				</tr>
			</table>
		</div>
	</htmlpageheader>

	<htmlpagefooter name="footer">
		<table style="width:100%">
			<tr>
				<td style="width:15%">
					<?php echo $custom_fields['user']['Bedriftspost']?>
				</td>
				<td style="width:70%;text-align:center">
					<b><?php echo $invoice->user_company ?></b> <?php if ($invoice->user_vat_id) { echo trans('vat_id_short') . ': ' . $invoice->user_vat_id;}?> 
				</td>
				<td style="width:15%;text-align:right">
					<?php echo trans('page') ?>  {PAGENO} av {nb}
				</td>
			</tr>
		</table>
	</htmlpagefooter>

	<div <?php if($custom_fields['invoice']['Detaljert faktura']) : echo 'id="page"'; endif; ?>>
		<!--<header>
		</header-->
		<main>
			<div id=clientInfo>
				<div id="client">
					<div id="lineUnderL">
						<b><?php _trans('client'); ?></b>
						<hr>
					</div>
					<div><?php _htmlsc($invoice->client_name); ?></div>
					<?php if ($invoice->client_vat_id) {
						echo '<div>' . trans('vat_id_short') . ': ' . $invoice->client_vat_id . '</div>';
					}
					if ($invoice->client_address_1) {
						echo '<div>' . htmlsc($invoice->client_address_1) . '</div>';
					}
					if ($invoice->client_address_2) {
						echo '<div>' . htmlsc($invoice->client_address_2) . '</div>';
					}
					if ($invoice->client_city || $invoice->client_state || $invoice->client_zip) {
						echo '<div>';
						if ($invoice->client_zip) {
							echo htmlsc($invoice->client_zip) . ' ';
						}
						if ($invoice->client_city) {
							echo htmlsc($invoice->client_city) . ' ';
						}
						if ($invoice->client_state) {
							echo htmlsc($invoice->client_state) . ' ';
						}
						echo '</div>';
					}
					if ($invoice->client_country) {
						echo '<div>' . get_country_name(trans('cldr'), $invoice->client_country) . '</div>';
					}

					if ($invoice->client_mobile) {
						echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->client_mobile) . '</div>';
					}
					if ($invoice->client_phone) {
						echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->client_phone) . '</div>';
					} ?>
				</div>
				<div id="company">
					<div id="lineUnderR">
						<b>Prosjektleder/Utleier</b>
						<hr>
					</div>
					<div id="utleier">

						<div><?php _htmlsc($invoice->user_name); ?></div>
						<?php 
						if ($invoice->user_address_1) {
							echo '<div>' . htmlsc($invoice->user_address_1) . '</div>';
						}
						if ($invoice->user_address_2) {
							echo '<div>' . htmlsc($invoice->user_address_2) . '</div>';
						}
						if ($invoice->user_city || $invoice->user_state || $invoice->user_zip) {
							echo '<div>';
							if ($invoice->user_zip) {
								echo htmlsc($invoice->user_zip) . ' ';
							}
							if ($invoice->user_city) {
								echo htmlsc($invoice->user_city) . ' ';
							}
							if ($invoice->user_state) {
								echo htmlsc($invoice->user_state) . ' ';
							}
							echo '</div>';
						}
						if ($invoice->user_country) {
							echo '<div>' . get_country_name(trans('cldr'), $invoice->user_country) . '</div>';
						}

						if ($invoice->user_mobile) {
							echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->user_mobile) . '</div>';
						}
						if ($invoice->user_phone) {
							echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($invoice->user_phone) . '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div id="project">
				<hr id="fat">
				<h1><?php echo $custom_fields['invoice']['Oppdrag']?></h1>
				<?php if ($invoice->quote_number) { ?>
					<?php _trans('invoice'); ?> for <?php echo strtolower(trans('quote')) . ' ' . $invoice->quote_number; ?><br>
				<?php } ?>
				<br>
				<b><?php echo trans('due_date') . ': '; ?></b><b><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></b>
				<br>
				Betales til kontonr: <b><?php echo $custom_fields['user']['Kontonr']?></b><br>
				Viktig at betalingen markeres med: <b style="color:red;"><?php echo $invoice->invoice_number; ?></b> (fakturanr.)
				<?php if($custom_fields['invoice']['Detaljert faktura']) : echo "<br><br>På neste side finnes en detaljert liste for fakturaen."; endif; ?>
				<hr>
				<div id="price">
					<table class="item-table">
						<thead>
						<tr>
							<th class="item-desc" style="width:80%"><?php _trans('description'); ?></th>
							<th class="item-price text-right" style="width:20%"><?php _trans('price'); ?></th>
						</tr>
						</thead>
						<tbody style="border-bottom: 1px solid #333">

						<?php
						$key = 0;
						foreach ($families as $value) { ?>
							<tr>
								<td><?php echo _trans('Sum') . " " . $value; ?></td>
								<td class="text-right"><?php echo format_currency($sums[$key]); ?></td>
							</tr>

						<?php $key++; } ?>

						</tbody>
						<tbody class="invoice-sums">

						<tr>
							<td class="text-right" style="border-top: 2px solid #000;"><?php _trans('subtotal'); ?></td>
							<td style="border-top: 2px solid #000;" class="text-right"><?php echo format_currency($invoice->invoice_item_subtotal); ?></td>
						</tr>

						<?php if ($invoice->invoice_item_tax_total > 0) { ?>
							<tr>
								<td class="text-right">
									<?php _trans('item_tax'); ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($invoice->invoice_item_tax_total); ?>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
							<tr>
								<td class="text-right">
									<?php echo $invoice_tax_rate->invoice_tax_rate_name . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($invoice_tax_rate->invoice_tax_rate_amount); ?>
								</td>
							</tr>
						<?php endforeach ?>

						<?php if ($invoice->invoice_discount_percent != '0.00') : ?>
							<tr>
								<td class="text-right">
									<?php _trans('discount'); ?>
								</td>
								<td class="text-right">
									<?php echo format_amount($invoice->invoice_discount_percent); ?>%
								</td>
							</tr>
						<?php endif; ?>
						<?php if ($invoice->invoice_discount_amount != '0.00') : ?>
							<tr>
								<td class="text-right">
									<?php _trans('discount'); ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($invoice->invoice_discount_amount); ?>
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td class="text-right">
								<b><?php _trans('total')?> å betale</b>
							</td>
							<td class="text-right">
								<b style="border-bottom: 4px double;"><?php echo format_currency($invoice->invoice_total); ?></b>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sign" style="font-size: large; text-align: right;">
				Viktig at betalingen markeres med: <b style="color:red;"><?php echo $invoice->invoice_number; ?></b><br>
				
				Betales til kontonr: <b><?php echo $custom_fields['user']['Kontonr']?></b><br>
				Sum å betale: <b><?php echo format_currency($invoice->invoice_total); ?></b><br><br>
				<b>Dersom betaling ikke merkes med fakturanr (rød tekst),<br> vil ikke betalingen bli registrert.</b><br>
			</div>
		</main>
	</div>
	<?php if($custom_fields['invoice']['Detaljert faktura']) : ?>
		<h1>Detaljert liste for faktura <?php echo $invoice->invoice_number; ?></h1>
		<?php 
		$key = 0;
		foreach ($families as $value) { ?>
			<h2><?php echo htmlsc($value); ?></h2>
			<table class="item-table">
				<thead>
					<tr>
						<th class="item-amount-gp text-right" style="width:10%;"><?php _trans('qty'); ?></th>
						<th class="item-name-gp" style="width:<?php if ($d_sums[$key] != 0){ echo '35%';} else { echo '50%';} ?>">
							<?php _trans('description'); ?>
						</th>
						<th class="item-price-gp text-right" style="width:15%;"><?php _trans('price'); ?> per</th>
						<?php if ($d_sums[$key] != 0) : ?>
							<th class="item-discount text-right" style="width:15%;"><?php _trans('discount'); ?></th>
						<?php endif; ?>
						<th class="item-total-gp text-right" style="width:20%;"><?php _trans('total'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($items as $item) { ?>
				<?php if ($item->item_description == $value) : ?>
					<tr>
						<td class="text-right">
							<?php echo format_amount($item->item_quantity); ?>
							<?php if ($item->item_product_unit) : ?>
								<br>
								<small><?php _htmlsc($item->item_product_unit); ?></small>
							<?php endif; ?>
						</td>
						<td><?php _htmlsc($item->item_name); ?></td>
						<td class="text-right">
							<?php echo format_currency($item->item_price); ?>
						</td>
						<?php if ($d_sums[$key] != 0) : ?>
							<td class="text-right">
								<?php echo format_currency($item->item_discount); ?>
							</td>
						<?php endif; ?>
						<td class="text-right">
							<?php echo format_currency($item->item_total); ?>
						</td>
					</tr>
				<?php endif; ?>
				<?php } ?>
				</tbody>
				<tbody style="border-top: 1px solid #333" class="gpsums">
					<?php if ($d_sums[$key] != 0): ?>
					<tr>
						<td colspan="4" style="border-top: 2px solid #444;" class="text-right">
							<?php _trans('discount'); ?>
						</td>
						<td style="border-top: 2px solid #444;" class="text-right">
							<?php echo format_currency($d_sums[$key]); ?>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td <?php if ($d_sums[$key] != 0) { echo 'colspan="4"';} else { echo 'colspan="3" style="border-top: 2px solid #444;"';} ?> class="text-right">
							<b><?php echo _trans('Sum') . " " . $value; ?></b>
						</td>
						<td <?php if ($d_sums[$key] != 0) { } else { echo 'style="border-top: 2px solid #444;"';} ?> class="text-right">
							<b style="border-bottom: 4px double;"><?php echo format_currency($sums[$key]); ?></b>
						</td>
					</tr>
				</tbody>
			</table>
			<?php $key++; ?>
		<?php } ?>
	<?php endif; ?>
</body>
</html>
