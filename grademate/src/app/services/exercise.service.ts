import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ExerciseService {
  private apiUrl = 'http://localhost/api'; // Base API URL

  constructor(private http: HttpClient) { }

  getExercises(subjectId: number): Observable<any[]> {
    const url = `${this.apiUrl}/subject_exercise.php?subject_id=${subjectId}`;
    return this.http.get<any[]>(url, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  addExercise(exerciseData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exercise.php`;
    return this.http.post<any>(url, exerciseData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  updateExercise(exerciseId: number, exerciseData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exercise.php?id=${exerciseId}`;
    return this.http.put<any>(url, exerciseData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  deleteExercise(exerciseData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exercise.php`;
    return this.http.request('delete', url, { 
      body: exerciseData, 
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
