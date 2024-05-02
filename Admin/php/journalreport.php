<?php
include 'function/redirect.php';
include 'function/report_function.php';
include 'function/submission_functions.php';

if(isset($_SESSION['LOGGED_IN']) && $_SESSION['LOGGED_IN'] === true){
    $journal_id = isset($_SESSION['journal_id']) ? ($_SESSION['journal_id']) : '';
}
$current_year = date('Y');

$contributor = get_contributor_list();
$all_articles = get_allarticle_list($journal_id);
$journal_list = get_journal_list($journal_id);
$journaldata = get_journal_data($journal_id);
$journaldata1 = get_journal_data1($journal_id, $current_year);

$combinedData = [
    ['name' => 'Published', 'data' => []],
    ['name' => 'Ongoing', 'data' => []],
    ['name' => 'Rejected', 'data' => []]
];

foreach ($journaldata['journaldata'] as $journal) {
    $combinedData[0]['data'][] = $journal->published_count;
    $combinedData[1]['data'][] = $journal->ongoing_count;
    $combinedData[2]['data'][] = $journal->reject_count;
}

$combinedDataJson = json_encode($combinedData);

$combinedData1 = [
    ['name' => 'Published', 'data' => []],
    ['name' => 'Ongoing', 'data' => []],
    ['name' => 'Rejected', 'data' => []]
];

foreach ($journaldata1['journaldata1'] as $journal) {
    $combinedData1[0]['data'][] = $journal->published_count;
    $combinedData1[1]['data'][] = $journal->ongoing_count;
    $combinedData1[2]['data'][] = $journal->reject_count; 
}

$combinedDataJson1 = json_encode($combinedData1);

$categories = [];

foreach ($journal_list as $journal) {
    if (!in_array($journal->journal, $categories)) {
        $categories[] = $journal->journal;
    }
}
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
    .nav-link.active {
        color: white !important;
        background-color: #007bff;
    }

    .nav-link:not(.active) {
        color: gray;
    }
</style>
<body>
    <?php include 'template/header.php'; ?>

    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4" style="display: flex; justify-content: space-between; align-items: baseline;">
        Others / <a href="../php/reportlist.php"> <span class="text-muted fw-light">&nbsp;Report /</span></a>&nbsp; Journal Report
            <span id="totalPublished" class="text-muted" style="margin-left: auto">
                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                    Export &nbsp<i class="bx bx-download"></i>
                </button>
            </span>
        </h4>

        <div class="row mb-2">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h5 class="card-header m-0 me-2 pb-3">Year <span id="currentYear"></span></h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a href="#" class="download-chart-btn dropdown-item" data-chart="totalRevenueChart">Download</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="width:100%;" id="totalRevenueChartDiv">
                            <div id="totalRevenueChart">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                            <h5 class="card-header m-0 me-2 pb-3">Overall</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a href="#" class="download-chart-btn dropdown-item" data-chart="totalRevenueChart1">Download</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="width:100%;" id="totalRevenueChart1Div">
                            <div id="totalRevenueChart1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Journal tabs -->
        <ul class="nav nav-tabs mb-3" id="journalTabs">
            <li class="nav-item">
                <a class="nav-link active" id="tabAll" data-status="">All</a>
            </li>

            <?php foreach ($journal_list as $journal): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab<?php echo $journal->journal_id; ?>" data-status="<?php echo $journal->journal; ?>"><?php echo $journal->journal; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

          <!-- Status tabs -->
          <ul class="nav nav-tabs mb-3" id="statusTabs">
            <li class="nav-item">
                <a class="nav-link active" id="tabAll" data-status="">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabReject" data-status="Reject">Reject</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabPending" data-status="Pending">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabReview" data-status="Review">Review</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabCopyediting" data-status="Copyediting">Copyediting</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabProduction" data-status="Production">Production</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabPublished" data-status="Published">Published</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabArchived" data-status="Archived">Archived</a>
            </li>
        </ul>

        <!-- Table with Status filter -->
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-striped" id="DataTable">
                    <thead>
                        <tr>
                            <th>Article ID</th>
                            <th>Journal</th>
                            <th>Article</th>
                            <th>Status</th>
                            <th>Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_articles as $all_articlesval): ?>
                            <tr>
                                <td width="5%"><?php echo $all_articlesval->article_id; ?></td>
                            <?php
                                $matchingJournal = null;
                                foreach ($journal_list as $journal) {
                                    if ($journal->journal_id == $all_articlesval->journal_id) {
                                        $matchingJournal = $journal;
                                        break;
                                    }
                                }
                                ?>
                                <td><?php echo ($matchingJournal !== null) ? $matchingJournal->journal : 'Unknown'; ?></td>
                                <td width="70%">
                                    <?php echo $all_articlesval->title; ?>
                                </td>
                                <?php
                                    $statusNumber = $all_articlesval->status;

                                    $statusInfo = [
                                        0 => ['label' => 'Archived', 'class' => 'label-danger'],
                                        1 => ['label' => 'Published', 'class' => 'label-success'],
                                        11 => ['label' => 'Scheduled', 'class' => 'label-info'],
                                        2 => ['label' => 'Production', 'class' => 'label-dark'],
                                        3 => ['label' => 'Copyediting', 'class' => 'label-primary'],
                                        4 => ['label' => 'Review', 'class' => 'label-warning'],
                                        5 => ['label' => 'Pending', 'class' => 'label-secondary'],
                                        6 => ['label' => 'Reject', 'class' => 'label-danger'],
                                    ];

                                    if (isset($statusInfo[$statusNumber])) {
                                        $statusLabel = $statusInfo[$statusNumber]['label'];
                                        $statusClass = $statusInfo[$statusNumber]['class'];
                                    } else {
                                        $statusLabel = 'Unknown';
                                        $statusClass = 'label-secondary';
                                    }
                                    ?>

                                <td width="10%">
                                    <span class="badge bg-<?php echo $statusClass; ?> me-1">
                                        <?php echo $statusLabel; ?>
                                    </span>
                                </td>
                                <td width="5%">
                                <?php
                                    $date_added = $all_articlesval->date_added;
                                    $formatted_date = date("F d, Y", strtotime($date_added));
                                    echo $formatted_date;
                                    ?>
                                </td>
                                <td width="5%">                         
                                    <a href="javascript:void(0);" onclick="viewWorkflow(<?php echo $all_articlesval->article_id; ?>, '<?php echo $all_articlesval->workflow; ?>')" class="btn btn-outline-dark">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>

    <script>
        function viewWorkflow(articleId, workflow) {
            $('#sloading').show(); 

            setTimeout(function () {
                window.location.href = "../php/workflow.php?aid=" + articleId + workflow;
            }, 2000);
            
            window.onload = function () {
                $('#sloading').hide();
            };
        }
        
        function generatePDF(chartContent, fileName) {
            const opt = {
                margin: 1,
                filename: fileName + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf()
                .set(opt)
                .from(chartContent)
                .save()
                .then(() => {
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const downloadBtns = document.querySelectorAll('.download-chart-btn');
            downloadBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const chartId = btn.getAttribute('data-chart');
                    const chartContent = document.getElementById(chartId).innerHTML;
                    const cardHeader = btn.closest('.card').querySelector('.card-header');
                    if (cardHeader) {
                        const headerText = cardHeader.textContent.trim();
                        generatePDF(chartContent, headerText);
                    } else {
                        console.error('Card header not found.');
                    }
                });
            });
        });
    </script>

    <!-- DataTables initialization script -->
    <script>
        const currentYear = new Date().getFullYear();

        document.getElementById("currentYear").innerText = currentYear;

        $(document).ready(function () {
            var dataTable = $('#DataTable').DataTable({
                "paging": true,
                "ordering": true,
                "searching": true,
                "order": [[0, 'desc']]  
            });

            $('#journalTabs a').on('click', function (e) {
                e.preventDefault();

                $('#journalTabs a').removeClass('active');

                $(this).addClass('active');

                var statusValue = $(this).data('status');
                dataTable.column(1).search(statusValue).draw();
            });

            $('#statusTabs a').on('click', function (e) {
                e.preventDefault();

                $('#statusTabs a').removeClass('active');

                $(this).addClass('active');

                var statusValue = $(this).data('status');
                dataTable.column(3).search(statusValue).draw();
            });
        });

        function doset() {
            $('#sloading').show();
            var url = "totalreport.php";

            window.location.href = url;
        }

        function exportToExcel() {
            var activeStatus = $('#journalTabs a.active').data('status'); 

            var dataTable = $('#DataTable').DataTable();
            var data = dataTable.rows().data().toArray();

            if (activeStatus.toLowerCase() === "") {
                var wsData = data.map(row => [row[0], row[1], row[2], getStatusText(row[3]), row[4]]);
            } else {
                var filteredData = data.filter(row => row[1] === activeStatus);
                var wsData = filteredData.map(row => [row[0], row[1], row[2], getStatusText(row[3]), row[4]]);
            }

            var currentDate = new Date().toLocaleString("en-US", {timeZone: "Asia/Manila"});
            var formattedDate = new Date(currentDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

            if (activeStatus == ""){
                var title = "Journal Report " + activeStatus + " - " + formattedDate;
            } else{
                var title = "Journal Report for " + activeStatus + " - " + formattedDate;
            }

            var ws = XLSX.utils.aoa_to_sheet([[title], ["Article ID", "Journal", "Title", "Status", "Added"]].concat(wsData));

            ws['!merges'] = [{ s: { r: 0, c: 0 }, e: { r: 0, c: 4 } }];

            ws['!cols'] = [{ wpx: 15 * 5 }];

            var columnSizes = [{ wch: 15 },{ wch: 25 }, { wch: 150 }, { wch: 25 }, { wch: 25 }]; 
            columnSizes.forEach((size, columnIndex) => {
                ws['!cols'] = ws['!cols'] || [];
                ws['!cols'][columnIndex] = size;
            });

            ws['!protect'] = true;

            var wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            
            var filename = 'JournalReport ' + activeStatus + " - " + formattedDate + '.xlsx';
            XLSX.writeFile(wb, filename);
        }

        function getStatusText(htmlString) {
            var doc = new DOMParser().parseFromString(htmlString, 'text/html');
            var textContent = doc.body.textContent || "";
            return textContent.trim();
        }

        function getStatusLabel(statusValue) {
            var statusInfo = {
                '10': 'AllArticle',
                '0': 'Archive',
                '1': 'Published',
                '2': 'Production',
                '3': 'Copyediting',
                '4': 'Review',
                '5': 'Pending',
                '6': 'Reject'
            };

            return statusInfo[statusValue] || 'Unknown';
        }

        (function() {
            let cardColor, headingColor, axisColor, shadeColor, borderColor;
        
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            axisColor = config.colors.axisColor;
            borderColor = config.colors.borderColor;
            var seriesData = <?php echo $combinedDataJson1; ?>;
            console.log(seriesData);

            const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
                totalRevenueChartOptions = {
                    series: seriesData,
                    chart: {
                        height: 300,
                        stacked: true,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '33%',
                            borderRadius: 12,
                            startingShape: 'rounded',
                            endingShape: 'rounded'
                        }
                    },
                    colors: ['#71dd37', '#ffab00', '#ff3e1d'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 6,
                        lineCap: 'round',
                        colors: [cardColor]
                    },
                    legend: {
                        show: true,
                        horizontalAlign: 'left',
                        position: 'top',
                        markers: {
                            height: 8,
                            width: 8,
                            radius: 12,
                            offsetX: -3
                        },
                        labels: {
                            colors: [axisColor, axisColor, axisColor]
                        },
                        itemMargin: {
                            horizontal: 10
                        }
                    },
                    grid: {
                        borderColor: borderColor,
                        padding: {
                            top: 0,
                            bottom: -8,
                            left: 20,
                            right: 20
                        }
                    },
                    xaxis: {
                        categories: <?php echo json_encode($categories); ?>,
                        labels: {
                            style: {
                                fontSize: '13px',
                                colors: axisColor
                            }
                        },
                        axisTicks: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '13px',
                                colors: axisColor
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 1700,
                        options: {
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '32%'
                                }
                            }
                        }
                    },
                    ],
                    states: {
                        hover: {
                            filter: {
                                type: 'none'
                            }
                        },
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    }
                };
            if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
                const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
                totalRevenueChart.render();
            }
        })();

        (function() {
            let cardColor, headingColor, axisColor, shadeColor, borderColor;
        
            cardColor = config.colors.cardColor;
            headingColor = config.colors.headingColor;
            axisColor = config.colors.axisColor;
            borderColor = config.colors.borderColor;
            var seriesData = <?php echo $combinedDataJson; ?>;
            console.log(seriesData);

            const totalRevenueChartEl = document.querySelector('#totalRevenueChart1'),
                totalRevenueChartOptions = {
                    series: seriesData,
                    chart: {
                        height: 300,
                        stacked: true,
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '33%',
                            borderRadius: 12,
                            startingShape: 'rounded',
                            endingShape: 'rounded'
                        }
                    },
                    colors: ['#71dd37', '#ffab00', '#ff3e1d'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 6,
                        lineCap: 'round',
                        colors: [cardColor]
                    },
                    legend: {
                        show: true,
                        horizontalAlign: 'left',
                        position: 'top',
                        markers: {
                            height: 8,
                            width: 8,
                            radius: 12,
                            offsetX: -3
                        },
                        labels: {
                            colors: [axisColor, axisColor, axisColor]
                        },
                        itemMargin: {
                            horizontal: 10
                        }
                    },
                    grid: {
                        borderColor: borderColor,
                        padding: {
                            top: 0,
                            bottom: -8,
                            left: 20,
                            right: 20
                        }
                    },
                    xaxis: {
                        categories: <?php echo json_encode($categories); ?>,
                        labels: {
                            style: {
                                fontSize: '13px',
                                colors: axisColor
                            }
                        },
                        axisTicks: {
                            show: false
                        },
                        axisBorder: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: '13px',
                                colors: axisColor
                            }
                        }
                    },
                    responsive: [{
                        breakpoint: 1700,
                        options: {
                            plotOptions: {
                                bar: {
                                    borderRadius: 10,
                                    columnWidth: '32%'
                                }
                            }
                        }
                    },
                    ],
                    states: {
                        hover: {
                            filter: {
                                type: 'none'
                            }
                        },
                        active: {
                            filter: {
                                type: 'none'
                            }
                        }
                    }
                };
            if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
                const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
                totalRevenueChart.render();
            }
        })();
    </script>
</body>
</html>
