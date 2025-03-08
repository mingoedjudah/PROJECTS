    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Post</title>

        @include('partials.links')

        <link href="../assets-user/css/profile.css" rel="stylesheet">
        <link href="../assets-user/css/post.css" rel="stylesheet">
        <link href="../assets-user/css/comment.css" rel="stylesheet">

    </head>

    <body>

        <!-- ======= Header ======= -->
        @include('partials.header')
        <!-- End Header -->

        <!-- ======= Sidebar ======= -->
        @include('partials.sidebar')
        <!-- End Sidebar-->


        <main id="main" class="main" data-user-id="{{ Auth::id() }}">

            <div class="pagetitle">
            </div><!-- End Page Title -->
            <section class="section">
                <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"
                    rel="stylesheet">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="content" class="content content-full-width">
                                <!-- begin profile -->
                                <div class="profile">
                                    <div class="profile-header">
                                        <!-- BEGIN profile-header-cover -->
                                        <div class="profile-header-cover"
                                            style="
                                                @if ($userDetails->cover_photo) background-image: url('{{ asset('storage/' . $userDetails->cover_photo) }}');
                                                @else
                                                    background-image: url('{{ asset('assets-user/img/default-cover.jpg') }}');
                                                    background-color: #dddddd; @endif
                                                background-size: cover; 
                                                background-position: center;">
                                        </div>

                                        <!-- END profile-header-cover -->
                                        <!-- BEGIN profile-header-content -->
                                        <div class="profile-header-content">
                                            <!-- BEGIN profile-header-img -->

                                            <div class="profile-header-img" style="margin-top: 80px;">
                                                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets-user/img/none-profile.jpg') }}"
                                                    alt="Profile Picture">
                                            </div>
                                            <!-- END profile-header-img -->
                                            <!-- BEGIN profile-header-info -->
                                            <div class="profile-header-info">
                                                <h4 class="name-user" style="font-weight:bold;">
                                                    <span class="username-background">
                                                        {{ $userDetails->username }}
                                                    </span>
                                                </h4>
                                                <a href="{{ route('user.edit-profile') }}"
                                                    class="btn btn-primary mb-2">Edit
                                                    Profile</a>
                                            </div>
                                            <!-- END profile-header-info -->
                                        </div>
                                        <!-- END profile-header-content -->

                                        <!-- BEGIN profile-header-tab -->
                                        <ul class="profile-header-tab nav nav-tabs">
                                            <li class="nav-item"><a href="#" class="nav-link_"
                                                    style="font-size: 15px;">{{ $postCount }}
                                                    Posts</a></li>
                                            <li class="nav-item"><a href="#" class="nav-link_"
                                                    style="font-size: 15px;">{{ $followersCount }}
                                                    Followers</a></li>
                                            <li class="nav-item"><a href="#" class="nav-link_"
                                                    style="font-size: 15px;">{{ $followingCount }}
                                                    Following</a></li>
                                        </ul>
                                        <!-- END profile-header-tab -->
                                    </div>
                                    <div class="card" style="margin-top: 20px;">
                                        <button type="button" class="btn w-100" data-bs-toggle="modal"
                                            data-bs-target="#onYourMindModal"
                                            style="background-color: rgb(255, 255, 255); height: 50px;">
                                            What's on your mind?
                                        </button>
                                        <div class="modal fade" id="onYourMindModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header text-center">
                                                        <h5 class="modal-title w-100" style="font-weight: bold;">Create
                                                            a
                                                            new post</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Text Field -->
                                                        <form action="{{ route('posts.store') }}" method="POST"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            <!-- Privacy Settings -->
                                                            <div class="mb-3">
                                                                <label for="privacy" class="form-label">Who can see
                                                                    your
                                                                    post?</label>
                                                                <select class="form-select" id="privacy"
                                                                    name="privacy">
                                                                    <option value="Public" selected>Public</option>
                                                                    <option value="Friends">Friends</option>
                                                                    <option value="Only Me">Only Me</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <textarea class="form-control" id="postText" name="caption" rows="4" placeholder="What's on your mind?"
                                                                    style="background-color: rgb(243, 243, 243);"></textarea>
                                                            </div>
                                                            <!-- Upload Photo Button -->
                                                            <div class="mb-3">
                                                                <button type="button" class="btn upload-button w-100"
                                                                    id="uploadPhotoBtn">
                                                                    Upload Photo
                                                                </button>
                                                                <input type="file" id="fileInput" name="image"
                                                                    accept="image/*" style="display: none;">
                                                            </div>
                                                            <!-- Buttons -->
                                                            <div class="d-grid gap-2">
                                                                <button type="submit"
                                                                    class="btn btn-primary">Post</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- End Basic Modal-->
                                    </div>
                                    </button>

                                    <div class="col-lg-12">
                                        @if ($posts->isNotEmpty())
                                            @foreach ($posts as $post)
                                                <div class="card">
                                                    <div class="card-body">
                                                        <!-- Post Header -->
                                                        <div class="d-flex align-items-center">
                                                            <!-- Profile Picture -->
                                                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets-user/img/none-profile.jpg') }}"
                                                                alt="Profile Picture" class="rounded-circle-profile"
                                                                style="width: 50px; height: 50px; object-fit: cover;">

                                                            <!-- Post Information -->
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            <strong
                                                                                class="d-block mt-2">{{ $post->user->username }}</strong>
                                                                        </div>
                                                                        <div class="text-muted"
                                                                            style="font-size:13px;">
                                                                            {{ $post->created_at->diffForHumans() }}
                                                                            @if ($post->privacy === 'Public')
                                                                                <i class="fas fa-globe"
                                                                                    style="color: #c0bebe; font-size:12px;"></i>
                                                                            @elseif ($post->privacy === 'Friends')
                                                                                <i class="fas fa-user-friends"
                                                                                    style="color: #c0bebe; font-size:10px;"></i>
                                                                            @elseif ($post->privacy === 'Only Me')
                                                                                <i class="fas fa-lock"
                                                                                    style="color: #c0bebe; font-size:12px;"></i>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <!-- Ellipsis Button -->
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-link" type="button"
                                                                            id="dropdownMenuButton-{{ $post->id }}"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <i class="fa-solid fa-ellipsis"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu"
                                                                            aria-labelledby="dropdownMenuButton-{{ $post->id }}">
                                                                            <li>
                                                                                <a class="dropdown-item edit-post-btn"
                                                                                    data-post-id="{{ $post->user_post_id }}"
                                                                                    data-caption="{{ $post->caption }}"
                                                                                    data-image-url="{{ asset('storage/' . $post->image) }}"
                                                                                    data-privacy="{{ $post->privacy }}"
                                                                                    data-first-name="{{ $post->user->first_name ?? 'N/A' }}"
                                                                                    data-last-name="{{ $post->user->last_name ?? 'N/A' }}">
                                                                                    <i class="fas fa-edit"></i> Edit
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item deletePostBtn"
                                                                                    href="#"
                                                                                    data-id="{{ $post->user_post_id }}">
                                                                                    <i class="fas fa-trash"></i> Delete
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <!-- Post Content/ -->
                                                        <div class="mb-3">
                                                            <!-- Caption -->
                                                            <p style="margin-top:20px;">{{ $post->caption }}</p>
                                                            <!-- Photo -->
                                                            @if ($post->image)
                                                                <div class="card" style="width: 100%;">
                                                                    <img src="{{ asset('storage/' . $post->image) }}"
                                                                        alt="Post Photo" class="img-fluid"
                                                                        style="max-height: 400px; object-fit: contain; width: 100%; height: auto;">
                                                                </div>
                                                            @endif
                                                            <!-- Icons and Counts -->
                                                            <div class="d-flex align-items-center mt-3">
                                                                <!-- Likes Button and Count -->
                                                                <button class="btn btn-link like-button no-underline"
                                                                    type="button"
                                                                    data-post-id="{{ $post->user_post_id }}"
                                                                    data-url="{{ route('like.toggle') }}">
                                                                    <i class="icon-comment fa-heart {{ $post->likes->contains('user_id', Auth::id()) ? 'fas' : 'far' }}"
                                                                        style="{{ $post->likes->contains('user_id', Auth::id()) ? 'color: red;' : 'color: black;' }}"></i>
                                                                    <span
                                                                        class="like-count">{{ $post->likes->count() }}</span>
                                                                </button>

                                                                <!-- Comments Button and Count -->
                                                                <button class="btn btn-link no-underline"
                                                                    type="button" data-bs-toggle="collapse"
                                                                    data-bs-target="#comments-{{ $post->user_post_id }}">
                                                                    <i class="fa-regular fa-comment"
                                                                        style="color: #333; font-size: 20px;"></i>
                                                                    <span id="comment-count-{{ $post->user_post_id }}"
                                                                        style="font-size: 20px;">
                                                                        {{ $post->comments->count() }}
                                                                    </span>
                                                            </div>


                                                            <!-- Comments Section -->
                                                            <div class="collapse mt-3"
                                                                id="comments-{{ $post->user_post_id }}">
                                                                <div class="comments-container">
                                                                    <h6>Comments</h6>
                                                                    <hr>
                                                                    <!-- Loop through comments -->
                                                                    @foreach ($post->comments->whereNull('parent_id') as $comment)
                                                                        <div class="comment d-flex mb-2 p-2 border-bottom"
                                                                            data-comment-id="{{ $comment->comment_id }}">
                                                                            <img src="{{ $comment->user->profile_picture ? asset('storage/' . $comment->user->profile_picture) : asset('assets-user/img/none-profile.jpg') }}"
                                                                                alt="Profile Picture"
                                                                                class="rounded-circle small-img">
                                                                            <div class="ms-2 w-100">
                                                                                <div
                                                                                    class="d-flex justify-content-between">
                                                                                    <div>
                                                                                        <strong>{{ $comment->user->username }}</strong>
                                                                                        @php
                                                                                            $createdAt =
                                                                                                $comment->created_at;
                                                                                            $now = \Carbon\Carbon::now();
                                                                                            $oneDayAgo = $now->subDay();
                                                                                        @endphp
                                                                                        <div class="text-muted"
                                                                                            style="font-size: 13px;">
                                                                                            @if ($createdAt->lessThanOrEqualTo($oneDayAgo))
                                                                                                {{ $createdAt->format('F j, Y') }}
                                                                                            @else
                                                                                                {{ $createdAt->format('F j, Y') }},
                                                                                                {{ $createdAt->diffForHumans() }}
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                    @auth
                                                                                        @if (Auth::id() === $comment->user_id || Auth::id() === $post->user_id)
                                                                                            <div id="comment-{{ $comment->comment_id }}"
                                                                                                class="comment-item">
                                                                                                <div class="dropdown">
                                                                                                    <button
                                                                                                        class="btn btn-link p-0"
                                                                                                        type="button"
                                                                                                        id="dropdownMenuButton{{ $comment->comment_id }}"
                                                                                                        data-bs-toggle="dropdown"
                                                                                                        aria-expanded="false">
                                                                                                        <i
                                                                                                            class="fa-solid fa-ellipsis"></i>
                                                                                                    </button>
                                                                                                    <ul class="dropdown-menu dropdown-menu-end"
                                                                                                        aria-labelledby="dropdownMenuButton{{ $comment->comment_id }}">
                                                                                                        @if (Auth::id() === $comment->user_id)
                                                                                                            <li>
                                                                                                                <button
                                                                                                                    class="dropdown-item edit-btn-comment"
                                                                                                                    data-comment-id="{{ $comment->comment_id }}"><i
                                                                                                                        class="fas fa-edit"></i>
                                                                                                                    Edit</button>
                                                                                                            </li>
                                                                                                        @endif
                                                                                                        <li>
                                                                                                            <form
                                                                                                                method="POST"
                                                                                                                action="{{ route('comments.destroy', $comment->comment_id) }}"
                                                                                                                id="delete-form-{{ $comment->comment_id }}"
                                                                                                                class="d-inline">
                                                                                                                @csrf
                                                                                                                @method('DELETE')
                                                                                                                <button
                                                                                                                    type="button"
                                                                                                                    class="dropdown-item delete-btn-comment"
                                                                                                                    data-comment-id="{{ $comment->comment_id }}">
                                                                                                                    <i
                                                                                                                        class="fas fa-trash"></i>
                                                                                                                    Delete
                                                                                                                </button>
                                                                                                            </form>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endauth
                                                                                </div>
                                                                                <p class="mb-1 comment-content"
                                                                                    id="comment-content-{{ $comment->comment_id }}">
                                                                                    {{ $comment->content }}</p>
                                                                                <!-- Hidden edit form -->
                                                                                <div class="edit-form d-none"
                                                                                    id="edit-form-{{ $comment->comment_id }}">
                                                                                    <form method="POST"
                                                                                        action="{{ route('comments.update', $comment->comment_id) }}"
                                                                                        class="d-flex mt-3 edit-comment-form"
                                                                                        enctype="multipart/form-data">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <input type="text"
                                                                                            class="form-control me-2"
                                                                                            name="content"
                                                                                            value="{{ $comment->content }}"
                                                                                            required>
                                                                                        <input type="hidden"
                                                                                            name="comment_id"
                                                                                            value="{{ $comment->comment_id }}">
                                                                                        <button class="btn btn-primary"
                                                                                            type="submit">Save</button>
                                                                                        <button type="button"
                                                                                            class="btn btn-secondary cancel-edit"
                                                                                            data-comment-id="{{ $comment->comment_id }}"
                                                                                            style="margin-left: 4px;">Cancel</button>
                                                                                    </form>
                                                                                </div>

                                                                                <div class="comment"
                                                                                    id="comment-{{ $comment->comment_id }}">
                                                                                    <button
                                                                                        class="btn btn-link btn-sm reply-btn"
                                                                                        data-comment-id="{{ $comment->comment_id }}">Reply</button>

                                                                                    <!-- Hidden reply form -->
                                                                                    <div class="reply-form d-none">
                                                                                        <form
                                                                                            class="reply-form d-flex justify-content-end"
                                                                                            method="POST">
                                                                                            @csrf
                                                                                            <input type="hidden"
                                                                                                name="post_id"
                                                                                                value="{{ $post->user_post_id }}">
                                                                                            <input type="hidden"
                                                                                                name="parent_id"
                                                                                                value="{{ $comment->comment_id }}">
                                                                                            <input type="text"
                                                                                                class="form-control me-2"
                                                                                                name="content"
                                                                                                placeholder="Add a reply..."
                                                                                                required>
                                                                                            <button
                                                                                                class="btn btn-primary me-2"
                                                                                                type="submit">Reply</button>
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary cancel-reply">Cancel</button>
                                                                                        </form>
                                                                                    </div>


                                                                                    <div
                                                                                        class="replies-container mt-2">
                                                                                        @if ($comment->replies->count() > 0)
                                                                                            <button
                                                                                                class="btn btn-link btn-sm view-replies-btn"
                                                                                                data-comment-id="{{ $comment->comment_id }}">
                                                                                                View <span
                                                                                                    class="reply-count">{{ $comment->replies->count() }}</span>
                                                                                                {{ Str::plural('reply', $comment->replies->count()) }}
                                                                                                <i
                                                                                                    class="fas fa-chevron-down"></i>
                                                                                            </button>

                                                                                            <!-- Replies container -->
                                                                                            <div
                                                                                                class="replies-list d-none">
                                                                                                @foreach ($comment->replies as $reply)
                                                                                                    <div class="comment d-flex mb-2 p-2 border-bottom"
                                                                                                        id="reply-{{ $reply->comment_id }}">
                                                                                                        <img src="{{ $reply->user->profile_picture ? '/storage/' . $reply->user->profile_picture : '/assets-user/img/none-profile.jpg' }}"
                                                                                                            alt="User"
                                                                                                            class="rounded-circle small-img">
                                                                                                        <div
                                                                                                            class="ms-2 flex-grow-1">
                                                                                                            <div
                                                                                                                class="d-flex justify-content-between">
                                                                                                                <div>
                                                                                                                    <strong>{{ $reply->user->username }}</strong>
                                                                                                                    <div class="text-muted"
                                                                                                                        style="font-size:13px;">
                                                                                                                        @php
                                                                                                                            $createdAt =
                                                                                                                                $reply->created_at;
                                                                                                                            $now = \Carbon\Carbon::now();
                                                                                                                            $oneDayAgo = $now->subDay();
                                                                                                                        @endphp

                                                                                                                        @if ($createdAt->lessThanOrEqualTo($oneDayAgo))
                                                                                                                            {{ $createdAt->format('F j, Y') }}
                                                                                                                        @else
                                                                                                                            {{ $createdAt->format('F j, Y') }},
                                                                                                                            {{ $createdAt->diffForHumans() }}
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div>
                                                                                                                    @if (auth()->id() === $reply->user_id || auth()->id() === $comment->user_id)
                                                                                                                        <button
                                                                                                                            class="btn btn-link btn-sm more-options-btn"
                                                                                                                            data-bs-toggle="dropdown"
                                                                                                                            aria-expanded="false">
                                                                                                                            <i
                                                                                                                                class="fa-solid fa-ellipsis"></i>
                                                                                                                        </button>
                                                                                                                        <ul
                                                                                                                            class="dropdown-menu dropdown-menu-end">
                                                                                                                            @if (auth()->id() === $reply->user_id)
                                                                                                                                <li>
                                                                                                                                    <a class="dropdown-item edit-reply-btn"
                                                                                                                                        href="#"
                                                                                                                                        data-reply-id="{{ $reply->comment_id }}"
                                                                                                                                        data-comment-id="{{ $comment->comment_id }}">
                                                                                                                                        <i
                                                                                                                                            class="fas fa-edit"></i>
                                                                                                                                        Edit
                                                                                                                                    </a>
                                                                                                                                </li>
                                                                                                                            @endif
                                                                                                                            @if (auth()->id() === $reply->user_id || auth()->id() === $comment->user_id)
                                                                                                                                <li>
                                                                                                                                    <a class="dropdown-item delete-reply-btn"
                                                                                                                                        href="#"
                                                                                                                                        data-reply-id="{{ $reply->comment_id }}"
                                                                                                                                        data-comment-id="{{ $comment->comment_id }}">
                                                                                                                                        <i
                                                                                                                                            class="fas fa-trash"></i>
                                                                                                                                        Delete
                                                                                                                                    </a>
                                                                                                                                </li>
                                                                                                                            @endif
                                                                                                                        </ul>
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <p
                                                                                                                class="mb-1 reply-content">
                                                                                                                {{ $reply->content }}
                                                                                                            </p>
                                                                                                            <div
                                                                                                                class="edit-reply-form d-none">
                                                                                                                <form
                                                                                                                    method="POST"
                                                                                                                    action="{{ route('comments.updateReply', ['comment' => $comment->comment_id, 'reply' => $reply->comment_id]) }}"
                                                                                                                    class="d-flex align-items-center">
                                                                                                                    @csrf
                                                                                                                    @method('PUT')
                                                                                                                    <input
                                                                                                                        type="text"
                                                                                                                        class="form-control me-2"
                                                                                                                        name="content"
                                                                                                                        value="{{ $reply->content }}"
                                                                                                                        required>
                                                                                                                    <button
                                                                                                                        class="btn btn-primary btn-sm me-2"
                                                                                                                        type="submit">Save</button>
                                                                                                                    <button
                                                                                                                        type="button"
                                                                                                                        class="btn btn-secondary btn-sm cancel-edit-btn">Cancel</button>
                                                                                                                </form>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>

                                                                <!-- Add Comment Form -->
                                                                <div class="mt-3">
                                                                    <form method="POST"
                                                                        action="{{ route('comments.store') }}"
                                                                        class="d-flex mt-3 comment-form"
                                                                        data-comment-url="{{ route('comments.store') }}">
                                                                        @csrf
                                                                        <input type="hidden" name="post_id"
                                                                            value="{{ $post->user_post_id }}">
                                                                        <input type="text"
                                                                            class="form-control me-2" name="content"
                                                                            placeholder="Add a comment..." required>
                                                                        <button class="btn btn-primary"
                                                                            type="submit">Comment</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            @if ($posts->isNotEmpty())
                                                <script>
                                                    var userId = @json(Auth::id());
                                                    var postOwnerId = @json($posts->first()->user_id);
                                                </script>
                                            @endif
                                        @else
                                            <div class="alert alert-info text-center">No posts to display.</div>
                                        @endif
                                    </div>

                                    <!-- Edit Post Modal -->
                                    <div class="modal fade" id="editPostModal" tabindex="-1"
                                        aria-labelledby="editPostModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form id="editPostForm" action="" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="modal-body"
                                                        style="max-height: 400px; overflow-y: auto;">
                                                        <!-- Profile Picture, Name, and Privacy Setting -->
                                                        <div class="d-flex align-items-center mb-3">
                                                            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets-user/img/none-profile.jpg') }}"
                                                                alt="Profile Picture" class="rounded-circle-profile"
                                                                style="width: 50px; height: 50px; object-fit: cover;">

                                                            <div class="ms-3">
                                                                <div class="fw-bold mt-3" id="userName"></div>
                                                                <div class="position-relative">
                                                                    <span
                                                                        id="privacyDisplay">{{ isset($post) ? $post->privacy : 'Public' }}</span>
                                                                    <i class="fa fa-caret-down" id="privacyIcon"
                                                                        style="cursor: pointer; padding: 5px; display: inline-block;"></i>
                                                                    <div class="dropdown-menu" id="privacyDropdown"
                                                                        style="display: none; position: absolute; top: 100%; left: 0; min-width: 300px;">
                                                                        <div class="p-2 fw-bold"
                                                                            style="margin-left: 13px;">Who can
                                                                            see
                                                                            your post?</div>
                                                                        <div
                                                                            class="form-check d-flex justify-content-between align-items-center">
                                                                            <label class="form-check-label me-2"
                                                                                for="public"><i
                                                                                    class="fas fa-globe"></i>
                                                                                Public</label>
                                                                            <input class="form-check-input"
                                                                                style="margin-right: 20px;"
                                                                                type="radio" name="privacy"
                                                                                id="public" value="Public">
                                                                        </div>
                                                                        <div
                                                                            class="form-check d-flex justify-content-between align-items-center">
                                                                            <label class="form-check-label me-2"
                                                                                for="friends"><i
                                                                                    class="fas fa-user-friends"></i>
                                                                                Friends</label>
                                                                            <input class="form-check-input"
                                                                                style="margin-right: 20px;"
                                                                                type="radio" name="privacy"
                                                                                id="friends" value="Friends">
                                                                        </div>
                                                                        <div
                                                                            class="form-check d-flex justify-content-between align-items-center">
                                                                            <label class="form-check-label me-2"
                                                                                for="onlyMe"><i
                                                                                    class="fas fa-lock"></i> Only
                                                                                Me</label>
                                                                            <input class="form-check-input"
                                                                                style="margin-right: 20px;"
                                                                                type="radio" name="privacy"
                                                                                id="onlyMe" value="Only Me">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Caption Textarea -->
                                                        <div class="mb-3">
                                                            <textarea class="form-control caption" id="caption" name="caption" required placeholder="What's on your mind?"
                                                                style="border:none; font-size: 16px; padding: 10px; background-color: rgb(255, 255, 255);">{{ old('caption', isset($post) ? $post->caption : '') }}</textarea>
                                                        </div>

                                                        <!-- Upload Photo Button -->
                                                        <div class="mb-3 position-relative">
                                                            <input type="file" id="fileInput" name="image"
                                                                accept="image/*" style="display: none;">
                                                            <button type="button"
                                                                class="btn btn-light position-absolute start-0"
                                                                id="uploadPhotoButton"
                                                                style="margin-top: 5px; margin-left:5px;">
                                                                <i class="fas fa-plus-circle"></i> Add photos
                                                            </button>
                                                        </div>

                                                        <!-- Image Preview -->
                                                        <img id="postImagePreview"
                                                            src="{{ isset($post) && $post->image ? asset('storage/' . $post->image) : '' }}"
                                                            alt="Post Image" class="img-fluid mt-2"
                                                            style="max-height: 300px; object-fit: cover; {{ isset($post) && $post->image ? '' : 'display: none;' }}">
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary w-100">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            </section>
                        </main><!-- End #main -->


        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        @include('partials.footer')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="../assets-user/js/user-post.js"></script>
        <script src="../assets-user/js/delete-comment.js"></script>
        <script src="../assets-user/js/reply.js"></script>
        <script src="../../assets-user/js/unfollow.js"></script>
        <script>
            window.csrfToken = '{{ csrf_token() }}';
            window.httpMethod = 'PUT';
        </script>

    </body>

    </html>
