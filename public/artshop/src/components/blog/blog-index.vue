<template>
<div class="has-header">
    <art-page-header :page="'Blog'"></art-page-header>
    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-9">
            <div class="post-item blog-list" v-for="post in displayedPosts" :key="post.id" v-if="posts.length > 0">
              <div class="entry-wrap">
                <div class="entry-thumbnail-wrap">
                  <div class="entry-thumbnail">
                    <a href="#" class="entry-thumbnail_overlay" @click.prevent="singlePost(post.slug)">
                      <img 
                        :src="theme.imagePath(post.file.path)" 
                        :alt="post.title" height="250" 
                        v-if="post.file"
                        class="attachment-shop_catalog size-shop_catalog wp-post-image"
                      />
                      <img 
                        src="/images/logo/logo-black.jpg"
                        :alt="post.title" 
                        v-else
                        class="attachment-shop_catalog size-shop_catalog wp-post-image"
                      />
                    </a>
                    <a href="#" class="prettyPhoto" @click.prevent="singlePost(post.slug)">
                      <i class="fa fa-arrows-alt"></i>
                    </a>
                  </div>
                  <div class="date-overlay">
                    <span class="day">{{post.created_at | moment("dddd, MM Do YYYY")}}</span>
                  </div>
                </div>
                <div class="entry-content-wrap">
                  <div class="entry-detail">
                    <h3 class="entry-title p-font capitalize">
                      <a href="#" @click.prevent="singlePost(post.slug)">{{post.title}}</a>
                    </h3>
                    <div class="entry-post-meta-wrap">
                      <ul class="entry-meta">
                        <li class="entry-meta-author"><i class="fa fa-pencil-square-o p-color"></i><a href="#">admin</a></li>
                        <li class="entry-meta-date"><i class="fa fa-clock-o p-color"></i><a href="#"> {{post.created_at | moment("MMM Do, YYYY")}}</a></li>
                        <li class="entry-meta-date"><i class="fa fa-pencil-square-o p-color"></i><a href="#"> {{post.user.name}}</a></li>                        
                        <li class="entry-meta-comment" v-if="post.comment.length > 0"><a href="#"><i class="fa fa-comments-o p-color"></i> {{post.comment.length}} Comments</a></li>
                      </ul>
                    </div>
                    <div class="entry-excerpt" style="margin-top: 20px;">
                      <p v-html="post.body.slice(0, 100) + '...'"></p>
                    </div><a href="#" class="btn-readmore" @click.prevent="singlePost(post.slug)"><span class="span-text">Read more</span></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <br> <br> <br>
              <div class="section">
                <div class="pagination">
                  <div class="pagination-list">
                    <div class="page" 
                      v-if="page != 1" 
                      @click="prev()">
                      <i class="fa fa-angle-double-left"> </i>
                    </div>
                    <div class="page" 
                      v-for="pageNumber in pages.slice(page-1, page+2)" 
                      :key="pageNumber"
                      :class="{activePage: pageNumber === page }"
                      @click="paginate(pageNumber)">
                      {{pageNumber}}
                    </div>
                    <div class="page" 
                      v-if="page < pages.length" 
                      @click="next()">
                      <i class="fa fa-angle-double-right"> </i>
                    </div>
                  </div>
                </div>
              </div>
            <art-loading v-if="posts.length <= 0"></art-loading>
          </div>
          <div class="col-md-3">
            <div class="sidebar-blog">
              <!-- <aside class="sidebar-search">
                <form @submit.prevent="search">
                  <input type="text" v-model="searchValue" placeholder="Search Here...">
                  <a href="#" @click.prevent="search"><i class="fa fa-search"></i></a>
                </form>
              </aside>
              <aside class="categorie">
                <h4>Categories</h4>
                <ul class="categories">
                  <li v-for="category in categories" :key="category.id"><a href="#">{{category.name}}</a></li>
                  
                </ul>
              </aside> -->
              <aside class="recent-post post-sidebar">
                <h4>Recent posts</h4>
                <ul class="recent-posts">
                  <li v-for="post in posts.slice(0, 4)" :key="post.id">
                    <a href="#" @click.prevent="singlePost(post.slug)"> 
                      <img :src="theme.imagePath(post.file.path)"
                        style="height:70px; width:70px;"  
                        width="70" height="70" 
                        v-if="post.file">
                      <img src="/images/logo/logo-black.jpg"
                        style="height:70px; width:70px;" 
                        width="70" height="70" 
                        v-else>
                    </a>
                    <div class="posts-thumbnail-content">
                      <h4><a href="#" @click.prevent="singlePost(post.slug)">{{post.title}}</a></h4>
                      <div class="posts-thumbnail-meta"><span class="author vcard">{{post.user.name}}</span><span>{{post.created_at | moment("MMM DD, YYYY")}}</span><span class="comment-count"><i class="fa fa-comments-o"></i><a href="#">{{post.comment.length}}</a></span></div>
                    </div>
                  </li>
                </ul>
              </aside>
              <aside class="tagcloud">
                <h4>tags</h4>
                <ul class="tagcloud">
                  <li v-for="tag in tags" :key="tag.id"><a href="#">{{tag.name}}</a></li>
                </ul>
              </aside>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default{
  props: ['posts', 'categories', 'products'],
  data () {
    return {
      tags: [],
      searchValue: null,
      displayedPosts: this.posts,
      pages: [],
      page: 1,
      perPage: 6,
    }
  },
  methods: {
    singlePost(slug) {
      this.$router.push('blog/' + slug);
    },
    paginate (page) {
      this.page = page;
      let perPage = this.perPage;
      var from = (page * perPage) - perPage;
      var to = (page * perPage);
      this.displayedPosts = this.posts.slice(from, to);
      $('html, body').animate({
        scrollTop: 400,
        queue: false
      }, 500);
    },
    setPages () {
      let perPage = this.perPage;
      let numberOfPages = Math.ceil(this.posts.length / perPage);
      for (let index = 1; index <= numberOfPages; index++) {
        this.pages.push(index);
      }
    },
    next () {
      this.page++;
      this.paginate(this.page);
    },
    prev () {
      this.page--;
      this.paginate(this.page);
    },
    search () {
      var $this = this;
      $this.theme.submitting();
      $this.axios.post('search/blog', {search: $this.searchValue})
        .then(response => {
          $this.theme.submitted();
          $this.displayedPosts = response.data.result;
        })
        .catch(response => {
          $this.theme.submitted();
        });
    }
  },
  watch: {
    posts () {
      this.paginate(1);
    }
  },
  created () {
    var $this = this;
    this.$emit('updatePosts');
    this.setPages();
    this.paginate(1);
    this.axios.get('blog-tags')
      .then(response => {
        $this.tags = response.data.tags;
      });
  }
}
</script>

<style>
.activePage {
  border-top: 1px solid #808080;
  border-bottom: 1px solid #808080;
}
</style>