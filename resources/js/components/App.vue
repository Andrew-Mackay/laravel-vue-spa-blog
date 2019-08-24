<template>
  <div>
    <router-link tag="li" to="/">Home</router-link>
    <router-link tag="li" to="/about">About</router-link>
    <router-link tag="li" to="/post/new">New Post</router-link>
    <router-view></router-view>
  </div>

</template>
<script>
export default {
}
</script>