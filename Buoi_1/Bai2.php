<?php
$students = [
    ["name" => "Nguyen Van An", "age" => 20, "score" => 8.5],
    ["name" => "Tran Thi Binh", "age" => 21, "score" => 6.5],
    ["name" => "Le Van Cuong", "age" => 19, "score" => 4.5],
    ["name" => "Pham Thi Dung", "age" => 20, "score" => 7.5]
];

// 1. 
function calculateAverageScore($students) {
    if (empty($students)) return 0;
    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student["score"];
    }
    return $totalScore / count($students);
}

// 2. 
function getRank($score) {
    if ($score >= 8.0) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5.0) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}

// 3. 
function displayStudent($student) {
    $rank = getRank($student["score"]);
    echo "Họ tên: {$student['name']} | Tuổi: {$student['age']} | Điểm: {$student['score']} | Xếp loại: {$rank}\n";
}

// Phần code chính chỉ gọi hàm[cite: 1]
echo "=== DANH SÁCH SINH VIÊN (ĐÃ TÁCH HÀM) ===\n";
foreach ($students as $student) {
    displayStudent($student);
}

$avg = calculateAverageScore($students);
echo "\nĐiểm trung bình lớp: " . number_format($avg, 2) . "\n";
?>
