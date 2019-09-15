<template>
  <div>
    Login: <br/>
    Email: <input v-model="email" placeholder="email"> <br/>
    Password: <input v-model="password" type=password> <br/>
    <button @click="login">Login</button>
  </div>
</template>
<script>
import Authentication from '@/js/services/Authentication.service.js';
import moment from 'moment';
export default {
  data() {
    return {
      email: "",
      password: ""
    }
  },
  methods: {
    async login() {
      try {
        let response = await Authentication.login(this.email, this.password);
        if(response.status === 200) {
          const token = response.data.access_token;
          localStorage.setItem('user-token', token);
          const timeUntilTokenExpiration = response.data.expires_in;
          const expires = moment.utc().add(timeUntilTokenExpiration, 'seconds');
          localStorage.setItem('user-token-expires', expires.format());
          window.location.replace('/post/new');
        }
      } catch(error){
        console.log("Error:", error)
      }
    }
  }
}
</script>
<style scoped>

</style>