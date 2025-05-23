<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Title -->
  <title><?= $title ?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= asset_url() ?>images/logo/favicon.png">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/bootstrap.min.css">
  <!-- file upload -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/file-upload.css">
  <!-- file upload -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/plyr.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
  <!-- full calendar -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/full-calendar.css">
  <!-- jquery Ui -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/jquery-ui.css">
  <!-- editor quill Ui -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/editor-quill.css">
  <!-- apex charts Css -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/apexcharts.css">
  <!-- calendar Css -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/calendar.css">
  <!-- jvector map Css -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/jquery-jvectormap-2.0.5.css">
  <!-- Main css -->
  <link rel="stylesheet" href="<?= asset_url() ?>css/main.css">


</head>

<body>

  <!--==================== Preloader Start ====================-->
  <div class="preloader">
    <div class="loader"></div>
  </div>
  <!--==================== Preloader End ====================-->

  <!--==================== Sidebar Overlay End ====================-->
  <div class="side-overlay"></div>

  <?php $this->load->view('common/sidebar'); ?>

  <div class="dashboard-main-wrapper">
    <?php $this->load->view('common/header'); ?>
    <?php echo $content; ?>
    <?php $this->load->view('common/footer'); ?>
  </div>


  <!-- Jquery js -->
  <script src="<?= asset_url() ?>js/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap Bundle Js -->
  <script src="<?= asset_url() ?>js/boostrap.bundle.min.js"></script>
  <!-- Phosphor Js -->
  <script src="<?= asset_url() ?>js/phosphor-icon.js"></script>
  <!-- file upload -->
  <script src="<?= asset_url() ?>js/file-upload.js"></script>
  <!-- file upload -->
  <script src="<?= asset_url() ?>js/plyr.js"></script>
  <!-- dataTables -->
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <!-- full calendar -->
  <script src="<?= asset_url() ?>js/full-calendar.js"></script>
  <!-- jQuery UI -->
  <script src="<?= asset_url() ?>js/jquery-ui.js"></script>
  <!-- jQuery UI -->
  <script src="<?= asset_url() ?>js/editor-quill.js"></script>
  <!-- apex charts -->
  <script src="<?= asset_url() ?>js/apexcharts.min.js"></script>
  <!-- Calendar Js -->
  <script src="<?= asset_url() ?>js/calendar.js"></script>
  <!-- jvectormap Js -->
  <script src="<?= asset_url() ?>js/jquery-jvectormap-2.0.5.min.js"></script>
  <!-- jvectormap world Js -->
  <script src="<?= asset_url() ?>js/jquery-jvectormap-world-mill-en.js"></script>

  <!-- main js -->
  <script src="<?= asset_url() ?>js/main.js"></script>

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  <script>
    function createChart(chartId, chartColor) {
      let currentYear = new Date().getFullYear();

      var options = {
        series: [{
          name: "series1",
          data: [18, 25, 22, 40, 34, 55, 50, 60, 55, 65],
        }, ],
        chart: {
          type: "area",
          width: 80,
          height: 42,
          sparkline: {
            enabled: true, // Remove whitespace
          },

          toolbar: {
            show: false,
          },
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0,
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
          width: 1,
          colors: [chartColor],
          lineCap: "round",
        },
        grid: {
          show: true,
          borderColor: "transparent",
          strokeDashArray: 0,
          position: "back",
          xaxis: {
            lines: {
              show: false,
            },
          },
          yaxis: {
            lines: {
              show: false,
            },
          },
          row: {
            colors: undefined,
            opacity: 0.5,
          },
          column: {
            colors: undefined,
            opacity: 0.5,
          },
          padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
          },
        },
        fill: {
          type: "gradient",
          colors: [chartColor], // Set the starting color (top color) here
          gradient: {
            shade: "light", // Gradient shading type
            type: "vertical", // Gradient direction (vertical)
            shadeIntensity: 0.5, // Intensity of the gradient shading
            gradientToColors: [`${chartColor}00`], // Bottom gradient color (with transparency)
            inverseColors: false, // Do not invert colors
            opacityFrom: 0.5, // Starting opacity
            opacityTo: 0.3, // Ending opacity
            stops: [0, 100],
          },
        },
        // Customize the circle marker color on hover
        markers: {
          colors: [chartColor],
          strokeWidth: 2,
          size: 0,
          hover: {
            size: 8,
          },
        },
        xaxis: {
          labels: {
            show: false,
          },
          categories: [
            `Jan ${currentYear}`,
            `Feb ${currentYear}`,
            `Mar ${currentYear}`,
            `Apr ${currentYear}`,
            `May ${currentYear}`,
            `Jun ${currentYear}`,
            `Jul ${currentYear}`,
            `Aug ${currentYear}`,
            `Sep ${currentYear}`,
            `Oct ${currentYear}`,
            `Nov ${currentYear}`,
            `Dec ${currentYear}`,
          ],
          tooltip: {
            enabled: false,
          },
        },
        yaxis: {
          labels: {
            show: false,
          },
        },
        tooltip: {
          x: {
            format: "dd/MM/yy HH:mm",
          },
        },
      };

      var chart = new ApexCharts(
        document.querySelector(`#${chartId}`),
        options
      );
      chart.render();
    }

    // Call the function for each chart with the desired ID and color
    createChart("complete-course", "#2FB2AB");
    createChart("earned-certificate", "#27CFA7");
    createChart("course-progress", "#6142FF");
    createChart("community-support", "#FA902F");

    // =========================== Double Line Chart Start ===============================
    function createLineChart(chartId, chartColor) {
      var options = {
        series: [{
            name: "Study",
            data: [8, 15, 9, 20, 10, 33, 13, 22, 8, 17, 10, 15],
          },
          {
            name: "Test",
            data: [8, 24, 18, 40, 18, 48, 22, 38, 18, 30, 20, 28],
          },
        ],
        chart: {
          type: "area",
          width: "100%",
          height: 300,
          sparkline: {
            enabled: false, // Remove whitespace
          },
          toolbar: {
            show: false,
          },
          padding: {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0,
          },
        },
        colors: ["#3D7FF9", chartColor], // Set the color of the series
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: "smooth",
          width: 1,
          colors: ["#3D7FF9", chartColor],
          lineCap: "round",
        },
        fill: {
          type: "gradient",
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.9, // Decrease this value to reduce opacity
            opacityTo: 0.2, // Decrease this value to reduce opacity
            stops: [0, 100],
          },
        },
        grid: {
          show: true,
          borderColor: "#E6E6E6",
          strokeDashArray: 3,
          position: "back",
          xaxis: {
            lines: {
              show: false,
            },
          },
          yaxis: {
            lines: {
              show: true,
            },
          },
          row: {
            colors: undefined,
            opacity: 0.5,
          },
          column: {
            colors: undefined,
            opacity: 0.5,
          },
          padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0,
          },
        },
        // Customize the circle marker color on hover
        markers: {
          colors: ["#3D7FF9", chartColor],
          strokeWidth: 3,
          size: 0,
          hover: {
            size: 8,
          },
        },
        xaxis: {
          labels: {
            show: false,
          },
          categories: [
            `Jan`,
            `Feb`,
            `Mar`,
            `Apr`,
            `May`,
            `Jun`,
            `Jul`,
            `Aug`,
            `Sep`,
            `Oct`,
            `Nov`,
            `Dec`,
          ],
          tooltip: {
            enabled: false,
          },
          labels: {
            formatter: function(value) {
              return value;
            },
            style: {
              fontSize: "14px",
            },
          },
        },
        yaxis: {
          labels: {
            formatter: function(value) {
              return "$" + value + "Hr";
            },
            style: {
              fontSize: "14px",
            },
          },
        },
        tooltip: {
          x: {
            format: "dd/MM/yy HH:mm",
          },
        },
        legend: {
          show: false,
          position: "top",
          horizontalAlign: "right",
          offsetX: -10,
          offsetY: -0,
        },
      };

      var chart = new ApexCharts(
        document.querySelector(`#${chartId}`),
        options
      );
      chart.render();
    }
    createLineChart("doubleLineChart", "#27CFA7");
    // =========================== Double Line Chart End ===============================

    // ================================= Multiple Radial Bar Chart Start =============================
    var options = {
      series: [100, 60, 25],
      chart: {
        height: 172,
        type: "radialBar",
      },
      colors: ["#3D7FF9", "#27CFA7", "#020203"],
      stroke: {
        lineCap: "round",
      },
      plotOptions: {
        radialBar: {
          hollow: {
            size: "30%", // Adjust this value to control the bar width
          },
          dataLabels: {
            name: {
              fontSize: "16px",
            },
            value: {
              fontSize: "16px",
            },
            total: {
              show: true,
              formatter: function(w) {
                return "82%";
              },
            },
          },
        },
      },
      labels: ["Completed", "In Progress", "Not Started"],
    };

    var chart = new ApexCharts(
      document.querySelector("#radialMultipleBar"),
      options
    );
    chart.render();
    // ================================= Multiple Radial Bar Chart End =============================

    // ========================== Export Js Start ==============================
    document
      .getElementById("exportOptions")
      .addEventListener("change", function() {
        const format = this.value;
        const table = document.getElementById("studentTable");
        let data = [];
        const headers = [];

        // Get the table headers
        table.querySelectorAll("thead th").forEach((th) => {
          headers.push(th.innerText.trim());
        });

        // Get the table rows
        table.querySelectorAll("tbody tr").forEach((tr) => {
          const row = {};
          tr.querySelectorAll("td").forEach((td, index) => {
            row[headers[index]] = td.innerText.trim();
          });
          data.push(row);
        });

        if (format === "csv") {
          downloadCSV(data);
        } else if (format === "json") {
          downloadJSON(data);
        }
      });

    function downloadCSV(data) {
      const csv = data.map((row) => Object.values(row).join(",")).join("\n");
      const blob = new Blob([csv], {
        type: "text/csv"
      });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "students.csv";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

    function downloadJSON(data) {
      const json = JSON.stringify(data, null, 2);
      const blob = new Blob([json], {
        type: "application/json"
      });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "students.json";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }
    // ========================== Export Js End ==============================
  </script>

  <script>
    // Plyr Js Start
    const player = new Plyr('#player');
    const featuredPlayer = new Plyr('#featuredPlayer');
    // Plyr Js End

    $('.delete-item-btn').on('click', function() {
      $(this).closest('.upload-card-item').addClass('d-none')
    });


    // ========================= Social Share Js Start ===========================
    $('.share-social__button').on('click', function(event) {
      event.stopPropagation();
      $(this).addClass('active');
      $('.share-social__icons').toggleClass('show')
    });

    $('body').on('click', function(event) {
      $('.share-social__icons').removeClass('show');
      $('.share-social__button').removeClass('active');
    });

    // For device width size js start
    // let screenSize = screen.width
    // alert(' Your Screen Size is: ' + screenSize + 'pixel'); 
    // For device width size js start

    let socialShareBtn = $('.share-social');
    // Check if the element exists
    if (socialShareBtn.length > 0) {
      let leftDistance = socialShareBtn.offset().left;
      let rightDistance = $(window).width() - (leftDistance + socialShareBtn.outerWidth());

      if (leftDistance < rightDistance) {
        $('.share-social__icons').addClass('left');
      }
    }
    // ========================= Social Share Js End ===========================

    // Bookmark js Start
    $('.bookmark-icon').on('click', function() {
      $(this).toggleClass('active');
      let icon = $(this).children('i');

      if ($(this).hasClass('active')) {
        icon.removeClass('ph ph-bookmarks');
        icon.addClass('ph-fill ph-bookmarks text-main-600');
      } else {
        icon.removeClass('ph-fill ph-bookmarks');
        icon.addClass('ph ph-bookmarks');
      }
    });
    // Bookmark js End
  </script>
  <script>
    $(document).ready(function() {
      $('#courseLesson').select2({
        placeholder: "Select course lesson",
        allowClear: true,
        width: '100%' // Ensures it fits within Bootstrap styling
      });
    });


    // ============================= Initialize Quill editor js Start ============================= 
    function editorFunction(editorId) {
      const quill = new Quill(editorId, {
        theme: 'snow'
      });
    }
    editorFunction('#editor');
  </script>
</body>

</html>