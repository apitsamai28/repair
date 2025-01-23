<?php
// การเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์
$username = "root"; // ชื่อผู้ใช้งาน
$password = ""; // รหัสผ่าน
$dbname = "dormitory"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli('localhost', 'root', '', 'dormitory');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// Query ดึงข้อมูล
$sql = "SELECT id,room_number, repair_type, description, image_path, created_at, status FROM repairs";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>สถานะการแจ้งซ่อม</title>
  <style>
    body {
      background-color: #e9f2fb; /* สีพื้นหลัง */
      font-family: Arial, sans-serif;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    table th, table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    table th {
      background-color: #007bff; /* สีน้ำเงินเข้ม */
      color: white; /* ตัวอักษรสีขาว */
    }
    table tr:nth-child(even) {
      background-color: #f2f2f2; /* สีแถวคู่ */
    }
    table tr:hover {
      background-color: #d9e7f7; /* สีเมื่อเอาเมาส์ชี้ */
    }
    img {
      max-width: 100px;
      max-height: 100px;
      border-radius: 8px;
      a {
  padding: 5px 10px;
  border-radius: 5px;
  font-size: 14px;
}

a[href*="cancel.php"] {
  background-color: orange;
  color: white;
  text-decoration: none;
}

a[href*="delete.php"] {
  background-color: red;
  color: white;
  text-decoration: none;
}

a:hover {
  opacity: 0.8;
}
    }
  </style>
</head>
<body>
  <h1>สถานะการแจ้งซ่อม</h1>
  <table>
    <thead>
      <tr>
        <th>หมายเลขห้อง</th>
        <th>ประเภทปัญหา</th>
        <th>คำอธิบาย</th>
        <th>รูปภาพ</th>
        <th>วันที่แจ้ง</th>
        <th>สถานะ</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // แสดงข้อมูลจากฐานข้อมูล
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($row["room_number"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["repair_type"]) . "</td>";
              echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
              echo "<td><img src='" . htmlspecialchars($row["image_path"]) . "' alt='รูปภาพ'></td>";
              echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
              echo "<td>";
          echo htmlspecialchars($row["status"]) . "<br>";
          echo "<a href='cancel.php?id=" . urlencode($row["id"]) . "' style='color: orange; text-decoration: none;'>ยกเลิก</a> | ";
          echo "<a href='delete.php?id=" . urlencode($row["id"]) . "' style='color: red; text-decoration: none;' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?\");'>ลบ</a>";
          echo "</td>";
          
          echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
      }
      ?>
    </tbody>
  </table>
</body>
</html>
<?php
// ปิดการเชื่อมต่อ
$conn->close();
?>
