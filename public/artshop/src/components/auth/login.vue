<template>
	<section>
				<title>Login | Artshop</title>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">LOGIN</h1>
            
            <div class="woocommerce-checkout-info">
                <!-- <h3>Enter Your E-mail Address</h3> -->
            </div>
            <form class="login" @submit.prevent="login">
              <p>
                <label>Email <span class="required">*</span></label>
                <input id="username" name="username" type="email" v-model="email" required>
              </p>
							<p>
                <label>Password   <span class="required">*</span></label>
                <input id="password" name="password" type="password" v-model="password" required>
              </p>
              <div class="clear"></div>
              <p>
                <button class="btn btn-transparent" type="submit">LOGIN</button><br><br>
              </p>
							<p class="lost_password">
								<router-link to="/register">Don't have an account?</router-link> <br>
								<router-link to="/password-reset">Lost your password?</router-link>
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
				email: '',
				password: '',
			}
		},
		methods: {
			showLogin() {
			    $('.login').slideToggle("slow");
      },
			login() {
				var $this = this;
				this.$validator.validateAll().then(() => {
					$this.auth.login($this, $this.theme.config.NEXT, $this.email, $this.password);
				});
			},
		},
		mounted () {
      this.showLogin();
    }
	}
</script>

<style>

	#login .sperator {
		margin-top: 70px;
	}

/*	#login .newletter-content .email-label {
		position: relative;
		left: -37.5%;
	}

	#login .newletter-content .password-label {
		position: relative;
		left: -36%;
	}*/

	#login .submit {
		text-align: center;
	}

/*	#login .newletter-content .btn {
	    display: inline-block;
	    margin-top: 20px;
	    padding: 10px 20px;
	    text-transform: uppercase;
	    border-radius: 0;
	}*/

/*	#login .newletter-content .btn:hover {
	    color: #fff;
	    background: #0dab76;
	}*/
</style>