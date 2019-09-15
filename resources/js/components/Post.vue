<template>
<div>
  <div v-if="loaded" id="post-container">
    <img id="header-image" :src="post.headerImage"/>
    <div id="post-title">{{ post.title }}</div>
    <div id="post-summary">{{ post.summary }}</div>
    <div id="post-content" v-html="compiledContent"></div>
  </div>
  <div v-else>
    loading...
  </div>
</div>
</template>
<script>
import BlogPost from '@/js/services/BlogPost.service.js';
import marked from 'marked';

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
      default: {}
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
      this.getPost(this.$route.params.id)
    } else {
      this.post = this.previewPost
    }
  },
  computed: {
    compiledContent() {
      return marked(this.post.content)
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
  #post-container {
    padding: 40px 0px ;
    display: flex;
    align-content: center;
    flex-direction: column;
    align-items: center;
  }
  header-image {
    max-width: 100%;
    height: auto;
  }
  #post-title {
    font-weight: bold;
    font-size: 30px;
  }
  #post-summary {
    font-style: italic;
  }
  #post-content {
    
  }
</style>