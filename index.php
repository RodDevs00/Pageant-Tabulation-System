<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADFC Pageant Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: url('assets/image/login.jpg') no-repeat center center/cover;
            font-family: 'Helvetica', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
      

        .topbar {
            position: absolute;
            top: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar img {
            height: 50px;
        }

        /* .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin-top: 80px;
        }

        .form-control, .form-select {
            border-radius: 50px;
            padding: 12px;
            font-size: 1.1rem;
            border: 1px solid #ddd;
        }

        .form-control:focus, .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        }

        .btn-primary {
            background: #007bff;
            border: none;
            border-radius: 50px;
            padding: 12px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: scale(1.05);
        } */
    </style>
    
</head>
<body>
<div class="topbar">
        <img src="assets/image/adflogo.png" alt="ADFC Logo">
    </div>
<div class="login-container">
    <img src="assets/image/adflogo.png" style="height: 200px; margin: 0 auto 30px; display: block;" alt="ADFC Logo">
    <form action="auth/login.php" method="POST">
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" id="username" name="username" placeholder="Username" required class="form-control form-control-lg">
            </div>
        </div>

        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password" placeholder="Password" required class="form-control form-control-lg">
            </div>
        </div>

        <div class="mb-4">
            <select id="role" name="role" required class="form-select form-select-lg">
                <option value="" disabled selected>Login as</option>
                <option value="admin">Admin</option>
                <option value="judge">Judge</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
    </form>
</div>

<style>
.login-container {
    background: rgba(255, 255, 255, 0.95);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* More prominent shadow */
    width: 100%;
    max-width: 400px;
    text-align: center;
}

.form-control, .form-select {
    border-radius: 8px; /* Slightly less rounded */
    padding: 14px; /* Larger padding */
    font-size: 1.1rem;
    border: 1px solid #e0e0e0;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.25); /* More prominent focus shadow */
}

.btn-primary {
    background: linear-gradient(135deg, #63a4ff, #007bff); /* Gradient background */
    border: none;
    border-radius: 8px; /* Slightly less rounded */
    padding: 14px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: transform 0.2s ease, box-shadow 0.2s ease; /* Smooth transitions */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.btn-primary:hover {
    transform: translateY(-2px); /* Slight lift on hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Increased shadow on hover */
}

.input-group-text {
    border-radius: 8px 0 0 8px;
    background-color: #f8f9fa;
    border-right: none;
    border: 1px solid #e0e0e0;
}

.input-group-text i {
    color: #555;
}

.mb-4 {
    margin-bottom: 25px; /* Increased margin */
}

img {
    display: block; /* Ensure image is a block element */
}
</style>

<link rel="stylesheet" href="assets/css/fontawesome.min.css">
<link rel="stylesheet" href="assets/css/all.min.css">
</style>
</body>
</html>
