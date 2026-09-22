<?php

class Student {
    public $name;
    public $age;
    public $score;

    public function __construct($name, $age, $score) {
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }

    public function getRank() {
        if ($this->score >= 8.0) {
            return "Giỏi";
        } elseif ($this->score >= 6.5) {
            return "Khá";
        } elseif ($this->score >= 5.0) {
            return "Trung bình";
        } else {
            return "Yếu";
        }
    }

    public function isPassed() {
        return $this->score >= 5.0;
    }

    public function display() {
        echo "Họ tên: {$this->name} | Tuổi: {$this->age} | Điểm: {$this->score} | Xếp loại: " . $this->getRank() . "\n";
    }
}

function calculateAverageScoreOOP($students) {
    if (empty($students)) return 0;
    $total = 0;
    foreach ($students as $student) {
        $total += $student->score;
    }
    return $total / count($students);
}

function findBestStudentOOP($students) {
    if (empty($students)) return null;
    $best = $students[0];
    foreach ($students as $student) {
        if ($student->score > $best->score) {
            $best = $student;
        }
    }
    return $best;
}

function countPassedStudentsOOP($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student->isPassed()) {
            $count++;
        }
    }
    return $count;
}

$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

$studentList = [$student1, $student2, $student3, $student4];

echo "=== DANH SÁCH SINH VIÊN (OOP) ===\n";
foreach ($studentList as $student) {
    $student->display();
}

$bestStudent = findBestStudentOOP($studentList);
echo "\nSinh viên có điểm cao nhất: " . $bestStudent->name . " (" . $bestStudent->score . " điểm)\n";

echo "Số sinh viên đạt: " . countPassedStudentsOOP($studentList) . "\n";

$avgScore = calculateAverageScoreOOP($studentList);
echo "Điểm trung bình cả lớp: " . number_format($avgScore, 2) . "\n";
?>
