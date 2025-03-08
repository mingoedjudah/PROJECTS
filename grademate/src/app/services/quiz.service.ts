import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class QuizService {
  private apiUrl = 'http://localhost/api'; // Base API URL

  constructor(private http: HttpClient) { }

  getQuizzes(subjectId: number): Observable<any[]> {
    const url = `${this.apiUrl}/subject_quiz.php?subject_id=${subjectId}`;
    return this.http.get<any[]>(url, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  addQuiz(quizData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_quiz.php`;
    return this.http.post<any>(url, quizData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  updateQuiz(quizId: number, quizData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_quiz.php?id=${quizId}`;
    return this.http.put<any>(url, quizData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  deleteQuiz(quizData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_quiz.php`;
    return this.http.request('delete', url, { 
      body: quizData, 
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
