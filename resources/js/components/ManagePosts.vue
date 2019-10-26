<template>
    <div>
      <div v-if="loaded" class="posts-container">
        <template v-for="post in posts">
          <div class="post" v-bind:key="post.slug">
            <div>Title: "{{ post.title }}"</div>
            <div>Created: {{ post.created_at }}</div>
            <!-- <button>Edit</button> -->
            <button @click="deletePost(post.slug)">Delete</button>
          </div>
        </template>
      </div>
      <div v-else>
        loading...
      </div>
    </div>
</template>

<script>
import BlogPost from '@/js/services/BlogPost.service.js';

export default {
  data() {
    return {
      posts: [],
      loaded: false
    }
  },
  mounted() {
      this.getPosts();
  },
  methods: {
    async getPosts() {
      try {
        let response = await BlogPost.getPosts();
        if(response.status === 200) {
          this.posts = response.data.posts;
        }
      } catch(error){
        console.log("Error:", error)
      }
      this.loaded = true;
    },
    async deletePost(slug) {
      let confirmedDelete = confirm(`Are you sure you want to delete this post? [${slug}]`);
      if (!confirmedDelete) {
        return;
      }
      let deletedSuccessfully = false;
      try {
        let response = await BlogPost.deletePost(slug);
        if(response.status === 200) {
          deletedSuccessfully = response.data.success;
        }
      } catch(error){
        console.log("Error:", error)
      }
      if (deletedSuccessfully) {
        // remove post from list
        this.posts = this.posts.filter(post => post.slug !== slug);
      } else {
        alert("Failed to delete post.");
      }
    }
  }
}
</script>
<style scoped>
  .posts-container {
    display: flex;
    flex-direction: column;
    margin-left: 20px;
  }
  .post {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }
  .post div {
    margin-right: 10px;
  }
  .post button {
    margin-right: 10px;
  }
</style>
