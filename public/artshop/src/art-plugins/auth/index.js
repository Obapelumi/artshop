const auth = {
	signup ($this, next) {
		$this.theme.submitting();
		var data = $this.user;

		$this.axios.post('signup', data)
			.then(response => {
				$this.theme.submitted();
				$this.auth.sendVerificationCode($this, response.data.user.email);
				var welcomeMessage = 'Hi ' + response.data.user.name + ', Welcome to Artshop';
				$this.theme.smoke('success', welcomeMessage, 3000);
				localStorage.setItem('user', JSON.stringify(response.data.user));
				$this.$router.push(next);	
			})
			.catch(response => {
				$this.theme.submitted();
				var errorMessage = 'Hi, there was an issue with sending your verification code';
				$this.theme.smoke('error', errorMessage, 3000);				
			});
	},

	sendVerificationCode ($this, email) {
		// $this.theme.submitting();
		$this.axios.get('mail/verification-code/' + email)
			.then(response => {
				$this.resendCode = false;
				$this.theme.submitted();
				var message = 'We just sent you an e-mail containing your verification code'
				$this.theme.smoke('success', message, 5000);
			})
			.catch(response => {
				$this.resendCode = false;
				$this.theme.submitted();
				$this.theme.smoke('error', 'We could not verify your E-mail address', 5000);
			});
	},

	verifyMail ($this, next) {
		$this.theme.submitting();
		var data = {
			email: $this.email,
			verification_code: $this.verification_code,
		}

		$this.axios.post('mail/verify-email', data)
			.then(response => {
				$this.theme.submitted();
				var welcomeMessage = 'Welcome ' + response.data.user.name;
				$this.theme.smoke('success', welcomeMessage, 3000);
				setInterval(function () {
					$this.auth.setToken($this, next, response.data.token.accessToken, response.data.token.token.expires_at, response.data.user);
				}, 3000);
			})
			.catch(response => {
				$this.theme.submitted();
				var errorMessage = 'Please check your verification code or resend code';
				$this.theme.smoke('error', errorMessage, 5000);
			});
	},

	login($this, next, email, password) {
		$this.theme.submitting();
		var data = {
			email: email,
			password: password,
		}

		$this.axios.post('signin', data)
			.then(response => {
				$this.theme.submitted();
				var welcomeMessage = 'Welcome ' + response.data.user.name;
				$this.theme.smoke('success', welcomeMessage, 3000);
				$this.auth.setToken($this, next, response.data.token.accessToken, response.data.token.token.expires_at, response.data.user);
			})
			.catch(response => {
				$this.theme.submitted();
				if (response.message === 'Request failed with status code 405') {
					var info = 'Please Verify Your email Address';
					$this.theme.smoke('info', info, 5000);
					$this.$router.push('/verify-mail');
				}
				else {
					var errorMessage = 'Hi, there was a problem signin you in. Check your user name and password';
					$this.theme.smoke('error', errorMessage, 5000);	
				}
			});
	},

	updateAccount ($this, data, next) {
		$this.theme.submitting();
		$this.axios.put('user', data)
			.then(response => {
				$this.theme.submitted();
				var user = response.data.user;
				$this.theme.smoke('success', 'Account Updated', 2000);
				setTimeout(function () {
					localStorage.setItem('user', JSON.stringify(user));
					$this.$router.push(next);
				}, 2000)
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'there was an issue please try again', 3000);
			});
		$this.theme.submitting();
		$this.axios.put('customer/' + $this.user.customer.id, data)
			.then(response => {
				$this.theme.submitted();
				$this.user.customer = response.data.customer;
				localStorage.setItem('user', JSON.stringify($this.user));
				$this.theme.smoke('success', 'Account Updated', 2000);
			})
			.catch(response => {
				$this.theme.submitted();
				$this.theme.smoke('error', 'there was an issue please try again', 3000);
			});
	},

	setToken ($this, next, token, expiresIn, user) {
		localStorage.setItem('token', token);
		localStorage.setItem('expiresIn', expiresIn);
		localStorage.setItem('user', JSON.stringify(user));
		if (next == null) {
			next = '/dashboard';
		}
		if ($this.auth.checkAuth()) {
			$this.axios.defaults.headers.common['Authorization'] = 'Bearer ' + auth.getToken().token;
		}
		$this.$emit('setAuth');
		$this.$router.push(next);
		if ($this.$route.path == '/login') {
			$this.$router.push('/dashboard');
		}
		// $this.$router.go($this.$route.path);
	},

	getToken ()  {
		var token = {
			token: localStorage.getItem('token'),
			expiresIn: localStorage.getItem('expiresIn'),
			user: JSON.parse(localStorage.getItem('user')),
		}

		if (!token.token || !token.expiresIn) {
			return null;
		}	
		else {
			return token;
		}
	},

	checkAuth ()  {
		// var user = JSON.parse(localStorage.getItem('user'));
		// // console.log(user.name);
		// if (user === null) {
		// 	return false;
		// }
		// else if (user.meta) {
		// 	console.log('bug');
		// 	if (user.meta.verified > 0) {
				var token = localStorage.getItem('token');
				if (token != null) {
					if (token.length > 5) {
						return true;	
					}
					else {
						return false;
					}
				}
				else {
					return false;
				}
			// }
			// else {
			// 	console.log('bug');
			// 	return false;
			// }
		// }
		// else {
		// 	return false;
		// }
	},

    checkCustomer () {
		if (!this.checkAuth()) {
			return false
		}
		else {
			var user = JSON.parse(localStorage.getItem('user'));
			if (user.customer !== null) {
				return true;
			}
			else {
				return false;
			}
		}
	},

    checkVendor () {
		if (!this.checkAuth()) {
			return false
		}
		else {
			var user = JSON.parse(localStorage.getItem('user'));
			if (user.vendor !== null) {
				return true;
			}
			else {
				return false;
			}
		}
	},

	checkBlogger () {
		if (!this.checkAuth()) {
			return false
		}
		else {
			var user = JSON.parse(localStorage.getItem('user'));
			if (user.blogger !== null) {
				return true;
			}
			else {
				return false;
			}
		}
	},
	
	checkAdmin() {
		if (!this.checkAuth()) {
			return false
		}
		else {
			var user = JSON.parse(localStorage.getItem('user'));
			if (user.admin !== null) {
				return true;
			}
			else {
				return false;
			}
		}
	},

	getUser ($this = this)  {
		var user = JSON.parse(localStorage.getItem('user'));
		return user;
	},

	signout ($this)  {
		$this.theme.submitting();
		$this.axios.delete('signout')
			.then(response => {
				$this.theme.submitted();
				var successMessage = 'Bye! come back soon';
				$this.theme.smoke('success', successMessage, 2000);
				localStorage.removeItem('token');
				localStorage.removeItem('user');
				localStorage.removeItem('expiresIn');
				$this.$router.go('/login');
			})
			.catch(response => {
				$this.theme.submitted();
				var successMessage = 'Bye! come back soon';
				$this.theme.smoke('success', successMessage, 2000);
				localStorage.removeItem('token');
				localStorage.removeItem('user');
				localStorage.removeItem('expiresIn');
				$this.$router.go('/login');
			});
	},

	registerCustomer($this, next) {
		$this.theme.submitting();
		var data = {
			phone: $this.customer.phone,
			address: $this.customer.address,
			zip_code: $this.customer.zip_code,
		};

		$this.axios.post('customer', data)
			.then(response => {
				$this.theme.submitted();
				if (response.data.status == true) {
					var user = $this.auth.getUser();
					user.customer = response.data.customer;
					var welcomeMessage = 'Hi ' + user.name + ', welcome to Artshop';
					$this.theme.smoke('success', welcomeMessage, 3000);
					setTimeout(function(){
                        localStorage.setItem('user', JSON.stringify(user));
                        $this.$router.push(next);
                    }, 3000);
				}
			})
			.catch(response => {
				$this.theme.submitted();
				var errorMessage = 'Hi, there is a problem signin you up';
				$this.theme.smoke('error', errorMessage, 3000);
			});
	},

    install: function(Vue){
      Object.defineProperty(Vue.prototype, 'auth', {
        get () { 
        	return auth; 
        }
      })
    },
}

export default auth;