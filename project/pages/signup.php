<?php
// Include the user model
require '../controllers/signup_user.php';
require '../controllers/helpers/utils.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission
    $username = $_POST['username'] ?? '';
    $employee_id = $_POST['employee_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $security_question = $_POST['security_question'] ?? '';
    $security_answer = $_POST['security_answer'] ?? '';
    $employee_id = htmlspecialchars($_POST['employee_id']);
    $errors = [];

    if (strlen($username) < 4) {
        $errors['username'] = "Username must be at least 4 characters long.";
    }

    if (!filter_var($employee_id) || $employee_id < 0) {
        $errors['employee_id'] = 'Please enter a valid Employee ID.';
    }

    if (strlen($password) < 8 || !validatePassword($password)) {
        $errors['password'] = 'Password must be at least 8 characters long and contain at least one capital letter, one special character, and one number.';
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if (empty($security_question)) {
        $errors['security_question'] = 'Please select a security question.';
    }

    if (empty($security_answer)) {
        $errors['security_answer'] = 'Please provide an answer to the security question.';
    }

    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create a new instance of UserModel
        $userModel = new UserModel();
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Check if user creation is successful
        $userCreated = $userModel->createUser($username, $employee_id, $hashedPassword, $ipAddress, $security_question, $security_answer);

        if ($userCreated) {
            // Redirect to the sign-in page
            header('Location: signin.php');
            exit;
        } else {
            // Handle username or employee ID already exists error
            $errors['username'] = 'Username or Employee ID already exists, Please choose another username or Sign In';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/main.css" rel="stylesheet">
</head>

<body style="background-color: #E0FBE2">
    <div class="logo-container">
        <img src="../assets/logo.png">
    </div>
    <div class="image-container1">
        <img src="../assets/signup.png">
    </div>
    <div class="signin-container">
        <div class="row justify-content-center">
            <div class="col-md-4" style="margin-left: 45%; margin-top: -33%">
                <h2 class="text-center mt-3 "
                    style="color: #365E32; font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 40px; font-weight: bold">
                    <strong>Sign Up</strong></h2>
                <form method="post" action="signup.php" novalidate>
                    <div class="form-group">
                        <label for="username" style="color: #31363F; font-family: monospace">Username:</label>
                        <div class="input-group">
                            <input type="text" style="border-color: #365E32; border-radius: 10px 0px 0px 10px"
                                class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username"
                                name="username" required minlength="4" value="<?= htmlspecialchars($username ?? '') ?>">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password"
                                    style="background-color: #365E32; border-color: #365E32; border-radius: 0px 10px 10px 0px">
                                    <i class="fas fa-user" style="color: white"></i>
                                </span>
                            </div>
                            <?php if (isset($errors['username'])): ?>
                                <div class="invalid-feedback"><?= $errors['username'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="employee_id" style="color: #31363F; font-family: monospace">Employee ID:</label>
                        <div class="input-group">
                            <input type="number" style="border-color: #365E32; border-radius: 10px 0px 0px 10px"
                                class="form-control <?= isset($errors['employee_id']) ? 'is-invalid' : '' ?>"
                                id="employee_id" name="employee_id" required min="0" max="1000"
                                value="<?= htmlspecialchars($employee_id ?? '') ?>">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password"
                                    style="background-color: #365E32; border-color: #365E32; border-radius: 0px 10px 10px 0px">
                                    <i class="fas fa-id-badge" style="color: white"></i>
                                </span>
                            </div>
                            <?php if (isset($errors['employee_id'])): ?>
                                <div class="invalid-feedback"><?= $errors['employee_id'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" style="color: #31363F; font-family: monospace">Password:</label>
                        <div class="input-group">
                            <input type="password" style="border-color: #365E32; border-radius: 10px 0px 0px 10px"
                                class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password"
                                name="password" required minlength="8">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password"
                                    style="background-color: #365E32; border-color: #365E32; border-radius: 0px 10px 10px 0px">
                                    <i class="fas fa-lock" style="color: white"></i>
                                </span>
                            </div>
                            <?php if (isset($errors['password'])): ?>
                                <div class="invalid-feedback"><?= $errors['password'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password" style="color: #31363F; font-family: monospace">Confirm
                            Password:</label>
                        <div class="input-group">
                            <input type="password" style="border-color: #365E32; border-radius: 10px 0px 0px 10px"
                                class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                id="confirm_password" name="confirm_password" required>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password"
                                    style="background-color: #365E32; border-color: #365E32; border-radius: 0px 10px 10px 0px">
                                    <i class="fas fa-lock" style="color: #E7D37F"></i>
                                </span>
                            </div>
                            <?php if (isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback"><?= $errors['confirm_password'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="security_question" style="color: #31363F; font-family: monospace">Security
                            Question:</label>
                        <select class="form-control <?= isset($errors['security_question']) ? 'is-invalid' : '' ?>"
                            id="security_question" name="security_question"
                            style="border-color: #365E32; border-radius: 10px" required>
                            <option value="">Select a security question</option>
                            <option value="favorite_teacher" <?= isset($security_question) && $security_question == 'favorite_teacher' ? 'selected' : '' ?>>Who is your favorite
                                Teacher?</option>
                            <option value="favorite_subject" <?= isset($security_question) && $security_question == 'favorite_subject' ? 'selected' : '' ?>>What is your
                                favorite
                                subject?</option>
                            <option value="favorite_sport" <?= isset($security_question) && $security_question == 'favorite_sport' ? 'selected' : '' ?>>What is your favorite
                                sport?
                            </option>
                            <option value="favorite_singer" <?= isset($security_question) && $security_question == 'favorite_singer' ? 'selected' : '' ?>>Who is your favorite
                                singer?
                            </option>
                        </select>
                        <?php if (isset($errors['security_question'])): ?>
                            <div class="invalid-feedback"><?= $errors['security_question'] ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="security_answer" style="color: #31363F; font-family: monospace">Security
                            Answer:</label>
                        <input type="text" style="border-color: #365E32; border-radius: 10px"
                            class="form-control <?= isset($errors['security_answer']) ? 'is-invalid' : '' ?>"
                            id="security_answer" name="security_answer" required
                            value="<?= htmlspecialchars($security_answer ?? '') ?>">
                        <?php if (isset($errors['security_answer'])): ?>
                            <div class="invalid-feedback"><?= $errors['security_answer'] ?></div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block"
                        style="background-color: #365E32; border: none; font-weight: bolder; border-radius: 10px">Sign
                        Up</button>
                </form>
                <div class="text-center mt-3">
                    <p>Already Registered? <a href="signin.php" class="btn btn-link">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="../bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../bootstrap/cdnall.min.js"></script>
</body>

</html>