<?php
require_once 'dbcon.php';
session_start();


// if (!isset($_SESSION['LOGGED_IN']) || $_SESSION['LOGGED_IN'] !== true) {
// 	header('Location: ./login.php');
// 	exit();
//   }

$orcid = isset($_GET['orcid']) ? $_GET['orcid'] : '';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('./meta.php'); ?>
    <title>QCUJ | AUTHOR PROFILE</title>
    <link rel="stylesheet" href="../CSS/user-dashboard.css">
    <link rel="stylesheet" href="../CSS/user-view.css">
    <link rel="stylesheet" href="../CSS/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400&display=swap">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="header-container" id="header-container">
            <!-- header will be display here by fetching reusable files -->
    </div>

    <nav class="navigation-menus-container" id="navigation-menus-container">
            <!-- navigation menus will be display here by fetching reusable files -->
    </nav>


    <!-- <div class="content-over">
        <div class="cover-content">
            <p>Dashboard / Author</p>
            <h2>
                You're in 
                <?php 
               
                $result = database_run("SELECT first_name FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                
                if ($result !== false && !empty($result)) {
                    $first_name = $result[0]->first_name;
                    echo "$first_name's Profile";
                } else {
                    echo "";
                }
                ?>
            </h2>

            
    
        </div>
    </div> -->
    <div class="main">
        <div class="main-profile">
			<div class="profile-container">
				<div class="profile-sidebar">
					<!-- Profile Image -->
					
                    <?php
                    

                    
                    $sqlGetProfilePic = "SELECT profile_pic FROM author WHERE orc_id = :orc_id";
                    $params = array(':orc_id' => $orcid);
                    $result = database_run($sqlGetProfilePic, $params);
                    
                    if ($result !== false && !empty($result[0]->profile_pic)) {
                        $profilePicPath = $result[0]->profile_pic;
                    
                        echo '<img src="' . $profilePicPath . '" alt="Profile Picture" class="profile-pic">';
                    } else {
                        echo '<div class="profile-pic-container">';
                        echo '<img src="../images/profile.jpg" alt="Profile Picture" class="profile-pic" id="profileImage">';
                        echo '</div>';
                    }
                    ?>
				</div>
				<div class="profile-info">
					<div class="row">
						<!--<div class="points">
							<p><span class="label">PHP:</span> 1.00 / Credit</p>
						</div>-->
							<!-- User Info -->
						<div class="user-info">
							<h1>
                                <?php 
                                    $result = database_run("SELECT first_name, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                                    
                                    if ($result !== false && !empty($result)) {
                                        $first_name = $result[0]->first_name;
                                        $profile_status = $result[0]->public_private_profile;
                                        
                                        if ($profile_status == 0) {
                                            echo "$first_name's Profile";
                                        } elseif ($profile_status == 1) {
                                            echo 'This account is Private';
                                        } else {
                                            echo 'Invalid profile status';
                                        }
                                    } else {
                                        echo "This author has not registered yet";
                                    }
                                ?>

                            </h1>
							<p class="role">
                            <?php 
               
                                $result = database_run("SELECT role FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                                
                                if ($result !== false && !empty($result)) {
                                    $role = $result[0]->role;
                                    echo $role;
                                } else {
                                    echo "";
                                }
                            ?>
                            </p>
							<!-- <p class="subscription">You're subscribed to Package plan</p> -->
						</div>
					</div>
					<!-- Action Buttons -->
                    <?php
                      	$sqlSelectName = "SELECT article.title FROM article JOIN author ON article.author_id = author.author_id WHERE author.orc_id = :orc_id";
                        $result = database_run($sqlSelectName, array(':orc_id' => $orcid));

                        $sqlReviewed = "SELECT reviewer_assigned.reviewer_assigned_id FROM reviewer_assigned JOIN article ON reviewer_assigned.article_id = article.article_id JOIN author ON article.author_id = author.author_id WHERE reviewer_assigned.answer = 1 AND reviewer_assigned.accept = 1 AND author.orc_id = :orc_id";
                        $resultReviewed = database_run($sqlReviewed, array(':orc_id' => $orcid));
                          
                        
                        // $sqlDonation = "SELECT user_points.action_engage FROM user_points JOIN author ON user_points.user_id = author.author_id WHERE author.orc_id = :orc_id AND user_points.action_engage = 'Donation'";
                        // $resultDonation = database_run($sqlDonation, array(':orc_id' => $orcid));

                        if(!$result === false && $resultReviewed === false){
                            if(count($result) === 1){
                                echo '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
                                echo '<span class="pubBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Bronze Badge: This author successfully publish one article
                                </span>';
                            }elseif(count($result) === 2){
                                echo '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
                                echo '<span class="pubSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Silver Badge: This author successfully publish two article
                                </span>';
                            }elseif(count($result) >= 3){
                                echo '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
                                echo '<span class="pubGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Gold Badge: This author successfully publish three or more article
                                </span>';
                            }
                        }elseif(!$resultReviewed && $result === false){
                            if(count($resultReviewed) === 1){
                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
                                echo '<span class="revBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Reviewer Bronze Badge: This author help to publish an article by reviewing once.
                                </span>';
                            }elseif(count($resultReviewed) === 2){
                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
                                echo '<span class="revSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Reviewer Silver Badge: This author help to publish an article by reviewing twice.
                                </span>';
                            }elseif(count($resultReviewed) >= 3){
                                echo '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
                                echo '<span class="revGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Reviewer Gold Badge: This author help to publish an article by reviewing three or more.
                                </span>';
                            }
                        }elseif(!$result === false && !$resultReviewed === false){
                            if(count($result) === 1 && count($resultReviewed) === 1){
                                echo '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
                                echo '<span class="pubBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Bronze Badge: This author successfully publish one article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
                                echo '<span class="revBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Bronze Badge: This author help to publish an article by reviewing once.
                                </span>';

                            }elseif(count($result) === 2 && count($resultReviewed) === 1){
                                echo '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
                                echo '<span class="pubSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Silver Badge: This author successfully publish two article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
                                echo '<span class="revBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Bronze Badge: This author help to publish an article by reviewing once.
                                </span>';
                            }elseif(count($result) === 1 && count($resultReviewed) === 2){
                                echo '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
                                echo '<span class="pubBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Bronze Badge: This author successfully publish one article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
                                echo '<span class="revSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Silver Badge: This author help to publish an article by reviewing twice.
                                </span>';
                            }elseif(count($result) === 2 && count($resultReviewed) === 2){
                                echo '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
                                echo '<span class="pubSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Silver Badge: This author successfully publish two article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
                                echo '<span class="revSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Silver Badge: This author help to publish an article by reviewing twice.
                                </span>';
                            }elseif(count($result) >= 3 && count($resultReviewed) === 1){
                                echo '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
                                echo '<span class="pubGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Gold Badge: This author successfully publish three or more article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
                                echo '<span class="revBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Bronze Badge: This author help to publish an article by reviewing once.
                                </span>';
                            }elseif(count($result) === 1 && count($resultReviewed) >= 3){
                                echo '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
                                echo '<span class="pubBrozeHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Bronze Badge: This author successfully publish one article
                                </span>';

                                echo '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
                                echo '<span class="revGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Gold Badge: This author help to publish an article by reviewing three or more.
                                </span>';
                            }elseif(count($result) >= 3 && count($resultReviewed) === 2){
                                echo '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
                                echo '<span class="pubGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Gold Badge: This author successfully publish three or more article
                                </span>';

                                echo '<div class="badge-box revBronzeBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
                                echo '<span class="revSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Silver Badge: This author help to publish an article by reviewing twice
                                </span>';
                            }elseif(count($result) === 2 && count($resultReviewed) >= 3){
                                echo '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
                                echo '<span class="pubSilverHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Silver Badge: This author successfully publish two article
                                </span>';

                                echo '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
                                echo '<span class="revGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Gold Badge: This author help to publish an article by reviewing three or more.
                                </span>';
                            }elseif(count($result) >= 3 && count($resultReviewed) >= 3){
                                echo '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
                                echo '<span class="pubGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 80px;
                                ">Publication Gold Badge: This author successfully publish three or more article
                                </span>';

                                echo '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
                                echo '<span class="revGoldHover" style="
                                width: 200px;
                                background: #333;
                                color: white;
                                position: absolute;
                                padding-left: 5px;
                                z-index: 999;
                                top: 90px;
                                margin-left: 160px;
                                ">Reviewer Gold Badge: This author help to publish an article by reviewing three or more.
                                </span>';
                            }
                        }else{
                            echo '<center><strong>You have no badge yet</strong></center>';
                        }


                                

                    ?>

					<!-- <div class="profile-badge">
                        <p class="recent-badges">Recent Badges</p>
						<div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge3.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
					</div> -->
				</div>
				<hr class="vertical-line">

				<div class="profile-main">
					<!-- Detailed Info -->
					<div class="details">
						<p><span class="label">Position:</span><span id="positionLabel">
                            <?php 
                
                                $result = database_run("SELECT position, public_private_profile  FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));

                              
                                
                                if ($result !== false && !empty($result)) {
                                    $position = $result[0]->position;
                                    $positionStats = $result[0]->public_private_profile ;
                                    if ($positionStats == 0){
                                        echo $position;
                                    }else{
                                        echo '';
                                    }
                                } else {
                                    echo '';
                                }
                            ?>
                        </span></p>
						<p><span class="label">Phone Number:</span> <span id="genderLabel">
                        <?php 
                
                            $result = database_run("SELECT phone_number, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                            
                            if ($result !== false && !empty($result)) {
                                $phone_number = $result[0]->phone_number;
                                $phone_numberStats = $result[0]->public_private_profile ;
                                if ($phone_numberStats == 0){
                                    echo $phone_number;
                                }else{
                                    echo '';
                                }
                            } else {
                                echo '';
                            }
                        ?>
                                
                        </span></p>
						<p><span class="label">Birthday:</span> <span id="birthdayLabel">
                        <?php 
                
                            $result = database_run("SELECT birth_date, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                            
                            if ($result !== false && !empty($result)) {
                                $birthday = $result[0]->birth_date;
                                $birthStats = $result[0]->public_private_profile ;
                                if ($birthStats == 0){
                                    echo $birthday;
                                }else{
                                    echo '';
                                }
                            } else {
                                echo '';
                            }
                        ?>
                        </span></p>
						<p><span class="label">Country:</span> <span id="countryLabel">
                        <?php 
                
                            $result = database_run("SELECT country, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                            
                            if ($result !== false && !empty($result)) {
                                $country = $result[0]->country;
                                $countryStats = $result[0]->public_private_profile ;
                                if ($countryStats == 0){
                                    echo $country;
                                }else{
                                    echo '';
                                }
                            } else {
                                echo '';
                            }
                        ?>
                        </span></p>
						<p><span class="label">ORCID:</span> <span id="orcidLabel">
                        <?php 
                
                            $result = database_run("SELECT orc_id, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                            
                            if ($result !== false && !empty($result)) {
                                $orc_id = $result[0]->orc_id;
                                $orcidId_Stats = $result[0]->public_private_profile ;
                                if ($orcidId_Stats == 0){
                                    echo $orc_id;
                                }else{
                                    echo '';
                                }
                            } else {
                                echo '';
                            }
                        ?>
                        </span></p>
					</div>
				</div>
			</div>
		</div>
        <section class="expertise">
			<!-- Expertise info here -->
			<div class="tab">
			    <button class="tablinks" onclick="openTab(event, 'About')" id="defaultOpen">About</button>
				<button class="tablinks" onclick="openTab(event, 'Attainments')">Attainments</button>
                <button class="tablinks" onclick="openTab(event, 'Publications')">Publications</button>
			</div>


			<div id="About" class="tabcontent" style="display: block;">
				<div id="about-container">
					<div id="logrecord-container">
						<div class="log-box"><h3>Join In Community Since:</h3>
                            <?php
                                $result = database_run("SELECT date_added FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));

                                if ($result !== false && !empty($result)) {
                                    $date_added = $result[0]->date_added;

                                    $formattedDate = date("F j, Y", strtotime($date_added));
                                    
                                    echo $formattedDate;
                                } else {
                                    echo "";
                                }
                            ?>

                        </div>
						<div class="log-box">
                            <?php
                                $result = database_run("SELECT MIN(article.date_added) AS oldest_date FROM article JOIN author ON article.author_id = author.author_id WHERE author.orc_id = :orc_id", array('orc_id' => $orcid));

                                if ($result !== false && !empty($result)) {
                                    $oldestDate = $result[0]->oldest_date;

                                
                                    $formattedDate = date("F j, Y", strtotime($oldestDate));
                                    
                                    echo "<h3>Author Since:</h3> $formattedDate";
                                } else {
                                    echo "This author has not submitted any article yet";
                                }
                            ?>

                        </div>
						<div class="log-box">
                            <h3>Reviewer Since: </h3> 
                            <?php
                                $result = database_run("SELECT MIN(date_issued) AS oldest_date FROM reviewer_assigned JOIN author ON reviewer_assigned.author_id = author.author_id WHERE author.orc_id = :orc_id", array('orc_id' => $orcid));

                                if ($result !== false && !empty($result) && !empty($result[0]->oldest_date)) {
                                    $oldestDate = $result[0]->oldest_date;

                                    $formattedDate = date("F j, Y", strtotime($oldestDate));
                                    
                                    echo "<p class='date'>$formattedDate</p>";
                                } else {
                                    echo 'This author has not reviewed any article yet';
                                }
                            ?>
                        </div>
					</div>
					<div id="info-container">
						<div class="info-box">
							<div class="bio-container">
                                <h3>Bio </h3>
								<!-- <hr> -->
								<p>
                                <?php 
                
                                    $result = database_run("SELECT bio, public_private_profile  FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));
                                    
                                    if ($result !== false && !empty($result)) {
                                        $bio = $result[0]->bio;
                                        $bioStats = $result[0]->public_private_profile ;
                                        if ($bioStats == 0){
                                            echo $bio;
                                        }else{
                                            echo '';
                                        }
                                    } else {
                                        echo '';
                                    }
                                ?>
								</p>
							</div>
						</div>
						<div class="info-box">
							<div class="expertise-container">
                                <h3>Expertise </h3>
								<!-- <hr> -->
								<p>
                                <?php 
                
                                    $result = database_run("SELECT field_of_expertise, public_private_profile FROM author WHERE orc_id = :orc_id", array('orc_id' => $orcid));

                                    if ($result !== false && !empty($result)) {
                                        $field_of_expertise = $result[0]->field_of_expertise;
                                        $expertiseStats = $result[0]->public_private_profile ;
                                        if ($expertiseStats == 0){
                                            echo $field_of_expertise;
                                        }else{
                                            echo '';
                                        }
                                    } else {
                                        echo '';
                                    }
                                ?>  
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div id="Publications" class="tabcontent">
                <div class="publications-container">
                    <div class="header-achievements">
                        <h4>Published Articles</h4>
                    </div>
                    <div class="table-container">
                        <table class="publications-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Views</th>
                                    <th>Support</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                            
                                    
                                <?php 
									$sqlSelect = "SELECT a.article_id, a.title, a.date, COUNT(CASE WHEN l.type = 'read' THEN 1 END) AS read_count, 
									COUNT(CASE WHEN l.type = 'support' THEN 1 END) AS support_count FROM article a
									LEFT JOIN logs l ON a.article_id = l.article_id LEFT JOIN author au ON a.author_id = au.author_id
									WHERE a.status = 1 AND au.orc_id = :orc_id GROUP BY a.article_id, a.title, a.date
									ORDER BY a.date ASC";

									$params = array('orc_id' => $orc_id);
									$sqlRun = database_run($sqlSelect, $params);

									if ($sqlRun) {
										foreach ($sqlRun as $row) {
                                            echo '<tr>';
											$title = $row->title;
											$date = $row->date;
											$read = $row->read_count; 
											$support = $row->support_count; 
											echo '<td><a href="../PHP/article-details.php?articleId=' . $row->article_id . '">' . $title . '</a></td>';
											echo '<td>' . $date . '</td>';
											echo '<td>' . $read . '</td>';
											echo '<td>' . $support . '</td>';
										}
                                        echo '</tr>';
									} else {
										echo '';
									}

								
								?>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="publications-container">
                    <div class="header-achievements">
                        <h4>Contributed Articles</h4>
                    </div>
                    <div class="table-container">
                        <table class="publications-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Views</th>
                                    <th>Support</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                             
                                    <?php
                                    $sql = "SELECT article.article_id, article.title, article.date, user_points.email, user_points.action_engage, 
                                            COUNT(CASE WHEN logs.type = 'read' THEN 1 END) AS read_count, 
                                            COUNT(CASE WHEN logs.type = 'support' THEN 1 END) AS support_count
                                            FROM user_points 
                                            JOIN contributors ON user_points.article_id = contributors.article_id 
                                            JOIN article ON contributors.article_id = article.article_id 
                                            LEFT JOIN logs ON article.article_id = logs.article_id
                                            WHERE contributors.orcid = :orc_id
                                            AND (user_points.action_engage = 'Co-Author' OR user_points.action_engage = 'Primary Contact') 
                                            AND article.status = 1
                                            GROUP BY article.article_id";

                                    $result = database_run($sql, array('orc_id' => $orcid));  

                                    if($result) {
                                        foreach($result as $row) {
                                            echo '<tr>';
                                            $article_id = $row->article_id;
                                            $title = $row->title;
                                            $date = $row->date;
                                            $email = $row->email; 
                                            $action_engage = $row->action_engage;
                                            $read_count = $row->read_count;
                                            $support_count = $row->support_count;
                                            
                                            echo '<tr>';
                                            echo '<td><a href="../PHP/article-details.php?articleId=' . $row->article_id . '">' . $title . '</a></td>';
                                            echo '<td>' . $date . '</td>';
                                            echo '<td>' . $action_engage . '</td>';
                                            echo '<td>' . $read_count . '</td>';
                                            echo '<td>' . $support_count . '</td>';
                                            echo '</tr>';
                                        }
                                        echo '</tr>';
                                    }
                                    ?>
                               

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="PublishedArticles" class="tabcontent" display="none">
                <div class="publications-articles">
                    <div class="stats-main">
                        <div class="publications-stats-container">
                            <div class="stat">
                                <div class="publications-stats-values">
                                    <?php
                                        $sql = "SELECT author.author_id, author.orc_id, logs.article_id, logs.type FROM author JOIN article ON author.author_id = article.author_id JOIN logs ON article.article_id = logs.article_id WHERE author.orc_id = :orc_id AND logs.type = 'read';
                                        ";
                                        $params = array('orc_id' => $orcid);
                                        $sqlRun = database_run($sql, $params);

                                        if($sqlRun != false){
                                           echo (count($sqlRun));
                                        }else{
                                            echo '0';
                                        }
                                    

                                    
                                    ?>
                                </div>
                                <div class="publications-stats-labels">Views</div>
                            </div>
                            <div class="stat">
                                <div class="stats-values">
                                    <?php
                                            $sql = "SELECT author.author_id, author.orc_id, logs.article_id, logs.type FROM author JOIN article ON author.author_id = article.author_id JOIN logs ON article.article_id = logs.article_id WHERE author.orc_id = :orc_id AND logs.type = 'download';
                                            ";
                                            $params = array('orc_id' => $orcid);
                                            $sqlRun = database_run($sql, $params);

                                            if($sqlRun != false){
                                            echo (count($sqlRun));
                                            }else{
                                                echo '0';
                                            }
                                        

                                        
                                    ?>
                                </div>
                                <div class="stats-labels">Downloads</div>
                            </div>
                            <div class="stat">
                                <div class="stats-values">
                                    <?php

                                        $sql = "SELECT author.author_id, author.orc_id, logs.article_id, logs.type FROM author JOIN article ON author.author_id = article.author_id JOIN logs ON article.article_id = logs.article_id WHERE author.orc_id = :orc_id AND logs.type = 'citation';
                                        ";
                    
                                        $params = array('orc_id' => $orcid);
                                        $sqlRun = database_run($sql, $params);

                                        if($sqlRun != false){
                                        echo (count($sqlRun));
                                        }else{
                                            echo '0';
                                        }
                                            

                                            
                                    ?>
                                </div>
                                <div class="stats-labels">Citations</div>
                            </div>
                            <div class="stat">
                                <div class="stats-values">
                                <?php

                                $sql = "SELECT author.author_id, author.orc_id, logs.article_id, logs.type FROM author JOIN article ON author.author_id = article.author_id JOIN logs ON article.article_id = logs.article_id WHERE author.orc_id = :orc_id AND logs.type = 'support';
                                ";

                                $params = array('orc_id' => $orcid);
                                $sqlRun = database_run($sql, $params);

                                if($sqlRun != false){
                                echo (count($sqlRun));
                                }else{
                                    echo '0';
                                }
                                    

                                    
                                ?>
                                </div>
                                <div class="stats-labels">Support</div>
                            </div>
                        </div>
                        <div class="stat search-container">
                            <input type="text" placeholder="Search">
                        </div>
                    </div>

                    <div class="publications-table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Publish Date</th>
                                    <th>Journal</th>
                                    <!-- <th><center>Action</center></th> -->
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="no-data-message" style="display: none;">
                                    <td colspan="6">No records found</td>
                                </tr>
                                <tr>
                                <?php 
                                    $sqlTitle = "SELECT article.title, article.article_id, article.publication_date, author.orc_id, author.public_private_profile FROM article JOIN author ON article.author_id = author.author_id WHERE author.orc_id = :orc_id AND article.status = 1";
                                    $result = database_run($sqlTitle, array('orc_id' => $orcid));

                                    if ($result !== false && !empty($result)) {
                                        echo '<td>'; 

                                        foreach ($result as $row) {
                                            $displayTitle = $row->title;
                                            $articleId = $row->article_id;
                                            echo '<a href="../PHP/article-details.php?articleId=' . $articleId . '">' . $displayTitle .'</a><br>';

                                        }

                                        echo '</td>'; 
                                    } else {
                                        echo ''; 
                                    }
                                ?>
                                <?php 
                                    $sqlTitle = "SELECT article.title, article.article_id, article.publication_date, author.orc_id, author.public_private_profile FROM article JOIN author ON article.author_id = author.author_id WHERE author.orc_id = :orc_id AND article.status = 1";
                                    $result = database_run($sqlTitle, array('orc_id' => $orcid));

                                    if ($result !== false && !empty($result)) {
                                        echo '<td>'; 

                                        foreach ($result as $row) {
                                            $publicationDate = new DateTime($row->publication_date);
                                            $formattedDate = $publicationDate->format('F j, Y');
                                            echo $formattedDate;
                                        }

                                        echo '</td>'; 
                                    } else {
                                        echo ''; 
                                    }
                                ?>


                                <?php 
                                    $sqlTitle = "SELECT journal.journal, article.title FROM journal JOIN article ON journal.journal_id = article.journal_id JOIN author ON article.author_id = author.author_id WHERE article.status = 1 AND author.orc_id = :orc_id;";
                                    $result = database_run($sqlTitle, array('orc_id' => $orcid));

                                    if ($result !== false && !empty($result)) {
                                        echo '<td>'; 

                                        foreach ($result as $row) {
                                            $displayJournal = $row->journal;
                                            echo $displayJournal;
                                        }

                                        echo '</td>'; 
                                    } else {
                                        echo ''; 
                                    }
                                ?>
                                

                                    <!-- <td></td> display the publication_date of each title -->
                                    <!-- <td>The Star</td> -->
                                    <!-- <td><center>...</center></td> -->
                                </tr>
                                <!-- <tr>
                                    <td style="color: #285581">Industries with Distributed Ledger Technology</td>
                                    <td>October 24, 2018</td>
                                    <td>The Star</td>
                                    <td><center>...</center></td>
                                    
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="Attainments" class="tabcontent">
                <div id="attainments-container">
                    <div id="contribution-container">
                        <div id="contribution-record-container">
                            <div id="badges-container">
                                <div class="header-badges">
                                    <h4>Badges</h4>
                                </div>
                                <div class="badge-box-container">
                                    <div class="xp-container">
                                        <!-- XP Bar -->
                                        <!-- <div class="xp-bar">
                                            <div class="progress-bar">
                                                <div class="progress" style="width: 66.7%;"></div>
                                            </div>
                                            <span class="xp-label">8/12</span>
                                        </div> -->
                                        
                                    </div>


                                                
                                    <!-- <div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge3.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge3.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
                                    <div class="badge-box" style="background-image: url('../images/badge3.jpg');"></div>
                                    <div class="badge-see-more"> -->
                                        <!-- <button class="btn btn-primary btn-md btn-seemore" id="see-more">See more</button> -->
                                    <!-- </div> -->
                                </div>	
                            </div>
                            <div class="stats-section">
                                <div class="stat-card top-card">
                                <h2>Total Contributions</h2>
                                <p>
                                    <?php 
                                    $sqlTotalContribution = "SELECT user_points.user_id, user_points.email, author.orc_id 
                                    FROM user_points 
                                    JOIN author ON user_points.user_id = author.author_id 
                                    WHERE author.orc_id = :orc_id 
                                    AND (user_points.action_engage = 'Reviewed an Article' OR user_points.action_engage = 'Submitted an Article')";


                                    $params = array('orc_id' => $orc_id);
                                    $result = database_run($sqlTotalContribution, $params);

                                    if ($result !== false) {
                                        $totalContributions = count($result);
                                        echo $totalContributions;

                                        // Fixed percentage increase
                                        $percentageIncrease = 100;

                                        // Check if total contributions are greater than 0 before calculating the increased count
                                        if ($totalContributions > 0) {
                                            $increasedCount = $totalContributions * (1 + ($percentageIncrease / 100));
                                        } else {
                                            $increasedCount = 0; // If there are no contributions yet, set the increased count to 0
                                        }

                                        echo '<span class="increase">' . round($increasedCount, 2) . '%' . '</span>';
                                    } else {
                                        echo '0'; 
                                    }
                                    ?>
                                </p>



                                </div>
                            </div>
                            <div class="stats-section">
                                <div class="stat-card top-card">
                                    <h2>Total Reviewed</h2>
                                    <p>
                                        <?php 
                                        $sqlTotalContribution = "SELECT user_points.user_id,  user_points.email, author.orc_id FROM user_points JOIN author ON user_points.user_id = author.author_id WHERE author.orc_id = :orc_id AND user_points.action_engage = 'Reviewed an Article'";

                                        $params = array('orc_id' => $orc_id);
                                        $result = database_run($sqlTotalContribution, $params);

                                        if ($result !== false) {
                                            $totalContributions = count($result);
                                            echo $totalContributions;

                                            // Fixed percentage increase
                                            $percentageIncrease = 100;

                                            // Check if total contributions are greater than 0 before calculating the increased count
                                            if ($totalContributions > 0) {
                                                $increasedCount = $totalContributions * (1 + ($percentageIncrease / 100));
                                            } else {
                                                $increasedCount = 0; // If there are no contributions yet, set the increased count to 0
                                            }

                                            echo '<span class="increase">' . round($increasedCount, 2) . '%' . '</span>';
                                        } else {
                                            echo '0'; 
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div> 
                            <div class="stats-section">
                                <div class="stat-card top-card">
                                    <h2>Total Submissions</h2>
                                    <p>
                                        <?php 
                                            $sqlTotalContribution = "SELECT user_points.user_id,  user_points.email, author.orc_id FROM user_points JOIN author ON user_points.user_id = author.author_id WHERE author.orc_id = :orc_id AND user_points.action_engage = 'Submitted an Article' ";

                                            $params = array('orc_id' => $orc_id);
                                            $result = database_run($sqlTotalContribution, $params);

                                            if ($result !== false) {
                                                $totalContributions = count($result);
                                                echo $totalContributions;

                                                // Fixed percentage increase
                                                $percentageIncrease = 100;

                                                // Check if total contributions are greater than 0 before calculating the increased count
                                                if ($totalContributions > 0) {
                                                    $increasedCount = $totalContributions * (1 + ($percentageIncrease / 100));
                                                } else {
                                                    $increasedCount = 0; // If there are no contributions yet, set the increased count to 0
                                                }

                                                echo '<span class="increase">' . round($increasedCount, 2) . '%' . '</span>';
                                            } else {
                                                echo '0'; 
                                            }
                                            ?>
                                    </p>
                                        
                                </div>
                            </div>
                        </div>
                        <div class="vertical-line"></div>
                        <div class="credit-container">
                            <div class="header-achievements">
                                <h4>Achievements</h4>
                            </div>
                            <div class="sort-container d-flex flex-column gap-2">
                                <!-- <div class="sort-header">
                                    <span class="sort-by-text" style="color: var(--main, #0858A4);">Sort by</span>
                                    <span class="sort-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
                                            <path fill="#e6e6e6" d="M11 7H1l5 7zm-2 7h10l-5-7z" />
                                        </svg>
                                    </span>
                                    <select id="sortby" name="sortby" class="sort-dropdown form-select form-select-sm px-8">
                                        <option value="" hidden>Recently added</option>
                                        <option value="title">Title</option>
                                        <option value="recently_added">Recently added</option>
                                        <option value="publication-date">Publication Date</option>
                                        <optgroup label="Popularity">
                                            <option value="popular">All</option>
                                            <option value="views">Views</option>
                                            <option value="downloads">Downloads</option>
                                            <option value="citations">Citations</option>
                                        </optgroup>
                                    </select>
                                </div> -->
                            </div>
                            <div class="table-container">
                                <table class="achievements-table">
                                    <thead>
                                        <tr>
                                            <th>Details</th>
                                            <th>Journal</th>
                                            <th>Date</th>
                                            <th>Reward</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php

                                                $sqlAchievements = "
                                                                                        

                                                (SELECT 'Published an Article' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN article ON user_points.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE user_points.action_engage = 'Published an Article' AND article.status = 1 AND author.orc_id = :orc_id)

                                                UNION

                                                (SELECT 'Reviewed Article Published' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
                                                JOIN article ON reviewer_assigned.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE article.status = 1 AND reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed Article Published' AND author.orc_id = :orc_id )

                                                UNION

                                                (SELECT 'Submitted an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN article ON user_points.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE user_points.action_engage = 'Submitted an Article' AND author.orc_id = :orc_id)

                                                UNION

                                                (SELECT 'Reviewed an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
                                                JOIN article ON reviewer_assigned.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed an Article' AND author.orc_id = :orc_id)

                                                UNION

                                                (SELECT 'Co-Author' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN article ON user_points.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE user_points.action_engage = 'Co-Author' AND article.status <= 6 AND author.orc_id = :orc_id)

                                                UNION

                                                (SELECT 'Primary Contact' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
                                                FROM user_points
                                                JOIN article ON user_points.article_id = article.article_id JOIN author ON user_points.user_id = author.author_id
                                                WHERE user_points.action_engage = 'Primary Contact' AND article.status <= 6 AND author.orc_id = :orc_id)

                                                ORDER BY date DESC
                                                ";


                                                $result = database_run($sqlAchievements,array('orc_id' => $orcid));

                                                if ($result !== false) {
                                                foreach ($result as $row) {
                                                    $journalMapping = [
                                                        '1' => 'The Gavel',
                                                        '2' => 'The Lamp',
                                                        '3' => 'The Star',
                                                    ];

                                                    echo '<tr>';
                                                    echo '<td>' . $row->action_engage .'</td>';
                                                    echo '<td>' . $journalMapping[$row->journal_id] .'</td>';
                                                    echo '<td style="display: none">' . $row->title .'</td>';
                                                    $formattedDate = date('F j, Y', strtotime($row->date)); // Formatting without time
                                                    echo '<td>' . $formattedDate . '</td>';
                                                    echo '<td style="color: green;"> Earned ' . $row->point_earned . ' Community Heart</td>';
                                                    echo '</tr>';

                                                    
                                                }
                                                }else{
                                                echo '<p style="margin-left: 50px; padding-top: 20px">You have no Achievements yet</p>';
                                                }

                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- <div id="graph-container">
                            <h3>Contributions Graph</h3>
                            <?php
                                $sqlViews = "SELECT MONTH(logs.date) AS month, COUNT(*) AS views
                                            FROM logs
                                            RIGHT JOIN article ON logs.article_id = article.article_id
                                            WHERE article.author_id = :author_id AND logs.type = 'read'
                                            GROUP BY MONTH(logs.date)";

                                $sqlDownloads = "SELECT MONTH(logs.date) AS month, COUNT(*) AS downloads
                                                FROM logs
                                                RIGHT JOIN article ON logs.article_id = article.article_id
                                                WHERE article.author_id = :author_id AND logs.type = 'download'
                                                GROUP BY MONTH(logs.date)";

                                $params = array('author_id' => $id);

                                $viewsData = database_run($sqlViews, $params);
                                $downloadsData = database_run($sqlDownloads, $params);

                                // Initialize data arrays with default values
                                $allMonthsData = array_fill(1, 12, 0);
                                $allDownloadsData = array_fill(1, 12, 0);

                                // Populate fetched data into respective arrays
                                if ($viewsData !== false) {
                                    foreach ($viewsData as $monthData) {
                                        $allMonthsData[$monthData->month] = $monthData->views;
                                    }
                                }

                                if ($downloadsData !== false) {
                                    foreach ($downloadsData as $monthData) {
                                        $allDownloadsData[$monthData->month] = $monthData->downloads;
                                    }
                                }
                            ?>

                            <canvas id="articlesChart" width="400" height="120"></canvas>
                        </div> -->
                    </div>
                </div>
            </div>
            
		</section>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../JS/reusable-header.js"></script>
    <script src="../JS/user-view.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
 function includeNavbar() {
  fetch('../PHP/navbar.php')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navigation-menus-container').innerHTML = data;
      // Now that the content is loaded, you can attach event listeners or perform other operations as needed
      // For example, you can attach the notification button click event listener here
      attachNotificationButtonListener();
      attachNotificationItemClickListener();
      playAudio();
    })
    .catch(error => console.error('Error loading navbar.php:', error));
}
// function playAudio() {
//   var x = document.getElementById("myAudio");
//   x.play();
// }


function attachNotificationButtonListener() {
  $(document).on('click', '#notification-button', function () {
    $("#notification-count").text("0");
    $("#notification-count").hide();
     
    // Send AJAX request to mark notifications as read
    $.ajax({
      url: "../PHP/mark_notifications_read.php",
      type: "POST",
      data: { author_id: <?php echo $_SESSION['id']; ?> },
      success: function (response) {
        console.log("Notifications marked as read:", response);
        // Update notification count on success
        // $("#notification-count").text("0");
        // $("#notification-count").hide();

      },
      error: function (xhr, status, error) {
        console.error("Error marking notifications as read:", error);
      }
    });
  });
}

function attachNotificationItemClickListener() {
  const notificationItems = document.querySelectorAll('.notification-item');

  notificationItems.forEach(item => {
    item.addEventListener('click', function () {
      const notificationId = this.dataset.notificationId;
      markNotificationAsRead(notificationId);
    });
  });

  function markNotificationAsRead(notificationId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../PHP/mark_as_read.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        // Handle success or display any feedback to the user
        console.log('Notification marked as read');
      }
    };
    xhr.send('notification_id=' + encodeURIComponent(notificationId));
  }
}

// Call this function in your code wherever appropriate, such as after loading notifications
// attachNotificationItemClickListener();


// Call includeNavbar function to load navbar.php content
includeNavbar();

    </script>
</body>
</html>