<template>
<div id="example-wrapper" class="blog blog-detail has-header" v-if="post">
        <art-page-header :page="'blog'" :subPage="post.title" :metaTags="metaTags" ></art-page-header>
        <section>
          <div class="container section">
            <article>
              <div class="entry-post-meta-wrap">
                <div class="single-post-entry-meta">
                  <div class="entry-meta-info">
                    <h2 class="entry-title"> {{post.title}}</h2>
                    <ul>
                      <li class="entry-meta-date"><i class="fa fa-calendar p-color"></i>{{post.published | moment("MMM Do, YYYY")}}</li>
                      <li class="entry-meta-author"><i class="fa fa-pencil-square-o p-color"></i><a href="#">{{post.user.name}}</a></li>
                      <li class="entry-meta-comment">
                        <a href="#" v-if="post.comment.length > 0"><i class="fa fa-comments-o p-color"></i> {{post.comment.length}} Comments</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="entry-thumbnail-wrap" v-if="post.file">
                <div class="entry-thumbnail">
                  <a class="entry-thumbnail_overlay">
                    <img :src="theme.imagePath(post.file.path)" 
                      :alt="post.title" 
                      style="max-width:100%; max-height:640px;" 
                      class="img-responsive"
                    />
                  </a>
                </div>
              </div>
              <div class="entry-content-wrap">
                <div id="blog-content" class="entry-content clearfix" v-html="post.body"></div>
                <div class="social-share-wrap">
                  <ul class="social-share">
                    <li>
                      <a :href="'http://www.facebook.com/sharer.php?u=' + theme.config.SITE_URL + '/blog/' + post.slug" target="_blank"><i class="fa fa-facebook"></i>facebook</a>
                    </li>
                    <li>
                      <a :href="'https://twitter.com/share?url=' + theme.config.SITE_URL + '/blog/' + post.slug + '&amp;text=' + post.title" target="_blank"><i class="fa fa-twitter"></i>twitter</a>
                    </li>
                    <li><a :href="'https://plus.google.com/share?url=' + theme.config.SITE_URL + '/blog/' + post.slug" target="_blank"><i class="fa fa-google-plus"></i>google</a></li>
                    <!-- <li><a href="#"><i class="fa fa-linkedin"></i>linkedi</a></li>
                    <li><a href="#"><i class="fa fa-tumblr"></i>tumblr</a></li>
                    <li><a href="#"><i class="fa fa-pinterest"></i>pinterest</a></li> -->
                  </ul>
                </div>
              </div>
            </article>
          </div>
        </section>
        <!-- <div class="section">
          <nav class="post-navigation">
            <div class="nav-links container">
              <div class="nav-previous"><a href="#" rel="prev">
                  <div class="post-navigation-left"><i class="post-navigation-icon fa fa-angle-double-left"></i></div>
                  <div class="post-navigation-content">
                    <div class="post-navigation-title">That has no annoying consequences</div>
                  </div></a></div>
              <div class="nav-next"><a href="#" rel="next">
                  <div class="post-navigation-content">
                    <div class="post-navigation-title">Duis aute irure dolor in reprehenderit</div>
                  </div>
                  <div class="post-navigation-right"><i class="post-navigation-icon fa fa-angle-double-right"></i></div></a></div>
            </div>
          </nav>
        </div> -->
        <div class="section" v-if="post.comment">
          <div id="comments" class="entry-comments">
            <div class="container">
              <h3 class="comments-title" v-if="post.comment.length > 0"><span> There are {{post.comment.length}} comments </span></h3>
              <div class="entry-comments-list col-lg-8 col-md-8">
                <ol class="commentlist clearfix">
                  <li id="li-comment-1" class="comment by user comment-author-admin bypostauthor even thread-even depth-1" v-for="comment in comments" :key="comment.id">
                    <div id="comment-1" class="comment-body clearfix">
                      <div class="comment-text">
                        <div class="author">
                          <span class="author-name">{{comment.user_name}}</span>
                          <span class="comment-meta-date">
                            <span class="time"> {{comment.created_at | moment("from")}}</span>
                          </span>
                        </div>
                        <div class="text">
                          <p>{{comment.comment}}</p>
                        </div>
                        <div class="comment-meta">
                          <a rel="nofollow" href="#respond" @click="replier(comment)"  class="comment-reply-link">
                            <i class="fa fa-reply"></i> Reply
                          </a>
                        </div>
                      </div>
                    </div>
                    <ul class="children">
                      <li 
                        id="li-comment-2" 
                        class="comment odd alt depth-2" 
                        v-for="reply in replies(comment)" 
                        :key="reply.id"
                      >
                        <div id="comment-2" class="comment-body clearfix">
                          <div class="comment-text">
                            <div class="author"><span class="author-name">{{reply.user_name}}</span><span class="comment-meta-date"><span class="time"> {{reply.created_at | moment("from")}}</span></span></div>
                            <div class="text">
                              <p>{{reply.comment}}</p>
                            </div>
                            <div class="comment-meta">
                              <a rel="nofollow" href="#respond" class="comment-reply-link" @click="replier(comment)">
                                <i class="fa fa-reply"></i> Reply
                              </a>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </li>
                </ol>
              </div>
              <div class="entry-comments-form">
                <div id="respond-wrap" class="col-lg-7 col-md-8">
                  <div id="respond" class="comment-respond">
                    <h3 id="reply-title" class="comment-reply-title">
                      <span v-if="replyTo">Reply {{replyTo}}'s comment</span>
                      <span v-else>Leave your thought</span>
                    </h3>
                    <form id="commentform" class="comment-form" @submit.prevent="postComment">
                      <div class="comment-fields-wrap">
                        <div class="comment-fields-inner clearfix">
                          <div class="yolo-row">
                            <!-- <p class="comment-form-author yolo-sm-6">
                              <input id="author" name="author" placeholder="Enter Your Name*" value="" size="30" aria-required="true" type="text" class="form-control" v-model="commentObj.name"/>
                            </p>
                            <p class="comment-form-email yolo-sm-6">
                              <input id="email" name="email" placeholder="Enter Your Email*" value="" size="30" aria-required="true" type="text" class="form-control" v-model="commentObj.email"/>
                            </p> -->
                            <div class="yolo-sm-12">
                              <p class="comment-form-comment">
                                <textarea id="comment" placeholder="Enter Your Comment" name="comment" cols="40" rows="4" aria-required="true" class="form-control" v-model="commentObj.comment" required></textarea>
                              </p>
                            </div>
                          </div>
                          <p class="form-submit">
                            <input id="submit" name="submit" value="Post Comments" type="submit" class="submit"/>
                          </p>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</template>

<script>
export default{
  props: ['posts', 'categories', 'products'],
  data () {
    return {
      post: null,
      comments: [],
      commentObj: {
        blog_id: '',
        name: '',
        comment: '',
        reply: 0
      },
      replyTo: '',
      metaTags: null,
    }
  },
  methods: {
    postComment () {
      this.admin.postComment(this);
    },
    setPost () {
      this.post = this.admin.singlePost(this);
      this.comments = this.admin.singlePost(this).comment.filter(comment => comment.reply <= 0);
      this.setMetaTags();
    },
    replies (comment) {
      return this.post.comment.filter(reply => reply.reply === comment.id);
    },
    replier (comment) {
      this.commentObj.reply = comment.id;
      this.replyTo = comment.user_name;
    },
    setMetaTags() {
      var $this = this;
      var description = $this.post.body.slice(0, 100).split('>')[1].replace("&nbsp;", " ") + '...';
      $this.metaTags = {
        type: 'article',
        title: $this.post.title + ' by ' + $this.post.user.name,
        description: description,
      }

      if ($this.post.file) {
        $this.metaTags.image = $this.theme.imagePath($this.post.file.path);
      }
    }
  },
  watch: {
    posts () {
      this.setPost();
    }
  },
  created () {
    var $this = this;
    this.$emit('updatePosts');
    if (this.posts) {
      this.setPost();
    }
  }
}
</script>

<style>

</style>