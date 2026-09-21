<?php

session_start();

// Check login
if (!isset($_SESSION["member_id"])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "power_gym";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get logged-in member ID
$member_id = $_SESSION["member_id"];

// Get member details
$stmt = $conn->prepare(
    "SELECT id, name, email, phone, username, plan, created_at
     FROM members
     WHERE id = ?"
);

$stmt->bind_param("i", $member_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $member = $result->fetch_assoc();
} else {
    die("Member details not found.");
}

$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Power Gym - My Profile</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
        }

        header {
            background: #111;
            color: white;
            padding: 20px 40px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
            color: #ff3333;
        }

        .dashboard {
            background: #d00000;
            color: white;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 40px auto;
        }

        .profile-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 3px 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #d00000;
            margin-bottom: 30px;
        }

        .profile-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .profile-item:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 180px;
        }

        .value {
            color: #222;
        }

        .logout {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 25px;
            padding: 12px;
            background: #d00000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .logout:hover {
            background: #a00000;
        }

        @media(max-width:600px) {

            header {
                padding: 15px;
            }

            .label {
                display: block;
                width: 100%;
                margin-bottom: 5px;
            }

        }

    </style>

</head>

<body>

<header>

    <h1>POWER GYM</h1>

    <a href="dashboard.php" class="dashboard">
        Dashboard
    </a>

</header>


<div class="container">

    <div class="profile-box">

        <h2>My Profile</h2>


        <div class="profile-item">

            <span class="label">
                Member ID:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["id"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Full Name:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["name"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Email:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["email"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Phone:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["phone"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Username:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["username"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Membership Plan:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["plan"]); ?>
            </span>

        </div>


        <div class="profile-item">

            <span class="label">
                Joined Date:
            </span>

            <span class="value">
                <?php echo htmlspecialchars($member["created_at"]); ?>
            </span>

        </div>


        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</div>

</body>
</html>