<template>
<section>
<div class="dales-this-week">
	<div class="sperator text-center">
		<title>Artshop | Home</title>
	  <p>THE BLOG</p>
	  <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
	</div>
	<div class="home-blog-box">
	  <div data-number="3" data-margin="30" data-loop="yes" data-navcontrol="yes" class="sofani-owl-carousel">
	    <div class="blog-box" v-for="post in posts.slice(0, 10)" :key="post.id">
	      <div class="blog-img" @click.prevent="singlePost(post.slug)" style="cursor: pointer;">
	        <figure>
						<img 
							:src="theme.imagePath(post.file.path)" 
							:alt="post.title" height="250" 
							v-if="post.file"
							class="attachment-shop_catalog size-shop_catalog wp-post-image"/>
						<img 
							src="/images/logo/logo-black.jpg"
							:alt="post.title" 
							v-else
							class="attachment-shop_catalog size-shop_catalog wp-post-image"
						/>
					</figure>
	        <div class="blog-overlay"></div>
	      </div>
	      <div class="blog-text">
	        <div class="post-content">
	          <div class="post-info"><span class="post-date"><i class="fa fa-calendar"></i>{{post.published | moment("MMM Do, YYYY")}}           </span><span class="post-author"><i class="fa fa-pencil-square-o"></i>{{post.user.name}}</span></div>
	        </div>
	        <h4 class="entry-title"><a href="#" @click.prevent="singlePost(post.slug)">{{post.title}}</a></h4>
	        <p class="post-excerpt" v-html="post.body.slice(0, 80) + '...'"></p><a href="#" @click.prevent="singlePost(post.slug)" class="btn-readmore">Read more}</a>
	      </div>
	    </div>
	  </div>				
		<div class="home-blog-content-col button-home-blog"><router-link to="/blog">Load More</router-link></div>
	</div>
</div>
</section>
</template>
<script>
	export default{
		props: ['posts'],
		methods: {
			singlePost(slug) {
				this.$router.push('blog/' + slug);
			}
		},
		mounted () {
			this.theme.carousel();
		}
	}
</script>



<style>
	#productContent{
		/*background-color: #eee; */
		text-align:left; 
		/*border-radius: 5px; */
		padding: 8px;
		margin-bottom: 10px;
	}

	.jumbotron{
		padding: 0;
		background-color: transparent;
	}

	@media screen and (min-width: 768px){
		#prd-hr{
			display: none;
		}
		#productContent{
			border-right: 1px solid;
			padding-right: 40px;
		}
	}
</style>