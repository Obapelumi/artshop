<template>
                <div class="recent-news-item">
                  <div class="post-thumbnail customer-spotlight-review" :id="'review-' + review.id">
                  </div>
                  <div class="post-information">
                    <h3 class="post-title"><router-link :to="'/product/'+ review.product.slug">{{review.user.name}} on {{review.product.name}}</router-link></h3>
                    <div class="post-meta"><span class="post-date"><i class="fa fa-calendar"></i>{{review.created_at | moment("MMM Do, YYYY")}}</span><span class="post-count-comments"><router-link :to="'/vendors/'+ review.product.vendor.slug"><i class="fa fa-search"></i>View vendor</router-link></span></div>
                    <p class="post-excerpt">“{{review.review}}</p>
                  </div>
                </div>
</template>
<script>
export default {
  props: ['review'],
  mounted () {
      var $this = this;
      if (this.review.product.file.length > 0) {
        var image = $this.theme.imagePath(this.review.product.file[0].path);   
      }
      else {
          var image = "/images/logo/favicon.png";
      }

      $('#review-'+ $this.review.id).css("background-image", "url(" + image +")");
  }
}
</script>
<style>
    .customer-spotlight-review {
        background-size: cover;
        box-shadow: 1px 1px 2px 1px #eee;
    }
</style>


