document.addEventListener("click", function (e) {
    if (e.target.matches(".js-add-ntp-server")) {
        e.preventDefault();

        const field = document.getElementById("add-ntp-server-field");
        const template = document.getElementById("ntp-server").innerHTML;
        const serverValue = field.value.trim();

        if (serverValue === "") return;

        const row = template.replace("{{ server }}", serverValue);
        document.querySelector(".js-ntp-servers").insertAdjacentHTML("beforeend", row);
        field.value = "";
    }

    if (e.target.matches(".js-remove-ntp-server")) {
        e.preventDefault();
        const row = e.target.closest(".js-ntp-server");
        if (row) row.remove();
    }
});

document.getElementById("chxntpedit").addEventListener("change", function () {
    document.getElementById("txtntpconfigraw").disabled = !this.checked;
});

