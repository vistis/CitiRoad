<div class="app-container">
{{-- ADDED THE SIDEBAR STYLES HERE --}}
<style>
  .app-container {
    display: flex;
    min-height: 100vh;
    background-color: #f3f4f6;
  }

  .sidebar {
    width: 16rem;
    background-color: #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: fixed;
    height: 100vh;
  }

  .sidebar-top {
    display: flex;
    flex-direction: column;
  }

  .logo-container {
    padding: 1.5rem 0;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: center;
  }

  .logo-text {
    font-size: 1.875rem;
    font-weight: 700;
    color: #1f2937;
  }

  .sidebar-nav {
    margin-top: 1.5rem;
    padding: 0 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .sidebar-user-nav {
    margin-top: 1.5rem;
    padding: 0 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .main-content {
    flex: 1;
    /* padding: 1.5rem; */
    margin-left: 16rem; /* Crucial for moving content away from fixed sidebar */
  }
</style>
  <aside class="sidebar">
    <div class="sidebar-top">
    <div class="logo-container">
                        {{-- Wrapped the CitiRoad header in an anchor tag --}}
                        <a href="{{ route('admin.dashboard') }}" class="no-underline">
                            <h1 class="logo-text">CitiRoad</h1>
                        </a>
                </div>

      <nav class="sidebar-nav">
        {{-- Using named routes for consistency and best practice --}}
        @include('components.sideBarItems', [
            'to' => route('admin.dashboard'), // Assuming 'summary' is admin.dashboard
            'label' => 'Summary',
            'icon' => 'assets/icons/summary.svg',
            'currentRoute' => $currentRoute ?? ''
        ])

        @include('components.sideBarItems', [
            'to' => route('admin.reports.all'), // <--- CHANGED THIS LINE!
            'label' => 'Reports',
            'icon' => 'assets/icons/report.svg',
            'currentRoute' => $currentRoute ?? ''
        ])

        @include('components.sideBarItems', [
            'to' => route('admin.officers'),
            'label' => 'Officers',
            'icon' => 'assets/icons/officers.svg',
            'currentRoute' => $currentRoute ?? ''
        ])

        @include('components.sideBarItems', [
            'to' => route('admin.citizens'),
            'label' => 'Citizens',
            'icon' => 'assets/icons/citizen.svg',
            'currentRoute' => $currentRoute ?? ''
        ])

        {{-- NEW ADMINS TAB --}}
        @include('components.sideBarItems', [
            'to' => route('admin.admins'),
            'label' => 'Admins',
            'icon' => 'assets/icons/admins.svg', {{-- Assuming you have an admin.svg icon --}}
            {{-- Or use a Font Awesome icon if you prefer: 'icon_class' => 'fas fa-user-shield' --}}
            'currentRoute' => $currentRoute ?? ''
        ])
      </nav>
    </div>

    <nav class="sidebar-user-nav">
      @include('components.sideBarItems', [
            'to' => route('admin.account'), // Assuming a 'profile' named route exists for the logged-in admin's profile
            'label' => Auth::user()->first_name . " " . Auth::user()->last_name,
            'icon' => 'assets/icons/user.svg',
            'currentRoute' => $currentRoute ?? ''
      ])
    </nav>
  </aside>

  <main class="main-content">
    @yield('content')
  </main>
</div>
