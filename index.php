<?php

session_start();

/* =========================
   DATABASE CONNECTION
========================= */

$host = "localhost";
$dbUser = "root";
$dbPassword = "";
$database = "power_gym";

$conn = new mysqli($host, $dbUser, $dbPassword, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$message = "";
$messageType = "";


/* =========================
   REGISTER
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $username = trim($_POST["reg_username"]);
    $regPassword = $_POST["reg_password"];
    $confirmPassword = $_POST["confirm_password"];
    $plan = $_POST["plan"];


    // Check passwords
    if ($regPassword !== $confirmPassword) {

        $message = "Passwords do not match!";
        $messageType = "error";

    } else {

        // Check username already exists
        $check = $conn->prepare(
            "SELECT id FROM members WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();

        $result = $check->get_result();


        if ($result->num_rows > 0) {

            $message = "Username already exists!";
            $messageType = "error";

        } else {

            // Securely hash password
            $hashedPassword = password_hash(
                $regPassword,
                PASSWORD_DEFAULT
            );


            // Insert member
            $sql = "INSERT INTO members
                    (name, email, phone, username, password, plan)
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ssssss",
                $name,
                $email,
                $phone,
                $username,
                $hashedPassword,
                $plan
            );


            if ($stmt->execute()) {

                $message = "Registration successful! Please login.";
                $messageType = "success";

            } else {

                $message = "Registration failed: " . $stmt->error;
                $messageType = "error";
            }

            $stmt->close();
        }

        $check->close();
    }
}


/* =========================
   LOGIN
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

    $username = trim($_POST["username"]);
    $loginPassword = $_POST["password"];


    // Find username
    $stmt = $conn->prepare(
        "SELECT id, name, username, password, plan
         FROM members
         WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows == 1) {

        $member = $result->fetch_assoc();


        // Check hashed password
        if (
            !empty($member["password"]) &&
            password_verify(
                $loginPassword,
                $member["password"]
            )
        ) {

            // Login successful
            $_SESSION["member_id"] = $member["id"];
            $_SESSION["name"] = $member["name"];
            $_SESSION["username"] = $member["username"];
            $_SESSION["plan"] = $member["plan"];


            /*
             * Dashboard page should exist.
             */
            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Invalid password!";
            $messageType = "error";
        }

    } else {

        $message = "Username not found!";
        $messageType = "error";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Power Gym - Login & Registration</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: Arial, sans-serif;

            background: linear-gradient(
                135deg,
                #111111,
                #d00000
            );

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .container {

            width: 400px;

            max-width: 95%;

            background: white;

            padding: 30px;

            border-radius: 15px;

            box-shadow:
                0 10px 30px
                rgba(0, 0, 0, 0.4);
        }


        h1 {

            text-align: center;

            color: #d00000;

            margin: 0 0 5px;
        }


        .subtitle {

            text-align: center;

            color: #666;

            margin-bottom: 25px;
        }


        h2 {

            margin-bottom: 20px;

            color: #222;
        }


        .form-box {

            display: none;
        }


        .form-box.active {

            display: block;
        }


        label {

            display: block;

            margin-top: 12px;

            font-weight: bold;

            color: #222;
        }


        input,
        select {

            width: 100%;

            padding: 11px;

            margin-top: 6px;

            border: 1px solid #ccc;

            border-radius: 6px;

            font-size: 15px;
        }


        input:focus,
        select:focus {

            outline: none;

            border-color: #d00000;
        }


        button {

            width: 100%;

            padding: 12px;

            margin-top: 20px;

            border: none;

            border-radius: 6px;

            background: #d00000;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;
        }


        button:hover {

            background: #a00000;
        }


        .switch {

            text-align: center;

            margin-top: 18px;

            color: #555;
        }


        .switch span {

            color: #d00000;

            font-weight: bold;

            cursor: pointer;
        }


        .message {

            text-align: center;

            padding: 10px;

            margin-bottom: 15px;

            border-radius: 5px;

            font-weight: bold;
        }


        .success {

            background: #d4edda;

            color: #155724;
        }


        .error {

            background: #f8d7da;

            color: #721c24;
        }


        .gym-text {

            text-align: center;

            margin-top: 20px;

            font-size: 13px;

            color: #777;
        }

    </style>

</head>


<body>


<div class="container">


    <h1>POWER GYM</h1>

    <div class="subtitle">
        Build Your Body, Build Your Confidence
    </div>


    <!-- MESSAGE -->

    <?php if ($message != "") { ?>

        <div class="message <?php echo $messageType; ?>">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- =====================
         LOGIN
    ====================== -->

    <div id="loginBox" class="form-box active">

        <h2>Member Login</h2>


        <form method="POST">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                placeholder="Enter username"
                required
            >


            <label for="loginPassword">
                Password
            </label>

            <input
                type="password"
                id="loginPassword"
                name="password"
                placeholder="Enter password"
                required
            >


            <button type="submit" name="login">
                LOGIN
            </button>

        </form>


        <div class="switch">

            Don't have an account?

            <span onclick="showRegister()">
                Register
            </span>

        </div>

    </div>


    <!-- =====================
         REGISTER
    ====================== -->

    <div id="registerBox" class="form-box">

        <h2>Member Registration</h2>


        <form
            method="POST"
            onsubmit="return validateRegister()"
        >


            <label for="name">
                Full Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your name"
                required
            >


            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter email"
                required
            >


            <label for="phone">
                Phone Number
            </label>

            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="Enter 10-digit phone number"
                maxlength="10"
                required
            >


            <label for="reg_username">
                Username
            </label>

            <input
                type="text"
                id="reg_username"
                name="reg_username"
                placeholder="Create username"
                required
            >


            <label for="reg_password">
                Password
            </label>

            <input
                type="password"
                id="reg_password"
                name="reg_password"
                placeholder="Create password"
                required
            >


            <label for="confirm_password">
                Confirm Password
            </label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Confirm password"
                required
            >


            <label for="plan">
                Membership Plan
            </label>

            <select
                id="plan"
                name="plan"
                required
            >

                <option value="">
                    Select Plan
                </option>

                <option value="Basic - 1 Month">
                    Basic - 1 Month
                </option>

                <option value="Premium - 3 Months">
                    Premium - 3 Months
                </option>

                <option value="Premium - 6 Months">
                    Premium - 6 Months
                </option>

                <option value="VIP - 1 Year">
                    VIP - 1 Year
                </option>

            </select>


            <button
                type="submit"
                name="register"
            >
                REGISTER
            </button>

        </form>


        <div class="switch">

            Already have an account?

            <span onclick="showLogin()">
                Login
            </span>

        </div>

    </div>


    <div class="gym-text">
        Welcome to Power Gym
    </div>


</div>


<script>


/* =====================
   SHOW REGISTER
===================== */

function showRegister() {

    document
        .getElementById("loginBox")
        .classList.remove("active");

    document
        .getElementById("registerBox")
        .classList.add("active");
}


/* =====================
   SHOW LOGIN
===================== */

function showLogin() {

    document
        .getElementById("registerBox")
        .classList.remove("active");

    document
        .getElementById("loginBox")
        .classList.add("active");
}


/* =====================
   REGISTER VALIDATION
===================== */

function validateRegister() {

    let password =
        document.getElementById("reg_password").value;

    let confirmPassword =
        document.getElementById("confirm_password").value;

    let phone =
        document.getElementById("phone").value;


    if (password.length < 6) {

        alert("Password must contain at least 6 characters.");

        return false;
    }


    if (password !== confirmPassword) {

        alert("Passwords do not match!");

        return false;
    }


    if (!/^[0-9]{10}$/.test(phone)) {

        alert("Please enter a valid 10-digit phone number.");

        return false;
    }


    return true;
}

</script>


</body>

</html>