  document.addEventListener("DOMContentLoaded", () => {
      const dateInput = document.getElementById("date");
      const expenseForm = document.getElementById("expenseForm");

      const getTodayLocalDate = () => {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      }

      const todayLocal = getTodayLocalDate();

      if (dateInput) {

        dateInput.value = todayLocal;
        dateInput.max = todayLocal;
      }
      if (expenseForm && dateInput) {
        expenseForm.addEventListener("reset", () => {
          setTimeout(() => {
            if (dateInput) {
              dateInput.value = getTodayLocalDate();
            }
          }, 0);
        });
      }
    });
