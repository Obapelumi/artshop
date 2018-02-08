<template>
          <form class="col-md-10 offset-md-1 col-lg-8 offset-lg-0" @submit.prevent="updateAccount">
            <div id="customer_details" class="col2-set row">
              <div class="">
                <div class="woocommerce-billing-fields">
                  <h3>ACCOUNT SETTINGS</h3>
                    <div class="col-md-6">
	                  <p>
	                    <label>Name *</label>
	                    <input type="text" v-model="userEdited.name" required>
	                  </p>
                    </div>
                    <div class="col-md-6">
	                  <p>
	                    <label>E-mail *</label>
	                    <input type="email" v-model="userEdited.email" required readonly>
	                  </p>
                    </div>
                    <div class="col-md-6" v-if="auth.checkCustomer()">
                      <p>
                        <label>Phone *</label>
                        <input type="tel" v-model="userEdited.customer.phone" required>
                      </p>
                    </div>
                    <div class="col-md-6" v-if="auth.checkCustomer()">
                      <p>
                        <label>Address *</label>
                        <input type="text" v-model="userEdited.customer.address" required>
                      </p>
                    </div>
                    <div class="col-md-6">
	                  <p>
	                    <label>New Password *</label>
	                    <input type="password" v-model="password">
	                  </p>
                    </div>
                    <div class="col-md-6">
	                  <p>
	                    <label>Confirm New Password *</label>
	                    <input type="password" v-model="c_password">
	                  </p>
                    </div>
                </div>
              </div>
              <div class="clearfix"></div>
              
              <div class="clearfix"></div>
              <div id="payment">
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit" >CONFIRM</button>
                </div>
              </div>
            </div>
          </form>
</template>

<script>
	export default{
		props: ['categories', 'products', 'user'],
		data(){
			return{
        userEdited: this.user,
        password: null,
        c_password: null,
			}
		},
		methods: {
			updateAccount () {
				this.auth.updateAccount(this, this.updateData, '/dashboard');
			},
		},
		computed: {
      updateData () {
        var data = {
          name: this.userEdited.name,
          password: this.password,
          password_confirmationn: this.c_password,
        }
        if (this.auth.checkCustomer()) {
          data.phone = this.userEdited.customer.phone;
          data.address = this.userEdited.customer.address;
        }
        return data;
      }
    },
    created () {

    }
	}
</script>

<style>
	
</style>