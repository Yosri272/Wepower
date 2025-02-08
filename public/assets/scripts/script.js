if ($(window).outerWidth() > 1199) {
    $("nav.side-navbar").removeClass("shrink");
}

new DataTable("#myDataTable");

function onlyLetter(evt) {
    var chars = String.fromCharCode(evt.which);
    if (!/[a-z,A-Z]/.test(chars)) {
        evt.preventDefault();
    }
}

const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
);
const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
);

function onlyNumber(evt) {
    var chars = String.fromCharCode(evt.which);
    if (!/[0-9.]/.test(chars)) {
        evt.preventDefault();
    }
}

document.addEventListener("DOMContentLoaded", function () {
    var root = document.documentElement;

    // Get the value of --theme-color
    var themeColor = getComputedStyle(root).getPropertyValue("--theme-color");

    var svgImages = document.querySelectorAll(".menu.active .menu-icon");

    // svgImages.forEach(function (svgImage) {
    //     var svgPath = svgImage.getAttribute("src");
    //     var xhr = new XMLHttpRequest();
    //     xhr.onreadystatechange = function () {
    //         if (xhr.readyState === 4 && xhr.status === 200) {
    //             var svgContent = xhr.responseText;
    //             svgContent = svgContent.replace(
    //                 /stroke="#9395A2"/g,
    //                 'stroke="' + themeColor + '"'
    //             );
    //             svgContent = svgContent.replace(
    //                 /fill="#9395A2"/g,
    //                 'fill="' + themeColor + '"'
    //             );
    //             svgImage.src =
    //                 "data:image/svg+xml;charset=utf-8," +
    //                 encodeURIComponent(svgContent);
    //         }
    //     };
    //     xhr.open("GET", svgPath, true);
    //     xhr.send();
    // });
});

// visitor messge
$(".visitorMessage").on("click", function (e) {
    Swal.fire(
        "Access Denied!",
        "You don't have permission to access update/edit this admin.",
        "warning"
    );
});

var previewFile = (event, previewID) => {
    var reader = new FileReader();
    reader.onload = function () {
        var output = document.getElementById(previewID);
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
};

var gridView = document.getElementById("gridView");
var listView = document.getElementById("listView");

var gridItem = document.getElementById("gridItem");
var listItem = document.getElementById("listItem");

if (
    gridView !== null &&
    listView !== null &&
    gridItem !== null &&
    listItem !== null
) {
    gridView.addEventListener("click", function () {
        gridView.classList.add("active");
        listView.classList.remove("active");
        localStorage.setItem("view", "grid");

        gridItem.classList.add("d-flex");
        gridItem.classList.remove("d-none");
        listItem.classList.remove("d-block");
        listItem.classList.add("d-none");
    });

    listView.addEventListener("click", function () {
        gridView.classList.remove("active");
        listView.classList.add("active");
        localStorage.setItem("view", "list");

        gridItem.classList.remove("d-flex");
        gridItem.classList.add("d-none");
        listItem.classList.add("d-block");
        listItem.classList.remove("d-none");
    });

    $(document).ready(function () {
        var view = localStorage.getItem("view");
        if (view === "grid") {
            gridView.classList.add("active");
            listView.classList.remove("active");

            gridItem.classList.add("d-flex");
            gridItem.classList.remove("d-none");
            listItem.classList.add("d-none");
        } else if (view === "list") {
            gridView.classList.remove("active");
            listView.classList.add("active");

            gridItem.classList.add("d-none");
            listItem.classList.add("d-block");
            listItem.classList.remove("d-none");
        } else {
            gridView.classList.add("active");
            listView.classList.remove("active");

            gridItem.classList.add("d-flex");
            gridItem.classList.remove("d-none");
            listItem.classList.add("d-none");
        }
    });
}

$(document).ready(function () {
    $("tr:not(:first-child)").each(function (index) {
        $(this).css("animation-delay", index * 0.1 + "s");
        $(this).css("display", "table-row");
    });

    setTimeout(function () {
        $("table").css("overflow", "auto");
    }, $("table tr").length * 200);
});
