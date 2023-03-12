const showButtonv2 = document.getElementById("show-stampv2");
const stampv2Form = document.getElementById("stampv2");

if (showButtonv2 != null){
    showButtonv2.addEventListener("click", function () {
        if (stampv2Form.style.display === "none") {
            stampv2Form.style.display = "block";
            document.addEventListener("click", handleClickOutsideV2);
        } else {
            stampv2Form.style.display = "none";
            document.removeEventListener("click", handleClickOutsideV2);
        }
    });
}

function handleClickOutsideV2(event) {
    if (!stampv2Form.contains(event.target) && event.target !== showButtonv2) {
        stampv2Form.style.display = "none";
        document.removeEventListener("click", handleClickOutsideV2);
    }
}

