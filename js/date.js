  document.addEventListener("DOMContentLoaded", () => {
      const dateInput = document.getElementById("date");
      const form = dateInput ? dateInput.closest("form") : null;

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
      if (form && dateInput) {
        form.addEventListener("reset", () => {
          setTimeout(() => {
            if (dateInput) {
              dateInput.value = getTodayLocalDate();
            }
          }, 0);
        });
      }
    });
