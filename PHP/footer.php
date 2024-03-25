<!DOCTYPE html>
<html lang="en">

<head>
    <?php include('./meta.php'); ?>
    <title>Footer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/footer.css">
</head>

<body>
    <footer class="site-footer mt-5">
      <div class="container">
        <div class="row">
          <div class="col-sm-12 col-md-5">
            <h6>About</h6>
            <p class="text-justify">QCUJ web application is an open access journal platform for Quezon City University, facilitating the submission and publication process. Leveraging machine learning, it offers personalized recommendations and streamlines procedures for enhanced user experience </p>
          </div>

          <div class="col-xs-6 col-md-2">
            <h6>Categories</h6>
            <ul class="footer-links">
              <li><a href="issues.php?journal_id=1">The Gavel</a></li>
              <li><a href="issues.php?journal_id=2">The Lamp</a></li>
              <li><a href="issues.php?journal_id=3">The Star</a></li>
              <li><a href="publication.php">All Publications</a></li>
            </ul>
          </div>

          <div class="col-xs-6 col-md-2">
            <h6>Quick Links</h6>
            <ul class="footer-links">
              <li><a href="/">Home</a></li>
              <li><a href="general-info.php">About QCUJ</a></li>
              <li><a href="announcement.php">Announcements</a></li>
              <li><a href="donation.php">Donation</a></li>
              <li><a href="browse-articles.php">Browse articles</a></li>
            </ul>
          </div>
          <div class="col-xs-6 col-md-2">
            <h6>Guidelines</h6>
            <ul class="footer-links">
              <li><a href="faqs.php">Frequently Asked Questions</a></li>
              <li><a href="guidelines.php#templates-for-author">Templates</a></li>
              <li><a href="guidelines.php#author-guidelines">Author Guidelines</a></li>
              <li><a href="guidelines.php#become-a-reviewer">Become a Reviewer</a></li>
              <li><a href="guidelines.php#publication-policy">Publication Policy</a></li>
            </ul>
          </div>
        </div>
        <hr>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-sm-6 col-xs-12 d-flex gap-2">
            <img src="../images/qcuj.png" style="width:3em; height:3em" alt="" class="d-none d-sm-flex"/>
            <img style="width:2.7em; height:2.7em"  src="../images/qcu-logo.webp" alt="" class="d-none d-sm-flex">
            <p class="copyright-text">Content on this site is licensed under a Creative Commons &copy; <?php echo date("Y"); ?> 
            All Rights Reserved by <a href="#">QCUJ</a>.
            </p>
          </div>

          <div class="col-md-4 col-sm-6 col-xs-12">
            <ul class="social-icons">
              <li><a class="facebook" href="https://www.facebook.com/QCUREPL" target="blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 50 50">
                	<path fill="currentColor" d="M26 20v-3c0-1.3.3-2 2.4-2H31v-5h-4c-5 0-7 3.3-7 7v3h-4v5h4v15h6V25h4.4l.6-5z" />
                </svg></a>
              </li>
            
            </ul>
          </div>
        </div>
      </div>
    </footer>
</body>

</html>