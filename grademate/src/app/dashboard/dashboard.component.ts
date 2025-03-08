import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { SignupService } from '../services/signup.service';
import { StudentService } from '../services/student.service';
import { SubjectService } from '../services/subject.service';
import { MatSnackBar } from '@angular/material/snack-bar';

interface Subject {
  id: number;
  subject_name: string;
  activity_weight: number;
  quiz_weight: number;
  exercise_weight: number;
  exam_weight: number;
  project_weight: number;
}

interface AssessmentWeights {
  activity_weight: number;
  quiz_weight: number;
  exercise_weight: number;
  exam_weight: number;
  project_weight: number;
}

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {
  selectedSubjects: boolean[] = [];
  selectedCourses: boolean[] = [];
  selectedSubMenu: string | null = '';
  selectedCourse = '';
  courses: string[] = [];
  selectedMenu = 'courses';
  showSuccessMessageQuiz = false;
  showSuccessMessageActivity = false;
  showSuccessMessageExam = false;
  showSuccessMessageProject = false;
  selectedSubjectId: number | null = null;
  assessmentWeights: AssessmentWeights | null = null;
  subjects: Subject[] = [];

  subjectData = {
    subject_name: '',
    quiz_weight: 0,
    activity_weight: 0,
    exam_weight: 0,
    project_weight: 0,
    exercise_weight: 0
  };

  newSubjectName: string = '';
  selectMode = false;
  showAddModal = false;
  showEditModal = false;
  selectedSubject: Subject = { 
    id: 0, 
    subject_name: '', 
    quiz_weight: 0, 
    activity_weight: 0, 
    exam_weight: 0, 
    project_weight: 0, 
    exercise_weight: 0 
  };

  //account
  profile = {
    image: '',
    profilePic: './assets/default.jpg',
    name: '',
    username: '',
    email: '',
    school: '',
    gender: '',
    birthday: new Date(1990, 1, 1),
    age: 30
  };
  showAvatarModal = false;

  studentData: any;
  newStudentData: any = {
    first_name: '',
    middle_name: '',
    surname: '',
    email: '',
    birthdate: '',
    gender: '',
    password: '',
    university: '',
    academic_level: '',
    username: ''
  };

  newCourseName: string = '';
  editCourseName: string = '';
  isModalOpen: boolean = false;
  isEditModalOpen: boolean = false;
  isDeleteModalOpen: boolean = false;
  courseToEditIndex: number | null = null;
  courseToDeleteIndex: number | null = null;
  isDeleteSelectedModalOpen = false;
  isLogoutModalOpen: boolean = false;
  dropdowns: Record<string, boolean> = {
    quizzes: false,
    activities: false,
    exams: false,
    projects: false
  };

  loggedInUsername = '';
  logout: any;

  constructor(
    private signupService: SignupService,
    private router: Router,
    private studentService: StudentService,
    private subjectService: SubjectService,
    private snackBar: MatSnackBar
  ) {}

  ngOnInit(): void {
    this.getStudent();
    this.loadSubjects();
  }

  getStudent() {
    this.studentService.getStudent().subscribe({
      next: data => {
        this.studentData = data;
        this.profile = {
          image: '',
          profilePic: data.profilePic || './assets/default.jpg',
          name: `${data.first_name} ${data.middle_name} ${data.surname}`,
          username: data.username,
          email: data.email,
          school: data.university || 'Unknown University',
          gender: data.gender,
          birthday: new Date(data.birthdate),
          age: this.calculateAge(new Date(data.birthdate))
        };
      },
      error: error => {
        console.error('Error fetching student data:', error);
      }
    });
  }

  calculateAge(birthday: Date): number {
    const ageDifMs = Date.now() - birthday.getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
  }

  createStudent() {
    this.studentService.createStudent(this.newStudentData).subscribe({
      next: data => {
        console.log('Student created:', data);
        this.getStudent(); // Refresh student data
      },
      error: error => {
        console.error('Error creating student:', error);
      }
    });
  }

  updateStudent() {
    this.studentService.updateStudent(this.studentData).subscribe({
      next: data => {
        console.log('Student updated:', data);
        this.getStudent(); // Refresh student data
      },
      error: error => {
        console.error('Error updating student:', error);
      }
    });
  }

  deleteStudent() {
    const studentId = this.studentData.id;
    this.studentService.deleteStudent(studentId).subscribe({
      next: data => {
        console.log('Student deleted:', data);
        this.studentData = null; // Clear student data
      },
      error: error => {
        console.error('Error deleting student:', error);
      }
    });
  }

  loadAssessments() {
    if (this.selectedSubjectId) {
      this.subjectService.getSubjectById(this.selectedSubjectId).subscribe(
        (subject) => {
          this.assessmentWeights = {
            activity_weight: subject.activity_weight,
            quiz_weight: subject.quiz_weight,
            exercise_weight: subject.exercise_weight,
            exam_weight: subject.exam_weight,
            project_weight: subject.project_weight
          };
        },
        (error) => {
          console.error('Error loading assessments:', error);
          this.assessmentWeights = null;  // Ensure assessmentWeights is null in case of error
        }
      );
    } else {
      this.assessmentWeights = null;  // Reset assessmentWeights if no subject is selected
    }
  }

  saveAssessmentWeights() {
    if (this.selectedSubjectId && this.assessmentWeights) {
      const totalWeight = this.assessmentWeights.activity_weight +
                          this.assessmentWeights.quiz_weight +
                          this.assessmentWeights.exercise_weight +
                          this.assessmentWeights.exam_weight +
                          this.assessmentWeights.project_weight;
      if (totalWeight > 100) {
        this.snackBar.open('Total weight of all assessments should be less or equal to 100', 'Close', { duration: 3000 });
        return;
      }

      const payload = {
        id: this.selectedSubjectId,
        ...this.assessmentWeights
      };
  
      this.subjectService.updateSubject(this.selectedSubjectId, payload).subscribe(
        () => {
          this.snackBar.open('Assessment weights updated successfully', 'Close', { duration: 3000 });
          this.loadSubjects();
        },
        (error) => {
          console.error('Error updating assessment weights:', error);
          this.snackBar.open('Weight should be less than or equal to 100.', 'Close', { duration: 3000 });
        }
      );
    }
  }

  deleteSelected() {
    for (let i = this.selectedSubjects.length - 1; i >= 0; i--) {
      if (this.selectedSubjects[i]) {
        const subjectId = this.subjects[i].id;
        this.subjectService.deleteSubject(subjectId).subscribe(
          () => {
            this.snackBar.open('Subject deleted successfully', 'Close', { duration: 3000 });
            this.loadSubjects();
          },
          error => {
            console.error('Error deleting subject:', error);
            this.snackBar.open('Something went wrong. Please try again later.', 'Close', { duration: 3000 });
          }
        );
      }
    }
    this.selectedSubjects = [];
  }

  addSubject() {
    this.subjectData.subject_name = this.newSubjectName;
    this.subjectService.addSubject(this.subjectData).subscribe(
      response => {
        this.snackBar.open('Subject added successfully', 'Close', { duration: 3000 });
  
        // Reload subjects and manage the selectedSubjects array
        this.loadSubjects();
  
        // Close the add modal
        this.closeAddModal();
      },
      error => {
        console.error('Error adding subject:', error);
        this.snackBar.open('Something went wrong. Please try again later.', 'Close', { duration: 3000 });
      }
    );
  }
  
  loadSubjects() {
    this.subjectService.getSubjects().subscribe(
      subjects => {
        this.subjects = subjects;
        // Initialize selectedSubjects array with false for each subject
        this.selectedSubjects = new Array(this.subjects.length).fill(false);
      },
      error => {
        console.error('Error loading subjects:', error);
      }
    );
  }
  

  saveEditedSubject() {
    if (this.selectedSubject && this.selectedSubject.subject_name.trim()) {
      const totalWeight = this.selectedSubject.quiz_weight +
                          this.selectedSubject.activity_weight +
                          this.selectedSubject.exam_weight +
                          this.selectedSubject.project_weight +
                          this.selectedSubject.exercise_weight;
      if (totalWeight > 100) {
        this.snackBar.open('Total weight of all assessments should be less or equal to 100', 'Close', { duration: 3000 });
        return;
      }

      const updatedSubject = {
        id: this.selectedSubject.id,
        subject_name: this.selectedSubject.subject_name,
        quiz_weight: this.selectedSubject.quiz_weight,
        activity_weight: this.selectedSubject.activity_weight,
        exam_weight: this.selectedSubject.exam_weight,
        project_weight: this.selectedSubject.project_weight,
        exercise_weight: this.selectedSubject.exercise_weight,
      };
      this.subjectService.updateSubject(this.selectedSubject.id, updatedSubject).subscribe(
        response => {
          this.snackBar.open('Subject updated successfully', 'Close', { duration: 3000 });
          this.loadSubjects();
          this.closeEditModal();
        },
        error => {
          console.error('Error updating subject:', error);
          this.snackBar.open('Something went wrong. Please try again later', 'Close', { duration: 3000 });
        }
      );
    }
  }

  //Courses
  openEditModalSubject(subject: Subject) {
    this.selectedSubject = { ...subject };
    this.showEditModal = true;
  }

  openAddModal() {
    this.newSubjectName = '';
    this.showAddModal = true;
  }

  closeAddModal() {
    this.showAddModal = false;
  }

  toggleSelection() {
    this.selectMode = !this.selectMode;
  }

  toggleAllSelection(event: any) {
    const checked = event.target.checked; // Safely access checked property
    this.selectedSubjects.fill(checked);
  }

  // Assessments
  quizzes: any[] = [];
  newAssessmentName: string = '';
  newAssessmentGrade: number | null = null;
  newAssessmentWeight: number | null = null;
  currentAssessmentType: string = '';
  isAssessmentModalOpen: boolean = false;
  assessmentToEditIndex: number | null = null;
  assessmentToDeleteIndex: number | null = null;
  
  selectSubMenu(subMenu: string): void {
    this.selectedSubMenu = subMenu;
  }

  toggleDropdown(menu: string): void {
    this.dropdowns[menu] = !this.dropdowns[menu];
  }

  // Courses
  addCourse(): void {
    if (this.newCourseName) {
      this.courses.push(this.newCourseName);
      this.newCourseName = '';
      this.selectedCourses.push(false);
      this.closeModal();
    }
  }

  openModal(): void {
    this.isModalOpen = true;
  }

  closeModal(): void {
    this.isModalOpen = false;
  }

  openEditModal(subject: Subject) {
    this.selectedSubject = { ...subject };
    this.showEditModal = true;
  }

  closeEditModal() {
    this.showEditModal = false;
  }

  updateCourse(): void {
    if (this.editCourseName && this.courseToEditIndex !== null) {
      this.courses[this.courseToEditIndex] = this.editCourseName;
      this.editCourseName = '';
      this.courseToEditIndex = null;
      this.closeEditModal();
    }
  }

  openDeleteModal(index: number): void {
    this.courseToDeleteIndex = index;
    this.isDeleteModalOpen = true;
  }

  closeDeleteModal(): void {
    this.isDeleteModalOpen = false;
    this.courseToDeleteIndex = null;
  }

  confirmDelete(): void {
    if (this.courseToDeleteIndex !== null) {
      this.courses.splice(this.courseToDeleteIndex, 1);
      this.selectedCourses.splice(this.courseToDeleteIndex, 1);
      this.courseToDeleteIndex = null;
      this.closeDeleteModal();
    }
  }

  openDeleteSelectedModal(): void {
    this.isDeleteSelectedModalOpen = true;
  }

  closeDeleteSelectedModal(): void {
    this.isDeleteSelectedModalOpen = false;
  }

  confirmDeleteSelected(): void {
    const indicesToDelete: number[] = this.selectedCourses
      .map((selected, index) => (selected ? index : -1))
      .filter(index => index !== -1)
      .sort((a, b) => b - a);

    indicesToDelete.forEach(index => {
      this.courses.splice(index, 1);
      this.selectedCourses.splice(index, 1);
    });

    this.closeDeleteSelectedModal();
  }

  toggleSelectAll(event: Event): void {
    const checked = (event.target as HTMLInputElement).checked;
    this.selectedCourses = this.selectedCourses.map(() => checked);
  }

  anyCourseSelected(): boolean {
    return this.selectedCourses.some(selected => selected);
  }

  checkSelectedCourses(): void {
    if (this.selectedCourses.every(selected => !selected)) {
      const selectAllCheckbox = document.querySelector('input[type="checkbox"]') as HTMLInputElement;
      if (selectAllCheckbox) {
        selectAllCheckbox.checked = false;
      }
    }
  }

  checkSubjectSelection(): void {
    if (!this.selectedCourse) {
      alert('Please select a subject before proceeding.');
    }
  }

  navigateCell(event: KeyboardEvent): void {
    const target = event.target as HTMLElement;
    const cell = target.closest('td');
    if (!cell) return;

    const row = cell.closest('tr');
    if (!row) return;

    const table = row.closest('table');
    if (!table) return;

    let cellIndex = Array.from(row.children).indexOf(cell);
    let newRow: HTMLElement | null = null;
    let newCell: HTMLElement | null = null;

    switch (event.key) {
        case 'ArrowUp':
            newRow = row.previousElementSibling as HTMLElement;
            if (newRow) {
                newCell = newRow.children[cellIndex] as HTMLElement;
            }
            break;
        case 'ArrowDown':
            newRow = row.nextElementSibling as HTMLElement;
            if (newRow) {
                newCell = newRow.children[cellIndex] as HTMLElement;
            }
            break;
        case 'ArrowLeft':
            if (cellIndex > 0) {
                newCell = row.children[cellIndex - 1] as HTMLElement;
            }
            break;
        case 'ArrowRight':
            if (cellIndex < row.children.length - 1) {
                newCell = row.children[cellIndex + 1] as HTMLElement;
            }
            break;
    }

    if (newCell) {
        newCell.focus();
        event.preventDefault();
    }
  }

  isDarkMode: boolean = false;

  toggleDarkMode() {
    this.isDarkMode = !this.isDarkMode;
    document.body.classList.toggle('dark-mode', this.isDarkMode);
    const elements = document.querySelectorAll('.header, .sidebar, .content, .table-container, table, th, td, .card, .menu-item, button, .modal-content');
    elements.forEach((element) => {
      element.classList.toggle('dark-mode', this.isDarkMode);
    });
  }

  trackByFn(index: number): number {
    return index;
  }

  selectMenu(menu: string) {
    this.selectedMenu = menu;
    if (menu === 'logout') {
      this.openLogoutModal();
    }
  }

  openLogoutModal(): void {
    this.isLogoutModalOpen = true;
  }

  closeLogoutModal(): void {
    this.isLogoutModalOpen = false;
  }

  confirmLogout(): void {
    this.router.navigate(['/login']);
  }

  triggerFileInput() {
    const fileInput = document.getElementById('uploadImage') as HTMLInputElement;
    fileInput.click();
  }

  onImageSelected(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = (e: any) => {
        this.profile.profilePic = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  saveAssessments(type: string) {
    // Your logic to save assessments
    switch(type) {
      case 'Quiz':
        this.showSuccessMessageQuiz = true;
        setTimeout(() => this.showSuccessMessageQuiz = false, 3000);
        break;
      case 'Activity':
        this.showSuccessMessageActivity = true;
        setTimeout(() => this.showSuccessMessageActivity = false, 3000);
        break;
      case 'Exam':
        this.showSuccessMessageExam = true;
        setTimeout(() => this.showSuccessMessageExam = false, 3000);
        break;
      case 'Project':
        this.showSuccessMessageProject = true;
        setTimeout(() => this.showSuccessMessageProject = false, 3000);
        break;
    }
  }
  
  openAvatarModal(): void {
    this.showAvatarModal = true;
  }

  closeAvatarModal(): void {
    this.showAvatarModal = false;
  }

  selectAvatar(avatarUrl: string): void {
    this.profile.profilePic = avatarUrl;
    this.closeAvatarModal();
  }

removeImage() {
  this.profile.profilePic = ''; 
}
}
