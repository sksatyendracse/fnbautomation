<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="<?= $html_lang ?>"> <![endif]-->
<html lang="<?= $html_lang ?>">
<head>
<title><?= $txt_html_title ?> - <?= $site_name ?></title>
<link rel="canonical" href="<?= $canonical ?>">
<meta name="description" content="<?= $txt_meta_desc ?>">

<!-- Open Graph data -->
<meta property="og:title" content="<?= $txt_html_title ?> - <?= $site_name ?>">
<meta property="og:url" content="<?= $canonical ?>">
<meta property="og:type" content="place">
<meta property="og:description" content="<?= $txt_meta_desc ?>">
<?php
if(!empty($photos[0]['img_url'])) {
	?><meta property="og:image" content="<?= $photos[0]['img_url'] ?>"><?php
}
?>

<!-- includes -->
<?php
require_once '_html_head.php';
?>

<style>
<?php
// styles for the background blur effect which shows only if this place has at least one photo
if(!empty($blur_photo)) {
	?>
	#blur-photo {
		background-image: url('<?= $blur_photo ?>');
		-webkit-background-size: 100%;
		-moz-background-size: 100%;
		-o-background-size: 100%;
		background-size: 100%;
		left:-35px;
		right:-35px;
		top:-35px;
		bottom:-35px;
		filter: blur(15px) brightness(0.75);
		-webkit-filter: blur(15px) brightness(0.7);
		-moz-filter: blur(15px) brightness(0.7);
		-ms-filter: blur(15px) brightness(0.7);
		-o-filter: blur(15px) brightness(0.7);
		position: absolute;
		background-position: center;
	}
	<?php
}
else {
	?>
	h1.place-title {
		color: #404040;
	}

	.place-category {
		color: #404040;
	}
	#content.single-page .breadcrumbs {
		color: #404040;
	}

	#content.single-page .breadcrumbs a {
		color: #404040;
	}
<?php
}
?>
</style>
</head>

<body class="tpl-place">
<?php require_once('_header.php'); ?>

<div id="blur-wrapper">
	<div id="blur-photo"></div>
</div>

<div class="wrapper">
	<?php
	if(!empty($blur_photo)) {
		?><div class="breadcrumbs"><?php
	}
	else {
		?><div class="breadcrumbs-empty-blur"><?php
	}
	?>
	<?php
	// breadcrumbs: home ?>
	<a href="<?= $baseurl ?>/"><?= $txt_breadcrumb_home ?></a> >

	<?php
	// breadcrumbs: state
	if(!empty($state_name)) {
		?><a href="<?= $baseurl ?>/<?= $state_slug ?>/list/all-categories/s-<?= $state_id ?>-0-1"><?= $state_name ?></a> >
		<?php
	}

	// breadcrumbs: city
	if(!empty($city_slug)) {
		?><a href="<?= $baseurl ?>/<?= $city_slug ?>/list/all-categories/c-<?= $city_id ?>-0-1"><?= $city_name ?></a> >
		<?php
	}

	// breadcrumbs: null city
	if(empty($city_slug) && empty($state_slug)) {
		$city_slug = 'location';
	}

	// breadcrumbs: categories
	if(!empty($cats_path)) {
		foreach($cats_path as $v) { // $cats_path is an array of category ids sorted hierarchically
			$stmt = $conn->prepare('SELECT plural_name, id FROM cats WHERE id = :cat_id');
			$stmt->bindValue(':cat_id', $v);
			$stmt->execute();
			$row           = $stmt->fetch(PDO::FETCH_ASSOC);
			$this_cat_id   = $row['id'];
			$this_cat_name = $row['plural_name'];
			$this_cat_slug = to_slug($this_cat_name);
			?>
			<a href="<?= $baseurl ?>/<?= $city_slug ?>/list/<?= $this_cat_slug ?>/c-<?= $city_id ?>-<?= $this_cat_id ?>-1"><?= $this_cat_name ?></a> >
			<?php
		}
	}
	?>

	<?= $place_name ?>
	</div><!-- .breadcrumbs or .breadcrumbs-empty-blur -->

	<h1 class="place-title"><?= $place_name ?></h1>

	<div class="item-rating" data-rating="<?= $rating ?>">
		<!-- raty plugin placeholder -->
	</div>

		<?php
		if(!empty($blur_photo)) {
			?>
			<div class="all-cats-line"><?php
		}
		else {
			?>
			<div class="all-cats-line-empty"><?php
		}
		?>
		<a href="<?= $baseurl ?>/<?= $city_slug ?>/list/<?= $cat_slug ?>/c-<?= $city_id ?>-<?= $cat_id ?>-1"><?= $cat_name ?></a>
	</div><!-- .all-cats-line / .all-cats-line-empty -->

	<div class="full-block">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active">
				<a href="#overview" aria-controls="home" role="tab" data-toggle="tab"><?= $txt_overview ?></a>
			</li>

			<?php
			if(!empty($photos)) {
				?>
				<li role="presentation">
					<a href="#photos" aria-controls="photos" role="tab" data-toggle="tab"><?= $txt_photos ?> (<?= count($photos) ?>)</a>
				</li>
			<?php
			}
			?>

			<?php
			if(!empty($coupons_arr)) {
				?>
				<li role="presentation">
					<a href="#coupons" aria-controls="coupons" role="tab" data-toggle="tab"><?= $txt_coupons ?> (<?= count($coupons_arr) ?>)</a>
				</li>
			<?php
			}
			?>

			<li role="presentation">
				<a href="#reviews" aria-controls="settings" role="tab" data-toggle="tab"><?= $txt_reviews ?> (<?= count($reviews) ?>)</a>
			</li>

			<?php
			if(!empty($email)) {
				?>
				<li role="presentation">
					<a href="#contact" aria-controls="contact" role="tab" data-toggle="tab"><?= $txt_contact ?></a>
				</li>
			<?php
			}
			?>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content padding-top">

			<!-- Tab #overview -->
			<div role="tabpanel" class="tab-pane active" id="overview">
				<!-- description -->
				<?php
				if(!empty($description)) {
					?>
					<div class="block">

						<?php
						if(!empty($description)) {
							?>
							<h2><?= $txt_description ?></h2>

							<div class="description"><?= $description ?></div>
						<?php
						}
						?>

					</div>
				<?php
				}
				?>

				<!-- Map -->
				<?php
				if (!empty($lat)) {
					?>
					<div id="place-map-wrapper">
						<div id="place-map-canvas" style="width:100%; height:100%"></div>
					</div>
					<?php
				}
				?>

				<!-- address -->
				<?php
				if(!empty($address)) {
					?>
					<div class="row block">
						 <div class="col-sm-3"><?= $txt_address ?></div>
						 <div class="col-sm-9">
							<address>
								<div>
									<?= $address ?>
									<?= $cross_street ?>
								</div>

								<div>
									<?= $neighborhood_name ?><?php if(!empty($neighborhood_name)) echo "<br>"; ?>
									<?= $city_name ?><?php if(!empty($city_name)) echo ", "; ?>
									<?= $state_abbr ?>
									<?= $postal_code ?>
								</div>
							</address>
						</div>
					</div>
				<?php
				}
				?>

				<!-- phone -->
				<?php
				if(!empty($phone)) {
					?>
					<div class="row block">
						<div class="col-sm-3"><?= $txt_phone ?></div>
						<div class="col-sm-9">
							<div><?= $area_code ?> <?= $phone ?></div>
						</div>
					</div>
				<?php
				}
				?>

				<!-- website -->
				<?php
				if(!empty($website)) {
					?>
					<div class="row block">
						<div class="col-sm-3"><?= $txt_website ?></div>
						<div class="col-sm-9"><?= $website ?></div>
					</div>
				<?php
				}
				?>

				<!-- claim -->
				<?php
				if(1 || $place_userid == 1 && $userid != 1) {
					?>
				<div class="row block">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<a href="<?= $baseurl ?>/plugins/claim_listings/claim/<?= $place_id ?>" class="btn btn-default btn-even-less-padding" style="font-weight: 700;"><em><?= $txt_claim ?></em></a>
					</div>
				</div>
				<?php
				}
				?>

				<!-- social media -->
				<?php
				if(!(empty($foursq_place_id) && empty($twitter) && empty($facebook))) {
					?>
					<div class="row block">
						<div class="col-sm-3"><?= $txt_social ?></div>
						<div class="col-sm-9">
							<?php
							if(!empty($foursq_place_id)) {
								?>
								<div class="social-button"><a href="https://foursquare.com/v/<?= $foursq_place_id ?>" target="_blank"><i class="fa fa-foursquare"></i></a></div>
								<?php
							}
							if(!empty($twitter)) {
								?>
								<div class="social-button"><a href="http://twitter.com/<?= $twitter ?>" target="_blank"><i class="fa fa-twitter"></i></a></div>
								<?php
							}
							if(!empty($facebook)) {
								?>
								<div class="social-button"><a href="http://facebook.com/<?= $facebook ?>" target="_blank"><i class="fa fa-facebook"></i></a></div>
								<?php
							}
							?>
						</div>
					</div>
				<?php
				}
				?>

				<!-- hours -->
				<?php
				if(!empty($tpl_hours)) {
					?>
					<div class="row block">
						<div class="col-sm-3"><?= $txt_hours ?></div>
						<div class="col-sm-6">
							<?php
							foreach($tpl_hours as $k => $v) {
								?>
								<div class="timeframes">
									<div class="timeframes-row">
										<span class="timeframes-days"><?= $v['days'] ?></span>
										<span class="timeframes-hours">
											<?php
											$counter = 1;
											foreach($v['open'] as $x) {
												echo ($counter > 1) ? '<br>' : '';
												echo $x;
												$counter++;
											}
											?>
										</span>
										<div class="clear"></div>
									</div>
								</div>
								<?php
							}
							?>
						</div>
						<div class="col-sm-3"></div>
					</div>
				<?php
				}
				?>

				<!-- custom fields -->
				<?php
				foreach($custom_fields_grouped as $k => $v) {
					?>
					<div class="row block">
						<div class="col-sm-3 custom-field custom-field-<?= $v[0]['field_id'] ?>">
							<strong><?= $v[0]['icon'] ?> <?= $k ?>:</strong>
						</div>

						<div class="col-sm-9">
							<?php
							$j = 1;
							foreach($v as $k2 => $v2) {
								if(!empty($v2['field_value'])) {
									if($v2['field_type'] == 'url' && filter_var($v2['field_value'], FILTER_VALIDATE_URL)) {
										?>
										<a href="<?= $v2['field_value'] ?>">
										<?php
									}

									if($j > 1) {
										echo ', ';
									}

									echo $v2['field_value'];
									$j++;

									if($v2['field_type'] == 'url' && filter_var($v2['field_value'], FILTER_VALIDATE_URL)) {
										echo "</a>";
									}
								}
							}
							?>
						</div>
					</div>
					<?php
				}
				?>

				<!-- neighborhood -->
				<?php
				if(!empty($website)) {
					?>
					<div class="row block">
						<div class="col-sm-3"><?= $txt_related ?></div>
						<div class="col-sm-9">
							<a href="<?= $cat_neighborhood_link ?>"><?= $cat_plural ?> <?= $txt_in ?> <?= $neighborhood_name ?></a><br>
							<a href="<?= $neighborhood_link ?>"><?= $txt_other_places ?> <?= $txt_in ?> <?= $neighborhood_name ?></a>
						</div>
					</div>
				<?php
				}
				?>
			</div>

			<!-- Tab #photos -->
			<?php
			if(!empty($photos)) {
				?>
				<div role="tabpanel" class="tab-pane" id="photos">
					<?php
					// image gallery
					if(!empty($photos)) {
						/*
						dynamic proportional height from width
						see: http://ansciath.tumblr.com/post/7347495869/css-aspect-ratio
						#container {
							display: inline-block;
							position: relative;
							width: 50%;
						}
						#dummy {
							padding-top: 75%; -> 4:3 aspect ratio
						}
						#element { // the element that you want to have height proportionate to width
							position: absolute;
							top: 0;
							bottom: 0;
							left: 0;
							right: 0;
							background-color: silver
						}

						<li id="container">
							<figure id="dummy">
								<img id="element"/> <!-- notice how it goes inside the dumy, YES!! IT WORKS!!!!!! :D -->
							<figure>
						<li>
						*/
						?>
						<div class="place-thumbs-wrapper">
							<?php
							$pic_count = 1;
							foreach($photos as $k => $v) {
								?>
								<div class="place-thumb">
									<a href="<?= $v['img_url'] ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?= $v['data_title'] ?>">
										<div class="dummy container-img" style="background-image:url('<?= $v['img_url_thumb'] ?>');"></div>
									</a>
								</div>
								<?php
								$pic_count++;
							}
							?>
							<div class="clear"></div>
						</div><!-- .place-thumbs-wrapper -->
						<?php
					} // if(!empty($photos))
					?>
				</div>
				<?php
			}
			?>

			<!-- Tab #coupons -->
			<?php
			if(!empty($coupons_arr)) {
				?>
				<div role="tabpanel" class="tab-pane" id="coupons">
					<?php
					if(!empty($coupons_arr)) {
						foreach($coupons_arr as $k => $v) {
							?>
							<div class="item" id="coupon-<?= $v['coupon_id'] ?>">
								<div class="item-pic coupon-img" id="<?= $v['coupon_id'] ?>">
									<a href="<?= $baseurl ?>/coupons/<?= $v['coupon_id'] ?>"><img src="<?= $v['coupon_img'] ?>" /></a>
								</div><!-- .user-item-pic -->

								<div class="item-description coupon-body">
									<div class="item-title-row block">
										<h2><a href="<?= $baseurl ?>/coupons/<?= $v['coupon_id'] ?>"><?= e($v['coupon_title']) ?></a></h2>
									</div>

									<div class="block"><?= e($v['coupon_description']) ?></div>

									<div class="block">
										<a href="<?= $baseurl ?>/coupons/<?= $v['coupon_id'] ?>" class="btn btn-green"><strong><?= $txt_view_details ?></strong></a>
									</div>

								</div><!-- .item-description -->
								<div class="clear"></div>
							</div><!-- .item -->
							<?php
						} // end foreach
					} // end if($total_rows > 0)
				?>
				</div>
				<?php
			}
			?>

			<!-- Tab #reviews -->
			<div role="tabpanel" class="tab-pane" id="reviews">

				<?php
				if(!empty($reviews)) {
					?>
					<h2><?= $txt_reviews ?></h2>

					<div class="list-reviews">
						<?php
						foreach($reviews as $k => $v) {
							?>
							<div class="review-item">
								<div class="profile-pic">
									<a href="<?= $v['profile_link'] ?>">
										<img src="<?= $v['profile_pic_url'] ?>" style="border-radius: 50%;width:100%">
									</a>
								</div><!-- .profile-pic -->

								<div class="review-text">
									<div class="review-author-name">
										<a href="<?= $v['profile_link'] ?>">
											<?= $v['user_display_name'] ?>
										</a>
									</div>

									<?php
									if($v['rating'] != 0) {
										?>
										<div class="review-rating" data-rating="<?= $v['rating'] ?>">
											<!-- .review-rating placeholder -->
										</div>
										<?php
									}
									?>

									<span class="review-pubdate"><?php echo date("F j, Y", $v['pubdate']) ?></span>

									<p><?= nl2p(ucfirst($v['text'])) ?></p>
								</div>

								<div class="clear"></div>
							</div><!-- .review-item -->
						<?php
						} // end foreach reviews
						?>
					</div><!-- .list-reviews -->
					<?php
				} // end if(!empty($reviews))
				// end reviews
				?>

				<h2><?= $txt_write_review ?></h2>

				<?php
				if(!empty($_SESSION['user_connected']) && !empty($_SESSION['userid'])) {
					?>
					<div id="review-form-wrapper">
						<form method="post" id="review-form">
							<input type="hidden" name="place_id" id="place_id" value="<?= $place_id ?>">

							<div class="form-row">
								<div><label for="email"><?= $txt_please_rate ?></label></div>
								<div class="raty"></div>
								<div id="hint" class="label label-blue label-even-less-padding"></div>
							</div>

							<div class="form-row">
								<div><label for="review"><?= $txt_review_txtarea_label ?></label></div>
								<div><textarea name="review" id="review" class=""></textarea></div>
							</div>

							<div class="form-row submit-row">
								<input type="button" id="submit-review" name="submit" value="Publish" class="btn btn-blue btn-less-padding">
							</div>
						</form>
						<div class="clear"></div>
					</div><!-- #comment-form-wrapper -->
					<?php
				}
				else {
					?>
					<p><?= $txt_review_login_req ?></p>
					<?php
				}
				?>
			</div>

			<!-- Tab #contact -->
			<?php
			if(!empty($email)) {
				?>
				<div role="tabpanel" class="tab-pane" id="contact">
					<div id="contact-owner-result"></div>
					<form id="contact-owner-form" method="post">
						<input type="hidden" name="place_id" id="place_id" value="<?= $place_id ?>">

						<div class="form-row">
							<label for=""><?= $txt_contact_owner_label_email ?></label>
							<input type="email" name="sender_email" id="sender_email" value="<?= $email ?>">
						</div>

						<div class="form-row">
							<label for=""><?= $txt_contact_owner_label_msg ?></label>
							<textarea name="sender_msg" id="sender_msg" style="height: 72px; min-height: 36px;"></textarea>
						</div>

						<div class="form-row">
							<label for=""><?= $plugin_values['question'] ?></label>
							<input type="text" name="verify_answer" id="verify_answer">
						</div>

						<input class="btn btn-blue btn-less-padding" type="submit" id="submit-contact-owner">
					</form>
				</div>
				<?php
			}
			?>
		</div>

	</div><!-- .full-block -->
</div><!-- .wrapper -->

<?php
require_once('_footer.php');
?>

<?php
$js_inc = __DIR__ . '/js/js-place.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}
?>
</body>
</html>