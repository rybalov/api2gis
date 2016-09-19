import { Category } from './category';
import { Injectable } from '@angular/core';
import { Http, Response, Headers, URLSearchParams } from '@angular/http';
import { Observable } from 'rxjs/Rx';
import 'rxjs/add/operator/map';


@Injectable()
export class CategoryService {
    private baseUrl: string = '/api/search/categories';

    constructor(private http : Http){}

    find(name: string): Observable<Category[]> {
        let params = new URLSearchParams();

        if (name.length) {
            params.set('type', 'name');
            params.set('name', name);
        } else {
            params.set('type', 'all');
        }

        return this.http
            .get(this.baseUrl, {headers: CategoryService.getHeaders(), search: params})
            .map(CategoryService.mapCategories);
    }

    private static getHeaders() {
        let headers = new Headers();
        headers.append('Accept', 'application/json');

        return headers;
    }

    private static mapCategories(response:Response): Category[]{
        let results = response.json().result.map(CategoryService.toCategory);

        return results.length? results: [];
    }

    private static toCategory(r: any): Category{
        return <Category>({
            id:         r.id,
            name:       r.name,
            level:      r.lvl
        });
    }
}
