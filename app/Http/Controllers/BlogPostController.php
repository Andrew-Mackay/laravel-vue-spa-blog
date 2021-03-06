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
      $posts = BlogPost::where("is_draft", false)->select("slug", "title", "summary", "header_image_url", "created_at")->orderBy('created_at', 'desc')->get();
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
            "markdown" => "string | required",
            "images" => "array | max:30",
            "images.*" => "image | mimes:gif,jpeg,png,bmp,jpg|max:20000",
            "headerImageName" => "nullable | string",
            "saveAsDraft" => "boolean | required"
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
      }
      $user = auth()->user();
      $blogPost = $user->blogPosts()->create([
        "title" => $request->title,
        "summary" => $request->summary,
        "content" => "",
        "markdown" => $request->markdown,
        "header_image_url" => "",
        "is_draft" => $request->saveAsDraft,
        "url_name_map" => []
      ]);

      $content = $request->content;
      $headerImageUrl = "";
      $urlMap = [];
      if($request->images) {
        $uploadFolder = self::CLOUDINARY_UPLOAD_FOLDER . env("APP_ENV", "local") . "/" . $blogPost->slug;
        $uploadOptions = [
          "folder" => $uploadFolder
        ];
        foreach ($request->images as $image) {
          try {
            $clientImageName = $image->getClientOriginalName();
            Cloudder::upload($image->getRealPath(), null, $uploadOptions);  
            $uploadedResult = Cloudder::getResult();
            $cloudinaryURL = $uploadedResult["secure_url"];  
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
      $blogPost->content = $content;
      $blogPost->header_image_url = $headerImageUrl;
      $blogPost->url_name_map = $urlMap;
      $blogPost->save();

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
     * Display the specified resource for editing.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function showEdit(BlogPost $blogPost)
    {
      // todo test fail condition
      $formattedResponse = [
        "title" => $blogPost->title,
        "summary" => $blogPost->summary,
        "markdown" => $blogPost->markdown,
        "urlMap" => $blogPost->url_name_map,
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
      $validator = Validator::make(
        $request->all(),
        [
            "title" => "string | required",
            "summary" => "string | required",
            "content" => "string | required",
            "markdown" => "string | required",
            "images" => "array | max:30",
            "images.*" => "image | mimes:gif,jpeg,png,bmp,jpg|max:20000",
            "headerImageName" => "nullable | string",
        ]
      );
      if ($validator->fails()) {
        return response()->json($validator->errors(), JsonResponse::HTTP_BAD_REQUEST);
      }
      $blogPost->title = $request->title;
      $blogPost->summary = $request->summary;
      $blogPost->markdown = $request->markdown;
      $urlMap = $blogPost->url_name_map;
      $content = $request->content;
      if($request->images) {
        $uploadFolder = self::CLOUDINARY_UPLOAD_FOLDER . env("APP_ENV", "local") . "/" . $blogPost->slug;
        $uploadOptions = [
          "folder" => $uploadFolder
        ];
        foreach ($request->images as $image) {
          try {
            $clientImageName = $image->getClientOriginalName();
            if (array_key_exists($clientImageName, $urlMap)) {
              continue;
            }
            Cloudder::upload($image->getRealPath(), null, $uploadOptions);  
            $uploadedResult = Cloudder::getResult();
            $cloudinaryURL = $uploadedResult["secure_url"];  
            $urlMap[$clientImageName] = $cloudinaryURL;    
          } catch (\Exception $exception) {
            \Log::error("Image upload failed");
            \Log::error($exception);
          }
        }
      }
      // Convert image names to cloudinary urls
      $content = strtr($request->content, $urlMap);
      $blogPost->content = $content;
      $headerImageIsCloudinary = strpos($request->headerImageName, "cloudinary") !== false;
      if (!$headerImageIsCloudinary && $request->headerImageName) {
        $blogPost->header_image_url = $urlMap[$request->headerImageName] ?? "";
      }
      $blogPost->url_name_map = $urlMap;
      $blogPost->save();
      return response()->json(["slug" => $blogPost->slug]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogPost  $blogPost
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $blogPost)
    {
      $blogPostDeleted = false;
        try {
          $blogPost->delete();
          $blogPostDeleted = true;
        } catch (\Exception $exception) {
          \Log::error("Failed to destroy blog post");
          \Log::error($exception);
        }
        return response()->json(["success" => $blogPostDeleted]);
    }

    public function getDrafts() 
    {
      $posts = BlogPost::where("is_draft", true)->select("slug", "title", "summary", "header_image_url", "created_at")->orderBy('created_at', 'desc')->get();
      return response()->json(["posts" => $posts]);
    }

    public function publishDraft(BlogPost $blogPost)
    {
      $blogPost->is_draft = false;
      $blogPost->save();
    }
}
