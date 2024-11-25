// // Fungsi untuk menambah notifikasi baru
// function addNotification(notification) {
//     var notificationItem = $("<a>")
//         .addClass("dropdown-item")
//         .attr("href", "#")
//         .html("New bill reminder: Pay your " + notification.category + " bill"); // Menggunakan category dari data notifikasi

//     $("#notificationList").prepend(notificationItem);

//     var currentCount = parseInt($("#notificationCount").text());
//     $("#notificationCount").text(currentCount + 1);
// }

// $(document).ready(function () {
//     fetch("/api/bill-notifications")
//         .then((response) => response.json())
//         .then((data) => {
//             data.forEach((notification) => {
//                 addNotification(notification);
//             });
//         })
//         .catch((error) =>
//             console.error("Error fetching bill notifications:", error)
//         );
// });
