<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="../../assets/image/adflogo.png" alt="logo">
            <h5>MR & MISS ADFC</h5>
        </div>
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i id="toggle-icon" class="fas fa-chevron-left"></i>
        </button>
    </div>

    <div class="sidebar-menu">
    <?php if ($_SESSION['role'] == 'admin') : ?>
    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    <a href="candidates.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'candidates.php' ? 'active' : ''; ?>">
        <i class="fas fa-user-friends"></i> <span>Candidates</span>
    </a>
    <a href="judges.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'judges.php' ? 'active' : ''; ?>">
        <i class="fas fa-gavel"></i> <span>Judges</span>
    </a>
    <a href="criteria.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'criteria.php' ? 'active' : ''; ?>">
        <i class="fas fa-list-ol"></i> <span>Criteria</span>
    </a>
    <a href="results.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'results.php' ? 'active' : ''; ?>">
        <i class="fas fa-chart-line"></i> <span>Results</span>
    </a>
<?php else : ?>
    <a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    <a href="rate.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'rate.php' ? 'active' : ''; ?>">
            <i class="fas fa-gavel"></i> <span>Rating</span>
    </a>
<?php endif; ?>

        
    </div>
</div>


<style>
    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background: #ffffff;
        border-right: 1px solid #e1e5ea;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s ease-in-out;
        z-index: 1001; /* Increased z-index to be above all elements */
        padding-top: 10px;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        border-bottom: 1px solid #e1e5ea;
    }

    .logo-container {
        display: flex;
        align-items: center;
    }

    .logo-container img {
        width: 40px;
        height: 40px;
        margin-right: 10px;
    }

    .logo-container h5 {
        font-size: 1rem;
        margin: 0;
    }

    /* Rounded square button for chevron */
    .toggle-btn {
        background: #ffffff;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        color: #007bff;
        padding: 8px;
        border-radius: 12px; /* Rounded square effect */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: background 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
    }

    .toggle-btn:hover {
        background: #f1f3f5;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        transform: scale(1.05); /* Slight pop effect on hover */
    }

    .sidebar-menu {
        padding: 10px 0;
    }

    .sidebar-menu a {
        text-decoration: none;
        color: #333;
        display: flex;
        align-items: center;
        padding: 12px 15px; /* Increased padding for space between items */
        margin-bottom: 8px; /* Added space between links */
        border-radius: 5px;
        transition: background 0.2s ease, color 0.2s ease;
        font-size: 1rem;
        font-weight: 500;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: #007bff;
        color: #ffffff;
    }

    .sidebar-menu a i {
        margin-right: 10px;
        font-size: 1.1rem;
    }

    /* Centering icons when sidebar is collapsed */
    .sidebar.collapsed .sidebar-menu a {
        justify-content: center;
        padding: 12px 0; /* Adjusted for better alignment */
    }

    .sidebar.collapsed .sidebar-menu a i {
        margin-right: 0;
    }

    .sidebar.collapsed .sidebar-menu a span,
    .sidebar.collapsed .logo-container h5 {
        display: none;
    }

    .sidebar.collapsed .logo-container img {
        margin-right: 0;
    }

    .logout-link {
        margin-top: auto;
        color: #ff4b5c;
    }

    .logout-link:hover {
        background: #ff4b5c;
        color: #ffffff;
    }
</style>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleIcon = document.getElementById('toggle-icon');

        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
            toggleIcon.classList.remove('fa-chevron-left');
            toggleIcon.classList.add('fa-chevron-right');
        } else {
            toggleIcon.classList.remove('fa-chevron-right');
            toggleIcon.classList.add('fa-chevron-left');
        }
    }
</script>
