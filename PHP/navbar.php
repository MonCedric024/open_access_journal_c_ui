
<?php 

session_start();
require_once 'dbcon.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('./meta.php'); ?>
<title>Navbars</title>
<link rel="stylesheet" href="../CSS/navbar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body>

<nav class="navbar navbar-expand-xl" style="padding:4px 1%" id="navbar-container" >
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="background-color: white; font-size:10px;">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex gap-2">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="about.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            About Us
          </a>
          <ul class="dropdown-menu">
          <li><a class="dropdown-item" style="color: black" href="general-info.php">General Information</a></li>
          <li><a class="dropdown-item" style="color: black" href="developers.php">The Developers</a></li>
          <!-- <li><a class="dropdown-item" style="color: black" href="contact-us.php">Contact us</a></li> -->
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="about.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Browse
          </a>
          <ul class="dropdown-menu">
          <li><a class="dropdown-item" style="color: black" href="browse-articles.php">Articles</a></li>
          <li><a class="dropdown-item" style="color: black" href="publication.php">Journals</a></li>
          <!-- <li><a class="dropdown-item" style="color: black" href="contact-us.php">Contact us</a></li> -->
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="announcement.php">Announcements</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="about.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Resources
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" style="color: black" href="./tutorials.php">Tutorials for Contributors</a></li>
            <li><a class="dropdown-item" style="color: black" href="./guidelines.php#templates-for-author">Templates for Author</a></li>
            <li><a class="dropdown-item" style="color: black" href="./guidelines.php">Guidelines and Policy</a></li>
            <li><a class="dropdown-item" style="color: black" href="./faqs.php">Frequently Asked Questions</a></li>
          </ul>
          <li class="d-flex d-sm-none nav-item">
            <a class="nav-link" href="donation.php">Donation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact-us.php">Contact Us</a>
          </li>
      </ul>
      <a class="nav-item dropdown rounded mr-3" style="padding:0.5em 1em;margin-right:1em" href="article-checker.php" >Find a Journal</a>
    <?php
    if (!isset($_SESSION['LOGGED_IN']) || $_SESSION['LOGGED_IN'] !== true) {
        echo '
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown" id="navlogin">
                <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Log-in
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end" id="login-register">
                    <li><a class="dropdown-item" style="color: black" href="login.php" >Log-in</a></li>
                    <li><a class="dropdown-item" style="color: black" href="signup.php">Register</a></li>
                </ul>
            </li>
        </ul>
        ';
    }
    ?>

      </div>
      </div>

<?php
  if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
    $author_id = $_SESSION['id'];
    $email = $_SESSION['email'];

    $query = "SELECT verify.expires, author.email_verified FROM verify JOIN author ON verify.email = author.email WHERE author.email = :email ORDER BY verify.id DESC LIMIT 1";
    $vars = array(':email' => $email);
    $latest_verification = database_run($query, $vars);

    if ($latest_verification && count($latest_verification) > 0) {
        $latest_verification = $latest_verification[0];
        $expiration_timestamp = $latest_verification->expires;
        $current_timestamp = time();

        
        $expiration_date = date('Y-m-d H:i:s', $expiration_timestamp);
        $current_date = date('Y-m-d H:i:s', $current_timestamp);
        // echo $expiration_date;
        // echo '<br>';
        // echo $current_date;
     
        if ($expiration_timestamp < $current_timestamp && !empty($latest_verification->email_verified)) {
            $update_query = "UPDATE author SET email_verified = '' WHERE email = :email";
            $update_vars = array(':email' => $email);
            database_run($update_query, $update_vars);
            session_destroy();
            exit;
        }
    }

    date_default_timezone_set('Asia/Manila');

    // Define a function to format the time elapsed
    function formatTimeElapsed($timeElapsed) {
        if ($timeElapsed < 60) {
            return $timeElapsed . ' seconds ago';
        } elseif ($timeElapsed < 3600) {
            $minutes = floor($timeElapsed / 60);
            return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
        } elseif ($timeElapsed < 86400) {
            $hours = floor($timeElapsed / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } else {
            $days = floor($timeElapsed / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        }
    }
    
  // Notification button and count
  echo '
    <div class="btn-group">
        <button type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" id="notification-button">
            <i class="fas fa-bell"></i>';

    // SQL to count new notifications
    $sqlCountNotif = "SELECT title FROM `notification` WHERE `author_id` = :author_id AND `read_user` = 0 AND title NOT IN ('Submit Article')";
    $paramsCount = array(':author_id' => $author_id);
    $countResult = database_run($sqlCountNotif, $paramsCount);
    
    if ($countResult !== false && isset($countResult[0]->title)) {
        $notificationCount = $countResult[0]->title;
        if ($notificationCount > 0) {
            echo '<span id="notification-count" style="width: 10px; height: 10px; font-size: 10px; text-align: center; display: inline-block; line-height: 5px;">' . count($countResult) . '</span>';
            
        } else {
            echo '<span id="notification-count" style="display: none"></span>';
        }
    } else {
        echo '<span id="notification-count" style="display: none"></span>';
    }

    echo '
        </button>
        <ul class="dropdown-menu" id="notification-dropdown">';

    // SQL to fetch notification list
    // $sqlNotif = "SELECT * FROM `notification` WHERE `author_id` = :author_id AND title NOT IN ('Send Donation', 'Submit Article') ORDER BY `created` DESC";
    $sqlNotif = "SELECT n.*, a.status as article_status FROM `notification` n JOIN `article` a ON n.article_id = a.article_id WHERE n.`author_id` = :author_id 
    AND n.title NOT IN ('Send Donation', 'Submit Article') AND a.status <> 0 ORDER BY n.`created` DESC";
    $paramsNotif = array(':author_id' => $author_id);
    $sqlNotifRun = database_run($sqlNotif, $paramsNotif);
    
   
    if ($sqlNotifRun !== false) {
      foreach ($sqlNotifRun as $notif) {
       
        $bgColor = ($notif->read_notif_list == 0) ? 'background-color: #E5E7E9;' : '';
    
        // Convert created timestamp to the server's time zone
        $createdTimestamp = strtotime($notif->created);
        // Calculate the time elapsed
        $currentTime = time();
        $timeElapsed = $currentTime - $createdTimestamp;
        // Format the time elapsed
        $elapsedText = formatTimeElapsed($timeElapsed);
    
        // Determine the article link based on conditions
        if ($notif->title === "Assign for review" && $notif->article_id !== $author_id) {
          $articleLink = './review-process.php?id=' . $notif->article_id;
        } else if ($notif->title === "Intent to Cite Your Article") {
          $articleLink = './article-details.php?articleId=' . $notif->article_id;
        } else {
          $articleLink = './submitted-article.php?id=' . $notif->article_id;
        }
    
        // Output the notification HTML with background color
        echo '
        <li class="notification-item" data-notification-id="' . $notif->id . '" style="' . $bgColor . '">
          <div>
            <p class="d-flex flex-column " style="font-weight: bold; margin-bottom: 5px;"> '  . $notif->title . '
          <div>
          <div>
            <a id="inviteMessage" style="text-decoration: none; color: gray; display: block; padding-bottom: 5px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;" href="' . $articleLink . '">' . $notif->description . '</a>
            <span style="font-weight: bold; color: #004e98;">' . $elapsedText . '</span>
          </div>
        </li>';
    }
    
    } else {
        echo '<p class="h6" style="color: gray; font-weight: normal; margin-left: 10px" >0 Notification</p>';
    }

    // User profile dropdown
    $sqlUserName = "SELECT first_name FROM author WHERE author_id = :author_id";
    $sqlRunName = database_run($sqlUserName, array(':author_id' => $author_id));

    if ($sqlRunName !== false && isset($sqlRunName[0]->first_name)) {
        $userName = ucfirst($sqlRunName[0]->first_name);
        echo '
        </ul>
        </div>
        <div class="profile px-4">
            <a id="user-profile" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                ' . $userName . '
            </a>
            <li class="dropdown" style="list-style-type: none;">
                <ul class="dropdown-menu" id="account-dropdown">';

                // User dashboard links based on role
                if ($_SESSION['role'] === 'Admin') {
                    if (!isset($_SESSION['journal_id']) || $_SESSION['journal_id'] === null || $_SESSION['journal_id'] == 0) {
                        echo '<li><a href="../Admin/php/dashboard.php" class="dropdown-item" style="color: black;">Admin Dashboard</a></li>';
                    }
                    echo '<li><a class="dropdown-item" href="../PHP/logout.php" style="color: black;">Log-out</a></li>';
                } elseif ($_SESSION['role'] === 'Assistant Admin') {
                    echo '
                    <li><a href="../Admin/php/dashboard.php" class="dropdown-item" style="color: black;">Admin Dashboard</a></li>
                    <li><a href="user-dashboard.php" class="dropdown-item" style="color: black;">My Profile</a></li>
                    <li><a href="author-dashboard.php" class="dropdown-item" style="color: black;">My Contributions</a></li>
                    <li><a href="change_pass.php" class="dropdown-item" style="color: black;">Manage Password</a></li>';
                    echo '<li><a class="dropdown-item" href="../PHP/logout.php" style="color: black;">Log-out</a></li>';
                } else {
                    echo '
                    <li><a href="user-dashboard.php" class="dropdown-item" style="color: black;">My Profile</a></li>
                    <li><a href="author-dashboard.php" class="dropdown-item" style="color: black;">My Contributions</a></li>
                    <li><a href="change_pass.php" class="dropdown-item" style="color: black;">Manage Password</a></li>';
                    echo '<li><a class="dropdown-item" href="../PHP/logout.php" style="color: black;">Log-out</a></li>';
                }

            echo '
              </ul>
          </li>
      </div>';
    }
  }
?>
</nav>




    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="../JS/navbar.js"></script>


</body>
</html>
