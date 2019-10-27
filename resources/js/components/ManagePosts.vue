<template>
    <div>
      <div>
        <button :disabled="!showingDrafts" @click="showingDrafts=false;">Published Posts ({{ numberOfPublishedPosts }})</button>
        <button :disabled="showingDrafts" @click="showingDrafts=true;">Draft Posts ( {{ numberOfDraftPosts }})</button>
      </div>
      <div v-if="loadedPublished" class="publishedPosts-container">
        <template v-for="post in postsToShow">
          <div class="post" v-bind:key="post.slug">
            <div>Title: "{{ post.title }}"</div>
            <div>Created: {{ post.created_at }}</div>
            <button @click="editPost(post.slug)">Edit</button>
            <template v-if="!showingDrafts">
              <button @click="deletePost(post.slug)">Delete</button>
            </template>
            <template v-else>
              <button @click="publishPost(post.slug)">Publish</button>
            </template>
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
      publishedPosts: [],
      draftPosts: [],
      loadedPublished: false,
      loadedDrafts: false,
      showingDrafts: false
    }
  },
  mounted() {
      this.getpublishedPosts();
      this.getDrafts();
  },
  computed: {
    numberOfPublishedPosts:function () {
      return this.publishedPosts.length;
    },
    numberOfDraftPosts: function () {
      return this.draftPosts.length;
    },
    postsToShow: function () {
      return this.showingDrafts ? this.draftPosts : this.publishedPosts;
    }
  },
  methods: {
    async getpublishedPosts() {
      try {
        let response = await BlogPost.getPosts();
        if(response.status === 200) {
          this.publishedPosts = response.data.posts;
        }
      } catch(error){
        console.log("Error:", error)
      }
      this.loadedPublished = true;
    },
    async getDrafts () {
      try {
        let response = await BlogPost.getDraftPosts();
        if(response.status === 200) {
          this.draftPosts = response.data.posts;
        }
      } catch(error){
        console.log("Error:", error)
      }
      this.loadedDrafts = true;
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
        this.publishedPosts = this.publishedPosts.filter(post => post.slug !== slug);
      } else {
        alert("Failed to delete post.");
      }
    },
    editPost(slug) {
      // todo redirect
    },
    //todo error handling 
    async publishPost(slug) {
      let confirmedPublish = confirm(`Are you sure you want to publish this post? [${slug}]`);
      if (!confirmedPublish || !this.showingDrafts) {
        return;
      }
      await BlogPost.publishDraft(slug);
      let publishedPost = this.draftPosts.filter(post => post.slug === slug)[0];
      this.draftPosts = this.draftPosts.filter(post => post.slug !== slug);
      this.publishedPosts.push(publishedPost);
    }
  }
}
</script>
<style scoped>
  .publishedPosts-container {
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
