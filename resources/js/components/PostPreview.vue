<template>
  <div class="preview-container">
    <router-link :to=postLink>
      <div class="preview-box">
        <img :src="preview.header_image_url"/>
        <div id="preview-title">{{ preview.title }}</div>
        <div id="preview-summary">{{ preview.summary }}</div>
      </div>
    </router-link>
  </div>
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
  .preview-container {
    display: flex;
    align-content: center;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
  }
  .preview-box {
    width: 40vw;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    border: 1px solid rgba(0,0,0,.09);
    border-radius: 5px;
    padding: 40px 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  img {
    max-width: 100%;
    height: auto;
  }
  #preview-title {
    font-weight: bold;
    font-size: 30px;
  }
  #preview-summary {
    font-style: italic;
  }

</style>