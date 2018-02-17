<template>
<div class="timeline col-md-10 offset-md-1 col-lg-8 offset-lg-0">
  
  <h1>Reviews</h1>
  
  <ul v-if="reviews.length > 0">
    <li v-for="review in reviews" :key="review.id">
      <div class="content reply">
        <h2>
          <h4>{{review.product.name}}</h4><br>
          <p>{{review.user.name}}</p>
          <p>{{review.created_at | moment("dddd, MMMM Do YYYY")}}</p>
        </h2>
        <p>{{review.review}}</p>
        <a v-if="review.platform !== 'spotlight'" @click="featureReview(review)" style="cursor: pointer;">FEATURE</a>
        <a v-else @click="hideReview(review)" style="cursor: pointer;">HIDE</a>
      </div>
    </li>
  </ul>
  <h2 v-else style="text-align: center;">No reviews yet</h2>
</div>
</template>

<script>

	export default{
		props: ['user', 'orders', 'myOrders', 'products', 'reviews'],
		data(){
			return{

			}
		},
		methods: {
			editPage (slug) {
	        var push = '/dashboard/edit-product/' + slug;
	        this.$router.push(push);
			},
		    singleProduct (slug) {
		      var push = '/product/' + slug;
		      this.$router.push(push);
	        },
	        // setProducts () {
			// 	var id = this.user.vendor.id;
			// 	var myProducts = this.shop.productsByVendor (this, id);
			// 	var reviewedProducts = myProducts.filter(product => product.review.length > 0);

			// 	for (var i = 0; i < reviewedProducts.length; i++) {
			// 		var reviews = reviewedProducts[i].review;
			// 		for (var j = 0; j < reviews.length; j++) {
			// 			reviews[j]['product'] = reviewedProducts[i]
			// 		}
			// 		this.reviews.push(reviews);
			// 	}
            // },
            featureReview (review) {
                let data = {
                    product_id: review.product.id,
                    platform: 'spotlight',
                };
                var $this = this;
                $this.theme.submitting();
                $this.axios.put('review/'+review.id, data)
                    .then(response => {
                        $this.$emit('updateReviews');
                        $this.theme.smoke('success', 'This review has been featured and will appear on the home page', 5000);
                        $this.theme.submitted();
                    })
                    .catch(response => {
                        $this.theme.smoke('error', 'There was an error please try again', 5000);
                        $this.theme.submitted(); 
                    });
            },
            hideReview (review) {
                let data = {
                    product_id: review.product.id,
                    platform: 'artshop',
                };
                var $this = this;
                $this.theme.submitting();
                $this.axios.put('review/'+review.id, data)
                    .then(response => {
                        $this.$emit('updateReviews');
                        $this.theme.smoke('success', 'This review has been hidden and will no longer appear on the home page', 5000);
                        $this.theme.submitted();
                    })
                    .catch(response => {
                        $this.theme.smoke('error', 'There was an error please try again', 5000);
                        $this.theme.submitted(); 
                    });
            }
        },
		watch: {
			products () {
				// this.setProducts();	
			}
		},
		created () {
			if (this.products) {
				// this.setProducts();	
			}
		},

	}
</script>

<style>
*, *::before, *::after {
  margin: 0;
  padding: 0;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
body {
  font-size: 16px;
}
.timeline,
.timeline h4 {
  color: #fff;

}
.content {
	border-radius: 5px;
}
.timeline h1 {
  background: #fff;
  font-size: 2.5em;
  text-align: center;
}
.timeline ul {
  background: #fff;
  padding: 50px 0;
}
.timeline ul li {
  background: #c4d6b0;
  position: relative;
  margin: 0 auto;
  width: 1.5px;
  padding-bottom: 40px;
  list-style-type: none;
}
.timeline ul li:last-child {
  padding-bottom: 7px;
}
.timeline ul li:before {
  content: '';
  background: #fff;
  position: absolute;
  left: 50%;
  top: 0;
  transform: translateX(-50%);
  -webkit-transform: translateX(-50%);
  width: 20px;
  height: 20px;
  border: 1px solid #c4d6b0;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  border-radius: 50%;
}
.timeline ul li .hidden {
  opacity: 0;
}
.timeline ul li .content {
  background: #c4d6b0;
  position: relative;
  top: 7px;
  width: 350px;
  padding: 20px;
}
.timeline ul li .content h2 {
  color: #fff;
  padding-bottom: 10px;
  text-align: center;
}
.timeline ul li .content p {
  color: #fff;
  text-align: center;
}
.timeline ul li .content:before {
  content: '';
  background: #c4d6b0;
  position: absolute;
  top: 0px;
  width: 40px;
  height: 1.5px;
}
.timeline ul li:nth-child(even) .content,
.timeline ul li:nth-child(even) .content h4,
.timeline ul li:nth-child(even) .content p,
.timeline ul li:nth-child(even) .content a {
  left: 50px;
  background: #fff;
  color: #000;
  
}
.timeline ul li:nth-child(even) .content {
	border: 3px solid #c4d6b0;
}
.timeline ul li:nth-child(even) .content:before {
  left: -41px;
}
.timeline ul li:nth-child(odd) .content {
  left: calc(-350px - 45px);
  background: #c4d6b0;
  background: -webkit-linear-gradient(45deg, #c4d6b0, #c4d6b0);
  background: linear-gradient(45deg, #c4d6b0, #c4d6b0);
}
.timeline ul li:nth-child(odd) .content a{
    color: #fff;
}
.timeline ul li:nth-child(odd) .content:before {
  right: -38px;
}
/* -------------------------
 ----- Media Queries ----- 
 ------------------------- */
@media screen and (max-width: 1020px) {
  .timeline ul li .content {
    width: 41vw;
  }
  .timeline ul li:nth-child(even) .content {
    left: calc(-41vw - 45px);
  }
}
@media screen and (max-width: 700px) {
  .timeline ul li {
    margin-left: 20px;
  }
  .timeline ul li .content {
    width: calc(100vw - 100px);
  }
  .timeline ul li .content h2 {
    text-align: initial;
  }
  .timeline ul li:nth-child(even) .content {
    left: 45px;
    background: #c4d6b0;
    background: -webkit-linear-gradient(-45deg, #c4d6b0, #c4d6b0);
    background: linear-gradient(-45deg, #c4d6b0, #c4d6b0);
  }
  .timeline ul li:nth-child(even) .content:before {
    left: -33px;
  }
}


</style>