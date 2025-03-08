import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { ExerciseService } from '../../services/exercise.service';
import { SubjectService } from '../../services/subject.service';

@Component({
    selector: 'app-exercise',
    templateUrl: './exercise.component.html',
    styleUrls: ['./exercise.component.css']
})
export class ExerciseComponent implements OnInit, OnChanges {
    @Input() subjectId: number | null = null;
    exercises: any[] = [];
    newExerciseScore: number = 0;
    newExerciseTotal: number = 0;
    showAddExerciseModal: boolean = false;
    subjectName: string = '';

    constructor(private exerciseService: ExerciseService, private subjectService: SubjectService) {}

    ngOnInit(): void {
        if (this.subjectId) {
            this.loadExercises(this.subjectId);
            this.loadSubjectName(this.subjectId);
        }
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes['subjectId'] && !changes['subjectId'].firstChange) {
            this.loadExercises(this.subjectId!);
            this.loadSubjectName(this.subjectId!);
        }
    }

    loadExercises(subjectId: number): void {
        this.exerciseService.getExercises(subjectId).subscribe(
            exercises => this.exercises = exercises,
            error => console.error('Error loading exercises:', error)
        );
    }

    loadSubjectName(subjectId: number): void {
        this.subjectService.getSubjectById(subjectId).subscribe(
            subject => this.subjectName = subject.subject_name,
            error => console.error('Error loading subject name:', error)
        );
    }

    updateExercise(exercise: any): void {
        const exerciseData = { id: exercise.id, subject_id: this.subjectId, score: exercise.score, total: exercise.total };
        this.exerciseService.updateExercise(exercise.id, [exerciseData]).subscribe(
            () => this.loadExercises(this.subjectId!),
            error => console.error('Error updating exercise:', error)
        );
    }

    deleteExercise(exerciseId: number): void {
        this.exerciseService.deleteExercise(exerciseId).subscribe(
            response => {
                console.log('Exercise deleted successfully:', response);
                if (this.subjectId) {
                    this.loadExercises(this.subjectId);
                }
            },
            error => {
                console.error('Error deleting exercise:', error);
            }
        );
    }

    openAddExerciseModal(): void {
        this.showAddExerciseModal = true;
    }

    closeAddExerciseModal(): void {
        this.showAddExerciseModal = false;
        this.newExerciseScore = 0;
        this.newExerciseTotal = 0;
    }

    addExercise(): void {
        const newExercise = [{ subject_id: this.subjectId, score: this.newExerciseScore, total: this.newExerciseTotal }];
        this.exerciseService.addExercise(newExercise).subscribe(
            () => {
                this.closeAddExerciseModal();
                this.loadExercises(this.subjectId!);
            },
            error => console.error('Error adding exercise:', error)
        );
    }
}
