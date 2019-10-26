<template>
  <div class="app">
    <div class="navbar">
      <div>
        <router-link tag="li" to="/">{{ appName }}</router-link>
      </div>
      <div>
        <router-link tag="li" to="/about">About</router-link>
      </div>
      <div>
        <router-link v-if="isLoggedIn && !tokenExpired" tag="li" to="/post/new">New Post</router-link>
      </div>
    </div>
      <router-view></router-view>
  </div>
</template>
<script>
import moment from 'moment';
export default {
  data() {
    return {
      appName: appName,
      isLoggedIn: localStorage.getItem('user-token') != null,
    }
  },
  computed: {
    // Check if the current logged in user's JWT has expired
    tokenExpired() {
      let userTokenExpires = localStorage.getItem('user-token-expires');
      return moment(userTokenExpires).isBefore(moment.utc());
    }
  }
}
</script>
<style scoped>
  .app {
    background-color: #fff;
  }
  .navbar {
    display: flex;
    list-style-type: none;
    margin-bottom: 20px;
  }
  .navbar div {
    margin-right: 20px;
    text-decoration: underline;
  }
  .navbar div :hover {
    color: rgb(201, 38, 9);
  }
</style>