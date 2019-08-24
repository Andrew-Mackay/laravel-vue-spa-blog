<template>
    <div>
      <div v-for="postPreview in postPreviews" v-bind:key="postPreview.id">
        <post-preview :preview=postPreview></post-preview>
        <hr/>
      </div>
    </div>
</template>

<script>
import PostPreview from '@/js/components/PostPreview.vue'
export default {
  components: {PostPreview},
  data() {
    return {
      postPreviews: [
        {
          id: 0,
          title: "Test 1",
          headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
          summary: "test 1 summary"
        },
        {
          id: 1,
          title: "Test 2",
          headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
          summary: "test 2 summary"
        },
        {
          id: 2,
          title: "Test 3",
          headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
          summary: "test 3 summary"
        },
        {
          id: 3,
          title: "Test 4",
          headerImage: "https://assets.pernod-ricard.com/nz/media_images/test.jpg",
          summary: "test 4 summary"
        },
      ]
    }
  },
  mounted() {
      console.log('Component mounted.')
  }
}
</script>
