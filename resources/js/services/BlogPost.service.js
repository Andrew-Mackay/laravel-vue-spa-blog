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

  static getEditPost(slug) {
    return axios.get(`${blogPostURL}/edit/${slug}`);
  }

  static createPost(formData) {
    let headers = this.getAuthHeader();
    headers['content-type'] = 'multipart/form-data';
    return axios.post(blogPostURL, formData, {headers:headers});
  }

  static editPost(slug, formData) {
    let headers = this.getAuthHeader();
    headers['content-type'] = 'multipart/form-data';
    return axios.post(`${blogPostURL}/update/${slug}`, formData, {headers:headers});
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
