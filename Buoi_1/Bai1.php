
<?php
$students = [
    [
        "name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5
    ],
    [
        "name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5
    ],
    [
        "name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5
    ],
    [
        "name" => "Pham Thi Dung",
        "age" => 20,
        "score" => 7.5
    ]
];

// 1. 
echo "=== DANH SÁCH SINH VIÊN ===\n";
$totalScore = 0;

foreach ($students as $student) {
    echo "Họ tên: " . $student["name"] . " | Tuổi: " . $student["age"] . " | Điểm: " . $student["score"] . "\n";
    $totalScore += $student["score"];
}

// 2.
$averageScore = $totalScore / count($students);
echo "\nĐiểm trung bình của tất cả sinh viên: " . number_format($averageScore, 2) . "\n";
?>
