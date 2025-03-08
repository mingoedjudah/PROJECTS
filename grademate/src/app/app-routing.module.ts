import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { AboutUsComponent } from './about-us/about-us.component';
import { FaqsComponent } from './faqs/faqs.component';
import { LoginComponent } from './login/login.component';
import { SignupComponent } from './signup/signup.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { SignupLandingComponent } from './signup-landing/signup-landing.component';
import { ActivityComponent } from './dashboard/activity/activity.component';
import { GradeEntryComponent } from './dashboard/grade-entry/grade-entry.component';
import { QuizComponent } from './dashboard/quiz/quiz.component';
import { ProjectComponent } from './dashboard/project/project.component';
import { ExamComponent } from './dashboard/exam/exam.component';
import { ExerciseComponent } from './dashboard/exercise/exercise.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'signup', component: SignupComponent },
  { path: '', component: HomeComponent },
  { path: 'about-us', component: AboutUsComponent },
  { path: 'faqs', component: FaqsComponent },
  { path: 'signup-landing', component: SignupLandingComponent },
  { path: 'dashboard', component: DashboardComponent },
  { path: 'activity', component: ActivityComponent },
  { path: 'grade-entry', component: GradeEntryComponent },
  { path: 'quiz', component: QuizComponent },
  { path: 'exam', component: ExamComponent },
  { path: 'project', component: ProjectComponent },
  { path: 'exercise', component: ExerciseComponent },
  { path: '', redirectTo: '/login', pathMatch: 'full' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
