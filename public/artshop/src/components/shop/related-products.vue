<template>
      <section class="relative-single-page" v-if="products.length > 1">
        <div class="container">
          <h2>Related Products</h2>
          <div class="related-logo">
            <div data-number="4" data-margin="10" data-loop="yes" data-navcontrol="yes" class="sofani-owl-carousel">
             <art-shop-product v-for="product in products" :product="product" :key="product.id"></art-shop-product>
            </div>
          </div>
        </div>
      </section> 
</template>
<script>
export default {
  props: ['products'],
  mounted () {
    this.theme.carousel();
  }
}
</script>
<style>

</style>
