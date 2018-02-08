<template>
<div class="container" id="#art-notification" v-show="show">
    <div class="row">
        <div class="alert alert-success" v-show="success">
            <button class="close" type="button" aria-hidden="true" @click="hide">&times;</button>
            <strong> {{success}} </strong>
        </div>
    </div>
    <div class="row" style="margin-top: 20px;" v-show="error">
        <div class="alert alert-danger">
            <button class="close" type="button" @click="hide" aria-hidden="true">&times;</button>
            <strong> {{error}} </strong>
        </div>
    </div>
</div>
</template>

<script>
export default{
	props: ['success', 'error'],
	data(){
		return{
			show: true,
			showError: true,
			showSuccess: true,
		}
	},
	methods: {
		hide () {
			console.log('hide')
			this.show = false
		},
	},
	watch: {
		error () {
			this.show = true;
		},
		success () {
			this.show = true;
		},
	},
	created () {

	},
}
</script>

<style>
	#art-notification {
		margin-left: 20%;
	}
</style>