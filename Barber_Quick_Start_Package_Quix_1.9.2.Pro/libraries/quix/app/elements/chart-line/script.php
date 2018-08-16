jQuery(function () {
    Chart.defaults.global.legend.display = false;

    var ctx = document.getElementById("<?php echo $id_rep;?>");
    var <?php echo $id_rep;?> = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [<?php echo implode(',', $labels)?>],
        datasets: [{
          fill: <?php echo $field['enable_fill'] ? 1 : 0; ?>,
          lineTension: 0.1,
          backgroundColor: "<?php echo $field['background_color'] ? $field['background_color'] : 'rgba(255, 99, 132, 0.2)'; ?>",
          borderColor: "<?php echo $field['border_color'] ? $field['border_color'] : 'rgba(255, 99, 132, 0.2)'?>",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "<?php echo $field['point_border_color'] ? $field['point_border_color'] : 'rgba(255, 99, 132, 0.4)'?>",
          pointBackgroundColor: "<?php echo $field['point_background_color'] ? $field['point_background_color'] : '#fff'?>",
          pointBorderWidth: 2,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "<?php echo $field['point_hover_background_color'] ? $field['point_hover_background_color'] : 'rgba(255, 99, 132, 0.4)'?>",
          pointHoverBorderColor: "<?php echo $field['point_hover_border_color'] ? $field['point_hover_border_color'] : 'rgba(255, 99, 132, 0.8)'?>",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          data: [<?php echo implode(',', $data)?>],
          spanGaps: false
        }]
      },
      options: {
        responsive: <?php echo ($field['enable_responsive'] OR $renderer->detect->isMobile()) ? 'true' : 'false'; ?>,
        showLines: <?php echo $field['show_line'] ? 'true' : 'false'; ?>,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero:true
            }
          }]
        }
      }
    });

  });