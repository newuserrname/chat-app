// Mobile responsive
/*document.querySelectorAll(".chat-list a").forEach(function(element) {
    element.addEventListener("click", function(event) {
        event.preventDefault();
        console.log("Click event received.");
        document.querySelector(".chatbox").classList.add("showbox");
    });
});

document.querySelector(".chat-icon").addEventListener("click", function(event) {
    event.preventDefault();
    document.querySelector(".chatbox").classList.remove("showbox");
});*/

// StampV2
// Lấy button và form
const showButtonv2 = document.getElementById("show-stampv2");
const stampv2Form = document.getElementById("stampv2");

// Thêm sự kiện click cho button
showButtonv2.addEventListener("click", function () {
    // Nếu form đang ẩn, hiển thị form và thêm sự kiện click để ẩn form khi click ra ngoài
    if (stampv2Form.style.display === "none") {
        stampv2Form.style.display = "block";
        document.addEventListener("click", handleClickOutsideV2);
    } else {
        // Nếu form đang hiển thị, ẩn form và xoá sự kiện click ra ngoài
        stampv2Form.style.display = "none";
        document.removeEventListener("click", handleClickOutsideV2);
    }
});

// Hàm xử lý sự kiện click ra ngoài
function handleClickOutsideV2(event) {
    if (!stampv2Form.contains(event.target) && event.target !== showButtonv2) {
        stampv2Form.style.display = "none";
        document.removeEventListener("click", handleClickOutsideV2);
    }
}

//StampV1
const showButtonv1 = document.getElementById("show-stampv1");
const stampv1Form = document.getElementById("stampv1");

// Thêm sự kiện click cho button
showButtonv1.addEventListener("click", function () {
    // Nếu form đang ẩn, hiển thị form và thêm sự kiện click để ẩn form khi click ra ngoài
    if (stampv1Form.style.display === "none") {
        stampv1Form.style.display = "block";
        document.addEventListener("click", handleClickOutsideV1);
    } else {
        // Nếu form đang hiển thị, ẩn form và xoá sự kiện click ra ngoài
        stampv1Form.style.display = "none";
        document.removeEventListener("click", handleClickOutsideV1);
    }
});

// Hàm xử lý sự kiện click ra ngoài
function handleClickOutsideV1(event) {
    if (!stampv1Form.contains(event.target) && event.target !== showButtonv1) {
        stampv1Form.style.display = "none";
        document.removeEventListener("click", handleClickOutsideV1);
    }
}
