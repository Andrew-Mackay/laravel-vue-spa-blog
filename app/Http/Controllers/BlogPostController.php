<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;

class BlogPostController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // todo paginate response
      // todo don't return content of post, only return preview information
      $posts = BlogPost::all();
      return response()->json(["posts" => $posts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // todo validate length (max) of content
      $validator = Validator::make(
        $request->all(),
        [
            "title" => "string | required",
            "summary" => "string | required",
            "content" => "string | required",
            "images" => "array | max:30",
            "images.*" => "image | mimes:gif,jpeg,png,bmp,jpg|max:20000"
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
      }

      $urls = [];
      foreach ($request->images as $image) {
        try {
          Cloudder::upload($image->getRealPath());   
          $cloudinaryURL = Cloudder::show(Cloudder::getPublicId());  
          $urls[] = $cloudinaryURL;    
        } catch (\Exception $exception) {
          \Log::error("Image upload failed");
          \Log::error($exception);
        }
      }
  
      $user = auth()->user();
      $blogPost = $user->blogPosts()->create([
        "title" => $request->title,
        "summary" => $request->summary,
        "content" => $request->content
      ]);

      return response()->json(["id" => $blogPost->id, "urls" => $urls]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost, int $postId)
    {
      // todo test fail condition
      // todo limit what is returned
      $post = BlogPost::findOrFail($postId);
      return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $blogPost)
    {
        //
    }
}
