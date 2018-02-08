<template>
	<section>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">REGISTER</h1>
            <div class="woocommerce-checkout-info" v-if="authCheck">
              <h3>Welcome, {{user.name}}<br><br> Please fill in your account details</h3>
            </div>
          </div>
          <form class="cart-form" @submit.prevent="register">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>BIO</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>Brand Name <abbr title="You can fill in the name of your brand. We will use your name if you choose to leave this blank" class="required"><i class="fa fa-question"></i></abbr></label>
                        <input type="text" v-model="vendorObj.brand_name">
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Business Phone Number <abbr title="This is the phone line we will contact concerning your account and products" class="required"><i class="fa fa-question"></i></abbr></label>
                        <input type="tel" v-model="vendorObj.phone" required>
                      </p>
                    </div>
                  </div>
                  <p id="order_comments_field" class="notes woocommerce-validated">
                    <label>Bio <abbr title="Tell us about your work" class="required"><i class="fa fa-question"></i></abbr></label>
                    <textarea id="order_comments" name="order_comments" placeholder="Tell us about your work" rows="6" cols="5" v-model="vendorObj.bio" required></textarea>
                  </p>
                  <p>
                    <label>Product Category <abbr title="What kind of art do you make?" class="required"><i class="fa fa-question"></i></abbr></label>
                    <select id="billing_country" class="country_to_state country_select capitalize" v-model="vendorObj.category_id" required>
                      <option v-for="category in categories" :value="category.id" :key="category.id">{{category.name}}</option>
                    </select>
                  </p>
                </div>
              </div>
              <div class="col-2 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>ACCOUNT &amp; PAYMENT DETAILS</h3>
                    <p>
                      <label>Profile Image <abbr title="upload your profile picture" class="required"><i class="fa fa-question"></i></abbr></label>
                      <art-image-upload :meta="meta" :upload="uploadImage"></art-image-upload>
                    </p>
                    <p>
                      <label>Work Address <abbr title="Where are you based?" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text" v-model="vendorObj.address" required>
                    </p>
                     <div class="row">
                    <div class="col-md-6">
                    <p>
                      <label>State <abbr title="required" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text" v-model="vendorObj.state" required>
                    </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>ZIP CODE <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="vendorObj.zip_code" required>
                      </p>
                    </div>
                    <div class="col-md-6">
                    <p>
                      <label>Account Number <abbr title="The bank account we will be paying you through" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text" v-model="vendorObj.acc_no" required>
                    </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>BANK CODE<abbr title="Fill in the sort code for the bank you use" class="required"><i class="fa fa-question"></i></abbr></label>
                        <input type="text" v-model="vendorObj.bank_code" required>
                      </p>
                    </div>
                    <!-- <div class="col-md-6">
                      <p>
                        <label>Country <abbr title="required" class="required">*</abbr></label>
                      <select id="billing_country" class="country_to_state country_select" v-model="vendorObj.country_code">
                        <option v-for="country in countries" :value="country.name" :key="country.name">{{country.name}}</option>
                      </select>
                      </p>
                    </div> -->
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div id="payment">
                <p class="checkbox inline"><input type="checkbox" id="createaccount" required> Please read &amp; agree to our <router-link to="/merchant-agreement">merchant agreement</router-link></p>
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit">REGISTER</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>
</template>

<script>
	export default{
		data(){
			return{
				returningEmail: '',
        returningPassword: '',
        categories: [],
        checkout: true,
        authCheck: false,
        uploadImage: false,
        meta: {
          image: {
            vendor_id: 0,
            slug: '',
          },
        },
        vendorObj: {
          category_id: null,
          brand_name: '',
          phone: '',
          address: '',
          state: '',
          zip_code: '',
          acc_no: '',
          bank_code: '',
          country_code: '',
        },
			}
		},
		methods: {
			showLogin() {
			    $('.login').slideToggle("slow");
			},
      login() {
        this.auth.login(this, '/pay', this.returningEmail, this.returningPassword);
      },
      register () {
        this.vendorObj.address = this.vendorObj.address + '. ' + this.vendorObj.state
        this.vendor.register(this, '/dashboard');
      }
		},
    beforeCreate () {
      console.log('creating')
      this.shop.getCategories(this);
    },
    created () {
      if (this.auth.checkAuth() === true) {
        this.authCheck = true;
        this.user = this.auth.getUser(this);
        this.vendorObj.brand_name = this.user.name;
        this.theme.getCountries(this); 
      }
    },
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	
</style>