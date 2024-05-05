<?php
include 'function/redirect.php';
include 'function/workflow_function.php';
include 'function/wf_modal_function.php';
include 'function/email_function.php';

$aid = isset($_GET['aid']) ? $_GET['aid'] : 1;
$emc = 10;

$article_data = get_article_data($aid);
$submission_files = get_submission_files($aid);
$review_files = get_review_files($aid);
$copyediting_files = get_copyediting_files($aid);
$production_files = get_production_files($aid);
$revision_files = get_revision_files($aid);
// $copyedited_files = get_copyedited_files($aid);
$allcopyedited_files = get_allcopyedited_files($aid);
$allproduction_files = get_production_files($aid);
$article_discussion = get_article_discussion($aid);
$discussion_list = get_discussion($aid);
$article_participant = get_article_participant($aid);
$reviewer_email = get_reviewer_content($emc);
$article_contributors = get_article_contributor($aid);
$article_reviewer = get_article_reviewer($aid);
$article_reviewer_check = check_article_reviewer($aid);
$article_reviewer_accept = check_reviewer_accept();
$article_reviewer_notcomplete = check_reviewer_notcomplete();
$article_reviewer_ongoing = check_reviewer_ongoing();
$authorlist = get_author_list();
$article_finalfile_checker = check_article_articlefinalfile($aid);
$reviewer_details = get_reviewer_details();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedRound = $_POST["roundFilter"];
} else {
    $selectedRound = $article_data[0]->round;
}

if ($selectedRound == "all") {
    $filteredData = $article_reviewer; 
} else {
    $filteredData = array_filter($article_reviewer, function ($item) use ($selectedRound) {
        return strpos($item->round, $selectedRound) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<style>
.custom-body {
  height: 50%;
  overflow: auto;
  max-height: 400px;
  margin: 0 auto; 
}

#nopadding {
  padding: 0 !important;
}

#addspadding {
  padding: 10px 30px 10px 30px;
  font-size: 15px;
  margin-right: 15px;
}

#addpadding {
  padding: 14px 30px 14px 30px;
  font-size: 15px;
  margin-right: 15px;
}
table {
  border: 1px solid #000;
  border-collapse: collapse;
}
.top5-reviewer {
    font-weight: bold; 
}
</style>

<body>
    <!-- Include header -->
    <?php include 'template/header.php'; ?>

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h5 class="py-3 mb-4"><a href="../php/submissionlist.php?cid=<?php echo $article_data[0]->journal_id; ?>"><span class="text-muted fw-light">Submission /</span></a>&nbsp;<?php echo $article_data[0]->article_id; ?> / <?php echo $article_data[0]->title; ?></h5>

            <div class="row">
                <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-tabs">
                            <li class="nav-item ms-auto">
                                <button type="button" class="btn btn-dark" id="activityLogButton" style="width: 150px; margin-right: 5px; height: 40px;" data-bs-toggle="modal" data-bs-target="#viewLogsModal">Activity Log</button>
                            </li>
                            <li class="nav-item">
                                
                            <?php
                                $status = $article_data[0]->status;

                                if ($status == 1) {
                                    echo '<a href="javascript:void(0);" onclick="sendForArchive()" class="btn btn-danger btn-lg btn-block" style="width: 200px; height: 40px; margin-right: 5px;">Archive Article</a>';
                                } else if ($status == 11){
                                    echo '<a href="javascript:void(0);" onclick="openPagePreviewCentered(\'../../PHP/article-details-preview.php?articleId=' . $article_data[0]->article_id . '\')" class="btn btn-primary btn-lg btn-block" style="width: 100px; height: 40px; margin-right: 5px;">Preview</a>';
                                    echo '<a href="javascript:void(0);" onclick="sendForPublished()" class="btn btn-success btn-lg btn-block" style="width: 100px; height: 40px; margin-right: 5px;">Publish</a>';
                                }else if ($status == 0){
                                    echo '<a class="duplicateButton btn btn-warning btn-lg btn-block d-none" href="javascript:void(0);" onclick="fetchDuplicate()" style="width: 120px; height: 40px; margin-right: 5px;">Duplicate<span id="badgeContainer" class="badge"></span></a>';
                                    echo '<a href="javascript:void(0);" onclick="sendForPublished()" class="btn btn-success btn-lg btn-block" style="width: 100px; height: 40px; margin-right: 5px;">Publish</a>';
                                }else if ($status != 6){
                                    echo '<a class="duplicateButton btn btn-warning btn-lg btn-block d-none" href="javascript:void(0);" onclick="fetchDuplicate()" style="width: 120px; height: 40px; margin-right: 5px;">Duplicate<span id="badgeContainer" class="badge"></span></a>';
                                    echo '<a href="javascript:void(0);" onclick="sendForDecline()" class="btn btn-danger btn-lg btn-block" style="width: 100px; height: 40px;">Decline</a>';
                                }
                                ?>
                            </li>
                        </ul>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <button type="button" id="addspadding" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-workflow" aria-controls="navs-top-workflow" aria-selected="true"> Workflow</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" id="addspadding" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-publication" aria-controls="navs-top-publication" aria-selected="false"> Publication</button>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="navs-top-workflow" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12" id="nopadding">
                                        <div class="nav-align-top mb-4">
                                        <ul class="nav nav-tabs" id="myTabs">   
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" id="navs-top-submission-tab" data-bs-toggle="tab" data-bs-target="#navs-top-submission" aria-controls="navs-top-submission" aria-selected="true">Submission</button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" id="navs-top-review-tab" data-bs-toggle="tab" data-bs-target="#navs-top-review" aria-controls="navs-top-review" aria-selected="false">Review</button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" id="navs-top-copyediting-tab" data-bs-toggle="tab" data-bs-target="#navs-top-copyediting" aria-controls="navs-top-copyediting" aria-selected="false">Copyediting</button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" id="navs-top-production-tab" data-bs-toggle="tab" data-bs-target="#navs-top-production" aria-controls="navs-top-production" aria-selected="false">Production</button>
                                            </li>
                                        </ul>
                                            <div class="tab-content">
                                                <?php if ($article_data[0]->status == 6): ?>
                                                <div class="tab-pane fade show active" id="navs-top-submission" role="tabpanel">
                                                    <div class="row">
                                                        <div class="alert alert-white" role="alert">
                                                            <p>Submission rejected.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php else: ?>
                                                <div class="tab-pane fade show active" id="navs-top-submission" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-9" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Submission Files</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addFilesModal">Upload File</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($submission_files)): ?>
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No Files</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($submission_files as $submission_filesval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $submission_filesval->article_files_id; ?></td>
                                                                                <td width="65%">
                                                                                    <a href="../../../Files/submitted-article/<?php echo ($submission_filesval->file_name); ?>" download>
                                                                                        <?php echo $submission_filesval->file_name; ?>
                                                                                    </a>
                                                                                </td>
                                                                                <td width="25%"><?php echo $submission_filesval->file_type; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;">
                                                                            <button type="button" class="btn btn-outline-dark" id="downloadAllButton" style="width: 150px;">Download File</button>
                                                                        </th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-4 mt-lg-0" id="dynamic-column">
                                                            <?php if ($article_data[0]->status == 11): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for review.</p>
                                                                </div>
                                                            <?php elseif ($article_data[0]->status <= 4): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for review.</p>
                                                                </div>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0);" onclick="sendForReview()" class="btn btn-primary btn-lg btn-block mb-2" style="width: 100%;">Send for Review</a>
                                                                <a href="javascript:void(0);" onclick="sendForCopyediting()" class="btn btn-outline-primary btn-lg btn-block mb-2" style="width: 100%;">Accept and Skip Review</a>
                                                                <!-- <a href="javascript:void(0);" onclick="sendForDecline()" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">Decline Submission</a> -->
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-9 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Submission Discussions</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" onclick="showDiscussionModal('Submission')" data-bs-toggle="modal" data-bs-target="#addDiscussionModal">Add Discussion</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (empty($discussion_list)): ?>
                                                                            <tr>
                                                                                <td colspan="3" class="text-center">No Items</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php $hasItems = false; ?>
                                                                            <?php foreach ($discussion_list as $discussion_listval): ?>
                                                                                <?php if ($discussion_listval->discussion_type === 'Submission'): ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $discussion_listval->discussion_id; ?></td>
                                                                                        <td width="90%"><?php echo $discussion_listval->subject; ?></td>
                                                                                        <td width="5%" style="text-align: right;"><a href="javascript:void(0);" onclick="viewDiscussion(<?php echo $discussion_listval->discussion_id; ?>,'<?php echo $discussion_listval->subject; ?>','Submission')" class="btn btn-outline-dark" style="margin-right: 10px;">View</a></td>
                                                                                    </tr>
                                                                                    <?php $hasItems = true; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <?php if (!$hasItems): ?>
                                                                                <tr>
                                                                                    <td colspan="3" class="text-center">No Items</td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>  
                                                        <div class="col-md-3 mt-4" id="dynamic-column">
                                                            <!-- <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Participants</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="margin-left: -10px">Add</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($article_participant)): ?>
                                                                        <tr>
                                                                            <td colspan="3" class="text-center">No Items</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($article_participant as $article_participantval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $article_participantval->id; ?></td>
                                                                                <td width="85%"><?php echo $article_participantval->file_name; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>    
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot> 
                                                                </table>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
<!-- Review -->
                                                <?php if ($article_data[0]->status >= 5 && $article_data[0]->status != 11): ?>
                                                <div class="tab-pane fade" id="navs-top-review" role="tabpanel">
                                                    <div class="row">
                                                        <div class="alert alert-white" role="alert">
                                                            <p>Not currently accepted for review.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php else: ?>
                                                <div class="tab-pane fade" id="navs-top-review" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-9" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Review Files</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addReviewFilesModal">Select Files</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($review_files)): ?>
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No Files</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($review_files as $review_filesval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $review_filesval->article_files_id; ?></td>
                                                                                <td width="65%">
                                                                                    <a href="../../../Files/submitted-article/<?php echo ($review_filesval->file_name); ?>" download>
                                                                                        <?php echo $review_filesval->file_name; ?>
                                                                                    </a>
                                                                                </td>
                                                                                <td width="25%"><?php echo $review_filesval->file_type; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;">
                                                                            <button type="button" class="btn btn-outline-dark" id="downloadAllButton1" style="width: 150px;">Download File</button>
                                                                        </th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 mt-4 mt-lg-0" id="dynamic-column">
                                                            <?php if ($article_data[0]->status == 11): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for copyediting.</p>
                                                                </div>
                                                            <?php elseif ($article_data[0]->status <= 3): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for copyediting.</p>
                                                                </div>
                                                            <?php else: ?>
                                                                <?php if ($article_data[0]->round != 'Round 3'): ?>
                                                                    <a href="javascript:void(0);" onclick="sendForRevision()" class="btn btn-outline-primary btn-lg btn-block mb-2" style="width: 100%;">Request Revision</a>
                                                                <?php endif; ?>
                                                                <a href="javascript:void(0);" onclick="sendForCopyediting()" class="btn btn-primary btn-lg btn-block mb-2" style="width: 100%;">Accept Submission</a>
                                                                <!-- <a href="javascript:void(0);" onclick="sendForDecline()" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">Decline Submission</a> -->
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-9 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Revisions</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 160px;" data-bs-toggle="modal" data-bs-target="#addRevisionModal">Upload Revision</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>FILE NAME</th>
                                                                            <th>TYPE</th>
                                                                            <th>FROM</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($revision_files)): ?>
                                                                        <tr>
                                                                            <td colspan="4" class="text-center">No Files</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($revision_files as $revision_filesval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $revision_filesval->revision_files_id; ?></td>
                                                                                <td width="55%">
                                                                                    <a href="../../../Files/revision-article/<?php echo ($revision_filesval->file_name); ?>" download>
                                                                                        <?php echo $revision_filesval->file_name; ?>
                                                                                    </a>
                                                                                </td>
                                                                                <td width="20%"><?php echo $revision_filesval->file_type; ?></td>
                                                                                <td width="20%"><?php echo $revision_filesval->fromuser; ?></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>    
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>  
                                                        <div class="col-md-3 mt-4" id="dynamic-column">
                                                            <!-- <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Participants</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="margin-left: -10px">Add</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($article_participant)): ?>
                                                                        <tr>
                                                                            <td colspan="3" class="text-center">No Items</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($article_participant as $article_participantval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $article_participantval->id; ?></td>
                                                                                <td width="85%"><?php echo $article_participantval->file_name; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>    
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot> 
                                                                </table>
                                                            </div> -->
                                                        </div> 

                                                        <div class="col-md-12 mt-4" id="dynamic-column">
                                                            <div class="mb-3">
                                                                <form method="post" action="">
                                                                    <?php
                                                                    // Initialize $selectedRound with the default value from $article_data[0]->round
                                                                    $selectedRound = isset($_POST["roundFilter"]) ? $_POST["roundFilter"] : $article_data[0]->round;
                                                                    ?>
                                                                    <label for="roundFilter" class="form-label">Filter by Round:</label>
                                                                    <select class="form-select" id="roundFilter" name="roundFilter" onchange="this.form.submit()">
                                                                        <?php
                                                                        // Conditionally generate options based on the value of $article_data[0]->round
                                                                        if ($article_data[0]->round == "Round 1") {
                                                                            echo '<option value="Round 1" ' . ($selectedRound == "Round 1" ? "selected" : "") . '>Round 1</option>';
                                                                        } elseif ($article_data[0]->round == "Round 2") {
                                                                            echo '<option value="Round 1" ' . ($selectedRound == "Round 1" ? "selected" : "") . '>Round 1</option>';
                                                                            echo '<option value="Round 2" ' . ($selectedRound == "Round 2" ? "selected" : "") . '>Round 2</option>';
                                                                        } elseif ($article_data[0]->round == "Round 3") {
                                                                            echo '<option value="Round 1" ' . ($selectedRound == "Round 1" ? "selected" : "") . '>Round 1</option>';
                                                                            echo '<option value="Round 2" ' . ($selectedRound == "Round 2" ? "selected" : "") . '>Round 2</option>';
                                                                            echo '<option value="Round 3" ' . ($selectedRound == "Round 3" ? "selected" : "") . '>Round 3</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </form>
                                                            </div>

                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="4"><h6>Reviewers</h6></th>
                                                                            <th colspan="3" style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="addReviewer" style="width: 150px;" data-bs-toggle="modal">Add Reviewers</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (empty($filteredData)): ?>
                                                                            <tr>
                                                                                <td colspan="7" class="text-center">No Items</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php foreach ($filteredData as $article_reviewerval): ?>
                                                                                <tr>
                                                                                    <td width="5%"><?php echo $article_reviewerval->reviewer_assigned_id; ?></td>
                                                                                    <td width="5%"><?php echo $article_reviewerval->author_id; ?></td>
                                                                                    <?php
                                                                                    $matchingReviewer = null;
                                                                                    foreach ($reviewer_details as $reviewer) {
                                                                                        if ($reviewer->author_id == $article_reviewerval->author_id) {
                                                                                            $matchingReviewer = $reviewer;
                                                                                            break;
                                                                                        }
                                                                                    }
                                                                                    if ($matchingReviewer) { ?>
                                                                                        <td width="84%"><?php echo $matchingReviewer->last_name . ', ' . $matchingReviewer->first_name; ?></td>
                                                                                        <td width="3%">
                                                                                        <?php
                                                                                            date_default_timezone_set('Asia/Manila');
                                                                                            $date = date('Y-m-d');  
                                                                                            if ($article_reviewerval->comment_accessible == 1) {
                                                                                                echo '<span style="color: green;">Accepted</span>';
                                                                                            } else if ($article_reviewerval->accept == 2) {
                                                                                                echo '<span style="color: red;">Reject</span>';
                                                                                            } else if ($article_reviewerval->deadline <= $date && $article_reviewerval->accept == 0) {
                                                                                                echo '<span style="color: orange;">Incomplete</span>';
                                                                                            }
                                                                                            ?>
                                                                                        </td>
                                                                                        <td colspan="3" style="text-align: right;">
                                                                                            <?php if ((strpos($article_reviewerval->round, 'Round 1') !== false) && ($article_reviewerval->answer != 0)): ?>
                                                                                                <a href="javascript:void(0);" onclick="viewReviewerAnswer(<?php echo $article_reviewerval->reviewer_assigned_id ; ?>,<?php echo $article_reviewerval->comment_accessible ; ?>,<?php echo $article_reviewerval->author_id; ?>,'<?php echo $article_data[0]->article_id; ?>','<?php echo $article_reviewerval->author_id ; ?>','<?php echo $matchingReviewer->last_name . ', ' . $matchingReviewer->first_name; ?>','Round 1')" class="btn btn-outline-dark Round1" style="margin-right: 10px;">Answer</a>
                                                                                            <?php endif; ?>

                                                                                            <?php if ((strpos($article_reviewerval->round, 'Round 2') !== false) && ($article_reviewerval->answer != 0)): ?>
                                                                                                <a href="javascript:void(0);" onclick="viewReviewerAnswer(<?php echo $article_reviewerval->reviewer_assigned_id ; ?>,<?php echo $article_reviewerval->comment_accessible ; ?>,<?php echo $article_reviewerval->author_id; ?>,'<?php echo $article_data[0]->article_id; ?>','<?php echo $article_reviewerval->author_id ; ?>','<?php echo $matchingReviewer->last_name . ', ' . $matchingReviewer->first_name; ?>','Round 2')" class="btn btn-outline-dark Round2" style="margin-right: 10px;">Answer</a>
                                                                                            <?php endif; ?>

                                                                                            <?php if ((strpos($article_reviewerval->round, 'Round 3') !== false) && ($article_reviewerval->answer != 0)): ?>
                                                                                                <a href="javascript:void(0);" onclick="viewReviewerAnswer(<?php echo $article_reviewerval->reviewer_assigned_id ; ?>,<?php echo $article_reviewerval->comment_accessible ; ?>,<?php echo $article_reviewerval->author_id; ?>,'<?php echo $article_data[0]->article_id; ?>','<?php echo $article_reviewerval->author_id ; ?>','<?php echo $matchingReviewer->last_name . ', ' . $matchingReviewer->first_name; ?>','Round 3')" class="btn btn-outline-dark Round3" style="margin-right: 10px;">Answer</a>
                                                                                            <?php endif; ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php } else { ?>
                                                                                        <tr>
                                                                                            <td colspan="7">No matching reviewer found.</td>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                            <?php endforeach; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="7" style="text-align: right;"></th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-12 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Review Discussion</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" onclick="showDiscussionModal('Review')" data-bs-toggle="modal" data-bs-target="#addDiscussionModal">Add Discussion</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (empty($discussion_list)): ?>
                                                                            <tr>
                                                                                <td colspan="3" class="text-center">No Items</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php $hasItems = false; ?>
                                                                            <?php foreach ($discussion_list as $discussion_listval): ?>
                                                                                <?php if ($discussion_listval->discussion_type === 'Review'): ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $discussion_listval->discussion_id; ?></td>
                                                                                        <td width="90%"><?php echo $discussion_listval->subject; ?></td>
                                                                                        <td width="5%" style="text-align: right;"><a href="javascript:void(0);" onclick="viewDiscussion(<?php echo $discussion_listval->discussion_id; ?>,'<?php echo $discussion_listval->subject; ?>','Review')" class="btn btn-outline-dark" style="margin-right: 10px;">View</a></td>
                                                                                    </tr>
                                                                                    <?php $hasItems = true; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <?php if (!$hasItems): ?>
                                                                                <tr>
                                                                                    <td colspan="3" class="text-center">No Items</td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div> 
                                                    </div>
                                                </div>
                                                <?php endif; ?>
<!-- Copyediting -->
                                                <?php if ($article_data[0]->status >= 4 && $article_data[0]->status != 11): ?>
                                                <div class="tab-pane fade" id="navs-top-copyediting" role="tabpanel">
                                                    <div class="row">
                                                        <div class="alert alert-white" role="alert">
                                                            <p>Not currently accepted for copyediting.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php else: ?>
                                                <div class="tab-pane fade" id="navs-top-copyediting" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-9" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Draft Files</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addCopyeditingFilesModal">Select Files</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $hasCopyeditedFiles = false;

                                                                        foreach ($revision_files as $revision_filesval) {
                                                                            if ($revision_filesval->copyediting == 1) {
                                                                                $hasCopyeditedFiles = true;
                                                                                break;
                                                                            }
                                                                        }

                                                                        foreach ($submission_files as $submission_filesval) {
                                                                            if ($submission_filesval->copyediting == 1) {
                                                                                $hasCopyeditedFiles = true;
                                                                                break;
                                                                            }
                                                                        }
                                                                        if ($hasCopyeditedFiles) {
                                                                            foreach ($revision_files as $revision_filesval) {
                                                                                if ($revision_filesval->copyediting == 1) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $revision_filesval->revision_files_id; ?></td>
                                                                                        <td width="65%">
                                                                                            <a href="../../../Files/submitted-article/<?php echo ($revision_filesval->file_name); ?>" download>
                                                                                                <?php echo $revision_filesval->file_name; ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td width="25%"><?php echo $revision_filesval->file_type; ?></td>
                                                                                        <td width="5%"><span class="badge rounded-pill bg-label-warning">Revision</span></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }

                                                                            foreach ($submission_files as $submission_filesval) {
                                                                                if ($submission_filesval->copyediting == 1) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $submission_filesval->article_files_id; ?></td>
                                                                                        <td width="65%">
                                                                                            <a href="../../../Files/submitted-article/<?php echo ($submission_filesval->file_name); ?>" download>
                                                                                                <?php echo $submission_filesval->file_name; ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td width="25%"><?php echo $submission_filesval->file_type; ?></td>
                                                                                        <td width="5%"><span class="badge rounded-pill bg-label-warning">Submission</span></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                        } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="4" class="text-center">No Items</td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;">
                                                                            <button type="button" class="btn btn-outline-dark" id="downloadAllButton2" style="width: 150px;">Download File</button>
                                                                        </th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-4 mt-lg-0" id="dynamic-column">
                                                            <?php if ($article_data[0]->status == 11): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for production.</p>
                                                                </div>
                                                            <?php elseif ($article_data[0]->status <= 2): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for production.</p>
                                                                </div>
                                                            <?php else: ?>
                                                                <a href="javascript:void(0);" onclick="sendForProduction()" class="btn btn-primary btn-lg btn-block mb-2" style="width: 100%;">Send to Production</a>
                                                                <!-- <a href="javascript:void(0);" onclick="" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">Cancel Copyediting</a> -->
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-9 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Copyedited Files</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addCopyeditedFilesModal">Select Files</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $hasCopyeditedFiles = false;

                                                                        foreach ($allcopyedited_files as $allcopyedited_filesval) {
                                                                            if ($allcopyedited_filesval->copyedited == 1) {
                                                                                $hasCopyeditedFiles = true;
                                                                                break;
                                                                            }
                                                                        }

                                                                        if ($hasCopyeditedFiles) {
                                                                            foreach ($allcopyedited_files as $allcopyedited_filesval) {
                                                                                if ($allcopyedited_filesval->copyedited == 1) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $allcopyedited_filesval->final_files_id; ?></td>
                                                                                        <td width="65%">
                                                                                            <a href="#" onclick="convertAndDownload('<?php echo ($allcopyedited_filesval->file_name); ?>'); return false;">
                                                                                                <?php echo $allcopyedited_filesval->file_name; ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td width="25%"><?php echo $allcopyedited_filesval->file_type; ?></td>
                                                                                        <td width="5%"><span class="badge rounded-pill bg-label-warning">Copyedited</span></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }

                                                                        } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="4" class="text-center">No Items</td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>  
                                                        <div class="col-md-3 mt-4" id="dynamic-column">
                                                            <!-- <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Participants</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="margin-left: -10px">Add</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($article_participant)): ?>
                                                                        <tr>
                                                                            <td colspan="3" class="text-center">No Items</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($article_participant as $article_participantval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $article_participantval->id; ?></td>
                                                                                <td width="85%"><?php echo $article_participantval->file_name; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>    
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot> 
                                                                </table>
                                                            </div> -->
                                                        </div> 
                                                        <div class="col-md-12 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Copyediting Discussions</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" onclick="showDiscussionModal('Copyediting')" data-bs-toggle="modal" data-bs-target="#addDiscussionModal">Add Discussion</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (empty($discussion_list)): ?>
                                                                            <tr>
                                                                                <td colspan="3" class="text-center">No Items</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php $hasItems = false; ?>
                                                                            <?php foreach ($discussion_list as $discussion_listval): ?>
                                                                                <?php if ($discussion_listval->discussion_type === 'Copyediting'): ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $discussion_listval->discussion_id; ?></td>
                                                                                        <td width="90%"><?php echo $discussion_listval->subject; ?></td>
                                                                                        <td width="5%" style="text-align: right;"><a href="javascript:void(0);" onclick="viewDiscussion(<?php echo $discussion_listval->discussion_id; ?>,'<?php echo $discussion_listval->subject; ?>','Copyediting')" class="btn btn-outline-dark" style="margin-right: 10px;">View</a></td>
                                                                                    </tr>
                                                                                    <?php $hasItems = true; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <?php if (!$hasItems): ?>
                                                                                <tr>
                                                                                    <td colspan="3" class="text-center">No Items</td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>
                                                <?php endif; ?>

<!-- Production -->                             <?php if ($article_data[0]->status >= 3 && $article_data[0]->status != 11): ?>
                                                <div class="tab-pane fade" id="navs-top-production" role="tabpanel">
                                                    <div class="row">
                                                        <div class="alert alert-white" role="alert">
                                                            <p>Not currently accepted for production.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php else: ?>
                                                <div class="tab-pane fade" id="navs-top-production" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-9" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="3"><h6>Production Ready Files</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#addProductionFilesModal">Select Files</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php
                                                                        $hasCopyeditedFiles = false;

                                                                        foreach ($allcopyedited_files as $allcopyedited_filesval) {
                                                                            if ($allcopyedited_filesval->production == 1) {
                                                                                $hasCopyeditedFiles = true;
                                                                                break;
                                                                            }
                                                                        }

                                                                        foreach ($allproduction_files as $allproduction_filesval) {
                                                                            if ($allproduction_filesval->production == 1) {
                                                                                $hasCopyeditedFiles = true;
                                                                                break;
                                                                            }
                                                                        }

                                                                        if ($hasCopyeditedFiles) {
                                                                            foreach ($allcopyedited_files as $allcopyedited_filesval) {
                                                                                if ($allcopyedited_filesval->production == 1) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $allcopyedited_filesval->final_files_id; ?></td>
                                                                                        <td width="65%">
                                                                                            <a href="#" onclick="convertAndDownload('<?php echo ($allcopyedited_filesval->file_name); ?>'); return false;">
                                                                                                <?php echo $allcopyedited_filesval->file_name; ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td width="25%"><?php echo $allcopyedited_filesval->file_type; ?></td>
                                                                                        <td width="5%"><span class="badge rounded-pill bg-label-warning">Copyedited</span></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }

                                                                            foreach ($allproduction_files as $allproduction_filesval) {
                                                                                if ($allproduction_filesval->production == 1) {
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $allproduction_filesval->final_files_id; ?></td>
                                                                                        <td width="65%">
                                                                                            <a href="../../../Files/final-file/<?php echo ($allproduction_filesval->file_name); ?>" download>
                                                                                                <?php echo $allproduction_filesval->file_name; ?>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td width="25%"><?php echo $allproduction_filesval->file_type; ?></td>
                                                                                        <td width="5%"><span class="badge rounded-pill bg-label-warning">Production</span></td>
                                                                                    </tr>
                                                                                    <?php
                                                                                }
                                                                            }

                                                                        } else {
                                                                            ?>
                                                                            <tr>
                                                                                <td colspan="4" class="text-center">No Items</td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="4" style="text-align: right;">
                                                                            <button type="button" class="btn btn-outline-dark" id="downloadAllButton3" style="width: 150px;">Download File</button>
                                                                        </th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mt-4 mt-lg-0" id="dynamic-column">
                                                            <?php if ($article_data[0]->status == 11): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission accepted for scheduled.</p>
                                                                </div>
                                                            <?php elseif ($article_data[0]->status == 1): ?>
                                                                <div class="alert alert-white" role="alert">
                                                                    <p>Submission published.</p>
                                                                </div>
                                                            <?php elseif ($article_data[0]->status >= 2): ?>
                                                                <a href="javascript:void(0);" onclick="checkFinalFileSubmission()" class="btn btn-primary btn-lg btn-block mb-2" style="width: 100%;">Send to Publication</a>
                                                                <!-- <a href="javascript:void(0);" onclick="" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">Cancel Production</a> -->
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-md-9 mt-4" id="dynamic-column">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Production Discussions</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="width: 150px;" onclick="showDiscussionModal('Production')" data-bs-toggle="modal" data-bs-target="#addDiscussionModal">Add Discussion</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if (empty($discussion_list)): ?>
                                                                            <tr>
                                                                                <td colspan="3" class="text-center">No Items</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php $hasItems = false; ?>
                                                                            <?php foreach ($discussion_list as $discussion_listval): ?>
                                                                                <?php if ($discussion_listval->discussion_type === 'Production'): ?>
                                                                                    <tr>
                                                                                        <td width="5%"><?php echo $discussion_listval->discussion_id; ?></td>
                                                                                        <td width="90%"><?php echo $discussion_listval->subject; ?></td>
                                                                                        <td width="5%" style="text-align: right;"><a href="javascript:void(0);" onclick="viewDiscussion(<?php echo $discussion_listval->discussion_id; ?>,'<?php echo $discussion_listval->subject; ?>','Production')" class="btn btn-outline-dark" style="margin-right: 10px;">View</a></td>
                                                                                    </tr>
                                                                                    <?php $hasItems = true; ?>
                                                                                <?php endif; ?>
                                                                            <?php endforeach; ?>
                                                                            <?php if (!$hasItems): ?>
                                                                                <tr>
                                                                                    <td colspan="3" class="text-center">No Items</td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot>   
                                                                </table>
                                                            </div>
                                                        </div>  
                                                        <div class="col-md-3 mt-4" id="dynamic-column">
                                                            <!-- <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped" id="DataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th colspan="2"><h6>Participants</h6></th>
                                                                            <th style="text-align: right;">
                                                                                <button type="button" class="btn btn-dark" id="uploadButton" style="margin-left: -10px">Add</button>
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php if (empty($article_participant)): ?>
                                                                        <tr>
                                                                            <td colspan="3" class="text-center">No Items</td>
                                                                        </tr>
                                                                    <?php else: ?>
                                                                        <?php foreach ($article_participant as $article_participantval): ?>
                                                                            <tr>
                                                                                <td width="5%"><?php echo $article_participantval->id; ?></td>
                                                                                <td width="85%"><?php echo $article_participantval->file_name; ?></td>
                                                                                <td width="5%"></td>
                                                                            </tr>
                                                                        <?php endforeach; ?>    
                                                                    <?php endif; ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <th colspan="3" style="text-align: right;"></th>
                                                                    </tfoot> 
                                                                </table>
                                                            </div> -->
                                                        </div> 
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="navs-top-publication" role="tabpanel">
                                <div class="container" id="nopadding">
                                    <div class="row">
                                        <!-- Left-side navigation -->
                                        <div class="col-md-2" id="nopadding">
                                            <div class="nav-align-left mb-4">
                                                <ul class="nav nav-tabs flex-column" role="tablist">
                                                    <li class="nav-item">
                                                        <button type="button" id="addpadding" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-one" aria-controls="navs-top-one" aria-selected="true">Title & Abstract</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button type="button" id="addpadding" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-two" aria-controls="navs-top-two" aria-selected="false">Contributors</button>
                                                    </li>
                                                    <!-- <li class="nav-item">
                                                        <button type="button" id="addpadding" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-three" aria-controls="navs-top-three" aria-selected="false">Meta Data</button>
                                                    </li> -->
                                                    <li class="nav-item">
                                                        <button type="button" id="addpadding" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-four" aria-controls="navs-top-four" aria-selected="false"> References</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Content section -->
                                        <div class="col-md-10">
                                            <div class="col-xl-12" id="nopadding">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="navs-top-one" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12" id="dynamic-column">
                                                                <div class="card-body">
                                                                    <div class="mb-3">
                                                                        <label for="defaultFormControlInput1" class="form-label">Title</label>
                                                                        <input type="text" class="form-control" id="title" placeholder="Title" value="<?php echo $article_data[0]->title; ?>" aria-describedby="defaultFormControlHelp" />
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="defaultFormControlInput2" class="form-label">Keywords</label>
                                                                        <input type="text" class="form-control" id="keyword" placeholder="Keywords" value="<?php echo $article_data[0]->keyword; ?>" aria-describedby="defaultFormControlHelp" />
                                                                    </div>   
                                                                    <div class="mb-3">
                                                                        <label for="defaultFormControlInput3" class="form-label">Abstract</label>
                                                                        <div id="quill-abstract" style="height: 250px;"></div>
                                                                    </div>
                                                                    <!-- <div class="d-flex justify-content-end mt-4" style="margin-bottom: -28px;">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="navs-top-two" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12" id="dynamic-column">
                                                                <div class="table-responsive text-nowrap">
                                                                    <table class="table table-striped" id="DataTable">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="4"><h6>Contributors</h6></th>
                                                                                <th style="text-align: right;">
                                                                                    <!-- <button type="button" class="btn btn-dark" id="uploadButton" style="margin-left: -10px" data-bs-toggle="modal" data-bs-target="#addContributorsModal">Add</button> -->
                                                                                </th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php if (empty($article_contributors)): ?>
                                                                            <tr>
                                                                                <td colspan="5" class="text-center">No data</td>
                                                                            </tr>
                                                                        <?php else: ?>
                                                                            <?php foreach ($article_contributors as $article_contributorsval): ?>
                                                                                <tr>
                                                                                    <td width="5%"><?php echo $article_contributorsval->contributors_id; ?></td>
                                                                                    <td width="40%">
                                                                                        <span class="badge rounded-pill bg-label-primary"><?php echo $article_contributorsval->contributor_type; ?></span>
                                                                                        <?php echo $article_contributorsval->lastname; ?>, <?php echo $article_contributorsval->firstname; ?>
                                                                                    </td>
                                                                                    <td width="40%"><?php echo $article_contributorsval->email; ?></td>
                                                                                    <td width="10%"><?php echo $article_contributorsval->orcid; ?></td>
                                                                                    <td width="5%">
                                                                                        <!-- <a href="javascript:void(0);" onclick="" class="btn btn-outline-primary">View</a> -->
                                                                                    </td>
                                                                                </tr>
                                                                            <?php endforeach; ?>    
                                                                        <?php endif; ?>
                                                                        </tbody>
                                                                        <tfoot>
                                                                            <th colspan="5" style="text-align: right;"></th>
                                                                        </tfoot> 
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="tab-pane fade" id="navs-top-four" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-md-12" id="dynamic-column">
                                                                <div class="card-body">
                                                                    <div class="mb-3">
                                                                        <label for="defaultFormControlInput3" class="form-label">Reference</label>
                                                                        <div id="quill-reference" style="height: 250px;"></div>
                                                                    </div>
                                                                    <!-- <div class="d-flex justify-content-end mt-4" style="margin-bottom: -28px;">
                                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                                    </div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Include footer -->
        <?php include 'template/footer.php'; include 'workflow_modal.php';?>
    </div>

<!-- Add Reviewer Modal -->
<div class="modal fade" id="addReviewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Assign Reviewer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>  
            <div class="modal-body">
                    <select class="form-select" id="sortDropdown">
                    <option value="score" selected>Sort By Score (default)</option>
                        <option value="success">Sort By Total Success</option>
                    </select> 
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped mb-4" id="DataTableSuggeted" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th colspan="3">Suggested Reviewers</th>
                                    </tr>
                                    <tr>
                                        <th>Author ID</th>
                                        <th>Author</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="reviewersTableBody">
                                </tbody>
                            </table>
                            <table class="table table-striped" id="DataTableOther"  style="width:100%;">
                                <thead>
                                    <tr>
                                        <th colspan="3">Other Reviewers</th>
                                    </tr>
                                    <tr>
                                        <th>Author ID</th>
                                        <th>Author</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="reviewersTableBody1">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Reviewer Modal -->
<div class="modal fade" id="AssignReviewer" tabindex="-1" aria-hidden="true">
    <form id="addModalForm1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Add <span id="AuthorName"></span> as Reviewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12 mb-2">  
                            <input type="hidden" id="round" value="<?php print $article_data[0]->round ?>" class="form-control"readonly/> 
                            <input type="hidden" id="articleid" value="<?php print $article_data[0]->article_id ?>" class="form-control"readonly/>  
                            <input type="hidden" id="reviewerid" class="form-control"readonly/>
                            <input type="hidden" id="revieweremail" class="form-control"readonly/>
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" id="emailsubject" class="form-control" value="<?php print $reviewer_email[0]->subject ?>" placeholder="Subject" readonly/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12 mb-2">
                            <label for="message" class="form-label">Message</label>
                            <input type="hidden" name="quillContentOne" id="quillContentOne">
                            <div id="quill-emailcontent" style="height: 350px;"></div>
                        </div>
                    </div>
                    <!-- <div class="row mb-2">
                        <div class="col-md-12 mb-2">
                            <label for="formFileAddDiscussion" class="form-label">Upload File</label>
                            <input class="form-control" type="file" id="formFileAddDiscussion" accept=".pdf, .doc, .docx" />
                        </div>
                    </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="" id="submitBtn">Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="DuplicateModal" tabindex="-1" aria-hidden="true">
    <form id="addModalForm1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">System Detect Duplicate <span id="AuthorName"></span> Content</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div id="Selectedarticle"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div id="SimilararticleLits"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="" id="submitBtn">Submit</button> -->
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="DuplicateModal2" tabindex="-1" aria-hidden="true">
    <form id="addModalForm1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Duplicate <span id="AuthorName"></span> Content</h5>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div id="Selectedarticle2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="card-body">
                                <div id="SimilararticleLits2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" onclick="" id="submitBtn">Submit</button> -->
                </div>
            </div>
        </div>
    </form>
</div>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
            $(document).ready(function() {
        $('#downloadAllButton').click(function() {
            <?php foreach ($submission_files as $submission_filesval): ?>
                downloadFile('../../../Files/submitted-article/<?php echo ($submission_filesval->file_name); ?>');
            <?php endforeach; ?>
        });
        $('#downloadAllButton1').click(function() {
            <?php foreach ($submission_files as $submission_filesval): ?>
                downloadFile('../../../Files/submitted-article/<?php echo ($submission_filesval->file_name); ?>');
            <?php endforeach; ?>
        });
        $('#downloadAllButton2').click(function() {
            <?php foreach ($revision_files as $revision_filesval): ?>
                downloadFile('../../../Files/submitted-article/<?php echo ($revision_filesval->file_name); ?>');
            <?php endforeach; ?>

            <?php foreach ($submission_files as $submission_filesval): ?>
                downloadFile('../../../Files/submitted-article/<?php echo ($submission_filesval->file_name); ?>');
            <?php endforeach; ?>
        });
        $('#downloadAllButton3').click(function() {
            <?php foreach ($allcopyedited_files as $allcopyedited_filesval): ?>
                convertAndDownload('<?php echo $allcopyedited_filesval->file_name; ?>');
            <?php endforeach; ?>

            <?php foreach ($allproduction_files as $allproduction_filesval): ?>
                downloadFile('../../../Files/final-file/<?php echo ($allproduction_filesval->file_name); ?>');
            <?php endforeach; ?>
        });

    function downloadFile(fileUrl) {
            var link = document.createElement('a');
            link.href = fileUrl;
            link.download = fileUrl.substr(fileUrl.lastIndexOf('/') + 1);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });

    function convertAndDownload(fileName) {
        $('#sloading').toggle();
        const fileExtension = fileName.split('.').pop().toLowerCase();
        if (fileExtension === 'pdf') {
            downloadFiles(fileName);
        } else {
            const inputFormat = fileExtension;
            const outputFormat = 'pdf';
            createCloudConvertJob(fileName, inputFormat, outputFormat);
        }
    }

    function downloadFiles(fileName) {
        const downloadUrl = `https://qcuj.online/Files/final-file/${encodeURIComponent(fileName)}`;

        const link = document.createElement("a");
        link.href = downloadUrl;
        link.download = fileName;
        link.setAttribute('target', '_blank');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        $('#sloading').toggle();
    }

    function createCloudConvertJob(file, inputFormat,format) {
        const apiKey =
        "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMGUyMzRlMzU3NTI1NzEyZGM2ZDBhNmE5N2E1Nzc3MThmMjRmYjA0MTA1Mjg5NDBiMzZkY2M1ODljNzg1YjdmNmE1MTFkOGU5MmJmNzU1YzAiLCJpYXQiOjE3MTI4OTE5MTkuMzQ2MzAyLCJuYmYiOjE3MTI4OTE5MTkuMzQ2MzA0LCJleHAiOjQ4Njg1NjU1MTkuMzQxMzM3LCJzdWIiOiI2NzQ2NTgzMyIsInNjb3BlcyI6WyJ1c2VyLnJlYWQiLCJ1c2VyLndyaXRlIiwidGFzay5yZWFkIiwidGFzay53cml0ZSJdfQ.rU5HjL0atO5jZephpN2qOBwPK7MiJp217e8QZBqAfxvvULfWewdWQXU7drpr-0KCN0DJZ1U4QXGz3AfzWzohb5HAjoYVaRBw1NLe5w2CsKVCTDWwNf85wKTqxtkxgy_UwlQQip1T1SMDhrDsDapNi7VTT0LsXyHC9_Sq754y74J2AMRtnV0BRVkLEpOgW-piRyg-zArmclLpYcb7W8yVL5UF8M--D2fLonHrwYOpKMCA1rOxQ8RlIjk18dtG2NRUTh8xFtmONA0xzTVmbdWGd_3a9WCKzBUxTcsZ29r5j_69fTYGcB1g7OYGhJr3YaLyyEOE6Fwr6Y6TE9b0ScdsB8Jw7WN2v2mBJhNjjholRmdxadq1RZreIA0xvCPwYsIu--Qnykxc9-p5l3sqm7FS5vWDBFpO1ZWK_C8L4QrAneISgutNoRMatXfdTlnmOtGFI7wCYKOAMaKGs67Kp1bomeoKFHrdyM4077WLIn5NJFqYYDo2_OZnb1IVku9v62OlJnxk_6sxicq8-BCdK78WKhP7yI2cm-0uG-d63thS4vTqqHEbkjn-dI4zWBuG8Z5WjBtNmgY8HzkM3LxXZuO0gKxO3kMeTiDW7c5_UvllQKATXMjsYEs2cFQKqO2Szrsmr7FSIIHmxRsUrJ4Ln-6A3NeNluDS97WRRx-WUKfondo";
    
        const apiUrl = "https://api.cloudconvert.com/v2/jobs";
        
        const jsonData = {
        tasks: {
            "import-1": {
            operation: "import/url",
            url: `https://qcuj.online/Files/final-file/${encodeURIComponent(file)}`,
            filename: file,
            },
            "task-1": {
            operation: "convert",
            input_format: inputFormat,
            output_format: format,
            engine: "calibre",
            input: ["import-1"],
            },
            "export-1": {
            operation: "export/url",
            input: ["task-1"],
            inline: true,
            archive_multiple_files: true,
            },
        },
        tag: "jobbuilder",
        };
    
        fetch(apiUrl, {
        method: "POST",
        body: JSON.stringify(jsonData),
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${apiKey}`,
        },
        })
        .then((response) => response.json())
        .then((data) => {
        
            const downloadUrl = `https://sync.api.cloudconvert.com/v2/jobs/${data.data.id}?redirect=true`;
    
            fetch(downloadUrl, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${apiKey}`,
            },
            })
            .then((response) => {
                response.blob().then((blob) => {
                console.log(window.URL.createObjectURL(blob));
                const url = window.URL.createObjectURL(blob);
    
                const link = document.createElement("a");
                link.href = url;
                link.download = `${file}.${format}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                });
                $('#sloading').toggle();
            })
            .catch((error) => {
                console.error("Error fetching file content:", error);
                
            });
        })
        .catch((error) => {
            console.error("Error creating job:", error);
            
        });
    }
    </script>
    <script>
        function toggleDetails(detailsId, button) {
            var details = document.getElementById(detailsId);
            if (details.style.display === "none") {
                details.style.display = "block";
                button.querySelector('.arrow-icon').innerHTML = '&#x25B2;'; // Arrow up
            } else {
                details.style.display = "none";
                button.querySelector('.arrow-icon').innerHTML = '&#x25BC;'; // Arrow down
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            $('#sloading').toggle();
            var articleID = <?php echo $article_data[0]->article_id; ?>;

            async function fetchData() {
                try {
                    const response = await fetch('https://web-production-cecc.up.railway.app//api/check/duplication/v2', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: articleID
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('API Response:', data);
                    $('#sloading').toggle();

                    if (data.flagged) {
                        const similarArticlesCount = data.similar_articles.length;
                        const badgeElement = document.createElement('span');
                        badgeElement.classList.add('badge', 'badge-center', 'rounded-pill', 'bg-danger');
                        badgeElement.textContent = similarArticlesCount;

                        const badgeContainer = document.getElementById('badgeContainer');
                        if (badgeContainer) {
                            badgeContainer.appendChild(badgeElement);
                        }

                        const duplicateButton = document.querySelector('.duplicateButton');
                        if (duplicateButton) {
                            duplicateButton.classList.remove('d-none');
                        }
                    } 

                    if (data.flagged) {
                        $('#DuplicateModal').modal('show');

                        const articleSelectedListContainer = document.getElementById('Selectedarticle');
                        const articleSelectedElement = document.createElement('div');

                        const contributors = data.selected_article.contributors;
                        const trimmedContributors = contributors.split('->')[0];

                        articleSelectedElement.innerHTML = `
                            <h5>Selected Article</h5>
                            <p><b>Article ID: </b>${data.selected_article.article_id}</p>
                            <p><b>Contributors: </b>${trimmedContributors}</p>
                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header" id="headingSelected">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionSelected" aria-expanded="true" aria-controls="accordionSelected">${data.selected_article.title}</button>
                                </h2>
                                <div id="accordionSelected" class="accordion-collapse collapse" data-bs-parent="#Selectedarticle">
                                    <div class="accordion-body text-start">${data.selected_article.abstract}</div>
                                </div>
                            </div>`;

                        articleSelectedListContainer.appendChild(articleSelectedElement);
                        const articleListContainer = document.getElementById('SimilararticleLits');
                        data.similar_articles.forEach((article, index) => {
                            const articleElement = document.createElement('div');

                            let trimmedContributors = '';

                            if (article.contributors) {
                                trimmedContributors = article.contributors.split('->')[0];
                            }
                            articleElement.innerHTML = `
                                <h5>Similar Article</h5>
                                <div class="row">
                                    <div class="col-md-6 text-start">
                                        <p><b>Article ID: </b>${article.article_id}</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="javascript:void(0);" onclick="viewArticle(${article.article_id})" class="btn btn-sm btn-dark">View More</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 text-start">
                                        <p><b>Contributors: </b>${trimmedContributors}</p>
                                    </div>
                                </div>
                                <div class="card accordion-item mb-4">
                                    <h2 class="accordion-header" id="headingSimilar${index}">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionSimilar${index}" aria-expanded="true" aria-controls="accordionSimilar${index}">${article.title}</button>
                                    </h2>
                                    <div id="accordionSimilar${index}" class="accordion-collapse collapse" data-bs-parent="#SimilararticleLits">
                                        <div class="accordion-body text-start">${article.abstract}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><b>Title: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.title * 100).toFixed(2)}%</small></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><b>Overview: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.overview * 100).toFixed(2)}%</small></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><b>Overall: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.total * 100).toFixed(2)}%</small></p>
                                    </div>
                                </div>
                            `;
                            articleListContainer.appendChild(articleElement);
                        });
                    }
                } catch (error) {
                console.error('Error fetching data:', error);
                const apiDataElement = document.getElementById('apiData');
                    if (apiDataElement) {
                        apiDataElement.innerText = 'Error fetching data';
                    }
                }
            }

            fetchData();

            const urlHash = window.location.hash;
            if (urlHash) {
                const matchingTab = document.querySelector(`.nav-link[data-bs-target="${urlHash}"]`);
                if (matchingTab) {
                    const tab = new bootstrap.Tab(matchingTab);
                    tab.show();
                }
            }

            const tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(tab => {
                tab.addEventListener('shown.bs.tab', function () {
                    const target = this.getAttribute('data-bs-target');
                    history.pushState({}, '', window.location.pathname + window.location.search + target);
                });
            });
        });

        async function fetchDuplicate() {
            $('#sloading').toggle();
            const articleSelectedListContainer = document.getElementById('Selectedarticle2');
            const articleListContainer = document.getElementById('SimilararticleLits2');

            // Clear existing content
            articleSelectedListContainer.innerHTML = '';
            articleListContainer.innerHTML = '';

            var articleID = <?php echo $article_data[0]->article_id; ?>;
            try {
                const response = await fetch('https://web-production-cecc.up.railway.app//api/check/duplication/v2', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: articleID
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                console.log('API Response:', data);
                $('#sloading').toggle();

                if (data.flagged) {
                    // Show the modal
                    $('#DuplicateModal2').modal('show');

                    // Display selected article
                    const articleSelectedListContainer = document.getElementById('Selectedarticle2');
                    const articleSelectedElement = document.createElement('div');
                    
                    const contributors = data.selected_article.contributors;
                    const trimmedContributors = contributors.split('->')[0];

                        articleSelectedElement.innerHTML = `
                            <h5>Selected Article</h5>
                            <p><b>Article ID: </b>${data.selected_article.article_id}</p>
                            <p><b>Contributors: </b>${trimmedContributors}</p>
                        <div class="card accordion-item mb-4">
                            <h2 class="accordion-header" id="headingSelected">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionSelected" aria-expanded="true" aria-controls="accordionSelected">${data.selected_article.title}</button>
                            </h2>
                            <div id="accordionSelected" class="accordion-collapse collapse" data-bs-parent="#Selectedarticle2">
                                <div class="accordion-body text-start">${data.selected_article.abstract}</div>
                            </div>
                        </div>`;

                    articleSelectedListContainer.appendChild(articleSelectedElement);

                    // Display similar articles
                    const articleListContainer = document.getElementById('SimilararticleLits2');
                    data.similar_articles.forEach((article, index) => {
                        const articleElement = document.createElement('div');
                        let trimmedContributors = '';

                        if (article.contributors) {
                            trimmedContributors = article.contributors.split('->')[0];
                        }
                        articleElement.innerHTML = `
                            <h5>Similar Article</h5>
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <p><b>Article ID: </b>${article.article_id}</p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <a href="javascript:void(0);" onclick="viewArticle(${article.article_id})" class="btn btn-sm btn-dark">View More</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-start">
                                    <p><b>Contributors: </b>${trimmedContributors}</p>
                                </div>
                            </div>
                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header" id="headingSimilar${index}">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionSimilar${index}" aria-expanded="true" aria-controls="accordionSimilar${index}">${article.title}</button>
                                </h2>
                                <div id="accordionSimilar${index}" class="accordion-collapse collapse" data-bs-parent="#SimilararticleLits2">
                                    <div class="accordion-body text-start">${article.abstract}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p><b>Title: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.title * 100).toFixed(2)}%</small></p>
                                </div>
                                <div class="col-md-4">
                                    <p><b>Overview: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.overview * 100).toFixed(2)}%</small></p>
                                </div>
                                <div class="col-md-4">
                                    <p><b>Overall: </b><small class="text-danger fw-semibold"><i class="bx bx-chevron-up"></i>${(article.score.total * 100).toFixed(2)}%</small></p>
                                </div>
                            </div>
                        `;
                        articleListContainer.appendChild(articleElement);
                    });
                }
            } catch (error) {
            console.error('Error fetching data:', error);
            const apiDataElement = document.getElementById('apiData');
                if (apiDataElement) {
                    apiDataElement.innerText = 'Error fetching data';
                }
            }
        }

        function viewArticle(articleId) {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/workflow.php?aid=" + articleId;
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function selectReviewer(authorId, firstName, lastName, email) {
            $('#AssignReviewer').modal('show');
            $('#AuthorName').html(lastName + ', ' + firstName);
            $('#reviewerid').val(authorId);
            $('#revieweremail').val(email);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
            ];

            var quill = new Quill('#quill-abstract', {
                theme: 'snow'
            });

            var quillTwo = new Quill('#quill-reference', {
                theme: 'snow'
            });

            var quillThree = new Quill('#quill-emailcontent', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
            });
                
            var abstractContent = <?php echo json_encode($article_data[0]->abstract); ?>;
            var referenceContent = '<?php echo addslashes($article_data[0]->references); ?>';
            var emailContent = <?php echo json_encode($reviewer_email[0]->content); ?>;
            var title = <?php echo json_encode($article_data[0]->title); ?>;
            var abstract = <?php echo json_encode($article_data[0]->abstract); ?>;
            var articleid = <?php echo json_encode($article_data[0]->article_id); ?>;
            var url = "https://qcuj.online/PHP/index.php?urli=https://qcuj.online/PHP/review-process.php?id=" + articleid;

            quillThree.clipboard.dangerouslyPasteHTML(abstractContent + '\n\n' + referenceContent);
            quill.clipboard.dangerouslyPasteHTML(abstractContent);
            var contentWithNewlines = referenceContent.replace(/\\n/g, '\n');
            var contentWithLineBreaks = contentWithNewlines.replace(/\n/g, '<br>');
            quillTwo.clipboard.dangerouslyPasteHTML(contentWithLineBreaks);

            var currentDate = new Date();
            currentDate.setDate(currentDate.getDate() + 2);
            var oneWeekAdvance = new Date(currentDate);
            oneWeekAdvance.setDate(oneWeekAdvance.getDate() + 7);
            var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var formattedDate = months[oneWeekAdvance.getMonth()] + " " + oneWeekAdvance.getDate() + ", " + oneWeekAdvance.getFullYear();

            var decisionText = <?php echo json_encode($reviewer_email[0]->decision); ?>;
            var decisionText1 = "Title";
            var decisionText2 = "Abstract";
            var decisionText3 = "The review itself is due on" + formattedDate;
            var urlText = "Submission URL:";

            if (emailContent.trim() !== '') {
                var delta = JSON.parse(emailContent);

                var reviewDelta = { insert: ' "' + title + '.' + '" ' };
                var titleDelta = { insert: '"' + title + '"\n' };
                var abstractDelta = { insert: '' + abstract + '\n' };  
                var dueDelta = { insert: ' ' + formattedDate + '\n' };  
                var urlDelta = { insert: ' ' + url + '\n' }; 

                var decisionIndex = delta.ops.findIndex(op => op.insert.includes(decisionText));
                var decisionIndex1 = delta.ops.findIndex(op => op.insert.includes(decisionText1));
                var decisionIndex2 = delta.ops.findIndex(op => op.insert.includes(decisionText2));
                var decisionIndex3 = delta.ops.findIndex(op => op.insert.includes(decisionText3));
                var urlIndex = delta.ops.findIndex(op => op.insert.includes(urlText));

                delta.ops.splice(decisionIndex + 1, 0, reviewDelta);
                delta.ops.splice(decisionIndex1 + 2, 0, titleDelta);
                delta.ops.splice(decisionIndex2 + 3, 0, abstractDelta);
                delta.ops.splice(decisionIndex3 + 8, 0, dueDelta);
                delta.ops.splice(urlIndex + 3, 0, urlDelta);

                quillThree.setContents(delta);
            } else {
                quillThree.setContents([{ insert: 'Test content\n\n' }]);
            }

            var form = document.getElementById('quillForm');
            var quillContentInputOne = document.getElementById('quillContentOne');

            var submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.addEventListener('click', function () {
                    sendEmail(quillThree);
                });
            }
        });

        function sendEmail(quillInstance) {
            $('#sloading').toggle();
            var subject = $('#emailsubject').val();
            var articleid = $('#articleid').val();
            var round = $('#round').val();
            var revieweremail = $('#revieweremail').val();
            var reviewerid = $('#reviewerid').val();

            var title = <?php echo json_encode($article_data[0]->title); ?>;

            var deltaContent = quillInstance.getContents();
            var jsonContent = JSON.stringify(deltaContent);

            $.ajax({
                type: 'POST',
                url: '../php/function/email_function.php',
                data: {
                    subject: subject,
                    quillContentOne: jsonContent,
                    articleid: articleid,
                    round: round,
                    reviewerid: reviewerid,
                    revieweremail: revieweremail,
                    title: title,
                    action: 'assign_reviewer'
                },
                success: function (response) {
                    $('#sloading').toggle();
                    console.log(response);
                    alert('Email sent to reviewer successfully.');
                    location.reload();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }

        function sendForReview() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=1";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function sendForDecline() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=2";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }       
        
        function sendForCopyediting() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=3";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function sendForRevision() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=4";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function sendForProduction() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=5";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function sendForArchive() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=7";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function sendForPublished() {
            $('#sloading').show();

            setTimeout(function () {
                window.location.href = "../php/emailcontent.php?aid=<?php echo $article_data[0]->article_id; ?>&emc=8";
            }, 2000);

            window.onload = function () {
                $('#sloading').hide();
            };
        }

        function viewReviewerAnswer(assignedId, accessible, ReviewerId, ArticleId, AuthorId, ReviewName, Round) {
            var authorId = AuthorId;
            var articleId = ArticleId
            var assignedId = assignedId;
            var accessible = accessible;
            $.ajax({
                type: 'POST',
                url: '../php/function/wf_modal_function.php',
                data: { action: 'fetchanswer', reviewer_id: ReviewerId, article_id: ArticleId, round: Round },
                dataType: 'json',
                success: function (response) {
                    document.getElementById('roundInfo').innerText = 'Reviewer '+ ReviewName + ' ' + Round + ' Answer';
                    document.getElementById('reviewer_assigned_id').value = assignedId;
                    document.getElementById('author_id').value = authorId;
                    document.getElementById('article_id').value = articleId;
                    document.getElementById('accessible').value = accessible;
                    if (accessible == 1) {
                        document.getElementById('acceptButton').style.display = 'none';
                    } else {
                        document.getElementById('acceptButton').style.display = 'block';
                    }
                    if (response.status === true && response.data.length > 0) {
                        const answerData = response.data;
                        $('#DataTableAnswer tbody').empty();

                        for (const answer of answerData) {
                            let commentSection = ''; 
                            if (answer.comments && answer.comments.trim() !== '') { 
                                commentSection = '<div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Comment: ' + answer.comments + '</div>'; // Append the comment section
                            }
                            
                            $('#DataTableAnswer tbody').append('<tr><td width="50%"><div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">' + 
                            answer.reviewer_questionnaire + '</div><div style="overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">Answer: ' + 
                            answer.answer + '</div>' + commentSection + '</td></tr>');
                        }   
                        $('#addReviewerAnswerModal').modal('show');
                    } else {
                        $('#DataTableAnswer tbody').empty();
                        $('#DataTableAnswer tbody').append('<tr><td colspan="2"><center>No answer found.</center></td></tr>');
                        
                        $('#addReviewerAnswerModal').modal('show');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error:', textStatus, errorThrown);
                    console.log('Error fetching reviewer answer data');
                }
            });
        }

        function viewDiscussion(DiscussionId, Subject, Discussion) {
            $('#messageContainer').hide();
            $('#addMessageButtonx').show();
            $.ajax({
                type: 'POST',
                url: '../php/function/wf_modal_function.php',
                data: { action: 'fetchsubmissiondiscussion', discussion_id: DiscussionId},
                dataType: 'json',
                success: function (response) {
                    document.getElementById('discussionType').innerText = 'View ' + Discussion + ' Discussion';
                    document.getElementById('discussionSubject').innerText = Subject;

                    if (response.status === true && response.data.length > 0) {
                        const submissionDiscussion = response.data;
                        $('#DataTableSubmissionDiscussion tbody').empty();
                        $('#DataTableSubmissionDiscussionFile tbody').empty();

                        var counter = 1;
                        for (const submissiondiscussion of submissionDiscussion) {
                            $('#discussion_id').val(submissiondiscussion.discussion_id);
                            
                            $('#DataTableSubmissionDiscussion tbody').append('<tr><th colspan="2" style="width: 100%;"></th></tr>');
                            $('#DataTableSubmissionDiscussion tbody').append('<tr><th style="width: 80%;">MESSAGE ' + counter + '</th><th style="width: 20%;">FROM</th></tr>');
                            $('#DataTableSubmissionDiscussion tbody').append('<tr><td style="width: 80%; white-space: pre-wrap; text-align: justify;"><div style="max-height: 200px; overflow-y: auto;">' + submissiondiscussion.message + '</div></td><td style="width: 20%;">' + submissiondiscussion.fromuser + '</td></tr>');

                            if (submissiondiscussion.file_type !== null && submissiondiscussion.file_type !== '') {
                                $('#DataTableSubmissionDiscussion tbody').append('<tr><th style="width: 80%;">FILE</th><th style="width: 20%;">FILE TYPE</th></tr>');
                                $('#DataTableSubmissionDiscussion tbody').append('<tr><td style="width: 80%;"><a href="/Files/discussion-file/' + submissiondiscussion.file + '" download>' + submissiondiscussion.file + '</a></td><td style="width: 20%;">' + submissiondiscussion.file_type + '</td></tr>');
                            }
                            counter++;
                        }

                        $('#ViewDiscussionModal').modal('show');
                    } else {
                        console.log('No answers found.');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error:', textStatus, errorThrown);
                    console.log('Error fetching reviewer answer data');
                }
            });
        }

        function showDiscussionModal(discussionType) {
            document.getElementById('discussionTypeInput').value = discussionType;
            document.getElementById('discussionType').innerText = 'Add ' + discussionType + ' Discussion';

            $('#addDiscussionModal').modal('show');
        }
        
        function openPagePreviewCentered(url, width = 1200, height = 800) {
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

            var screenWidth = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var screenHeight = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = (screenWidth / 2) - (width / 2) + dualScreenLeft;
            var top = (screenHeight / 2) - (height / 2) + dualScreenTop;

            var previewWindow = window.open(url, '_blank', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);

            // Create a transparent overlay div
            var overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100%';
            overlay.style.height = '100%';
            overlay.style.backgroundColor = 'transparent';
            overlay.style.zIndex = '9999';
            overlay.style.pointerEvents = 'none'; // Disable pointer events to make content not clickable
            document.body.appendChild(overlay);

            // Close the overlay when the preview window is closed
            previewWindow.addEventListener('beforeunload', function() {
                document.body.removeChild(overlay);
            });
        }

        function openPageCentered(url, width = 1200, height = 800) {
            var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
            var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

            var screenWidth = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
            var screenHeight = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

            var left = (screenWidth / 2) - (width / 2) + dualScreenLeft;
            var top = (screenHeight / 2) - (height / 2) + dualScreenTop;

            window.open(url, '_blank', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);
        }

        function checkFinalFileSubmission() {
            <?php
            if (is_array($article_finalfile_checker) && (empty($article_finalfile_checker) || $article_finalfile_checker[0] === 0)) {
                echo 'alert("Please submit or select final file into production before proceeding.");';
                echo 'return;';
            }
            ?>
            
            $('#addIssueModal').modal('show');
        }

    </script>
    <script>
    const uploadButton = $('#addReviewer');
    const articleID = <?php echo $article_data[0]->article_id; ?>;
    let isFirstModalOpen = true; 

    const openAddReviewerModal = () => {
        var backdropOption = isFirstModalOpen ? true : false; 
        var myModal = new bootstrap.Modal($('#addReviewerModal'), {
            backdrop: backdropOption
        });
        myModal.show();
        isFirstModalOpen = false;
    };

    uploadButton.click(openAddReviewerModal);

    async function fetchAndRenderData(sortValue) {
        $('#sloading').toggle();
        try {
            const response = await fetch('https://web-production-cecc.up.railway.app//api/check/reviewers', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: articleID
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            console.log('API Response:', data);

            if (!Array.isArray(data.sorted_reviewers)) {
                console.error('Sorted reviewers is not an array:', data.sorted_reviewers);
                return;
            }

            const articleReviewerIds = <?php echo json_encode(array_column($article_reviewer_check, 'author_id')); ?>;

            const tbodySuggested = $('#reviewersTableBody');
            const tbodyOther = $('#reviewersTableBody1');
            tbodySuggested.empty();
            tbodyOther.empty();

            data.sorted_reviewers.forEach(item => {
                if (!articleReviewerIds.includes(item.author_id)) {
                    return;
                }

                const row = $('<tr>');
                const affiliation = item.afiliations && item.afiliations.length > 30 ? item.afiliations.substring(0, 30) + '...' : item.afiliations;
                const expertise = item.field_of_expertise && item.field_of_expertise.length > 30 ? item.field_of_expertise.substring(0, 30) + '...' : item.field_of_expertise;

                row.html(`
                    <td width="5%">${item.author_id}</td>
                    <td width="80%">
                        <a href="javascript:void(0);" onclick="openPageCentered('../../PHP/userdashboard.php?orcid=${item.orc_id}')">${item.last_name}, ${item.first_name}</a><br>
                        Afiliations: ${affiliation}<br>
                        Expertise: ${expertise}
                        <div id="details_${item.author_id}" style="display: none;">
                            <br>
                            Reviews completed: ${item.total_success}<br>
                            Reviews ongoing: ${item.ongoing}<br>
                            Reviews not completed: ${item.decline}<br>
                        </div>
                    </td>
                    <td width="15%">
                        <button type="button" class="btn btn-outline-dark" style="width: 160px;" onclick="selectReviewer(${item.author_id}, '${item.first_name}', '${item.last_name}', '${item.email_verified}')" data-bs-toggle="modal">Select Reviewer</button>
                        <button type="button" class="btn btn-outline-dark" style="width: 20px;" onclick="toggleDetails('details_${item.author_id}', this)">
                            <span class="arrow-icon">&#x25BC;</span>
                        </button>
                    </td>
                `);

                if (sortValue === 'score') {
                    if (item.score === 0) {
                        tbodyOther.append(row); 
                    } else {
                        tbodySuggested.append(row);
                    }
                } else if (sortValue === 'success') {
                    if (item.total_success === 0) {
                        tbodyOther.append(row); 
                    } else {
                        tbodySuggested.append(row);
                    }
                }
                
            });

            $('#sloading').toggle();
            
        if (!$.fn.DataTable.isDataTable('#DataTableSuggested')) {
            $('#DataTableSuggested').DataTable({
                "paging": false,
                "ordering": false,
                "searching": true,
                "info": false
            });
        }

        if (!$.fn.DataTable.isDataTable('#DataTableOther')) {
            $('#DataTableOther').DataTable({
                "paging": false,
                "ordering": false,
                "searching": true,
                "info": false
            });
        }

        } catch (error) {
            console.error('Error:', error);
        }
    }

    $('#sortDropdown').change(function() {
        const sortValue = $(this).val();
        fetchAndRenderData(sortValue);
    });

    fetchAndRenderData('score');
    </script>
</body>
</html>

