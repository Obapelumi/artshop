<template>
	<div>
		<img src="/images/logo/logo-black.jpg" class="ld ld-heartbeat" id="loader-image" v-if="loading">
		<transition name="fade">
          	<div class="artshop-modal" v-if="showNewsLetter">
				<button class="closer" @click.prevent="togglePopUp()"><i class="fa fa-times"></i></button>
                <art-newsletter @subscribed="togglePopUp()"></art-newsletter>
            </div>                
		</transition>    
	  	<art-header 
	  		:cart="cart" 
	  		:checkCart="checkCart"
			:check="check"
	  		:wishList="wishList"
			:products="products"
			:vendors="vendors"
			:categories="categories">
	  	</art-header>
	  	<transition name="fade">
	  		<router-view 
		  		:cart="cart"
		  		:wishList="wishList" 
		  		:checkCart="checkCart"
				:check="check" 
		  		:products="products"
				:vendors="vendors"
		  		:categories="categories"
				:tags="tags"
				:posts="posts"
				:reviews="reviews"
				:API_URL="API_URL"
				@setAuth="setAuth()"
				@loadMore="loadMore"
		  		@updateProduct="getProducts"
				@updateShop="getShop"
		  		@getMore="loadMore"
		  		@updateCart="getCart"
		  		@updateWishList="getWishList"
				@updatePosts="getPosts"
				@updateReviews="getReviews"
				@newsLetter="togglePopUp()"
	  		></router-view>
	  	</transition>
        <art-footer></art-footer>
	</div>
</template>

<script>
export default {
 	name: 'app',
	data () {
    	return {
    		cart: {},
    		wishList: {},
			products: [],
			posts: [],
			categories: [],
			tags: [],
			vendors: [],
			reviews: [],
    		take: 20,
			checkCart: false,
			check: {
				auth: false,
				vendor: false,
				admin: false,
				blogger: false,
			},
			realTime: '',
			loading: false,
			API_URL: 'http://localhost:8000/api/',
			showNewsLetter: false,
			firstLoad: true,
		}
	},
	methods: {
		setAuth () {
			this.check.auth = this.auth.checkAuth();
			this.check.vendor = this.auth.checkVendor();
			this.check.admin = this.auth.checkAdmin();
			this.check.blogger = this.auth.checkBlogger();
		},
		getProducts () {
			var display;
			if (this.auth.checkAdmin()) {
				display = 'pending';
			}
			else {
				display = 'approved'
			}
			this.shop.getProducts(this, display, this.take);
		},
		getVendors () {
			this.shop.getVendors(this);
		},
		getPosts () {
			this.admin.getBlogPosts(this);
		},
		loadMore() {
			if (this.take <= this.products.length) {
				this.take += 10;
				this.getProducts();	
			}
			else {
				this.theme.submitted();
			}
		},
		loadPeriodic () {
			var $this = this;
			setInterval(function () {
				$this.loadMore();
			}, 3000);
		},
		getCart ($this = this) {
			$this.cart = $this.shop.getCart();
			if ($this.shop.checkCart() === false) {
				$this.checkCart = false;
			}
			else {
				$this.checkCart = true;
			}
		},
		getWishList ($this = this) {
			$this.wishList = $this.shop.getWishList();
		},
		getShop () {
			var $this = this;
			$this.getProducts(); 
			$this.shop.getVendors($this);
			$this.admin.getBlogPosts($this); 
			$this.getCart($this); 
			$this.getWishList($this); 
			$this.shop.getTags($this);
		},
		getReviews () {
			this.shop.getReviews(this);
		},
		togglePopUp () {
			if (this.firstLoad || this.$route.path == '/not-found') {
				this.showNewsLetter = !this.showNewsLetter;
				this.firstLoad = false;
			}
			else {
				this.showNewsLetter = false;
			}
		},
	},

	mounted () {
		$('#primary-menu').mmenu({
                extensions: ['effect-slide-menu', 'pageshadow'],
                navbar: {
	                title: 'Menu'
				},                    
				onClick: {
                    close: true,
                },
                navbars: [
                    {                            
						position: 'top',
                        content: [
                            'prev',
                            'title',
                            'close'
                        ]
					}                    
				]
            }, {
                // configuration
				clone: true
		});
		var $this = this;
	},
	beforeCreate () {
		if (this.auth.checkAuth()) {
			this.checkAuth = true;
			this.theme.getConfig(this);	
		}
	},
	created () {
		var $this = this;
		$this.getPosts ();
		$this.getProducts();
		$this.getReviews();
		$this.vendor.getVendors($this);
		$this.shop.getCategories(this);
		$this.shop.getTags(this);
		$this.getCart();
		$this.getWishList();
		setInterval(function () {
			$this.loadMore();
		}, 3000);

		// if ($this.products.length <= 0 && $this.$route.path.includes('/product/')) {
		// 	$("#preloaderKDZ").fadeIn(950);
		// 	$this.theme.config.PREV = $this.$route.path;
		// 	$this.$router.push('/shop');
		// }		
	},
}
</script>
<style>
	.has-header section,
	.has-header .section {
		position: relative;
		top: -90px;
		margin-bottom: 0px;
	}
	.fade-enter-active, .fade-leave-active {
	  transition-property: opacity;
	  transition-duration: .25s;
	}

	.fade-enter-active {
	  transition-delay: .20s;
	}

	.fade-enter, .fade-leave-active {
	  opacity: 0
	}
	.slideRight-leave-active,
	.slideRight-enter-active {
		transition-property: opacity;
		transition: 0.5s;
	}
	.slideRight-enter {
		transform: translate(-100%, 0);
		 opacity: 0
	}
	.slideRight-leave-to {
		transform: translate(100%, 0);
	}

	.mm-menu {
		background: white;
	}

	.reject:hover,
	.reject:focus {
		background-color: #C91713;
	}
	/* .router-link-exact-active a {
		background: #0dab76;
		color: white;
	} */
</style>
