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

    // Paleta kolorów dopasowana do HomyFinance
      const backgroundColors = ['#e76f51', '#f4a261', '#e9c46a', '#2a9d8f', '#52796f', '#2f3e46', '#e07a5f'];
    
      // Wspólne opcje dla wykresów Chart.js
  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          font: {
            family: 'system-ui, -apple-system, sans-serif',
            size: 13,
            weight: '600'
          },
          padding: 14
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
  };

      // 1. Inicjalizacja wykresu Przychodów (Income Chart)
  const incomeCtx = document.getElementById('incomeChart');
  if (incomeCtx) {
    const incLabels = (typeof incomeChartLabels !== 'undefined') ? incomeChartLabels : [];
    const incData = (typeof incomeChartData !== 'undefined') ? incomeChartData : [];

    new Chart(incomeCtx.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: incLabels,
        datasets: [{
          data: incData,
          backgroundColor: backgroundColors.slice(0, incLabels.length),
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: chartOptions
    });
  }

  // 2. Inicjalizacja wykresu Wydatków (Expense Chart)
  const expenseCtx = document.getElementById('expenseChart');
  if (expenseCtx) {
    const expLabels = (typeof expenseChartLabels !== 'undefined') ? expenseChartLabels : [];
    const expData = (typeof expenseChartData !== 'undefined') ? expenseChartData : [];

    new Chart(expenseCtx.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: expLabels,
        datasets: [{
          data: expData,
          backgroundColor: backgroundColors.slice(0, expLabels.length),
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: chartOptions
    });
  }
});