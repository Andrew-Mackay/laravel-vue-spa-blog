<template>
  <div id="preview-container">
    <img :src="preview.headerImage"/>
    <div id="preview-title">{{ preview.title }}</div>
    <div id="preview-summary">{{ preview.summary }}</div>
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
}
</script>
<style scoped>
  #preview-container {
    padding: 40px 0px ;
    display: flex;
    align-content: center;
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