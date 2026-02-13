<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
          <span class="text-secondary text-small">{{ ucfirst(Auth::user()->role->name ?? 'User') }}</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/home">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    @if(Auth::user()->role->name == 'admin')
      <li class="nav-item">
         <span class="nav-link" style="font-size: 0.8em; color: #b66dff; font-weight: bold; margin-top: 15px;">
            ADMIN MENU
         </span>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('kategori.index') }}">
          <span class="menu-title">Kelola Kategori</span>
          <i class="mdi mdi-format-list-bulleted menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('buku.index') }}">
          <span class="menu-title">Kelola Buku</span>
          <i class="mdi mdi-book-open-page-variant menu-icon"></i>
        </a>
      </li>
    @endif

    @if(Auth::user()->role->name == 'visitor')
      <li class="nav-item">
         <span class="nav-link" style="font-size: 0.8em; color: #b66dff; font-weight: bold; margin-top: 15px;">
            VISITOR MENU
         </span>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('visitor.kategori') }}">
          <span class="menu-title">Daftar Kategori</span>
          <i class="mdi mdi-format-list-bulleted menu-icon"></i>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('visitor.buku') }}">
          <span class="menu-title">Daftar Buku</span>
          <i class="mdi mdi-book-open-variant menu-icon"></i>
        </a>
      </li>
    @endif
  </ul>
</nav>