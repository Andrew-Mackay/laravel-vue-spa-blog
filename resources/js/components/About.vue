<template>
<div id="container">
  <div id="about-container">
    <div id="title">
      What this Blog is About:
    </div>
    <div id="about-content">
      Add description of what this blog is about here.
    </div>
  </div>
</div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
  #container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  #about-container {
    max-width: 700px;
    width: 80%;
    padding: 40px 0px ;
    display: flex;
    align-content: center;
    flex-direction: column;
    align-items: center;
  }
  #title {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 20px;
  }
  #about-content {
    margin: 16px 0;
    line-height: 1.6;
    font-size: 1.2rem;
  }
</style>
