<template>
	<section>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">REGISTER</h1>
            <div class="woocommerce-checkout-info"><h3>Already have an account?</h3> 
              <div @click='showLogin'><h4>Click here to login</h4></div>
            </div>

            <form class="login" @submit.prevent="login">
              <p>
                <label>Email <span class="required">*</span></label>
                <input id="username" name="username" type="email" v-model="returningEmail" required>
              </p>
              <p>
                <label>Password   <span class="required">*</span></label>
                <input id="password" name="password" type="password" v-model="returningPassword" required>
              </p>
              <div class="clear"></div>
              <p>
                <button class="btn btn-transparent" type="submit">LOGIN</button><br><br>
                <!-- <label class="inline">
                  <input id="" name="rememberme" value="forever" type="checkbox"> Remember me
                </label> -->
              </p>
              <p class="lost_password"><router-link to="/password-reset">Lost your password?</router-link></p>
              <div class="clear"></div>
            </form>
          </div>
          <div class="container" style="width: 80%; text-align: center;">
            <form  class="woocommerce-billing-fields" @submit.prevent="register">
                <p class="col-md-6">
                    <label>NAME</label>
                    <input name="name" type="text" v-model="user.name" required>
                </p>
                <p class="col-md-6">
                    <label>EMAIL</label>
                    <input name="email" type="email" v-model="user.email" required>
                </p>
                <p class="col-md-6">
                    <label>PASSWORD</label>
                    <input name="password" type="password" v-model="user.password" required>
                </p>
                <p class="col-md-6">
                    <label>CONFIRM PASSWORD</label>
                    <input name="password" type="password" v-model="user.c_password" required>
                </p>
                <div class="clear"></div>
                <p>
                    <button class="btn btn-transparent" type="submit">REGISTER</button><br><br>
                </p>
                <div class="clear"></div>
            </form>
          </div>
          
        </div>
      </section>
</template>

<script>
	export default{
		data(){
			return{
				returningEmail: '',
            returningPassword: '',
            user: {
                name: '',
                email: '',
                password: '',
                c_password: '',
            },
			}
		},
		methods: {
			showLogin() {
			    $('.login').slideToggle("slow");
            },
            showRegister () {
                $('.register').slideToggle("slow");
            },
            login() {
                this.auth.login(this, this.theme.config.NEXT, this.returningEmail, this.returningPassword);
            },
            register () {
                var $this = this;
                if (this.user.password !== this.user.c_password) {
                  this.smoke('error', 'passwords do not match');
                }
                else {
                  $this.auth.signup($this, '/verify-mail');
                }
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
        this.theme.getCountries(this); 
        this.showRegister();
      }
    },
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	
</style>