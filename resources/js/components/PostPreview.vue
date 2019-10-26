<template>
    <router-link :to=postLink id="router-link-to-post">
      <div class="post-preview">
        <img :src="preview.header_image_url"/>
        <div id="preview-title">{{ preview.title }}</div>
        <div id="preview-summary">{{ preview.summary }}</div>
      </div>
    </router-link>
</template>
<script>
export default {
  props: {
    preview: {
      type: Object,
      required: true
    }
  },
  computed: {
    postLink() {
      return {path: "/post/" + this.preview.slug}
    }
  }
}
</script>
<style scoped>
  #router-link-to-post {
    text-decoration: none;
    max-width: 700px;
    width: 60%;  
  }
  .post-preview {
    color: black;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    border: 1px solid rgba(0,0,0,.09);
    border-radius: 5px;
    padding: 10% 20%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  img {
    max-width: 100%;
    height: auto;
    margin-bottom: 26px;
    border-radius: 10% 20%;
  }
  #preview-title {
    font-weight: bold;
    font-size: 2rem;
    margin-bottom: 5px;
  }
  #preview-summary {
    font-style: italic;
    font-size: 1.2rem;
  }
  @media only screen and (max-width: 600px) {
    #router-link-to-post {
      width: 80%;
    }
    .post-preview {
      padding: 8% 16%;
    }
  }
  @media only screen and (max-width: 400px) {
    #router-link-to-post {
      width: 90%;
    }
    .post-preview {
      padding: 6% 6%;
    }
  }

</style>