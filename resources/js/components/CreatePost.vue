<template>
  <div>
    <div id="preview-edit-button-container" class="space-bottom">
    <button v-if="!editMode" @click="editMode = true">Edit</button>
    <button v-else @click="compile(); editMode = false;">Preview</button>
    </div>
    <div v-show="editMode" id="editor-container">
      <div id="editor">
        <div>
          <input v-model="title" placeholder="title" id="title" class="space-bottom"/>
        </div>
        <div>
          <textarea v-model="summary" placeholder="summary" id="summary" class="space-bottom"></textarea>
        </div>
        <textarea ref="contentTextArea" v-model="content" placeholder="blog post" id="blog-post" class="space-bottom"></textarea>
        <div v-if="Object.keys(imageSrcMaps).length > 0">
          Click on an image to insert it into the blog post:
        </div>
        <div id=insert-images-container class="space-bottom">
          <div v-for="(path, name) in imageSrcMaps" v-bind:key="name">
            <button @click="insertImage(name)"><img :src="path"/></button>
          </div>
        </div>
        <br />
        <div class="space-bottom">
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
        <button
        @click="createPost(true)"
        class="save-button"
        :disabled="isSavingPost"
        v-text="isSavingPost ? 'Saving Draft' : 'Save as Draft'"/>
        <button
        @click="createPost(false)"
        class="save-button"
        :disabled="isSavingPost"
        v-text="isSavingPost ? 'Publishing Post' : 'Publish Post'"/>
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
      },
      isSavingPost: false
    };
  },
  methods: {
    async createPost(saveAsDraft) {
      if (!saveAsDraft) {
        let confirmed = confirm(`Are you sure you want to publish this blog post? [${this.title}]`)
        if (!confirmed) {
          return
        }
      }
      this.isSavingPost = true;
      this.formData.append("title", this.title);
      this.formData.append("headerImageName", this.headerImageName);
      this.formData.append("summary", this.summary);
      this.formData.append("content", this.compileMarkdownContentToHTML());
      this.formData.append('saveAsDraft', saveAsDraft ? 1 : 0);
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
      this.isSavingPost = false;
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
.space-bottom {
  margin-bottom: 20px;
}
#preview-edit-button-container {
  display: flex;
  flex-direction:column;
  align-items: center;
}
#preview-edit-button-container button {
  padding: 10px 40px;
}
#editor-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  align-content: space-between;
  padding: 0 10px
}
#editor {
  max-width: 800px;
  width: 100%;
}
#title {
  width: 100%;
  height: 28px;
  border: 1px solid gray;
  border-radius: 3px; 
}
#summary {
  width: 100%;
  min-height: 50px;
  border: 1px solid gray;
  border-radius: 3px; 
}
#blog-post {
  width: 100%;
  min-height: 300px;
  border: 1px solid gray;
  border-radius: 3px; 
}
#insert-images-container {
  display: flex;
  flex-direction: row;
  align-content: flex-start;
  flex-wrap: wrap;
}
#insert-images-container img {
  width: 100%;
}
#insert-images-container button {
  width: 64px;
  height: 64px;
  margin-right: 5px;
}
#publish-button-container {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.save-button {
  padding: 10px 40px;
  width: 200px;
  margin-bottom: 10px;
}
>>>.image-container {
  width: 100%;
  min-height: 200px;
  border: 1px solid gray;
}
>>>.image-list-container {
  max-width: 100%;
}
>>>.image-list-item {
  width: 64px !important;
  height: 64px !important;
}
>>>.show-image {

}
</style>