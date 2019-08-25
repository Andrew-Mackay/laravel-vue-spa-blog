<template>
  <div>
    <div>
      isLoggedIn? {{isLoggedIn}}
    </div>
    <router-link tag="li" to="/">Home</router-link>
    <router-link tag="li" to="/about">About</router-link>
    <router-link v-if="isLoggedIn" tag="li" to="/post/new">New Post</router-link>
    <router-view></router-view>
  </div>

</template>
<script>
export default {
  data() {
    return {
      // todo add check for is expired
      isLoggedIn: localStorage.getItem('user-token') != null
    }
  }
}
</script>