<template>
<form class="col-md-10 offset-md-1 col-lg-8 offset-lg-0" id="create-product" @submit.prevent="register">
    <div id="customer_details" class="col2-set row">
    <div class="">
        <div class="woocommerce-billing-fields">
        <h3>CREATE NEWS LETTER</h3>
                <p style="width: 80%;">
                    <label>Title <abbr title="" class="required">*</abbr></label>
                    <input type="text" required v-model="letter.title">
                </p>
            <p id="order_comments_field" class="notes woocommerce-validated">
	            <label>Body <abbr>*</abbr></label>
	            <textarea id="newsletter_body" name="order_comments" placeholder="Express your art" rows="15" cols="5"></textarea>
	        </p>
              <div id="payment">
                <div class="place-order">
                  <button class="btn btn-transparent" type="submit" @click.prevent="submitLetter">SUBMIT</button>
                </div>
              </div>
        </div>
            <h3>NEWS LETTERS</h3>
        <div class="cart-form" v-if="newsletters.length !== 0">
			<table>
			    <tbody>
			    <tr>
			      <th>Title</th>
			      <th>Audience</th>
			      <th>Date Sent</th>
			    </tr>
			    <tr v-for="letter in newsletters" :key="letter.id">
			      <td data-title="Title">
			      	<a href="" >{{letter.title}}</a>
			      </td>
			      <td data-title="Audience">
			      	<span class="price">{{letter.audience}}</span>
			      </td>
			      <td data-title="Date Sent" v-if="letter.sent_at !== null"><span class="total">{{letter.sent_at | moment("dddd, MMMM Do YYYY")}}</span></td>
            <td data-title="Date Sent" v-else><span class="total">Pending</span></td>
			    </tr>
			  </tbody>
			</table>
		</div>
        <h1 v-else>No News Letters yet</h1>
    </div>
    </div>        
</form>
</template>

<script>
export default {
    props: ['user', 'newsletters'],
	data () {
		return {
            letter: {
                title: '',
                body: '',
            },
            body: '',
            timer: '',
		}
    },
    methods: {
        submitLetter () {
            this.letter.body = this.body;
            this.admin.submitNewsLetter(this);
        },
        setLetters () {
          this.admin.getNewsLetters(this);
        }
    },
    created () {
    //   this.setLetters();
    },
    mounted () {
        this.theme.getCKEditor(this, 'newsletter_body', 'body')
    },
    beforeDestroy () {
        this.timer = '';
        document.getElementById('editor').id = 'newsletter_body';
    },
}
</script>

<style>
	
</style>