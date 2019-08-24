<template>
  <div id="post-container">
    <img id="header-image" :src="post.headerImage"/>
    <div id="post-title">{{ post.title }}</div>
    <div id="post-summary">{{ post.summary }}</div>
    <div v-if="!previewMode" id="post-content">{{ post.content }} </div>
    <div v-else v-html="post.content"></div>
  </div>
</template>
<script>
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
      post: {
        id: "0",
        title: "Title",
        summary: "Summary",
        headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
        content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        createdAt: "24 August 2019 - 09:19",
        lastEdited: "24 August 2019 - 0920"
      }
    }
  },
  mounted() {
    if(!this.previewMode) {
      console.log('Component mounted.', this.$route.params.id)
      this.post.id = this.$route.params.id;
    } else {
      this.post = this.previewPost
    }
  },
  watch: { 
    previewPost (newPost) {
      this.post = newPost
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