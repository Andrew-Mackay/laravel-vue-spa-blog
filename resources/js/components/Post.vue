<template>
<div id="container">
  <div v-if="loaded || previewMode" id="post-container">
    <img id="header-image" :src="post.header_image_url"/>
    <div id="post-title">{{ post.title }}</div>
    <div id="post-summary">{{ post.summary }}</div>
    <div id="post-content" v-html="post.content"></div>
  </div>
  <div v-else>
    loading...
  </div>
</div>
</template>
<script>
import BlogPost from '@/js/services/BlogPost.service.js';

export default {
  props: {
    previewMode: {
      type: Boolean,
      required: false,
      default: false
    },
    previewPost: {
      type: Object,
      required: false,
      default: () => ({})
    }
  },
  data() {
    return {
      post: {},
      loaded: false
    }
  },
  mounted() {
    if(!this.previewMode) {
      this.getPost(this.$route.params.slug)
    } else {
      this.post = this.previewPost
    }
  },
  watch: {
    previewPost (newPost) {
      this.post = newPost
    }
  },
  methods: {
    async getPost(id) {
      try {
        let response = await BlogPost.getPost(id);
        if(response.status === 200) {
          this.post = response.data;
        }
      }catch(error){
        console.log("Error:", error)
      }
      this.loaded = true;
    }
  }
}
</script>
<style scoped>
  #container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  #post-container {
    max-width: 700px;
    width: 80%;
    padding: 40px 0px ;
    display: flex;
    align-content: center;
    flex-direction: column;
    align-items: center;
  }
  #header-image {
    width: 100%;
    border-radius: 20px;
  }
  #post-title {
    font-weight: bold;
    font-size: 4.5rem;
    margin: 16px 0;
  }
  #post-summary {
    font-style: italic;
    font-size: 1.5rem;
  }
  #post-content {
    display: flex;
    flex-direction: column;
    margin: 16px 0;
    line-height: 1.6;
    font-size: 1.2rem;
  }
  #post-content >>> img{
    width: 70%;
    align-self: center;
    margin: 16px 0;
    border-radius: 20px;
  }
</style>
