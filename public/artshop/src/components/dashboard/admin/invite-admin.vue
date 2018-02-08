<template>
	<form class="checkout" id="create-product" @submit.prevent="register">
            <div id="customer_details" class="col2-set row">
              <div class="col-1 col-md-6">
                <div class="woocommerce-billing-fields">
                  <h3>Invite Admin</h3>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>First Name <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="firstName">
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Last Name <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="lastName"/>
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <p>
                        <label>E-mail <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="email" >
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Role <abbr title="required" class="required">*</abbr></label>
                        <select v-model="isAdmin">
                        	<option value="true">Administrator</option>
                        	<option value="false">Blogger</option>
                        </select>
                      </p>
                    </div>
                    <div class="col-md-6">
                      <p>
                        <label>Phone Number <abbr title="required" class="required">*</abbr></label>
                        <input type="text" v-model="phone" >
                      </p>
                    </div>
                  </div>
                  
                    <div class="clear"></div>
                  </div>
                  <div class="button-cart-right">
                    <button class="btn btn-transparent" style="margin-left: 50%; margin-top: 30px;" type="submit" @click.prevent="register"> Submit</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
</template>

<script>
export default {
	data () {
		return {
			firstName: '',
			lastName: '',
			email: '',
			phone: '',
			isAdmin: false,
		}
	},
	methods: {
		register () {
			if (this.isAdmin) {
				this.admin.registerAdmin(this);
			}
			else {
				this.admin.registerBlogger(this);
			}
		},
	},
	computed: {
		password () {
			return Math.floor(100000 + Math.random() * 900000)
		},
	}
}
</script>

<style>
	
</style>