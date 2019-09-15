const blogPostURL = '/api/blog-post';
export default class BlogPost {
  static getAuthHeader() {
    let token = localStorage.getItem('user-token') || '';
    return {Authorization: 'Bearer ' + token};
  }
  static getPosts() {
    return axios.get(blogPostURL + '/index');
  }

  static getPost(id) {
    return axios.get(blogPostURL + '/' + id);
  }

  static createPost(post) {
    return axios.post(
      blogPostURL,
      {
        title: post.title,
        summary: post.summary,
        content: post.content
      },
      {headers:this.getAuthHeader()}
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
