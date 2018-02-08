<template>
	<section>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">SHIPPING DETAILS</h1>
            <div class="woocommerce-checkout-info" v-if="authCheck"><h3>Welcome, {{user.name}}<br><br> Please confirm your Shipping Details</h3></div>
          </div>
          <form class="cart-form" @submit.prevent="register">
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
                        <label>STATE <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="customer.state" required>
                      </p>
                    </div>
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
              <div class="clearfix"></div>
              <div id="payment">
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit">NEXT</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>
</template>

<script>
	export default {
		data(){
			return{
        order_notes: '',
        checkout: true,
        authCheck: false,
        user: {email: ''},
        customer: {
          phone: '',
          address: '',
          zip_code: '',
          state: '',
        },
			}
		},
		methods: {
      register () {
        var $this = this;
        localStorage.setItem('order_notes', this.order_notes);
        this.customer.address = this.customer.address + '. ' + this.customer.state
        $this.auth.registerCustomer($this, '/pay');
      }
		},
    created () {
      if (this.auth.checkAuth() === true) {
        this.authCheck = true;
        this.user = this.auth.getUser(this); 
      }
    },
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	

  .paystack-hide {
    /*display: none;*/
  }
</style>