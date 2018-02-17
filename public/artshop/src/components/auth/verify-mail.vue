<template>
	<section>
        <title> Verify Mail | Artshop</title>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">VERIFY E-MAIL ADDRESS</h1>
            
            <div class="woocommerce-checkout-info">
                <h3>Enter Your E-mail <span>and Verification Code</span></h3>
            </div>
            <form class="login" @submit.prevent="verify">
              <p>
                <label>Email <span class="required">*</span></label>
                <input id="username" name="username" type="email" v-model="email" required>
              </p>
              <p>
                <label>Verification Code <span class="required">*</span></label>
                <input id="username" name="username" type="text" v-model="verification_code">
              </p>
              <div class="clear"></div>
              <p>
                <button class="btn btn-transparent" type="submit">VERIFY</button><br> OR <br>
                <button class="btn btn-transparent" type="submit" @click="resendCode = true">RESEND CODE</button><br>
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
                email: null,
                verification_code: null,
                resendCode: false,
            }
		},
		methods: {
			showLogin() {
			    $('.login').slideToggle("slow");
            },
            verify() {
                if (this.resendCode === true) {
                    this.sendVerificationCode();
                }
                else {
                    this.auth.verifyMail(this, this.theme.config.NEXT);
                }
            },
            sendVerificationCode () {
                this.theme.submitting();
                this.auth.sendVerificationCode(this, this.email);
            },
        },
        mounted () {
            this.showLogin();
        },
        created () {
            var user = this.auth.getUser();
            if (user != null) {
                this.email = user.email;
            }
        }
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	
</style>