<!--ADD BUSINESS-->
<!-- <section class="com-padd home-dis">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2><span>30% Off</span> Promote Your Business with us <a href="price.php"> Create listing</a></h2> </div>
    </div>
  </div>
</section> -->
<!--FOOTER SECTION-->
<footer id="colophon" class="site-footer clearfix footer-white">
  <div id="quaternary" class="sidebar-container " role="complementary">
    <div class="sidebar-inner">
      <div class="widget-area clearfix">
        <div id="azh_widget-2" class="widget widget_azh_widget">
          <div data-section="section">
            <div class="container">
              <div class="row">
                <div class="col-sm-4 col-md-3 foot-logo">
                  <img src="<?php echo $baseurl;?>/images/logo/logo1.png" alt="logo" style="margin-top: -15px;">
                  <p class="hasimg">Myanmar No. 1 Local Business Directory</p>
                  <br>
                  <div class="foot-social">
                    <ul>
                      <li><a href="#!"><i class="fa fa-facebook" aria-hidden="true"></i></a> </li>
                      <li><a href="#!"><i class="fa fa-twitter" aria-hidden="true"></i></a> </li>
                      <li><a href="#!"><i class="fa fa-linkedin" aria-hidden="true"></i></a> </li>
                      <li><a href="#!"><i class="fa fa-youtube" aria-hidden="true"></i></a> </li>
                      <li><a href="#!"><i class="fa fa-whatsapp" aria-hidden="true"></i></a> </li>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-4 col-md-3">
                  <h4>Quick Links</h4>
                  <ul class="two-columns">
                    <li> <a href="about-us.php">About Us</a> </li>
                    <li> <a href="#">Services</a> </li>
                    <li> <a href="#">Quick Enquiry</a> </li>
                  </ul>
                </div>
                <div class="col-sm-4 col-md-3">
                  <h4>Popular Supplier Category</h4>
                  <ul class="two-columns">
                    <li> <a href="#!">Hotels</a> </li>
                    <li> <a href="#!">Hospitals</a> </li>
                    <li> <a href="#!">Transportation</a> </li>
                    <li> <a href="#!">Real Estates</a> </li>
                  </ul>
                </div>
                <div class="col-sm-4 col-md-3">
                  <h4>Latest Posts</h4>
                  <ul class="two-columns">
                    <li> <a href="#!">Alum Cladding : Open Joint Hook-on System 1... </a> </li>
                    <li> <a href="#!">Alum Cladding : Open Joint Hook-on System 2... </a> </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- .widget-area -->
    </div>
    <!-- .sidebar-inner -->
  </div>
  <!-- #quaternary -->
</footer>
<!--COPY RIGHTS-->
<section class="copy">
  <div class="container">
    <p>copyrights © <span id="cryear1">2020</span>  &nbsp;&nbsp;All rights reserved. </p>
  </div>
</section>
<section>
  <!-- GET QUOTES POPUP -->
  <div class="modal fade dir-pop-com" id="list-quo" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header dir-pop-head">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Get a Quotes</h4>
          <!--<i class="fa fa-pencil dir-pop-head-icon" aria-hidden="true"></i>-->
        </div>
        <div class="modal-body dir-pop-body">
          <form method="post" class="form-horizontal">
            <!--LISTING INFORMATION-->
            <div class="form-group has-feedback ak-field">
              <label class="col-md-4 control-label">Full Name *</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="fname" placeholder="" required=""> </div>
            </div>
            <!--LISTING INFORMATION-->
            <div class="form-group has-feedback ak-field">
              <label class="col-md-4 control-label">Mobile</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="mobile" placeholder=""> </div>
            </div>
            <!--LISTING INFORMATION-->
            <div class="form-group has-feedback ak-field">
              <label class="col-md-4 control-label">Email</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="email" placeholder=""> </div>
            </div>
            <!--LISTING INFORMATION-->
            <div class="form-group has-feedback ak-field">
              <label class="col-md-4 control-label">Message</label>
              <div class="col-md-8 get-quo">
                <textarea class="form-control"></textarea>
              </div>
            </div>
            <!--LISTING INFORMATION-->
            <div class="form-group has-feedback ak-field">
              <div class="col-md-6 col-md-offset-4">
                <input type="submit" value="SUBMIT" class="pop-btn"> </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- GET QUOTES Popup END -->
</section>
<!--SCRIPT FILES-->
<script>
var baseURL = '<?php echo $baseurl;?>';
</script>
<script src="<?php echo $baseurl;?>/js/jquery.min.js"></script>
<script src="<?php echo $baseurl;?>/js/ajaxData.js"></script>
<script src="<?php echo $baseurl;?>/js/bootstrap.js" type="text/javascript"></script>
<script src="<?php echo $baseurl;?>/js/materialize.min.js" type="text/javascript"></script>

<script>
$(document).ready(function() {
    $.getScript("<?php echo $baseurl;?>/js/custom.js");
});
</script>
</html>
