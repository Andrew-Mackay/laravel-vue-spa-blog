<template>
<div id="editor">
  <input v-model="title" placeholder="title"> <br/>
  <textarea v-model="summary" placeholder="summary"></textarea><br/>
  <textarea v-model="content" placeholder="blog post"></textarea> <br/>
  <button @click="createPost">Save</button>
  <button @click="showPreview = !showPreview" v-text="showPreview ? 'Hide Preview': 'Show Preview'">Preview</button>
  <hr v-if=showPreview />
  <post v-if=showPreview :previewMode="true" :previewPost="previewPostObject"></post>
</div>
</template>
<script>
import BlogPost from '@/js/services/BlogPost.service.js';
import marked from 'marked';
import Post from '@/js/components/Post';

export default {
  components: {Post},
  data() {
    return {
      title: "",
      headerImage: "",
      summary: "",
      content: "",

      showPreview: false
    }
  },
  computed: {
    compiledMarkdown() {
      return marked(this.content)
    },
    previewPostObject() {
      return {
        "title": this.title,
        "headerImage": this.headerImage,
        "summary": this.summary,
        "content": this.compiledMarkdown
      }
    }
  },
  methods: {
    async createPost() {
      let post = {
        title: this.title,
        summary: this.summary,
        content: this.content
      }
      try {
        response = await BlogPost.createPost(post);
        if(response.status === 200) {
          router.push({name: 'post', params: {id: response.data.id}});
        }
      }catch(error){
        console.log("Error:", error)
      }
    }
  }
}
</script>
<style scoped>
/* html, body, #editor {
  margin: 0;
  height: 100%;
  font-family: 'Helvetica Neue', Arial, sans-serif;
  color: #333;
} */

/* textarea, #editor div {
  display: inline-block;
  width: 49%;
  height: 100%;
  vertical-align: top;
  box-sizing: border-box;
  padding: 0 20px;
} */

/* textarea {
  border: none;
  border-right: 1px solid #ccc;
  resize: none;
  outline: none;
  background-color: #f6f6f6;
  font-size: 14px;
  font-family: 'Monaco', courier, monospace;
  padding: 20px;
} */

/* code {
  color: #f66;
} */
</style>