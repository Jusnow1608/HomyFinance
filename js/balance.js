    document.addEventListener("DOMContentLoaded", () => {
      // Modal Logic
      const periodSelect = document.getElementById("periodSelect");
      const customDateModal = new bootstrap.Modal(document.getElementById("customDateModal"));

      periodSelect.addEventListener("change", (e) => {
        if (e.target.value === "custom") {
          customDateModal.show();
        }
      });

      // Chart Logic
      const ctx = document.getElementById('expenseChart').getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Apartments', 'Food', 'Recreation', 'Transport'],
          datasets: [{
            data: [1200, 850, 500, 300],
            backgroundColor: ['#e76f51', '#f4a261', '#e9c46a', '#2a9d8f'],
            borderWidth: 2,
            borderColor: '#ffffff'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                font: {
                  family: 'system-ui, -apple-system, sans-serif',
                  size: 14,
                  weight: '600'
                },
                padding: 16
              }
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return ` ${context.label}: ${context.raw.toFixed(2)} PLN`;
                }
              }
            }
          },
          cutout: '62%'
        }
      });
    });