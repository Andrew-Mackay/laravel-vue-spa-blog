<template>
  <div id="editor">
    <input v-model="title" placeholder="title" />
    <br />
    <textarea v-model="summary" placeholder="summary"></textarea>
    <br />
    <textarea v-model="content" placeholder="blog post"></textarea>
    <br />

    <vue-upload-multiple-image
      @upload-success="uploadImageSuccess"
      @before-remove="beforeRemove"
      @edit-image="editImage"
      @mark-is-primary="markIsHeader"
      :data-images="images"
      :dragText="'Drag and drop images here to upload'"
      :browseText="'or click browse'"
      :primaryText="'header image'"
      :markIsPrimaryText="'use as header image'"
      :popupText="'This image is set as your header image and will be displayed at the top of your blog post'"
      :dropText="'Drop image here to upload ...'"
      :accept="IMAGE_TYPES_ACCEPTED"
      :maxImage="MAX_NUMBER_OF_IMAGES"
    ></vue-upload-multiple-image>

    <button @click="createPost">Save</button>
    <button
      @click="showPreview = !showPreview"
      v-text="showPreview ? 'Hide Preview': 'Show Preview'"
    >Preview</button>
    <hr v-if="showPreview" />
    <post v-if="showPreview" :previewMode="true" :previewPost="previewPostObject"></post>
  </div>
</template>
<script>
import BlogPost from "@/js/services/BlogPost.service.js";
import marked from "marked";
import Post from "@/js/components/Post";
import VueUploadMultipleImage from "vue-upload-multiple-image";

export default {
  components: { Post, VueUploadMultipleImage },
  data() {
    return {
      title: "",
      headerImage: "",
      summary: "",
      content: "",
      showPreview: false,
      images: [],
      IMAGE_TYPES_ACCEPTED:
        "image/gif,image/jpeg,image/png,image/bmp,image/jpg",
      MAX_NUMBER_OF_IMAGES: 30
    };
  },
  computed: {
    previewPostObject() {
      return {
        title: this.title,
        headerImage: this.headerImage,
        summary: this.summary,
        content: this.compiledContent
      };
    },
    compiledContent() {
      return marked(this.content);
    }
  },
  methods: {
    async createPost() {
      let post = {
        title: this.title,
        summary: this.summary,
        content: this.compiledContent
      };
      try {
        let response = await BlogPost.createPost(post);
        if (response.status === 200) {
          this.$router.push({ name: "post", params: { id: response.data.id } });
        }
      } catch (error) {
        console.log("Error:", error);
      }
    },
    uploadImageSuccess() {},
    beforeRemove() {},
    editImage() {},
    markIsHeader() {}
  }
};
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