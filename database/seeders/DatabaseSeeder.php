<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseSection;
use App\Models\Department;
use App\Models\Enrollment;
use App\Models\Faculty;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Semester;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Permissions Definition
        $permissions = [
            // System Administration
            ['name' => 'Manage Roles & Permissions', 'slug' => 'roles.manage', 'group' => 'Administration', 'description' => 'Create custom roles and configure permission matrices'],
            ['name' => 'View User Directory', 'slug' => 'users.view', 'group' => 'Administration', 'description' => 'View full list of students, lecturers and staff'],
            ['name' => 'Create University Accounts', 'slug' => 'users.create', 'group' => 'Administration', 'description' => 'Register new user profiles and set roles'],
            ['name' => 'Edit Users & Toggle Status', 'slug' => 'users.edit', 'group' => 'Administration', 'description' => 'Modify profile details and suspend/activate accounts'],

            // Academic Structure
            ['name' => 'Manage Faculties', 'slug' => 'faculties.manage', 'group' => 'Academic Hierarchy', 'description' => 'Create, edit, and delete academic faculties'],
            ['name' => 'Manage Departments', 'slug' => 'departments.manage', 'group' => 'Academic Hierarchy', 'description' => 'Create and configure academic departments'],
            ['name' => 'Manage Semesters', 'slug' => 'semesters.manage', 'group' => 'Academic Hierarchy', 'description' => 'Configure academic terms and years'],

            // Course Management
            ['name' => 'View Course Catalog', 'slug' => 'courses.view', 'group' => 'Courses & Curriculum', 'description' => 'Browse and view available courses'],
            ['name' => 'Create New Courses', 'slug' => 'courses.create', 'group' => 'Courses & Curriculum', 'description' => 'Add new academic courses to departments'],
            ['name' => 'Edit Courses & Instructors', 'slug' => 'courses.edit', 'group' => 'Courses & Curriculum', 'description' => 'Modify syllabus, assign faculty lecturers'],
            ['name' => 'Delete Courses', 'slug' => 'courses.delete', 'group' => 'Courses & Curriculum', 'description' => 'Remove courses from system'],
            ['name' => 'Self-Enroll in Courses', 'slug' => 'courses.enroll', 'group' => 'Courses & Curriculum', 'description' => 'Register for active courses in catalog'],

            // Classroom Delivery
            ['name' => 'Manage Curriculum & Materials', 'slug' => 'materials.manage', 'group' => 'Classroom Delivery', 'description' => 'Create module sections and upload lecture notes/slides'],
            ['name' => 'View & Download Materials', 'slug' => 'materials.view', 'group' => 'Classroom Delivery', 'description' => 'Access lectures and study resources'],
            ['name' => 'Broadcast Announcements', 'slug' => 'announcements.create', 'group' => 'Classroom Delivery', 'description' => 'Publish notices to enrolled students'],

            // Assessment & Grading
            ['name' => 'Create & Delete Assignments', 'slug' => 'assignments.create', 'group' => 'Assessment & Grading', 'description' => 'Publish assignments and project specifications'],
            ['name' => 'Submit Assignment Deliverables', 'slug' => 'assignments.submit', 'group' => 'Assessment & Grading', 'description' => 'Upload files and notes for grading'],
            ['name' => 'Evaluate & Grade Submissions', 'slug' => 'grading.manage', 'group' => 'Assessment & Grading', 'description' => 'Grade student submissions and issue feedback'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $p) {
            $createdPermissions[$p['slug']] = Permission::create($p);
        }

        // 2. Roles Definition
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full administrative access across all university faculties, courses, users, and security settings.',
            'is_system' => true,
        ]);
        // Admin gets all permissions
        $adminRole->permissions()->sync(array_column($createdPermissions, 'id'));

        $lecturerRole = Role::create([
            'name' => 'Lecturer / Faculty',
            'slug' => 'lecturer',
            'description' => 'Manages assigned course curriculum, uploads learning materials, creates assignments, and conducts grading.',
            'is_system' => true,
        ]);
        $lecturerRole->permissions()->sync([
            $createdPermissions['courses.view']->id,
            $createdPermissions['materials.manage']->id,
            $createdPermissions['materials.view']->id,
            $createdPermissions['announcements.create']->id,
            $createdPermissions['assignments.create']->id,
            $createdPermissions['grading.manage']->id,
        ]);

        $studentRole = Role::create([
            'name' => 'Student',
            'slug' => 'student',
            'description' => 'Accesses enrolled classrooms, downloads lecture slides, turns in coursework, and tracks marks.',
            'is_system' => true,
        ]);
        $studentRole->permissions()->sync([
            $createdPermissions['courses.view']->id,
            $createdPermissions['courses.enroll']->id,
            $createdPermissions['materials.view']->id,
            $createdPermissions['assignments.submit']->id,
        ]);

        $taRole = Role::create([
            'name' => 'Teaching Assistant',
            'slug' => 'teaching_assistant',
            'description' => 'Assists faculty with lecture discussion, grading support, and classroom announcements.',
            'is_system' => false,
        ]);
        $taRole->permissions()->sync([
            $createdPermissions['courses.view']->id,
            $createdPermissions['materials.view']->id,
            $createdPermissions['grading.manage']->id,
            $createdPermissions['announcements.create']->id,
        ]);

        // 3. Faculties
        $foc = Faculty::create([
            'name' => 'Faculty of Computing',
            'code' => 'FOC',
            'description' => 'Excellence in computing, software development, data science, and cyber systems.',
        ]);

        $foe = Faculty::create([
            'name' => 'Faculty of Engineering',
            'code' => 'FOE',
            'description' => 'Advancing technology through world-class electrical and mechanical engineering.',
        ]);

        // 4. Departments
        $deptSE = Department::create([
            'faculty_id' => $foc->id,
            'name' => 'Department of Software Engineering',
            'code' => 'SE',
            'description' => 'Focusing on large-scale software design, architecture, and quality assurance.',
        ]);

        $deptCS = Department::create([
            'faculty_id' => $foc->id,
            'name' => 'Department of Computer Science',
            'code' => 'CS',
            'description' => 'Algorithms, systems, and foundational computational theory.',
        ]);

        $deptAI = Department::create([
            'faculty_id' => $foc->id,
            'name' => 'Department of Data Science & AI',
            'code' => 'DSAI',
            'description' => 'Machine learning, neural networks, and big data engineering.',
        ]);

        $deptEE = Department::create([
            'faculty_id' => $foe->id,
            'name' => 'Department of Electrical & Electronic Engineering',
            'code' => 'EEE',
            'description' => 'Digital circuits, signal processing, and robotics.',
        ]);

        // 5. Semesters
        $currentSemester = Semester::create([
            'name' => 'Year 2 Semester 1',
            'academic_year' => '2026/2027',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(4)->endOfMonth(),
            'is_current' => true,
        ]);

        // 6. Users
        $password = Hash::make('password');

        // Admin
        $admin = User::create([
            'name' => 'University Registrar',
            'email' => 'admin@university.edu',
            'password' => $password,
            'role' => User::ROLE_ADMIN,
            'identifier' => 'ADM001',
            'phone' => '+1 555-0100',
            'status' => 'active',
        ]);
        $admin->roles()->attach($adminRole->id);

        // Lecturers
        $lecturer1 = User::create([
            'name' => 'Dr. Alan Smith',
            'email' => 'dr.smith@university.edu',
            'password' => $password,
            'role' => User::ROLE_LECTURER,
            'identifier' => 'FAC101',
            'phone' => '+1 555-0101',
            'department_id' => $deptSE->id,
            'status' => 'active',
        ]);
        $lecturer1->roles()->attach($lecturerRole->id);

        $lecturer2 = User::create([
            'name' => 'Prof. Sarah Jones',
            'email' => 'prof.jones@university.edu',
            'password' => $password,
            'role' => User::ROLE_LECTURER,
            'identifier' => 'FAC102',
            'phone' => '+1 555-0102',
            'department_id' => $deptCS->id,
            'status' => 'active',
        ]);
        $lecturer2->roles()->attach($lecturerRole->id);

        // Students
        $student1 = User::create([
            'name' => 'John Doe',
            'email' => 'student1@university.edu',
            'password' => $password,
            'role' => User::ROLE_STUDENT,
            'identifier' => 'STU2026001',
            'phone' => '+1 555-0201',
            'department_id' => $deptSE->id,
            'status' => 'active',
        ]);
        $student1->roles()->attach($studentRole->id);

        $student2 = User::create([
            'name' => 'Jane Miller',
            'email' => 'student2@university.edu',
            'password' => $password,
            'role' => User::ROLE_STUDENT,
            'identifier' => 'STU2026002',
            'phone' => '+1 555-0202',
            'department_id' => $deptSE->id,
            'status' => 'active',
        ]);
        $student2->roles()->attach($studentRole->id);

        $student3 = User::create([
            'name' => 'Alex Rivera',
            'email' => 'student3@university.edu',
            'password' => $password,
            'role' => User::ROLE_STUDENT,
            'identifier' => 'STU2026003',
            'phone' => '+1 555-0203',
            'department_id' => $deptCS->id,
            'status' => 'active',
        ]);
        $student3->roles()->attach($studentRole->id);

        // 7. Courses
        $course1 = Course::create([
            'department_id' => $deptSE->id,
            'instructor_id' => $lecturer1->id,
            'semester_id' => $currentSemester->id,
            'code' => 'SE204',
            'title' => 'Advanced Database Management Systems',
            'description' => 'Relational modeling, query optimization, concurrency control, and distributed storage systems.',
            'credits' => 3,
            'is_active' => true,
        ]);

        $course2 = Course::create([
            'department_id' => $deptSE->id,
            'instructor_id' => $lecturer1->id,
            'semester_id' => $currentSemester->id,
            'code' => 'SE201',
            'title' => 'Software Architecture & Design Patterns',
            'description' => 'Clean architecture, SOLID principles, design patterns (GoF), and enterprise system modeling.',
            'credits' => 4,
            'is_active' => true,
        ]);

        $course3 = Course::create([
            'department_id' => $deptCS->id,
            'instructor_id' => $lecturer2->id,
            'semester_id' => $currentSemester->id,
            'code' => 'CS305',
            'title' => 'Machine Learning & Neural Networks',
            'description' => 'Supervised and unsupervised learning, backpropagation, and deep learning architectures.',
            'credits' => 4,
            'is_active' => true,
        ]);

        $course4 = Course::create([
            'department_id' => $deptEE->id,
            'instructor_id' => null,
            'semester_id' => $currentSemester->id,
            'code' => 'EE102',
            'title' => 'Digital Logic & Circuit Design',
            'description' => 'Boolean algebra, combinational and sequential circuit design, and hardware description languages.',
            'credits' => 3,
            'is_active' => true,
        ]);

        // 8. Enrollments
        Enrollment::create([
            'course_id' => $course1->id,
            'student_id' => $student1->id,
            'enrolled_at' => now()->subDays(10),
            'status' => 'enrolled',
        ]);

        Enrollment::create([
            'course_id' => $course2->id,
            'student_id' => $student1->id,
            'enrolled_at' => now()->subDays(10),
            'status' => 'enrolled',
        ]);

        Enrollment::create([
            'course_id' => $course1->id,
            'student_id' => $student2->id,
            'enrolled_at' => now()->subDays(8),
            'status' => 'enrolled',
        ]);

        Enrollment::create([
            'course_id' => $course3->id,
            'student_id' => $student3->id,
            'enrolled_at' => now()->subDays(5),
            'status' => 'enrolled',
        ]);

        // 9. Course Sections & Materials for Course 1 (SE204)
        $section1 = CourseSection::create([
            'course_id' => $course1->id,
            'title' => 'Module 01: Relational Algebra & Normalization',
            'description' => 'First normal form through Boyce-Codd normal form with functional dependencies.',
            'order' => 1,
        ]);

        CourseMaterial::create([
            'course_section_id' => $section1->id,
            'title' => 'Lecture 01 Slides: Functional Dependencies & BCNF',
            'type' => 'document',
            'content' => 'Review chapters 3 & 4 before attending the laboratory session on Thursday.',
            'order' => 1,
        ]);

        CourseMaterial::create([
            'course_section_id' => $section1->id,
            'title' => 'Recommended Reading: Relational Schema Decomposition Guide',
            'type' => 'link',
            'external_url' => 'https://en.wikipedia.org/wiki/Database_normalization',
            'order' => 2,
        ]);

        $section2 = CourseSection::create([
            'course_id' => $course1->id,
            'title' => 'Module 02: Indexing & Query Optimization',
            'description' => 'B+ Trees, Hash Indexes, cost-based query plan analysis.',
            'order' => 2,
        ]);

        CourseMaterial::create([
            'course_section_id' => $section2->id,
            'title' => 'Lecture 02 Slides: B+ Tree Implementations in Modern RDBMS',
            'type' => 'document',
            'content' => 'Index structures, clustered vs non-clustered indexes, and query explain plans.',
            'order' => 1,
        ]);

        // 10. Announcements
        Announcement::create([
            'course_id' => $course1->id,
            'user_id' => $lecturer1->id,
            'title' => 'Welcome to SE204 & Lab Schedule Announcement',
            'content' => 'Welcome students to Advanced Database Systems. Lab sessions will take place every Thursday at Computing Lab 4. Please ensure you have MySQL and PostgreSQL clients set up on your machines.',
            'is_pinned' => true,
        ]);

        // 11. Assignments
        $assignment1 = Assignment::create([
            'course_id' => $course1->id,
            'title' => 'Assignment 1: Relational Schema Normalization & Query Tuning',
            'description' => "1. Decompose the provided healthcare database schema to 3NF and BCNF.\n2. Write SQL DDL with appropriate primary, foreign key, and check constraints.\n3. Submit a PDF report explaining your decomposition steps.",
            'max_score' => 100,
            'due_date' => now()->addDays(5),
        ]);

        $assignment2 = Assignment::create([
            'course_id' => $course1->id,
            'title' => 'Project Milestone 1: Database Architecture Proposal',
            'description' => 'Submit your team project proposal and Entity Relationship diagram for your proposed university service application.',
            'max_score' => 50,
            'due_date' => now()->addDays(14),
        ]);

        // 12. Submissions & Grading
        Submission::create([
            'assignment_id' => $assignment1->id,
            'student_id' => $student1->id,
            'comments' => 'Attached complete relational schema and BCNF proof.',
            'submitted_at' => now()->subDays(2),
            'score' => 95.0,
            'feedback' => 'Outstanding work John. Your loss-less decomposition proof and functional dependency sets were flawless.',
            'graded_by' => $lecturer1->id,
            'graded_at' => now()->subDay(),
        ]);

        Submission::create([
            'assignment_id' => $assignment1->id,
            'student_id' => $student2->id,
            'comments' => 'Please find attached my solution for assignment 1. Let me know if any questions.',
            'submitted_at' => now()->subHours(6),
            'score' => null,
            'feedback' => null,
            'graded_by' => null,
            'graded_at' => null,
        ]);
    }
}
