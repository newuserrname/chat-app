import $ from 'jquery';

$(document).ready(function() {

    $(".chat-list a").click(function() {
        $(".chatbox").addClass('showbox');
        return false;
    });

    $(".chat-icon").click(function() {
        $(".chatbox").removeClass('showbox');
    });
});

// Lấy button và form
const showButton = document.getElementById("show-stampv2");
const stampv2Form = document.getElementById("stampv2");

// Thêm sự kiện click cho button
showButton.addEventListener("click", function () {
    // Nếu form đang ẩn, hiển thị form và thêm sự kiện click để ẩn form khi click ra ngoài
    if (stampv2Form.style.display === "none") {
        stampv2Form.style.display = "block";
        document.addEventListener("click", handleClickOutside);
    } else {
        // Nếu form đang hiển thị, ẩn form và xoá sự kiện click ra ngoài
        stampv2Form.style.display = "none";
        document.removeEventListener("click", handleClickOutside);
    }
});

// Hàm xử lý sự kiện click ra ngoài
function handleClickOutside(event) {
    if (!stampv2Form.contains(event.target) && event.target !== showButton) {
        stampv2Form.style.display = "none";
        document.removeEventListener("click", handleClickOutside);
    }
}
