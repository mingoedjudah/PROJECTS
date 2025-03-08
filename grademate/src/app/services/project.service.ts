import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ProjectService {
  private apiUrl = 'http://localhost/api'; // Base API URL

  constructor(private http: HttpClient) { }

  getProjects(subjectId: number): Observable<any[]> {
    const url = `${this.apiUrl}/subject_project.php?subject_id=${subjectId}`;
    return this.http.get<any[]>(url, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  addProject(projectData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_project.php`;
    return this.http.post<any>(url, projectData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  updateProject(projectId: number, projectData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_project.php?id=${projectId}`;
    return this.http.put<any>(url, projectData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  deleteProject(projectData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_project.php`;
    return this.http.request('delete', url, { 
      body: projectData, 
      withCredentials: true,
      headers: new HttpHeaders({ 'Content-Type': 'application/json' }) // Ensure the correct content type
    }).pipe(
      catchError(this.handleError)
    );
  }

  private handleError(error: any) {
    console.error('An error occurred:', error);
    return throwError('Something bad happened; please try again later.');
  }
}
