<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dormitory";

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'dormitory');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม
$room_Number = $_POST['room_number'] ?? null;
$repair_Type = $_POST['repairType'] ?? null;
$description = $_POST['description'] ?? null;
$Image_path = $_FILES['repairImage'] ?? null;

// ตรวจสอบข้อมูลที่จำเป็น
if (empty($room_Number) || empty($repair_Type) || empty($description)) {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    exit;
}

// ตรวจสอบและอัปโหลดไฟล์รูปภาพ
$targetFile = '';
if ($Image_path && !empty($Image_path['tmp_name'])) {
    // ตรวจสอบชนิดไฟล์
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($Image_path['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        echo "รองรับเฉพาะไฟล์ JPEG, PNG และ GIF เท่านั้น";
        exit;
    }

    // ตั้งค่าโฟลเดอร์อัปโหลด
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    $targetFile = $targetDir . time() . "_" . basename($Image_path["name"]); // ชื่อไฟล์ที่ไม่ซ้ำ

    if (!move_uploaded_file($Image_path["tmp_name"], $targetFile)) {
        echo "การอัปโหลดไฟล์ล้มเหลว";
        exit;
    }
} else {
    echo "กรุณาอัปโหลดรูปภาพ";
    exit;
}

// เพิ่มข้อมูลลงฐานข้อมูล
$stmt = $conn->prepare("INSERT INTO repairs (room_number, repair_type, description, image_path, created_at, status) VALUES (?, ?, ?, ?, NOW(), 'รอการยืนยัน')");
$stmt->bind_param("ssss", $room_Number, $repair_Type, $description, $targetFile);

if ($stmt->execute()) {
    echo "การแจ้งซ่อมสำเร็จ!";
    // เปลี่ยนหน้าไปยังหน้าสถานะการแจ้งซ่อม
    header("Location: สถานะการแจ้งซ่อม.php");
    exit;
} else {
    echo "เกิดข้อผิดพลาด: " . $stmt->error;
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
