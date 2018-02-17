<template>
  <section v-if="displayedReviews.length > 2">
          <div class="recent-news-home-blog">
            <div class="recent-news-container">
                <div class="sperator text-center">
                <p>CUSTOMER SPOTLIGHT</p>
                <div class="sperator-bottom"><img src="/images/demo/sperator.png" alt="spertor"/></div>
                </div>
              <div id="recent-news-list" class="slick-slider">
                <art-review v-for="review in displayedReviews" :key="review.id" :review="review"></art-review>
              </div>
            </div>
          </div>
        </section>
</template>

<script>
import ReviewDisplay from  './review-display.vue';
export default {
  props: ['products', 'vendors', 'reviews'],
  data () {
      return {

      }
  },
  computed: {
      displayedReviews () {
          var $this = this;
          let reviews = this.reviews.filter(review => review.platform == 'spotlight');
          reviews.forEach(review => {
              review.product = $this.products.filter(product => product.id == review.product.id)[0];
          });
          return reviews;
      }
  },
  mounted () {
      this.theme.customerSpotLight();
  },
  methods: {

  },
  components: {
      'art-review': ReviewDisplay
  }
}
</script>
<style>

</style>


