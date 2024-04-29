<?php
include 'function/redirect.php';
include 'function/report_function.php';

$notificationlist = get_notification_list();
?>  
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>
<style>
    #nopadding {
        padding: 0 !important;
    }
    #totalPublished {
        color: #566A7F !important;
    }
    tr.read {
        background-color: #d9dee3 !important;
    }

    tr.unread {
        background-color: white !important;
    }
</style>
<body>
    <?php include 'template/header.php'; ?>

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- <h4 class="py-3 mb-4" style="display: flex; justify-content: space-between; align-items: baseline;"> Notification</h4> -->

        <div class="card">
            <div class="table-responsive text-nowrap" id="nopadding">
                <table class="table" id="DataTable">
                    <thead>
                        <tr>
                        <th>Notification</th>
                        <th style="display:none;">Notification</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach (array_reverse($notificationlist) as $notification) {
                            $readClass = ($notification->read == 1) ? 'read' : 'unread';
                            $currentMonth = date('n');
                            $currentYear = date('Y');
                            $hrefworkflow = "workflow.php?aid=" . $notification->article_id;
                            $hrefdonation = "donationreportmtd.php?m=" . $currentMonth . "&y=" . $currentYear;
                            $messageHref = "message.php";
                        ?>

                        <tr class="<?php echo $readClass; ?>" data-toggle="modal" data-target="" data-id="<?php echo $notification->id; ?>">
                            <td style="display:none;"><?php echo $notification->id; ?><br> </td>
                            <td width="100%">
                                <?php echo $notification->title; ?><br>
                                <?php
                                $description = $notification->description;
                                $limitedTitle = strlen($description) > 170 ? substr($description, 0, 170) . '...' : $description;
                                echo $limitedTitle;
                            ?><br>
                                <span class="time-ago-<?php echo $notification->id; ?>"></span>
                                <script>
                                    var currentTime = new Date().getTime();
                                    var notificationTime = new Date("<?php echo $notification->created; ?>").getTime();
                                    var timeDifference = currentTime - notificationTime;
                                    var timeAgo;

                                    if (timeDifference < 60000) {
                                        const seconds = Math.floor(timeDifference / 1000);
                                        timeAgo = seconds + (seconds === 1 ? ' second ago' : ' seconds ago');
                                    } else if (timeDifference < 3600000) {
                                        const minutes = Math.floor(timeDifference / 60000);
                                        timeAgo = minutes + (minutes === 1 ? ' minute ago' : ' minutes ago');
                                    } else if (timeDifference < 86400000) {
                                        const hours = Math.floor(timeDifference / 3600000);
                                        timeAgo = hours + (hours === 1 ? ' hour ago' : ' hours ago');
                                    } else {
                                        const days = Math.floor(timeDifference / 86400000);
                                        timeAgo = days + (days === 1 ? ' day ago' : ' days ago');
                                    }

                                    // Assuming you have an element with class 'time-ago-<?php echo $notification->id; ?>'
                                    document.querySelector('.time-ago-<?php echo $notification->id; ?>').textContent = timeAgo;
                                </script>
                            </td>
                        </tr>

                        <script>
                            document.querySelector('.time-ago-<?php echo $notification->id; ?>').closest('tr').addEventListener('click', function() {
                                var id = <?php echo $notification->id; ?>;
                                var title = '<?php echo $notification->title; ?>';
                                var contact_us = '<?php echo $notification->contact_us; ?>';
                                var href;
                                if (title === 'Send Donation') {
                                    href = '<?php echo $hrefdonation; ?>';
                                } else {
                                    href = (contact_us === '1') ? '<?php echo $messageHref; ?>' : '<?php echo $hrefworkflow; ?>';
                                }
                                
                                $.ajax({
                                    type: 'POST',
                                    url: 'function/update_notification.php',
                                    data: { id: id },
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.status) {
                                            console.log(response.message);

                                        } else {
                                            console.error(response.message);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('AJAX error: ' + status + ' - ' + error);
                                    }
                                });
                            });
                        </script>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include 'template/footer.php'; ?>
    </div>

    <!-- Include the DataTables CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

    <!-- DataTables initialization script -->
    <script>
        $(document).ready(function () {
            $('#DataTable').DataTable({
                "paging": true,
                // "ordering": false,
                "searching": false,
                "order": [[0, 'desc']]  
            });
        });
    </script>
</body>
</html>
