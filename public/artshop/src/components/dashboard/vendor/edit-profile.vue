<template>
          <form class="col-md-10 offset-md-1 col-lg-8 offset-lg-0"  @submit.prevent="updateVendor">
            <div id="customer_details" class="col2-set row">
              <div class="">
                <div class="woocommerce-billing-fields">
                  <h3>PROFILE IMAGE</h3>
                    <p v-if="vendors.length > 0">
                      <art-image-update :file="editedVendor.file" :meta="meta"></art-image-update>
                    </p>
                  <h3>EDIT BIO</h3>
                    <div class="col-md-6">
                      <p>
                        <label>Business Phone <abbr title="This is the phone line we will contact concerning your account and products" class="required"><i class="fa fa-question"></i></abbr></label>
                        <input type="tel" v-model="editedVendor.phone" required>
                      </p>
                    </div>
                    <div class="col-md-6">
	                  <p>
	                    <label>Brand Name <abbr title="You can fill in the name of your brand. We will use your name if you choose to leave this blank" class="required"><i class="fa fa-question"></i></abbr></label>
	                    <input type="text" v-model="editedVendor.brand_name" required>
	                  </p>
                    </div>
                  <div class="col-md-8">
	                  <p id="order_comments_field" class="notes woocommerce-validated">
	                    <label>Bio <abbr title="Tell us about your work" class="required"><i class="fa fa-question"></i></abbr></label>
	                    <textarea id="order_comments" name="order_comments" placeholder="Tell us about your work" rows="6" cols="5" v-model="editedVendor.bio" required></textarea>
	                  </p>
              	  </div>
              	  <div class="col-md-6">
	                  <p>
	                    <label>Product Category <abbr title="What kind of art do you make?" class="required"><i class="fa fa-question"></i></abbr></label>
	                    <select id="billing_country" class="country_to_state country_select capitalize" v-model="editedVendor.category_id" required>
	                      <option v-for="category in categories" :value="category.id" :key="category.id">{{category.name}}</option>
	                    </select>
	                  </p>
              	  </div>
<!--                   <p class="create-account woocommerce-validated">
                    <input type="checkbox" id="createaccount">
                    <label class="checkbox">Create an account?</label>
                  </p> -->
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-10 offset-md-1 col-lg-4 offset-lg-0"></div>
              <div>
                <div class="woocommerce-billing-fields">
                  <h3>EDIT CONTACT DETAILS</h3>
                    <div class="clearfix"></div>
                    <p class="col-md-8">
                      <label>Address <abbr title="required" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text" v-model="editedVendor.address" required>
                    </p>
                    <!-- <div class="col-md-6">
                    <p>
                      <label>Town / City <abbr title="required" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text">
                    </p>
                    </div> -->
                    <div class="col-md-4">
                      <p>
                        <label>ZIP CODE <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="editedVendor.zip_code" required>
                      </p>
                    </div>
                    <div class="col-md-6">
                    <p>
                      <label>Account Number <abbr title="Contact Support" class="required"><i class="fa fa-question"></i></abbr></label>
                      <input type="text" v-model="editedVendor.acc_no" readonly required>
                    </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>BANK CODE<abbr title="Contact Support" class="required"><i class="fa fa-question"></i></abbr></label>
                        <input type="text" v-model="editedVendor.bank_code" readonly required>
                      </p>
                    </div>
                    <!-- <div class="col-md-6">
                      <p>
                        <label>Country <abbr title="required" class="required">*</abbr></label>
                      <select id="billing_country" class="country_to_state country_select">
                        <option v-for="country in countries" :value="country.name" :key="country.name">{{country.name}}</option>
                      </select>
                      </p>
                    </div> -->
                </div>
              </div>
              <div class="clearfix"></div>
              <div id="payment">
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit">UPDATE</button>
                </div>
              </div>
            </div>
          </form>
</template>

<script>
	export default{
		props: ['categories', 'products', 'user', 'vendors'],
		data(){
			return{
		        checkout: true,
		        authCheck: false,
		        meta: {
		          image: {
                vendor_id: null,
		            slug: null,
              }
		        },
			}
		},
		methods: {
			updateVendor () {
				this.vendor.updateVendor(this, '/dashboard');
      },
      setMeta () {
        this.meta.image.vendor_id = this.user.vendor.id;
        this.meta.image.slug = this.user.vendor.slug;
      }
		},
		computed: {
			editedVendor () {
				var id = {
					vendor_id: this.user.vendor.id,
        };
        var vendor = this.user.vendor;
        if (this.vendors.length > 0) {
          vendor = this.vendors.filter(vendor => vendor.id === id.vendor_id)[0];  
        }

				var data = Object.assign(vendor, id);
				return data;
			},
		},
    created () {
      if (this.auth.checkAuth() === true) {
        this.authCheck = true;
        this.setMeta();
      }
    },
	}
</script>

<style>
	
</style>