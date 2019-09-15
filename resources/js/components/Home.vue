<template>
    <div>
      <div v-if="loaded">
        <div v-for="postPreview in postPreviews" v-bind:key="postPreview.id">
          <post-preview :preview=postPreview></post-preview>
          <hr/>
        </div>
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
