<!DOCTYPE html>
<html lang="<?php echo trans('cldr'); ?>">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>
		<?php echo get_setting('custom_title', 'InvoicePlane', true); ?>
		- <?php echo trans('quote'); ?> <?php echo $quote->quote_number; ?>
	</title>

	<meta name="viewport" content="width=device-width,initial-scale=1">

	<link rel="stylesheet"
		  href="<?php echo base_url(); ?>assets/<?php echo get_setting('system_theme', 'invoiceplane'); ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/core/css/custom.css">

</head>
<body>

	<!--pre><?php print_r($custom_fields); ?></pre-->

	<!-- Code for crating array of family groups and the length of the lease defined in the quote-->
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

			<h2><?php echo trans('quote') . ' ' . $quote->quote_number; ?></h2>

			<div class="btn-group">
				<?php if (in_array($quote->quote_status_id, array(2, 3))) : ?>
					<a href="<?php echo site_url('guest/view/approve_quote/' . $quote_url_key); ?>"
					   class="btn btn-success">
						<i class="fa fa-check"></i><?php echo trans('approve_this_quote'); ?>
					</a>
					<a href="<?php echo site_url('guest/view/reject_quote/' . $quote_url_key); ?>"
					   class="btn btn-danger">
						<i class="fa fa-times-circle"></i><?php echo trans('reject_this_quote'); ?>
					</a>
				<?php endif; ?>
				<a href="<?php echo site_url('guest/view/generate_quote_pdf/' . $quote_url_key); ?>"
				   class="btn btn-primary">
					<i class="fa fa-print"></i> <?php echo trans('download_pdf'); ?>
				</a>
			</div>

		</div>

		<hr>

		<?php if ($flash_message) { ?>
			<div class="alert alert-info">
				<?php echo $flash_message; ?>
			</div>
		<?php } else {
			echo '<br>';
		} ?>

		<div class="quote">
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
					<h4><?php _htmlsc($quote->client_name); ?></h4>
					<p><?php if ($quote->client_vat_id) {
							echo lang("vat_id_short") . ": " . $quote->client_vat_id . '<br>';
						} ?>
						<?php if ($quote->client_tax_code) {
							echo lang("tax_code_short") . ": " . $quote->client_tax_code . '<br>';
						} ?>
						<?php if ($quote->client_address_1) {
							echo htmlsc($quote->client_address_1) . '<br>';
						} ?>
						<?php if ($quote->client_address_2) {
							echo htmlsc($quote->client_address_2) . '<br>';
						} ?>
						<?php if ($quote->client_city) {
							echo htmlsc($quote->client_city) . ' ';
						} ?>
						<?php if ($quote->client_state) {
							echo htmlsc($quote->client_state) . ' ';
						} ?>
						<?php if ($quote->client_zip) {
							echo htmlsc($quote->client_zip) . '<br>';
						} ?>
						<?php if ($quote->client_phone) {
							echo trans('phone_abbr') . ': ' . htmlsc($quote->client_phone); ?>
							<br>
						<?php } ?>
					

				</div>
				<div class="col-lg-2"></div>
				<div class="col-xs-12 col-md-6 col-lg-5 text-right">
					<h3>Prosjektleder/Utleier</h3>
					<hr>
					<h4><?php _htmlsc($quote->user_name); ?></h4>
					<p><?php if ($quote->user_vat_id) {
							echo lang("vat_id_short") . ": " . $quote->user_vat_id . '<br>';
						} ?>
						<?php if ($quote->user_tax_code) {
							echo lang("tax_code_short") . ": " . $quote->user_tax_code . '<br>';
						} ?>
						<?php if ($quote->user_address_1) {
							echo htmlsc($quote->user_address_1) . '<br>';
						} ?>
						<?php if ($quote->user_address_2) {
							echo htmlsc($quote->user_address_2) . '<br>';
						} ?>
						<?php if ($quote->user_city) {
							echo htmlsc($quote->user_city) . ' ';
						} ?>
						<?php if ($quote->user_state) {
							echo htmlsc($quote->user_state) . ' ';
						} ?>
						<?php if ($quote->user_zip) {
							echo htmlsc($quote->user_zip) . '<br>';
						} ?>
						<?php if ($quote->user_phone) { ?><?php echo trans('phone_abbr'); ?>: <?php echo htmlsc($quote->user_phone); ?>
							<br><?php } ?>
						<?php if ($quote->user_fax) { ?><?php echo trans('fax_abbr'); ?>: <?php echo htmlsc($quote->user_fax); ?><?php } ?>
					</p>
					</p>

					<br>

					<table class="table table-condensed">
						<tbody>
						<tr>
							<td><?php echo trans('quote_date'); ?></td>
							<td style="text-align:right;"><?php echo date_from_mysql($quote->quote_date_created); ?></td>
						</tr>
						<tr class="<?php echo($is_expired ? 'overdue' : '') ?>">
							<td><?php echo trans('expires'); ?></td>
							<td class="text-right">
								<?php echo date_from_mysql($quote->quote_date_expires); ?>
							</td>
						</tr>
						<tr>
							<td><?php echo trans('total'); ?></td>
							<td class="text-right"><?php echo format_currency($quote->quote_total); ?></td>
						</tr>
						</tbody>
					</table>

				</div>
			</div>

			<br>

			<div class="quote-items">
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
							<td style="border-top: 2px solid #000;" class="amount"><?php echo format_currency($quote->quote_item_subtotal); ?></td>
						</tr>

						<?php if ($quote->quote_item_tax_total > 0) { ?>
							<tr>
								<td class="text-right"><?php echo trans('item_tax'); ?></td>
								<td class="amount"><?php echo format_currency($quote->quote_item_tax_total); ?></td>
							</tr>
						<?php } ?>

						<?php foreach ($quote_tax_rates as $quote_tax_rate) : ?>
							<tr>
								<td class="text-right no-bottom-border">
									<?php echo $quote_tax_rate->quote_tax_rate_name . ' ' . format_amount($quote_tax_rate->quote_tax_rate_percent); ?>
									%
								</td>
								<td class="amount"><?php echo format_currency($quote_tax_rate->quote_tax_rate_amount); ?></td>
							</tr>
						<?php endforeach ?>

						<tr>
							<td class="text-right no-bottom-border"><?php echo trans('discount'); ?>:</td>
							<td class="amount">
								<?php
								if ($quote->quote_discount_percent > 0) {
									echo format_amount($quote->quote_discount_percent) . ' %';
								} else {
									echo format_amount($quote->quote_discount_amount);
								}
								?>
							</td>
						</tr>

						<tr>
							<td class="text-right no-bottom-border"><b><?php echo trans('total'); ?></b></td>
							<td class="amount"><b style="border-bottom: 4px double;"><?php echo format_currency($quote->quote_total) ?></b></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<br><h3>Detaljert liste for <?php echo trans('quote') . ' ' . $quote->quote_number; ?></h3><br>
			<div class="quote-items">
				<div class="table-responsive">
				
				<?php 
				$key = 0;
				foreach ($families as $value) { ?>
					<h4><?php echo htmlsc($value); ?></h4>
					<table style="width:100%;" class="table table-striped table-bordered">
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
				</div>
				
				<div class="row">
					<br><br>
					<h3>Utleiebetingelser <?php echo $quote->quote_number; ?></h3><br>
					<p>Betingelsene gjelder for utleie av utstyr ihht. Spesifikasjoner i tilbud <b><?php echo $quote->quote_number; ?></b> i avtalt leieperiode. Ved å akseptere og signere dette tilbudet erkjenner leietaker seg kjent med følgende punkter:</p>
					<ol style="list-style-type: decimal">
						<li><b>Utleiers erstatningsansvar</b>
							<ol style="list-style-type: lower-alpha">
								<li>Utleier står ikke ansvarlig ved person- eller materiellskader ved bruk av utstyr.</li>
								<li>Utleier står ikke ansvarlig for økonomisk tap hos leietaker ved teknisk svikt eller andre forhold utenfor utleiers kontroll.</li>
								<li>Utleiers erstatningsansvar er ved alle tilfeller begrenset til frafallelse av avtalt leiesum.</li>
							</ol>
						</li>
						<li><b>Sikkerhet og risiko</b>
							<ol style="list-style-type: lower-alpha">
								<li>Utleier forplikter seg at utstyr blir utlevert i fungerende og forskriftsmessig stand.</li>
								<li>Leietaker plikter seg til å benytte kyndig personell ved bruk av utstyr, og eventuelt leie inn tekniker fra <?php echo $quote->user_company ?>. Ved kyndig personell menes at operatør har gjort seg kjent med og kan dokumentere kompetanse på utstyr som inngår i tilbudet.</li>
							</ol>
						</li>
						<li><b>Leietakers erstatningsansvar</b>
							<ol style="list-style-type: lower-alpha">
								<li>Leietaker har fult ansvar for alt utstyr fra det leveres til det er returnert til utleier.</li>
								<li>Leietaker er pliktet til å erstatte, reparere, betale en sum av utstyrets sum som nytt eller dekke kostnader tilknyttet reparasjon, samt en evt. økonomisk kompensasjon ved skade eller tap av utstyr, uansett årsak. Leietaker er ansvarlig for skader som følge av feil på strømtilførsel. Dette gjelder uavhengig av hvem som betjener utstyret.</li>
							</ol>
						</li>
						<li><b>Transport</b>
							<ol style="list-style-type: lower-alpha">
								<li>Dersom leietaker står for transport av utstyr, er leietaker ansvarlig for sikker og forsvarlig transport, samt sikring av utstyr ved transport og ved lasting og lossing av utstyr.</li>
								<li>Dersom utleier står for transport plikter leietaker seg til hensiktsmessige parkeringsfasiliteter i umiddelbar nærhet av lokalet for utleiers biler gjennom hele leieperiode dersom annet ikke avtalt skriftlig. Utleier viderefakturerer alle kostnader tilknyttet parkering, samt evt. bøter som følge av feil eller mangelfull informasjon om parkeringsfasilitetene.</li>
							</ol>
						</li>
						<li><b>Retur</b>
							<ol style="list-style-type: lower-alpha">
								<li>Leietaker forplikter seg til at alt utstyr blir returnert i utlevert stand, og informere utleier om eventuelle skader eller endringer som har oppstått gjennom leieperioden.</li>
								<li>Leietaker skal melde fra til <?php _htmlsc($quote->user_name); ?> på telefon <?php if ($quote->user_mobile) {
										echo htmlsc($quote->user_mobile);
									} elseif($quote->user_phone) {
										echo htmlsc($quote->user_phone);
									}?> dersom returtidspunktet blir flyttet. Utleier kan belaste leietaker for ordinær døgnleie mellom avtalt- og faktisk returtidspunkt. Om endringer ikke innmeldes, kan utleier i tillegg til ordinær døgnleie belaste leietaker en økonomisk kompensasjon for tap av utstyr.</li>
							</ol>
						</li>
						<li><b>Avbestilling</b>
							<ol style="list-style-type: lower-alpha">
								<li>Ved avbestilling senere enn 7 dager før leieperiodens start forbeholder utleier seg rettigheten til å fakturere leietaker for alle påløpte kostnader.</li>
								<li>Ved avbestilling mindre enn 48 timer før leieperiodens start vil leiebeløpet i sin helhet bli fakturert.</li>
							</ol>
						</li>
						<li><b>Mislighold</b>
							<ol style="list-style-type: lower-alpha">
								<li>Ved mislighold kan utleier til enhver tid kreve utstyr tilbakelevert eller hente utstyr for leietakers regning og risiko.</li>
								<li>Leietaker forplikter seg til å betale avtalt leiepris til utleier innen forfall.</li>
							</ol>
						</li>
					</ol>
				</div>

			</div><!-- .quote-items -->
		</div><!-- .quote -->
	</div><!-- #content -->
</div>

</body>
</html>
