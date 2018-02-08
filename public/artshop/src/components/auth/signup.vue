<template>
	<div class="section" id="login">
          <div class="sn-newletter-style1">
            <div class="container">
              <div class="sperator text-center">
                <p>Sign Up</p>
                <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
              </div>
              <div class="newletter-content">
                <form @submit.prevent="signup">
                  <div class="form-group">
                    <label for="name" class="password-label"><h3>Full Name</h3></label><br>
                  	<input type="text" name="name" placeholder="Full Name" v-model="name"/>
                  </div>
                  <div class="form-group">
	                  <label for="email" class="email-label"><h3>Email</h3></label><br>
	                  <input type="text" name="email" placeholder="Email" v-model="email" />
                  </div>
                  <div class="form-group">
                    <label for="password" class="password-label"><h3>Password</h3></label><br>
                  	<input type="password" name="password" placeholder="Password" v-model="password" />
                  </div>
                  <div class="form-group">
                    <label for="confirm-password" class="password-label"><h3>Confirm Password</h3></label><br>
                  	<input type="password" name="confirm-password" placeholder="Confirm Password" v-model="confirmPassword" />
                  </div>
				  <div class="form-group submit">
				  	<button class="btn btn-transparent" type="submit">SIGN UP</button>
				  </div>
                </form>
              </div>
            </div>
          </div>
    </div>
</template>

<script>
	export default{
		data(){
			return{
				name: '',
				email: '',
				password: '',
				confirmPassword: '',
			}
		},
		methods: {
			signup() {
				var url = 'http://localhost:8000/';
				var data = {
					name: this.name,
					email: this.email,
					password: this.password,
					c_password: this.confirmPassword,
				};
				
				this.axios.post(url + 'api/signup', data)
					.then(function (response) {
						console.log(response);
					})
					.catch(function (response) {
						console.log(response);
					});
			},
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
	}
*/
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