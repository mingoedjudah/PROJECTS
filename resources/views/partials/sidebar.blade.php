<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-heading">DASHBOARD</li>

      <li class="nav-item">
          <a class="nav-link {{ Route::is('user.home') ? '' : 'collapsed' }}" href="{{ route('user.home') }}">
              <i class="fas fa-home"></i>
              <span>Post</span>
          </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
          <a class="nav-link {{ Route::is('user.profile') ? '' : 'collapsed' }}" href="{{ route('user.profile') }}">
              <i class="fas fa-user"></i>
              <span>Profile</span>
          </a>
      </li><!-- End Profile Nav -->

      <li class="nav-item">
        <a class="nav-link {{ Route::is('user.friends') ? '' : 'collapsed' }}" href="{{ route('user.friends') }}">
            <i class="fas fa-user-friends"></i><span>Friends</span>
        </a>
    </li><!-- End Friends Nav -->
      <br>
      <li class="nav-heading">Settings</li>

      <li class="nav-item">
          <a class="nav-link {{ Route::is('user.settings') ? '' : 'collapsed' }}" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
              <i class="fas fa-cogs"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="tables-nav" class="nav-content collapse {{ Route::is('user.settings') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
              <li>
                             <a href="{{ route('user.edit-profile') }}">
                      <i class="bi bi-circle"></i><span>Edit Profile</span>
                  </a>
              </li>
          </ul>
      </li><!-- End Settings Nav -->

      <li class="nav-item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="button" id="logout-button" class="nav-link {{ Route::currentRouteName() === 'logout' ? '' : 'collapsed' }}">
                <i class="fas fa-sign-out-alt"></i><span>Log out</span>
            </button>
        </form>
    </li>
    
  </ul>
</aside>

<script>
    /**
 * Logout
 */
    document.getElementById('logout-button').addEventListener('click', function() {
    Swal.fire({
        title: 'Logout',
        text: "Are you sure you want to leave this page or logout?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Log out'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    })
    });
</script>