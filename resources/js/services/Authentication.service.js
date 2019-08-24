export default class Authentication {
  /**
   * Log in a user
   * @param user_email
   * @param user_password
   * @returns {*|AxiosPromise}
   */
  static login(user_email, user_password) {
      return axios.post('/api/auth/login', { email: user_email, password: user_password });
  }

  /**
   * Logout a user
   * @returns {*|AxiosPromise}
   */
  static logout() {
    return axios.post('/api/auth/logout', {});
  }
}
