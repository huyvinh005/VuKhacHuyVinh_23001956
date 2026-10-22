<?php
$danhSachHocSinh = [
    ["name" => "Vũ Khắc Huy Vinh", "score" => 8.5],
    ["name" => "Nguyễn Văn A", "score" => 7.0],  
    ["name" => "Trần Thị B", "score" => 5.5],  
    ["name" => "Lê Văn C", "score" => 4.0],       
    ["name" => "Phạm Văn D", "score" => 11],      
    ["name" => "Hoàng Thị E", "score" => -1]    
];

echo "<h3>KẾT QUẢ XẾP LOẠI HỌC SINH</h3>";

foreach ($danhSachHocSinh as $hs) {
    $name = $hs["name"];
    $score = $hs["score"];

    if (!is_numeric($score) || $score < 0 || $score > 10) {
        $ranking = "Điểm số không hợp lệ (Phải từ 0 đến 10)";
    } else {
        if ($score >= 8.0) {
            $ranking = "Giỏi";
        } elseif ($score >= 6.5) {
            $ranking = "Khá";
        } elseif ($score >= 5.0) {
            $ranking = "Trung bình";
        } else {
            $ranking = "Yếu";
        }
    }

    echo "Họ tên: <b>" . $name . "</b> | Điểm: " . $score . " | Xếp loại: <b>" . $ranking . "</b><br>";
}
?>
