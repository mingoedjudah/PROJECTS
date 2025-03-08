import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ExamService {
  private apiUrl = 'http://localhost/api'; // Base API URL

  constructor(private http: HttpClient) { }

  getExams(subjectId: number): Observable<any[]> {
    const url = `${this.apiUrl}/subject_exam.php?subject_id=${subjectId}`;
    return this.http.get<any[]>(url, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  addExam(examData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exam.php`;
    return this.http.post<any>(url, examData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  updateExam(examId: number, examData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exam.php?id=${examId}`;
    return this.http.put<any>(url, examData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  deleteExam(examData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_exam.php`;
    return this.http.request('delete', url, { 
      body: examData, 
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
