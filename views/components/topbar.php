<style>
    /* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 0;
}

/* Ensure content does not overlap with fixed topbar */
.content {
    margin-left: 250px;
    padding: 20px;
    transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 70px; /* Push content down below the fixed topbar */
}

.content.collapsed {
    margin-left: 80px;
}

/* Topbar Styles */
.topbar {
    background: #ffffff; /* White background */
    color: #333333; /* Darker text for contrast */
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* Soft shadow */
    
    /* Fix the topbar at the top */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px; /* Fixed height */
    z-index: 1000; /* Ensure it stays on top */
    border-radius: 0; /* Remove border-radius since it's now fixed */
}

/* Push logout button to the right */
.topbar h4 {
    margin: 0;
    font-weight: 600;
}

.logout-btn {
    background: #ff4b5c;
    border: none;
    padding: 8px 15px;
    color: #ffffff;
    font-weight: bold;
    border-radius: 20px;
    text-decoration: none;
    transition: background 0.3s ease;
    margin-left: auto; /* Keeps the button to the right */
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Soft button shadow */
}

.logout-btn:hover {
    background: #d43f4b;
}

</style>

<div class="topbar">
    <h4></h4>
    <a href="../../auth/logout.php" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> 
        </a>
</div>
