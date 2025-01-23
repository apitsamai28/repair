<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dormitory";
$conn = new mysqli('localhost', 'root', '', 'dormitory');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบพารามิเตอร์
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM repairs WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "ลบข้อมูลสำเร็จ";
    } else {
        echo "ข้อผิดพลาด: " . $conn->error;
    }
} else {
    echo "ไม่มี ID ระบุมา";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
