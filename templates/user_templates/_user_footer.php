<div id="footer">
	<div id="footer-inner">
		<div class="footer-inner-left">
			<?= $site_name; ?>
		</div>
		<div class="footer-inner-right">
			<ul>
				<?= show_menu('footer_menu', false); ?>
				<li><a href="<?= $baseurl; ?>/_contact">Contact</a>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
</div><!-- #footer -->

<!-- modal city change -->
<div class="modal fade" id="change-city-modal" role="dialog" aria-labelledby="change-city-modal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?= $txt_modalclose; ?></span></button>
				<h3 class="modal-title" id="myModalLabel"><?= $txt_selectyourcity; ?></h3>
			</div>
			<div class="modal-body">
				<form id="city-change-form" method="post">
					<div class="block"><select id="city-change" name="city-change"></select></div>
				</form>
			</div><!-- .modal-body -->
			<div class="modal-footer">
				<button type="button" class="btn btn-blue" data-dismiss="modal"><?= $txt_modalclose; ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal" id="clear-city"><?= $txt_clearcity; ?></button>
			</div>
		</div><!-- .modal-content -->
	</div><!-- .modal-dialog -->
</div><!-- end modal -->

<!-- css -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<!-- external javascript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="<?= $baseurl; ?>/templates/lib/raty/jquery.raty.js"></script>
<script src="<?= $baseurl; ?>/lib/jquery-autocomplete/jquery.autocomplete.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/<?= $html_lang; ?>.js"></script>

<?php
$js_inc = __DIR__ . '/../js/user_js/js-user-footer.php';
if(file_exists($js_inc)) {
	include_once($js_inc);
}