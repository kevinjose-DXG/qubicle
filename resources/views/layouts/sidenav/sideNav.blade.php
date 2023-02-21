<style>
  .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active{
    background-color:#5189f9 !important;
    color: #343a40 !important;
  }
</style>
<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link" id="dashboard_menu">
            <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="{{route('showSlider')}}" class="nav-link" id="slider_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Slider</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showState')}}" class="nav-link" id="state_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>State</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showDistrict')}}" class="nav-link" id="district_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>District</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showLocation')}}" class="nav-link" id="location_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Location</p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="{{route('showCategory')}}" class="nav-link" id="category_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Category</p>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a href="{{route('showBusinessCategory')}}" class="nav-link" id="business_category_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Business Category</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showDesigned')}}" class="nav-link" id="designed_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Designed By</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showPlan')}}" class="nav-link" id="plan_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Plan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showVendor')}}" class="nav-link" id="vendor_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Vendors</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showPendingVendor')}}" class="nav-link" id="pending_vendor_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Pending Vendor</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showPendingVendorPayment')}}" class="nav-link" id="vendor_payment_approval_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Vendor Payment Approval</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('showVendorFlyer')}}" class="nav-link" id="vendor_flyer_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Pending Flyers</p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="{{route('showCustomer')}}" class="nav-link" id="customer_menu">
                <i class="nav-icon fa fa-clone"></i>
              <p>Customers</p>
            </a>
          </li>
          <!-- <li class="nav-item" id="report_menu">
              <a href="#" class="nav-link">
              <i class="nav-icon far fa-edit"></i>
              <p>Report
                  <i class="fas fa-angle-left right"></i>
              </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ route('transactionReport') }}" class="nav-link" id="transaction_report_menu">
                      <i class="nav-icon fas fa-cash-register"></i>
                      <p>Transaction</p>
                      </a>
                  </li>
              </ul>
          </li> -->
        </ul>
      </nav>