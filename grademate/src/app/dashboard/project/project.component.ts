import { Component, OnInit, Input, OnChanges, SimpleChanges } from '@angular/core';
import { ProjectService } from '../../services/project.service';
import { SubjectService } from '../../services/subject.service';

@Component({
    selector: 'app-project',
    templateUrl: './project.component.html',
    styleUrls: ['./project.component.css']
})
export class ProjectComponent implements OnInit, OnChanges {
    @Input() subjectId: number | null = null;
    projects: any[] = [];
    newProjectScore: number = 0;
    newProjectTotal: number = 0;
    showAddProjectModal: boolean = false;
    subjectName: string = '';

    constructor(private projectService: ProjectService, private subjectService: SubjectService) {}

    ngOnInit(): void {
        if (this.subjectId) {
            this.loadProjects(this.subjectId);
            this.loadSubjectName(this.subjectId);
        }
    }

    ngOnChanges(changes: SimpleChanges): void {
        if (changes['subjectId'] && !changes['subjectId'].firstChange) {
            this.loadProjects(this.subjectId!);
            this.loadSubjectName(this.subjectId!);
        }
    }

    loadProjects(subjectId: number): void {
        this.projectService.getProjects(subjectId).subscribe(
            projects => this.projects = projects,
            error => console.error('Error loading projects:', error)
        );
    }

    loadSubjectName(subjectId: number): void {
        this.subjectService.getSubjectById(subjectId).subscribe(
            subject => this.subjectName = subject.subject_name,
            error => console.error('Error loading subject name:', error)
        );
    }

    updateProject(project: any): void {
        const projectData = { id: project.id, subject_id: this.subjectId, score: project.score, total: project.total };
        this.projectService.updateProject(project.id, [projectData]).subscribe(
            () => this.loadProjects(this.subjectId!),
            error => console.error('Error updating project:', error)
        );
    }

    deleteProject(projectId: number): void {
        this.projectService.deleteProject(projectId).subscribe(
            response => {
                console.log('Project deleted successfully:', response);
                if (this.subjectId) {
                    this.loadProjects(this.subjectId);
                }
            },
            error => {
                console.error('Error deleting project:', error);
            }
        );
    }

    openAddProjectModal(): void {
        this.showAddProjectModal = true;
    }

    closeAddProjectModal(): void {
        this.showAddProjectModal = false;
        this.newProjectScore = 0;
        this.newProjectTotal = 0;
    }

    addProject(): void {
        const newProject = [{ subject_id: this.subjectId, score: this.newProjectScore, total: this.newProjectTotal }];
        this.projectService.addProject(newProject).subscribe(
            () => {
                this.closeAddProjectModal();
                this.loadProjects(this.subjectId!);
            },
            error => console.error('Error adding project:', error)
        );
    }
}
