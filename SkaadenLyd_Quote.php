<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">

<head>
	<meta charset="utf-8">
	<title><?php echo $quote->user_company ?> - <?php _trans('quote'); echo " " . $quote->quote_number; ?> - <?php echo $custom_fields['quote']['Oppdrag']?></title>
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

	$datediff = date_from_mysql($custom_fields['quote']['Til Dato']) - date_from_mysql($custom_fields['quote']['Fra Dato']);
	$days_total = $datediff + 1;

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
			<h2><b><?php echo trans('quote') . ' ' . $quote->quote_number; ?></b></h2>
			<table class="info">
				<tr>
					<td class="info"><b>Oppdrag:</b></td>
					<td><b><?php echo $custom_fields['quote']['Oppdrag']?></b></td>
				</tr>
				<tr>
					<td class="info"><b><?php echo trans('quote_date') . ':'; ?></b></td>
					<td><b><?php echo date_from_mysql($quote->quote_date_created, true); ?></b></td>
				</tr>
				<tr>
					<td class="info"><b><?php echo trans('expires') . ': '; ?></b></td>
					<td><b><?php echo date_from_mysql($quote->quote_date_expires, true); ?></b></td>
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
					<b><?php echo $quote->user_company ?></b> <?php if ($quote->user_vat_id) { echo trans('vat_id_short') . ': ' . $quote->user_vat_id;}?> 
				</td>
				<td style="width:15%;text-align:right">
					<?php echo trans('page') ?>  {PAGENO} av {nb}
				</td>
			</tr>
		</table>		
	</htmlpagefooter>

	<div id="page">
		<!--<header>
		</header-->
		<main>
			<div id=clientInfo>
				<div id="client">
					<div id="lineUnderL">
						<b><?php _trans('client'); ?></b>
						<hr>
					</div>
					<div><?php _htmlsc($quote->client_name); ?></div>
					<?php if ($quote->client_vat_id) {
						echo '<div>' . trans('vat_id_short') . ': ' . $quote->client_vat_id . '</div>';
					}
					if ($quote->client_address_1) {
						echo '<div>' . htmlsc($quote->client_address_1) . '</div>';
					}
					if ($quote->client_address_2) {
						echo '<div>' . htmlsc($quote->client_address_2) . '</div>';
					}
					if ($quote->client_city || $quote->client_state || $quote->client_zip) {
						echo '<div>';
						if ($quote->client_zip) {
							echo htmlsc($quote->client_zip) . ' ';
						}
						if ($quote->client_city) {
							echo htmlsc($quote->client_city) . ' ';
						}
						if ($quote->client_state) {
							echo htmlsc($quote->client_state) . ' ';
						}
						echo '</div>';
					}
					if ($quote->client_country) {
						echo '<div>' . get_country_name(trans('cldr'), $quote->client_country) . '</div>';
					}

					if ($quote->client_mobile) {
						echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->client_mobile) . '</div>';
					}
					if ($quote->client_phone) {
						echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->client_phone) . '</div>';
					} ?>
				</div>
				<div id="company">
					<div id="lineUnderR">
						<b>Prosjektleder/Utleier</b>
						<hr>
					</div>
					<div id="utleier">

						<div><?php _htmlsc($quote->user_name); ?></div>
						<?php 
						if ($quote->user_address_1) {
							echo '<div>' . htmlsc($quote->user_address_1) . '</div>';
						}
						if ($quote->user_address_2) {
							echo '<div>' . htmlsc($quote->user_address_2) . '</div>';
						}
						if ($quote->user_city || $quote->user_state || $quote->user_zip) {
							echo '<div>';
							if ($quote->user_zip) {
								echo htmlsc($quote->user_zip) . ' ';
							}
							if ($quote->user_city) {
								echo htmlsc($quote->user_city) . ' ';
							}
							if ($quote->user_state) {
								echo htmlsc($quote->user_state) . ' ';
							}
							echo '</div>';
						}
						if ($quote->user_country) {
							echo '<div>' . get_country_name(trans('cldr'), $quote->user_country) . '</div>';
						}

						if ($quote->user_mobile) {
							echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->user_mobile) . '</div>';
						}
						if ($quote->user_phone) {
							echo '<div>' . trans('phone_abbr') . ': ' . htmlsc($quote->user_phone) . '</div>';
						}
						?>
					</div>
				</div>
			</div>
			<div id="project">
				<hr id="fat">
				<h1><?php echo $custom_fields['quote']['Oppdrag']?></h1>
				<table>
				<!--<tr>
					<td>Prosjektleder:</td>
					<td><?php _htmlsc($quote->user_name); ?><?php
					if ($quote->user_mobile) {
						echo ', på telefon ' . htmlsc($quote->user_mobile);
					}
					elseif ($quote->user_phone) {
						echo ', på telefon ' . htmlsc($quote->user_phone);
					}	 
					?></td>
				</tr>-->
				<tr>
					<td>Fra dato</td>
					<td><?php echo date_from_mysql($custom_fields['quote']['Fra Dato']);?></td>
				</tr>
				<tr>
					<td>Til dato</td>
					<td><?php echo date_from_mysql($custom_fields['quote']['Til Dato']);?></td>
				</tr>
				</table>
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
							<td style="border-top: 2px solid #000;" class="text-right"><?php echo format_currency($quote->quote_item_subtotal); ?></td>
						</tr>

						<?php if ($quote->quote_item_tax_total > 0) { ?>
							<tr>
								<td class="text-right">
									<?php _trans('item_tax'); ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($quote->quote_item_tax_total); ?>
								</td>
							</tr>
						<?php } ?>

						<?php foreach ($quote_tax_rates as $quote_tax_rate) : ?>
							<tr>
								<td class="text-right">
									<?php echo $quote_tax_rate->quote_tax_rate_name . ' (' . format_amount($quote_tax_rate->quote_tax_rate_percent) . '%)'; ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($quote_tax_rate->quote_tax_rate_amount); ?>
								</td>
							</tr>
						<?php endforeach ?>

						<?php if ($quote->quote_discount_percent != '0.00') : ?>
							<tr>
								<td class="text-right">
									<?php _trans('discount'); ?>
								</td>
								<td class="text-right">
									<?php echo format_amount($quote->quote_discount_percent); ?>%
								</td>
							</tr>
						<?php endif; ?>
						<?php if ($quote->quote_discount_amount != '0.00') : ?>
							<tr>
								<td class="text-right">
									<?php _trans('discount'); ?>
								</td>
								<td class="text-right">
									<?php echo format_currency($quote->quote_discount_amount); ?>
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td class="text-right">
								<b><?php _trans('total'); ?></b>
							</td>
							<td class="text-right">
								<b style="border-bottom: 4px double;"><?php echo format_currency($quote->quote_total); ?></b>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="sign">
				<p>Utstyr og eventuell bemanning reserveres ved aksept av tilbudet. Innhold i tilbudet er spesifisert over de påfølgende side. Vi forbeholder oss muligheten til å endre spesifiert utstyr, i så fall til bedre eller tilsvarende kvaliet.</p>
				<p>Med vennlig hilsen <br> <?php _htmlsc($quote->user_name); ?></p>
				<br>
				<table class="signature">
					<tr>
						<td id="signL">
							<div id="lineUnderL">
								<b></b>
								<hr style="color: #000">
								<b>Dato, sted</b>
							</div>
						</td>
						<td id="signC"></td>
						<td id="signR">
							<div id="lineUnderR">
								<b></b>
								<hr style="color: #000">
								<b>Leietakers underskrift</b> <br>
							</div>
						</td>
					</tr>
				</table>
				<br>
				<small style="color:#484848"><i>Jeg aksepterer tilbudet som beskrevet i dette dokumentet. Jeg har også lest og forstått utleiebetingelsene beskrevet på siste side i dette dokumentet. Retur av denne siden i underskrevet form, eller bekreftelse over e-mail, ansees som gyldig aksept.</i></small>
			</div>
		</main>
		<!--footer>
			
		</footer-->
	</div>

	<div id="page">
	
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
	</div>

	<!-- Utleiebetingelser - Quote conditions -->
	<div>
		<h1>Utleiebetingelser <?php echo $quote->quote_number; ?></h1>
		<p>Betingelsene gjelder for utleie av utstyr og/eller arbeid ihht. Spesifikasjoner i tilbud <b><?php echo $quote->quote_number; ?></b> i avtalt leieperiode. Ved å akseptere og signere dette tilbudet erkjenner leietaker seg kjent med følgende punkter:</p>
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
</body>
</html>
