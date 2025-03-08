
</style>
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{route ('user.home')}}" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/images/logo.png') }}" alt="">
        <span class="d-none d-lg-block" style="font-weight: bold;">POSTIFY</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="GET" action="#">
          <input type="text" id="search-query" name="query" placeholder="Search" title="Enter search keyword">
          <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
      <div id="search-results"></div>
  </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Search Icon-->

        <li class="nav-item dropdown">
          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            @if($notificationCount > 0)
                <span class="badge bg-danger badge-number">{{ $notificationCount }}</span>
            @endif
        </a><!-- End Notification Icon -->
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="max-height: 400px; overflow-y: auto; width:650%;">
            <li class="dropdown-header fw-bold">
                Notifications
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            @forelse($notifications as $notification)
                <li class="notification-item {{ $notification->status == 'unread' ? 'unread' : '' }}">
                    <a href="#">
                        <div class="d-flex">
                            <div class="pe-3">
                                @php
                                    $user = null;
                                    $username = 'Unknown';
                                    $profilePicture = asset('assets-user/img/none-profile.jpg');
                                    if ($notification->type == 'like' && $notification->likedBy) {
                                        $user = $notification->likedBy;
                                    } elseif ($notification->type == 'comment' && $notification->commentBy) {
                                        $user = $notification->commentBy;
                                    } elseif ($notification->type == 'reply' && $notification->replyBy) {
                                        $user = $notification->replyBy;
                                    }
                                    if ($user) {
                                        $username = $user->username;
                                        $profilePicture = $user->profile_picture
                                            ? asset('storage/' . $user->profile_picture)
                                            : asset('assets-user/img/none-profile.jpg');
                                    }
                                @endphp
                                <img src="{{ $profilePicture }}" alt="{{ $username }}" class="rounded-circle" style="width: 40px; height: 40px;">
                            </div>
                            <div class="text-wrap">
                                <p class="text-sm mb-1">
                                    <strong>{{ $username }}</strong>
                                    @switch($notification->type)
                                        @case('like')
                                            liked your post
                                            @break
                                        @case('comment')
                                            commented on your post
                                            @break
                                        @case('reply')
                                            replied to your comment
                                            @break
                                        @default
                                            sent you a notification
                                    @endswitch
                                </p>
                                <p class="text-muted mb-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
            @empty
                <li class="notification-item">
                    <p class="text-center mb-0">No notifications</p>
                </li>
            @endforelse
        </ul>
        

      
        </li><!-- End Notification Nav -->
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('assets-user/img/none-profile.jpg') }}" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block ps-2">{{ Auth::user()->username }}</span>
          </a><!-- End Profile Iamge Icon -->

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->
      </ul>
    </nav><!-- End Icons Navigation -->
</header>