import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable()
export class ActivityService {
  private apiUrl = 'http://localhost/api'; // Base API URL

  constructor(private http: HttpClient) { }

  getActivities(subjectId: number): Observable<any[]> {
    const url = `${this.apiUrl}/subject_activity.php?subject_id=${subjectId}`;
    return this.http.get<any[]>(url, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  addActivity(activityData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_activity.php`;
    return this.http.post<any>(url, activityData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  updateActivity(activityId: number, activityData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_activity.php?id=${activityId}`;
    return this.http.put<any>(url, activityData, { withCredentials: true }).pipe(
      catchError(this.handleError)
    );
  }

  deleteActivity(activityData: any): Observable<any> {
    const url = `${this.apiUrl}/subject_activity.php`;
    return this.http.request('delete', url, { 
      body: activityData, 
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
