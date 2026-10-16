    document.addEventListener("DOMContentLoaded", () => {
      // Modal Logic
      const periodSelect = document.getElementById("periodSelect");
      const customDateModalElement = document.getElementById("customDateModal");
      const customDateModal = new bootstrap.Modal(customDateModalElement);

      // Ustawienie wybranej opcji w selectie na podstawie URL
      const urlParams = new URLSearchParams(window.location.search);
      const currentPeriod = urlParams.get("period");
      
      if (currentPeriod && periodSelect) {
        periodSelect.value = currentPeriod;
      }

     // Obsługa zmiany okresu w selectie
    periodSelect.addEventListener("change", (e) => {
        const selectedValue = e.target.value;
        if (selectedValue === "custom") {
            customDateModal.show();
        } else {
            window.location.href = `balance.php?period=${selectedValue}`;
        }
    });

      // Chart Logic (używamy zmiennych przekazanych z PHP lub domyślnych pustych tablic, jeśli brak wydatków)
      const ctx = document.getElementById('expenseChart').getContext('2d');
      
      // Korzystamy ze zmiennych zdefiniowanych w pliku balance.php
      const labels = (typeof expenseChartLabels !== 'undefined') ? expenseChartLabels : [];
      const dataValues = (typeof expenseChartData !== 'undefined') ? expenseChartData : [];

      // Paleta kolorów dopasowana do HomyFinance
      const backgroundColors = ['#e76f51', '#f4a261', '#e9c46a', '#2a9d8f', '#52796f', '#2f3e46', '#e07a5f'];

      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: labels,
          datasets: [{
            data: dataValues,
            backgroundColor: backgroundColors.slice(0, labels.length),
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