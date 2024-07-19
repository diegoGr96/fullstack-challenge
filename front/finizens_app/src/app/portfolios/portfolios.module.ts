import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PortfolioListComponent } from './components/portfolio-list/portfolio-list.component';
import { AppRoutingModule } from '../app-routing.module';
import { PortfolioComponent } from './pages/portfolio/portfolio.component';

@NgModule({
  declarations: [
    PortfolioListComponent,
    PortfolioComponent,
  ],
  imports: [
    CommonModule, 
    AppRoutingModule,
  ],
  exports: [
    PortfolioListComponent,
  ],
})
export class PortfoliosModule {}
