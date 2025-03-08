import { Injectable } from '@angular/core';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';

@Injectable()
export class SubjectService {
  private baseUrl = 'http://localhost/api/subject.php';

  constructor(private http: HttpClient) { }

  getSubjects(): Observable<any> {
    return this.http.get(this.baseUrl, { withCredentials: true })
      .pipe(
        catchError((error: any) => {
          console.error('Error fetching subjects:', error);
          return throwError(error);
        })
      );
  }

  getSubjectById(id: number): Observable<any> {
    const url = `${this.baseUrl}?id=${id}`;
    return this.http.get(url, { withCredentials: true })
      .pipe(
        catchError((error: any) => {
          console.error('Error fetching subject:', error);
          return throwError(error);
        })
      );
  }

  addSubject(subjectData: any): Observable<any> {
    return this.http.post<any>(this.baseUrl, subjectData, { withCredentials: true })
      .pipe(
        catchError(this.handleError)
      );
  }

  updateSubject(subjectId: number, payload: any): Observable<any> {
    const url = `${this.baseUrl}?id=${subjectId}`;
    const headers = new HttpHeaders({
      'Content-Type': 'application/json'
    });

    return this.http.put(url, payload, { headers, withCredentials: true }).pipe(
      catchError((error: HttpErrorResponse) => {
        console.error('Error updating subject:', error);
        return throwError(error);
      })
    );
  }

  deleteSubject(id: number): Observable<any> {
    const url = `${this.baseUrl}`;
    const body = { id };
    return this.http.request('delete', url, { body, withCredentials: true })
      .pipe(
        catchError((error: any) => {
          console.error('Error deleting subject:', error);
          return throwError(error);
        })
      );
  }

  private handleError(error: HttpErrorResponse) {
    if (error.error instanceof ErrorEvent) {
      // Client-side error
      console.error('An error occurred:', error.error.message);
    } else {
      // Server-side error
      console.error(
        `Backend returned code ${error.status}, ` +
        `body was: ${error.error}`
      );
    }
    // Return an observable with a user-facing error message
    return throwError('Something bad happened; please try again later.');
  }
}
