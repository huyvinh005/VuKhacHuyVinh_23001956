<?php
$students = [
    ["name" => "Nguyen Van An", "age" => 20, "score" => 8.5],
    ["name" => "Tran Thi Binh", "age" => 21, "score" => 6.5],
    ["name" => "Le Van Cuong", "age" => 19, "score" => 4.5],
    ["name" => "Pham Thi Dung", "age" => 20, "score" => 7.5]
];

function findBestStudent($students) {
    if (empty($students)) return null;
    $best = $students[0];
    foreach ($students as $student) {
        if ($student["score"] > $best["score"]) {
            $best = $student;
        }
    }
    return $best;
}

function findWorstStudent($students) {
    if (empty($students)) return null;
    $worst = $students[0];
    foreach ($students as $student) {
        if ($student["score"] < $worst["score"]) {
            $worst = $student;
        }
    }
    return $worst;
}

function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student["score"] >= 5.0) {
            $count++;
        }
    }
    return $count;
}

function findStudentByName($students, $name) {
    foreach ($students as $student) {
        // So sánh không phân biệt hoa thường
        if (strcasecmp($student["name"], $name) == 0) {
            return $student;
        }
    }
    return null;
}

echo "=== KẾT QUẢ XỬ LÝ DANH SÁCH ===\n";

$best = findBestStudent($students);
echo "Sinh viên điểm cao nhất: " . $best["name"] . " (" . $best["score"] . " điểm)\n";

$worst = findWorstStudent($students);
echo "Sinh viên điểm thấp nhất: " . $worst["name"] . " (" . $worst["score"] . " điểm)\n";

echo "Số sinh viên đạt: " . countPassedStudents($students) . " sinh viên\n";

$searchName = "Tran Thi Binh";
$found = findStudentByName($students, $searchName);
if ($found) {
    echo "Tìm thấy sinh viên '{$searchName}': Tuổi {$found['age']}, Điểm {$found['score']}\n";
} else {
    echo "Không tìm thấy sinh viên '{$searchName}'\n";
}
?>
