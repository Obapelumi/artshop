<template>
<form class="col-md-10 offset-md-1 col-lg-8 offset-lg-0" id="create-product" @submit.prevent="submitPost">
    <div id="customer_details" class="col2-set row">
    <div class="">
        <div class="woocommerce-billing-fields">
        <h3>EDIT POST</h3>
                <p style="width: 80%;">
                    <label>Title <abbr title="" class="required">*</abbr></label>
                    <input type="text" v-model="post.title">
                </p>
            <art-image-update :file="post.file" :meta="meta"></art-image-update>
            <p id="order_comments_field" class="notes woocommerce-validated">
	            <label>Body <abbr>*</abbr></label>
	            <textarea id="edit_blog_body" value="moments" name="order_comments" placeholder="Express your art" rows="15" cols="5"></textarea>
	        </p>
            <div class="clearfix col-lg-6 col-md-6">
                <p>
                    <label>SEO Key Words <abbr title="" class="required">*</abbr></label>
                    <input type="text" v-model="post.seo_key_words">
                </p>
            </div>
            <!-- <div class="clearfix col-lg-6 col-md-6">
                <p>
                    <label>Tags <abbr title="seperate each tag with a comma" class="required">?</abbr></label>
                    <input type="text" v-model="post.tags">
                </p>
            </div> --> <div class="clearfix"></div>
                  <div class="button-cart-right">
                    <button class="btn btn-transparent"  type="submit"> UPDATE</button>
                  </div>
        </div>
    </div>
    </div>        
</form>
</template>

<script>
export default {
    props: ['posts', 'user'],
	data () {
		return {
            post: null,
            body: '',
            timer: '',
            myPosts: [],
            meta: {
		        image: {
                vendor_id: null,
		        slug: null,
              }
		    },

		}
    },
    methods: {
        submitPost () {
            this.post.body = this.body;
            this.admin.updateBlogPost(this)
        },
        saveDraft () {
            this.post.published = false;
            this.submitPost();
        },
        setPost () {
            this.post = this.admin.singlePost(this);
        },
        setMeta () {
            this.meta.image.blog_id = this.post.id;
            this.meta.image.slug = this.post.slug;
        }
    },
    watch: {
        posts () {
            this.setPost();
        }
    },
    created () {
        var $this = this;
        // this.$emit('updatePosts');
        if (this.posts) {
            this.setPost();
        }
        this.setMeta();
    },
    mounted () {
        this.theme.getCKEditor(this, 'edit_blog_body', 'body');
    },
    beforeDestroy () {
        this.timer = '';
        document.getElementById('editor').id = 'edit_blog_body';
    },
}
</script>

<style>
	
</style>