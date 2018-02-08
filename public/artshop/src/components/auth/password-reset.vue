<template>
	<section>
        <div class="container">
          <div class="form-customers" id="returning-customer">
            <h1 class="check-out-title">PASSWORD RESET</h1>
            
            <div class="woocommerce-checkout-info">
                <h3>Enter Your E-mail Address</h3>
            </div>
            <form class="login" @submit.prevent="reset">
              <p>
                <label>Email <span class="required">*</span></label>
                <input id="username" name="username" type="email" v-model="email" required>
              </p>
              <div class="clear"></div>
              <p>
                <button class="btn btn-transparent" type="submit">RESET PASSWORD</button><br><br>
              </p>
              <p class="lost_password">
								<router-link to="/login">Login</router-link>
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
            }
		},
		methods: {
			showLogin() {
			    $('.login').slideToggle("slow");
      },
      reset() {
        var $this = this
        $this.theme.submitting();
        this.axios.post('mail/password-reset')
          .then(response => {
            $this.theme.submitted();
            $this.theme.smoke('success', 'Your new password has been sent to your e-mail address', 5000);
            setTimeout(function (){
              $this.$router.push('/login'); 
            }, 5000);
          })
          .catch(response => {
            $this.theme.submitted();
            $this.theme.smoke('error', 'There was an issue. Check the email and try again', 5000);
          });
      }
		},
    mounted () {
      this.showLogin();
    }
	}
</script>

<style scoped>
	.woocommerce-checkout-info h4:hover {
		color: #0dab76;
	}	
</style>