<?php
require_once(__DIR__ . '/../inc/config.php');
require_once(__DIR__ . '/_admin_inc.php'); // checks session and user id
require_once($lang_folder . '/admin_translations/trans-index.php');
?>
<?php require_once(__DIR__ . '/_admin_header.php'); ?>
			<div class="sb2-2">
				<!--== breadcrumbs ==-->
				<div class="sb2-2-2">
					<ul>
						<li><a href="<?= $baseurl; ?>/admin"><i class="fa fa-home" aria-hidden="true"></i> Home</a> </li>
						<li class="active-bre"><a href="#"> Notifications</a> </li>
						<li class="page-back"><a href="<?= $baseurl; ?>/admin"><i class="fa fa-backward" aria-hidden="true"></i> Back</a> </li>
					</ul>
				</div>
				<div class="tz-2 tz-2-admin">
					<div class="tz-2-com tz-2-main">
						<h4>All Notifications</h4>
						<!-- Dropdown Structure -->
						<div class="split-row">
							<div class="col-md-12">
								<div class="box-inn-sp ad-inn-page">
									<div class="tab-inn ad-tab-inn">
										<div class="table-responsive">
											<table class="table table-hover">
												<thead>
													<tr>
														<th>Title</th>
														<th>Descriptions</th>
														<th>Date</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><a href="#"><span class="list-enq-name">Post free ads - today only</span><span class="list-enq-city">11:00 am</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>14 Dec 2017</span></td>
													</tr>
													<tr>
														<td><a href="#"><span class="list-enq-name">listing limit increase</span><span class="list-enq-city">02:00 pm</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>21 Jun 2018</span></td>
													</tr>
													<tr>
														<td><a href="#"><span class="list-enq-name">mobile app launch</span><span class="list-enq-city">06:30 pm</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>08 Jun 2017</span></td>
													</tr>
													<tr>
														<td><a href="#"><span class="list-enq-name">Post free ads - today only</span><span class="list-enq-city">11:00 am</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>14 Dec 2017</span></td>
													</tr>
													<tr>
														<td><a href="#"><span class="list-enq-name">listing limit increase</span><span class="list-enq-city">02:00 pm</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>21 Jun 2018</span></td>
													</tr>
													<tr>
														<td><a href="#"><span class="list-enq-name">mobile app launch</span><span class="list-enq-city">06:30 pm</span></a> </td>
														<td>Duis nulla ligula, interdum porta nulla sed, efficitur tempus lacus. Quisque facilisis.</td>
														<td><span>08 Jun 2017</span></td>
													</tr>

												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="admin-pag-na">
									<ul class="pagination list-pagenat">
										<li class="disabled"><a href="#!!"><i class="material-icons">chevron_left</i></a> </li>
										<li class="active"><a href="#!">1</a> </li>
										<li class="waves-effect"><a href="#!">2</a> </li>
										<li class="waves-effect"><a href="#!">3</a> </li>
										<li class="waves-effect"><a href="#!">4</a> </li>
										<li class="waves-effect"><a href="#!">5</a> </li>
										<li class="waves-effect"><a href="#!">6</a> </li>
										<li class="waves-effect"><a href="#!">7</a> </li>
										<li class="waves-effect"><a href="#!">8</a> </li>
										<li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a> </li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php require_once(__DIR__ . '/_admin_footer.php'); ?>
