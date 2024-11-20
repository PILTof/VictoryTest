/**
 * Theme Name: testing
 * Tags: testing
 */

function sendAjax(dom) {
    let form = dom;
    if (dom.getAttribute("id") !== "filter") {
        form = document.getElementById("filter");
    }
    let data = new FormData(form);
    data.append("type", dom.dataset.type);
    $.ajax({
        type: "method",
        url: "url",
        data: "data",
        dataType: "dataType",
        success: function (response) {},
    });
    $.ajax({
        type: "POST",
        url: form.getAttribute("action"),
        data: data,
        dataType: "html",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log("filtred");
            let dom = document.createElement("div");
            dom.innerHTML = response;
            document.querySelector('[data-entity="page-container"]').innerHTML =
                dom.querySelector('[data-entity="page-container"]').innerHTML;
            updateEvents();
        },
        error: function (agr1, agr2, agr3) {
            console.log(arguments);
        },
    });
    return false;
}
function updateEvents() {
    console.log("ивенты обновлены");
    $("#filter").submit(function (e) {
        e.preventDefault();
        return false;
        // sendAjax(e.target.getAttribute('action'));
    });
    document
        .querySelectorAll('#filter input[type="submit"]')
        .forEach((element) => {
            $(element).click(function (e) {
                e.preventDefault();
                sendAjax(e.target);
                return false;
            });
        });
}
document.addEventListener("DOMContentLoaded", function () {
    updateEvents();
});
