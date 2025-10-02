

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CRM Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      display: flex;
    }

    .sidebar {
  width: 260px;
  background: #1f2937;
  color: #ffffff;
  height: 100vh;              /* Full height */
  position: fixed;
  top: 0;
  left: 0;
  display: flex;
  flex-direction: column;     /* Keep logo on top, logout at bottom */
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
  transition: all 0.3s;
  overflow-y: auto;           /* ✅ scroll inside sidebar */
  padding: 1.5rem 0;          /* top/bottom padding */
}

.sidebar ul {
  flex: 1;                    /* Take all available space */
  padding: 0 1.5rem;
}

    .logo {
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 2rem;
      text-align: center;
      letter-spacing: 1px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      margin: 1rem 0;
    }

    .sidebar ul li a {
      color: #cbd5e1;
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      padding: 0.6rem 1rem;
      border-radius: 8px;
      transition: 0.3s;
    }

    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background: #374151;
      color: #ffffff;
    }

    .sidebar ul li a svg {
      margin-right: 10px;
      stroke-width: 1.8;
    }

    .main {
      margin-left: 260px;
      padding: 2rem;
      width: 100%;
    }

    header h1 {
      font-size: 2rem;
      color: #1f2937;
      margin-bottom: 1.5rem;
    }

    .content .card {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .content .card h3 {
      margin-bottom: 0.5rem;
    }

    .content .card p {
      color: #6b7280;
    }


/* Reset */
ul { list-style: none; padding: 0; margin: 0; }
ul li { position: relative; }

.logo {
  font-size: 20px;
}

/* Dropdown styles */
.dropdown > a {
  display: flex;
  align-items: center;       /* keeps icon + text vertically aligned */
  justify-content: flex-start; /* align everything to the left */
  cursor: pointer;
  padding: 8px 0px;
  text-decoration: none;
  color: #333;
  font-weight: 500;
}

/* Arrow style */
.dropdown > a .arrow {
  margin-left: auto; /* small gap between text and arrow */
  font-size: 12px;
  transition: transform 0.3s ease;
}
/* Dropdown menu hidden by default */
.dropdown-menu {
  display: none;
  padding-left: 20px;
  margin: 0;
}

.dropdown-menu li a {
  display: block;
  padding: 6px 10px;
  color: #444;
  text-decoration: none;
  font-size: 14px;
}

.dropdown-menu li a:hover {
  color: #000;
  font-weight: 500;
}

/* Show menu when open */
.dropdown.open .dropdown-menu {
  display: block;
}

/* Rotate arrow when open */
.dropdown.open .arrow {
  transform: rotate(180deg);
}


.logout-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 15px;
  margin: 15px;
  background: #00b4d8;   /* cyan tone */
  color: #fff;
  font-weight: 500;
  border-radius: 6px;
  text-decoration: none;
  transition: background 0.3s ease, transform 0.2s ease;
}

.logout-btn:hover {
  background: #0096c7;   /* darker cyan on hover */
  transform: translateY(-2px);
}

  </style>
</head>
<body>

  <!-- Sidebar -->

    <aside class="sidebar">
    <div class="top">
      <div class="logo">Qatar Gold Prices</div>
      <ul>
        <!-- <li><a href="/#" class="active"><i data-lucide="layout-dashboard"></i>Dashboard</a></li> -->
      <!-- Currency -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
<i data-lucide="circle-dollar-sign"></i>Currencies
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_currency.php">Add Currency</a></li>
      <li><a href="view_currency.php">View Currency</a></li>
    </ul>
  </li>   
  <!-- Purity -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="layers"></i>Purities 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_purity.php">Add Purity</a></li>
      <li><a href="view_purity.php">View Purity</a></li>
    </ul>
  </li>   
  <!-- Weights -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="scale"></i>Weights 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_weight.php">Add Weight</a></li>
      <li><a href="view_weight.php">View Weight</a></li>
    </ul>
  </li> 
   <!-- Cities -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="building-2"></i>Cities 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_city.php">Add City</a></li>
      <li><a href="view_city.php">View Cities</a></li>
    </ul>
  </li>   
  
   <!-- Users -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="user"></i>Users 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="view_user.php">View Users</a></li>
    </ul>
  </li>      
        <!-- Gold -->
        <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="coins"></i>Gold Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_gold.php">Add Rate</a></li>
      <li><a href="view_gold.php">View Rates</a></li>
    </ul>
  </li>
   
  <!-- Silver -->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="badge-dollar-sign"></i>Silver Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_silver.php">Add Rate</a></li>
      <li><a href="view_silver.php">View Rates</a></li>
    </ul>
  </li>
  <!-- Diamond -->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="gem"></i>Diamond Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_diamond.php">Add Rate</a></li>
      <li><a href="view_diamond.php">View Rates</a></li>
    </ul>
  </li>
  <!-- Platinum-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="medal"></i>Platinum Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_platinum.php">Add Rate</a></li>
      <li><a href="view_platinum.php">View Rates</a></li>
    </ul>
  </li>
  <!--Emerald-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="leaf"></i>Emerald Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_emerald.php">Add Rate</a></li>
      <li><a href="view_emerald.php">View Rates</a></li>
    </ul>
  </li>
  <!-- Ruby-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="flame"></i>Ruby Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_ruby.php">Add Rate</a></li>
      <li><a href="view_ruby.php">View Rates</a></li>
    </ul>
  </li>
  <!-- Sapphire-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="droplet"></i>Sapphire Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_sapphire.php">Add Rate</a></li>
      <li><a href="view_sapphire.php">View Rates</a></li>
    </ul>
  </li>
   <!-- Pearl-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="circle"></i>Pearl Rates 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
      <li><a href="add_pearl.php">Add Rate</a></li>
      <li><a href="view_pearl.php">View Rates</a></li>
    </ul>
  </li>
  <!-- 24 Carat Gold-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="shapes"></i>24 Carat Gold 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
    
      <li><a href="view_24k_gold.php">View Rates</a></li>
    </ul>
  </li>
   <!-- 22 Carat Gold-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="shapes"></i>22 Carat Gold 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">

      <li><a href="view_22k_gold.php">View Rates</a></li>
    </ul>
  </li>
  <!-- 21 Carat Gold-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="shapes"></i>21 Carat Gold 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
    
      <li><a href="view_21k_gold.php">View Rates</a></li>
    </ul>
  </li>
   <!-- 18 Carat Gold-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="shapes"></i>18 Carat Gold 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
   
      <li><a href="view_18k_gold.php">View Rates</a></li>
    </ul>
  </li>
   <!-- 14 Carat Gold-->
  <li class="dropdown">
    <a href="javascript:void(0);" class="dropdown-toggle">
      <i data-lucide="shapes"></i>10 Carat Gold 
      <span class="arrow">▼</span>
    </a>
    <ul class="dropdown-menu">
   
      <li><a href="view_10k_gold.php">View Rates</a></li>
    </ul>
  </li>
  
  <li>
     <a href="../logout.php" class="logout-btn">
  <i data-lucide="log-out"></i> Logout
</a>
  </li>


        <!-- <li><a href="clients.php"><i data-lucide="user-check"></i>Clients</a></li> -->
        <!-- <li><a href="projects.php"><i data-lucide="briefcase"></i>Projects</a></li> -->
        <!-- <li><a href="invoices.php"><i data-lucide="file-text"></i>Invoices</a></li> -->
        <!-- <li><a href="accounting.php"><i data-lucide="credit-card"></i>Accounting</a></li> -->
        <!-- <li><a href="suppliers.php"><i data-lucide="truck"></i>Suppliers</a></li> -->
        <!-- <li><a href="analytics.php"><i data-lucide="bar-chart-2"></i>Analytics</a></li> -->
      </ul>
   </div>
       </aside>

<style>
</style>


  
     

    <!-- End here -->

  

    </div>
    <!-- <div class="profile">
      <img src="https://i.pravatar.cc/100?img=15" alt="User Avatar">
      <p>Admin</p>
    </div> -->
    

<style>
</style>





  <!-- <div class="sidebar">
    <h2 class="logo">🚀 CRM</h2>
    <ul>
      <li><a href="dashboard.php" class="active"><i data-lucide="layout-dashboard"></i> Dashboard</a></li>
      <li><a href="leads.php"><i data-lucide="users"></i> Leads</a></li>
      <li><a href="clients.php"><i data-lucide="user-check"></i> Clients</a></li>
      <li><a href="projects.php"><i data-lucide="briefcase"></i> Projects</a></li>
      <li><a href="invoices.php"><i data-lucide="file-text"></i> Invoices</a></li>
      <li><a href="accounting.php"><i data-lucide="credit-card"></i> Accounting</a></li>
      <li><a href="suppliers.php"><i data-lucide="truck"></i> Suppliers</a></li>
      <li><a href="analytics.php"><i data-lucide="bar-chart-2"></i> Analytics</a></li>
    </ul>
  </div> -->

  <!-- Main Content -->
  <!-- <div class="main">
    <header>
      <h1>Dashboard</h1>
    </header>
    <section class="content">
      <div class="card">
        <h3>Welcome to Your Renovation CRM</h3>
        <p>Manage leads, clients, projects, invoices, and more with ease.</p>
      </div>
    </section>
  </div> -->

  <script>
    lucide.createIcons();
  </script>
</body>
</html>