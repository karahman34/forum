<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\PostRequest;
use App\Http\Resources\CommentsCollection;
use App\Http\Resources\PostsCollection;
use App\Image;
use App\Post;
use App\PostSeen;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get QS Options
        $limit = $request->get('limit', 15);
        $q = $request->get('q', null);
        $popular = $request->get('popular', 0);
        $tags = $request->get('tags', null);
        $sort = $request->get('sort', null);

        // Get user id
        $userId = -1;
        if (auth()->check()) {
            $userId = auth()->id();
        }

        $query = Post::select(
            'posts.id',
            'user_id',
            'title',
            'posts.created_at',
            'posts.updated_at',
            'post_seens.count as seens_count',
        )
            ->with([
                'author:id,username,avatar',
                'tags:name',
            ])
            ->join('post_seens', 'posts.id', 'post_seens.post_id')
            ->withCount(['comments', 'savedFromUser AS saved' => function (Builder $query) use ($userId) {
                $query->where('saved_posts.user_id', $userId);
            }]);

        // Apply search
        if ($q !== null) {
            $query->where('posts.title', 'like', "%{$q}%");
        }

        // Apply Popular
        if ($popular == '1') {
            $now = Carbon::now();
            $weekBefore = Carbon::now()->subDays(7);

            $query->whereBetween('posts.created_at', [$weekBefore, $now])
                ->orderBy('post_seens.count', 'DESC');
        }

        // Apply tags filter
        if ($tags !== null) {
            $tags = explode(',', $tags);
            $query->join('post_tags', 'post_tags.post_id', 'posts.id')
                ->join('tags', 'tags.id', 'post_tags.tag_id')
                ->whereIn('tags.name', $tags);
        }

        // Apply sort query
        if ($sort !== null) {
            $sort = ($sort === 'new') ? 'desc' : 'asc';
            $query->orderBy('posts.created_at', $sort);
        }

        // Get the posts
        $posts = $query->paginate($limit);

        if ($request->wantsJson()) {
            return (new PostsCollection($posts))
                ->additional([
                    'ok' => true,
                ]);
        }

        return view('welcome', compact('posts'));
    }

    /**
     * Save post
     *
     * @param   int  $id
     *
     * @return  \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function savePost(int $id)
    {
        $post = Post::select('id')->findOrFail($id);
        $user = auth()->user();
        $user->savedPosts()->attach($post->id);

        return response()->json([
            'ok' => true,
            'message' => 'Success to save post.',
        ], 201);
    }

    /**
     * Unsave post
     *
     * @param   int  $id
     *
     * @return  \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function unSavePost(int $id)
    {
        $post = Post::select('id')->findOrFail($id);
        $user = auth()->user();
        $user->savedPosts()->detach($post->id);

        return response()->json([
            'ok' => true,
            'message' => 'Success to unsave post.',
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Post';
        $method = 'POST';
        $action = route('post.store');

        return view('posts.form', compact('method', 'action', 'title'));
    }

    /**
     * Store post image.
     *
     * @param   array  $images
     *
     * @return  array  $imageNames
     */
    private function storeImages(array $images)
    {
        $imageNames = [];
        foreach ($images as $image) {
            $imageNames[] = [
                'url' => $image->store('img/posts')
            ];
        }

        return $imageNames;
    }

    /**
     * Sync post tags
     *
     * @param Request $request
     * @param Post    $post
     *
     * @return void
     */
    private function syncTags(Request $request, Post $post)
    {
        // Get Tags
        $tags = $request->get('tags');
        // Get Tags in db
        $tagsInDB = Tag::select('id', 'name')->whereIn('name', $tags)->get();

        $selectedTagIds = [];
        foreach ($tags as $tag) {
            $t = $tagsInDB->firstWhere('name', $tag);
            if (!$t) {
                $t = Tag::create([
                    'name' => $tag,
                ]);
            }

            $selectedTagIds[] =  $t->id;
        }

        // Sync post tags
        $post->tags()->sync($selectedTagIds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // Create Post
        $payload = $request->only('title', 'body');
        $payload['user_id'] = Auth::id();
        $post = Post::create($payload);

        // Sync tags
        $this->syncTags($request, $post);

        if ($request->has('images')) {
            $imageNames = $this->storeImages($request->file('images'));

            // Associate post images
            $post->images()->createMany($imageNames);
        }

        // Set flash message
        $request->session()->flash('success', 'Success to create Post.');

        $nextUrl = route('post.show', ['id' => $post->id]);
        return response()->json(['next_url' => $nextUrl], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        // Get the post
        $qResult = Post::with([
            'images',
            'author:id,avatar,username',
            'tags:name',
            'seen:post_id,count'
        ])
            ->withCount('comments')
            ->findOrFail($id);

        // Set title
        $title = $qResult->title;

        $post = [
            'id' => $qResult->id,
            'title' => $qResult->title,
            'body' => $qResult->body,
            'created_at' => $qResult->created_at->diffForHumans(),
            'updated_at' => $qResult->updated_at->diffForHumans(),
            'comments_count' => $qResult->comments_count,
            'seen' => [
                'count' => $qResult->seen->count,
            ],
            'images' => $qResult->images->map(function ($image) {
                return [
                    'src' => $image->getImage(),
                    'original' => $image->src,
                ];
            }),
            'author' => [
                'id' => $qResult->author->id,
                'avatar' => $qResult->author->getAvatar(),
                'username' => $qResult->author->username,
            ],
            'tags' => $qResult->tags->map(function ($tag) {
                return [
                    'name' => $tag->name,
                ];
            }),
        ];

        return view('posts.show', compact('post', 'title'));
    }

    /**
     * Get post of comments.
     *
     * @param   Request $request
     * @param   int     $id
     *
     * @return  LengthAwarePaginator
     */
    public function getComments(Request $request, int $id)
    {
        $sort = $request->get('sort', null);
        $query = Comment::with(['images:url,imageable_id,imageable_type', 'user:id,username,avatar'])
            ->where('post_id', $id)
            ->orderBy('pinned', 'asc');

        // Apply sort
        if ($sort !== null) {
            $sort = ($sort === 'new') ? 'desc' : 'asc';
            $query->orderBy('comments.created_at', $sort);
        }

        $comments = $query->paginate();

        return (new CommentsCollection($comments));
    }

    /**
     * Increment post seen.
     *
     * @param   int  $id
     *
     * @return  JsonResponse
     */
    public function incrementSeen(int $id)
    {
        $postSeen = PostSeen::where('post_id', $id)->first();
        if ($postSeen) {
            $postSeen->increment('count', 1);
        } else {
            $postSeen = PostSeen::create([
                'post_id' => $id,
                'count' => 1,
            ]);
        }

        return response()->json([
            'ok' => true
        ], 202);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        // Get the post
        $post = Post::with(['tags:tags.name', 'images'])->findOrFail($id);

        // Check authorization
        $this->authorize('update', $post);

        // Set the title & form
        $title = "Update {$post->title}";
        $method = 'PUT';
        $action = route('post.update', ['id' => $post->id]);

        return view('posts.form', compact('method', 'action', 'post', 'title'));
    }

    /**
     * Delete Post Image
     *
     * @param   array  images
     *
     * @return  void
     */
    private function deletePostImage(array $images)
    {
        // Delete post images from storage.
        Storage::delete($images);

        // Delete post image from db.
        Image::whereIn('url', $images)->delete();
    }

    /**
     * Sync old Post images
     *
     * @param   Request  $request
     * @param   Post     $post
     *
     * @return  void
     */
    private function syncOldImages(Request $request, Post $post)
    {
        // Get old images
        $oldImages = $request->get('old_images', []);
        // Get post images
        $postImages = $post->images;

        // Array containing url of images
        $imageWillDelete = [];

        if (count($oldImages) === 0) {
            $imageWillDelete = $postImages->pluck('url');
        } else {
            foreach ($postImages as $postImage) {
                if (!in_array($postImage->url, $oldImages)) {
                    $imageWillDelete[] = $postImage->url;
                }
            }
        }

        if (count($imageWillDelete) > 0) {
            // Delete post image from storage
            $this->deletePostImage($imageWillDelete->toArray());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PostRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        // Get payload
        $payload = $request->only('title', 'body');

        // Get post
        $post = Post::findOrFail($id);

        // Check authorization
        $this->authorize('update', $post);

        // Update Post
        $post->update($payload);

        // Sync post tags
        $this->syncTags($request, $post);

        // Sync old images
        $this->syncOldImages($request, $post);

        // Upload images
        if ($request->has('images')) {
            $imageNames = $this->storeImages($request->file('images'));
            $post->images()->createMany($imageNames);
        }

        // Set flash message
        $request->session()->flash('success', 'Success to update Post.');

        $nextUrl = route('post.show', ['id' => $post->id]);
        return response()->json(['next_url' => $nextUrl], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the post
        $post = Post::select('id', 'user_id')->where('id', $id)->firstOrFail();

        // Check authorization
        $this->authorize('update', $post);

        if ($post->delete()) {
            // Get post images
            $postImages = $post->images->pluck('url')->toArray();
            // Delete post images from local
            $this->deletePostImage($postImages);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Success to delete post.',
        ], 202);
    }
}
