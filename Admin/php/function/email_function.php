<?php
require_once 'dbcon.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Check if the function is not already defined
if (!function_exists('get_email_content')) {
    function get_email_content($emc)
    {
        $pdo = connect_to_database();

        if ($pdo) {
            try {
                $query = "SELECT * FROM email_content WHERE id = :emc";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':emc', $emc, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                return $result;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }

        return false;
    }
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'email':
        sendEmail();
        break;
    case 'assign_reviewer':
        sendEmailAssignReviewer();
        break;
    case 'updateaccessible':
        acceptReviewerAnswer();
        break;
}

function sendEmail()
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcujournal@gmail.com';
        $mail->Password = 'txtprxrytyqmloth';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $recipients = !empty($_POST['hiddenEmail']) ? explode(', ', $_POST['hiddenEmail']) : ['qcujournal@gmail.com'];

        $mail->setFrom('qcujournal@gmail.com', 'QCU Journal');

        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient);
        }

        $mail->Subject = $_POST['subject'] . ", " . $_POST['title'];

        $mail->isHTML(true);

        $body = '';

        $quillContent = json_decode($_POST['quillContentOne'])->ops;

        foreach ($quillContent as $content) {
            $body .= nl2br($content->insert);
        }

        $mail->Body = $body;

        $id = $_POST['id'];
        $round = $_POST['round'];
        $fromuser = $_POST['fromuser'];
        $article_id = $_POST['article_id'];
        $title = $_POST['title'];
        $author_id = $_POST['author_id'];
        $author_email = $_POST['author_email'];
        $issues_id = $_POST['issues_id'];
        $articleFilesId = $_POST['checkedData'];
        // $articleFilesId1 = $_POST['checkedData1'];
        $revisionFilesId = $_POST['checkedData2'];
        $copyeditedFilesIds = $_POST['checkedData5'];
        if ($mail->send()) {
            echo "Email sent successfully.";
            if ($id == 1) {
                updateReviewFiles(1, $articleFilesId);
                updateArticleStatus($article_id, 4, '#navs-top-review');
                addLogs($article_id, $fromuser, 'Send to Review');
                addNotification($article_id, $author_id, $title, 'Send to Review');
                echo "<script>alert('Send to review successfully.');</script>";
            } elseif ($id == 2) {
                updateArticleStatus($article_id, 6, '#navs-top-submission');
                addLogs($article_id, $fromuser, 'Decline for Submission');
                addNotification($article_id, $author_id, $title, 'Decline for Submission');
                echo "<script>alert('Decline submission successfully.');</script>";
            } elseif ($id == 3) {
                updateCopyeditingRevisionFiles(1, $revisionFilesId);
                updateArticleStatus($article_id, 3, '#navs-top-copyediting');
                addLogs($article_id, $fromuser, 'Send to Copyediting');
                addNotification($article_id, $author_id, $title, 'Send to Copyediting');
                echo "<script>alert('Send to copyediting successfully.');</script>";
            } elseif ($id == 4) {
                if ($round == 'Round 2') {
                    updateRound($article_id, 'Round 3');
                    addLogs($article_id, $fromuser, 'Send for Revision Round 3');
                    addNotification($article_id, $author_id, $title, 'Send for Revision Round 3');
                } else {
                    updateRound($article_id, 'Round 2');
                    addLogs($article_id, $fromuser, 'Send for Revision Round 2');
                    addNotification($article_id, $author_id, $title, 'Send for Revision Round 2');
                }
                echo "<script>alert('Request for revision successfully.');</script>";
            } elseif ($id == 5) {
                updateCopyeditedFiles(1, $copyeditedFilesIds);
                updateArticleStatus($article_id, 2, '#navs-top-production');
                addLogs($article_id, $fromuser, 'Send to Production');
                addNotification($article_id, $author_id, $title, 'Send to Production');
                echo "<script>alert('Send to production successfully.');</script>";
            } elseif ($id == 6) {
                updateIssues($article_id, $issues_id);
                updateArticleStatus($article_id, 11, '#navs-top-production');
                addLogs($article_id, $fromuser, 'Article send for Scheduled');
                addNotification($article_id, $author_id, $title, 'Article send for Scheduled');
                echo "<script>alert('Send to Scheduled successfully.');</script>";
            } elseif ($id == 7) {
                updateArticleStatusArchive($article_id, 0, '#navs-top-production');
                addLogs($article_id, $fromuser, 'Article move to Archive');
                addNotification($article_id, $author_id, $title, 'Article move to Archive');
                echo "<script>alert('Article move to archive successfully.');</script>";
            } elseif ($id == 8) {
                addUserPoints($article_id, $author_id, $author_email);
                addUserPointsReviewer($article_id);
                updateArticleStatusPublished($article_id, 1, '#navs-top-production');
                addLogs($article_id, $fromuser, 'Article Published');
                addNotification($article_id, $author_id, $title, 'Article Published');
                echo "<script>alert('Article published successfully.');</script>";
            } else {
                echo 'Error sending email: ' . $mail->ErrorInfo;
            }
        }
    } catch (Exception $e) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

function addNotification($article_id, $author_id, $title, $message)
{
    $options = array(
        'cluster' => 'ap1',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        'cabcad916f55a998eaf5',
        '0aef8b4d2da6760f5726',
        '1764683',
        $options
    );

    date_default_timezone_set('Asia/Manila');
    $created = date('Y-m-d H:i:s');
    $admin = 0;
    $data['message'] = 'hello world';
    $pusher->trigger('my-channel', 'my-event', $data);

    $description = $article_id . ' - ' . $title . ', ' . $message;

    $query = "INSERT INTO notification (article_id, author_id, title, description, admin, created) VALUES (?, ?, ?, ?, ?, ?)";

    $result = execute_query($query, [$article_id, $author_id, $message, $description, $admin, $created], true);

    if ($result !== false) {
        echo json_encode(['status' => true, 'message' => 'Record added successfully']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to add record']);
    }
}

function addUserPointsReviewer($article_id)
{

    $query = "SELECT ra.article_id, a.author_id, a.email
                  FROM reviewer_assigned ra
                  JOIN author a ON ra.author_id = a.author_id
                  WHERE ra.article_id = $article_id AND ra.accept = 1 AND ra.answer = 1";

    $result = execute_query($query);

    // Check if $result is an array
    if (is_array($result)) {
        // Process the array directly
        foreach ($result as $row) {
            $article_id = $row->article_id;
            $author_id = $row->author_id;
            $author_email = $row->email;

            $action_engage = "Reviewed Article Published";
            $points = 3;

            $query = "INSERT INTO user_points (user_id, email, action_engage, article_id, point_earned) VALUES (?, ?, ?, ?, ?)";

            $result_insert = execute_query($query, [$author_id, $author_email, $action_engage, $article_id, $points], true);

            if ($result_insert !== false) {
                echo json_encode(['status' => true, 'message' => 'Points added successfully']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Failed to add points record', 'error']);
            }
        }
    } elseif ($result !== false && mysqli_num_rows($result) > 0) {
        // Process the result set as before
        while ($row = mysqli_fetch_assoc($result)) {
            // Your existing code here
        }
    } else {
        echo json_encode(['status' => true, 'message' => 'No data found']);
    }
}

function addUserPoints($article_id, $author_id, $author_email)
{
    $action_engage = "Published an Article";
    $points = 3;

    $query = "INSERT INTO user_points (user_id, email, action_engage, article_id, point_earned) VALUES (?, ?, ?, ?, ?)";

    $result = execute_query($query, [$author_id, $author_email, $action_engage, $article_id, $points], true);

    if ($result !== false) {
        echo json_encode(['status' => true, 'message' => 'Points added successfully']);
    } else {

        echo json_encode(['status' => false, 'message' => 'Failed to add points record', 'error']);
    }
}

function updateArticleStatusArchive($article_id, $status, $workflow)
{
    $query = "UPDATE article 
                  SET status = ?, workflow = ?, archive_date = NOW()
                  WHERE article_id = ?";

    $pdo = connect_to_database();

    $stm = $pdo->prepare($query);
    $check = $stm->execute([$status, $workflow, $article_id]);

    if ($check !== false) {
        echo "<script>alert('Article Status updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update status data');</script>";
    }
}

function updateArticleStatusPublished($article_id, $status, $workflow)
{
    $query = "UPDATE article 
                  SET status = ?, workflow = ?, publication_date = NOW()
                  WHERE article_id = ?";

    $pdo = connect_to_database();

    $stm = $pdo->prepare($query);
    $check = $stm->execute([$status, $workflow, $article_id]);

    if ($check !== false) {
        echo "<script>alert('Article Status updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update status data');</script>";
    }
}

function updateArticleStatus($article_id, $status, $workflow)
{

    $query = "UPDATE article 
                SET status = ?, workflow = ?
                WHERE article_id = ?";

    $pdo = connect_to_database();

    $stm = $pdo->prepare($query);
    $check = $stm->execute([$status, $workflow, $article_id]);

    if ($check !== false) {
        echo "<script>alert('Article Status updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update status data');</script>";
    }
}

function updateRound($article_id, $round)
{

    $query = "UPDATE article 
                SET round = ?
                WHERE article_id = ?";

    $pdo = connect_to_database();

    $stm = $pdo->prepare($query);
    $check = $stm->execute([$round, $article_id]);

    if ($check !== false) {
        echo "<script>alert('Article round updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update round data');</script>";
    }
}

function updateIssues($article_id, $issues_id)
{

    $query = "UPDATE article 
                SET issues_id = ?
                WHERE article_id = ?";

    $pdo = connect_to_database();

    $stm = $pdo->prepare($query);
    $check = $stm->execute([$issues_id, $article_id]);

    if ($check !== false) {
        echo "<script>alert('Article issues updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update issues data');</script>";
    }
}

function updateReviewFiles($status, $articleFilesIds)
{

    if (!is_array($articleFilesIds)) {
        $articleFilesIds = array($articleFilesIds);
    }

    $decodedIds = json_decode($articleFilesIds[0], true);
    $articleFilesIds = array_column($decodedIds, 'articleFilesId');

    // Create an array of named parameters for binding
    $params = array(':status' => $status);
    foreach ($articleFilesIds as $key => $articleFilesId) {
        $paramName = ":id$key";
        $params[$paramName] = $articleFilesId;
        $placeholders[] = $paramName;
    }

    if (empty($placeholders)) {
        $placeholders = 0;
    } else {
        $placeholders = implode(',', $placeholders);
    }

    $query = "UPDATE article_files
                  SET review = :status
                  WHERE article_files_id IN ($placeholders)";

    $pdo = connect_to_database();

    $pdo->beginTransaction();

    $stm = $pdo->prepare($query);

    // Bind the parameters
    foreach ($params as $paramName => &$paramValue) {
        $stm->bindParam($paramName, $paramValue, PDO::PARAM_INT);
    }

    $check = $stm->execute();

    if ($check !== false) {
        echo "Review Files updated successfully";
        $pdo->commit();
    } else {
        echo "Failed to update file review data";
        print_r($stm->errorInfo());
        $pdo->rollBack();
    }
}

function updateCopyeditingFiles($status, $articleFilesIds)
{

    if (!is_array($articleFilesIds)) {
        $articleFilesIds = array($articleFilesIds);
    }

    $decodedIds = json_decode($articleFilesIds[0], true);
    $articleFilesIds = array_column($decodedIds, 'articleFilesId');

    // Create an array of named parameters for binding
    $params = array(':status' => $status);
    foreach ($articleFilesIds as $key => $articleFilesId) {
        $paramName = ":id$key";
        $params[$paramName] = $articleFilesId;
        $placeholders[] = $paramName;
    }

    if (empty($placeholders)) {
        $placeholders = 0;
    } else {
        $placeholders = implode(',', $placeholders);
    }

    $query = "UPDATE article_files
                  SET copyediting = :status
                  WHERE article_files_id IN ($placeholders)";

    $pdo = connect_to_database();

    $pdo->beginTransaction();

    $stm = $pdo->prepare($query);

    // Bind the parameters
    foreach ($params as $paramName => &$paramValue) {
        $stm->bindParam($paramName, $paramValue, PDO::PARAM_INT);
    }

    $check = $stm->execute();

    if ($check !== false) {
        echo "Review Files updated successfully";
        $pdo->commit();
    } else {
        echo "Failed to update file review data";
        print_r($stm->errorInfo());
        $pdo->rollBack();
    }
}

function updateCopyeditingRevisionFiles($status, $revisionFilesIds)
{

    if (!is_array($revisionFilesIds)) {
        $revisionFilesIds = array($revisionFilesIds);
    }

    $decodedIds = json_decode($revisionFilesIds[0], true);
    $revisionFilesIds = array_column($decodedIds, 'revisionFilesId');

    // Create an array of named parameters for binding
    $params = array(':status' => $status);
    foreach ($revisionFilesIds as $key => $revisionFilesId) {
        $paramName = ":id$key";
        $params[$paramName] = $revisionFilesId;
        $placeholders[] = $paramName;
    }

    if (empty($placeholders)) {
        $placeholders = 0;
    } else {
        $placeholders = implode(',', $placeholders);
    }

    $query = "UPDATE article_revision_files
                  SET copyediting = :status
                  WHERE revision_files_id IN ($placeholders)";

    $pdo = connect_to_database();

    $pdo->beginTransaction();

    $stm = $pdo->prepare($query);

    // Bind the parameters
    foreach ($params as $paramName => &$paramValue) {
        $stm->bindParam($paramName, $paramValue, PDO::PARAM_INT);
    }

    $check = $stm->execute();

    if ($check !== false) {
        echo "Revision Files updated successfully";
        $pdo->commit();
    } else {
        echo "Failed to update file revision data";
        print_r($stm->errorInfo());
        $pdo->rollBack();
    }
}

function updateCopyeditedFiles($status, $copyeditedFilesIds)
{

    if (!is_array($copyeditedFilesIds)) {
        $copyeditedFilesIds = array($copyeditedFilesIds);
    }

    $decodedIds = json_decode($copyeditedFilesIds[0], true);
    $copyeditedFilesIds = array_column($decodedIds, 'copyeditedFilesId');

    // Create an array of named parameters for binding
    $params = array(':status' => $status);
    foreach ($copyeditedFilesIds as $key => $copyeditedFilesId) {
        $paramName = ":id$key";
        $params[$paramName] = $copyeditedFilesId;
        $placeholders[] = $paramName;
    }

    if (empty($placeholders)) {
        $placeholders = 0;
    } else {
        $placeholders = implode(',', $placeholders);
    }

    $query = "UPDATE article_final_files
                  SET production = :status
                  WHERE final_files_id IN ($placeholders)";

    $pdo = connect_to_database();

    $pdo->beginTransaction();

    $stm = $pdo->prepare($query);

    // Bind the parameters
    foreach ($params as $paramName => &$paramValue) {
        $stm->bindParam($paramName, $paramValue, PDO::PARAM_INT);
    }

    $check = $stm->execute();

    if ($check !== false) {
        echo "Review production Files updated successfully";
        $pdo->commit();
    } else {
        echo "Failed to update file review production data";
        print_r($stm->errorInfo());
        $pdo->rollBack();
    }
}

if (!function_exists('get_reviewer_content')) {
    function get_reviewer_content($emc)
    {
        $pdo = connect_to_database();

        if ($pdo) {
            try {
                $query = "SELECT * FROM email_content WHERE id = :emc";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':emc', $emc, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                return $result;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                return false;
            }
        }
        return false;
    }
}

function sendEmailAssignReviewer()
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcujournal@gmail.com';
        $mail->Password = 'txtprxrytyqmloth';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->addAddress($_POST['revieweremail']);
        $mail->setFrom('qcujournal@gmail.com', 'QCU Journal');
        $mail->Subject = $_POST['subject'];
        $mail->isHTML(true);

        $quillContent = json_decode($_POST['quillContentOne'])->ops;

        $body = '';

        foreach ($quillContent as $content) {
            if (isset($content->insert)) {
                $body .= nl2br($content->insert);
            }
        }

        $mail->Body = $body;

        $title = $_POST['title'];
        $reviewerid = $_POST['reviewerid'];
        $articleid = $_POST['articleid'];
        $round = $_POST['round'];

        if ($mail->send()) {
            echo "Email sent to reviewer successfully.";
            assignReviewer($articleid, $reviewerid, $round);
            addNotification($articleid, $reviewerid, $title, 'Assign for review');
        } else {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}

function assignReviewer($articleid, $reviewerid, $round)
{
    $date_issued = date('Y-m-d H:i:s', strtotime('+1 week'));

    $query = "INSERT INTO reviewer_assigned (article_id, author_id, round, date_issued) VALUES (?, ?, ?, ?)";

    $result = execute_query($query, [$articleid, $reviewerid, $round, $date_issued], true);

    if ($result !== false) {
        echo json_encode(['status' => true, 'message' => 'Record added successfully']);
    } else {

        echo json_encode(['status' => false, 'message' => 'Failed to add record', 'error']);
    }
}

function addLogs($articleid, $fromuser, $message)
{

    $query = "INSERT INTO logs_article (article_id, fromuser, type) VALUES (?, ?, ?)";

    $result = execute_query($query, [$articleid, $fromuser, $message], true);

    if ($result !== false) {
        echo json_encode(['status' => true, 'message' => 'Record added successfully']);
    } else {
        // $errorInfo = get_db_error_info();
        // echo json_encode(['status' => false, 'message' => 'Failed to add record', 'error' => $errorInfo]);
        echo json_encode(['status' => false, 'message' => 'Failed to add record', 'error']);
    }
}

function acceptReviewerAnswer() { 
    $reviewer_assigned_id = $_POST['reviewer_assigned_id'];
    $author_id = $_POST['author_id'];
    $article_id = $_POST['article_id'];
    $comment_accessible = 1;

    $pdo = connect_to_database();

    if ($pdo) {
        try {
            $update_query = "UPDATE reviewer_assigned 
                            SET comment_accessible = :comment_accessible
                            WHERE reviewer_assigned_id = :reviewer_assigned_id";

            $update_stmt = $pdo->prepare($update_query);
            $update_stmt->bindParam(':reviewer_assigned_id', $reviewer_assigned_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':comment_accessible', $comment_accessible, PDO::PARAM_INT);
            $update_check = $update_stmt->execute();

            if ($update_check !== false) {
                $author_query = "SELECT email FROM author WHERE author_id = :author_id";
                $author_stmt = $pdo->prepare($author_query);
                $author_stmt->bindParam(':author_id', $author_id, PDO::PARAM_INT);
                $author_stmt->execute();
                $author_result = $author_stmt->fetch(PDO::FETCH_ASSOC);

                $article_query = "SELECT title FROM article WHERE article_id = :article_id";
                $article_stmt = $pdo->prepare($article_query);
                $article_stmt->bindParam(':article_id', $article_id, PDO::PARAM_INT);
                $article_stmt->execute();
                $article_result = $article_stmt->fetch(PDO::FETCH_ASSOC);

                if ($author_result && $article_result) {
                    $mail = new PHPMailer(true);

                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'qcujournal@gmail.com';
                    $mail->Password = 'txtprxrytyqmloth';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;


                    $mail->setFrom('qcujournal@gmail.com', 'QCU Journal');
                    $mail->addAddress($author_result['email']); 
                    $mail->Subject = 'Your article review is accepted';
                    $mail->Body = 'Your article titled "'.$article_result['title'].'" has received a review and the answer is accepted. We appreciate your contribution to the quality of work that we publish.';
                    $mail->isHTML(true); 

                    $mail->send();

                    echo json_encode(['status' => true, 'message' => 'Reviewer answer accepted successfully! Email sent to the author.']);
                } else {
                    echo json_encode(['status' => false, 'message' => 'Author email or article title not found.']);
                }
            } else {
                echo json_encode(['status' => false, 'message' => 'Failed to accept review']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => 'Email error: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Database connection error']);
    }
    exit;
}
