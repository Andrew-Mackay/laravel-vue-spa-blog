const blogPostURL = '/api/blog-post';
export default class BlogPost {
  static getAuthHeader() {
    let token = localStorage.getItem('user-token') || '';
    return {Authorization: 'Bearer ' + token};
  }
  static getPosts() {
    return axios.get(blogPostURL + '/index');
  }

  static getPost(slug) {
    return axios.get(blogPostURL + '/' + slug);
  }

  static createPost(formData) {
    let headers = this.getAuthHeader();
    headers['content-type'] = 'multipart/form-data';
    return axios.post(
      blogPostURL,
      formData,
      {headers:headers}
      );
  }

  static editPost(post) {
    // todo parse params
    return axios.put(
      blogPostURL,
      {}, 
      {headers:this.getAuthHeader()}
      );
  }

  static deletePost(id) {
    return axios.delete(
      blogPostURL, 
      { 
        params: { id: id },
        headers:this.getAuthHeader()}, 
      );
  }
}
