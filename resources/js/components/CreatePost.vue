<template>
  <div>
    <button v-if="!editMode" @click="editMode = true">Edit</button>
    <button v-else @click="compile(); editMode = false;">Preview</button>

    <div v-show="editMode" id="editor-container">
      <div id="editor">
        <input v-model="title" placeholder="title" id="title" />
        <br />
        <textarea v-model="summary" placeholder="summary" id="summary"></textarea>
        <br />
        <textarea ref="contentTextArea" v-model="content" placeholder="blog post" id="blog-post"></textarea>
        <br />
        <div v-for="(path, name) in imageSrcMaps" v-bind:key="name" id="insert-images">
          <img :src="path"/>
          <button @click="insertImage(name)">Add Image {{ name }}</button>
        </div>
        <br />
        <div id="image-uploader">
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
            :maxImage="MAX_NUMBER_OF_IMAGES"/>
        </div>
      </div>
    </div>
      <div v-if="!editMode">
        <post :previewMode="true" :previewPost="previewPostObject"></post>
      </div>
      <div id="publish-button-container">
        <button @click="createPost" id="publish-button">Publish Post</button>
      </div>
    </div>
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
      headerImageName: "",
      summary: "",
      content: "",
      showPreview: false,
      images: [],
      IMAGE_TYPES_ACCEPTED:
        "image/gif,image/jpeg,image/png,image/bmp,image/jpg",
      MAX_NUMBER_OF_IMAGES: 30,
      formData: new FormData(),
      imageSrcMaps: {},
      editMode: true,
      previewPostObject: {
        title: "",
        headerImage: "",
        summary: "",
        content: ""
      }
    };
  },
  methods: {
    async createPost() {
      this.formData.append("title", this.title);
      this.formData.append("headerImageName", this.headerImageName);
      this.formData.append("summary", this.summary);
      this.formData.append("content", this.compileMarkdownContentToHTML());
      try {
        let response = await BlogPost.createPost(this.formData);
        if (response.status === 200) {
          this.$router.push({
            name: "post",
            params: { slug: response.data.slug }
          });
        }
      } catch (error) {
        console.log("Error:", error);
      }
    },
    uploadImageSuccess(formData, index, fileList) {
      let image = fileList[index];
      // set header image if not already set
      if (this.headerImage.length == 0) {
        this.headerImage = image.path;
        this.headerImageName = image.name;
      }
      Vue.set(this.imageSrcMaps, image.name, image.path);
      let file = formData.get("file");
      this.formData.append("images[]", file);
    },
    beforeRemove(index, done, fileList) {},
    editImage(formData, index, fileList) {},
    markIsHeader(index, fileList) {
      let image = fileList[0];
      this.headerImage = image.path;
      this.headerImageName = image.name;
    },
    insertImage(name) {
      let contentTextArea = this.$refs.contentTextArea;
      // get cursor's position:
      let startPos = contentTextArea.selectionStart;
      let endPos = contentTextArea.selectionEnd;
      let cursorPos = startPos;
      let tmpStr = contentTextArea.value;

      // insert:
      let id = "foobar";
      let insertText = `<img id="${id}" src="${name}"/>`;
      this.content =
        tmpStr.substring(0, startPos) +
        insertText +
        tmpStr.substring(endPos, tmpStr.length);

      // move cursor:
      setTimeout(() => {
        cursorPos += name.length;
        contentTextArea.selectionStart = contentTextArea.selectionEnd = cursorPos;
      }, 10);
    },
    compileMarkdownContentToHTML() {
      return marked(this.content);
    },
    compile() {
      let compiledContent = this.compileMarkdownContentToHTML();
      let imageNames = Object.keys(this.imageSrcMaps);
      if (imageNames.length != 0) {
        let re = new RegExp(imageNames.join("|"), "g");
        compiledContent = compiledContent.replace(re, matched => {
          return this.imageSrcMaps[matched];
        });
      }
      this.previewPostObject.title = this.title;
      this.previewPostObject.header_image_url = this.headerImage;
      this.previewPostObject.summary = this.summary;
      this.previewPostObject.content = compiledContent;
    }
  }
};
</script>
<style scoped>
#editor-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  align-content: space-between;
}
#editor {
  max-width: 800px;
}
#title {
  width: 100%;
  height: 28px;
  margin-bottom: 20px;
}
#summary {
  width: 100%;
  min-height: 50px;
  margin-bottom: 20px;
}
#blog-post {
  width: 100%;
  min-height: 300px;
  margin-bottom: 20px;
}
#insert-images {
  margin-bottom: 20px;
}
#insert-images img {
  width: 50px;
}
#image-uploader {
  margin-bottom: 20px;
}
#publish-button-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
#publish-button {
  padding: 10px 40px
}
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