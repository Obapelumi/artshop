<template>
<section>
	<div class="sperator text-center">
	  <p>THE BLOG</p>
	  <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
	</div>
          <div class="home-blog-content">
            <div class="container">
              <div class="home-blog-content-col">
                <div class="post-item blog-grid" v-for="post in posts.slice(0, 3)" :key="post.id">
                  <div class="entry-wrap">
                    <div class="entry-thumbnail-wrap">
                      <div class="entry-thumbnail">
												<router-link :to="'/blog/'+ post.slug" :title="post.title" class="entry-thumbnail_overlay">
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
												</router-link>
												<a href="#" class="prettyPhoto">
													<i class="fa fa-arrows-alt"></i>
												</a>
											</div>
                      <div class="date-overlay">
												<span class="day">{{post.published | moment("MMM Do, YYYY")}}</span>
											</div>
                    </div>
                    <div class="entry-content-wrap">
                      <div class="entry-detail">
                        <h3 class="entry-title p-font">
													<router-link :to="'/blog/'+ post.slug" href="#"> {{post.title}} </router-link>
												</h3>
                        <div class="entry-post-meta-wrap">
                          <ul class="entry-meta">
                            <li class="entry-meta-author"><i class="fa fa-pencil-square-o p-color"></i>
															<router-link :to="'/blog/'+ post.slug" href="#"> {{post.user.name}} </router-link>
														</li>
                            <li class="entry-meta-date">
															<i class="fa fa-clock-o p-color"></i>
															<router-link :to="'/blog/'+ post.slug" href="#"> {{post.published | moment("MMM Do, YYYY")}} </router-link>
														</li>
                            <li class="entry-meta-category"><i class="fa fa-folder-open p-color"></i><a href="#">Creative Design</a></li>
                            <li class="entry-meta-comment"><a href="#"><i class="fa fa-comments-o p-color"></i> 0 Comment</a></li>
                          </ul>
                        </div>
                        <div class="entry-excerpt" style="margin-top: 20px;">
                          <p v-html="post.body.slice(0, 100) + '...'"></p>
                        </div><a href="#" class="btn-readmore"><span class="span-text">Read more</span></a>
                      </div>
                    </div>
                  </div>
                </div> <div class="clearfix"></div>
								<div class="button-home-blog"><router-link to="/blog">Load More</router-link></div>
              </div>
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