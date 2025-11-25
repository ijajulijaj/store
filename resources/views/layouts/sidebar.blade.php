<ul class="navbar-nav bg-custom-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">
  
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex flex-column align-items-center justify-content-center" href="{{ route('dashboard') }}">
    <div class="sidebar-brand-icon mb-2">
      <img src="{{ asset('public/admin_assets/img/logo.png') }}" alt="Company Logo" style="height:25px; width:auto;">
    </div>
    <div class="sidebar-brand-text" style="font-size: 0.65rem; text-align: center;">
      Agora Superstores
    </div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">
  
  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-home"></i>
      <span>Dashboard</span></a>
  </li>
  
  <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders"
        aria-expanded="false" aria-controls="collapseOrders">
        <i class="fas fa-cart-plus"></i>
        <span>Orders</span>
      </a>
      <div id="collapseOrders" class="collapse" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('admin.orders.pending.index') }}">Pending Order</a>
          <a class="collapse-item" href="{{ route('admin.orders.complete.index') }}">Complete Order</a>
          <a class="collapse-item" href="{{ route('admin.orders.cancel.index') }}">Cancel Order</a>
        </div>
      </div>
    </li>
    
    @if(session('user_type') == 1)
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseModifyOrders"
        aria-expanded="false" aria-controls="collapseModifyOrders">
        <i class="fas fa-sync-alt"></i>
        <span>Order Modify</span>
      </a>
      <div id="collapseModifyOrders" class="collapse" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('admin.orders.modify.index') }}">Modify Order</a>
        </div>
      </div>
    </li>
    @endif
    
    @if(session('user_type') == 1)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.products.index') }}">
        <i class="fas fa-boxes"></i>
        <span>All Products</span></a>
    </li>
    @endif
    
    <!-- category & sub category section -->
    @if(session('user_type') == 1)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.groups.index') }}">
        <i class="fas fa-layer-group"></i>
        <span>Groups</span></a>
    </li>
    @endif
    
    <!-- Images Section -->
    @if(session('user_type') == 1)
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseImages"
          aria-expanded="false" aria-controls="collapseImages">
          <i class="fas fa-image"></i>
          <span>Images</span>
        </a>
        <div id="collapseImages" class="collapse" aria-labelledby="headingImages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            
            <!-- Banner Submenu -->
            <h6 class="collapse-header">Banner:</h6>
            <a class="collapse-item" href="{{ route('admin.images.banner.index') }}">Add Banner</a>
            <a class="collapse-item" href="{{ route('admin.images.banner.view') }}">View Banner</a>
    
            <!-- Product Images Submenu -->
            <h6 class="collapse-header">Product Images:</h6>
            <a class="collapse-item" href="{{ route('admin.images.product_images.index') }}">View Images</a>
    
          </div>
        </div>
      </li>
    @endif

  @if(session('user_type') == 1)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.offers.index') }}">
        <i class="fas fa-fw fa-gift"></i>
        <span>All Offers</span></a>
    </li>
  @endif

  @if(session('user_type') == 1)
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.promo.index') }}">
        <i class="fas fa-tags fa-fw"></i>
        <span>Monthly Promo</span></a>
    </li>
  @endif

  @if(session('user_type') == 1)
    <!-- Admin Profile -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.transaction.index') }}">
        <i class="fas fa-credit-card"></i>
        <span>Online Transaction</span></a>
    </li>
  @endif
  
  @if(session('user_type') == 1)
    <!-- Admin Profile -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.review.index') }}">
        <i class="fas fa-comment-dots"></i>
        <span>Customer Review</span></a>
    </li>
  @endif
  
  @if(session('user_type') == 1)
    <!-- Announcement -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAnnouncement"
        aria-expanded="false" aria-controls="collapseAnnouncement">
        <i class="fas fa-fw fa-bullhorn"></i>
        <span>Announcement</span>
      </a>
      <div id="collapseAnnouncement" class="collapse" aria-labelledby="headingAnnouncement" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('admin.announcements.create') }}">Add Announcement</a>
          <a class="collapse-item" href="{{ route('admin.announcement.list') }}">Update Announcement</a>
        </div>
      </div>
    </li>
  @endif

  <li class="nav-item">
    <a class="nav-link" href="{{ route('admin.outlets.index') }}">
      <i class="fas fa-store"></i>
      <span>Outlets</span></a>
  </li>

    <!-- Admin Profile -->
    <li class="nav-item">
      <a class="nav-link" href="{{ route('admin.orders.report.index') }}">
        <i class="fas fa-fw fa-file-alt"></i>
        <span>Report</span></a>
    </li>
    
@if(session('user_type') == 1)
  <!-- Admin Profile -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('admin.customers.index') }}">
      <i class="fas fa-users"></i>
      <span>Customers</span></a>
  </li>
@endif    

@if(session('user_type') == 1)
  <!-- Admin Profile -->
  <li class="nav-item">
    <a class="nav-link" href="/profile">
      <i class="fas fa-fw fa-user"></i>
      <span>Profile</span></a>
  </li>
@endif

   
  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Social Section -->
  <div class="sidebar-social mb-3 px-3">
    <div class="small text-white mb-2 text-center text-md-left">Follow Me On</div>
    <div class="d-flex flex-wrap justify-content-center justify-content-md-start">
      <a href="https://agorasuperstores.com" target="_blank" class="text-white m-2">
        <i class="fas fa-globe fa-lg"></i>
      </a>
      <a href="https://www.facebook.com/AgoraBD" target="_blank" class="text-white m-2">
        <i class="fab fa-facebook-f fa-lg"></i>
      </a>
      <a href="https://www.instagram.com/agora.bd" target="_blank" class="text-white m-2">
        <i class="fab fa-instagram fa-lg"></i>
      </a>
      <a href="https://www.linkedin.com/company/agoralimited" target="_blank" class="text-white m-2">
        <i class="fab fa-linkedin-in fa-lg"></i>
      </a>
    </div>
  </div>

</ul>