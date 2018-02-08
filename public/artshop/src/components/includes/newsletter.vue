<template>
	<div class="section">
          <div class="sn-newletter-style1">
            <div class="container">
              <div class="sperator text-center">
                <p>NEWSLETTER</p>
                <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
              </div>
              <div class="newletter-content">
                <p>Get Updates on products and events on Artshop</p>
                <form @submit.prevent="submit">
                  <input type="email" placeholder="Subscribe your email here" v-model="email" required/><i class="fa fa-envelope-o"></i> <br> <br> <br>
                  <button type="submit" class="btn btn-color-white btn-background-white">Subscribe</button>
                </form>
              </div>
            </div>
          </div>
    </div>
</template>

<script>
	export default{
    data () {
      return {
        email: null,
      }
    },
    methods: {
      submit () {
        var $this = this;
        $this.theme.submitting();
        this.axios.post('mail/subscribe', {email: this.email})
          .then(response => {
            $this.theme.submitted();
            $this.$emit('subscribed');
            $this.theme.smoke('success', 'Thank You for Subscribing', 3000)
          })
          .catch(response => {
            $this.theme.submitted();
            if (response.status === 422) {
              $this.theme.smoke('error', 'Please check your email and try again', 4000);
            }
            else {
              $this.theme.smoke('error', 'There was an issue please try again', 4000);
            }
          });
      },
    }
	}
</script>

<style>

</style>