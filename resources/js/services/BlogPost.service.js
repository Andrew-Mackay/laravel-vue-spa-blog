const blogPostURL = '/api/blog-post';
const draftsURL = `${blogPostURL}/drafts`;

export default class BlogPost {
  static getAuthHeader() {
    let token = localStorage.getItem('user-token') || '';
    return {Authorization: `Bearer ${token}`};
  }

  static getPosts() {
    return axios.get(`${blogPostURL}/index`);
  }

  static getPost(slug) {
    return axios.get(`${blogPostURL}/${slug}`);
  }

  static createPost(formData) {
    let headers = this.getAuthHeader();
    headers['content-type'] = 'multipart/form-data';
    return axios.post(blogPostURL, formData, {headers:headers});
  }

  static editPost(post) {
    // todo parse params
    return axios.put(blogPostURL, {}, {headers:this.getAuthHeader()});
  }

  static deletePost(slug) {
    return axios.delete(`${blogPostURL}/${slug}`, {headers:this.getAuthHeader()});
  }

  static getDraftPosts() {
    return axios.get(`${draftsURL}/index`, {headers: this.getAuthHeader()});
  }

  static publishDraft(slug) {
    return axios.put(`${draftsURL}/${slug}`, {headers: this.getAuthHeader()});
  }
}
