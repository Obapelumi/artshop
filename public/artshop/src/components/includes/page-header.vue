<template>
        <section>
          <div v-if="metaTags">
            <meta name="title" :content="metaTags.title">
            <meta name="referrer" content="unsafe-url">
            <meta name="description" :content="metaTags.description">
            <meta property="og:type" :content="metaTags.type" />
            <meta property="og:title" :content="metaTags.title" />
            <meta property="og:description" :content="metaTags.description" />
            <meta property="og:image" :content="metaTags.image" />
            <meta property="og:image:alt" :content="metaTags.title" />
            <meta name="twitter:description" :content="metaTags.image">
            <meta name="twitter:image:src" :content="metaTags.description">
          </div>
              <div class="banner-sub-page">
                <div class="container capitalize">
                  <title v-if="subPage">Artshop | {{page}} | {{subPage}}</title>
                  <title v-else>Artshop | {{page}}</title>
                  <div class="breadcrumbs">
                    <div class="breadcrumbs-list">
                      <div class="page"> <router-link to="/">Home</router-link></div>
                      <div class="page"><a href="#" @click.prevent="$router.push('/' + page)">{{page}}</a></div>
                      <div class="page" v-if="subPage">{{subPage}}</div>
                    </div>
                  </div>
                  <!-- <h2 class="sub-page-name">Shop</h2> -->
                </div>
              </div>
        </section>
</template>
<script>
export default {
  props: ['page', 'subPage', 'metaTags']
}
</script>

