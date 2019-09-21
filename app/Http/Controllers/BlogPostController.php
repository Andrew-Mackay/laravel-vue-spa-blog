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
  const CLOUDINARY_UPLOAD_FOLDER = "k_blog/";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      // todo paginate response
      $posts = BlogPost::select("slug", "title", "summary", "header_image_url", "created_at")->orderBy('created_at', 'desc')->get();
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
            "images.*" => "image | mimes:gif,jpeg,png,bmp,jpg|max:20000",
            "headerImageName" => "nullable | string",
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
      }

      $content = $request->content;
      $headerImageUrl = "";
      if($request->images) {
        $urlMap = [];
        // todo check slug is unique?
        $uploadFolder = self::CLOUDINARY_UPLOAD_FOLDER . str_slug($request->title, "-");
        $uploadOptions = [
          "folder" => $uploadFolder
        ];
        foreach ($request->images as $image) {
          try {
            $clientImageName = $image->getClientOriginalName();
            Cloudder::upload($image->getRealPath(), null, $uploadOptions);   
            $cloudinaryURL = Cloudder::show(Cloudder::getPublicId());  
            $urlMap[$clientImageName] = $cloudinaryURL;    
          } catch (\Exception $exception) {
            \Log::error("Image upload failed");
            \Log::error($exception);
          }
        }
        // Convert image names to cloudinary urls
        $content = strtr($request->content, $urlMap);
        if($request->headerImageName) {
          $headerImageUrl = $urlMap[$request->headerImageName];
        }
      }

      $user = auth()->user();
      $blogPost = $user->blogPosts()->create([
        "title" => $request->title,
        "summary" => $request->summary,
        "content" => $content,
        "header_image_url" => $headerImageUrl
      ]);

      return response()->json(["slug" => $blogPost->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $blogPost)
    {
      // todo test fail condition
      $formattedResponse = [
        "title" => $blogPost->title,
        "summary" => $blogPost->summary,
        "content" => $blogPost->content,
        "header_image_url" => $blogPost->header_image_url,
        "created_at" => $blogPost->created_at
      ];
      return response()->json($formattedResponse);
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
