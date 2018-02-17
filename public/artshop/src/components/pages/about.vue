<template>
        <div class="about-us has-header">
            <art-page-header :page="'About'"></art-page-header>
            <div class="container row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                <div class="about-us-content">
                  <h2>ABOUT US</h2>
                  <div class="wpb_text_column wpb_content_element" style="line-height: 30px">
                    <div class="wpb_wrapper">
                    <p>
                        ArtShopNG seeks to be the world’s leading online marketplace for Nigeria Art, Crafts and Textile Prints, connecting people with art, crafts and textile prints they love. 
                    </p>
                    <!-- <br> -->
                    <p>    
                        ArtShopNG offers an unparalleled selection of paintings, drawings, sculpture, crafts and textile prints in a range of prices, and it provides artists and craftsmen with an expertly curated environment in which to exhibit and sell their work.
                    </p>
                    <!-- <br> -->
                    <p> 
                        Based in Lagos, Nigeria. ArtShopNG is redefining the experience of buying and selling art by making it easy, convenient and welcoming for both the buyers and sellers
                     </p>
                    </div>
                  </div>
                  <div class="wpb_text_column wpb_content_element" style="line-height: 30px">
                    <div class="wpb_wrapper">
                      <p>ArtShopNG promotes the beauty of the Nigerian culture by creating an online community where crafters, artists and makers could sell their handmade, vintage goods and craft supplies not only providing the advantage of being open for business 24/7, but also the opportunity to broaden their reach to consumers across Nigeria, Africa and the rest of the world</p>
                    </div>
                  </div>
                  <h2><router-link to="/merchant-agreement">READ OUR MERCHANT AGREEMENT</router-link></h2>
                  <br>
                  <div class="about-bottom">
                        <div class="contact-us contact-bottom">
                          <h2>GET IN TOUCH</h2>
                          <div class="row">
                            <div class="contact-bottom-left col-md-8">
                              <form ACTION="mailto:contactus@artshop.com.ng" METHOD="POST" enctype="text/plain" class="wpcf7-form">
                                <p></p>
                                <h6>NAME *</h6>
                                <p>
                                  <span class="wpcf7-form-control-wrap your-name">
                                    <input name="name" required value="" size="40" placeholder="Your Name" type="text" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"/>
                                  </span>
                                </p>
                                <p></p>
                                <h6>EMAIL *</h6>
                                <p>
                                  <span class="wpcf7-form-control-wrap your-email">
                                    <input name="email" required value="" placeholder="Email Address" type="email" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"/>
                                  </span>
                                </p>
                                <p></p>
                                <h6>MESSAGE</h6>
                                <p><span class="wpcf7-form-control-wrap your-message">
                                    <textarea name="message" required cols="40" rows="4" aria-invalid="false" class="wpcf7-form-control wpcf7-textarea"></textarea></span></p>
                                <p>
                                  <input value="SEND MESSAGE" type="submit" class="wpcf7-form-control wpcf7-submit"/>
                                </p>
                                <div class="wpcf7-response-output wpcf7-display-none"></div>
                              </form>
                            </div>
                            <div class="contact-bottom-right col-md-4">
                              <div class="contact-bottom-right-content">
                                <p>For inquiry on our products and services at Artshop.com.ng, you can reach us 24/7.</p>
                                <p>Fill your name, mail and message. We will get back to you as soon as we can.</p>
                                <p>Thank You for reaching out!</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
</template>

<script>
export default {
  
}
</script>

<style>

</style>


