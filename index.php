<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADFC Pageant Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background: url('assets/images/pageant-bg.jpg') no-repeat center center/cover;
            font-family: 'Helvetica', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h2 {
            font-family: 'Helvetica', sans-serif;
            font-weight: bold;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
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
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center text-dark mb-4" style="font-size: 2.5rem;">MISTER & MISS ADFC</h2>
        <form action="auth/login.php" method="POST">
            <div class="mb-3">
                <input type="text" id="username" name="username" placeholder="Username" required
                       class="form-control">
            </div>

            <div class="mb-3">
                <input type="password" id="password" name="password" placeholder="Password" required
                       class="form-control">
            </div>

            <div class="mb-3">
                <select id="role" name="role" required class="form-select">
                    <option value="" disabled selected>Login as</option>
                    <option value="admin">Admin</option>
                    <option value="judge">Judge</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Login
            </button>
        </form>
    </div>
</body>
</html>
