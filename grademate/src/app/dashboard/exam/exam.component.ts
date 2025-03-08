import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { ExamService } from '../../services/exam.service';
import { SubjectService } from '../../services/subject.service';

@Component({
    selector: 'app-exam',
    templateUrl: './exam.component.html',
    styleUrls: ['./exam.component.css']
})
export class ExamComponent implements OnInit, OnChanges {
    @Input() subjectId: number | null = null;
    exams: any[] = [];
    newExamScore: number = 0;
    newExamTotal: number = 0;
    showAddExamModal: boolean = false;
    subjectName: string = '';

    constructor(private examService: ExamService, private subjectService: SubjectService) {}

    ngOnInit(): void {
        if (this.subjectId) {
            this.loadExams(this.subjectId);
            this.loadSubjectName(this.subjectId);
        }
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes['subjectId'] && !changes['subjectId'].firstChange) {
            this.loadExams(this.subjectId!);
            this.loadSubjectName(this.subjectId!);
        }
    }

    loadExams(subjectId: number): void {
        this.examService.getExams(subjectId).subscribe(
            exams => this.exams = exams,
            error => console.error('Error loading exams:', error)
        );
    }

    loadSubjectName(subjectId: number): void {
        this.subjectService.getSubjectById(subjectId).subscribe(
            subject => this.subjectName = subject.subject_name,
            error => console.error('Error loading subject name:', error)
        );
    }

    updateExam(exam: any): void {
        const examData = { id: exam.id, subject_id: this.subjectId, score: exam.score, total: exam.total };
        this.examService.updateExam(exam.id, [examData]).subscribe(
            () => this.loadExams(this.subjectId!),
            error => console.error('Error updating exam:', error)
        );
    }

    deleteExam(examId: number): void {
        this.examService.deleteExam(examId).subscribe(
            response => {
                console.log('Exam deleted successfully:', response);
                if (this.subjectId) {
                    this.loadExams(this.subjectId);
                }
            },
            error => {
                console.error('Error deleting exam:', error);
            }
        );
    }

    openAddExamModal(): void {
        this.showAddExamModal = true;
    }

    closeAddExamModal(): void {
        this.showAddExamModal = false;
        this.newExamScore = 0;
        this.newExamTotal = 0;
    }

    addExam(): void {
        const newExam = [{ subject_id: this.subjectId, score: this.newExamScore, total: this.newExamTotal }];
        this.examService.addExam(newExam).subscribe(
            () => {
                this.closeAddExamModal();
                this.loadExams(this.subjectId!);
            },
            error => console.error('Error adding exam:', error)
        );
    }
}
