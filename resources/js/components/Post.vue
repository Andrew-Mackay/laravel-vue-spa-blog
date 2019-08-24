<template>
  <div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      post: {
        id: "0",
        title: "Title",
        summary: "Summary",
        headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
        content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        createdAt: "24 August 2019 - 09:19",
        lastEdited: "24 August 2019 - 0920"
      }
    }
  },
  mounted() {
    console.log('Component mounted.')
  }
  
}
</script>
<style scoped>

</style>