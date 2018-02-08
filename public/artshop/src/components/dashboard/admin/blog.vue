<template>
<form class="col-md-10 offset-md-1 col-lg-8 offset-lg-0" id="create-product" @submit.prevent="register">
    <div id="customer_details" class="col2-set row">
    <div class="">
        <div class="woocommerce-billing-fields">
        <h3>CREATE POST</h3>
                <form @submit.prevent="submitPost">
                <p style="width: 80%;">
                    <label>Title <abbr title="" class="required">*</abbr></label>
                    <input type="text" required v-model="post.title">
                </p>
                <p>
                    <label>Cover Image</label>
                    <art-image-upload :meta="post.meta" :upload="uploadImage"></art-image-upload>
                </p>
                <p id="order_comments_field" class="notes woocommerce-validated">
                    <label>Body <abbr>*</abbr></label>
                    <textarea id="blog_body" value="moments" name="order_comments" placeholder="Express your art" rows="15" cols="5"></textarea>
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
                    <a class="btn btn-black" @click.prevent="saveDraft" style="margin-left: 60px">Save as draft</a>
                    <button class="btn btn-transparent" style="margin-left: 55%; margin-top: 32px;" type="submit"> PUBLISH</button>
                  </div>
                </form>
        </div> <br>
        <h3>BLOG POSTS</h3>
        <div class="cart-form" v-if="posts.length !== 0">
			<table>
			    <tbody>
			    <tr>
			      <th> </th>
			      <th>Title</th>
			      <!-- <th>Views</th>
			      <th>Likes & Shares</th> -->
			      <th>Date Published</th>
                  <th>Edit</th>
			    </tr>
			    <tr v-for="blogPost in posts" :key="blogPost.id">
			      <td data-title="Image">
			      	<a href="#" class="image-product">
                        <img 
                            :src="theme.imagePath(blogPost.file.path)" 
                            :alt="blogPost.title" 
                            width="180" height="220" style="height: 80px; width: 180px"
                            v-if="blogPost.file"
                        >
                        <img 
                            src="/images/logo/logo-black.jpg"
                            :alt="blogPost.title" 
                            width="180" height="220" style="height: 80px; width: 180px" 
                            v-else
                        >
                    </a>
			      </td>
			      <td data-title="Title">
			      	<a href="" >{{blogPost.title}}</a>
			      </td>
			      <!-- <td data-title="Views">
			      	<span class="price">{{blogPost.views}}</span>
			      </td>
			      <td data-title="Comments">
			      	<span class="quantity"></span>
			      </td> -->
			      <td data-title="Published" v-if="post.published === true"><span class="total">{{blogPost.published | moment("dddd, MMMM Do YYYY")}}</span></td>
                  <td data-title="Publish" v-else><span class="total"><a href="">Publish</a></span></td>
                  <td data-title="Edit">
                      <router-link :to="'/dashboard/blog/edit/'+blogPost.slug">
                          <i class="fa fa-pencil" style="cursor: pointer">
                    </i></router-link>
                  </td>

			    </tr>
					
			  </tbody>
			</table>
		</div>
        <h1 v-else>No Posts yet</h1>
    </div>
    </div>        
</form>
</template>

<script>
export default {
    props: ['posts', 'user'],
	data () {
		return {
            post: {
                title: null,
                body: '',
                tags: '',
                seo_key_words: '',
                published: true,
                meta: {
                    image: {
                        blog_id: 0,
                        slug: '',
                    },
                },
            },
            body: '',
            timer: '',
            myPosts: [],
            uploadImage: false,
		}
    },
    methods: {
        submitPost () {
            this.post.body = this.body;
            this.admin.submitBlogPost(this)
        },
        saveDraft () {
            this.post.published = false;
            this.submitPost();
        },
        setMyPosts () {
            this.myPosts = this.admin.postsByBlogger(this);
        }
    },
    mounted () {
        console.log('mounted')
        this.theme.getCKEditor(this, 'blog_body', 'body');
    },
    beforeDestroy () {
        this.timer = '';
        document.getElementById('editor').id = 'blog_body';
    },
}
</script>

<style>
	
</style>