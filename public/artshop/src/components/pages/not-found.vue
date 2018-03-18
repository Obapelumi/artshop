<template>
	<div class="not-found" id="not-found">
		<title>Page Not Found | ArtShop</title>
		<div class="container">
			<h1>Oops! Page not found</h1> <br> <br> <br>
	  		<router-link to="/" class="return-home">Return to homepage</router-link>
		</div>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				
			}
		},
		methods: {

		},
		mounted () {
			var $this = this;
			setTimeout(function () {
				$this.$emit('newsLetter');
			}, 3500);
		}
	}
</script>

<style>

</style>