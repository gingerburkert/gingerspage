jQuery(function () {
    Chart.defaults.global.legend.display = false;

    var ctx = document.getElementById("<?php echo $id_rep;?>");
    var <?php echo $id_rep;?> = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [<?php echo implode(',', $labels)?>],
        datasets: [{
          data: [<?php echo implode(',', $data)?>],
          backgroundColor: [<?php echo implode(',', $bgColor)?>],
          borderColor: [<?php echo implode(',', $borderColor)?>],
          borderWidth: <?php echo $field['enable_border'] ? $field['border_width'] : 0; ?>
        }]
      },
      options: {
        responsive: <?php echo ($field['enable_responsive'] OR $renderer->detect->isMobile()) ? 'true' : 'false'; ?>,
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