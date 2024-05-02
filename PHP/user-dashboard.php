<?php 
require 'dbcon.php';
session_start();

if (!isset($_SESSION['LOGGED_IN']) || $_SESSION['LOGGED_IN'] !== true) {
	header('Location: ./login.php');
  }

$id = $_SESSION['id'];
$email = $_SESSION['email'];
$first_name = $_SESSION['first_name'];
$middle_name = $_SESSION['middle_name'];
$last_name = $_SESSION['last_name'];
$role = $_SESSION['role'];
$position = $_SESSION['position'];
$country = $_SESSION['country'];
$gender = $_SESSION['gender'];
$afiliations = $_SESSION['afiliations'];
$status = $_SESSION['status'];
$affix = $_SESSION['affix'];
$expertise = $_SESSION['expertise'];

$birthday = $_SESSION['birthday'];
$dateTime = new DateTime($birthday);
$formattedBirthday = $dateTime->format('F j, Y');

$date_added = $_SESSION['date_added'];
$dateAdded = new DateTime($date_added);
$formattedDateAdded = $dateAdded->format('F j, Y');


$orc_id = $_SESSION['orc_id'];
$bio = $_SESSION['bio'];
$expertise = $_SESSION['expertise'];




?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php include('./meta.php'); ?>
  <title>QCUJ | USER DASHBOARD</title>
  <link rel="stylesheet" href="../CSS/user-dashboard.css">
  <link rel="stylesheet" href="../CSS/index.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> -->
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


<div class="main">
    <div class="main-profile">
		<div class="profile-container">
			<div class="profile-sidebar">
		
				<!-- <img src="../images/profile.jpg" alt="Profile Picture" class="profile-pic" id="profileImage">
				<input type="file" accept="image/*" style="display:none" id="fileInput">
				<button type="button" class="btn btn-secondary btn-sm"  onclick="openFileInput()"><i class="fa-solid fa-camera"></i></button> -->
				<?php
				if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
					$sqlSelectProfile = "SELECT profile_pic FROM author WHERE author_id = :author_id";

					$result = database_run($sqlSelectProfile, array(':author_id' => $id));

					if ($result) {
						if (count($result) > 0) {
							$user = $result[0];
							$profilePic = $user->profile_pic;

							
							if (empty($profilePic)) {
								$profilePic = "../images/profile.jpg";
							}


							echo '<div class="profile-pic-container">';
							echo '<img src="' . htmlspecialchars($profilePic) . '" alt="Profile Picture" class="profile-pic" id="profileImage">';
							echo '<input type="file" accept="image/*" style="display:none" id="fileInput" name="fileInput" >';
							// echo '<input type="file" accept="image/*" style="display:none" id="fileInput" name="fileInput">';
							// echo '<div class="change-profile-text">Change Profile</div>';
							// echo '<div class="edit-icon" onclick="openFileInput()"> <span>&#9998;</span>Change Profile </div>';
							
							echo '<div class="button-container"><button type="button" class="btn btn-secondary btn-sm" onclick="openFileInput()" style="margin-left: auto; margin-right: auto;"><i class="fa-solid fa-camera"></i></button></div>';
							
							echo '</div>';
						} else {
							echo "User not found.";
						}
					} else {
						echo "Unable to fetch profile picture.";
					}
				}
				?>


				<!-- Modal for Image Preview and Confirmation -->
				<div id="imageModal" class="modal" style="display:none">
					<div class="modal-content mt-3">
						<p class="h6 mt-2" style="text-align: center; magin: 0; border-bottom: 1px gray solid">Change Profile Picture</p>
						
						<img class="img-fluid mt-4" src="" alt="Selected Image" id="selectedImagePreview">
						<div class="btn-change mt-5" style="width: 100%">
						<button type="button" class="btn btn-success btn-sm" style="width: 95%; display: block; margin-left: 10px; margin-right: 5px" onclick="saveProfile()">Save</button>
						<button type="button" class="btn btn-secondary btn-sm mt-1" style="width: 95%; display: block; margin-left: 10px" onclick="cancelUpdate()">Cancel</button>
						</div>
						
					</div>
				</div>
			</div>
			<div class="profile-info">
				<div class="row">
					<!--<div class="points">
						<p><span class="label">PHP:</span> 1.00 / Credit</p>
					</div>-->
						<!-- User Info -->
					<div class="user-info">
						<div class="info-header ">
								<h1>
								<?php
									if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
										$sqlSelectName = "SELECT first_name, middle_name, last_name, affix, birth_date, gender, phone_number, marital_status, country, orc_id, afiliations, position, bio, field_of_expertise FROM author WHERE author_id = :author_id";
										$result = database_run($sqlSelectName, array(':author_id' => $id));

										if ($result) {
											if (count($result) > 0) {
												$user = $result[0];
												$firstName = $user->first_name;
												$middleName = $user->middle_name;
												$lastName = $user->last_name;
												$affix = $user->affix;
												$birthDate = $user->birth_date;
												$gender = $user->gender;
												$phone_number = $user->phone_number;
												$maritalStatus = $user->marital_status;
												$country = $user->country;
												$orcId = $user->orc_id;
												$afiliations = $user->afiliations;
												$position = $user->position;
												$bio = $user->bio;
												$field_of_expertise = $user->field_of_expertise;

												$profileFields = array($firstName, $lastName, $birthDate, $gender, $phone_number, $maritalStatus, $country, $orcId, $afiliations, $position, $field_of_expertise);
												$completedFields = count(array_filter($profileFields, function($field) { return !empty($field); }));
												$totalFields = count($profileFields);

												$fullName = "$firstName $middleName $lastName";
												$percentageCompletion = ($completedFields / $totalFields) * 100;

												// Calculate remaining percentage
												$remainingPercentage = 100 - $percentageCompletion;

												// Display profile information
												echo "<p style='font-size: 20px'>" . $fullName . "</p>";
												echo "<p>PROFILE COMPLETENESS
														<span style='color: #004e98'>" . round($percentageCompletion, 2) . "%</span>";

												if ($percentageCompletion >= 100) {
													echo "<dfn class='d-none d-md-inline-flex' data-info='Complete profile, you can now submit a paper'>
													<i class='fa-regular fa-circle-check' style='color: green'></i>
													</dfn>";
												} else {
													echo "<dfn class='d-none d-md-inline-flex' data-info='You must complete required fields in your profile to submit or review a paper'>
															<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 32 32' color='red'>
																<path fill='currentColor' d='M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v2h2v-2zm0 4v8h2v-8z'></path>
															</svg>
														</dfn>";
												}

												echo "</p>
													<div class='progress' style='height: 3px; background-image: linear-gradient(to right, #004e98 " . round($percentageCompletion, 2) . "%, red " . round($percentageCompletion, 2) . "%, red 100%);'></div>
													<hr/>";
											} else {
												echo "User not found.";
											}
										} else {
											echo "Unable to fetch user info.";
										}
									}
								?>

								</h1>
							<!-- <i class="ri-edit-box-line" id="editIcon" style="color: green solid"></i> -->
							<div class="other-action">
								<!--<label id="editIcon" style="cursor: pointer; color: green"><span style="padding-right: 5px; font-size: 12px; font-family: Arial, Helvetica, sans-serif;
								">Edit</span><i class="fa-solid fa-pen-to-square" style="color: green"></i></label>
								<br> -->

								<!-- <label id="supportIcon" style="cursor: pointer; color: green"><span style="padding-right: 5px; font-size: 12px; font-family: Arial, Helvetica, sans-serif;
								">Support</span><i class="fa-solid fa-hand-holding-heart"></i></label> -->
								<!-- <button class="btn  btn-sm text-white" id="btn1" onclick="window.location.href='author-dashboard.php'">My Articles</button> -->
								
								<button type="button" id="editIcon" class="btn btn-success btn-sm" style="width: 100px">Edit<i class="fa-solid fa-pen-to-square"  style="margin-left: 5px"></i></button>
								<br>
					
							
								

								
							
							</div>
						</div>
						<p class="role">
							<?php
							if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
								$sqlSelectName = "SELECT role FROM author WHERE author_id = :author_id";
							
								$result = database_run($sqlSelectName, array(':author_id' => $id));

								if ($result) {
									
									if (count($result) > 0) {
										$user = $result[0];
										$role = $user->role;
										

										echo "$role";
									} else {
										echo "Can't identify user role.";
									}
								} else {
									echo "Unable to fetch user role.";
								}
							}
							?>
						</p>
						<!-- <p class="subscription">You're subscribed to Package plan</p> -->
					</div>
				</div>
				<!-- </form> -->
				<div class="points-container d-flex">
					<div class="balance-points p-1"><i class="fa-solid fa-fire" style="color: orange"></i>&nbsp;
						<?php
							$sqlPoints = "SELECT point_earned FROM user_points WHERE email = :email";


							$result = database_run($sqlPoints, array('email' => $email));

							if ($result !== false) {
								$totalPoints = 0;

								foreach ($result as $row) {
									$points = $row->point_earned;
									$totalPoints += $points;
								}

								echo  $totalPoints;
							}else{
								echo '0';
							}
						?>
						<br>
					</div>
					<div class="hoverPoints">
						<?php
						$sql = "SELECT action_engage, SUM(point_earned) AS total_points FROM user_points WHERE email = :email GROUP BY action_engage";
						$run = database_run($sql, array('email' => $email)); // Assuming $email is defined somewhere

						if ($run !== false) {
							foreach ($run as $row) {
								$action = $row->action_engage;
								$totalPoints = $row->total_points;
								echo '<span class="pointsH">' . $action . ': ' . $totalPoints . '</span>';
							}
						}
						?>

					</div>

					<div class="totalLikes p-1"><i class="fa-solid fa-heart" style="color: red"></i>&nbsp;
						<?php
							$sqlPoints = "SELECT author.author_id, author.first_name, article.article_id, article.title, logs.article_id, logs.type 
										FROM author 
										JOIN article ON author.author_id = article.author_id 
										JOIN logs ON article.article_id = logs.article_id 
										WHERE author.author_id = $id AND logs.type = 'support'";

							$result = database_run($sqlPoints);

							if ($result !== false) {
								$totalPoints = count($result);
								echo $totalPoints;
							} else {
								echo '0';
							}
						?>

					</div>
					<div class="hoverSupport">
						<span class="hoverS">Total Article Support</span>
					</div>
				</div>

				<!-- <?php
					if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
					$sqlMode = "SELECT public_private_profile FROM author WHERE author_id = :author_id";
					
					$result = database_run($sqlMode, array(':author_id' => $id));

					if ($result) {
						
						if (count($result) > 0) {
						$user = $result[0];
						$mode = $user->public_private_profile;
						
						if($mode === '0'){
							echo '<button class="btn btn-outline-primary btn-sm mt-2" id="changeModeBtn1">Public Profile</button>';
						}else{
							echo '<button class="btn btn-outline-primary btn-sm mt-2" id="changeModeBtn2">Private Profile</button>';
						}

						
						} else {
						echo "Something went wrong";
						}
					} else {
						echo "Something went wrong";
					}
					}
				?> -->
		

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
					<div class="icon-container">
						<!-- <i class="ri-eye-line" id="toggleIcon"></i> -->
					</div>
					<p><span class="label">Position:</span> <span id="positionLabel">
					
						<?php
						if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
							$sqlSelectName = "SELECT position FROM author WHERE author_id = :author_id";
						
							$result = database_run($sqlSelectName, array(':author_id' => $id));

							if ($result) {
								
								if (count($result) > 0) {
									$user = $result[0];
									$position = $user->position;
								
									echo $position;
								
									
					
								} else {
									echo "User not found.";
								}
							} else {
								echo "Unable to fetch user info.";
							}
						}
						?>
					</span></p>
					<p><span class="label">Phone Number: </span> <span id="genderLabel">
					<?php
						if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
							$sqlSelectName = "SELECT phone_number FROM author WHERE author_id = :author_id";
						
							$result = database_run($sqlSelectName, array(':author_id' => $id));

							if ($result) {
								
								if (count($result) > 0) {
									$user = $result[0];
									$phone_number = $user->phone_number;
								
									echo $phone_number;
								
									
					
								} else {
									echo "User not found.";
								}
							} else {
								echo "Unable to fetch user info.";
							}
						}
						?>
					</span></p>
					<p><span class="label">Birthday:</span> <span id="birthdayLabel">
						<?php
						if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
							$sqlSelectName = "SELECT birth_date FROM author WHERE author_id = :author_id";

							$result = database_run($sqlSelectName, array(':author_id' => $id));

							if ($result) {
								if (count($result) > 0) {
									$user = $result[0];
									$birth_date = $user->birth_date;

									if ($birth_date !== NULL) {
									
										$formatted_birth_date = date('F j, Y', strtotime($birth_date));
										echo $formatted_birth_date;
									} else {
										echo ""; 
									}
								} else {
									echo "User not found.";
								}
							} else {
								echo "Unable to fetch user info.";
							}
						}
						?>
					</span></p>

					<p><span class="label">Country:</span> <span id="countryLabel">
					<?php
						if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
							$sqlSelectName = "SELECT country FROM author WHERE author_id = :author_id";
						
							$result = database_run($sqlSelectName, array(':author_id' => $id));

							if ($result) {
								
								if (count($result) > 0) {
									$user = $result[0];
									$country = $user->country;
								
									echo $country;
								
									
					
								} else {
									echo "User not found.";
								}
							} else {
								echo "Unable to fetch user info.";
							}
						}
						?>
					</span></p>
					<p><span class="label">ORCID:</span> <span id="orcidLabel">
						<?php
						if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
							$sqlSelectName = "SELECT orc_id FROM author WHERE author_id = :author_id";
						
							$result = database_run($sqlSelectName, array(':author_id' => $id));

							if ($result) {
								
								if (count($result) > 0) {
									$user = $result[0];
									$orc_id = $user->orc_id;
								
									echo $orc_id;
								
									
					
								} else {
									echo "User not found.";
								}
							} else {
								echo "Unable to fetch user info.";
							}
						}
						?>
					</span></p>
				</div>
			</div>
		</div>
		<!--   -->
	</div>
	<!-- Popup Form -->
	<div class="popup-overlay" id="editForm">
		<div class="popup-form">
			<div class="form-header">
				<h4>Edit Profile</h4>
				<!-- <div class="edit-profile-btn">
					<button type="button" class="btn btn-outline-light" id="saveButton">Save</button>
					<button type="button" class="btn btn-outline-light" id="cancelBtn">Cancel</button>
				</div> -->
				<span class="close-icon" id="closeIcon">&times;</span>
			</div>
			<form id="form" method="POST" action="update-user.php">		
				<div class="form-content">
					<!-- <div class="edit-profile-pic">
					
					
					
						<img src="../images/capstone1.png" alt="Profile Picture" class="profile-pic" id="profileImage">
						<button type="button" style="border: none" id="changeProfileBtn"><i class="fas fa-camera change-icon"></i></button>
						<input type="file" id="selectProfile" style="display: none" accept="image/*">


					</div> -->
					<!-- Personal Information -->
					<div class="form-section">
						<h5>Personal Information</h5>
						<hr>
						<div class="row-form">
							<div class="form-row">
								<label for="firstName">First Name:</label>
								<!-- <label for="firstName">First Name: <span class="requiredFilled" style="color: red">*</span></label> -->
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT first_name FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$firstName = $user->first_name;
										
											echo '<input type="text" id="firstName" name="firstName" class="text-box" value="' . $firstName . '" required onchange="checkCredentialsagain();">';
							
										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
							
							</div>
							<div class="form-row">
								<label for="middleName">Middle Name: (Optional)</label>
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT middle_name FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$middle_name = $user->middle_name;
										
											echo '<input type="text" id="middleName" name="middleName" class="text-box"
											value="' . $middle_name . '" >';
										
							
										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
			
							
							</div>
							<div class="form-row">
								<label for="lastName">Last Name:</label>
								<!-- <label for="lastName">Last Name: <span class="requiredFilled" style="color: red">*</span></label> -->
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT last_name FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$last_name = $user->last_name;
										
											echo '<input type="text" id="lastName" name="lastName" class="text-box" value="' . $last_name . '" required onchange="checkCredentialsagain();">';

										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
			
								
							</div>
							<div class="form-row">
								<label for="affix">Affix: (Optional)</label>
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT affix FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$affix = $user->affix;
										
											echo '<input type="text" id="affix" name="affix" class="text-box"
											value="' . $affix . '" >';
										
											
							
										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
								
							</div>
							<div class="form-row">
								<label for="birthdate">Birth Date:</label>
								<!-- <label for="birthdate">Birth Date: <span class="requiredFilled" style="color: red">*</span></label> -->
							
								<?php
									if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
										$sqlSelectName = "SELECT birth_date FROM author WHERE author_id = :author_id";

										$result = database_run($sqlSelectName, array(':author_id' => $id));

										if ($result) {
											if (count($result) > 0) {
												$user = $result[0];
												$birth_date = $user->birth_date;

												// Output the selected option based on the user's gender
												echo '<input type="date" id="birthdate" name="birthdate" class="date-box" value="' . $birth_date . '" max="2010-12-31" required>';

											} else {
												echo "User not found.";
											}
										} else {
											echo "Unable to fetch user info.";
										}
									}
									?>
							</div>
							<div class="form-row">
							<label for="gender">Gender:</label>
								<!-- <label for="gender">Gender: <span class="requiredFilled" style="color: red">*</span></label> -->
								<select id="gender" name="gender" class="dropdown-box" required>
									<?php
									if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
										$sqlSelectName = "SELECT gender FROM author WHERE author_id = :author_id";

										$result = database_run($sqlSelectName, array(':author_id' => $id));

										if ($result) {
											if (count($result) > 0) {
												$user = $result[0];
												$gender = $user->gender;

												// Output the selected option based on the user's gender
												echo '<option value="' . $gender . '" selected>' . $gender . '</option>';
											} else {
												echo "User not found.";
											}
										} else {
											echo "Unable to fetch user info.";
										}
									}
									?>
									<option value="Male">Male</option>
									<option value="Female">Female</option>
								</select>

							</div>
							<div class="form-row">
							<label for="status">Status:</label>
								<!-- <label for="status">Status: <span class="requiredFilled" style="color: red">*</span></label> -->
								<select id="status" name="status" class="dropdown-box" required>
								
									<?php
									if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
										$sqlSelectName = "SELECT marital_status FROM author WHERE author_id = :author_id";

										$result = database_run($sqlSelectName, array(':author_id' => $id));

										if ($result) {
											if (count($result) > 0) {
												$user = $result[0];
												$marital_status = $user->marital_status;

												// Output the selected option based on the user's gender
												echo '<option value="' . $marital_status . '" selected>' . $marital_status . '</option>';
											} else {
												echo "User not found.";
											}
										} else {
											echo "Unable to fetch user info.";
										}
									}
									?>
									<option value="Single">Single</option>
									<option value="Married">Married</option>
									<option value="Divorced">Divorced</option>
									<option value="Widowed">Widowed</option>
								</select>
							</div>
							<div class="form-row">
							<label for="country">Country:</label>
								<!-- <label for="country">Country: <span class="requiredFilled" style="color: red">*</span></label> -->
								<select id="country" name="country" class="dropdown-box" required>
								</select>
							</div>
							<div class="form-row">
								
								<label for="phone_number">Phone Number: </label>
								<?php
								
									$sqlSelectName = "SELECT phone_number FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$phone_number = $user->phone_number;
										
											echo '<input type="text" id="phone_number" name="phone_number" maxlength="11" class="text-box"
											value="' . $phone_number . '">';
										
											
							
										} else {
											echo "User not found.";
										}
									} else {
										echo '<input type="text" id="phone_number" name="phone_number" maxlength="11" class="text-box">';
									}
								
								?>
								
							
							</div>
						</div>
						<!-- Add similar fields for Middle name, Last Name, Affix, Birth date, gender, status, country -->
					</div>

					<!-- Other Information -->
					<div class="form-section">
						<h5>Other Information</h5>
						<hr>
						<div class="row-form">
							<!-- <div class="form-row">
								<label for="email">E-mail:</label>
								<input type="email" id="email" name="email" class="other-text-box" value="<?php echo $email ?>" >
							</div> -->
							<div class="form-row">
								<label for="orcid">ORCID:</label>
								<!-- <label for="orcid">ORCID: <span class="requiredFilled" style="color: red">*</span></label> -->
								<?php
									if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
										$sqlSelectName = "SELECT orc_id FROM author WHERE author_id = :author_id";
										$result = database_run($sqlSelectName, array(':author_id' => $id));

										if ($result) {
											if (count($result) > 0) {
												$user = $result[0];
												$orc_id = $user->orc_id;
											
												echo '<input type="text" id="orcid" name="orcid" class="other-text-box" pattern="\d{4}-\d{4}-\d{4}-\d{4}" placeholder="(e.g., xxxx-xxxx-xxxx-xxxx)" value="' . $orc_id . '" onchange="checkCredentials(' . $id . ', this.value);">';

												echo '<input type="hidden" id="orcid_check_status" name="orcid_check_status" value="true">';

												echo '<script>window.onload = function() { checkCredentials(' . $id . ', "' . $orc_id . '"); }</script>';
											} else {
												echo "User not found.";
											}
										} else {
											echo "Unable to fetch user info.";
										}
									}
								?>
								<p>Don't have ORCID yet? <a href="https://orcid.org/register">Register Here</a></p>
							</div>
							<div class="form-row">
								<label for="affiliation">Affiliation:</label>
								<!-- <label for="affiliation">Affiliation: <span class="requiredFilled" style="color: red">*</span></label> -->
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT afiliations FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$afiliations = $user->afiliations;
										
											echo '<input type="text" list="universityList" id="affiliation" name="affiliation" class="text-box"
											value="' . $afiliations . '" >
											<datalist id="universityList">

											</datalist>
											';
										
											
							
										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
								
							</div>
							<div class="form-row">
							<label for="position">Position:</label>
								<!-- <label for="position">Position: <span class="requiredFilled" style="color: red">*</span></label> -->
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT position FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										
										if (count($result) > 0) {
											$user = $result[0];
											$position = $user->position;
										
											echo '<input type="text" id="position" name="position" class="text-box"
											value="' . $position . '" required>';
										
											
							
										} else {
											echo "User not found.";
										}
									} else {
										echo "Unable to fetch user info.";
									}
								}
								?>
							
							</div>
						</div>
						<!-- Add similar fields for ORCID, Affiliation, Position -->
					</div>

					<!-- About me -->
					<div class="form-section">
						<h5>About Me</h5>
						<hr>
						<div class="form-row">
						<label for="bio">Bio: (Optional)</label>
							<?php
							if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
								$sqlSelectName = "SELECT bio FROM author WHERE author_id = :author_id";

								$result = database_run($sqlSelectName, array(':author_id' => $id));

								if ($result) {
									if (count($result) > 0) {
										$user = $result[0];
										$bio = $user->bio;

										echo '<textarea id="bio" name="bio" class="bio-textarea" placeholder="Tell us about yourself" >' . $bio . '</textarea>';
									} else {
										echo "User not found.";
									}
								} else {
									echo "Unable to fetch user info.";
								}
							}
							?>

						</div>
						<!-- <br><br><br> -->
						<div class="form-row">
						<label for="fieldofexpertise">Field of Expertise:</label>
						<!-- <label for="fieldofexpertise">Field of Expertise: <span class="requiredFilled" style="color: red">*</span></label> -->
						<div>
							<input type="text" id="fieldofexpertise" name="fieldofexpertise" class="text-box">
							<button class="btn btn-primary" type="button" id="addExpertiseButton">Add</button>
						</div>
						</div>
						<div id="keywordContainer">

							<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT field_of_expertise FROM author WHERE author_id = :author_id";
								
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									if ($result) {
										if (count($result) > 0) {
											$user = $result[0];
											$field_of_expertise = $user->field_of_expertise;
									
											if (!empty($field_of_expertise)) {
												$expertiseArray = explode(', ', $field_of_expertise);
									
												foreach ($expertiseArray as $expertiseItem) {
													echo '<div class="keyword" id="expertise" name="expertise"><span>' . htmlspecialchars($expertiseItem) . '</span><span class="close-btn">x</span></div>';
												}
											}
										} else {
											echo '';
										}
									}
									else {
										echo "Unable to fetch user info.";
									}
								}
								?>
						</div>
						<input type="hidden" id="expertiseData" name="expertiseData" value="">

					</div>
					<!-- <div class="form-buttons">
						<button type="submit" class="btn btn-primary" id="saveButton">Save</button>
						<button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
					</div> -->
					<!-- <button type="button" class="btn btn-success btn-md" id="editBtn">Edit
						<span class="spinner-border spinner-border-sm" aria-hidden="true" style="display: none"></span>
					</button>
					<button type="button" class="btn btn-secondary btn-md" id="cancelBtn">Cancel
						<span class="spinner-border spinner-border-sm" aria-hidden="true" style="display: none"></span>
					</button> -->

					
					<!-- <input type="submit" class="btn btn-primary btn-md" id="saveButton" value="Save" disabled> -->
					<!-- <button type="submit" class="btn btn-primary btn-md" id="saveButton" value="Save" disabled>Save</button> -->
				</div>
				<div class="form-buttons">
					<button type="submit" class="btn btn-primary" id="saveButton">Save</button>
					<button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
				</div>
			</form>
		</div>
	</div>
	<section class="expertise">
		<!-- Expertise info here -->
		<div class="tab">
			<button class="tablinks" onclick="openTab(event, 'About')" id="defaultOpen">About</button>
			<!-- <button class="tablinks" onclick="openTab(event, 'Contributions')">Contributions</button>
			<button class="tablinks" onclick="openTab(event, 'Rewards')">Rewards</button> -->
			<button class="tablinks" onclick="openTab(event, 'Attainments')">Attainments</button>
			<button class="tablinks" onclick="openTab(event, 'Publications')">Publications</button>
			<button class="tablinks" onclick="openTab(event, 'Certificates')">Certificates</button>
		</div>


		<div id="About" class="tabcontent">
			<div id="about-container">
				<div id="logrecord-container">
					<div class="log-box"><h3>Join In Community Since:</h3>
						<?php
							if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
								$sqlSelectName = "SELECT date_added FROM author WHERE author_id = :author_id";

								$result = database_run($sqlSelectName, array(':author_id' => $id));

								if ($result) {
									if (count($result) > 0) {
										$user = $result[0];
										$date_added = $user->date_added;

										// Format the date as a string (assuming $birth_date is in 'YYYY-MM-DD' format)
										$formatted_date_addedd = date('F j, Y', strtotime($date_added));

										echo $formatted_date_addedd;
									} else {
										echo "User not found.";
									}
								} else {
									echo "Unable to fetch user info.";
								}
							}
						?>
					</div>
					<div class="log-box">
						<?php
							if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
								$sqlSelectName = "SELECT date_added FROM article WHERE author_id = :author_id ORDER BY date_added ASC LIMIT 1 ";

								$result = database_run($sqlSelectName, array(':author_id' => $id));

								if ($result) {
									if (count($result) > 0) {
										$user = $result[0];
										$date_added = $user->date_added;

										$formatted_date_added = date('F j, Y', strtotime($date_added));

										echo '<h3>Author Since:</h3> ' . $formatted_date_added;
									} else {
										echo "You did not submit any articles yet.";
									}
								} else {
									echo "You did not submit any articles yet.";
								}
							}
						?>
					</div>
					<div class="log-box">
						<h3>Reviewer Since: </h3> 
						
						<?php 
							$sqlStartReviewDate = "SELECT date_issued FROM reviewer_assigned WHERE author_id = $id ORDER BY date_issued ASC LIMIT 1 ";
							$result = database_run($sqlStartReviewDate);
							if ($result !== false) {
								foreach ($result as $row) {
									$dateIssued = new DateTime($row->date_issued);
									$formattedDate = $dateIssued->format('F j, Y');
									echo "<p class='date'>$formattedDate</p>";
								}
							} else {
								echo "<p class='not-found'>You are not a reviewer yet.</p>"; 
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
								if(isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true){
									echo $bio;
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
								if(isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true){
									echo $expertise;
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
						<tbody>	<tr>	
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
											echo '</tr>';
										}
									} else {
										echo '';
									}

								
								?>

							</tr>
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
									$sql = "SELECT user_points.email, user_points.action_engage, article.article_id, article.title, article.date, article.author, article.abstract, journal.journal, COUNT(CASE WHEN logs.type = 'read' THEN 1 END) AS read_count, COUNT(CASE WHEN logs.type = 'support' THEN 1 END) AS support_count FROM user_points JOIN article ON user_points.article_id = article.article_id JOIN journal ON journal.journal_id = article.journal_id LEFT JOIN logs ON article.article_id = logs.article_id WHERE user_points.email = :email
									AND (user_points.action_engage = 'Co-Author' OR user_points.action_engage = 'Primary Contact') AND article.status = 1 GROUP BY article.article_id";
									$result = database_run($sql, array('email' => $email));  

									if($result) {
										foreach($result as $row) {
											echo '<tr>';
											$title = $row->title;
											$date = $row->date;
											$read = $row->read_count; 
											$support = $row->support_count;
										
											echo '<td><a href="../PHP/article-details.php?articleId=' . $row->article_id . '">' . $title . '</a></td>';
											echo '<td>' . $date . '</td>';
											echo '<td>' . $read . '</td>';
											echo '<td>' . $support . '</td>';
											echo '</tr>';
										}
									}
								?>

							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div id="Certificates" class="tabcontent">
			<div class="publications-container">
				<div class="header-achievements">
					<h4>Certificate Received</h4>
				</div>
				<div class="table-container">
					<table class="publications-table">
						<thead>
							<tr>
								<th>Details</th>
								<th>Journal</th>
								<th>Date</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<tr>	
							<?php
										$sqlAchievements = "
										

											(SELECT 'Published an Article' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Published an Article' AND user_points.user_id = :user_id AND article.status = 1)
											
											UNION
											
											(SELECT 'Reviewed Article Published' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
											JOIN article ON reviewer_assigned.article_id = article.article_id
											WHERE article.status = 1 AND reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed Article Published' AND user_points.user_id = :author_id)
											
											UNION
											
											(SELECT 'Submitted an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Submitted an Article' AND user_points.user_id = :user_id)
											
											UNION
											
											(SELECT 'Reviewed an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
											JOIN article ON reviewer_assigned.article_id = article.article_id
											WHERE reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed an Article' AND user_points.user_id = :author_id)

											UNION
								
											(SELECT 'Co-Author' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Co-Author' AND article.status <= 6 AND user_points.email = :email)

											UNION
									
											(SELECT 'Primary Contact' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Primary Contact' AND article.status <= 6 AND user_points.email = :email)

											ORDER BY date DESC
										";

										
										$result = database_run($sqlAchievements,array('author_id' => $id, 'user_id' => $id, 'email' => $email));

										if ($result !== false) {
											foreach ($result as $row) {
												$journalMapping = [
													'1' => 'The Gavel',
													'2' => 'The Lamp',
													'3' => 'The Star',
												];
										
												// Check if the action is one of the desired actions
												if ($row->action_engage === 'Published an Article' || $row->action_engage === 'Reviewed Article Published') {
													echo '<tr>';
													echo '<td>' . $row->action_engage .'</td>';
													echo '<td>' . $journalMapping[$row->journal_id] .'</td>';
													echo '<td style="display: none">' . $row->title .'</td>';
													$formattedDate = date('F j, Y', strtotime($row->date)); // Formatting without time
													echo '<td>' . $formattedDate . '</td>';
													// echo '<td style="color: green;">You earned ' . $row->point_earned . ' Community Heart</td>';
													echo '<td><button type="button" class="view-button" onclick="updateEngagementTitle(\'' . htmlspecialchars($row->title) . '\', \'' . $journalMapping[$row->journal_id] . '\', \'' . $formattedDate . '\')">View</button></td>';
													echo '</tr>';
	
												}
											}
										} else {
											echo '<p style="margin-left: 50px; padding-top: 20px">You have no Achievements yet</p>';
										}
										
									?>

							</tr>
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
								<?php
								if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
									$sqlSelectName = "SELECT title FROM article WHERE author_id = :author_id AND status = 1";
									$result = database_run($sqlSelectName, array(':author_id' => $id));

									$sqlSelectReviewed = "SELECT action_engage FROM user_points WHERE user_id = :user_id AND action_engage = 'Reviewed Article Published'";
									$resultReviewd = database_run($sqlSelectReviewed, array(':user_id' => $id));

									if ($result !== false && $resultReviewd === false){
										$publicationCount = count($result);

										// Determine badge level and progress percentage
										$badgeLevel = 'None';
										$badgeProgress = 0;

										if ($publicationCount >= 1 && $publicationCount <= 5) {
											$badge = '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
											$badgeLevel = 'Bronze';
											$remainingArticles = 5 - $publicationCount;
											$badgeProgress = ($publicationCount / 5) * 100;
										} elseif ($publicationCount >= 6 && $publicationCount <= 9) {
											$badge = '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
											$badgeLevel = 'Silver';
											$remainingArticles = 10 - $publicationCount;
											$badgeProgress = (($publicationCount - 5) / 5) * 100;
										} elseif ($publicationCount >= 10) {
											$badge = '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
											$badgeLevel = 'Gold';
											$remainingArticles = 0; // No remaining articles needed for Gold level
											$badgeProgress = 100;
										}
										echo $badge;
										echo '<div class="progresshover">';
										echo "<p style='color: white'>PUBLICATION BADGE (Level: $badgeLevel)</p>";
										echo "<div class='progress' style='height: 3px; background-image: linear-gradient(to right, #ffd700 " . round($badgeProgress, 2) . "%, red " . round($badgeProgress, 2) . "%, red 100%);'></div>";
										echo "<p style='color: white'>Progress: " . round($badgeProgress, 2) . "%</p>";

										// Display remaining articles message
										if ($remainingArticles > 0) {
											echo "<p style='color: white; margin-top: -20px; font-size: 11px;'>$remainingArticles more article";
											echo ($remainingArticles !== 1) ? 's' : ''; // Add plural "s" if needed
											echo " to level up to ";
											echo ($badgeLevel === 'Bronze') ? 'Silver' : 'Gold';
											echo " badge</p>";
										}

										echo '</div>';
									}elseif($resultReviewd !== false && $result === false){
										$reviewedPublishCount = count($resultReviewd);

										// Determine badge level and progress percentage
										$badgeLevelReviewed = 'None';
										$badgeProgressReviewed = 0;

										if ($reviewedPublishCount >= 1 && $reviewedPublishCount <= 5) {
											$badgeReviewed = '<div class="badge-box revBrozeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Bronze';
											$remainingArticlesReviewed = 5 - $reviewedPublishCount;
											$badgeProgressReviewed = ($reviewedPublishCount / 5) * 100;
										} elseif ($reviewedPublishCount >= 6 && $reviewedPublishCount <= 9) {
											$badgeReviewed = '<div class="badge-box revSilverBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Silver';
											$remainingArticlesReviewed = 10 - $reviewedPublishCount;
											$badgeProgressReviewed = (($reviewedPublishCount - 5) / 5) * 100;
										} elseif ($reviewedPublishCount >= 10) {
											$badgeReviewed = '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Gold';
											$remainingArticlesReviewed = 0; // No remaining articles needed for Gold level
											$badgeProgressReviewed = 100;
										}
										echo $badgeReviewed;
										echo '<div class="progressRevHover">';
										echo "<p style='color: white'>REVIEWER BADGE (Level: $badgeLevelReviewed)</p>";
										echo "<div class='progress' style='height: 3px; background-image: linear-gradient(to right, #ffd700 " . round($badgeProgressReviewed, 2) . "%, red " . round($badgeProgressReviewed, 2) . "%, red 100%);'></div>";
										echo "<p style='color: white'>Progress: " . round($badgeProgressReviewed, 2) . "%</p>";

										// Display remaining articles message
										if ($remainingArticlesReviewed > 0) {
											echo "<p style='color: white; margin-top: -20px; font-size: 11px;'>$remainingArticlesReviewed more article";
											echo ($remainingArticlesReviewed !== 1) ? 's' : ''; // Add plural "s" if needed
											echo " to level up to ";
											echo ($badgeLevelReviewed === 'Bronze') ? 'Silver' : 'Gold';
											echo " badge</p>";
										}

										echo '</div>';
									}elseif($result !== false && $resultReviewd !== false){
										
										$publicationCount = count($result);

										// Determine badge level and progress percentage
										$badgeLevel = 'None';
										$badgeProgress = 0;

										if ($publicationCount >= 1 && $publicationCount <= 5) {
											$badge = '<div class="badge-box pubBrozeBadge" style="background-image: url(\'../images/first_publication_badges.png\');"></div>';
											$badgeLevel = 'Bronze';
											$remainingArticles = 5 - $publicationCount;
											$badgeProgress = ($publicationCount / 5) * 100;
										} elseif ($publicationCount >= 6 && $publicationCount <= 9) {
											$badge = '<div class="badge-box pubSilverBadge" style="background-image: url(\'../images/second_publication_badges.png\');"></div>';
											$badgeLevel = 'Silver';
											$remainingArticles = 10 - $publicationCount;
											$badgeProgress = (($publicationCount - 5) / 5) * 100;
										} elseif ($publicationCount >= 10) {
											$badge = '<div class="badge-box pubGoldBadge" style="background-image: url(\'../images/third_publication_badges.png\');"></div>';
											$badgeLevel = 'Gold';
											$remainingArticles = 0; // No remaining articles needed for Gold level
											$badgeProgress = 100;
										}
										echo $badge;
										echo '<div class="progresshover">';
										echo "<p style='color: white'>PUBLICATION BADGE (Level: $badgeLevel)</p>";
										echo "<div class='progress' style='height: 3px; background-image: linear-gradient(to right, #ffd700 " . round($badgeProgress, 2) . "%, red " . round($badgeProgress, 2) . "%, red 100%);'></div>";
										echo "<p style='color: white'>Progress: " . round($badgeProgress, 2) . "%</p>";

										// Display remaining articles message
										if ($remainingArticles > 0) {
											echo "<p style='color: white; margin-top: -20px; font-size: 11px;'>$remainingArticles more article";
											echo ($remainingArticles !== 1) ? 's' : ''; // Add plural "s" if needed
											echo " to level up to ";
											echo ($badgeLevel === 'Bronze') ? 'Silver' : 'Gold';
											echo " badge</p>";
										}

										echo '</div>';
										

										$reviewedPublishCount = count($resultReviewd);

										// Determine badge level and progress percentage
										$badgeLevelReviewed = 'None';
										$badgeProgressReviewed = 0;

										if ($reviewedPublishCount >= 1 && $reviewedPublishCount <= 5) {
											$badgeReviewed = '<div class="badge-box revBrozeBadge" style="background-image: url(\'../images/first_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Bronze';
											$remainingArticlesReviewed = 5 - $reviewedPublishCount;
											$badgeProgressReviewed = ($reviewedPublishCount / 5) * 100;
										} elseif ($reviewedPublishCount >= 6 && $reviewedPublishCount <= 9) {
											$badgeReviewed = '<div class="badge-box revSilverBadge" style="background-image: url(\'../images/second_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Silver';
											$remainingArticlesReviewed = 10 - $reviewedPublishCount;
											$badgeProgressReviewed = (($reviewedPublishCount - 5) / 5) * 100;
										} elseif ($reviewedPublishCount >= 10) {
											$badgeReviewed = '<div class="badge-box revGoldBadge" style="background-image: url(\'../images/thirdd_review_badges.png\');"></div>';
											$badgeLevelReviewed = 'Gold';
											$remainingArticlesReviewed = 0; // No remaining articles needed for Gold level
											$badgeProgressReviewed = 100;
										}
										echo $badgeReviewed;
										echo '<div class="progressRevHover">';
										echo "<p style='color: white'>REVIEWER BADGE (Level: $badgeLevelReviewed)</p>";
										echo "<div class='progress' style='height: 3px; background-image: linear-gradient(to right, #ffd700 " . round($badgeProgressReviewed, 2) . "%, red " . round($badgeProgressReviewed, 2) . "%, red 100%);'></div>";
										echo "<p style='color: white'>Progress: " . round($badgeProgressReviewed, 2) . "%</p>";

										// Display remaining articles message
										if ($remainingArticlesReviewed > 0) {
											echo "<p style='color: white; margin-top: -20px; font-size: 11px;'>$remainingArticlesReviewed more article";
											echo ($remainingArticlesReviewed !== 1) ? 's' : ''; // Add plural "s" if needed
											echo " to level up to ";
											echo ($badgeLevelReviewed === 'Bronze') ? 'Silver' : 'Gold';
											echo " badge</p>";
										}

										echo '</div>';

									}else {
										echo "You have no badges yet";
									}
								}
								?>



											
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
								$sqlTotalContribution = "SELECT * FROM user_points WHERE email = :email";

								$params = array('email' => $email);
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
									$sqlReviewedArticles = "SELECT COUNT(*) as total_reviewed FROM reviewer_assigned  WHERE author_id = $id AND answer = 1" ;

									$result = database_run($sqlReviewedArticles);

									if ($result !== false && isset($result[0]->total_reviewed)) {
										$totalArticles = $result[0]->total_reviewed;
										echo $totalArticles;
									} else {
										echo "0"; 
									}
								?>	
								<span class="increase">

								<?php 
								$sqlReviewedArticles = "SELECT COUNT(*) as total_reviewed FROM reviewer_assigned  WHERE author_id = $id AND answer = 1" ;

								$result = database_run($sqlReviewedArticles);

								if ($result !== false && isset($result[0]->total_reviewed)) {
									$totalArticles = $result[0]->total_reviewed;

									$percentageIncrease = 0;

								
									$increasedCount = $totalArticles * (2 + ($percentageIncrease / 100));

									echo $percentageIncrease .''. "%";
								} else {
									echo "0"; 
								}
								?>
								
								</span></p>
							</div>
						</div> 
						<div class="stats-section">
							<div class="stat-card top-card">
								<h2>Total Submissions</h2>
								<p>
								<?php 
									$sqlCountAritcle = "SELECT COUNT(*) as total_articles FROM article WHERE author_id = $id" ;

									$result = database_run($sqlCountAritcle);

									if ($result !== false && isset($result[0]->total_articles)) {
										$totalArticles = $result[0]->total_articles;
										echo $totalArticles;
									} else {
										echo "0"; 
									}
								?>

								<span class="increase">
								<?php 
								$sqlCountAritcle = "SELECT COUNT(*) as total_articles FROM article WHERE author_id = $id" ;

								$result = database_run($sqlCountAritcle);

								if ($result !== false && isset($result[0]->total_articles)) {
									$totalArticles = $result[0]->total_articles;

									$percentageIncrease = 0;

								
									$increasedCount = $totalArticles * (2 + ($percentageIncrease / 100));

									echo $percentageIncrease .''. "%";
								} else {
									echo "0"; 
								}
								?>
								</span></p>
				
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
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sqlAchievements = "
										

											(SELECT 'Published an Article' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Published an Article' AND user_points.user_id = :user_id AND article.status = 1)
											
											UNION
											
											(SELECT 'Reviewed Article Published' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
											JOIN article ON reviewer_assigned.article_id = article.article_id
											WHERE article.status = 1 AND reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed Article Published' AND user_points.user_id = :author_id)
											
											UNION
											
											(SELECT 'Submitted an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Submitted an Article' AND user_points.user_id = :user_id)
											
											UNION
											
											(SELECT 'Reviewed an Article' as action_engage, article.title, article.journal_id, NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN reviewer_assigned ON user_points.user_id = reviewer_assigned.author_id
											JOIN article ON reviewer_assigned.article_id = article.article_id
											WHERE reviewer_assigned.accept = 1 AND reviewer_assigned.answer = 1 AND user_points.action_engage = 'Reviewed an Article' AND user_points.user_id = :author_id)

											UNION
								
											(SELECT 'Co-Author' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Co-Author' AND article.status <= 6 AND user_points.email = :email)

											UNION
									
											(SELECT 'Primary Contact' as action_engage, article.title, article.journal_id,NULL as status, user_points.date, user_points.point_earned
											FROM user_points
											JOIN article ON user_points.article_id = article.article_id
											WHERE user_points.action_engage = 'Primary Contact' AND article.status <= 6 AND user_points.email = :email)

											ORDER BY date DESC
										";

										
										$result = database_run($sqlAchievements,array('author_id' => $id, 'user_id' => $id, 'email' => $email));

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
												echo '<td style="color: green;">You earned ' . $row->point_earned . ' Community Heart</td>';
												echo '<td><button type="button" class="view-button" onclick="updateEngagementTitle(\'' . htmlspecialchars($row->title) . '\', \'' . $journalMapping[$row->journal_id] . '\', \'' . $formattedDate . '\')">View</button></td>';
												echo '</tr>';

												
											}
										}else{
											echo '<p style="margin-left: 50px; padding-top: 20px">You have no Achievements yet</p>';
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="popup-container" id="popup">
					<h6>Owned Badges</h6>
					<div class="badge-popup">
						<!-- Badges go here -->
						<div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge2.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge3.jpg');"></div>
						<div class="badge-box" style="background-image: url('../images/badge1.jpg');"></div>
					</div>
					<p>Click Badges to Learn More</p>
				</div>
			</div>
		</div>
	</section>
	<section class="published-articles">
		<div class="fluid-container">
			<div>
				<h4>Your Published Articles</h4>
				<div class="articles-container">
					
					<?php 
						$sql = "SELECT article.article_id, article.title, article.author, article.abstract, journal.journal 
								FROM article 
								JOIN journal ON journal.journal_id = article.journal_id 
								WHERE article.author_id = $id AND article.status = 1";

						$result = database_run($sql);

						$sqlSelectProfile = "SELECT first_name, last_name, birth_date, gender, marital_status, orc_id, afiliations, position, field_of_expertise, country FROM author WHERE author_id = :author_id";

						$resultProfile = database_run($sqlSelectProfile, array(':author_id' => $id));

						if ($result !== false) {
							foreach ($result as $row) {
								echo '<div class="article" data-article-id="' . $row->article_id . '">';
								echo '<p class="h6">' . $row->title . '</p>';
								echo '<div class="article-info">';
								echo '<p class="info" style="display="inline-block; width: auto">' . $row->journal . '</p>';
								echo '<span class="views" style="display="inline-block; width: auto"> 143</span>';
								echo '<p class="author">' .$row->author .  '</p>';
								echo '<p class="article-content">' . $row->abstract .'</p>';
								echo '</div>';
								echo '<button type="button" class="btn btn-primary btn-md btn-article" onclick="openArticleInNewTab(' . $row->article_id . ')" style=" border: 2px #115272 solid;
									background-color: transparent;
									border-radius: 20px;
									color: #115272;
									width: 100%;">Read Article</button>';
								echo '</div>';
							}
						}elseif ($resultProfile) {
							if (count($resultProfile) > 0) {
								$userProfile = $resultProfile[0];
		
								// Check for the presence of all required fields
								$requiredFields = ['first_name', 'last_name', 'birth_date', 'gender', 'marital_status', 'orc_id', 'afiliations', 'position', 'field_of_expertise', 'country'];
		
								$profileComplete = true;
								foreach ($requiredFields as $field) {
									if (empty($userProfile->$field)) {
										$profileComplete = false;
										break;
									}
								}
								if ($profileComplete) {
									echo "<div class='no-article-message'>
											<p>You don't have published article yet, want to published article? Click here <a href='ex_submit.php'>Submit an Article</a></p>
										</div>"; 
								} else {
								echo "<p>You don't have published article yet, want to published article? Click here <a href='#' id='link'>Submit an Article</a></p>"; 
									echo "<script>
											document.getElementById('link').addEventListener('click', function(event){
												Swal.fire({
													icon: 'warning',
													text: 'Please complete the required data in your profile details before submitting a paper'
												});
											});
										</script>";
								}                        
							} else {
								echo "User not found.";
							}
						}
					?>

					<!-- <div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div> -->

					<!-- <div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div>
					<div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div>

					<div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div>

					<div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div>

					<div class="article">
						<p class="h6">Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</p>
						<div class="article-info">
							<p class="info">THE LAMP</p>
							<span class="views"></span>103 views
						</div>
						<p class="author">By Jane Delacruz</p>
						<p class="article-content">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas.</p>
						<button class="btn btn-primary btn-md btn-article"  style=" border: 2px #115272 solid;
						background-color: transparent;
						border-radius: 20px;
						color: #115272;
						width: 100%;">Read Article</button>
					</div> -->
				</div>
				<div class="contibutedArticleContainer">
					<h4>Article you contributed</h4>
					<?php 
						$sql = "SELECT user_points.email, user_points.action_engage, article.article_id, article.title, article.author, article.abstract, journal.journal FROM user_points JOIN article ON user_points.article_id = article.article_id JOIN journal ON journal.journal_id = article.journal_id WHERE user_points.email = :email AND (user_points.action_engage = 'Co-Author' OR user_points.action_engage = 'Primary Contact') AND article.status = 1;";

						$result = database_run($sql, array('email' => $email));	

						if ($result !== false) {
							foreach ($result as $row) {
								echo '<div class="article" data-article-id="' . $row->article_id . '">';
								echo '<p class="h6">' . $row->title . '</p>';
								echo '<div class="article-info">';
								echo '<p class="info" style="display="inline-block; width: auto">' . $row->journal . '</p>';
								echo '<span class="views" style="display="inline-block; width: auto"> 143</span>';
								echo '<p class="author">' .$row->author .  '</p>';
								echo '<p class="article-content">' . $row->abstract .'</p>';
								echo '</div>';
								echo '<button type="button" class="btn btn-primary btn-md btn-article" onclick="openArticleInNewTab(' . $row->article_id . ')" style=" border: 2px #115272 solid;
									background-color: transparent;
									border-radius: 20px;
									color: #115272;
									width: 100%;">Read Article</button>';
								echo '</div>';
							}
						}else{
							echo "<div class='no-article-message'>
										<p>You have not contributed to any article yet</p>
									</div>"; 
						}
						
					?>
				</div>
			</div>
		</div>
	</section>
	<section>
		<h4 class="mt-5">Most Engaged Articles</h4>
		<div class="articles-container" id="articleDetailsContainer">
		</div>
		<h4 class="mt-5">Liked Articles</h4>
		<div class="articles-container flex-wrap" style="max-height:80vh;overflow-y:scroll;" id="likesContainer">
		</div>
		
		<!-- <div class="continue-reading-article-container">
			<div class="continue-reading-article-details">
				<h6 style="color: #115272;"><strong>Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</strong></h6>
				<p style="color: #454545;">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas. </p>
				<div class="continue-reading-keywords">

				</div>
			</div>
			<div class="continue-reading-article-stats">
				<div class="continue-reading-stats-container">
					<div class="continue-reading-view-download">
						<p class="continue-reading-stats-values" style="color: #115272;">99</p>
						<p class="continue-reading-stats-labels" style="color: #959595;">VIEWS</p>
					</div>
					<div class="continue-reading-view-downloads">
						<p class="continue-reading-stats-values" style="color: #115272;">99</p>
						<p class="continue-reading-stats-labels" style="color: #959595;">DOWNLOADS</p>
					</div>
				</div>
				<hr style="border-top: 1px solid #ccc; margin: 10px 0;">
				<div class="continue-reading-published-infos">
					<h6 class="continue-reading-publish-labels" style="color: #115272;"><strong>Published in The Gavel</strong></h6>
					<p class="continue-reading-authors" style="color: #959595;">By Jane Delacruz</p>
				</div>
			</div>
		</div>
		
		<div class="continue-reading-article-container">
			<div class="continue-reading-article-details">
				<h6 style="color: #115272;"><strong>Blockchain Beyond Cyptocurrency: Transforming Industries with Distributed Ledger Technology</strong></h6>
				<p style="color: #454545;">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nemo sint facilis nihil possimus, illum ullam. Illo voluptatem totam repellendus voluptas. </p>
				<div class="continue-reading-keywords">

				</div>
			</div>
			<div class="continue-reading-article-stats">
				<div class="continue-reading-stats-container">
					<div class="continue-reading-view-download">
						<p class="continue-reading-stats-values" style="color: #115272;">99</p>
						<p class="continue-reading-stats-labels" style="color: #959595;">VIEWS</p>
					</div>
					<div class="continue-reading-view-downloads">
						<p class="continue-reading-stats-values" style="color: #115272;">99</p>
						<p class="continue-reading-stats-labels" style="color: #959595;">DOWNLOADS</p>
					</div>
				</div>
				<hr style="border-top: 1px solid #ccc; margin: 10px 0;">
				<div class="continue-reading-published-infos">
					<h6 class="continue-reading-publish-labels" style="color: #115272;"><strong>Published in The Gavel</strong></h6>
					<p class="continue-reading-authors" style="color: #959595;">By Jane Delacruz</p>
				</div>
			</div>
		</div> -->
	</section>
		
		
		<!-- <div class="featured-updates-container">
			<h2>Featured Updates</h2>
			<div class="ex-featured">
				<div class="example-featured">
					<p><b>USaid.Gov</b><br>Through a USAID grant awarded to the Quezon City University, local government officials of Quezon City, youth council members, and leaders of local community organizations compl....</p>
					<div class="img-featured mb-3">
						<img src="../images/featured.png" alt="">
						<a href="">USAID Trains Quezon City Barangay Leaders to Impro...</a>
					</div>
				</div>

				<div class="example-featured">
					<p><b>USaid.Gov</b><br>Through a USAID grant awarded to the Quezon City University, local government officials of Quezon City, youth council members, and leaders of local community organizations compl....</p>
					<div class="img-featured mb-3">
						<img src="../images/featured.png" alt="">
						<a href="">USAID Trains Quezon City Barangay Leaders to Impro...</a>
					</div>
				</div>
			</div>
			<button type="button" class="btn tbn-primary btn-md" id="show-more">Show more</button>
		</div> -->
</div>


<div class="container-fluid mt-5" id="certContainerHead" style="display: none; align-items: center !important; width: 889px; height: 500px; margin-left: auto;
margin-right: auto; padding: 0; background-color: red;">
    <div class="cert-container d-flex justify-content-center align-items-center">
		<?php 
			echo '<img class="imgCert" id="cert1" src="../images/qcuj-reviewer-certificate.png" alt="cert">'
		?>
        
		<div class="cert-category">
			<p class="h2" id="category"></p>
			<p class="issn" id="iss1"></p>
			<!-- <p class="h6" id="awardee">This Certificate is Awarded to</p> -->
		</div>
		<div class="cert-rev-details">
			<p class="h1" id="awardeeName">
					<?php 
					echo $first_name . ' ' . $middle_name . ' ' . $last_name	
					?>
				</p>
				<!-- <p class="h6" id="engagement">For participating in the peer review process for the article titled:</p> -->
				<p class="h3" id="engagementTitle"></p>
				<p class="revDate" id="revDate"></p>
			</div>
    </div>
</div>




<div class="container-fluid mt-5" id="certPublishedHead" style="display: none; align-items: center !important; width: 889px; height: 500px; margin-left: auto;
margin-right: auto; padding: 0;">
    <div class="cert-container d-flex justify-content-center align-items-center">
        <img class="imgCert" id="cert2" src="../images/qcuj-publication-certificate.png" alt="cert">

			<div class="articlePubInfo">
				<p class="h2" id="categoryPublished"></p>
				<p class="issn" id="iss2"></p>
			</div>
			
			<!-- <p class="h6" id="awardeePublished" style="
			font-family: 'Times New Roman', Times, serif;
			font-weight: bold;
			margin-left: 80px;
			margin-top: 25px;">This Certificate is Awarded to</p> -->
			<div class="pubInfo">
				<p class="h1" id="awardeeNamePublished">
					<?php 
					echo $first_name . ' ' . $middle_name . ' ' . $last_name	
					?>
				</p>
				<!-- <p class="h6" id="engagementPublished" style="    
				font-family: 'Times New Roman', Times, serif;
				font-weight: bold;
				margin-left: 80px;">For successfully publishing the article titled:</p> -->
				<p class="h3" id="engagementTitlePublished"></p>
				<p class="publishdate" id="publishdate"></p>
			</div>
    </div>
</div>
<div id="loadingOverlay">
    <div id="loadingSpinner"></div>
</div>



	


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
	function showAlert() {
		Swal.fire({
		icon: 'info',
		title: 'Profile Incomplete',
		text: 'Please complete the required details in profile to submit article'
		});
	}
	</script>
	<script src="jquery-3.7.1.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="../JS/reusable-header.js"></script>
    <script src="../JS/user-dashboard.js"></script>
	<script src="../JS/user-account.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	
	<script>



function saveProfile() {

  const fileInput = document.getElementById('fileInput');
  const profileImage = document.getElementById('profileImage');
  const imageModal = document.getElementById('imageModal');
  const selectedImagePreview = document.getElementById('selectedImagePreview');
  const id = <?php echo $id; ?>;

  if (fileInput.files.length > 0) {
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('id', id);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../PHP/user-profile.php', true);

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        profileImage.src = selectedImagePreview.src;
        imageModal.style.display = 'none';

        Swal.fire({
          icon: 'success',
          text: 'Change profile picture successfully',
          showConfirmButton: true
        });
      }
    };

    xhr.send(formData);
  } else {
    imageModal.style.display = 'none';
  }
}

function openArticleInNewTab(articleId) {
        window.open(`../PHP/article-details.php?articleId=${articleId}`, '_blank');
    }
  const apiURL = "https://web-production-cecc.up.railway.app/api/recommendations/";

  // Assuming you have the author_id stored in a PHP variable $id
  const authorId = <?php echo $_SESSION['id']; ?>;

  // Make a GET request to the API endpoint
  fetch(`${apiURL}${authorId}`)
    .then(response => response.json())
    .then(data => {
      // Get the details of the first 3 articles from the history
      const latestArticleDetails = data.history;
	  const likes = data.likes;
      // Update the container with the latest article details
      const articleDetailsContainer = document.getElementById('articleDetailsContainer');
      articleDetailsContainer.innerHTML = latestArticleDetails.map(article => {
        return `
          <div class="article d-flex flex-column" style="justify-content:space-between; min-height:380px" data-article-id="${article.article_id}">
            <div class="">
              <h6 class="historyTitle" style="color: #115272;"><strong>${article.title}</strong></h6>
              <p class="historyAbstract" style="color: #454545;">${article.abstract}</p>
              <div class="continue-reading-keywords"></div>
            </div>
			<div>
				<span style="border-radius: 100px;font-size: small;float: right;">Last Viewed: ${article.last_read.slice(0,22)}</span>
				<span style="color:#0d7ff8;font-weight: bold;background: aliceblue;padding: 0.5em;border-radius: 100px;font-size: small;float: right;">Interacted ${article.user_interactions} times</span>
			</div>
          </div>
        `;
      }).join('');
      
	  const likesContainer = document.getElementById('likesContainer');
      likesContainer.innerHTML = likes.map(article => {
        return `
          <div class="article d-flex flex-column" style="justify-content:space-between; min-height:140px; width:47%;" data-article-id="${article.article_id}">
            <div class="">
              <h6 class="historyTitle" style="color: #115272;"><strong>${article.title}</strong></h6>
              <p class="historyAbstract" style="color: #454545;">${article.abstract.slice(0,100)}...</p>
              <div class="continue-reading-keywords"></div>
            </div>
          </div>
        `;
      }).join('');

      // Add click event listener to each article container
      const articleContainers = document.querySelectorAll('.article');
      articleContainers.forEach(container => {
        container.addEventListener('click', function() {
          const articleId = this.getAttribute('data-article-id');
          window.open(`../PHP/article-details.php?articleId=${articleId}`, '_blank');
        });
      });
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });

	document.addEventListener('DOMContentLoaded', function () {
  const countrySelect = document.getElementById('country');
//   countrySelect.disabled = true;


  fetch('https://restcountries.com/v3.1/all')
      .then(response => response.json())
      .then(data => {
       
          if (data.find(country => country.name.common === '<?php
							if (isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true) {
								$sqlSelectName = "SELECT country FROM author WHERE author_id = :author_id";
							
								$result = database_run($sqlSelectName, array(':author_id' => $id));

								if ($result) {
									
									if (count($result) > 0) {
										$user = $result[0];
										$country = $user->country;
									
										echo $country;
									
										
						
									} else {
										echo "User not found.";
									}
								} else {
									echo "Unable to fetch user info.";
								}
							}
							?>')) {
              const userCountryOption = document.createElement('option');
              userCountryOption.value = '<?php echo $country ?>';
              userCountryOption.textContent = '<?php echo $country ?>';
              countrySelect.appendChild(userCountryOption);
          }

      
          data.forEach(country => {
              const option = document.createElement('option');
              option.value = country.name.common;
              option.textContent = country.name.common;
              countrySelect.appendChild(option);
          });

         
      })
      .catch(error => console.error('Error fetching countries:', error));
});


document.getElementById('cancelBtn').addEventListener('click', function(event) {
    const editForm = document.getElementById('editForm');
    const firstNameInput = document.getElementById('firstName');
    const middleName = document.getElementById('middleName');
    const lastName = document.getElementById('lastName');
    const affix = document.getElementById('affix');
    const birthdate = document.getElementById('birthdate');
    const gender = document.getElementById('gender');
    const status = document.getElementById('status');
    const country = document.getElementById('country');
    const orcid = document.getElementById('orcid');
    const affiliation = document.getElementById('affiliation');
    const position = document.getElementById('position');
    const bio = document.getElementById('bio');
    const keywordContainer = document.getElementById('keywordContainer');

    // Hide the edit form
    editForm.style.display = 'none';

    // Set values for other inputs
    firstNameInput.value = "<?php echo htmlspecialchars($firstName); ?>";
    middleName.value = "<?php echo htmlspecialchars($middle_name); ?>";
    lastName.value = "<?php echo htmlspecialchars($last_name); ?>";
    affix.value = "<?php echo htmlspecialchars($affix); ?>";
    birthdate.value = "<?php echo htmlspecialchars($birthday); ?>";
    gender.value = "<?php echo htmlspecialchars($gender); ?>";
    status.value = "<?php echo htmlspecialchars($marital_status); ?>";
    country.value = "<?php echo htmlspecialchars($country); ?>";
    orcid.value = "<?php echo htmlspecialchars($orc_id); ?>";
    affiliation.value = "<?php echo htmlspecialchars($afiliations); ?>";
    position.value = "<?php echo htmlspecialchars($position); ?>";
    bio.value = "<?php echo htmlspecialchars($bio); ?>";

    // Set the expertise container content to the default expertise value
    keywordContainer.innerHTML = '<?php echo '<div class="keyword" id="expertise" name="expertise"><span>' . htmlspecialchars($expertise) . '</span><span class="close-btn">x</span></div>'; ?>';
});




document.addEventListener('DOMContentLoaded', function () {
    // Attach click event listener to all elements with class 'view-button'
    var viewButtons = document.querySelectorAll('.view-button');
    const reviewerCert = document.getElementById('certContainerHead');
	const authorCert = document.getElementById('certPublishedHead');


    viewButtons.forEach(function (button) {
        button.addEventListener('click', function () {

            reviewerCert.style.display = 'block';
			authorCert.style.display = 'block';

            // Find the corresponding achievement row
            var achievementRow = button.closest('tr');
            
            // Get the action_engage and title values
            var actionEngage = achievementRow.querySelector('td:first-child').textContent.trim();
            var title = achievementRow.querySelector('td:nth-child(3)').textContent.trim();

            if (actionEngage === 'Submitted an Article') {
                Swal.fire({
					title: "<span class='heart-icon'>&#x2764;</span>",
                    html: "<p style='font-weight: bold'>You've earned 1 Community Heart for submitting an article.</p>" + 
						"<p>Title: " + title + "</p>",
                    // imageUrl: "../images/qcu-bg.jpg",
                    // imageWidth: 400,
                    // imageHeight: 200,
                    // imageAlt: "Custom image",
                    didOpen: function () {
                        // This will be executed when the Swal modal is closed
                        reviewerCert.style.display = 'none';
						authorCert.style.display = 'none';
                    }
                });
            } else if (actionEngage === 'Reviewed Article Published') {
				const certContainer = document.getElementById('certContainerHead');

				html2canvas(certContainer).then(canvas => {
					const imageUrl = canvas.toDataURL();

					function handleDownload() {
                        var link = document.createElement('a');
                        link.download = 'Reviewer-Certificate.png';
                        link.href = imageUrl;
                        link.click();
                    }

					Swal.fire({
						html: "<p><strong>You've earned 3 Community Hearts because the article you reviewed was published.</strong></p>" + 
							"<p>Title: " + title + "</p>" + 
							"<br>" + 
							"<button type='button' class='swal-button swal-button--confirm' id='downloadButton'>Download Certificate</button>",
						imageUrl: imageUrl,
						imageWidth: 400,
						imageHeight: 200,
						imageAlt: "Reviewer Certificate",
						showCancelButton: false,
						showCloseButton: true,
						showConfirmButton: false,
						didOpen: function () {
							document.getElementById('downloadButton').addEventListener('click', handleDownload);

							reviewerCert.style.display = 'none';
							authorCert.style.display = 'none';
						}
					});
				});
            } else if (actionEngage === 'Published an Article') {
				const certContainer = document.getElementById('certPublishedHead');

				html2canvas(certContainer).then(canvas => {
					const imageUrl = canvas.toDataURL();

					function handleDownloadPublished() {
                        var link = document.createElement('a');
                        link.download = 'Published-Certificate.png';
                        link.href = imageUrl;
                        link.click();
                    }

					Swal.fire({
						html: "<p><strong>You've earned 3 Community Hearts because you've successfully published an article</strong></p>" + 
							"<p>Title: " +  title + "</p>" + 
							"<br>" + 
							"<button type='button' class='swal-button swal-button--confirm' id='downloadButtonPublished'>Download Certificate</button>",
						imageUrl: imageUrl,
						imageWidth: 400,
						imageHeight: 200,
						imageAlt: "Published Article Certificate",
						showCancelButton: false,
						showCloseButton: true,
						showConfirmButton: false,
						didOpen: function () {
							document.getElementById('downloadButtonPublished').addEventListener('click', handleDownloadPublished);

							reviewerCert.style.display = 'none';
							authorCert.style.display = 'none';
						}
					});
				});
            } else if (actionEngage === 'Reviewed an Article') {
                Swal.fire({
					title: "<span class='heart-icon'>&#x2764;</span>",
                    html: "<p><strong>You've earned 1 Community Heart for helping us publish an article</strong></p>" + 
						"<p>Title: " + title + "</p>",
                    // imageUrl: "../images/qcu-bg.jpg",
                    // imageWidth: 400,
                    // imageHeight: 200,
                    // imageAlt: "Custom image",
                    didOpen: function () {
                        reviewerCert.style.display = 'none';
						authorCert.style.display = 'none';
                    }
                });
            }else if (actionEngage === 'Co-Author') {
                Swal.fire({
					title: "<span class='heart-icon'>&#x2764;</span>",
                    html: "<p><strong>You've earned 1 Community Heart for contributing to the article</strong></p>" + 
						"<p>Title: " + title + "</p>",
                    // imageUrl: "../images/qcu-bg.jpg",
                    // imageWidth: 400,
                    // imageHeight: 200,
                    // imageAlt: "Custom image",
                    didOpen: function () {
                        reviewerCert.style.display = 'none';
						authorCert.style.display = 'none';
                    }
                });
            }else if (actionEngage === 'Primary Contact') {
                Swal.fire({
					title: "<span class='heart-icon'>&#x2764;</span>",
                    html: "<p><strong>You've earned 1 Community Heart for being the primary contact to the article</strong></p>" + 
						"<p>Title: " + title + "</p>",
                    // imageUrl: "../images/qcu-bg.jpg",
                    // imageWidth: 400,
                    // imageHeight: 200,
                    // imageAlt: "Custom image",
                    didOpen: function () {
                        reviewerCert.style.display = 'none';
						authorCert.style.display = 'none';
                    }
                });
            }
        });
    });
});

function updateEngagementTitle(title, journalId, formattedDateTime) {
	const iss1 = document.getElementById('iss1');
	const iss2 = document.getElementById('iss2');
    document.getElementById("engagementTitle").innerHTML = title;
    document.getElementById("category").innerHTML = 'Journal of ' + journalId;
	document.getElementById("publishdate").innerHTML = formattedDateTime;
	document.getElementById("revDate").innerHTML = formattedDateTime;

	document.getElementById("engagementTitlePublished").innerHTML = title;
    document.getElementById("categoryPublished").innerHTML = 'Journal of ' + journalId;
	
	if (document.getElementById("category").innerHTML === 'Journal of The Gavel') {
		iss1.innerHTML = 'ISSN 3027-9895';
	} else if (document.getElementById("category").innerHTML === 'Journal of The Lamp') {
		iss1.innerHTML = 'ISSN 2984-8369';
	} else if (document.getElementById("category").innerHTML === 'Journal of The Star') {
		iss1.innerHTML = 'ISSN 3027-9895';
	}

	if (document.getElementById("categoryPublished").innerHTML === 'Journal of The Gavel') {
		iss2.innerHTML = 'ISSN 3027-9895';
	} else if (document.getElementById("categoryPublished").innerHTML === 'Journal of The Lamp') {
		iss2.innerHTML = 'ISSN 2984-8369';
	} else if (document.getElementById("categoryPublished").innerHTML === 'Journal of The Star') {
		iss2.innerHTML = 'ISSN 3027-9895';
	}	
}




function downloadCertificate() {
    // Get the certificate container by ID
    var certificateContainer = document.getElementById('certContainerHead');

    // Use html2canvas to capture the content as an image
    html2canvas(certificateContainer).then(function(canvas) {
        // Convert the canvas to a data URL
        var dataUrl = canvas.toDataURL('image/png');

        // Create a link element
        var link = document.createElement('a');

        // Set the download attribute and the href with the data URL
        link.download = 'Reviewer-Certificate.png';
        link.href = dataUrl;

        // Trigger a click on the link to start the download
        link.click();
    });

	
}

function downloadCertificatePublished() {
    // Get the certificate container by ID
    var certificateContainerPublished = document.getElementById('certPublishedHead');

    // Use html2canvas to capture the content as an image
    html2canvas(certificateContainerPublished).then(function(canvas) {
        // Convert the canvas to a data URL
        var dataUrl = canvas.toDataURL('image/png');

        // Create a link element
        var link = document.createElement('a');

        // Set the download attribute and the href with the data URL
        link.download = 'Published-Certificate.png';
        link.href = dataUrl;

        // Trigger a click on the link to start the download
        link.click();
    });

	
}


$(document).ready(function () {
        $('#searchUser').on('input', function () {
            var searchData = $(this).val();
            if (searchData.length > 0) {
                $.ajax({
                    type: 'POST',
                    url: '../PHP/search.php',
                    data: {searchData: searchData},
                    success: function (response) {
                        $('#userList').html(response);
                    }
                });
            } else {
                $('#userList').empty();
            }
        });
    });

	document.getElementById('changeModeBtn1').addEventListener('click', function (event) {
    Swal.fire({
        icon: 'question',
        text: 'Switch to private profile?',
        showCancelButton: true,
        showConfirmButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            var xhr = new XMLHttpRequest();

            xhr.open('POST', '../PHP/private_account.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('You are now in private profile');
                    window.location.href='../PHP/user-dashboard.php';
                }
            };

            xhr.send();
        }
    });
});		




</script>

<div class="footer" id="footer">
</div>
<script src="../JS/user-dashboard-universities.js"></script>  
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

<script>
    var originalFirstNameValue = "<?php echo $firstName; ?>";
    var originalLastNameValue = "<?php echo $last_name; ?>";

    function checkCredentialsagain() {
    if ($('#orcid').val().trim() === '') {
        return;
    }
    
    if ($('#editForm').is(':visible')) {
        $('#loadingOverlay').show();
        const xorc_id = document.getElementById('orcid').value;
        const xauthor_id = <?php echo $id; ?>;
        const firstNameInput = document.getElementById('firstName');
        const lastNameInput = document.getElementById('lastName');
        const firstName = firstNameInput.value;
        const lastName = lastNameInput.value;
        console.log(xauthor_id);

        var xhr = new XMLHttpRequest();

        xhr.open('POST', 'check_orcid.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            $('#loadingOverlay').hide();

            if (xhr.status >= 200 && xhr.status < 400) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true
                    });
                    document.getElementById("orcid_check_status").value = "true";
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true
                    });
                    document.getElementById("orcid_check_status").value = "false";
                    firstNameInput.value = originalFirstNameValue;
                    lastNameInput.value = originalLastNameValue;
                }
            } else {
                console.error("Error fetching data: " + xhr.status);
            }
        };

        xhr.send('orcid=' + xorc_id + '&author_id=' + xauthor_id + '&firstName=' + firstName + '&lastName=' + lastName);
    }
}
</script>

<script>
	function checkCredentials(author_id, orc_id) {
		
		if ($('#orcid').val().trim() === '') {
			return;
		}
		if ($('#editForm').is(':visible')) {
			$('#loadingOverlay').show();

			const firstName = document.getElementById('firstName').value;
			const lastName = document.getElementById('lastName').value;

			var xhr = new XMLHttpRequest();

			xhr.open('POST', 'check_orcid.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xhr.onload = function () {
				$('#loadingOverlay').hide();

				if (xhr.status >= 200 && xhr.status < 400) {
					var response = JSON.parse(xhr.responseText);
					if (response.success) {
						Swal.fire({
							icon: 'success',
							text: response.message,
							showConfirmButton: true
						});
						document.getElementById("orcid_check_status").value = "true";
					} else {
						Swal.fire({
							icon: 'error',
							text: response.message,
							showConfirmButton: true
						});
						document.getElementById("orcid").value = "";
						document.getElementById("orcid_check_status").value = "false";
					}
				} else {
					console.error("Error fetching data: " + xhr.status);
				}
			};

			xhr.send('orcid=' + orc_id + '&author_id=' + author_id + '&firstName=' + firstName + '&lastName=' + lastName);
		}
	}
</script>

</body>
</html>
