import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { QuizService } from '../../services/quiz.service';
import { SubjectService } from '../../services/subject.service';

@Component({
    selector: 'app-quiz',
    templateUrl: './quiz.component.html',
    styleUrls: ['./quiz.component.css']
})
export class QuizComponent implements OnInit, OnChanges {
    @Input() subjectId: number | null = null;
    quizzes: any[] = [];
    newQuizScore: number = 0;
    newQuizTotal: number = 0;
    showAddQuizModal: boolean = false;
    subjectName: string = '';

    constructor(private quizService: QuizService, private subjectService: SubjectService) {}

    ngOnInit(): void {
        if (this.subjectId) {
            this.loadQuizzes(this.subjectId);
            this.loadSubjectName(this.subjectId);
        }
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes['subjectId'] && !changes['subjectId'].firstChange) {
            this.loadQuizzes(this.subjectId!);
            this.loadSubjectName(this.subjectId!);
        }
    }

    loadQuizzes(subjectId: number): void {
        this.quizService.getQuizzes(subjectId).subscribe(
            quizzes => this.quizzes = quizzes,
            error => console.error('Error loading quizzes:', error)
        );
    }

    loadSubjectName(subjectId: number): void {
        this.subjectService.getSubjectById(subjectId).subscribe(
            subject => this.subjectName = subject.subject_name,
            error => console.error('Error loading subject name:', error)
        );
    }

    updateQuiz(quiz: any): void {
        const quizData = { id: quiz.id, subject_id: this.subjectId, score: quiz.score, total: quiz.total };
        this.quizService.updateQuiz(quiz.id, [quizData]).subscribe(
            () => this.loadQuizzes(this.subjectId!),
            error => console.error('Error updating quiz:', error)
        );
    }

    deleteQuiz(quizId: number): void {
        this.quizService.deleteQuiz(quizId).subscribe(
            response => {
                console.log('Quiz deleted successfully:', response);
                if (this.subjectId) {
                    this.loadQuizzes(this.subjectId);
                }
            },
            error => {
                console.error('Error deleting quiz:', error);
            }
        );
    }

    openAddQuizModal(): void {
        this.showAddQuizModal = true;
    }

    closeAddQuizModal(): void {
        this.showAddQuizModal = false;
        this.newQuizScore = 0;
        this.newQuizTotal = 0;
    }

    addQuiz(): void {
        const newQuiz = [{ subject_id: this.subjectId, score: this.newQuizScore, total: this.newQuizTotal }];
        this.quizService.addQuiz(newQuiz).subscribe(
            () => {
                this.closeAddQuizModal();
                this.loadQuizzes(this.subjectId!);
            },
            error => console.error('Error adding quiz:', error)
        );
    }
}
