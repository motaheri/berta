import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { take, map, catchError } from 'rxjs/operators';
import { Store } from '@ngxs/store';
import { AppState } from 'src/app/app-state/app.state';
import { AppShowLoading, AppHideLoading } from '../../app-state/app.actions';


@Injectable({
  providedIn: 'root'
})
export class FileUploadService {

  constructor(
    private store: Store,
    private http: HttpClient) {
  }

  upload(property, file) {
    const currentSite = this.store.selectSnapshot(AppState.getSite);
    const url = '/engine/upload.php?property=' + property + (currentSite ? '&site=' + currentSite : '');
    const formData = new FormData();
    formData.append('Filedata', file);

    this.store.dispatch(new AppShowLoading());

    return this.http.post<{filename: string}>(url, formData).pipe(
      take(1),
      map((response) => {
        this.store.dispatch(new AppHideLoading());
        return response;
      }),
      catchError(error => {
        this.store.dispatch(new AppHideLoading());
        throw error;
      })
    );
  }
}
