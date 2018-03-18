<template>
	<section>
        <div class="container">
          <title>Payment | Artshop </title>
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">MAKE PAYMENT</h1>
            <div class="woocommerce-checkout-info" v-if="authCheck"><h3>Hi, {{user.name}}! Please Confirm and Make Payment</h3></div>
          </div>
          <form class="cart-form" :class="{ disable: paying }" @submit.prevent="pay">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>CONTACT INFORMATION</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>Email <abbr title="required" class="required">*</abbr></label>
                        <input type="email" v-model="user.email" readonly required>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Phone <abbr title="required" class="required">*</abbr></label>
                        <input type="tel" v-model="customer.phone" required>
                      </p>
                    </div>
                  </div>
                  <p>
                    <label>Address <abbr title="required" class="required">*</abbr></label>
                    <input type="text" v-model="customer.address" required>
                  </p>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>ZIP CODE <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="customer.zip_code">
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-2 col-md-6">
                <div class="woocommerce-shipping-fields">
                  <h3>Additional Information</h3>
                  <p id="order_comments_field" class="notes woocommerce-validated">
                    <label>Order Notes</label>
                    <textarea id="order_comments" name="order_comments" placeholder="Notes about your order, e.g. special notes for delivery." rows="6" cols="5" v-model="order_notes"></textarea>
                  </p>
                </div>
              </div>
            </div>
            <h2 id="order_review_heading">Your order</h2>
            <div class="woocommerce-checkout-review-order">
            <transition name="fade">
              <art-cart-table 
                :cart="verifiedCart.cart" 
                :checkCart="checkCart" 
                v-if="verifiedCart"
              ></art-cart-table>
            </transition>
            <transition name="slideRight">
              <art-cart-total 
                :cart="verifiedCart.cart" 
                :shipping_cost="verifiedCart.shipping_cost"
                v-if="verifiedCart"
              ></art-cart-total>
              <art-loading v-else></art-loading>
            </transition>
              <div id="payment" v-if="verifiedCart">
                <p class="checkbox inline">
                  <input type="checkbox" id="createaccount" required="Please accept our terms and conditions"> Please agree to our 
                  <router-link to="/faqs">terms and conditions</router-link>
                </p>
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit">MAKE PAYMENT</button>
                </div>
              </div>
            </div>
          </form>
          <div class="container" id="paystack">
            <div id="paystackEmbedContainer"></div>
          </div>
        </div>
      </section>
</template>

<script>
	export default{
    props: ['cart', 'checkCart'],
		data(){
			return{
				returningEmail: '',
        returningPassword: '',
        order_notes: 'omore bi custard',
        shoppingCart: false,
        authCheck: false,
        checkout: true,
        customer: null,
        user: null,
        verifiedCart: null,
        paying: false,
			}
		},
		methods: {
      pay () {
       this.paying = true;
       this.theme.smoke('info', 'Just a moment Please!', 10000);
       this.shop.pay(this);
      },
		},
    beforeCreate () {
      if (this.auth.checkAuth() === true) {
        this.verifiedCart = this.shop.submitCart(this);
      }
      else {
        this.cart = this.shop.getCart();
      }
    },
    created () {
      if (this.auth.checkAuth() === true) {
        this.theme.getConfig(this);	
        this.authCheck = true;
        this.user = this.auth.getUser(this); 
        this.customer = this.user.customer
        this.order_notes = localStorage.getItem('order_notes');
      }
    },
    beforeDestroy () {
      this.paying = false;
    }
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	
  .disable {
    pointer-events: none;
    opacity: 0.2;
  }
  @media (min-width: 650px) {
      #paystack {
        max-width: 60%;
      }
  }
</style>