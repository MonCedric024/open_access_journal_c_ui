<?php
require_once 'dbcon.php';
session_start();

if (!isset($_SESSION['LOGGED_IN']) || $_SESSION['LOGGED_IN'] !== true) {
    header('Location: ./login.php');
    exit();
}

$userId = $_SESSION['id'];
$articleId = isset($_GET['id']) ? $_GET['id'] : null;

date_default_timezone_set('Asia/Manila');

$sqlCheckArticle = "SELECT * FROM reviewer_assigned JOIN article ON reviewer_assigned.article_id = article.article_id WHERE reviewer_assigned.author_id = :author_id AND reviewer_assigned.article_id = :article_id";

$params = array('author_id' => $userId, 'article_id' => $articleId);

$result = database_run($sqlCheckArticle, $params);

if ($result !== false) {
    
    foreach($result as $row){
        $created = date('Y-m-d', strtotime($row->date_issued));
        $deadline = $row->deadline;
        $answered = $row->answer;
        $currentDate = date('Y-m-d'); // Ensure currentDate only contains date portion
    
        if ($currentDate > $created && $answered == '1') {
            echo "<script>alert('Overdue Invitation or You have already responded to this article');</script>";
            echo "<script>window.location.href = './index.php';</script>";
        } elseif ($currentDate > $created && $deadline !== null && $currentDate > $deadline) {
            echo "<script>alert('Overdue Invitation');</script>";
            echo "<script>window.location.href = './index.php';</script>";
        } elseif ($answered == '1') {
            echo "<script>alert('You have already responded to this article');</script>";
            echo "<script>window.location.href = './index.php';</script>";
        }
    }
    
   
} else {
    echo "You are uninvited to this article";
}


 
?>



<!DOCTYPE html>
<html lang="en">
<head>
<?php include('./meta.php'); ?>
<title>QCUJ | Review</title>
<link rel="stylesheet" href="../CSS/review-process.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>



<div class="header-container" id="header-container">
</div>

<nav class="navigation-menus-container"  id="navigation-menus-container">
</nav>


<form id="form" method="post" action="reviewer_answer.php">
    <!-- Step 1 -->
    <div class="step active" id="step1">
        <div class="main-container">
            <div class="content-over">
                <div class="cover-content" style=" position: relative; width: 100%; background-size: cover; background-repeat: no-repeat;background-image: url('../images/review-cover.png'); padding: 2em 8%;">
                   
                    <p> Dashboard / Reviewer / Submitted Articles </p>
                        <h3> 
                            <?php 
                            $sqlReviewraticle = "SELECT article.title 
                                                FROM article 
                                                JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id 
                                                WHERE article.status = 4
                                                AND reviewer_assigned.author_id = :author_id AND article.article_id = :article_id ORDER BY reviewer_assigned.date_issued DESC
                                                LIMIT 1";

                            $result = database_run($sqlReviewraticle, array('author_id' => $userId,
                                'article_id' => $articleId));

                            if ($result !== false) {
                                foreach ($result as $row) {
                                    echo $row->title;
                                }
                            } else {
                                echo "No title for this article."; 
                            }
                            ?>
                        </h3>


                </div>
            </div>
        </div>


        <div class="container-fluid">
        <div class="row justify-content-between" style="padding: 2em 8%;" >
            <div class="column" id="step1Content">
                <div class="col-md-12 abstract" style="height: auto"> 
               

                    <?php
                    echo '<input id="getId" name="getId" type="text" value="' . $articleId . '" placeholder="' . $articleId . '" style="display: none">';

                    $sqlRound = "SELECT round FROM reviewer_assigned WHERE article_id = $articleId AND author_id = $userId ORDER BY round DESC LIMIT 1";
                    $sqlRunRound = database_run($sqlRound);
                    
                  
                    if ($sqlRunRound !== false && is_array($sqlRunRound) && count($sqlRunRound) > 0) {
                       
                        if (is_object($sqlRunRound[0]) && property_exists($sqlRunRound[0], 'round')) {
                           
                            $roundValue = $sqlRunRound[0]->round;
                    
                            echo '<input style="display: none" id="getRound" name="getRound" type="text" value="' . $roundValue . '" placeholder="' . $roundValue . '">';
                        } else {
                        
                            echo 'Debugging info: ' . print_r($sqlRunRound, true);
                            // Handle the case where the 'round' property is not present in the result array
                            echo 'Error: "round" property not found in result array';
                        }
                    } else {
                        // Output debugging information
                        echo 'Debugging info: ' . print_r($sqlRunRound, true);
                        // Handle the case where the query failed or the result is not as expected
                        echo 'Error fetching round value';
                    }

                    ?>

                    <h4>Abstract</h4>
                    <p id="dAbstract">
                    <?php 
                    $sqlReviewraticle = "SELECT article.abstract 
                                        FROM article 
                                        JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id 
                                        WHERE article.status = 4
                                        AND reviewer_assigned.author_id = :author_id AND article.article_id = :article_id ORDER BY reviewer_assigned.date_issued DESC
                                        LIMIT 1";

                    $result = database_run($sqlReviewraticle, array('author_id' => $userId,
                                    'article_id' => $articleId));

                    if ($result !== false) {
                        foreach ($result as $row) {
                        
                            $firstLetter = substr($row->abstract, 0, 1);
                            // Get the rest of the abstract
                            $restOfAbstract = substr($row->abstract, 1);
                            
                            
                            echo '<span class="first-letter">' . $firstLetter . '</span>' . $restOfAbstract;
                        }
                    } else {
                        echo "No abstract for this article."; 
                    }
                    ?>
                    </p>
                        


                </div>

                <div class="col-md-12">
                <div class="keywords">
                    <h4 style="margin-bottom: 10px;">Keywords</h4>
                    <div class="keyword1">
                    <ul style="display: flex;padding-left:0;">
                        <?php
                        $sqlKeyword = "SELECT article.keyword FROM article JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id AND article.status = 4 WHERE reviewer_assigned.author_id = :author_id AND article.article_id = :article_id";
                        
                        $result = database_run($sqlKeyword, array('author_id' => $userId,
                        'article_id' => $articleId));

                        if ($result !== false) {
                            foreach ($result as $row) {
                                $keywords = explode(',', $row->keyword);
                                foreach ($keywords as $keyword) {
                                    echo '<li style="list-style-type: none; 
                                                    margin-right: 5px;
                                                    width: auto;
                                                    color: var(--main, #0858A4);
                                                    border: 1px solid var(--main, #0858A4);
                                                    border-radius: 10px;
                                                    background-color: white;
                                                    padding:3px;
                                                    font-size: 12px;">' . trim($keyword) . '</li>';
                                }
                                
                            }
                        } else {
                            echo "No keywords for this article";
                        }
                        ?>
                    </ul>

                    </div>
                </div>


                </div>
            </div>

                
            <div class="column" id="step1Content2">
                <div class="col-md-12">
                    <div>
                        <!-- <div class="btn" id="step1button">
                            <button type="button" class="btn tbn-primary btn-md nextBtn" id="acceptBtn"  onclick="nextStep()" >Accept</button>
                            <button type="button"  id="btnReject" class="btn tbn-primary btn-md" onclick="rejectInvitation('<?php echo $articleId; ?>')">Decline</button>
                        </div> -->
                            <h4 style="padding-top: 30px;" >Submitted in the 
                            <?php
                                $sqlJournal = "SELECT journal.journal, article.title FROM journal JOIN article ON journal.journal_id = article.journal_id JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id AND article.status = 4
                                AND reviewer_assigned.author_id = :author_id AND article.article_id = :article_id";

                                $result = database_run($sqlJournal, array('author_id' => $userId,
                                    'article_id' => $articleId));

                                if ($result !== false) {
                                foreach ($result as $row) {
                                echo $row->journal;
                                }
                                } else {
                                echo "No status for this article"; 
                                }
                            ?>
                            </h4>
                            <div class="status" >
                                <p>
                                <?php
                                $sqlStatus = "SELECT article_status.status, article.title 
                                            FROM article_status 
                                            JOIN article ON article_status.status_id = article.status 
                                            JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id AND article.status = 4
                                            WHERE reviewer_assigned.author_id = :author_id AND article.article_id = :article_id
                                            ORDER BY reviewer_assigned.date_issued DESC
                                            LIMIT 1";

                                $result = database_run($sqlStatus, array('author_id' => $userId, 'article_id' => $articleId));

                                if ($result !== false) {
                                    foreach ($result as $row) {
                                        echo $row->status;
                                    }
                                } else {
                                    echo "No status for this article";
                                }
                                ?>

                                    
                                </p>
                            </div>
                            <hr style="height: 1px; background-color: var(--main, #0858A4); width: 100%">
                            </div>
                <div class="row" id="allLogs" >
                    <div class="col-md-5 col-6 logs-date">
                        <p id="logsTitle" style="color: black; font-family: 'Judson', serif; font-weight: bold; font-style: normal;">Logs</p>
                    
                        <div class="log-entry mt-4" id="logEntries">
                            <?php
                                $sqlLogs = "SELECT logs_article.type FROM logs_article JOIN article ON logs_article.article_id = article.article_id WHERE logs_article.article_id = :article_id";

                                $sqlRunLogs = database_run($sqlLogs, array('article_id' => $articleId));

                                if ($sqlRunLogs !== false){
                                    $count = 0;
                                    foreach ($sqlRunLogs as $logsRow){
                                        if ($count < 5) {
                                            echo '<p class="logsArticle" style="display: block">' . $logsRow->type . '</p>';
                                        } else {
                                            echo '<p class="logsArticle" style="display: none">' . $logsRow->type . '</p>';
                                        }
                                        $count++;
                                    }
                                } else {
                                    echo 'no logs for this article';
                                }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6 col-6 date">
                        <p style="color: black; font-family: 'Judson', serif; font-weight: bold; font-style: normal;">Date</p>
                        <div class="log-date" id="logDates">
                            <?php
                                $sqlLogsDate = "SELECT logs_article.date FROM logs_article JOIN article ON logs_article.article_id = article.article_id WHERE logs_article.article_id = :article_id";

                                $sqlDateParams = database_run($sqlLogsDate, array('article_id' => $articleId));

                                if ($sqlDateParams !== false){
                                    $count = 0;
                                    foreach ($sqlDateParams as $logsDate){
                                        if ($count < 5) {
                                            echo '<p style="display: block;">' . $logsDate->date . '</p>';
                                        } else {
                                            echo '<p style="display: none">' . $logsDate->date . '</p>';
                                        }
                                        $count++;
                                    }
                                } else {
                                    echo 'no logs for this article';
                                }
                            ?>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 col-12 btn-group mt-4">
                    <button type="button" class="btn btn-outline-primary btn-sm"  onclick="viewAllLogs()" id="viewLogsBtn">View All Logs</button>
                    <button type="button" class="btn btn-outline-primary btn-sm"  onclick="hideLogs()" id="hideLogsBtn" style="display: none;">Hide Logs</button>
                </div>
                <div class="btn" id="step1button">
                <?php 
                            $sqlReviewraticle = "SELECT article.title, reviewer_assigned.accept
                            FROM article 
                            JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id 
                            WHERE article.status = 4
                            AND reviewer_assigned.author_id = :author_id 
                            AND article.article_id = :article_id 
                            ORDER BY reviewer_assigned.date_issued DESC
                            LIMIT 1";

                        $result = database_run($sqlReviewraticle, array(
                            'author_id' => $userId,
                            'article_id' => $articleId
                        ));

                        if ($result !== false) {
                            $accept = $result[0]->accept;
                            if ($accept == '0') {
                                echo '<button type="button" class="btn tbn-primary btn-md nextBtn" id="acceptBtn" onclick="nextStep()">Accept</button>';
                                echo '<button type="button" id="btnReject" class="btn tbn-primary btn-md" onclick="rejectInvitation(' . $articleId . ')">Decline</button>';
                            } elseif ($accept == '1') {
                                echo '<button type="button" class="btn tbn-primary btn-md nextBtn" id="reviewNow" onclick="nextStep()">Review</button>';
                            } else {
                                echo 'You have rejected the invitation for this article';
                            }
                        } else {
                            echo ""; // Not sure what you intend to echo here
                        }
                        
                    ?>

                        </div>
                </div>
                <div class="col-md-4" style="padding:0;">
                    <!-- This is a Blank space -->
                    
                </div>
            </div>
        </div>
            


            <div class="row">
                <div class="col-md-1">
                    <!-- This is a Blank space -->
                </div>



                <div class="col-md-4">
                    <!-- This is a Blank space -->
                    <div class="table-container">
                       
                  
           
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-1">
                    <!-- This is a Blank space -->
                </div>

                <div class="col-md-1"><!-- This is a Blank space --></div>

            </div>
        </div>
    </div>

        <!-- Step 2 -->
    <div class="step" id="step2">

        <div class="main-container">
            <div class="content-over">
                <div class="cover-content" style=" position: relative; width: 100%; background-size: cover; background-repeat: no-repeat;background-image: url('../images/background-spot.svg'); padding: 2em 8%;">
                    <p> Dashboard / Reviewer / Submitted Articles / Steps and Guideline</p>
                    <h3> Review Form Response </h3>
                </div>
            </div>
        </div>



        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <!-- This is a Blank space -->
                </div>

                <div class="col-md-8 rev-guide" style="padding-top:50px;">
                    <h4>Review Steps</h4>

                    <hr style="height: 1px; background-color: var(--main, #0858A4); width: 100%">

                    <ol>
                        <li> Consult Reviewer Guidelines below. </li>
                        <li> Click on file names to download and review (on screen or by printing) the files associated with this submission. Submission Manuscript 
                        <?php
                            $sqlFileName = "SELECT article_files.file_name, article.title 
                                            FROM article_files 
                                            JOIN article ON article_files.article_id = article.article_id 
                                            JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id 
                                            WHERE article_files.file_type = 'File with no author name' 
                                            AND article.status = 4 
                                            AND reviewer_assigned.author_id = :author_id 
                                            AND article.article_id = :article_id
                                            ORDER BY reviewer_assigned.date_issued DESC
                                            LIMIT 1";

                            $result = database_run($sqlFileName, array('author_id' => $userId, 'article_id' => $articleId));

                            if ($result !== false) {
                                foreach ($result as $row) {
                                    $fileName = $row->file_name;
                                    $filePath = '../Files/submitted-article/' . $fileName;
                            
                                    if (pathinfo($filePath, PATHINFO_EXTENSION) === 'docx') {
                                        $url = 'https://v2.convertapi.com/convert/docx/to/pdf?Secret=w96RLvfwIworUItk';
                                        $requestData = array(
                                            'Parameters' => array(
                                                array(
                                                    'Name' => 'File',
                                                    'FileValue' => array(
                                                        'Name' => $fileName,
                                                        'Data' => base64_encode(file_get_contents($filePath))
                                                    )
                                                ),
                                                array(
                                                    'Name' => 'StoreFile',
                                                    'Value' => true
                                                )
                                            )
                                        );
                            
                                        $ch = curl_init($url);
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_POST, true);
                                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
                                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                                        $result = curl_exec($ch);
                            
                                        if ($result !== false) {
                                            $responseData = json_decode($result, true);
                                            if (isset($responseData['Files'][0]['Url'])) {
                                                $pdfUrl = $responseData['Files'][0]['Url'];
                                                $pdfFileName = pathinfo($fileName, PATHINFO_FILENAME) . ".pdf";
                                                echo "<a href='$pdfUrl' download='$pdfFileName'>$pdfFileName</a><br>";
                                            } else {
                                                echo "Conversion failed for $fileName";
                                            }
                                        } else {
                                            echo "Failed to convert $fileName to PDF";
                                        }
                            
                                        curl_close($ch);
                                    }
                                }
                            } else {
                                echo "Can't find the file or it has been put in the archive";
                            }
                        ?>  
                        Supplementary File(s) None </li>
                        <li> Click on icon to fill in the review form. Review Form Comment </li>
                        <li> In addition, you can upload files for the editor and/or author to consult. Uploaded files None </li>
                        <li> Select a recommendation and submit the review to complete the process. You must enter a review or upload a file before selecting a recommendation.</li>
                    </ol>


                    <h4>Review Guidelines</h4>
                    <hr style="height: 1px; background-color: var(--main, #0858A4); width: 100%">
                    <div class="guidelines">
                        <p>
                        The International Journal of Learning, Teaching and Educational Research values the role of reviewers in the peer-review process that enables us to publish high-quality materials in a timely way.
                        </p>
                        <p>
                        Reviewers are expected to accept for review only articles in which they have sufficient expertise. Any conflict of interest must be reported to the Chief Editor.
                        </p>
                        <p>
                        Reviewers should submit their reviews using the online form provided on the portal. They are expected to provide a clear recommendation and justifications for their recommendation for either acceptance or rejection of an article.
                        </p>
                        <p>
                        Reviewers should appreciate that they are a privileged group of persons who are having first-hand access to unpublished work and they should therefore maintain the confidentiality of all such works to which they are given access.
                        </p>
                        <p>
                        Reviewers must analyze the methodology and results and discuss whether these could be repeated.
                        </p>
                        <p>
                        Reviewers must identify gaps that could or should be addressed in order to provide a better understanding of the results.
                        </p>
                        <p>
                        Reviewers should provide comments on how the article can be enhanced in terms of focus, style, and length.
                        </p>
                        <p>
                        Reviewers must check whether the references are relevant, recent, and in the proper format.
                        </p>
                        <p>
                        Reviewers must comment on the overall originality of the work and its contribution to the field.
                        </p>
                        <p>
                        Reviewers will be expected to re-review articles which are submitted again after substantial improvements.
                        </p>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="checkBox" name="checkBox" value="1" >
                        <label for="checkBox" style="color: var(--main, #0858A4); font-family: 'Raleway', sans-serif; font-size: 16px; " >I have read and will follow the steps and Guidelines of reviewing this assign Article.</label><br>
                    </div>

                    <div class="btn-action">
                        <button type="button" class="btn tbn-primary btn-md" id="reviewBtn" onclick="nextStep()" disabled>Review Form</button>
                    </div> 
                </div>

                <div class="col-md-2">
                    <!-- This is a Blank space -->
                </div>
            </div>
        </div>  
    </div>

        <!-- Step 3 -->
    <div class="step" id="step3">


        <div class="main-container">
            <div class="content-over">
                <div class="cover-content" style=" position: relative; width: 100%; background-size: cover; background-repeat: no-repeat;background-image: url('../images/background-spot.svg'); padding: 2em 8%;">
                    <p> Dashboard / Reviewer / Submitted Articles / Steps and Guideline / Review Form</p>
                    <h3> Review Form Response </h3>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row" style="background:var(--gray);">
                <div class="col-md-2">
                    <!-- This is a Blank space -->
                </div>
                <div class="col-md-8 col-12" style="padding-top:50px;">
                    <!-- <h5 style="background-color:var(--main, #0858A4); color: white; padding:10px;" >Research Article Review Form</h5> -->
                    <div class="contents rounded p-5" style="background-color:white;">
                        <div class="row">
                            <div class="firstContent">
                                <div class="col-md-12">
                                <h4 class="reviewFormTitle" id="reviewFormTitle">Research Review Form</h4>
                                <p id="title2">
                                    <?php 
                                        $sqlReviewraticle = "SELECT article.title 
                                                            FROM article 
                                                            JOIN reviewer_assigned ON article.article_id = reviewer_assigned.article_id 
                                                            WHERE article.status = 4
                                                            AND reviewer_assigned.author_id = :author_id AND article.article_id = :article_id ORDER BY reviewer_assigned.date_issued DESC
                                                            LIMIT 1";

                                        $result = database_run($sqlReviewraticle, array('author_id' => $userId,
                                            'article_id' => $articleId));

                                        if ($result !== false) {
                                            foreach ($result as $row) {
                                                echo $row->title;
                                            }
                                        } else {
                                            echo "No title for this article."; 
                                        }
                                    ?>
                                </p>
                                </div>

                                <hr style="height: 1px; background-color: #F5F5F9; width: 100%;">

                            <!-- Content for the left half of the screen -->
                                <!-- <h4 class="mt-4">Note: </h4> -->
                                

                                <!-- <h5>Paper Length:</h5> -->
                                <div class="questionsContainer mt-4">
                                    <div class="questionsAnswer">
                                    <?php
                                        $sqlQuestionnaire = "SELECT question, answer FROM reviewer_questionnaire";
                                        $result = database_run($sqlQuestionnaire);

                                        if ($result) {
                                            foreach ($result as $row) {
                                                $question = htmlspecialchars($row->question);
                                                echo '<li class="list-group-item mt-5" style="list-style: none; color: var(--main, #0858A4);  padding-bottom: 10px; font-size: 18px; font-family: \'Raleway\', sans-serif;">' . $question . '</li>';

                                                // Split the choices using commas
                                                $choices = explode(',', $row->answer);
                                                
                                                // Display each choice as a radio button
                                                echo '<div class="d-flex gap-4 choices">';
                                                foreach ($choices as $choice) {
                                                    $uniqueId = htmlspecialchars(trim($choice)) . '_' . uniqid(); // Create a unique ID for each radio button

                                                    echo '<label for="' . $uniqueId . '" style="font-size: small; color: gray; font-family: \'Raleway\', sans-serif; "> 
                                                    ' . htmlspecialchars(trim($choice)) . ' <input type="radio" name="answers[' . $question . ']" value="' . htmlspecialchars(trim($choice)) . '" id="' . $uniqueId . '" required class="custom-radio" style="margin-top: 10px; display: inline-block"></label><br>';
                                                }
                                                echo'</div>';

                                                echo '<div class="form-floating mt-2" style="padding:10px;" >';
                                                echo '<textarea class="form-control" name="comments[' . $question . ']" id="floatingTextarea" style="height: 120px; font-size: small; color: gray; width: 100%;"></textarea>';
                                                echo '<label for="floatingTextarea" style="margin:10px;">Additional comment</label>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo 'The questionnaire has not been updated yet';
                                        }
                                        ?>


                                    </div>
                                </div>



                                <!-- <div>
                                    <input type="radio" name="paperLength" value="quiteShort" id="quiteShort">
                                    <label for="quiteShort">Quite Short</label>
                                </div>

                                <div>
                                    <input type="radio" name="paperLength" value="ok" id="ok">
                                    <label for="ok">Ok</label>
                                </div>

                                <div>
                                    <input type="radio" name="paperLength" value="quiteLong" id="quiteLong">
                                    <label for="quiteLong">Quite Long</label>
                                </div>

                                <div style="margin-bottom: 10px;" >
                                    <input type="radio" name="paperLength" value="tooLong" id="tooLong">
                                    <label for="tooLong">Too Long</label>
                                </div>


                                <h5>Originality:</h5>

                                <div>
                                    <input type="radio" name="originality" value="Nil" id="Nil">
                                    <label for="Nil">Nil</label>
                                </div>

                                <div>
                                    <input type="radio" name="originality" value="acceptable" id="acceptable">
                                    <label for="acceptable">Acceptable</label>
                                </div>

                                <div>
                                    <input type="radio" name="originality" value="good" id="good">
                                    <label for="good">Good</label>
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <input type="radio" name="originality" value="veryInnovative" id="veryInnovative">
                                    <label for="veryInnovative">Very Innovative</label>
                                </div>

                                <h5>Paper Representation:</h5>

                                <div>
                                    <input type="checkbox" name="representation" value="improveSignificantly" id="improveSignificantly">
                                    <label for="improveSignificantly">Must Improve Significantly</label>
                                </div>

                                <div>
                                    <input type="checkbox" name="representation" value="improveSlightly" id="improveSlightly">
                                    <label for="improveSlightly">Must Improve Slightly</label>
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <input type="checkbox" name="representation" value="ok" id="ok">
                                    <label for="ok">Ok</label>
                                </div>

                                <h5>Scope of Paper:</h5>

                                <div>
                                    <input type="radio" name="scope" value="notRelevant" id="notRelevant">
                                    <label for="notRelevant">Not relevant</label>
                                </div>

                                <div>
                                    <input type="radio" name="scope" value="relevant" id="relevant">
                                    <label for="relevant">Relevant</label>
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <input type="radio" name="scope" value="highlyRelevant" id="highlyRelevant">
                                    <label for="highlyRelevant">Highly relevant</label>
                                </div>

                                <h5>Related work:</h5>

                                <div>
                                    <input type="radio" name="related" value="nil" id="nil">
                                    <label for="nil">Nil</label>
                                </div>

                                <div>
                                    <input type="radio" name="related" value="veryPoor" id="veryPoor">
                                    <label for="veryPoor">Very poor</label>
                                </div>

                                <div>
                                    <input type="radio" name="related" value="poor" id="poor">
                                    <label for="poor">Poor</label>
                                </div>

                                <div>
                                    <input type="radio" name="related" value="acceptable" id="acceptable">
                                    <label for="acceptable">Acceptable</label>
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <input type="radio" name="related" value="excellent" id="excellent">
                                    <label for="excellent">Excellent</label>
                                </div>

                                <h5>Reviewers Expertise:</h5>

                                <div>
                                    <input type="radio" name="expertise" value="nill" id="nill" >
                                    <label for="nill">Nil</label>
                                </div>

                                <div>
                                    <input type="radio" name="expertise" value="veryLow" id="veryLow" >
                                    <label for="veryLow">Very low</label>
                                </div>

                                <div>
                                    <input type="radio" name="expertise" value="low" id="low" >
                                    <label for="low">Low</label>
                                </div>

                                <div>
                                    <input type="radio" name="expertise" value="knowledgeable" id="knowledgeable" >
                                    <label for="knowledgeable">Knowledgeable</label>
                                </div>

                                <div>
                                    <input type="radio" name="expertise" value="high" id="high" >
                                    <label for="high">High</label>
                                </div>

                                <div>
                                    <input type="radio" name="expertise" value="veryHigh" id="veryHigh" >
                                    <label for="veryHigh">Very high</label>
                                </div>

                                <div style="margin-bottom: 30px;">
                                    <input type="radio" name="expertise" value="expert" id="expert" >
                                    <label for="expert">Expert</label>
                                </div> -->




                            </div>



                            <!----------------in between------------------>




                        </div>

                        <hr style="height: 1px; background-color: var(--main, #0858A4); width: 100%;">

                        <!-- <div class="decisions">
                            <h5>Decision:</h5>

                            <div>
                                <input type="radio" name="decision" value="decline" id="decline">
                                <label for="decline">Decline</label>
                            </div>

                            <div>
                                <input type="radio" name="decision" value="acceptSignif" id="acceptSignif">
                                <label for="acceptSignif">Accept if significant modifications are carried out</label>
                            </div>

                            <div>
                                <input type="radio" name="decision" value="acceptMinor" id="acceptMinor">
                                <label for="acceptMinor">Accept if minor modifications are carried out</label>
                            </div>

                            <div>
                                <input type="radio" name="decision" value="acceptWithout" id="acceptWithout">
                                <label for="acceptWithout">Accept without modifications</label>
                            </div>

                        </div> -->

                        <div class="btn-final">
                            <button type="button" id="btnSubmit" class="btn tbn-primary btn-md" >Submit</button>
                            <button type="button" id="btnCancel" class="btn tbn-primary btn-md" onclick="prevStep()" >Cancel</button>
                        </div>
                    </div>
                </div>



                <div class="col-md-1">
                    <!-- This is a Blank space -->
                </div>

            </div>
        </div>
    </div>
  
</form>


  <div id="loadingOverlay">
        <div id="loadingSpinner"></div>
    </div>
</body>
</html>




<div class="footer" id="footer">

</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="../JS/reusable-header.js"></script>
    <script src="../JS/review-process.js"></script>
    <script>
        document.getElementById('reviewNow').addEventListener('click', function(event){
           const step1 = document.getElementById('step1');
           const step2 = document.getElementById('step2');
           step1.style.display = 'none';
           step2.style.display = 'block';
        });

        document.getElementById('reviewBtn').addEventListener('click', function(event){
           const step2 = document.getElementById('step2');
           const step3 = document.getElementById('step3');
           step2.style.display = 'none';
           step3.style.display = 'block';
        });
 

        document.getElementById('btnReject').addEventListener('click', function(event){
    Swal.fire({
        title: "Decline Invitation",
        text: "You won't be able to revert this",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "secondary",
        confirmButtonText: "Decline"
    }).then((result) => {
        if (result.isConfirmed) {
            showLoader();

            // Send AJAX request
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'reject-invi.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            var articleId = "<?php echo $articleId; ?>";

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // AJAX request was successful
                    window.location.href = '../PHP/author-dashboard.php';
                } else {
                    // Handle errors if any
                    console.error('AJAX request failed with status: ' + xhr.status);
                }
            };

            // Send the request with articleId
            xhr.send('article_id=' + articleId);
        }
    });
});

document.getElementById('btnSubmit').addEventListener('click', function(event){
    const form = document.getElementById('form');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    // Check if all radio buttons are selected
    const radioGroups = document.querySelectorAll('input[type="radio"]:required');
    let allRadioButtonsSelected = true;

    radioGroups.forEach(function(radioGroup) {
        if (!document.querySelector('input[name="' + radioGroup.name + '"]:checked')) {
            allRadioButtonsSelected = false;
        }
    });

    if (!allRadioButtonsSelected) {
        // Alert the user if not all radio buttons are selected
        Swal.fire({
            icon: 'warning',
            text: 'All fields are required!'
        })
        return;
    }

    Swal.fire({
        icon: 'question',
        title: 'Submit it now?',
        text: 'you won\'t be able to revert it after submitting',
        showCancelButton: true,
        showConfirmButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
            loadingOverlay.style.display = "block";
        }
    });
});


    </script>
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
    })
    .catch(error => console.error('Error loading navbar.php:', error));
}

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

// Call includeNavbar function to load navbar.php content
includeNavbar();


</script>   
</body>
</html>

