<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

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
      // todo validate length
      $validator = Validator::make(
        $request->all(),
        [
            "title" => "string | required",
            "summary" => "string | required",
            "content" => "string | required",
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
      }

      $user = auth()->user();
      $blogPost = $user->blogPosts()->create([
        "title" => $request->title,
        "summary" => $request->summary,
        "content" => $request->content
      ]);

      return response()->json(["id" => $blogPost->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost)
    {
        //
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
