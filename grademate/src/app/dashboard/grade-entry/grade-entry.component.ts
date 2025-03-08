import { Component, OnInit, ViewChild } from '@angular/core';
import { SubjectService } from '../../services/subject.service';
import { ActivityComponent } from '../activity/activity.component';
import { ExerciseComponent } from '../exercise/exercise.component';
import { QuizComponent } from '../quiz/quiz.component';
import { ExamComponent } from '../exam/exam.component';
import { ProjectComponent } from '../project/project.component';

@Component({
  selector: 'app-grade-entry',
  templateUrl: './grade-entry.component.html',
  styleUrls: ['./grade-entry.component.css']
})
export class GradeEntryComponent implements OnInit {
  subjects: any[] = [];
  selectedSubject!: number;
  selectedTable: string = '';
  assessmentTypes = [
    { value: 'activity', label: 'Activity' },
    { value: 'exercise', label: 'Exercise' },
    { value: 'quiz', label: 'Quiz' },
    { value: 'exam', label: 'Exam' },
    { value: 'project', label: 'Project' }
  ];

  @ViewChild(ActivityComponent) activityComponent!: ActivityComponent;
  @ViewChild(ExerciseComponent) exerciseComponent!: ExerciseComponent;
  @ViewChild(QuizComponent) quizComponent!: QuizComponent;
  @ViewChild(ExamComponent) examComponent!: ExamComponent;
  @ViewChild(ProjectComponent) projectComponent!: ProjectComponent;

  constructor(private subjectService: SubjectService) {}

  ngOnInit(): void {
    this.loadSubjects();
  }

  loadSubjects(): void {
    this.subjectService.getSubjects().subscribe(
      data => {
        console.log('Subjects loaded:', data);
        this.subjects = data;
      },
      error => {
        console.error('Error loading subjects:', error);
      }
    );
  }

  onSubjectChange(event: any): void {
    console.log('Selected subject:', event.value);
    this.selectedSubject = event.value;
    this.reloadActivities();
  }

  onTableChange(event: any): void {
    console.log('Selected assessment type:', event.value);
    this.selectedTable = event.value;
    this.reloadActivities();
  }

  reloadActivities(): void {
    switch (this.selectedTable) {
      case 'activity':
        if (this.activityComponent) {
          this.activityComponent.loadActivities(this.selectedSubject);
        }
        break;
      case 'exercise':
        if (this.exerciseComponent) {
          this.exerciseComponent.loadExercises(this.selectedSubject);
        }
        break;
      case 'quiz':
        if (this.quizComponent) {
          this.quizComponent.loadQuizzes(this.selectedSubject);
        }
        break;
      case 'exam':
        if (this.examComponent) {
          this.examComponent.loadExams(this.selectedSubject);
        }
        break;
      case 'project':
        if (this.projectComponent) {
          this.projectComponent.loadProjects(this.selectedSubject);
        }
        break;
    }
  }
}
