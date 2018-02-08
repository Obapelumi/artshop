<template>
    <div class="section-no-margin">
      <hr>
          <footer class="footer footer-style-3">
            <!-- <div class="slide-logo">
              <div class="container">
                <div data-number="5" data-margin="10" data-loop="yes" data-navcontrol="yes" class="sofani-owl-carousel">
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-1.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-2.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-3.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-4.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-5.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-3.png" alt="logo1"/></a></div>
                  <div class="slide-logo-item"><a href="#"><img src="/images/demo/slide-logo-5.png" alt="logo1"/></a></div>
                </div>
              </div>
            </div> -->
            <div class="container">
              <div class="footer-top">
                <div class="row">
                  <div class="col-md-4">
                    <div class="header-logo"><router-link to="/" title="ARTSHOP"><img src="/images/logo/favicon.png" alt="Artshop" class="logo-img" style="margin-left: 30%"/></router-link></div>
                    <div class="footer-top-content">
                      <p>
                        ArtShopNG seeks to be the world’s leading online marketplace for Nigeria Art, Crafts and Textile Prints, connecting people with art, crafts and textile prints they love. 
                      </p>
                          <router-link to="/about">DETAILS</router-link>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h3>INFORMATION</h3>
                    <ul class="footer-top-content">
                      <li><router-link to="/about">About Us</router-link></li>
                      <li><router-link to="/blog">Blog</router-link></li>
                      <li><router-link to="/shop">Store</router-link></li>
                      <li><router-link to="/faqs">Policy &amp; FAQs</router-link></li>
                    </ul>
                  </div>
                  <div class="col-md-4">
                    <h3>CONTACT US</h3>
                    <div class="icon-box icon-box-style2">
                      <div class="icon-box-left"><i class="fa fa-map-marker"></i></div>
                      <div class="icon-box-right">
                        <span>10, Emokaro street. Off Governor road. Ikotun. Lagos</span>
                      </div>
                    </div>
                    <div class="icon-box icon-box-style2">
                      <div class="icon-box-left"><i class="fa fa-phone"></i></div>
                      <div class="icon-box-right">
                        <span>Phone : <a href="tel:+234 (0) 80 5110 6313">+234 (0) 80 5110 6313</a> <br>
                        <a href="tel:+234 (0) 81 8983 7848">+234 (0) 81 8983 7848</a></span>
                      </div>
                    </div>
                    <div class="icon-box icon-box-style2">
                      <div class="icon-box-left"><i class="fa fa-envelope-o"></i></div>
                      <div class="icon-box-right"><span>Email : <a href="mailto:contactus@artshop.com.ng">contactus@artshop.com.ng</a></span></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="footer-bottom">
                <div class="click-back-top-footer">
                  <button type="button" class="sn-btn sn-btn-style-17 sn-back-to-top fixed-right-bottom"><i class="btn-icon fa fa-angle-up"></i></button>
                </div>
                <div class="footer-bottom-content">
                  <div class="copyright">©  {{theme.config.TODAY() | moment("YYYY")}}  ArtShopNG  Developed by  <a href="http://thrive.ng" target="_blank">THRIVE-TECH</a></div>
                </div>
              </div>
            </div>
          </footer>
    </div>
</template>

<script>
	export default{
		methods: {

		},
	}
</script>

<style>

</style>