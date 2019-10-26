<template>
    <div>
      <div v-if="loaded" class="post-previews-container">
          <post-preview 
            v-for="postPreview in postPreviews"
            v-bind:key="postPreview.slug" 
            :preview=postPreview
            class="post-preview-spacer">
          </post-preview>
      </div>
      <div v-else>
        loading...
      </div>
    </div>
</template>

<script>
import BlogPost from '@/js/services/BlogPost.service.js';
import PostPreview from '@/js/components/PostPreview.vue';

export default {
  components: {PostPreview},
  data() {
    return {
      postPreviews: [],
      loaded: false
    }
  },
  mounted() {
      this.getPostPreviews();
  },
  methods: {
    async getPostPreviews() {
      try {
        let response = await BlogPost.getPosts();
        if(response.status === 200) {
          this.postPreviews = response.data.posts;
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
.post-previews-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.post-preview-spacer {
  margin-bottom: 30px;
}
</style>
