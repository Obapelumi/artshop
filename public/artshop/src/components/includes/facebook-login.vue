<template>
    <button 
        class="btn btn-transparent" 
        @click.prevent="loginWithFacebook()" 
        type="submit"><i class="fa fa-facebook">
        </i> {{verb}} WITH FACEBOOK
    </button>
</template>

<script>
export default {
props: ['verb'],
methods: {
		    activateFaceBookLogin () {
                (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12&appId=310422192779199&autoLogAppEvents=1';
                fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
			},
			loginWithFacebook () {
				let $this = this;
				$this.theme.submitting();
				FB.login(function(response) {
					if (response.status == 'connected') {
						FB.api('/me', { locale: 'en_US', fields: 'name, email' },
							function(response) {
								$this.theme.submitting();
								$this.axios.post('facebook-login', response)
									.then(response => {
										$this.theme.submitted();
										var welcomeMessage = 'Welcome ' + response.data.user.name;
										$this.theme.smoke('success', welcomeMessage, 3000);
										$this.auth.setToken($this, $this.theme.config.NEXT, response.data.token.accessToken, response.data.token.token.expires_at, response.data.user);
									})
							}
						);
					}
					else {
						$this.theme.submitted();
						$this.theme.smoke('error', 'Facebook Authentication Failed', 5000);
					}
				}, {scope: 'public_profile,email'});
			}
		},
    created () {
		this.activateFaceBookLogin();
	}
}
</script>

